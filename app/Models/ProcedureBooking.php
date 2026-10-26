<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcedureBooking extends Model
{
    protected $fillable = [
        'booking_no',
        'customer_id',
        'family_member_id',
        'procedure_id',
        'doctor_id',
        'hospital_id',
        'booking_date',
        'booking_time',
        'price_type',
        'actual_price',
        'price',
        'discount',
        'tax',
        'total_amount',
        'payment_method',
        'transaction_id',
        'payment_id',
        'payment_status',
        'booking_status',
        'notes',
        'cancel_reason',
        'cancelled_at',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'cancelled_at' => 'datetime',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function procedure()
    {
        return $this->belongsTo(Procedure::class);
    }

    public function familyMember()
    {
        return $this->belongsTo(FamilyMember::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
