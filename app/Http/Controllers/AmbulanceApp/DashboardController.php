<?php

namespace App\Http\Controllers\AmbulanceApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\AmbulanceCollection;
use App\Models\AmbulanceBooking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $ambulance = auth('sanctum')->user();

        if (!$ambulance) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */

        $baseQuery = AmbulanceBooking::where(
            'ambulance_id',
            $ambulance->id
        );


        /*
        |--------------------------------------------------------------------------
        | Booking Counts
        |--------------------------------------------------------------------------
        */

        $totalBookings = (clone $baseQuery)
            ->count();


        $assignedBookings = (clone $baseQuery)
            ->where(
                'booking_status',
                'ambulance_assigned'
            )
            ->count();


        $onTheWayBookings = (clone $baseQuery)
            ->where(
                'booking_status',
                'on_the_way'
            )
            ->count();


        $completedBookings = (clone $baseQuery)
            ->where(
                'booking_status',
                'completed'
            )
            ->count();


        $cancelledBookings = (clone $baseQuery)
            ->where(
                'booking_status',
                'cancelled'
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Today's Bookings
        |--------------------------------------------------------------------------
        */

        $todayBookings = (clone $baseQuery)
            ->whereDate(
                'created_at',
                today()
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Today's Completed Trips
        |--------------------------------------------------------------------------
        */

        $todayCompletedTrips = (clone $baseQuery)
            ->where(
                'booking_status',
                'completed'
            )
            ->whereDate(
                'completed_at',
                today()
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Earnings
        |--------------------------------------------------------------------------
        |
        | Only completed + paid bookings are counted.
        |
        */

        $todayEarnings = (clone $baseQuery)
            ->where(
                'booking_status',
                'completed'
            )
            ->where(
                'payment_status',
                'paid'
            )
            ->whereDate(
                'completed_at',
                today()
            )
            ->sum('total_amount');


        $totalEarnings = (clone $baseQuery)
            ->where(
                'booking_status',
                'completed'
            )
            ->where(
                'payment_status',
                'paid'
            )
            ->sum('total_amount');


        /*
        |--------------------------------------------------------------------------
        | Current Booking
        |--------------------------------------------------------------------------
        |
        | Priority:
        |
        | 1. on_the_way
        | 2. ambulance_assigned
        |
        */

        $currentBooking = (clone $baseQuery)
            ->whereIn(
                'booking_status',
                [
                    'on_the_way',
                    'ambulance_assigned'
                ]
            )
            ->orderByRaw("
            CASE
                WHEN booking_status = 'on_the_way' THEN 1
                WHEN booking_status = 'ambulance_assigned' THEN 2
                ELSE 3
            END
        ")
            ->latest('id')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Current Booking Data
        |--------------------------------------------------------------------------
        */

        $currentBookingData = null;

        if ($currentBooking) {

            $currentBookingData = [

                'id' =>
                    $currentBooking->id,

                'booking_no' =>
                    $currentBooking->booking_no,

                'booking_status' =>
                    $currentBooking->booking_status,


                /*
                |--------------------------------------------------------------------------
                | Pickup
                |--------------------------------------------------------------------------
                */

                'pickup_address' =>
                    $currentBooking->pickup_address,

                'pickup_city' =>
                    $currentBooking->pickup_city,

                'pickup_state' =>
                    $currentBooking->pickup_state,

                'pickup_pincode' =>
                    $currentBooking->pickup_pincode,

                'pickup_latitude' =>
                    $currentBooking->pickup_latitude,

                'pickup_longitude' =>
                    $currentBooking->pickup_longitude,


                /*
                |--------------------------------------------------------------------------
                | Destination
                |--------------------------------------------------------------------------
                */

                'destination_address' =>
                    $currentBooking->destination_address,

                'destination_city' =>
                    $currentBooking->destination_city,

                'destination_state' =>
                    $currentBooking->destination_state,

                'destination_pincode' =>
                    $currentBooking->destination_pincode,

                'destination_latitude' =>
                    $currentBooking->destination_latitude,

                'destination_longitude' =>
                    $currentBooking->destination_longitude,


                /*
                |--------------------------------------------------------------------------
                | Emergency
                |--------------------------------------------------------------------------
                */

                'is_emergency' =>
                    (bool) $currentBooking->is_emergency,

                'emergency_notes' =>
                    $currentBooking->emergency_notes,


                /*
                |--------------------------------------------------------------------------
                | Trip
                |--------------------------------------------------------------------------
                */

                'distance_km' =>
                    $currentBooking->distance_km,


                /*
                |--------------------------------------------------------------------------
                | Amount
                |--------------------------------------------------------------------------
                */

                'base_amount' =>
                    $currentBooking->base_amount,

                'distance_amount' =>
                    $currentBooking->distance_amount,

                'extra_charge' =>
                    $currentBooking->extra_charge,

                'discount' =>
                    $currentBooking->discount,

                'tax' =>
                    $currentBooking->tax,

                'total_amount' =>
                    $currentBooking->total_amount,


                /*
                |--------------------------------------------------------------------------
                | Payment
                |--------------------------------------------------------------------------
                */

                'payment_method' =>
                    $currentBooking->payment_method,

                'payment_status' =>
                    $currentBooking->payment_status,


                /*
                |--------------------------------------------------------------------------
                | Dates
                |--------------------------------------------------------------------------
                */

                'assigned_at' =>
                    $currentBooking->assigned_at
                    ? $currentBooking->assigned_at
                        ->format('Y-m-d H:i:s')
                    : null,

                'created_at' =>
                    $currentBooking->created_at
                    ? $currentBooking->created_at
                        ->format('Y-m-d H:i:s')
                    : null,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Recent Completed Trips
        |--------------------------------------------------------------------------
        */

        $recentTrips = (clone $baseQuery)
            ->where(
                'booking_status',
                'completed'
            )
            ->latest('completed_at')
            ->limit(5)
            ->get()
            ->map(function ($booking) {

                return [

                    'id' =>
                        $booking->id,

                    'booking_no' =>
                        $booking->booking_no,

                    'pickup_address' =>
                        $booking->pickup_address,

                    'destination_address' =>
                        $booking->destination_address,

                    'distance_km' =>
                        $booking->distance_km,

                    'total_amount' =>
                        $booking->total_amount,

                    'payment_method' =>
                        $booking->payment_method,

                    'payment_status' =>
                        $booking->payment_status,

                    'completed_at' =>
                        $booking->completed_at
                        ? $booking->completed_at
                            ->format('Y-m-d H:i:s')
                        : null,
                ];
            });


        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => 1,

            'message' =>
                'Dashboard Fetched Successfully',

            'data' => [

                /*
                |--------------------------------------------------------------------------
                | Overview
                |--------------------------------------------------------------------------
                */

                'overview' => [

                    'total_bookings' =>
                        $totalBookings,

                    'today_bookings' =>
                        $todayBookings,

                    'assigned_bookings' =>
                        $assignedBookings,

                    'on_the_way_bookings' =>
                        $onTheWayBookings,

                    'completed_bookings' =>
                        $completedBookings,

                    'today_completed_trips' =>
                        $todayCompletedTrips,

                    'cancelled_bookings' =>
                        $cancelledBookings,
                ],


                /*
                |--------------------------------------------------------------------------
                | Earnings
                |--------------------------------------------------------------------------
                */

                'earnings' => [

                    'today_earnings' =>
                        number_format(
                            (float) $todayEarnings,
                            2,
                            '.',
                            ''
                        ),

                    'total_earnings' =>
                        number_format(
                            (float) $totalEarnings,
                            2,
                            '.',
                            ''
                        ),
                ],


                /*
                |--------------------------------------------------------------------------
                | Current Booking
                |--------------------------------------------------------------------------
                */

                'current_booking' =>
                    $currentBookingData,


                /*
                |--------------------------------------------------------------------------
                | Recent Trips
                |--------------------------------------------------------------------------
                */

                'recent_trips' =>
                    $recentTrips,
            ]
        ]);
    }
}