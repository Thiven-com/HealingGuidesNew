<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotFaq extends Model
{
    protected $fillable = [
        'question',
        'answer',
        'keywords',
        'category',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'keywords' => 'array',
        'status' => 'boolean',
    ];
}