<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnostic extends Model
{
    //
    protected $fillable = [
        'diagnostic_name',
        'diagnostic_code',
        'slug',
        'registration_number',
        'email',
        'mobile',
        'phone',
        'address',
        'country',
        'state',
        'city',
        'pincode',
        'latitude',
        'longitude',
        'opening_time',
        'closing_time',
        'home_collection',
        'logo',
        'banner',
        'status',
    ];
}
