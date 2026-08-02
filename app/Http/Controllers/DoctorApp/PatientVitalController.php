<?php

namespace App\Http\Controllers\DoctorApp;

use App\Http\Controllers\Controller;
use App\Models\DoctorAppointment;
use App\Models\PatientVital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientVitalController extends Controller
{
    //
    public function savePatientVitals(Request $request)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [

            'appointment_id' => 'required|exists:doctor_appointments,id',

            'blood_pressure' => 'nullable|string|max:20',

            'blood_sugar' => 'nullable|numeric|min:0',

            'blood_sugar_type' => 'nullable',

            'height' => 'nullable|numeric|min:0',

            'weight' => 'nullable|numeric|min:0',

            'temperature' => 'nullable|numeric|min:0',

            'pulse_rate' => 'nullable|integer|min:0',

            'spo2' => 'nullable|integer|min:0|max:100',

            'notes' => 'nullable|string',

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
        | BMI Calculation
        |--------------------------------------------------------------------------
        */

        $bmi = null;

        if ($request->filled('height') && $request->filled('weight')) {

            $heightInMeters = $request->height / 100;

            if ($heightInMeters > 0) {

                $bmi = round(
                    $request->weight / ($heightInMeters * $heightInMeters),
                    2
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Save / Update Vitals
        |--------------------------------------------------------------------------
        */

        $vital = PatientVital::updateOrCreate(

            [
                'appointment_id' => $appointment->id
            ],

            [
                'doctor_id' => $doctor->id,

                'customer_id' => $appointment->customer_id,

                'family_member_id' => $appointment->family_member_id,

                'blood_pressure' => $request->blood_pressure,

                'blood_sugar' => $request->blood_sugar,

                'blood_sugar_type' => $request->blood_sugar_type,

                'height' => $request->height,

                'weight' => $request->weight,

                'bmi' => $bmi,

                'temperature' => $request->temperature,

                'pulse_rate' => $request->pulse_rate,

                'spo2' => $request->spo2,

                'notes' => $request->notes,
            ]
        );

        return response()->json([
            'success' => 1,
            'message' => $vital->wasRecentlyCreated
                ? 'Patient vitals saved successfully.'
                : 'Patient vitals updated successfully.',
            'data' => $vital
        ]);
    }

    public function patientVitals($appointmentId)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $appointment = DoctorAppointment::where('id', $appointmentId)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$appointment) {
            return response()->json([
                'success' => 0,
                'message' => 'Appointment not found.'
            ]);
        }

        $vital = PatientVital::where('appointment_id', $appointment->id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$vital) {
            return response()->json([
                'success' => 0,
                'message' => 'Patient vitals not found.'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Patient vitals fetched successfully.',
            'data' => $vital
        ]);
    }
    public function patientVitalsHistory(Request $request, $customerId)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $vitals = PatientVital::with([
            'appointment:id,appointment_no,appointment_date,appointment_time'
        ])
            ->where('doctor_id', $doctor->id)
            ->where('customer_id', $customerId);

        /*
        |--------------------------------------------------------------------------
        | Family Member Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('family_member_id')) {

            $vitals->where(
                'family_member_id',
                $request->family_member_id
            );
        }

        $vitals = $vitals
            ->latest()
            ->get();

        if ($vitals->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'No patient vitals history found.'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Patient vitals history fetched successfully.',
            'data' => $vitals
        ]);
    }
}
