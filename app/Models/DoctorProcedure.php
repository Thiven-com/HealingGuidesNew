<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorProcedure extends Model
{
    protected $table = 'doctor_procedures';

    protected $fillable = [
        'doctor_id',
        'procedure_id',
    ];


    /*
    |--------------------------------------------------------------------------
    | Doctor
    |--------------------------------------------------------------------------
    */

    public function doctor()
    {
        return $this->belongsTo(
            Doctor::class,
            'doctor_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Procedure
    |--------------------------------------------------------------------------
    */

    public function procedure()
    {
        return $this->belongsTo(
            Procedure::class,
            'procedure_id'
        );
    }
}