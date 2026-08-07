<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PatientVitalCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($vital) {

            return [

                /*
                |--------------------------------------------------------------------------
                | Vital
                |--------------------------------------------------------------------------
                */

                'id' => $vital->id,

                'appointment_id' => $vital->appointment_id,

                'doctor_id' => $vital->doctor_id,

                'customer_id' => $vital->customer_id,

                'family_member_id' => $vital->family_member_id,

                /*
                |--------------------------------------------------------------------------
                | Patient Vitals
                |--------------------------------------------------------------------------
                */

                'blood_pressure' =>
                    $vital->blood_pressure,

                'blood_sugar' =>
                    $vital->blood_sugar,

                'blood_sugar_type' =>
                    $vital->blood_sugar_type,

                'height' =>
                    $vital->height,

                'weight' =>
                    $vital->weight,

                'bmi' =>
                    $vital->bmi,

                'temperature' =>
                    $vital->temperature,

                'pulse_rate' =>
                    $vital->pulse_rate,

                'spo2' =>
                    $vital->spo2,

                'notes' =>
                    $vital->notes,

                /*
                |--------------------------------------------------------------------------
                | Doctor
                |--------------------------------------------------------------------------
                */

                'doctor' => $vital->doctor ? [

                    'id' =>
                        $vital->doctor->id,

                    'doctor_name' =>
                        $vital->doctor->doctor_name,

                    'doctor_code' =>
                        $vital->doctor->doctor_code,

                    'qualification' =>
                        $vital->doctor->qualification,

                    'designation' =>
                        $vital->doctor->designation,

                    'experience' =>
                        $vital->doctor->experience,

                    'photo' =>
                        $vital->doctor->photo
                        ? asset($vital->doctor->photo)
                        : null,

                ] : null,

                /*
                |--------------------------------------------------------------------------
                | Appointment
                |--------------------------------------------------------------------------
                */

                'appointment' => $vital->appointment ? [

                    'id' =>
                        $vital->appointment->id,

                    'appointment_no' =>
                        $vital->appointment->appointment_no,

                    'appointment_date' =>
                        $vital->appointment->appointment_date,

                    'appointment_time' =>
                        $vital->appointment->appointment_time,

                    'consultation_type' =>
                        $vital->appointment->consultation_type,

                    'appointment_status' =>
                        $vital->appointment->appointment_status,

                ] : null,

                /*
                |--------------------------------------------------------------------------
                | Family Member
                |--------------------------------------------------------------------------
                */

                'family_member' => $vital->familyMember ? [

                    'id' =>
                        $vital->familyMember->id,

                    'name' =>
                        $vital->familyMember->name,
                    'mobile' =>
                        $vital->familyMember->mobile,

                    'relation' =>
                        $vital->familyMember->relation,

                    'gender' =>
                        $vital->familyMember->gender,

                    'dob' =>
                        $vital->familyMember->dob,
                    'age' => $vital->familyMember->dob
                        ? Carbon::parse($vital->familyMember->dob)->age
                        : null,

                ] : null,

                /*
                |--------------------------------------------------------------------------
                | Created / Updated
                |--------------------------------------------------------------------------
                */

                'created_at' =>
                    $vital->created_at
                    ? $vital->created_at->format(
                        'Y-m-d H:i:s'
                    )
                    : null,

                'updated_at' =>
                    $vital->updated_at
                    ? $vital->updated_at->format(
                        'Y-m-d H:i:s'
                    )
                    : null,

            ];

        })->values()->all();
    }
}