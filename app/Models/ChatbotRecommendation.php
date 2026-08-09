<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotRecommendation extends Model
{
    protected $fillable = [
        'conversation_id',
        'customer_id',
        'recommendation_type',
        'reference_id',
        'title',
        'description',
        'metadata',
        'sort_order',
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
}