<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    //
    protected $fillable = [
        'customer_id',
        'name',
        'mobile',
        'relationship',
        'gender',
        'dob',
        'age',
        'blood_group',
        'height',
        'weight',
        'occupation',
        'aadhaar_no',
        'photo',
        'status',
    ];
}
