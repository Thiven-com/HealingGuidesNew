<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageBenefitUsageHistory extends Model
{
    protected $fillable = [

        'customer_id',

        'package_id',

        'package_benefit_id',

        'benefit_type',

        'benefit_name',

        'quantity',

        'reference_type',

        'reference_id',

        'usage_type',

        'notes',

    ];


    /*
    |--------------------------------------------------------------------------
    | Customer
    |--------------------------------------------------------------------------
    */

    public function customer()
    {
        return $this->belongsTo(
            Customer::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Package
    |--------------------------------------------------------------------------
    */

    public function package()
    {
        return $this->belongsTo(
            Package::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Package Benefit
    |--------------------------------------------------------------------------
    */

    public function packageBenefit()
    {
        return $this->belongsTo(
            PackageBenefit::class
        );
    }
}