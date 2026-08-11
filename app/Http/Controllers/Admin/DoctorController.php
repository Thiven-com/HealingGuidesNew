<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\HospitalSpecialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with([
            'hospital',
            'hospitalSpecialization'
        ])->latest();

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('doctor_name', 'like', "%{$search}%")
                    ->orWhere('doctor_code', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");

            });
        }

        if ($request->filled('hospital_id')) {
            $query->where(
                'hospital_id',
                $request->hospital_id
            );
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        $doctors = $query->paginate(20);

        $hospitals = Hospital::where('status', 1)
            ->orderBy('hospital_name')
            ->get();

        return view(
            'admin.doctors.index',
            compact(
                'doctors',
                'hospitals'
            )
        );
    }

    public function create()
    {
        $hospitals = Hospital::where('status', 1)
            ->orderBy('hospital_name')
            ->get();

        $specializations = HospitalSpecialization::with('specialization')->where('status', 1)->get();

        return view(
            'admin.doctors.create',
            compact(
                'hospitals',
                'specializations'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([

            'hospital_id' =>
                'required|exists:hospitals,id',

            'hospital_specialization_id' =>
                'required|exists:hospital_specializations,id',

            'doctor_name' =>
                'required|string|max:255',

            'qualification' =>
                'nullable|string|max:255',

            'designation' =>
                'nullable|string|max:255',

            'experience' =>
                'nullable|numeric|min:0',

            'consultation_fee' =>
                'required|numeric|min:0',

            'video_consultation_fee' =>
                'nullable|numeric|min:0',

            'chat_consultation_fee' =>
                'nullable|numeric|min:0',

            'home_visit_fee' =>
                'nullable|numeric|min:0',

            'email' =>
                'nullable|email|max:255',

            'mobile' =>
                'required|string|max:20',

            'dob' =>
                'nullable|date',

            'gender' =>
                'nullable|in:male,female,other',

            'blood_group' =>
                'nullable|string|max:10',

            'photo' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            'certificate' =>
                'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',

            'address' =>
                'nullable|string|max:1000',

            'about' =>
                'nullable|string',

            'available_from' =>
                'nullable',

            'available_to' =>
                'nullable',

            'status' =>
                'nullable|boolean',
        ]);

        $doctor = new Doctor();

        $doctor->hospital_id =
            $request->hospital_id;

        $doctor->hospital_specialization_id =
            $request->hospital_specialization_id;

        $doctor->doctor_name =
            $request->doctor_name;

        $doctor->slug = Str::slug(
            $request->doctor_name
        );

        $doctor->doctor_code =
            'DOC' . strtoupper(
                Str::random(8)
            );

        $doctor->qualification =
            $request->qualification;

        $doctor->designation =
            $request->designation;

        $doctor->experience =
            $request->experience;

        $doctor->consultation_fee =
            $request->consultation_fee;

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

        $doctor->status =
            $request->has('status')
            ? $request->status
            : 1;

        if ($request->hasFile('photo')) {

            $doctor->photo =
                $request->file('photo')
                    ->store(
                        'doctors',
                        'public'
                    );
        }

        if ($request->hasFile('certificate')) {

            $doctor->certificate =
                $request->file('certificate')
                    ->store(
                        'doctor-certificates',
                        'public'
                    );
        }

        $doctor->save();

        return redirect()
            ->route('admin.doctors.index')
            ->with(
                'success',
                'Doctor added successfully.'
            );
    }

    public function show($id)
    {
        $doctor = Doctor::with([
            'hospital',
            'hospitalSpecialization'
        ])->findOrFail($id);

        return view(
            'admin.doctors.show',
            compact('doctor')
        );
    }

    public function edit($id)
    {
        $doctor = Doctor::findOrFail($id);

        $hospitals = Hospital::where('status', 1)
            ->orderBy('hospital_name')
            ->get();

        $specializations = HospitalSpecialization::with(
            'specialization'
        )
            ->get();

        return view(
            'admin.doctors.edit',
            compact(
                'doctor',
                'hospitals',
                'specializations'
            )
        );
    }

    public function update(
        Request $request,
        $id
    ) {
        $doctor = Doctor::findOrFail($id);

        $request->validate([

            'hospital_id' =>
                'required|exists:hospitals,id',

            'hospital_specialization_id' =>
                'required|exists:hospital_specializations,id',

            'doctor_name' =>
                'required|string|max:255',

            'qualification' =>
                'nullable|string|max:255',

            'designation' =>
                'nullable|string|max:255',

            'experience' =>
                'nullable|numeric|min:0',

            'consultation_fee' =>
                'required|numeric|min:0',

            'video_consultation_fee' =>
                'nullable|numeric|min:0',

            'chat_consultation_fee' =>
                'nullable|numeric|min:0',

            'home_visit_fee' =>
                'nullable|numeric|min:0',

            'email' =>
                'nullable|email|max:255',

            'mobile' =>
                'required|string|max:20',

            'dob' =>
                'nullable|date',

            'gender' =>
                'nullable|in:male,female,other',

            'blood_group' =>
                'nullable|string|max:10',

            'photo' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            'certificate' =>
                'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',

            'address' =>
                'nullable|string|max:1000',

            'about' =>
                'nullable|string',

            'available_from' =>
                'nullable',

            'available_to' =>
                'nullable',

            'status' =>
                'nullable|boolean',
        ]);

        $doctor->hospital_id =
            $request->hospital_id;

        $doctor->hospital_specialization_id =
            $request->hospital_specialization_id;

        $doctor->doctor_name =
            $request->doctor_name;

        $doctor->slug = Str::slug(
            $request->doctor_name
        );

        $doctor->qualification =
            $request->qualification;

        $doctor->designation =
            $request->designation;

        $doctor->experience =
            $request->experience;

        $doctor->consultation_fee =
            $request->consultation_fee;

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

        if ($request->has('status')) {
            $doctor->status =
                $request->status;
        }

        if ($request->hasFile('photo')) {

            if ($doctor->photo) {
                Storage::disk('public')
                    ->delete($doctor->photo);
            }

            $doctor->photo =
                $request->file('photo')
                    ->store(
                        'doctors',
                        'public'
                    );
        }

        if ($request->hasFile('certificate')) {

            if ($doctor->certificate) {
                Storage::disk('public')
                    ->delete($doctor->certificate);
            }

            $doctor->certificate =
                $request->file('certificate')
                    ->store(
                        'doctor-certificates',
                        'public'
                    );
        }

        $doctor->save();

        return redirect()
            ->route('admin.doctors.index')
            ->with(
                'success',
                'Doctor updated successfully.'
            );
    }

    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);

        if ($doctor->photo) {
            Storage::disk('public')
                ->delete($doctor->photo);
        }

        if ($doctor->certificate) {
            Storage::disk('public')
                ->delete($doctor->certificate);
        }

        $doctor->delete();

        return redirect()
            ->route('admin.doctors.index')
            ->with(
                'success',
                'Doctor deleted successfully.'
            );
    }

    public function status($id)
    {
        $doctor = Doctor::findOrFail($id);

        $doctor->status =
            $doctor->status ? 0 : 1;

        $doctor->save();

        return back()->with(
            'success',
            'Doctor status updated successfully.'
        );
    }
}