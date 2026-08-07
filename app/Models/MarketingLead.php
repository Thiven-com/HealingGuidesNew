<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingLead extends Model
{
    protected $table = 'marketing_leads';

    protected $fillable = [
        'marketing_staff_id',
        'lead_type',
        'name',
        'mobile',
        'alternate_mobile',
        'email',
        'organization_name',
        'contact_person',
        'specialization',
        'qualification',
        'address',
        'country',
        'state',
        'city',
        'pincode',
        'latitude',
        'longitude',
        'source',
        'priority',
        'lead_status',
        'next_followup_at',
        'notes',
        'converted_id',
        'converted_at',
        'status',
    ];

    protected $casts = [
        'next_followup_at' => 'datetime',
        'converted_at' => 'datetime',
        'status' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Marketing Staff
    |--------------------------------------------------------------------------
    */

    public function marketingStaff()
    {
        return $this->belongsTo(
            MarketingStaff::class,
            'marketing_staff_id',
            'id'
        );
    }
}