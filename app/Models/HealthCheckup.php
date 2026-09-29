<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HealthCheckup extends Model
{
    use HasFactory;

    protected $table = 'health_checkups';

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
    ];
}
