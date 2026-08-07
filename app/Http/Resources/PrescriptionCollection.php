<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PrescriptionCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($prescription) {

            return [

                /*
                |--------------------------------------------------------------------------
                | Prescription
                |--------------------------------------------------------------------------
                */

                'id' => $prescription->id,

                'appointment_id' => $prescription->appointment_id,

                'doctor_id' => $prescription->doctor_id,

                'customer_id' => $prescription->customer_id,

                'family_member_id' => $prescription->family_member_id,

                /*
                |--------------------------------------------------------------------------
                | Clinical Details
                |--------------------------------------------------------------------------
                */

                'symptoms' => $prescription->symptoms,

                'diagnosis' => $prescription->diagnosis,

                'clinical_notes' => $prescription->clinical_notes,

                'advice' => $prescription->advice,

                'tests_recommended' => $prescription->tests_recommended,

                /*
                |--------------------------------------------------------------------------
                | Follow Up
                |--------------------------------------------------------------------------
                */

                'followup_date' => $prescription->followup_date
                    ? $prescription->followup_date->format('Y-m-d')
                    : null,

                'followup_notes' => $prescription->followup_notes,

                'status' => (bool) $prescription->status,

                /*
                |--------------------------------------------------------------------------
                | Doctor
                |--------------------------------------------------------------------------
                */

                'doctor' => $prescription->doctor ? [

                    'id' => $prescription->doctor->id,

                    'doctor_name' =>
                        $prescription->doctor->doctor_name,

                    'doctor_code' =>
                        $prescription->doctor->doctor_code,

                    'qualification' =>
                        $prescription->doctor->qualification,

                    'designation' =>
                        $prescription->doctor->designation,

                    'experience' =>
                        $prescription->doctor->experience,

                    'photo' =>
                        $prescription->doctor->photo
                        ? asset($prescription->doctor->photo)
                        : null,

                ] : null,

                /*
                |--------------------------------------------------------------------------
                | Appointment
                |--------------------------------------------------------------------------
                */

                'appointment' => $prescription->appointment ? [

                    'id' =>
                        $prescription->appointment->id,

                    'appointment_no' =>
                        $prescription->appointment->appointment_no,

                    'appointment_date' =>
                        $prescription->appointment->appointment_date,

                    'appointment_time' =>
                        $prescription->appointment->appointment_time,

                    'consultation_type' =>
                        $prescription->appointment->consultation_type,

                    'appointment_status' =>
                        $prescription->appointment->appointment_status,

                ] : null,

                /*
                |--------------------------------------------------------------------------
                | Family Member
                |--------------------------------------------------------------------------
                */

                'family_member' => $prescription->familyMember ? [

                    'id' =>
                        $prescription->familyMember->id,

                    'name' =>
                        $prescription->familyMember->name,
                    'mobile' =>
                        $prescription->familyMember->mobile,

                    'relation' =>
                        $prescription->familyMember->relation,

                    'gender' =>
                        $prescription->familyMember->gender,

                    'dob' =>
                        $prescription->familyMember->dob,
                    'age' => $prescription->familyMember->dob
                        ? Carbon::parse($prescription->familyMember->dob)->age
                        : null,
                ] : null,

                /*
                |--------------------------------------------------------------------------
                | Medicines
                |--------------------------------------------------------------------------
                */

                'medicines' => $prescription->medicines
                    ->map(function ($medicine) {

                        return [

                            'id' =>
                                $medicine->id,

                            'medicine_name' =>
                                $medicine->medicine_name,

                            'dosage' =>
                                $medicine->dosage,

                            'frequency' =>
                                $medicine->frequency,

                            'duration' =>
                                $medicine->duration,

                            /*
                            |--------------------------------------------------------------------------
                            | Morning / Afternoon / Night
                            |--------------------------------------------------------------------------
                            */

                            'morning' =>
                                (bool) ($medicine->morning ?? false),

                            'afternoon' =>
                                (bool) ($medicine->afternoon ?? false),

                            'night' =>
                                (bool) ($medicine->night ?? false),

                            'before_food' =>
                                (bool) ($medicine->before_food ?? false),

                            'after_food' =>
                                (bool) ($medicine->after_food ?? false),

                            'instructions' =>
                                $medicine->instructions,

                        ];

                    })
                    ->values(),

                /*
                |--------------------------------------------------------------------------
                | Recommended Lab Tests
                |--------------------------------------------------------------------------
                */

                'recommended_lab_tests' =>
                    $prescription->recommendedLabTests
                        ->map(function ($recommended) {

                            return [

                                // 'id' =>
                                //     $recommended->id,
            
                                'id' =>
                                    $recommended->lab_test_id,
                                'test_name' =>
                                    $recommended->labTest->test_name ?? null,

                                'notes' =>
                                    $recommended->notes ?? null,

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
                                            $recommended->labTest->image
                                            ? asset(
                                                $recommended->labTest->image
                                            )
                                            : null,

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
                                            (bool) $recommended
                                                ->labTest
                                                ->fasting_required,

                                        'home_collection' =>
                                            (bool) $recommended
                                                ->labTest
                                                ->home_collection,

                                    ]
                                    : null,

                            ];

                        })
                        ->values(),

                /*
                |--------------------------------------------------------------------------
                | Dates
                |--------------------------------------------------------------------------
                */

                'created_at' => $prescription->created_at
                    ? $prescription->created_at->format('Y-m-d H:i:s')
                    : null,

                'updated_at' => $prescription->updated_at
                    ? $prescription->updated_at->format('Y-m-d H:i:s')
                    : null,

            ];

        })->values()->all();
    }
}