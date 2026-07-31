<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoRoom extends Model
{
    protected $fillable = [
        'appointment_id',
        'hospital_id',
        'doctor_id',
        'customer_id',

        'room_id',
        'room_name',
        'room_password',

        'offer_sdp',
        'answer_sdp',
        'ice_candidates',

        'status',

        'doctor_online',
        'doctor_joined_at',
        'doctor_left_at',
        'doctor_socket_id',
        'doctor_ip',

        'customer_online',
        'customer_joined_at',
        'customer_left_at',
        'customer_socket_id',
        'customer_ip',

        'started_at',
        'ended_at',
        'duration',

        'is_recording',
        'recording_path',

        'ended_by',
        'end_reason',

        'metadata',
    ];

    protected $casts = [
        'doctor_online' => 'boolean',
        'customer_online' => 'boolean',
        'is_recording' => 'boolean',
        'metadata' => 'array',

        'doctor_joined_at' => 'datetime',
        'doctor_left_at' => 'datetime',

        'customer_joined_at' => 'datetime',
        'customer_left_at' => 'datetime',

        'started_at' => 'datetime',
        'ended_at' => 'datetime',

        'ice_candidates' => 'array',
    ];

    public function appointment()
    {
        return $this->belongsTo(DoctorAppointment::class, 'appointment_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}