<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctor extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

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

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class, 'doctor_id');
    }


}