<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookAdmission extends Model
{
    use HasFactory;

    protected $table = 'book_admissions';

    protected $fillable = [
        'customer_id',
        'family_member_id',
        'admission_type',
        'preferred_admission_date',
        'surgery_procedure',
        'preferred_doctor_id',
        'additional_information',
        'status',
    ];

    protected $casts = [
        'preferred_admission_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function familyMember()
    {
        return $this->belongsTo(FamilyMember::class, 'family_member_id');
    }

    public function preferredDoctor()
    {
        return $this->belongsTo(Doctor::class, 'preferred_doctor_id');
    }
}