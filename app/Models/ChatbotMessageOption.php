<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotMessageOption extends Model
{
    protected $fillable = [
        'message_id',
        'option_id',
        'title',
        'value',
        'action',
        'sort_order',
    ];

    public function message()
    {
        return $this->belongsTo(
            ChatbotMessage::class,
            'message_id'
        );
    }
}