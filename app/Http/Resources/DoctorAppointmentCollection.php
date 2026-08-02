<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DoctorAppointmentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($appointment) {

            return [

                'id' => $appointment->id,

                'appointment_no' => $appointment->appointment_no,

                'appointment_date' => $appointment->appointment_date,

                'appointment_time' => $appointment->appointment_time,

                'token_no' => $appointment->token_no,

                'consultation_type' => $appointment->consultation_type,

                'consultation_fee' => $appointment->consultation_fee,

                'discount' => $appointment->discount,

                'tax' => $appointment->tax,

                'total_amount' => $appointment->total_amount,

                'payment_status' => $appointment->payment_status,

                'appointment_status' => $appointment->appointment_status,

                'doctor' => [

                    'id' => optional($appointment->doctor)->id,

                    'doctor_name' => optional($appointment->doctor)->doctor_name,

                    'doctor_image' => optional($appointment->doctor)->image,

                    'specialization' => optional(
                        optional($appointment->doctor->hospitalSpecialization)->specialization
                    )->specialization_name,


                    'hospital_name' => optional($appointment->doctor->hospital)->hospital_name,

                    'consultation_fee' => optional($appointment->doctor)->consultation_fee,

                ],

                'family_member' => [

                    'id' => optional($appointment->familyMember)->id,

                    'name' => optional($appointment->familyMember)->name,

                    'relationship' => optional($appointment->familyMember)->relationship,

                    'gender' => optional($appointment->familyMember)->gender,

                    'age' => optional($appointment->familyMember)->age,
                    

                ],

            ];

        })->toArray();
    }
}