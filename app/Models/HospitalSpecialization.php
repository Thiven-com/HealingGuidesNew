<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HospitalSpecialization extends Model
{
    //
    protected $fillable = [
        'hospital_id',
        'specialization_id',
        'status',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }
}
