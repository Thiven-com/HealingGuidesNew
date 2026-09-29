<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPackageBenefit extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'package_id',
        'benefit_type',
        'benefit_name',
        'total_quantity',
        'used_quantity',
    ];

    protected $appends = [
        'remaining_quantity',
    ];

    protected $casts = [
        'total_quantity' => 'integer',
        'used_quantity' => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function getRemainingQuantityAttribute()
    {
        return max(
            0,
            $this->total_quantity - $this->used_quantity
        );
    }
}