<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotConversation extends Model
{
    protected $fillable = [
        'conversation_id',
        'customer_id',
        'title',
        'intent',
        'status',
        'started_at',
        'last_message_at',
        'ended_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'last_message_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function messages()
    {
        return $this->hasMany(
            ChatbotMessage::class,
            'conversation_id'
        );
    }

    public function session()
    {
        return $this->hasOne(
            ChatbotSession::class,
            'conversation_id'
        );
    }

    public function recommendations()
    {
        return $this->hasMany(
            ChatbotRecommendation::class,
            'conversation_id'
        );
    }
}