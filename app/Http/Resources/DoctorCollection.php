<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DoctorCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($doctor) {

            return [
                'id' => $doctor->id,
                'doctor_name' => $doctor->doctor_name,
                'doctor_code' => $doctor->doctor_code,
                'slug' => $doctor->slug,
                'qualification' => $doctor->qualification,
                'designation' => $doctor->designation,
                'experience' => $doctor->experience,
                'consultation_fee' => $doctor->consultation_fee,
                'video_consultation_fee' => $doctor->video_consultation_fee,
                'chat_consultation_fee' => $doctor->chat_consultation_fee,
                'home_visit_fee' => $doctor->home_visit_fee,
                'email' => $doctor->email,
                'mobile' => $doctor->mobile,
                'dob' => $doctor->dob,
                'gender' => $doctor->gender,
                'blood_group' => $doctor->blood_group,
                'address' => $doctor->address,
                'about' => $doctor->about,
                'available_from' => $doctor->available_from,
                'available_to' => $doctor->available_to,
                'photo' => $doctor->photo ? asset($doctor->photo) : null,
                'hospital' => [
                    'id' => optional($doctor->hospital)->id,
                    'hospital_name' => optional($doctor->hospital)->hospital_name,
                    'hospital_code' => optional($doctor->hospital)->hospital_code,
                ],
                'specialization' => [
                    'id' => optional($doctor->hospitalSpecialization)->id,
                    'specialization_id' => optional(optional($doctor->hospitalSpecialization)->specialization)->id,
                    'specialization_name' => optional(optional($doctor->hospitalSpecialization)->specialization)->specialization_name,
                ],
                'status' => $doctor->status,
                'rating' => rand(35, 50) / 10,
                'rating_count' => rand(60, 100),
            ];

        })->toArray();
    }
}