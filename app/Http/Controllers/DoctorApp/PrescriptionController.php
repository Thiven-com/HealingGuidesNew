<?php

namespace App\Http\Controllers\DoctorApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\PrescriptionCollection;
use App\Models\DoctorAppointment;
use App\Models\Prescription;
use App\Models\PrescriptionMedicine;
use App\Models\PrescriptionLabTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PrescriptionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Create Prescription
    |--------------------------------------------------------------------------
    */

    public function createPrescription(Request $request)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
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

            'appointment_id' => 'required|exists:doctor_appointments,id',

            'symptoms' => 'nullable|string',

            'diagnosis' => 'nullable|string',

            'clinical_notes' => 'nullable|string',

            'advice' => 'nullable|string',

            'followup_date' => 'nullable|date|after_or_equal:today',

            'followup_notes' => 'nullable|string',

            /*
            |--------------------------------------------------------------------------
            | Medicines
            |--------------------------------------------------------------------------
            */

            'medicines' => 'nullable|array',

            'medicines.*.medicine_name' => 'required|filled|string|max:255',

            'medicines.*.dosage' => 'nullable|string|max:255',

            'medicines.*.frequency' => 'nullable|string|max:255',

            'medicines.*.duration' => 'nullable|string|max:255',

            'medicines.*.route' => 'nullable|string|max:255',

            'medicines.*.morning' => 'nullable|boolean',

            'medicines.*.afternoon' => 'nullable|boolean',

            'medicines.*.night' => 'nullable|boolean',

            'medicines.*.timing' => 'nullable|in:before_food,after_food,with_food,anytime',

            'medicines.*.instructions' => 'nullable|string',

            /*
            |--------------------------------------------------------------------------
            | Recommended Lab Tests
            |--------------------------------------------------------------------------
            */

            'lab_tests' => 'nullable|array',

            'lab_tests.*.lab_test_id' => 'required|exists:lab_tests,id',

            'lab_tests.*.instructions' => 'nullable|string|max:500',

        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Get Appointment
        |--------------------------------------------------------------------------
        */

        $appointment = DoctorAppointment::where('id', $request->appointment_id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$appointment) {

            return response()->json([
                'success' => 0,
                'message' => 'Appointment not found.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Appointment Status
        |--------------------------------------------------------------------------
        */

        if (
            in_array($appointment->appointment_status, [
                'cancelled',
                'rejected',
                'no_show'
            ])
        ) {

            return response()->json([
                'success' => 0,
                'message' => 'Prescription cannot be created for this appointment.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Existing Prescription
        |--------------------------------------------------------------------------
        */

        $existing = Prescription::where(
            'appointment_id',
            $appointment->id
        )->first();

        if ($existing) {

            return response()->json([
                'success' => 0,
                'message' => 'Prescription already created for this appointment.'
            ]);
        }

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Create Prescription
            |--------------------------------------------------------------------------
            */

            $prescription = Prescription::create([

                'appointment_id' => $appointment->id,

                'doctor_id' => $doctor->id,

                'customer_id' => $appointment->customer_id,

                'family_member_id' => $appointment->family_member_id,

                'symptoms' => $request->symptoms,

                'diagnosis' => $request->diagnosis,

                'clinical_notes' => $request->clinical_notes,

                'advice' => $request->advice,

                'followup_date' => $request->followup_date,

                'followup_notes' => $request->followup_notes,

                'status' => 1,

            ]);

            /*
            |--------------------------------------------------------------------------
            | Create Medicines
            |--------------------------------------------------------------------------
            */

            foreach ($request->medicines ?? [] as $medicine) {

                PrescriptionMedicine::create([

                    'prescription_id' => $prescription->id,

                    'medicine_name' => $medicine['medicine_name'],

                    'dosage' => $medicine['dosage'] ?? null,

                    'frequency' => $medicine['frequency'] ?? null,

                    'duration' => $medicine['duration'] ?? null,

                    'route' => $medicine['route'] ?? null,

                    'morning' => $medicine['morning'] ?? false,

                    'afternoon' => $medicine['afternoon'] ?? false,

                    'night' => $medicine['night'] ?? false,

                    'timing' => $medicine['timing'] ?? null,

                    'instructions' => $medicine['instructions'] ?? null,

                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Create Recommended Lab Tests
            |--------------------------------------------------------------------------
            */

            foreach ($request->lab_tests ?? [] as $test) {

                PrescriptionLabTest::create([

                    'prescription_id' => $prescription->id,

                    'lab_test_id' => $test['lab_test_id'],

                    'instructions' => $test['instructions'] ?? null,

                ]);
            }

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | Load Prescription Details
            |--------------------------------------------------------------------------
            */

            $prescription->load([

                'medicines',

                'recommendedLabTests.labTest',

                'appointment',

                'customer',

                'familyMember'

            ]);

            return response()->json([
                'success' => 1,
                'message' => 'Prescription created successfully.',
                'data' => $prescription
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => 0,
                'message' => 'Something went wrong while creating prescription.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Update Prescription
    |--------------------------------------------------------------------------
    */

    public function updatePrescription(Request $request)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {

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

            'prescription_id' => 'required|exists:prescriptions,id',

            'symptoms' => 'nullable|string',

            'diagnosis' => 'nullable|string',

            'clinical_notes' => 'nullable|string',

            'advice' => 'nullable|string',

            'followup_date' => 'nullable|date',

            'followup_notes' => 'nullable|string',

            /*
            |--------------------------------------------------------------------------
            | Medicines
            |--------------------------------------------------------------------------
            */

            'medicines' => 'nullable|array',

            'medicines.*.medicine_name' => 'required|filled|string|max:255',

            'medicines.*.dosage' => 'nullable|string|max:255',

            'medicines.*.frequency' => 'nullable|string|max:255',

            'medicines.*.duration' => 'nullable|string|max:255',

            'medicines.*.route' => 'nullable|string|max:255',

            'medicines.*.morning' => 'nullable|boolean',

            'medicines.*.afternoon' => 'nullable|boolean',

            'medicines.*.night' => 'nullable|boolean',

            'medicines.*.timing' => 'nullable|in:before_food,after_food,with_food,anytime',

            'medicines.*.instructions' => 'nullable|string',

            /*
            |--------------------------------------------------------------------------
            | Recommended Lab Tests
            |--------------------------------------------------------------------------
            */

            'lab_tests' => 'nullable|array',

            'lab_tests.*.lab_test_id' => 'required|exists:lab_tests,id',

            'lab_tests.*.instructions' => 'nullable|string|max:500',

        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Get Prescription
        |--------------------------------------------------------------------------
        */

        $prescription = Prescription::where('id', $request->prescription_id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$prescription) {

            return response()->json([
                'success' => 0,
                'message' => 'Prescription not found.'
            ]);
        }

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Update Prescription Fields
            |--------------------------------------------------------------------------
            */

            $data = [];

            if ($request->has('symptoms')) {

                $data['symptoms'] = $request->symptoms;
            }

            if ($request->has('diagnosis')) {

                $data['diagnosis'] = $request->diagnosis;
            }

            if ($request->has('clinical_notes')) {

                $data['clinical_notes'] = $request->clinical_notes;
            }

            if ($request->has('advice')) {

                $data['advice'] = $request->advice;
            }

            if ($request->has('followup_date')) {

                $data['followup_date'] = $request->followup_date;
            }

            if ($request->has('followup_notes')) {

                $data['followup_notes'] = $request->followup_notes;
            }

            if (!empty($data)) {

                $prescription->update($data);
            }

            /*
            |--------------------------------------------------------------------------
            | Replace Medicines
            |--------------------------------------------------------------------------
            |
            | If medicines is not sent:
            | Existing medicines remain unchanged.
            |
            | If medicines = []:
            | Existing medicines will be removed.
            |
            */

            if ($request->has('medicines')) {

                PrescriptionMedicine::where(
                    'prescription_id',
                    $prescription->id
                )->delete();

                foreach ($request->medicines ?? [] as $medicine) {

                    PrescriptionMedicine::create([

                        'prescription_id' => $prescription->id,

                        'medicine_name' => $medicine['medicine_name'],

                        'dosage' => $medicine['dosage'] ?? null,

                        'frequency' => $medicine['frequency'] ?? null,

                        'duration' => $medicine['duration'] ?? null,

                        'route' => $medicine['route'] ?? null,

                        'morning' => $medicine['morning'] ?? false,

                        'afternoon' => $medicine['afternoon'] ?? false,

                        'night' => $medicine['night'] ?? false,

                        'timing' => $medicine['timing'] ?? null,

                        'instructions' => $medicine['instructions'] ?? null,

                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Replace Recommended Lab Tests
            |--------------------------------------------------------------------------
            |
            | If lab_tests is not sent:
            | Existing tests remain unchanged.
            |
            | If lab_tests = []:
            | Existing tests will be removed.
            |
            */

            if ($request->has('lab_tests')) {

                PrescriptionLabTest::where(
                    'prescription_id',
                    $prescription->id
                )->delete();

                foreach ($request->lab_tests ?? [] as $test) {

                    PrescriptionLabTest::create([

                        'prescription_id' => $prescription->id,

                        'lab_test_id' => $test['lab_test_id'],

                        'instructions' => $test['instructions'] ?? null,

                    ]);
                }
            }

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | Load Updated Prescription
            |--------------------------------------------------------------------------
            */

            $prescription->load([

                'medicines',

                'recommendedLabTests.labTest',

                'appointment',

                'customer',

                'familyMember'

            ]);

            return response()->json([
                'success' => 1,
                'message' => 'Prescription updated successfully.',
                'data' => $prescription
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => 0,
                'message' => 'Something went wrong while updating prescription.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Prescription Details
    |--------------------------------------------------------------------------
    */

    public function prescriptionDetails($appointmentId)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Appointment
        |--------------------------------------------------------------------------
        */

        $appointment = DoctorAppointment::where('id', $appointmentId)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$appointment) {
            return response()->json([
                'success' => 0,
                'message' => 'Appointment not found.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Get Prescription
        |--------------------------------------------------------------------------
        */

        $prescription = Prescription::with([
            'doctor',
            'medicines',
            'recommendedLabTests.labTest',
            'appointment',
            'customer',
            'familyMember'
        ])
            ->where('appointment_id', $appointment->id)
            ->where('doctor_id', $doctor->id)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Check Prescription
        |--------------------------------------------------------------------------
        */

        if ($prescription->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'Prescription not found.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => 1,
            'data' => new PrescriptionCollection($prescription),
            'message' => 'Prescription fetched successfully.'
        ]);
    }
}