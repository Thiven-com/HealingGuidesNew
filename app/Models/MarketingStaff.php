<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MarketingStaff extends Authenticatable
{
    use HasApiTokens, Notifiable;
    //
    protected $fillable = [
        'name',
        'employee_code',
        'mobile',
        'email',
        'password',
        'otp',
        'dob',
        'gender',
        'photo',
        'address',
        'country',
        'state',
        'city',
        'pincode',
        'designation',
        'joining_date',
        'latitude',
        'longitude',
        'is_available',
        'last_login_at',
        'status',
    ];
}
