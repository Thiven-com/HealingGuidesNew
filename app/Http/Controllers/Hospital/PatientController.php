<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\DoctorAppointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index(Request $request)
    { /* |-------------------------------------------------------------------------- | Hospital ID |-------------------------------------------------------------------------- */
        $hospital = Auth::guard('hospital')->user();

        $hospitalId = $hospital->id; /* |-------------------------------------------------------------------------- | Patients from Doctor Appointments |-------------------------------------------------------------------------- | | We use family_member_id as the patient identifier. | If family_member_id is NULL, customer_id is used. | */
        $query = DoctorAppointment::with(['customer', 'familyMember', 'hospital',])->when($hospitalId, function ($query) use ($hospitalId) {
            $query->where('hospital_id', $hospitalId);
        })->where(function ($query) {
            $query->whereNotNull('family_member_id')->orWhereNotNull('customer_id');
        })->latest(); /* |-------------------------------------------------------------------------- | Search |-------------------------------------------------------------------------- */
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('appointment_no', 'like', "%{$search}%")->orWhereHas('customer', function ($customerQuery) use ($search) {
                    $customerQuery->where('name', 'like', "%{$search}%")->orWhere('mobile', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")->orWhere('customer_code', 'like', "%{$search}%");
                })->orWhereHas('familyMember', function ($familyQuery) use ($search) {
                    $familyQuery->where('name', 'like', "%{$search}%")->orWhere('mobile', 'like', "%{$search}%");
                });
            });
        } /* |-------------------------------------------------------------------------- | Get Appointments |-------------------------------------------------------------------------- */
        $appointments = $query->get(); /* |-------------------------------------------------------------------------- | Create Unique Patients Collection |-------------------------------------------------------------------------- | | family_member_id is the primary patient identifier. | For appointments without a family member, | customer_id is used. | */
        $patients = $appointments->map(function ($appointment) {
            $customer = $appointment->customer;
            if ($appointment->family_member_id) {
                $patient = $appointment->familyMember;
                return ['unique_id' => 'family_' . $appointment->family_member_id, 'type' => 'Family Member', 'family_member_id' => $appointment->family_member_id, 'customer_id' => $patient?->customer_id, 'patientname' => $patient?->name ?? '—', 'customername' => $customer?->name ?? '—', 'mobile' => $patient?->mobile ?? '—', 'email' => $patient?->email ?? '—', 'gender' => $patient?->gender ?? '—', 'dob' => $patient?->dob, 'age' => $patient?->age, 'blood_group' => $patient?->blood_group, 'height' => $patient?->height, 'weight' => $patient?->weight, 'occupation' => $patient?->occupation, 'photo' => $patient?->photo, 'address' => $patient?->address, 'city' => $patient?->city, 'state' => $patient?->state, 'country' => $patient?->country, 'pincode' => $patient?->pincode, 'emergency_contact_name' => $patient?->emergency_contact_name, 'emergency_contact_mobile' => $patient?->emergency_contact_mobile, 'appointment' => $appointment,];
            } /* |-------------------------------------------------------------------------- | Normal Customer |-------------------------------------------------------------------------- */
            return ['unique_id' => 'customer_' . $appointment->customer_id, 'type' => 'Customer', 'family_member_id' => null, 'customer_id' => $appointment->customer_id, 'customername' => $customer?->name ?? '—', 'mobile' => $customer?->mobile ?? '—', 'email' => $customer?->email ?? '—', 'gender' => $customer?->gender ?? '—', 'dob' => $customer?->dob, 'age' => $customer?->age, 'blood_group' => $customer?->blood_group, 'height' => $customer?->height, 'weight' => $customer?->weight, 'occupation' => $customer?->occupation, 'photo' => $customer?->photo, 'address' => $customer?->address, 'city' => $customer?->city, 'state' => $customer?->state, 'country' => $customer?->country, 'pincode' => $customer?->pincode, 'emergency_contact_name' => $customer?->emergency_contact_name, 'emergency_contact_mobile' => $customer?->emergency_contact_mobile, 'appointment' => $appointment,];
        })->unique('unique_id')->values();
        return view('hospital.patients.all', compact('patients', 'hospitalId'));
    }

    /*
   |--------------------------------------------------------------------------
   | Patient Details
   |--------------------------------------------------------------------------
   */

    public function show(Request $request, $type, $id)
    {
        /*
        |--------------------------------------------------------------------------
        | Family Member Patient
        |--------------------------------------------------------------------------
        */
        $hospital = Auth::guard('hospital')->user();

        $hospitalId = $hospital->id;
        if ($type === 'family') {

            $appointment = DoctorAppointment::with([
                'familyMember',
                'hospital',
                'customer',
            ])
                ->where('family_member_id', $id)
                ->when($hospitalId, function ($query) use ($hospitalId) {
                    $query->where('hospital_id', $hospitalId);
                })
                ->latest()
                ->firstOrFail();

            $patient = $appointment->familyMember;

            return view(
                'hospital.patients.show',
                compact(
                    'patient',
                    'appointment',
                    'type'
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Normal Customer Patient
        |--------------------------------------------------------------------------
        */

        $appointment = DoctorAppointment::with([
            'customer',
            'hospital',
        ])
            ->where('customer_id', $id)
            ->when($hospitalId, function ($query) use ($hospitalId) {
                $query->where('hospital_id', $hospitalId);
            })
            ->latest()
            ->firstOrFail();

        $patient = $appointment->customer;

        return view(
            'hospital.patients.show',
            compact(
                'patient',
                'appointment',
                'type'
            )
        );
    }
}
