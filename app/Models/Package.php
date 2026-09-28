<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Package extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'duration_days',
        'image',
        'display_order',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'boolean',
        'duration_days' => 'integer',
        'display_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($package) {

            if (empty($package->slug)) {

                $package->slug = Str::slug(
                    $package->name
                );
            }
        });

        static::updating(function ($package) {

            if ($package->isDirty('name')) {

                $package->slug = Str::slug(
                    $package->name
                );
            }
        });
    }

    public function benefits()
    {
        return $this->hasMany(
            PackageBenefit::class
        )->orderBy('display_order');
    }

    public function activeBenefits()
    {
        return $this->hasMany(
            PackageBenefit::class
        )
            ->where('status', 1)
            ->orderBy('display_order');
    }
}