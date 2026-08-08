<?php

namespace App\Http\Controllers\HospitalApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\HospitalDoctorCollection;
use App\Models\Doctor;
use App\Models\HospitalSpecialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Doctors List
    |--------------------------------------------------------------------------
    */

    public function doctors(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $doctors = Doctor::with([
            'hospital',
            'hospitalSpecialization.specialization'
        ])
            ->where('hospital_id', $hospital->id);

        /*
        |--------------------------------------------------------------------------
        | ID Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('id')) {
            $doctors->where('id', $request->id);
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $doctors->where(function ($query) use ($search) {

                $query->where(
                    'doctor_name',
                    'LIKE',
                    '%' . $search . '%'
                )
                    ->orWhere(
                        'doctor_code',
                        'LIKE',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'mobile',
                        'LIKE',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'email',
                        'LIKE',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'qualification',
                        'LIKE',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'designation',
                        'LIKE',
                        '%' . $search . '%'
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Specialization Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('hospital_specialization_id')) {

            $doctors->where(
                'hospital_specialization_id',
                $request->hospital_specialization_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Gender Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('gender')) {

            $doctors->where(
                'gender',
                $request->gender
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->has('status')) {

            $doctors->where(
                'status',
                $request->status
            );
        }

        $doctors = $doctors
            ->latest()
            ->paginate(20);

        if ($doctors->isEmpty()) {

            return response()->json([
                'success' => 0,
                'message' => 'No Doctors Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Doctors Fetched Successfully',
            'data' => new HospitalDoctorCollection($doctors)
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Doctor Details
    |--------------------------------------------------------------------------
    */

    public function doctorDetails($id)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {

            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $doctor = Doctor::with([
            'hospital',
            'hospitalSpecialization.specialization'
        ])
            ->where('id', $id)
            ->where('hospital_id', $hospital->id)
            ->first();

        if (!$doctor) {

            return response()->json([
                'success' => 0,
                'message' => 'Doctor not found.'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Doctor Details Fetched Successfully',
            'data' => new HospitalDoctorCollection(
                collect([$doctor])
            )
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Hospital Specializations
    |--------------------------------------------------------------------------
    */

    public function specializations(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {

            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $specializations = HospitalSpecialization::with([
            'specialization'
        ])
            ->where(
                'hospital_id',
                $hospital->id
            );

        if ($request->filled('id')) {

            $specializations->where(
                'id',
                $request->id
            );
        }

        if ($request->has('status')) {

            $specializations->where(
                'status',
                $request->status
            );
        }

        $specializations = $specializations
            ->latest()
            ->get();

        if ($specializations->isEmpty()) {

            return response()->json([
                'success' => 0,
                'message' => 'No Specializations Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Specializations Fetched Successfully',
            'data' => $specializations
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Create Doctor
    |--------------------------------------------------------------------------
    */

    public function createDoctor(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {

            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make($request->all(), [

            'hospital_specialization_id' =>
                'required|exists:hospital_specializations,id',

            'doctor_name' =>
                'required|string|max:255',

            'qualification' =>
                'nullable|string|max:255',

            'designation' =>
                'nullable|string|max:255',

            'experience' =>
                'nullable|integer|min:0',

            'consultation_fee' =>
                'nullable|numeric|min:0',

            'video_consultation_fee' =>
                'nullable|numeric|min:0',

            'chat_consultation_fee' =>
                'nullable|numeric|min:0',

            'home_visit_fee' =>
                'nullable|numeric|min:0',

            'email' =>
                'nullable|email|max:255|unique:doctors,email',

            'mobile' =>
                'required|string|max:20|unique:doctors,mobile',

            'dob' =>
                'nullable|date|before:today',

            'gender' =>
                'nullable|in:male,female,other',

            'blood_group' =>
                'nullable|string|max:10',

            'photo' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            'address' =>
                'nullable|string|max:1000',

            'about' =>
                'nullable|string',

            'available_from' =>
                'nullable|date_format:H:i',

            'available_to' =>
                'nullable|date_format:H:i',

            'status' =>
                'nullable|in:0,1',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Specialization Belongs To Hospital
        |--------------------------------------------------------------------------
        */

        $specialization = HospitalSpecialization::where(
            'id',
            $request->hospital_specialization_id
        )
            ->where(
                'hospital_id',
                $hospital->id
            )
            ->first();

        if (!$specialization) {

            return response()->json([
                'success' => 0,
                'message' => 'Invalid hospital specialization.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Upload Photo
        |--------------------------------------------------------------------------
        */

        $photo = null;

        if ($request->hasFile('photo')) {

            $file = $request->file('photo');

            $fileName =
                time() .
                '_' .
                uniqid() .
                '.' .
                $file->getClientOriginalExtension();

            $directory = public_path('uploads/doctors');

            if (!file_exists($directory)) {

                mkdir(
                    $directory,
                    0755,
                    true
                );
            }

            $file->move(
                $directory,
                $fileName
            );

            $photo =
                'uploads/doctors/' . $fileName;
        }

        $lastDoctor = Doctor::orderBy('id', 'desc')->first();

        if (!$lastDoctor) {
            return 'DOC0001';
        }

        // Assuming the code is stored in a `doctor_code` column
        $lastNumber = (int) str_replace('DOC', '', $lastDoctor->doctor_code);

        $doctorCode = 'DOC' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        /*
        |--------------------------------------------------------------------------
        | Generate Unique Slug
        |--------------------------------------------------------------------------
        */

        $slug = $this->generateSlug(
            $request->doctor_name
        );

        /*
        |--------------------------------------------------------------------------
        | Create Doctor
        |--------------------------------------------------------------------------
        */

        $doctor = Doctor::create([

            'hospital_id' =>
                $hospital->id,

            'hospital_specialization_id' =>
                $request->hospital_specialization_id,

            'doctor_name' =>
                $request->doctor_name,

            'slug' =>
                $slug,

            'doctor_code' =>
                $doctorCode,

            'qualification' =>
                $request->qualification,

            'designation' =>
                $request->designation,

            'experience' =>
                $request->experience ?? 0,

            'consultation_fee' =>
                $request->consultation_fee ?? 0,

            'video_consultation_fee' =>
                $request->video_consultation_fee ?? 0,

            'chat_consultation_fee' =>
                $request->chat_consultation_fee ?? 0,

            'home_visit_fee' =>
                $request->home_visit_fee ?? 0,

            'email' =>
                $request->email,

            'mobile' =>
                $request->mobile,

            'dob' =>
                $request->dob,

            'gender' =>
                $request->gender,

            'blood_group' =>
                $request->blood_group,

            'photo' =>
                $photo,

            'address' =>
                $request->address,

            'about' =>
                $request->about,

            'available_from' =>
                $request->available_from,

            'available_to' =>
                $request->available_to,

            'status' =>
                $request->has('status')
                ? $request->status
                : 1,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Load Relations
        |--------------------------------------------------------------------------
        */

        $doctor->load([
            'hospital',
            'hospitalSpecialization.specialization'
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Doctor Created Successfully',
            'data' => new HospitalDoctorCollection(
                collect([$doctor])
            )
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Doctor
    |--------------------------------------------------------------------------
    */

    public function updateDoctor(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {

            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Validation Doctor ID
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make($request->all(), [

            'doctor_id' =>
                'required|integer',

        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Find Doctor
        |--------------------------------------------------------------------------
        */

        $doctor = Doctor::where(
            'id',
            $request->doctor_id
        )
            ->where(
                'hospital_id',
                $hospital->id
            )
            ->first();

        if (!$doctor) {

            return response()->json([
                'success' => 0,
                'message' => 'Doctor not found.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make($request->all(), [

            'hospital_specialization_id' =>
                'nullable|exists:hospital_specializations,id',

            'doctor_name' =>
                'nullable|string|max:255',

            'qualification' =>
                'nullable|string|max:255',

            'designation' =>
                'nullable|string|max:255',

            'experience' =>
                'nullable|integer|min:0',

            'consultation_fee' =>
                'nullable|numeric|min:0',

            'video_consultation_fee' =>
                'nullable|numeric|min:0',

            'chat_consultation_fee' =>
                'nullable|numeric|min:0',

            'home_visit_fee' =>
                'nullable|numeric|min:0',

            'email' => [

                'nullable',

                'email',

                'max:255',

                Rule::unique(
                    'doctors',
                    'email'
                )->ignore($doctor->id),
            ],

            'mobile' => [

                'nullable',

                'string',

                'max:20',

                Rule::unique(
                    'doctors',
                    'mobile'
                )->ignore($doctor->id),
            ],

            'dob' =>
                'nullable|date|before:today',

            'gender' =>
                'nullable|in:male,female,other',

            'blood_group' =>
                'nullable|string|max:10',

            'photo' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            'address' =>
                'nullable|string|max:1000',

            'about' =>
                'nullable|string',

            'available_from' =>
                'nullable|date_format:H:i',

            'available_to' =>
                'nullable|date_format:H:i',

            'status' =>
                'nullable|in:0,1',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Specialization
        |--------------------------------------------------------------------------
        */

        if ($request->filled('hospital_specialization_id')) {

            $specialization =
                HospitalSpecialization::where(
                    'id',
                    $request->hospital_specialization_id
                )
                    ->where(
                        'hospital_id',
                        $hospital->id
                    )
                    ->first();

            if (!$specialization) {

                return response()->json([
                    'success' => 0,
                    'message' => 'Invalid hospital specialization.'
                ]);
            }

            $doctor->hospital_specialization_id =
                $specialization->id;
        }

        /*
        |--------------------------------------------------------------------------
        | Doctor Name
        |--------------------------------------------------------------------------
        */

        if ($request->filled('doctor_name')) {

            $doctor->doctor_name =
                $request->doctor_name;

            $doctor->slug =
                $this->generateSlug(
                    $request->doctor_name,
                    $doctor->id
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Qualification
        |--------------------------------------------------------------------------
        */

        if ($request->has('qualification')) {

            $doctor->qualification =
                $request->qualification;
        }

        /*
        |--------------------------------------------------------------------------
        | Designation
        |--------------------------------------------------------------------------
        */

        if ($request->has('designation')) {

            $doctor->designation =
                $request->designation;
        }

        /*
        |--------------------------------------------------------------------------
        | Experience
        |--------------------------------------------------------------------------
        */

        if ($request->has('experience')) {

            $doctor->experience =
                $request->experience;
        }

        /*
        |--------------------------------------------------------------------------
        | Consultation Fee
        |--------------------------------------------------------------------------
        */

        if ($request->has('consultation_fee')) {

            $doctor->consultation_fee =
                $request->consultation_fee;
        }

        /*
        |--------------------------------------------------------------------------
        | Video Consultation Fee
        |--------------------------------------------------------------------------
        */

        if ($request->has('video_consultation_fee')) {

            $doctor->video_consultation_fee =
                $request->video_consultation_fee;
        }

        /*
        |--------------------------------------------------------------------------
        | Chat Consultation Fee
        |--------------------------------------------------------------------------
        */

        if ($request->has('chat_consultation_fee')) {

            $doctor->chat_consultation_fee =
                $request->chat_consultation_fee;
        }

        /*
        |--------------------------------------------------------------------------
        | Home Visit Fee
        |--------------------------------------------------------------------------
        */

        if ($request->has('home_visit_fee')) {

            $doctor->home_visit_fee =
                $request->home_visit_fee;
        }

        /*
        |--------------------------------------------------------------------------
        | Email
        |--------------------------------------------------------------------------
        */

        if ($request->has('email')) {

            $doctor->email =
                $request->email;
        }

        /*
        |--------------------------------------------------------------------------
        | Mobile
        |--------------------------------------------------------------------------
        */

        if ($request->filled('mobile')) {

            $doctor->mobile =
                $request->mobile;
        }

        /*
        |--------------------------------------------------------------------------
        | DOB
        |--------------------------------------------------------------------------
        */

        if ($request->has('dob')) {

            $doctor->dob =
                $request->dob;
        }

        /*
        |--------------------------------------------------------------------------
        | Gender
        |--------------------------------------------------------------------------
        */

        if ($request->has('gender')) {

            $doctor->gender =
                $request->gender;
        }

        /*
        |--------------------------------------------------------------------------
        | Blood Group
        |--------------------------------------------------------------------------
        */

        if ($request->has('blood_group')) {

            $doctor->blood_group =
                $request->blood_group;
        }

        /*
        |--------------------------------------------------------------------------
        | Address
        |--------------------------------------------------------------------------
        */

        if ($request->has('address')) {

            $doctor->address =
                $request->address;
        }

        /*
        |--------------------------------------------------------------------------
        | About
        |--------------------------------------------------------------------------
        */

        if ($request->has('about')) {

            $doctor->about =
                $request->about;
        }

        /*
        |--------------------------------------------------------------------------
        | Available From
        |--------------------------------------------------------------------------
        */

        if ($request->has('available_from')) {

            $doctor->available_from =
                $request->available_from;
        }

        /*
        |--------------------------------------------------------------------------
        | Available To
        |--------------------------------------------------------------------------
        */

        if ($request->has('available_to')) {

            $doctor->available_to =
                $request->available_to;
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->has('status')) {

            $doctor->status =
                $request->status;
        }

        /*
        |--------------------------------------------------------------------------
        | Update Photo
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('photo')) {

            /*
            |--------------------------------------------------------------------------
            | Delete Old Photo
            |--------------------------------------------------------------------------
            */

            if (
                $doctor->photo &&
                file_exists(
                    public_path($doctor->photo)
                )
            ) {

                @unlink(
                    public_path($doctor->photo)
                );
            }

            $file =
                $request->file('photo');

            $fileName =
                time() .
                '_' .
                uniqid() .
                '.' .
                $file->getClientOriginalExtension();

            $directory =
                public_path('uploads/doctors');

            if (!file_exists($directory)) {

                mkdir(
                    $directory,
                    0755,
                    true
                );
            }

            $file->move(
                $directory,
                $fileName
            );

            $doctor->photo =
                'uploads/doctors/' .
                $fileName;
        }

        /*
        |--------------------------------------------------------------------------
        | Save
        |--------------------------------------------------------------------------
        */

        $doctor->save();

        $doctor->load([
            'hospital',
            'hospitalSpecialization.specialization'
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Doctor Updated Successfully',
            'data' => new HospitalDoctorCollection(
                collect([$doctor])
            )
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Doctor Status
    |--------------------------------------------------------------------------
    */

    public function updateStatus(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {

            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make($request->all(), [

            'doctor_id' =>
                'required|integer',

            'status' =>
                'required|in:0,1',

        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Find Doctor
        |--------------------------------------------------------------------------
        */

        $doctor = Doctor::where(
            'id',
            $request->doctor_id
        )
            ->where(
                'hospital_id',
                $hospital->id
            )
            ->first();

        if (!$doctor) {

            return response()->json([
                'success' => 0,
                'message' => 'Doctor not found.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $doctor->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => 1,

            'message' =>
                $request->status == 1
                ? 'Doctor Activated Successfully'
                : 'Doctor Deactivated Successfully'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Unique Slug
    |--------------------------------------------------------------------------
    */

    private function generateSlug(
        $doctorName,
        $ignoreId = null
    ) {

        $baseSlug =
            Str::slug($doctorName);

        $slug =
            $baseSlug;

        $count = 1;

        while (true) {

            $query =
                Doctor::where(
                    'slug',
                    $slug
                );

            if ($ignoreId) {

                $query->where(
                    'id',
                    '!=',
                    $ignoreId
                );
            }

            if (!$query->exists()) {

                break;
            }

            $slug =
                $baseSlug .
                '-' .
                $count;

            $count++;
        }

        return $slug;
    }
}