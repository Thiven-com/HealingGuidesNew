<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionQuotation extends Model
{
    protected $fillable = [

        'prescription_request_id',

        'quotation_no',

        'quotation_type',

        /*
        |--------------------------------------------------------------------------
        | Provider
        |--------------------------------------------------------------------------
        */

        'provider_type',

        'hospital_id',

        'diagnostic_id',

        /*
        |--------------------------------------------------------------------------
        | Amount
        |--------------------------------------------------------------------------
        */

        'subtotal',

        'delivery_charge',

        'discount',

        'tax',

        'total_amount',

        /*
        |--------------------------------------------------------------------------
        | Quotation Details
        |--------------------------------------------------------------------------
        */

        'quotation_details',

        'admin_notes',

        'valid_until',

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        'status',

        'sent_at',

        'approved_at',

        'rejected_at',

        'paid_at',

        'completed_at',
    ];

    protected $casts = [

        'subtotal' => 'decimal:2',

        'delivery_charge' => 'decimal:2',

        'discount' => 'decimal:2',

        'tax' => 'decimal:2',

        'total_amount' => 'decimal:2',

        'valid_until' => 'date',

        'sent_at' => 'datetime',

        'approved_at' => 'datetime',

        'rejected_at' => 'datetime',

        'paid_at' => 'datetime',

        'completed_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Prescription Request
    |--------------------------------------------------------------------------
    */

    public function prescriptionRequest()
    {
        return $this->belongsTo(
            PrescriptionRequest::class,
            'prescription_request_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Hospital
    |--------------------------------------------------------------------------
    */

    public function hospital()
    {
        return $this->belongsTo(
            Hospital::class,
            'hospital_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Diagnostic
    |--------------------------------------------------------------------------
    */

    public function diagnostic()
    {
        return $this->belongsTo(
            Diagnostic::class,
            'diagnostic_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Medicine Items
    |--------------------------------------------------------------------------
    */

    public function medicineItems()
    {
        return $this->hasMany(
            PrescriptionQuotationMedicine::class,
            'prescription_quotation_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Lab Test Items
    |--------------------------------------------------------------------------
    */

    public function labTestItems()
    {
        return $this->hasMany(
            PrescriptionQuotationLabTest::class,
            'prescription_quotation_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Payments
    |--------------------------------------------------------------------------
    */

    public function payments()
    {
        return $this->hasMany(
            Payment::class,
            'reference_id'
        )->where(
                'payment_for',
                'prescription_quotation'
            );
    }
}