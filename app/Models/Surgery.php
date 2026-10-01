<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surgery extends Model
{
    use HasFactory;

    protected $table = 'surgeries';

    protected $fillable = [
        'name',
        'slug',
        'image',
        'short_description',
        'description',
        'duration',
        'recovery_time',
        'preparation_instructions',
        'post_surgery_care',
        'display_order',
        'status',
    ];

    protected $casts = [
        'mrp' => 'decimal:2',
        'price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'display_order' => 'integer',
        'status' => 'boolean',
    ];
}