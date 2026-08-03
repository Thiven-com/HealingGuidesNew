<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientVitalCollection;
use App\Http\Resources\PrescriptionCollection;
use App\Models\PatientVital;
use App\Models\Prescription;
use Illuminate\Http\Request;

class HealthRecordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | My Prescriptions
    |--------------------------------------------------------------------------
    */

    public function prescriptions(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $prescriptions = Prescription::with([
            'doctor',
            'appointment',
            'familyMember',
            'medicines',
            'recommendedLabTests.labTest'
        ])
            ->where('customer_id', $customer->id)
            ->where('status', 1);

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        if ($request->filled('id')) {
            $prescriptions->where('id', $request->id);
        }

        if ($request->filled('appointment_id')) {
            $prescriptions->where(
                'appointment_id',
                $request->appointment_id
            );
        }

        if ($request->filled('doctor_id')) {
            $prescriptions->where(
                'doctor_id',
                $request->doctor_id
            );
        }

        if ($request->filled('family_member_id')) {

            $prescriptions->where(
                'family_member_id',
                $request->family_member_id
            );

        } elseif ($request->has('family_member_id')) {

            $prescriptions->whereNull(
                'family_member_id'
            );
        }

        if ($request->filled('followup_date')) {

            $prescriptions->whereDate(
                'followup_date',
                $request->followup_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get Prescriptions
        |--------------------------------------------------------------------------
        */

        $prescriptions = $prescriptions
            ->latest()
            ->paginate(20);

        if ($prescriptions->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'No Prescriptions Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => new PrescriptionCollection(
                $prescriptions
            ),
            'message' => 'Prescriptions fetched successfully.'
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Prescription Details
    |--------------------------------------------------------------------------
    */

    public function prescriptionDetails($id)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $prescription = Prescription::with([
            'doctor',
            'appointment',
            'familyMember',
            'medicines',
            'recommendedLabTests.labTest'
        ])
            ->where('id', $id)
            ->where('customer_id', $customer->id)
            ->where('status', 1)
            ->get();

        if ($prescription->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'Prescription not found.'
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => new PrescriptionCollection(
                $prescription
            ),
            'message' => 'Prescription details fetched successfully.'
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | My Vitals
    |--------------------------------------------------------------------------
    */

    public function vitals(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $vitals = PatientVital::with([
            'doctor',
            'appointment',
            'familyMember'
        ])
            ->where('customer_id', $customer->id);

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        if ($request->filled('id')) {
            $vitals->where(
                'id',
                $request->id
            );
        }

        if ($request->filled('appointment_id')) {
            $vitals->where(
                'appointment_id',
                $request->appointment_id
            );
        }

        if ($request->filled('doctor_id')) {
            $vitals->where(
                'doctor_id',
                $request->doctor_id
            );
        }

        if ($request->filled('family_member_id')) {

            $vitals->where(
                'family_member_id',
                $request->family_member_id
            );

        } elseif ($request->has('family_member_id')) {

            $vitals->whereNull(
                'family_member_id'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get Vitals
        |--------------------------------------------------------------------------
        */

        $vitals = $vitals
            ->latest()
            ->paginate(20);

        if ($vitals->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'No Patient Vitals Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => new PatientVitalCollection(
                $vitals
            ),
            'message' => 'Patient vitals fetched successfully.'
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Vital Details
    |--------------------------------------------------------------------------
    */

    public function vitalDetails($id)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $vital = PatientVital::with([
            'doctor',
            'appointment',
            'familyMember'
        ])
            ->where('id', $id)
            ->where('customer_id', $customer->id)
            ->get();

        if ($vital->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'Patient vital not found.'
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => new PatientVitalCollection(
                $vital
            ),
            'message' => 'Patient vital details fetched successfully.'
        ]);
    }
}