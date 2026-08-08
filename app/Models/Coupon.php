<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | Owner
        |--------------------------------------------------------------------------
        */

        'created_by_type',
        'created_by_id',

        /*
        |--------------------------------------------------------------------------
        | Hospital
        |--------------------------------------------------------------------------
        */

        'hospital_id',

        /*
        |--------------------------------------------------------------------------
        | Coupon
        |--------------------------------------------------------------------------
        */

        'code',
        'title',
        'description',
        'coupon_type',

        /*
        |--------------------------------------------------------------------------
        | Discount
        |--------------------------------------------------------------------------
        */

        'discount_type',
        'discount_value',
        'max_discount',
        'min_order_amount',

        /*
        |--------------------------------------------------------------------------
        | Applicable To
        |--------------------------------------------------------------------------
        */

        'applicable_to',

        /*
        |--------------------------------------------------------------------------
        | Customer Eligibility
        |--------------------------------------------------------------------------
        */

        'new_customer_only',
        'first_appointment_only',
        'free_appointment',

        /*
        |--------------------------------------------------------------------------
        | Usage
        |--------------------------------------------------------------------------
        */

        'usage_limit',
        'usage_per_customer',
        'used_count',

        /*
        |--------------------------------------------------------------------------
        | Validity
        |--------------------------------------------------------------------------
        */

        'starts_at',
        'expires_at',

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        'status',
    ];


    protected $casts = [

        'discount_value' => 'decimal:2',

        'max_discount' => 'decimal:2',

        'min_order_amount' => 'decimal:2',

        'new_customer_only' => 'boolean',

        'first_appointment_only' => 'boolean',

        'free_appointment' => 'boolean',

        'status' => 'boolean',

        'starts_at' => 'datetime',

        'expires_at' => 'datetime',

    ];


    /*
    |--------------------------------------------------------------------------
    | Hospital
    |--------------------------------------------------------------------------
    */

    public function hospital()
    {
        return $this->belongsTo(
            Hospital::class,
            'hospital_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    |
    | Since created_by can be Admin or Hospital,
    | don't create a normal belongsTo relationship here.
    |
    */


    /*
    |--------------------------------------------------------------------------
    | Coupon Usages
    |--------------------------------------------------------------------------
    */

    public function usages()
    {
        return $this->hasMany(
            CouponUsage::class,
            'coupon_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Scope - Active
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }


    /*
    |--------------------------------------------------------------------------
    | Scope - Hospital
    |--------------------------------------------------------------------------
    */

    public function scopeForHospital(
        $query,
        $hospitalId
    ) {
        return $query->where(function ($q) use ($hospitalId) {

            $q->whereNull('hospital_id')
                ->orWhere('hospital_id', $hospitalId);

        });
    }


    /*
    |--------------------------------------------------------------------------
    | Check Currently Valid
    |--------------------------------------------------------------------------
    */

    public function isValid(): bool
    {
        $now = Carbon::now();

        if (!$this->status) {
            return false;
        }

        if (
            $this->starts_at &&
            $now->lt($this->starts_at)
        ) {
            return false;
        }

        if (
            $this->expires_at &&
            $now->gt($this->expires_at)
        ) {
            return false;
        }

        if (
            $this->usage_limit !== null &&
            $this->used_count >= $this->usage_limit
        ) {
            return false;
        }

        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | Check Minimum Amount
    |--------------------------------------------------------------------------
    */

    public function meetsMinimumAmount(
        $amount
    ): bool {
        return (float) $amount >=
            (float) $this->min_order_amount;
    }


    /*
    |--------------------------------------------------------------------------
    | Calculate Discount
    |--------------------------------------------------------------------------
    */

    public function calculateDiscount(
        float $amount
    ): float {

        if (!$this->isValid()) {
            return 0;
        }

        if (
            !$this->meetsMinimumAmount($amount)
        ) {
            return 0;
        }


        /*
        |--------------------------------------------------------------------------
        | Free
        |--------------------------------------------------------------------------
        */

        if (
            $this->discount_type === 'free' ||
            $this->free_appointment
        ) {
            return round($amount, 2);
        }


        /*
        |--------------------------------------------------------------------------
        | Fixed
        |--------------------------------------------------------------------------
        */

        if (
            $this->discount_type === 'fixed'
        ) {

            return min(
                (float) $this->discount_value,
                $amount
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Percentage
        |--------------------------------------------------------------------------
        */

        if (
            $this->discount_type === 'percentage'
        ) {

            $discount =
                ($amount *
                    (float) $this->discount_value)
                / 100;


            if (
                $this->max_discount !== null
            ) {

                $discount = min(
                    $discount,
                    (float) $this->max_discount
                );
            }


            return min(
                round($discount, 2),
                $amount
            );
        }


        return 0;
    }
}