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
    ];
    // Customer.php

    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class, 'customer_id');
    }
}
