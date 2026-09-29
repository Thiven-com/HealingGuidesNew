<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    //
    protected $fillable = [
        'name',
        'email',
        'alternate_mobile',
        'gender',
        'dob',
        'age',
        'blood_group',
        'height',
        'weight',
        'occupation',
        'aadhaar_no',
        'address',
        'country',
        'state',
        'city',
        'pincode',
        'emergency_contact_name',
        'emergency_contact_mobile',
        'package_id',
        'package_start_date',
        'package_expiry_date',
    ];
    // Customer.php

    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class, 'customer_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function packageBenefits()
    {
        return $this->hasMany(CustomerPackageBenefit::class);
    }
}
