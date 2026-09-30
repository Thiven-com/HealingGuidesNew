<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HealthCheckupPackageTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'health_checkup_package_id',
        'health_checkup_test_id',
        'display_order',
    ];

    protected $casts = [
        'health_checkup_package_id' => 'integer',
        'health_checkup_test_id' => 'integer',
        'display_order' => 'integer',
    ];


    /*
    |--------------------------------------------------------------------------
    | Package
    |--------------------------------------------------------------------------
    */

    public function package()
    {
        return $this->belongsTo(
            HealthCheckupPackage::class,
            'health_checkup_package_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Test
    |--------------------------------------------------------------------------
    */

    public function test()
    {
        return $this->belongsTo(
            HealthCheckupTest::class,
            'health_checkup_test_id'
        );
    }
}