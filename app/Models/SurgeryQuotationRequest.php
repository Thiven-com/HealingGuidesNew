<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SurgeryQuotationRequest extends Model
{
    use HasFactory;

    protected $table = 'surgery_quotation_requests';

    protected $fillable = [
        'request_no',
        'customer_id',
        'family_member_id',
        'surgery_id',
        'status',
        'notes',
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

    public function surgery()
    {
        return $this->belongsTo(
            Surgery::class,
            'surgery_id'
        );
    }

    public function quotations()
    {
        return $this->hasMany(
            SurgeryQuotation::class,
            'surgery_quotation_request_id'
        );
    }
}