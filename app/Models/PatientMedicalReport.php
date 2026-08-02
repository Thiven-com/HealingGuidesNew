<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientMedicalReport extends Model
{
    protected $fillable = [

        'customer_id',
        'family_member_id',
        'appointment_id',
        'doctor_id',

        'report_type',
        'report_name',
        'report_file',
        'report_date',

        'notes',

    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function familyMember()
    {
        return $this->belongsTo(FamilyMember::class);
    }

    public function appointment()
    {
        return $this->belongsTo(
            DoctorAppointment::class,
            'appointment_id'
        );
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}