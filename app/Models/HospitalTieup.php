<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HospitalTieup extends Model
{
    protected $table = 'hospital_tieups';

    protected $fillable = [
        'hospital',
        'tieup_id',
    ];
    public function tieup()
    {
        return $this->belongsTo(Tieup::class, 'tieup_id');
    }
    public function hospitalDetail()
    {
        return $this->belongsTo(Hospital::class, 'hospital');
    }
}
