<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HealthCheckupTest extends Model
{
    use HasFactory;

    protected $fillable = [

        'name',

        'slug',

        'short_description',

        'description',

        'sample_type',

        'report_time',

        'display_order',

        'status',

    ];


    protected $casts = [

        'display_order' => 'integer',

        'status' => 'boolean',

    ];

    public function packages()
    {
        return $this->belongsToMany(
            HealthCheckupPackage::class,
            'health_checkup_package_tests',
            'health_checkup_test_id',
            'health_checkup_package_id'
        )
            ->withPivot('display_order')
            ->orderBy('display_order');
    }
}