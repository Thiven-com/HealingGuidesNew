<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmergencyConnect extends Model
{
    protected $table = 'emergency_connects';

    protected $fillable = [
        'image',
        'title',
        'slug',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
