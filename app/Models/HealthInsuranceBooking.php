<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthInsuranceBooking extends Model
{
    protected $fillable = [
        'user_id',
        'provider_id',
        'plan_id',
        'booking_reference',
        'customer_name',
        'customer_phone',
        'customer_email',
        'date_of_birth',
        'members_count',
        'notes',
        'price',
        'discount_price',
        'tax_price',
        'payment_method',
        'transaction_id',
        'payment_status',
        'cancel_reason',
        'cancelled_at',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date:Y-m-d',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'tax_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(HealthInsuranceProvider::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(
            HealthInsurancePlan::class,
            'plan_id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
