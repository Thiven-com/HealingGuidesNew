<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'customer_id',
        'address',
        'name',
        'mobile',
        'email',
        'house_no',
        'city',
        'state',
        'postal_code',
        'country',
        'landmark',
        'latitude',
        'longitude',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}