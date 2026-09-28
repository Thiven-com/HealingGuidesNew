<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'hospital_id',
        'name',
        'slug',
        'description',
        'image',
    ];
    public function hospitalFacilities()
    {
        return $this->hasMany(
            HospitalFacility::class,
            'facility_id'
        );
    }

    public function hospitals()
    {
        return $this->belongsToMany(
            Hospital::class,
            'hospital_facilities',
            'facility_id',
            'hospital_id'
        )->withPivot([
                    'description',
                    'short_description',
                ]);
    }
}
