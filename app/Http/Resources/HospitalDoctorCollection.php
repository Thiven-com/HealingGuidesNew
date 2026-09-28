<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HospitalDoctorCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [

            'doctors' => $this->collection->map(function ($doctor) {

                /*
                  |--------------------------------------------------------------------------
                  | Consultation Fee Discount
                  |--------------------------------------------------------------------------
                  */

                $actualFee = (float) ($doctor->actual_fee ?? 0);
                $consultationFee = (float) ($doctor->consultation_fee ?? 0);

                $discountPercentage = 0;

                if ($actualFee > 0 && $consultationFee < $actualFee) {
                    $discountPercentage =
                        (($actualFee - $consultationFee) / $actualFee) * 100;
                }


                /*
                |--------------------------------------------------------------------------
                | Video Consultation Fee Discount
                |--------------------------------------------------------------------------
                */

                $actualVideoFee = (float) ($doctor->actual_video_consultation_fee ?? 0);
                $videoConsultationFee = (float) ($doctor->video_consultation_fee ?? 0);

                $videoDiscountPercentage = 0;

                if ($actualVideoFee > 0 && $videoConsultationFee < $actualVideoFee) {
                    $videoDiscountPercentage =
                        (($actualVideoFee - $videoConsultationFee) / $actualVideoFee) * 100;
                }


                /*
                |--------------------------------------------------------------------------
                | Chat Consultation Fee Discount
                |--------------------------------------------------------------------------
                */

                $actualChatFee = (float) ($doctor->actual_chat_consultation_fee ?? 0);
                $chatConsultationFee = (float) ($doctor->chat_consultation_fee ?? 0);

                $chatDiscountPercentage = 0;

                if ($actualChatFee > 0 && $chatConsultationFee < $actualChatFee) {
                    $chatDiscountPercentage =
                        (($actualChatFee - $chatConsultationFee) / $actualChatFee) * 100;
                }


                /*
                |--------------------------------------------------------------------------
                | Home Visit Fee Discount
                |--------------------------------------------------------------------------
                */

                $actualHomeVisitFee = (float) ($doctor->actual_home_visit_fee ?? 0);
                $homeVisitFee = (float) ($doctor->home_visit_fee ?? 0);

                $homeVisitDiscountPercentage = 0;

                if ($actualHomeVisitFee > 0 && $homeVisitFee < $actualHomeVisitFee) {
                    $homeVisitDiscountPercentage =
                        (($actualHomeVisitFee - $homeVisitFee) / $actualHomeVisitFee) * 100;
                }


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

                    'preferred_language' =>
                        $doctor->preferred_language,

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

                    'actual_fee' =>
                        $doctor->actual_fee,

                    'discount_percentage' =>
                        round($discountPercentage, 2),

                    'video_consultation_fee' =>
                        $doctor->video_consultation_fee,

                    'actual_video_consultation_fee' =>
                        $doctor->actual_video_consultation_fee,

                    'video_discount_percentage' =>
                        round($videoDiscountPercentage, 2),

                    'chat_consultation_fee' =>
                        $doctor->chat_consultation_fee,

                    'actual_chat_consultation_fee' =>
                        $doctor->actual_chat_consultation_fee,

                    'chat_discount_percentage' =>
                        round($chatDiscountPercentage, 2),

                    'home_visit_fee' =>
                        $doctor->home_visit_fee,

                    'actual_home_visit_fee' =>
                        $doctor->actual_home_visit_fee,

                    'home_visit_discount_percentage' =>
                        round($homeVisitDiscountPercentage, 2),

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
                        Carbon::parse($doctor->available_from)->format('H:i'),

                    'available_to' =>
                        Carbon::parse($doctor->available_to)->format('H:i'),
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