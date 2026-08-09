<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotSession extends Model
{
    protected $fillable = [
        'conversation_id',
        'customer_id',
        'intent',
        'current_step',
        'symptoms',
        'answers',
        'selected_service',
        'selected_id',
        'status',
    ];

    protected $casts = [
        'symptoms' => 'array',
        'answers' => 'array',
    ];

    public function conversation()
    {
        return $this->belongsTo(
            ChatbotConversation::class,
            'conversation_id'
        );
    }
}