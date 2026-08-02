<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [

        'appointment_id',

        'doctor_id',

        'customer_id',

        'family_member_id',

        'symptoms',

        'diagnosis',

        'clinical_notes',

        'advice',

        'tests_recommended',

        'followup_date',

        'followup_notes',

        'status',

    ];

    protected $casts = [

        'followup_date' => 'date',

        'status' => 'boolean',

    ];

    public function appointment()
    {
        return $this->belongsTo(
            DoctorAppointment::class,
            'appointment_id'
        );
    }

    public function doctor()
    {
        return $this->belongsTo(
            Doctor::class,
            'doctor_id'
        );
    }

    public function customer()
    {
        return $this->belongsTo(
            Customer::class,
            'customer_id'
        );
    }

    public function familyMember()
    {
        return $this->belongsTo(
            FamilyMember::class,
            'family_member_id'
        );
    }

    public function medicines()
    {
        return $this->hasMany(
            PrescriptionMedicine::class,
            'prescription_id'
        );
    }
    public function recommendedLabTests()
    {
        return $this->hasMany(
            PrescriptionLabTest::class,
            'prescription_id'
        );
    }
}