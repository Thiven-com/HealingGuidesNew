<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurgeryQuotationBooking extends Model
{
    protected $fillable = [

        'booking_no',

        'customer_id',

        'family_member_id',

        'surgery_quotation_request_id',

        'surgery_quotation_id',

        'surgery_id',

        'hospital_id',

        'amount',

        'discount',

        'tax',

        'total_amount',

        'payment_method',

        'payment_id',

        'transaction_id',

        'payment_status',

        'booking_date',

        'booking_time',

        'booking_status',

        'hospital_address',

        'notes',

        'cancel_reason',

        'confirmed_at',

        'cancelled_at',

        'completed_at',
    ];

    protected $casts = [

        'amount' => 'decimal:2',

        'discount' => 'decimal:2',

        'tax' => 'decimal:2',

        'total_amount' => 'decimal:2',

        'booking_date' => 'date',

        'booking_time' => 'datetime:H:i',

        'confirmed_at' => 'datetime',

        'cancelled_at' => 'datetime',

        'completed_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Customer
    |--------------------------------------------------------------------------
    */

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Family Member
    |--------------------------------------------------------------------------
    */

    public function familyMember()
    {
        return $this->belongsTo(FamilyMember::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Quotation Request
    |--------------------------------------------------------------------------
    */

    public function quotationRequest()
    {
        return $this->belongsTo(
            SurgeryQuotationRequest::class,
            'surgery_quotation_request_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Quotation
    |--------------------------------------------------------------------------
    */

    public function quotation()
    {
        return $this->belongsTo(
            SurgeryQuotation::class,
            'surgery_quotation_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Surgery
    |--------------------------------------------------------------------------
    */

    public function surgery()
    {
        return $this->belongsTo(
            Surgery::class,
            'surgery_id'
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
    | Payment
    |--------------------------------------------------------------------------
    */

    public function payment()
    {
        return $this->belongsTo(
            Payment::class,
            'payment_id'
        );
    }
}