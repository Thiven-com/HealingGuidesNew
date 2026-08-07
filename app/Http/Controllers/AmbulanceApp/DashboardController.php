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
        $ambulance->load([
            'ambulanceType',
            'hospital'
        ]);
        $baseQuery = AmbulanceBooking::where(
            'ambulance_id',
            $ambulance->id
        );
        $totalBookings = (clone $baseQuery)
            ->count();
        $assignedBookings = (clone $baseQuery)
            ->where(
                'booking_status',
                'assigned'
            )
            ->count();
        $acceptedBookings = (clone $baseQuery)
            ->where(
                'booking_status',
                'accepted'
            )
            ->count();
        $ongoingBookings = (clone $baseQuery)
            ->where(
                'booking_status',
                'ongoing'
            )
            ->count();
        $completedBookings = (clone $baseQuery)
            ->where(
                'booking_status',
                'completed'
            )
            ->count();
        $rejectedBookings = (clone $baseQuery)
            ->where(
                'booking_status',
                'rejected'
            )
            ->count();
        $cancelledBookings = (clone $baseQuery)
            ->where(
                'booking_status',
                'cancelled'
            )
            ->count();
        $todayBookings = (clone $baseQuery)
            ->whereDate(
                'created_at',
                today()
            )
            ->count();
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
        $currentBooking = (clone $baseQuery)
            ->whereIn(
                'booking_status',
                [
                    'ongoing',
                    'accepted',
                    'assigned'
                ]
            )
            ->orderByRaw("
                CASE
                    WHEN booking_status = 'ongoing' THEN 1
                    WHEN booking_status = 'accepted' THEN 2
                    WHEN booking_status = 'assigned' THEN 3
                    ELSE 4
                END
            ")
            ->latest('id')
            ->first();
        $currentBookingData = null;

        if ($currentBooking) {

            $currentBookingData = [

                'id' =>
                    $currentBooking->id,

                'booking_no' =>
                    $currentBooking->booking_no,

                'booking_status' =>
                    $currentBooking->booking_status,
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
                'is_emergency' =>
                    (bool) $currentBooking->is_emergency,

                'emergency_notes' =>
                    $currentBooking->emergency_notes,
                'distance_km' =>
                    $currentBooking->distance_km,

                'total_amount' =>
                    $currentBooking->total_amount,

                'payment_method' =>
                    $currentBooking->payment_method,

                'payment_status' =>
                    $currentBooking->payment_status,
                'accepted_at' =>
                    $currentBooking->accepted_at
                    ? $currentBooking->accepted_at
                        ->format('Y-m-d H:i:s')
                    : null,

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
        return response()->json([

            'success' => 1,

            'message' =>
                'Dashboard Fetched Successfully',

            'data' => [
                'overview' => [

                    'total_bookings' =>
                        $totalBookings,

                    'today_bookings' =>
                        $todayBookings,

                    'assigned_bookings' =>
                        $assignedBookings,

                    'accepted_bookings' =>
                        $acceptedBookings,

                    'ongoing_bookings' =>
                        $ongoingBookings,

                    'completed_bookings' =>
                        $completedBookings,

                    'today_completed_trips' =>
                        $todayCompletedTrips,

                    'rejected_bookings' =>
                        $rejectedBookings,

                    'cancelled_bookings' =>
                        $cancelledBookings,
                ],
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
                'current_booking' =>
                    $currentBookingData,
                'recent_trips' =>
                    $recentTrips,
            ]
        ]);
    }
}