<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'logo',
        'description',
        'website',

        'contact_name',
        'contact_mobile',
        'contact_email',

        'min_amount',
        'max_amount',
        'interest_rate',
        'processing_fee',

        'tenure_min_months',
        'tenure_max_months',

        'cibil_required',
        'medical_finance',

        'hospitalization',
        'surgery',
        'diagnostics',
        'medicines',
        'doctor_consultation',
        'dental_treatment',
        'medical_treatment',

        'status',
        'display_order',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'processing_fee' => 'decimal:2',

        'cibil_required' => 'boolean',
        'medical_finance' => 'boolean',

        'hospitalization' => 'boolean',
        'surgery' => 'boolean',
        'diagnostics' => 'boolean',
        'medicines' => 'boolean',
        'doctor_consultation' => 'boolean',
        'dental_treatment' => 'boolean',
        'medical_treatment' => 'boolean',

        'status' => 'boolean',
    ];

    public function financeRequests()
    {
        return $this->hasMany(
            MedicalFinanceRequest::class,
            'finance_provider_id'
        );
    }
}