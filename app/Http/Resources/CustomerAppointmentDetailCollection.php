<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerAppointmentDetailCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($appointment) {

            return [

                /*
                |--------------------------------------------------------------------------
                | Appointment
                |--------------------------------------------------------------------------
                */

                'id' => $appointment->id,

                'appointment_no' => $appointment->appointment_no,

                'appointment_date' => $appointment->appointment_date,

                'appointment_time' => $appointment->appointment_time,

                'consultation_type' => $appointment->consultation_type,

                'token_no' => $appointment->token_no,

                'appointment_status' => $appointment->appointment_status,

                'payment_status' => $appointment->payment_status,

                'remarks' => $appointment->remarks,

                'cancel_reason' => $appointment->cancel_reason,

                'cancelled_at' => $appointment->cancelled_at,


                /*
                |--------------------------------------------------------------------------
                | Amount
                |--------------------------------------------------------------------------
                */

                'consultation_fee' => $appointment->consultation_fee,

                'discount' => $appointment->discount,

                'tax' => $appointment->tax,

                'total_amount' => $appointment->total_amount,


                /*
                |--------------------------------------------------------------------------
                | Doctor
                |--------------------------------------------------------------------------
                */

                'doctor' => $appointment->doctor ? [

                    'id' => $appointment->doctor->id,

                    'doctor_name' =>
                        $appointment->doctor->doctor_name,

                    'doctor_code' =>
                        $appointment->doctor->doctor_code,

                    'qualification' =>
                        $appointment->doctor->qualification,

                    'designation' =>
                        $appointment->doctor->designation,

                    'experience' =>
                        $appointment->doctor->experience,

                    'photo' =>
                        $appointment->doctor->photo
                            ? asset($appointment->doctor->photo)
                            : null,

                    'about' =>
                        $appointment->doctor->about,

                ] : null,


                /*
                |--------------------------------------------------------------------------
                | Hospital
                |--------------------------------------------------------------------------
                */

                'hospital' => $appointment->hospital ? [

                    'id' =>
                        $appointment->hospital->id,

                    'hospital_name' =>
                        $appointment->hospital->hospital_name ?? null,

                    'address' =>
                        $appointment->hospital->address ?? null,

                    'mobile' =>
                        $appointment->hospital->mobile ?? null,

                ] : null,


                /*
                |--------------------------------------------------------------------------
                | Specialization
                |--------------------------------------------------------------------------
                */

                'specialization' =>
                    $appointment->doctor
                        ?->hospitalSpecialization
                        ?->specialization,


                /*
                |--------------------------------------------------------------------------
                | Patient / Family Member
                |--------------------------------------------------------------------------
                */

                'family_member' => $appointment->familyMember,


                /*
                |--------------------------------------------------------------------------
                | Schedule
                |--------------------------------------------------------------------------
                */

                'schedule' => $appointment->schedule ? [

                    'id' =>
                        $appointment->schedule->id,

                    'day_of_week' =>
                        $appointment->schedule->day_of_week,

                    'available_from' =>
                        $appointment->schedule->available_from,

                    'available_to' =>
                        $appointment->schedule->available_to,

                    'slot_duration' =>
                        $appointment->schedule->slot_duration,

                    'consultation_type' =>
                        $appointment->schedule->consultation_type,

                ] : null,


                /*
                |--------------------------------------------------------------------------
                | Patient Vitals
                |--------------------------------------------------------------------------
                */

                'patient_vitals' => $appointment->patientVitals ? [

                    'id' =>
                        $appointment->patientVitals->id,

                    'systolic_bp' =>
                        $appointment->patientVitals->systolic_bp,

                    'diastolic_bp' =>
                        $appointment->patientVitals->diastolic_bp,

                    'blood_sugar' =>
                        $appointment->patientVitals->blood_sugar,

                    'blood_sugar_type' =>
                        $appointment->patientVitals->blood_sugar_type,

                    'height_cm' =>
                        $appointment->patientVitals->height_cm,

                    'weight_kg' =>
                        $appointment->patientVitals->weight_kg,

                    'bmi' =>
                        $appointment->patientVitals->bmi,

                    'temperature' =>
                        $appointment->patientVitals->temperature,

                    'pulse_rate' =>
                        $appointment->patientVitals->pulse_rate,

                    'respiratory_rate' =>
                        $appointment->patientVitals->respiratory_rate,

                    'spo2' =>
                        $appointment->patientVitals->spo2,

                    'notes' =>
                        $appointment->patientVitals->notes,

                    'recorded_at' =>
                        $appointment->patientVitals->recorded_at,

                ] : null,


                /*
                |--------------------------------------------------------------------------
                | Prescription
                |--------------------------------------------------------------------------
                */

                'prescription' => $appointment->prescription ? [

                    'id' =>
                        $appointment->prescription->id,

                    'symptoms' =>
                        $appointment->prescription->symptoms,

                    'diagnosis' =>
                        $appointment->prescription->diagnosis,

                    'clinical_notes' =>
                        $appointment->prescription->clinical_notes,

                    'advice' =>
                        $appointment->prescription->advice,

                    'followup_date' =>
                        $appointment->prescription->followup_date,

                    'followup_notes' =>
                        $appointment->prescription->followup_notes,


                    /*
                    |--------------------------------------------------------------------------
                    | Medicines
                    |--------------------------------------------------------------------------
                    */

                    'medicines' =>
                        $appointment->prescription
                            ->medicines
                            ->map(function ($medicine) {

                                return [

                                    'id' => $medicine->id,

                                    'medicine_name' =>
                                        $medicine->medicine_name,

                                    'dosage' =>
                                        $medicine->dosage,

                                    'frequency' =>
                                        $medicine->frequency,

                                    'duration' =>
                                        $medicine->duration,

                                    'route' =>
                                        $medicine->route,

                                    'morning' =>
                                        (bool) $medicine->morning,

                                    'afternoon' =>
                                        (bool) $medicine->afternoon,

                                    'night' =>
                                        (bool) $medicine->night,

                                    'timing' =>
                                        $medicine->timing,

                                    'instructions' =>
                                        $medicine->instructions,

                                ];
                            }),


                    /*
                    |--------------------------------------------------------------------------
                    | Recommended Lab Tests
                    |--------------------------------------------------------------------------
                    */

                    'recommended_lab_tests' =>
                        $appointment->prescription
                            ->recommendedLabTests
                            ->map(function ($recommended) {

                                return [

                                    'id' =>
                                        $recommended->id,

                                    'lab_test_id' =>
                                        $recommended->lab_test_id,

                                    'instructions' =>
                                        $recommended->instructions,

                                    'lab_test' =>
                                        $recommended->labTest ? [

                                            'id' =>
                                                $recommended->labTest->id,

                                            'test_name' =>
                                                $recommended->labTest->test_name,

                                            'test_code' =>
                                                $recommended->labTest->test_code,

                                            'image' =>
                                                $recommended->labTest->image
                                                    ? asset($recommended->labTest->image)
                                                    : null,

                                            'sample_type' =>
                                                $recommended->labTest->sample_type,

                                            'preparation' =>
                                                $recommended->labTest->preparation,

                                            'report_time' =>
                                                $recommended->labTest->report_time,

                                            'report_time_type' =>
                                                $recommended->labTest->report_time_type,

                                            'fasting_required' =>
                                                $recommended->labTest->fasting_required,

                                            'home_collection' =>
                                                $recommended->labTest->home_collection,

                                        ] : null,

                                ];
                            }),

                ] : null,


                /*
                |--------------------------------------------------------------------------
                | Medical Reports
                |--------------------------------------------------------------------------
                */

                'medical_reports' =>
                    $appointment->medicalReports
                        ->map(function ($report) {

                            return [

                                'id' =>
                                    $report->id,

                                'report_type' =>
                                    $report->report_type,

                                'report_name' =>
                                    $report->report_name,

                                'report_file' =>
                                    $report->report_file
                                        ? asset($report->report_file)
                                        : null,

                                'report_date' =>
                                    $report->report_date,

                                'notes' =>
                                    $report->notes,

                            ];
                        }),


                /*
                |--------------------------------------------------------------------------
                | Video Consultation
                |--------------------------------------------------------------------------
                */

                'video_room' => $appointment->videoRoom ? [

                    'id' =>
                        $appointment->videoRoom->id,

                    'room_id' =>
                        $appointment->videoRoom->room_id,

                    'status' =>
                        $appointment->videoRoom->status,

                    'customer_joined_at' =>
                        $appointment->videoRoom->customer_joined_at,

                    'doctor_joined_at' =>
                        $appointment->videoRoom->doctor_joined_at,

                    'started_at' =>
                        $appointment->videoRoom->started_at,

                    'ended_at' =>
                        $appointment->videoRoom->ended_at,

                    'duration' =>
                        $appointment->videoRoom->duration,

                ] : null,


                /*
                |--------------------------------------------------------------------------
                | Home Visit
                |--------------------------------------------------------------------------
                */

                'home_visit' => [

                    'visit_address' =>
                        $appointment->visit_address,

                    'visit_city' =>
                        $appointment->visit_city,

                    'visit_state' =>
                        $appointment->visit_state,

                    'visit_pincode' =>
                        $appointment->visit_pincode,

                    'visit_latitude' =>
                        $appointment->visit_latitude,

                    'visit_longitude' =>
                        $appointment->visit_longitude,

                    'visit_status' =>
                        $appointment->visit_status,

                ],


                /*
                |--------------------------------------------------------------------------
                | Meeting
                |--------------------------------------------------------------------------
                */

                'meeting' => [

                    'meeting_provider' =>
                        $appointment->meeting_provider,

                    'meeting_id' =>
                        $appointment->meeting_id,

                    'meeting_link' =>
                        $appointment->meeting_link,

                    'meeting_status' =>
                        $appointment->meeting_status,

                ],

                'created_at' => $appointment->created_at,

                'updated_at' => $appointment->updated_at,

            ];

        })->values()->all();
    }
}