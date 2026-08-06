<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HospitalDoctorCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [

            'doctors' => $this->collection->map(function ($doctor) {

                return [

                    /*
                    |--------------------------------------------------------------------------
                    | Basic Details
                    |--------------------------------------------------------------------------
                    */

                    'id' =>
                        $doctor->id,

                    'hospital_id' =>
                        $doctor->hospital_id,

                    'hospital_specialization_id' =>
                        $doctor->hospital_specialization_id,

                    'doctor_name' =>
                        $doctor->doctor_name,

                    'slug' =>
                        $doctor->slug,

                    'doctor_code' =>
                        $doctor->doctor_code,

                    /*
                    |--------------------------------------------------------------------------
                    | Professional Details
                    |--------------------------------------------------------------------------
                    */

                    'qualification' =>
                        $doctor->qualification,

                    'designation' =>
                        $doctor->designation,

                    'experience' =>
                        $doctor->experience,

                    /*
                    |--------------------------------------------------------------------------
                    | Consultation Fees
                    |--------------------------------------------------------------------------
                    */

                    'consultation_fee' =>
                        $doctor->consultation_fee,

                    'video_consultation_fee' =>
                        $doctor->video_consultation_fee,

                    'chat_consultation_fee' =>
                        $doctor->chat_consultation_fee,

                    'home_visit_fee' =>
                        $doctor->home_visit_fee,

                    /*
                    |--------------------------------------------------------------------------
                    | Contact Details
                    |--------------------------------------------------------------------------
                    */

                    'email' =>
                        $doctor->email,

                    'mobile' =>
                        $doctor->mobile,

                    /*
                    |--------------------------------------------------------------------------
                    | Personal Details
                    |--------------------------------------------------------------------------
                    */

                    'dob' =>
                        $doctor->dob,

                    'gender' =>
                        $doctor->gender,

                    'blood_group' =>
                        $doctor->blood_group,

                     'photo' => $doctor->photo ? asset($doctor->photo) : null,

                    'address' =>
                        $doctor->address,

                    'about' =>
                        $doctor->about,

                    /*
                    |--------------------------------------------------------------------------
                    | Availability
                    |--------------------------------------------------------------------------
                    */

                    'available_from' =>
                        $doctor->available_from,

                    'available_to' =>
                        $doctor->available_to,

                    /*
                    |--------------------------------------------------------------------------
                    | Specialization
                    |--------------------------------------------------------------------------
                    */

                    'specialization' =>
                        $doctor->hospitalSpecialization &&
                        $doctor->hospitalSpecialization->specialization
                            ? [

                                'hospital_specialization_id' =>
                                    $doctor->hospitalSpecialization->id,

                                'specialization_id' =>
                                    $doctor->hospitalSpecialization
                                        ->specialization->id,

                                'specialization_name' =>
                                    $doctor->hospitalSpecialization
                                        ->specialization
                                        ->specialization_name,

                            ]
                            : null,

                    /*
                    |--------------------------------------------------------------------------
                    | Hospital
                    |--------------------------------------------------------------------------
                    */

                    'hospital' =>
                        $doctor->hospital
                            ? [

                                'id' =>
                                    $doctor->hospital->id,

                                'hospital_name' =>
                                    $doctor->hospital->hospital_name,

                                'hospital_code' =>
                                    $doctor->hospital->hospital_code,

                                'hospital_type' =>
                                    $doctor->hospital->hospital_type,

                                'logo' =>
                                    $doctor->hospital->logo,

                                'email' =>
                                    $doctor->hospital->email,

                                'mobile' =>
                                    $doctor->hospital->mobile,

                                'address' =>
                                    $doctor->hospital->address,

                                'city' =>
                                    $doctor->hospital->city,

                                'state' =>
                                    $doctor->hospital->state,

                                'pincode' =>
                                    $doctor->hospital->pincode,

                            ]
                            : null,

                    /*
                    |--------------------------------------------------------------------------
                    | Status
                    |--------------------------------------------------------------------------
                    */

                    'status' =>
                        (bool) $doctor->status,

                    /*
                    |--------------------------------------------------------------------------
                    | Dates
                    |--------------------------------------------------------------------------
                    */

                    'created_at' =>
                        $doctor->created_at,

                    'updated_at' =>
                        $doctor->updated_at,
                ];
            }),
        ];
    }
}