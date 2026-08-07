<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ambulance extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $fillable = [

        'ambulance_type_id',

        'hospital_id',

        'ambulance_name',

        'ambulance_code',
        'image',

        'vehicle_number',

        'registration_number',

        'driver_name',

        'driver_mobile',

        'driver_license_number',

        'driver_photo',

        'model',

        'manufacturing_year',

        'current_location',

        'latitude',

        'longitude',

        'base_fare',

        'price_per_km',

        'is_available',

        'status'

    ];

    public function ambulanceType()
    {
        return $this->belongsTo(AmbulanceType::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}