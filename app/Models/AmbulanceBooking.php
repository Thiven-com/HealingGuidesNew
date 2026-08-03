<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmbulanceBooking extends Model
{
    protected $fillable = [

        'booking_no',

        'customer_id',

        'family_member_id',

        'hospital_id',

        'ambulance_type_id',

        'ambulance_id',

        // Pickup
        'pickup_address',

        'pickup_city',

        'pickup_state',

        'pickup_pincode',

        'pickup_latitude',

        'pickup_longitude',

        // Destination
        'destination_address',

        'destination_city',

        'destination_state',

        'destination_pincode',

        'destination_latitude',

        'destination_longitude',

        // Emergency
        'is_emergency',

        'emergency_notes',

        // Distance / Fare
        'distance_km',

        'base_amount',

        'price_per_km',

        'distance_amount',

        'extra_charge',

        'discount',

        'tax',

        'total_amount',

        // Payment
        'payment_method',

        'transaction_id',

        'payment_id',

        'payment_status',

        // Booking
        'booking_status',

        'notes',

        'cancel_reason',

        'accepted_at',

        'assigned_at',

        'cancelled_at',

        'completed_at',

    ];

    protected $casts = [

        'is_emergency' => 'boolean',

        'pickup_latitude' => 'decimal:7',

        'pickup_longitude' => 'decimal:7',

        'destination_latitude' => 'decimal:7',

        'destination_longitude' => 'decimal:7',

        'distance_km' => 'decimal:2',

        'base_amount' => 'decimal:2',

        'price_per_km' => 'decimal:2',

        'distance_amount' => 'decimal:2',

        'extra_charge' => 'decimal:2',

        'discount' => 'decimal:2',

        'tax' => 'decimal:2',

        'total_amount' => 'decimal:2',

        'accepted_at' => 'datetime',

        'assigned_at' => 'datetime',

        'cancelled_at' => 'datetime',

        'completed_at' => 'datetime',

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
    | Requested Ambulance Type
    |--------------------------------------------------------------------------
    */

    public function ambulanceType()
    {
        return $this->belongsTo(
            AmbulanceType::class,
            'ambulance_type_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Assigned Ambulance
    |--------------------------------------------------------------------------
    */

    public function ambulance()
    {
        return $this->belongsTo(
            Ambulance::class,
            'ambulance_id'
        );
    }
}