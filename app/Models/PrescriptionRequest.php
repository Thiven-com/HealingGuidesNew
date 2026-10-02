<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionRequest extends Model
{
    protected $fillable = [

        'customer_id',
        'family_member_id',
        'prescription_id',

        'request_type',

        'prescription_image',

        'notes',

        'address',
        'city',
        'state',
        'pincode',
        'latitude',
        'longitude',

        'status',

        'admin_notes',

        'reviewed_at',
        'approved_at',
        'rejected_at',
        'completed_at',
    ];

    protected $casts = [

        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',

        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(
            Customer::class,
            'customer_id'
        );
    }

    public function familyMember()
    {
        return $this->belongsTo(
            FamilyMember::class,
            'family_member_id'
        );
    }

    public function prescription()
    {
        return $this->belongsTo(
            Prescription::class,
            'prescription_id'
        );
    }

    public function quotations()
    {
        return $this->hasMany(
            PrescriptionQuotation::class,
            'prescription_request_id'
        );
    }

    public function medicineOrders()
    {
        return $this->hasMany(
            MedicineOrder::class,
            'prescription_request_id'
        );
    }

    public function diagnosticBookings()
    {
        return $this->hasMany(
            DiagnosticBooking::class,
            'prescription_request_id'
        );
    }
}