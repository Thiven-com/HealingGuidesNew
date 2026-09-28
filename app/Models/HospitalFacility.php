<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HospitalFacility extends Model
{
    protected $fillable = [
        'hospital_id',
        'facility_id',
        'description',
        'short_description',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }
}
