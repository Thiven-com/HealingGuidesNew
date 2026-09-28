<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SpecializationCategory;

class Specialization extends Model
{
    //
    protected $fillable = [

        'specialization_name',

        'specialization_category',

        'slug',

        'icon',

        'image',

        'description',

        'status'

    ];

    public function specializationCategory()
    {
        return $this->belongsTo(
            SpecializationCategory::class,
            'specialization_category'
        );
    }
}
