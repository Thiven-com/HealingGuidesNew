<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\HospitalSpecialization;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $hospital = Auth::guard('hospital')->user();

        $query = Doctor::where('hospital_id', $hospital->id);

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('doctor_name', 'like', "%{$search}%")
                    ->orWhere('doctor_code', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('qualification', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        $doctors = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'hospital.doctors.index',
            compact('doctors')
        );
    }


    public function create()
    {
        $hospital = Auth::guard('hospital')->user();

        $specializations = HospitalSpecialization::with([
            'specialization'
        ])
            ->where(
                'hospital_id',
                $hospital->id
            )
            ->where(
                'status',
                1
            )
            ->get();

        return view(
            'hospital.doctors.create',
            compact(
                'hospital',
                'specializations'
            )
        );
    }


    public function store(Request $request)
    {
        $hospital = Auth::guard('hospital')->user();

        $validator = Validator::make($request->all(), [

            'doctor_name' =>
                'required|string|max:255',

            'hospital_specialization_id' =>
                'required|exists:hospital_specializations,id',

            'certificate' =>
                'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',

            'qualification' =>
                'required|string|max:255',

            'designation' =>
                'nullable|string|max:255',

            'experience' =>
                'nullable|integer|min:0|max:100',

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

            'mobile' => [
                'required',
                'digits:10',
                'regex:/^[6-9][0-9]{9}$/',
                'unique:doctors,mobile'
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
                'nullable|string',

            'about' =>
                'nullable|string',

            'available_from' =>
                'nullable',

            'available_to' =>
                'nullable',

        ]);


        if ($validator->fails()) {

            return back()
                ->withErrors($validator)
                ->withInput();
        }


        $photoPath = null;


        if ($request->hasFile('photo')) {

            $file = $request->file('photo');

            $fileName =
                time()
                . '_'
                . uniqid()
                . '.'
                . $file->getClientOriginalExtension();

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


            $photoPath =
                'uploads/doctors/' . $fileName;
        }
        $certificatePath = null;

        if ($request->hasFile('certificate')) {

            $file = $request->file('certificate');

            $fileName =
                time()
                . '_certificate_'
                . uniqid()
                . '.'
                . $file->getClientOriginalExtension();

            $directory =
                public_path('uploads/doctors/certificates');

            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $file->move(
                $directory,
                $fileName
            );

            $certificatePath =
                'uploads/doctors/certificates/' . $fileName;
        }


        do {

            $doctorCode =
                'DOC'
                . strtoupper(
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

        $doctor->hospital_id =
            $hospital->id;

        $doctor->hospital_specialization_id =
            $request->hospital_specialization_id;

        $doctor->doctor_name =
            trim($request->doctor_name);

        $doctor->doctor_code =
            $doctorCode;

        $doctor->slug =
            Str::slug(
                $request->doctor_name
                . '-'
                . $doctorCode
            );

        $doctor->qualification =
            $request->qualification;

        $doctor->designation =
            $request->designation;

        $doctor->experience =
            $request->experience ?? null;

        $doctor->consultation_fee =
            $request->consultation_fee ?? 0;

        $doctor->video_consultation_fee =
            $request->video_consultation_fee ?? 0;

        $doctor->chat_consultation_fee =
            $request->chat_consultation_fee ?? 0;

        $doctor->home_visit_fee =
            $request->home_visit_fee ?? 0;

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

        $doctor->address =
            $request->address;

        $doctor->about =
            $request->about;

        $doctor->available_from =
            $request->available_from;

        $doctor->available_to =
            $request->available_to;

        $doctor->status = 1;
        $doctor->certificate = $certificatePath;

        $doctor->save();


        return redirect()
            ->route('hospital.doctors.index')
            ->with(
                'success',
                'Doctor Added Successfully'
            );
    }


    public function show($id)
    {
        $hospital = Auth::guard('hospital')->user();

        $doctor = Doctor::with([
            'hospitalSpecialization.specialization'
        ])
            ->where('hospital_id', $hospital->id)
            ->where('id', $id)
            ->firstOrFail();

        return view(
            'hospital.doctors.show',
            compact('doctor')
        );
    }


    public function edit($id)
    {
        $hospital = Auth::guard('hospital')->user();

        $doctor = Doctor::where(
            'hospital_id',
            $hospital->id
        )
            ->where(
                'id',
                $id
            )
            ->firstOrFail();


        $specializations = HospitalSpecialization::with([
            'specialization'
        ])
            ->where(
                'hospital_id',
                $hospital->id
            )
            ->where(
                'status',
                1
            )
            ->get();


        return view(
            'hospital.doctors.edit',
            compact(
                'doctor',
                'specializations'
            )
        );
    }


    public function update(Request $request, $id)
    {
        $hospital = Auth::guard('hospital')->user();

        $doctor = Doctor::where(
            'hospital_id',
            $hospital->id
        )
            ->where('id', $id)
            ->firstOrFail();


        $validator = Validator::make($request->all(), [

            'doctor_name' =>
                'required|string|max:255',

            'hospital_specialization_id' =>
                'nullable|exists:specializations,id',

            'qualification' =>
                'required|string|max:255',

            'designation' =>
                'nullable|string|max:255',

            'experience' =>
                'nullable|integer|min:0|max:100',

            'consultation_fee' =>
                'nullable|numeric|min:0',

            'email' =>
                'nullable|email|max:255|unique:doctors,email,'
                . $doctor->id,

            'mobile' => [
                'required',
                'digits:10',
                'regex:/^[6-9][0-9]{9}$/',
                'unique:doctors,mobile,' . $doctor->id
            ],

            'photo' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

        ]);


        if ($validator->fails()) {

            return back()
                ->withErrors($validator)
                ->withInput();
        }


        if ($request->hasFile('photo')) {

            if (
                $doctor->photo &&
                file_exists(public_path($doctor->photo))
            ) {
                @unlink(
                    public_path($doctor->photo)
                );
            }


            $file = $request->file('photo');

            $fileName =
                time()
                . '_'
                . uniqid()
                . '.'
                . $file->getClientOriginalExtension();


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
                'uploads/doctors/' . $fileName;
        }
        if ($request->hasFile('certificate')) {

            if (
                $doctor->certificate &&
                file_exists(public_path($doctor->certificate))
            ) {
                @unlink(
                    public_path($doctor->certificate)
                );
            }


            $file = $request->file('certificate');

            $fileName =
                time()
                . '_'
                . uniqid()
                . '.'
                . $file->getClientOriginalExtension();


            $directory =
                public_path('uploads/doctors/certificates');


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


            $doctor->certificate =
                'uploads/doctors/certificates/' . $fileName;
        }


        $doctor->hospital_specialization_id =
            $request->hospital_specialization_id;

        $doctor->doctor_name =
            trim($request->doctor_name);

        $doctor->slug =
            Str::slug(
                $request->doctor_name
                . '-'
                . $doctor->doctor_code
            );

        $doctor->qualification =
            $request->qualification;

        $doctor->designation =
            $request->designation;

        $doctor->experience =
            $request->experience ?? null;

        $doctor->consultation_fee =
            $request->consultation_fee ?? 0;

        $doctor->video_consultation_fee =
            $request->video_consultation_fee ?? 0;

        $doctor->chat_consultation_fee =
            $request->chat_consultation_fee ?? 0;

        $doctor->home_visit_fee =
            $request->home_visit_fee ?? 0;

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

        $doctor->address =
            $request->address;

        $doctor->about =
            $request->about;

        $doctor->available_from =
            $request->available_from;

        $doctor->available_to =
            $request->available_to;

        $doctor->save();


        return redirect()
            ->route(
                'hospital.doctors.index'
            )
            ->with(
                'success',
                'Doctor Updated Successfully'
            );
    }


    public function updateStatus(Request $request)
    {
        $hospital = Auth::guard('hospital')->user();

        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|integer',
                'status' => 'required|in:0,1',
            ]
        );


        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' =>
                    $validator->errors()->first()
            ]);
        }


        $doctor = Doctor::where(
            'hospital_id',
            $hospital->id
        )
            ->where(
                'id',
                $request->id
            )
            ->first();


        if (!$doctor) {

            return response()->json([
                'success' => 0,
                'message' => 'Doctor Not Found'
            ]);
        }


        $doctor->status =
            $request->status;

        $doctor->save();


        return response()->json([
            'success' => 1,
            'message' =>
                'Doctor Status Updated Successfully'
        ]);
    }


    public function destroy($id)
    {
        $hospital = Auth::guard('hospital')->user();

        $doctor = Doctor::where(
            'hospital_id',
            $hospital->id
        )
            ->where('id', $id)
            ->firstOrFail();


        $doctor->delete();


        return redirect()
            ->route('hospital.doctors.index')
            ->with(
                'success',
                'Doctor Deleted Successfully'
            );
    }
}