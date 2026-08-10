<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'mobile_number',
        'email',
        'date_of_birth',
        'gender',
        'city',
        'membership',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'status' => 'boolean',
    ];
}
