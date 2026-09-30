<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    protected $fillable = [

        'specialization_id',

        'name',

        'slug',

        'short_description',

        'description',

        'about',

        'duration',

        'hospital_stay',

        'recovery',

        'image',

        'icon',

        'price',

        'display_order',

        'status',
        'banner',
        'youtube_video'

    ];


    protected $casts = [

        'price' => 'decimal:2',

        'status' => 'boolean',

    ];


    /*
    |--------------------------------------------------------------------------
    | Specialization
    |--------------------------------------------------------------------------
    */

    public function specialization()
    {
        return $this->belongsTo(
            Specialization::class,
            'specialization_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Procedure Benefits
    |--------------------------------------------------------------------------
    */

    public function benefits()
    {
        return $this->hasMany(
            ProcedureBenefit::class,
            'procedure_id'
        )
            ->where('status', 1)
            ->orderBy('display_order');
    }


    /*
    |--------------------------------------------------------------------------
    | All Benefits
    |--------------------------------------------------------------------------
    */

    public function allBenefits()
    {
        return $this->hasMany(
            ProcedureBenefit::class,
            'procedure_id'
        )
            ->orderBy('display_order');
    }


    /*
    |--------------------------------------------------------------------------
    | Doctors
    |--------------------------------------------------------------------------
    */

    public function doctors()
    {
        return $this->belongsToMany(
            Doctor::class,
            'doctor_procedures',
            'procedure_id',
            'doctor_id'
        )->withTimestamps();
    }
}