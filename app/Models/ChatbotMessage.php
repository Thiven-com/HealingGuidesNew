<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotMessage extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender',
        'message',
        'message_type',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function conversation()
    {
        return $this->belongsTo(
            ChatbotConversation::class,
            'conversation_id'
        );
    }

    public function options()
    {
        return $this->hasMany(
            ChatbotMessageOption::class,
            'message_id'
        )->orderBy('sort_order');
    }
}