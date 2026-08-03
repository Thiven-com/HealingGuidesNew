<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineOrder extends Model
{
    protected $fillable = [

        'order_no',

        'customer_id',
        'family_member_id',
        'hospital_id',

        'prescription_id',

        'subtotal',
        'delivery_charge',
        'discount',
        'tax',
        'total_amount',

        'delivery_address',
        'delivery_city',
        'delivery_state',
        'delivery_pincode',
        'delivery_latitude',
        'delivery_longitude',

        'payment_method',
        'transaction_id',
        'payment_id',
        'payment_status',

        'order_status',

        'notes',
        'cancel_reason',

        'accepted_at',
        'rejected_at',
        'cancelled_at',
        'delivered_at',
    ];

    protected $casts = [

        'subtotal' => 'decimal:2',
        'delivery_charge' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',

        'delivery_latitude' => 'decimal:7',
        'delivery_longitude' => 'decimal:7',

        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function familyMember()
    {
        return $this->belongsTo(FamilyMember::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function items()
    {
        return $this->hasMany(
            MedicineOrderItem::class,
            'medicine_order_id'
        );
    }
}