<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientVital extends Model
{
    protected $fillable = [

        'appointment_id',

        'doctor_id',

        'customer_id',

        'family_member_id',

        'blood_pressure',

        'blood_sugar',

        'blood_sugar_type',

        'height',

        'weight',

        'bmi',

        'temperature',

        'pulse_rate',

        'spo2',

        'notes',

    ];

    protected $casts = [

        'blood_sugar' => 'decimal:2',

        'height' => 'decimal:2',

        'weight' => 'decimal:2',

        'bmi' => 'decimal:2',

        'temperature' => 'decimal:2',

    ];

    /*
    |--------------------------------------------------------------------------
    | Appointment
    |--------------------------------------------------------------------------
    */

    public function appointment()
    {
        return $this->belongsTo(
            DoctorAppointment::class,
            'appointment_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Doctor
    |--------------------------------------------------------------------------
    */

    public function doctor()
    {
        return $this->belongsTo(
            Doctor::class,
            'doctor_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Customer
    |--------------------------------------------------------------------------
    */

    public function customer()
    {
        return $this->belongsTo(
            Customer::class,
            'customer_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Family Member
    |--------------------------------------------------------------------------
    */

    public function familyMember()
    {
        return $this->belongsTo(
            FamilyMember::class,
            'family_member_id'
        );
    }
}