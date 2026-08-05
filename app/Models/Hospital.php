<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hospital extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $fillable = [

        'hospital_name',
        'hospital_slug',
        'hospital_code',

        'hospital_type',

        'registration_number',

        'gst_number',

        'pan_number',

        'email',

        'mobile',

        'phone',

        'logo',

        'banner',

        'address',

        'country',

        'state',

        'city',

        'pincode',

        'latitude',

        'longitude',

        'opening_time',

        'closing_time',

        'emergency_available',

        'status'

    ];
    public function hospitalSpecializations()
    {
        return $this->hasMany(HospitalSpecialization::class);
    }
}