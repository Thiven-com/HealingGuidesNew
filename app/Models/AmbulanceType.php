<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmbulanceType extends Model
{
    //
    protected $fillable = [
        'ambulance_type_name',
        'ambulance_type_code',
        'slug',
        'description',
        'image',
        'status'

    ];

    public function ambulances()
    {
        return $this->hasMany(Ambulance::class);
    }
}
