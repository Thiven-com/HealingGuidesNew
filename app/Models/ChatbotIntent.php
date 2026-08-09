<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotIntent extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'keywords',
        'service',
        'status',
    ];

    protected $casts = [
        'keywords' => 'array',
        'status' => 'boolean',
    ];
}