<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DoctorAppointmentCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($appointment) {

            $doctor = $appointment->doctor;

            // Get hospital from appointment first, otherwise from doctor
            $hospital = $appointment->hospital ?? $doctor?->hospital;

            $specialization = $doctor?->hospitalSpecialization?->specialization;

            return [

                'id' => $appointment->id,
                'appointment_no' => $appointment->appointment_no,
                'appointment_date' => $appointment->appointment_date,
                'appointment_time' => $appointment->appointment_time,
                'token_no' => $appointment->token_no,

                'consultation_type' => $appointment->consultation_type,
                'consultation_fee' => $appointment->consultation_fee,
                'registration_fee' => $appointment->registration_fee,
                'discount' => $appointment->discount,
                'tax' => $appointment->tax,
                'total_amount' => $appointment->total_amount,

                'payment_status' => $appointment->payment_status,
                'appointment_status' => $appointment->appointment_status,

                'doctor' => [
                    'id' => $doctor?->id,
                    'doctor_name' => $doctor?->doctor_name,
                    'doctor_image' => $doctor?->image,
                    'specialization' => $specialization?->specialization_name,
                    'hospital_name' => $hospital?->hospital_name,
                    'consultation_fee' => $doctor?->consultation_fee,
                ],

                'hospital' => [
                    'id' => $hospital?->id,
                    'hospital_name' => $hospital?->hospital_name,
                    'hospital_image' => $hospital?->logo
                        ? asset($hospital->logo)
                        : null,
                    'address' => $hospital?->address,
                    'city' => $hospital?->city,
                    'state' => $hospital?->state,
                    'pincode' => $hospital?->pincode,
                    'latitude' => $hospital?->latitude,
                    'longitude' => $hospital?->longitude,
                    'specialization' => $specialization?->specialization_name,
                ],

                'family_member' => [
                    'id' => $appointment->familyMember?->id,
                    'name' => $appointment->familyMember?->name,
                    'relationship' => $appointment->familyMember?->relationship,
                    'gender' => $appointment->familyMember?->gender,
                    'age' => $appointment->familyMember?->age,
                ],

            ];

        })->toArray();
    }
}