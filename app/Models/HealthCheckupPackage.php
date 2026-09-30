<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HealthCheckupPackage extends Model
{
    use HasFactory;

    protected $fillable = [

        'health_checkup_id',

        'name',
        'slug',
        'image',

        'short_description',
        'description',

        'total_tests',

        'mrp',
        'price',
        'discount_percentage',

        'home_collection',
        'centre_collection',

        'report_delivery',

        'fasting_required',
        'preparation_instructions',

        'display_order',
        'status',
    ];

    protected $casts = [
        'mrp' => 'decimal:2',
        'price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',

        'home_collection' => 'boolean',
        'centre_collection' => 'boolean',
        'fasting_required' => 'boolean',

        'total_tests' => 'integer',
        'display_order' => 'integer',
        'status' => 'boolean',
    ];


    /*
    |--------------------------------------------------------------------------
    | Health Checkup
    |--------------------------------------------------------------------------
    */

    public function healthCheckup()
    {
        return $this->belongsTo(
            HealthCheckup::class,
            'health_checkup_id'
        );
    }

    public function tests()
    {
        return $this->belongsToMany(
            HealthCheckupTest::class,
            'health_checkup_package_tests',
            'health_checkup_package_id',
            'health_checkup_test_id'
        )
            ->withPivot('display_order')
            ->orderBy('display_order');
    }
}