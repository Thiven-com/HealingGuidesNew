<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    use HasFactory;

    protected $fillable = [

        'coupon_id',

        'customer_id',

        'hospital_id',

        'appointment_id',

        'medicine_order_id',

        'coupon_code',

        'discount_amount',

        'used_at',

    ];


    protected $casts = [

        'discount_amount' => 'decimal:2',

        'used_at' => 'datetime',

    ];


    /*
    |--------------------------------------------------------------------------
    | Coupon
    |--------------------------------------------------------------------------
    */

    public function coupon()
    {
        return $this->belongsTo(
            Coupon::class,
            'coupon_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Customer
    |--------------------------------------------------------------------------
    */

    public function customer()
    {
        return $this->belongsTo(
            Customer::class,
            'customer_id'
        );
    }


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
    | Appointment
    |--------------------------------------------------------------------------
    */

    public function appointment()
    {
        return $this->belongsTo(
            DoctorAppointment::class,
            'appointment_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Medicine Order
    |--------------------------------------------------------------------------
    */

    public function medicineOrder()
    {
        return $this->belongsTo(
            MedicineOrder::class,
            'medicine_order_id'
        );
    }
}