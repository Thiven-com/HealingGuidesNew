<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorAppointment extends Model
{
    protected $fillable = [

        'appointment_no',

        'doctor_id',

        'hospital_id',

        'customer_id',

        'family_member_id',

        'doctor_schedule_id',

        'appointment_date',

        'appointment_time',

        'consultation_type',

        'token_no',

        'consultation_fee',

        'discount',

        'tax',

        'total_amount',

        'payment_status',

        'appointment_status',

        'remarks',

        'cancel_reason',

        'cancelled_at'

    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function schedule()
    {
        return $this->belongsTo(DoctorSchedule::class, 'doctor_schedule_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function familyMember()
    {
        return $this->belongsTo(FamilyMember::class);
    }
}