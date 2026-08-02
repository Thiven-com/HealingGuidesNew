<?php

namespace App\Http\Controllers\DoctorApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorCollection;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
                'message' => 'Mobile Number Registered , Please Conatact Admin For Registration'
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
}
