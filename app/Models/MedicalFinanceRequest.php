<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalFinanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_no',

        'customer_id',
        'family_member_id',
        'finance_provider_id',
        'name',
        'email',
        'mobile',
        'purpose',

        'requested_amount',
        'tenure_months',

        'interest_rate',
        'processing_fee',

        'approved_amount',
        'disbursed_amount',

        'cibil_status',
        'cibil_score',

        'consent_given',
        'consent_at',

        'status',

        'rejection_reason',
        'admin_notes',

        'submitted_at',
        'approved_at',
        'rejected_at',
        'disbursed_at',
        'completed_at',
    ];

    protected $casts = [
        'requested_amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',
        'disbursed_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'processing_fee' => 'decimal:2',

        'consent_given' => 'boolean',

        'consent_at' => 'datetime',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'disbursed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function familyMember()
    {
        return $this->belongsTo(FamilyMember::class, 'family_member_id');
    }

    public function financeProvider()
    {
        return $this->belongsTo(FinanceProvider::class);
    }
}