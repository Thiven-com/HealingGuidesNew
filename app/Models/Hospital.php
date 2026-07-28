<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
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

    public function departments()
    {
        return $this->hasMany(Department::class);
    }
}