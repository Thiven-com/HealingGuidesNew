<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareServiceBooking extends Model
{
    protected $table = 'care_service_bookings';

    protected $fillable = [
        'family_member_id',
        'home_visit_service_categories_id',
        'home_visit_services_id',
        'reason',
        'preferred_date',
        'preferred_time',
        'country',
        'state',
        'city',
        'pincode',
        'additional_note',
        'address',
        'latitude',
        'longitude',
        'contact_name',
        'contact_mobile',
        'booking_no',
        'customer_id',
        'total_amount',
        'payment_method',
        'payment_id',
        'payment_status',
        'booking_status',
    ];

    protected $casts = [
        'preferred_date' => 'date',
    ];

    public function familyMember()
    {
        return $this->belongsTo(
            FamilyMember::class,
            'family_member_id',
            'id'
        );
    }

    public function category()
    {
        return $this->belongsTo(
            HomeVisitServiceCategory::class,
            'home_visit_service_categories_id',
            'id'
        );
    }

    public function service()
    {
        return $this->belongsTo(
            HomeVisitService::class,
            'home_visit_services_id',
            'id'
        );
    }
}
