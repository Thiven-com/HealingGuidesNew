<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DoctorAppointmentDetailCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($appointment) {

            return [

                'id' => $appointment->id,

                'appointment_no' => $appointment->appointment_no,

                'doctor_id' => $appointment->doctor_id,

                'hospital_id' => $appointment->hospital_id,

                'customer_id' => $appointment->customer_id,

                'family_member_id' => $appointment->family_member_id,

                'doctor_schedule_id' => $appointment->doctor_schedule_id,

                'appointment_date' => $appointment->appointment_date,

                'appointment_time' => $appointment->appointment_time,

                'consultation_type' => $appointment->consultation_type,

                'token_no' => $appointment->token_no,

                'consultation_fee' => $appointment->consultation_fee,

                'discount' => $appointment->discount,

                'tax' => $appointment->tax,

                'total_amount' => $appointment->total_amount,

                'payment_status' => $appointment->payment_status,

                'appointment_status' => $appointment->appointment_status,

                'remarks' => $appointment->remarks,

                'cancel_reason' => $appointment->cancel_reason,

                'cancelled_at' => $appointment->cancelled_at,

                'doctor' => $appointment->doctor ? [

                    'id' => $appointment->doctor->id,

                    'doctor_name' => $appointment->doctor->doctor_name,

                    'doctor_code' => $appointment->doctor->doctor_code,

                    'qualification' => $appointment->doctor->qualification,

                    'designation' => $appointment->doctor->designation,

                    'experience' => $appointment->doctor->experience,

                    'consultation_fee' => $appointment->doctor->consultation_fee,

                    'video_consultation_fee' =>
                        $appointment->doctor->video_consultation_fee,

                    'chat_consultation_fee' =>
                        $appointment->doctor->chat_consultation_fee,

                    'home_visit_fee' =>
                        $appointment->doctor->home_visit_fee,

                    'photo' => $appointment->doctor->photo,

                    'mobile' => $appointment->doctor->mobile,

                    'email' => $appointment->doctor->email,

                    'gender' => $appointment->doctor->gender,

                    'about' => $appointment->doctor->about,

                ] : null,
                'hospital' => $appointment->doctor?->hospital ? [

                    'id' => $appointment->doctor->hospital->id,

                    'hospital_name' =>
                        $appointment->doctor->hospital->hospital_name ?? null,

                ] : null,

                'specialization' =>
                    $appointment->doctor
                        ?->hospitalSpecialization
                            ?->specialization,

                'customer' => $appointment->customer,

                'family_member' => $appointment->familyMember,

                'doctor_schedule' => $appointment->doctorSchedule ? [

                    'id' => $appointment->doctorSchedule->id,

                    'day_of_week' =>
                        $appointment->doctorSchedule->day_of_week,

                    'available_from' =>
                        $appointment->doctorSchedule->available_from,

                    'available_to' =>
                        $appointment->doctorSchedule->available_to,

                    'slot_duration' =>
                        $appointment->doctorSchedule->slot_duration,

                    'consultation_type' =>
                        $appointment->doctorSchedule->consultation_type,

                    'status' =>
                        $appointment->doctorSchedule->status,

                ] : null,

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
                    'medicines' =>
                        $appointment->prescription->medicines
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

                                            'slug' =>
                                                $recommended->labTest->slug,

                                            'image' =>
                                                $recommended->labTest->image,

                                            'description' =>
                                                $recommended->labTest->description,

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
                'medical_reports' =>
                    $appointment->medicalReports
                        ->map(function ($report) {

                            return [

                                'id' => $report->id,

                                'customer_id' =>
                                    $report->customer_id,

                                'family_member_id' =>
                                    $report->family_member_id,

                                'appointment_id' =>
                                    $report->appointment_id,

                                'doctor_id' =>
                                    $report->doctor_id,

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

                'video_room' => $appointment->videoRoom ? [

                    'id' =>
                        $appointment->videoRoom->id,

                    'room_id' =>
                        $appointment->videoRoom->room_id,

                    'status' =>
                        $appointment->videoRoom->status,

                    'doctor_joined_at' =>
                        $appointment->videoRoom->doctor_joined_at,

                    'customer_joined_at' =>
                        $appointment->videoRoom->customer_joined_at,

                    'started_at' =>
                        $appointment->videoRoom->started_at,

                    'ended_at' =>
                        $appointment->videoRoom->ended_at,

                    'duration' =>
                        $appointment->videoRoom->duration,

                ] : null,
                'created_at' => $appointment->created_at,

                'updated_at' => $appointment->updated_at,

            ];
        })->values()->all();
    }
}