<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosticBookingItem extends Model
{
    protected $fillable = [

        'diagnostic_booking_id',

        'lab_test_id',

        'price',

    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Booking
    |--------------------------------------------------------------------------
    */

    public function booking()
    {
        return $this->belongsTo(
            DiagnosticBooking::class,
            'diagnostic_booking_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Lab Test
    |--------------------------------------------------------------------------
    */

    public function labTest()
    {
        return $this->belongsTo(
            LabTest::class,
            'lab_test_id'
        );
    }
}