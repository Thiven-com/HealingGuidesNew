<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'payment_no',
        'customer_id',
        'payment_for',
        'reference_id',
        'amount',
        'discount',
        'tax',
        'paid_amount',
        'balance_amount',
        'currency',
        'payment_gateway',
        'payment_method',
        'gateway_order_id',
        'gateway_payment_id',
        'gateway_signature',
        'transaction_id',
        'bank_reference_no',
        'invoice_no',
        'payment_status',
        'failure_reason',
        'paid_at',
        'remarks',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}