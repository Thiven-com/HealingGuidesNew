<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = [
        'appointment_id',
        'sender_type',
        'sender_id',
        'message',
        'message_type',
        'attachment_url',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function appointment()
    {
        return $this->belongsTo(
            DoctorAppointment::class,
            'appointment_id'
        );
    }
}