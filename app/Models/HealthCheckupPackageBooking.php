<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HealthCheckupPackageBooking extends Model
{
    use HasFactory;

    protected $table = 'health_checkup_package_bookings';

    protected $fillable = [
        'booking_no',
        'customer_id',
        'family_member_id',
        'health_checkup_package_id',
        'booking_date',
        'booking_time',
        'total_amount',
        'payment_method',
        'payment_id',
        'payment_status',
        'booking_status',
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'booking_time' => 'datetime:H:i',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Customer who made the booking.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Family member for whom the package was booked.
     */
    public function familyMember()
    {
        return $this->belongsTo(
            FamilyMember::class,
            'family_member_id'
        );
    }

    /**
     * Health checkup package.
     */
    public function healthCheckupPackage()
    {
        return $this->belongsTo(
            HealthCheckupPackage::class,
            'health_checkup_package_id'
        );
    }
}