<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthInsurancePlan extends Model
{
    protected $table = 'health_insurance_plans';

    protected $fillable = [
        'health_insurance_provider_id',
        'name',
        'image',
        'slug',
        'description',
        'policy_term_years',
        'actual_price',
        'price',
        'discount_price',
        'tax_price',
        'minimum_entry_age',
        'maximum_entry_age',
        'key_benefits',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'discount_price' => 'decimal:2',
            'tax_price' => 'decimal:2',
            'status' => 'boolean',
        ];
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(
            HealthInsuranceProvider::class,
            'health_insurance_provider_id'
        );
    }
}
