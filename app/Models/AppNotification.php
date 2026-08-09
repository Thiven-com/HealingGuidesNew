<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    use HasFactory;

    protected $table = 'app_notifications';

    protected $fillable = [
        'notifiable_type',
        'notifiable_id',

        'type',
        'title',
        'message',

        'reference_type',
        'reference_id',

        'action',
        'data',

        'is_read',
        'read_at',

        'status',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'status' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function notifiable()
    {
        return $this->morphTo();
    }
}