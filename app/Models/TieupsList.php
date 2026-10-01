<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TieupsList extends Model
{
    protected $table = 'tieups_lists';

    protected $fillable = [
        'hospital_id',
        'hospital_tieups_id',
        'title',
        'image',
        'description',
        'health_insurance_providers_id',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }
}
