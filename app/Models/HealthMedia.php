<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthMedia extends Model
{
    protected $table = 'health_media';

    protected $fillable = [
        'image',
        'name',
        'description',
        'status',
    ];
}
