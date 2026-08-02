<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosticBooking extends Model
{
    protected $fillable = [

        'booking_no',

        'customer_id',

        'family_member_id',

        'diagnostic_id',

        'collection_type',

        'booking_date',

        'booking_time',

        'subtotal',

        'home_collection_charge',

        'discount',

        'tax',

        'total_amount',

        'payment_method',

        'transaction_id',

        'payment_id',

        'payment_status',

        'booking_status',

        'address',

        'city',

        'state',

        'pincode',

        'latitude',

        'longitude',

        'notes',

        'cancel_reason',

        'cancelled_at',
    ];

    protected $casts = [

        'booking_date' => 'date',

        'cancelled_at' => 'datetime',

        'subtotal' => 'decimal:2',

        'home_collection_charge' => 'decimal:2',

        'discount' => 'decimal:2',

        'tax' => 'decimal:2',

        'total_amount' => 'decimal:2',
    ];

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
    | Family Member
    |--------------------------------------------------------------------------
    */

    public function familyMember()
    {
        return $this->belongsTo(
            FamilyMember::class,
            'family_member_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Diagnostic
    |--------------------------------------------------------------------------
    */

    public function diagnostic()
    {
        return $this->belongsTo(
            Diagnostic::class,
            'diagnostic_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Booking Items
    |--------------------------------------------------------------------------
    */

    public function items()
    {
        return $this->hasMany(
            DiagnosticBookingItem::class,
            'diagnostic_booking_id'
        );
    }
}