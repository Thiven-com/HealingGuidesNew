<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [

        'hospital_id',
        'hospital_specialization_id',
        'doctor_name',
        'slug',
        'doctor_code',
        'qualification',
        'designation',
        'experience',
        'consultation_fee',
        'video_consultation_fee',
        'chat_consultation_fee',
        'home_visit_fee',
        'email',
        'mobile',
        'dob',
        'gender',
        'blood_group',
        'photo',
        'address',
        'about',
        'available_from',
        'available_to',
        'status'

    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function hospitalSpecialization()
    {
        return $this->belongsTo(HospitalSpecialization::class);
    }


}