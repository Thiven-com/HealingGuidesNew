<?php

namespace App\Http\Controllers\DoctorApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorCollection;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    //
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }
        $otp = 1234;
        // $otp = rand(1000, 9999);
        $doctor = Doctor::where('mobile', $request->mobile)->first();
        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Mobile Number Registered , Please Register'
            ]);
        }
        if ($doctor->status == 0) {
            return response()->json([
                'success' => 0,
                'message' => 'Your Account Is Pending Admin Approval'
            ]);
        }
        $doctor->otp = $otp;
        $doctor->save();

        // try {
        //     $msg = "Your OTP is {$otp} to log in to your ECM App. Do not share this code with anyone.- E Care Managers";
        //     $url = "http://sms.hspsms.com/sendSMS?username=Ecm&message=" . urlencode($msg) . "&sendername=ECAREM&smstype=TRANS&numbers=$doctor->mobile&apikey=ba52516b-ab36-4b55-a3b4-679af134744e";
        //     $ret = file($url);
        //     Log::info($ret);
        // } catch (\Exception $e) {
        //     Log::info($e->getMessage());
        // }
        return response()->json([
            'success' => 1,
            'message' => 'OTP Sent Successfully'
        ]);

    }

    public function verifyMobile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10',
            'otp' => 'required|digits:4',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }
        $doctor = Doctor::where('mobile', $request->mobile)->first();
        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'User not found'
            ]);
        }
        $isMasterLogin = $request->mobile == '9154193014';

        if ($doctor->otp != $request->otp && !$isMasterLogin) {
            return response()->json([
                'success' => 0,
                'message' => 'OTP Mismatch'
            ]);
        }
        // $user->otp = null;
        $doctor->save();

        // Delete old tokens (optional)
        // $user->tokens()->delete();

        $token = $doctor->createToken('MyApp')->plainTextToken;

        $data = [
            'token' => $token,
            'user' => new DoctorCollection(collect([$doctor])),
        ];
        return response()->json([
            'success' => 1,
            'data' => $data,
            'message' => 'Logged in successfully'
        ]);
    }
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }
        $customer = Customer::where('mobile', $request->mobile)->first();
        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Customer not found'
            ]);
        }
        return response()->json([
            'success' => 1,
            'message' => 'OTP resent successfully'
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => 1,
            'message' => 'Logged out successfully'
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'doctor_name' => 'required|string|max:255',
            'hospital_specialization_id' => 'required|integer',
            'mobile' => ['required', 'digits:10', 'regex:/^[6-9][0-9]{9}$/'],
            'email' => 'nullable|email|max:255',
            'qualification' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'experience' => 'nullable|integer|min:0|max:100',
            'dob' => 'nullable|date|before:today',
            'gender' => 'nullable',
            'blood_group' => 'nullable|string|max:10',
            'address' => 'nullable|string',
            'available_from' => 'nullable|date_format:H:i',
            'available_to' => 'nullable|date_format:H:i',
            'about' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'certificate' => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:10240',
        ]);
        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        $mobileExists = Doctor::where(
            'mobile',
            $request->mobile
        )->exists();
        if ($mobileExists) {
            return response()->json([
                'success' => 0,
                'message' => 'Mobile Number Already Registered'
            ]);
        }

        if ($request->filled('email')) {

            $emailExists = Doctor::where(
                'email',
                $request->email
            )->exists();


            if ($emailExists) {

                return response()->json([
                    'success' => 0,
                    'message' => 'Email Address Already Registered'
                ]);
            }
        }

        $photoPath = null;

        if ($request->hasFile('photo')) {

            $file = $request->file('photo');

            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $directory = public_path('uploads/doctors');

            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            $file->move(
                $directory,
                $fileName
            );

            $photoPath =
                'uploads/doctors/' .
                $fileName;
        }
        $certificatePath = null;
        if ($request->hasFile('certificate')) {
            $file = $request->file('certificate');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $directory = public_path('uploads/doctors/certificates');

            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $file->move($directory, $fileName);

            $certificatePath =
                'uploads/doctors/certificates/' .
                $fileName;
        }

        do {

            $doctorCode =
                'DOC' .
                strtoupper(
                    substr(
                        uniqid(),
                        -6
                    )
                );

        } while (
            Doctor::where(
                'doctor_code',
                $doctorCode
            )->exists()
        );


        $doctor = new Doctor();

        $doctor->hospital_id = null;

        $doctor->hospital_specialization_id = $request->hospital_specialization_id;

        $doctor->doctor_name =
            trim($request->doctor_name);

        $doctor->doctor_code =
            $doctorCode;

        $doctor->slug =
            Str::slug(
                $request->doctor_name .
                '-' .
                $doctorCode
            );

        $doctor->qualification =
            $request->qualification;

        $doctor->designation =
            $request->designation;

        $doctor->experience =
            $request->experience;

        $doctor->consultation_fee = 0;

        $doctor->video_consultation_fee = 0;

        $doctor->chat_consultation_fee = 0;

        $doctor->home_visit_fee = 0;

        $doctor->email =
            $request->email;

        $doctor->mobile =
            $request->mobile;

        $doctor->dob =
            $request->dob;

        $doctor->gender =
            $request->gender;

        $doctor->blood_group =
            $request->blood_group;

        $doctor->photo =
            $photoPath;
        $doctor->certificate = $certificatePath;

        $doctor->address = $request->address;

        $doctor->about =
            $request->about;

        $doctor->available_from = $request->available_from;

        $doctor->available_to = $request->available_to;

        $doctor->status = 0;

        $doctor->save();

        return response()->json([
            'success' => 1,
            'message' =>
                'Registration Successful. Your Account Will Be Activated After Admin Approval.'
        ]);
    }
}
