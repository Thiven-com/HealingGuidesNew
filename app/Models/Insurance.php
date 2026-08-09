<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    use HasFactory;

    protected $table = 'insurances';

    protected $fillable = [
        'customer_id',
        'insurance_provider',
        'policy_number',
        'policy_type',
        'policy_holder_name',
        'member_id',
        'coverage_amount',
        'start_date',
        'expiry_date',
        'document',
        'notes',
        'status',
    ];

    protected $casts = [
        'coverage_amount' => 'decimal:2',
        'start_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}