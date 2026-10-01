<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HealthInsuranceProvider extends Model
{
    protected $fillable = [
        'id',
        'name',
        'slug',
        'logo',
        'type',
        'description',
        'website_url',
        'support_email',
        'support_phone',
        'display_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'display_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($provider) {

            if (empty($provider->slug) && !empty($provider->name)) {
                $provider->slug = Str::slug($provider->name);
            }
        });

        static::updating(function ($provider) {

            if (empty($provider->slug) && !empty($provider->name)) {
                $provider->slug = Str::slug($provider->name);
            }
        });
    }
}