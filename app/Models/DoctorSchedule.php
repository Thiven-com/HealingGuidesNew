<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    //
    protected $fillable = [

        'doctor_id',

        'day_of_week',

        'available_from',

        'available_to',

        'slot_duration',

        'consultation_type',

        'status'

    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
