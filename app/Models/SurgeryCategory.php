<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurgeryCategory extends Model
{
    protected $table = 'surgery_categories';

    protected $fillable = [
        'image',
        'name',
        'slug',
        'description',
    ];
}
