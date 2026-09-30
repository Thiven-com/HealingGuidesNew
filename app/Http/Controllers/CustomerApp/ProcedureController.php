<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\Procedure;
use Illuminate\Http\Request;

class ProcedureController extends Controller
{
    //
    public function procedures(Request $request)
    {
        $procedures = Procedure::with([
            'specialization',
            'doctors.hospital',
            'doctors.hospitalSpecialization.specialization',
        ])
            ->where('status', 1)
            ->orderBy('display_order');
        if (!empty($request->id)) {
            $procedures = $procedures->where('id', $request->id);
        }
        $procedures = $procedures->get();

        return response()->json([
            'success' => 1,
            'message' => 'Procedures fetched successfully.',
            'data' => $procedures->map(function ($procedure) {

                return [
                    'id' => $procedure->id,
                    'name' => $procedure->name,
                    'slug' => $procedure->slug,

                    'short_description' =>
                        $procedure->short_description,

                    'description' =>
                        $procedure->description,

                    'about' =>
                        $procedure->about,

                    'duration' =>
                        $procedure->duration,

                    'hospital_stay' =>
                        $procedure->hospital_stay,

                    'recovery' =>
                        $procedure->recovery,

                    'price' =>
                        $procedure->price,

                    'image' => $procedure->image
                        ? asset($procedure->image)
                        : null,
                    'banner' => $procedure->banner
                        ? asset($procedure->banner)
                        : null,

                    'youtube_video' =>
                        $procedure->youtube_video,
                    'icon' =>
                        $procedure->icon,

                    'specialization' => [
                        'id' =>
                            optional($procedure->specialization)->id,

                        'name' =>
                            optional($procedure->specialization)
                                ->specialization_name,
                    ],

                    /*
                    |--------------------------------------------------------------------------
                    | Procedure Benefits
                    |--------------------------------------------------------------------------
                    */

                    'benefits' => $procedure->benefits
                        ->where('status', 1)
                        ->map(function ($benefit) {

                            return [
                                'id' =>
                                    $benefit->id,

                                'title' =>
                                    $benefit->title,

                                'description' =>
                                    $benefit->description,

                                'icon' =>
                                    $benefit->icon,
                            ];

                        })->values(),

                    /*
                    |--------------------------------------------------------------------------
                    | Doctors
                    |--------------------------------------------------------------------------
                    */

                    'doctors' => $procedure->doctors
                        ->where('status', 1)
                        ->map(function ($doctor) {

                            return [
                                'id' =>
                                    $doctor->id,

                                'doctor_name' =>
                                    $doctor->doctor_name,

                                'doctor_code' =>
                                    $doctor->doctor_code,

                                'slug' =>
                                    $doctor->slug,

                                'qualification' =>
                                    $doctor->qualification,

                                'designation' =>
                                    $doctor->designation,

                                'experience' =>
                                    $doctor->experience,

                                'consultation_fee' =>
                                    $doctor->consultation_fee,

                                'actual_fee' =>
                                    $doctor->actual_fee,

                                'video_consultation_fee' =>
                                    $doctor->video_consultation_fee,

                                'actual_video_consultation_fee' =>
                                    $doctor->actual_video_consultation_fee,

                                'chat_consultation_fee' =>
                                    $doctor->chat_consultation_fee,

                                'actual_chat_consultation_fee' =>
                                    $doctor->actual_chat_consultation_fee,

                                'home_visit_fee' =>
                                    $doctor->home_visit_fee,

                                'actual_home_visit_fee' =>
                                    $doctor->actual_home_visit_fee,

                                'consultation_enabled' =>
                                    $doctor->consultation_enabled ? 1 : 0,

                                'video_consultation_enabled' =>
                                    $doctor->video_consultation_enabled ? 1 : 0,

                                'chat_consultation_enabled' =>
                                    $doctor->chat_consultation_enabled ? 1 : 0,

                                'home_visit_enabled' =>
                                    $doctor->home_visit_enabled ? 1 : 0,

                                'email' =>
                                    $doctor->email,

                                'mobile' =>
                                    $doctor->mobile,

                                'gender' =>
                                    $doctor->gender,

                                'photo' => $doctor->photo
                                    ? asset($doctor->photo)
                                    : null,

                                'hospital' => [
                                    'id' =>
                                        optional($doctor->hospital)->id,

                                    'hospital_name' =>
                                        optional($doctor->hospital)
                                            ->hospital_name,

                                    'hospital_code' =>
                                        optional($doctor->hospital)
                                            ->hospital_code,
                                ],

                                'specialization' => [
                                    'id' =>
                                        optional(
                                            $doctor->hospitalSpecialization
                                        )->id,

                                    'specialization_id' =>
                                        optional(
                                            optional(
                                                $doctor->hospitalSpecialization
                                            )->specialization
                                        )->id,

                                    'specialization_name' =>
                                        optional(
                                            optional(
                                                $doctor->hospitalSpecialization
                                            )->specialization
                                        )->specialization_name,
                                ],
                            ];

                        })->values(),
                ];
            }),
        ]);
    }
}
