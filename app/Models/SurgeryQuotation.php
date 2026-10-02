<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SurgeryQuotation extends Model
{
    use HasFactory;

    protected $table = 'surgery_quotations';

    protected $fillable = [
        'surgery_quotation_request_id',
        'hospital_id',
        'hospital_name',
        'amount',
        'discount',
        'tax',
        'total_amount',
        'quotation_details',
        'included_services',
        'excluded_services',
        'valid_until',
        'status',
        'sent_at',
        'accepted_at',
        'rejected_at',
        'admin_notes',
    ];


    protected $casts = [
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'valid_until' => 'datetime',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function quotationRequest()
    {
        return $this->belongsTo(
            SurgeryQuotationRequest::class,
            'surgery_quotation_request_id'
        );
    }

    public function hospital()
    {
        return $this->belongsTo(
            Hospital::class,
            'hospital_id'
        );
    }
}