<?php

namespace App\Http\Controllers\AmbulanceApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\AmbulanceBookingCollection;
use App\Models\AmbulanceBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class EarningController extends Controller
{
    public function earnings(Request $request)
    {
        
        $ambulance = auth('sanctum')->user();

        if (!$ambulance) {

            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $baseQuery = AmbulanceBooking::where(
            'ambulance_id',
            $ambulance->id
        )
            ->where(
                'booking_status',
                'completed'
            );

        $completedRides = (clone $baseQuery)
            ->count();

        $totalEarnings = (clone $baseQuery)
            ->sum('total_amount');

        $todayEarnings = (clone $baseQuery)
            ->whereDate(
                'completed_at',
                today()
            )
            ->sum('total_amount');

        $todayCompletedRides = (clone $baseQuery)
            ->whereDate(
                'completed_at',
                today()
            )
            ->count();

        $weekStart = Carbon::now()
            ->startOfWeek();

        $weekEnd = Carbon::now()
            ->endOfWeek();


        $thisWeekEarnings = (clone $baseQuery)
            ->whereBetween(
                'completed_at',
                [
                    $weekStart,
                    $weekEnd
                ]
            )
            ->sum('total_amount');

        $thisWeekCompletedRides = (clone $baseQuery)
            ->whereBetween(
                'completed_at',
                [
                    $weekStart,
                    $weekEnd
                ]
            )
            ->count();
       $thisMonthEarnings = (clone $baseQuery)
            ->whereYear(
                'completed_at',
                now()->year
            )
            ->whereMonth(
                'completed_at',
                now()->month
            )
            ->sum('total_amount');
        $thisMonthCompletedRides = (clone $baseQuery)
            ->whereYear(
                'completed_at',
                now()->year
            )
            ->whereMonth(
                'completed_at',
                now()->month
            )
            ->count();

        $paidEarnings = (clone $baseQuery)
            ->where(
                'payment_status',
                'paid'
            )
            ->sum('total_amount');

        $pendingEarnings = (clone $baseQuery)
            ->where(
                'payment_status',
                'pending'
            )
            ->sum('total_amount');
        $recentEarnings = (clone $baseQuery)
            ->latest('completed_at')
            ->limit(5)
            ->get();
        return response()->json([

            'success' => 1,

            'message' =>
                'Earnings Fetched Successfully',

            'data' => [
                'overview' => [

                    'total_earnings' =>
                        number_format(
                            (float) $totalEarnings,
                            2,
                            '.',
                            ''
                        ),

                    'completed_rides' =>
                        $completedRides,

                    'paid_earnings' =>
                        number_format(
                            (float) $paidEarnings,
                            2,
                            '.',
                            ''
                        ),

                    'pending_earnings' =>
                        number_format(
                            (float) $pendingEarnings,
                            2,
                            '.',
                            ''
                        ),
                ],

                'today' => [

                    'earnings' =>
                        number_format(
                            (float) $todayEarnings,
                            2,
                            '.',
                            ''
                        ),

                    'completed_rides' =>
                        $todayCompletedRides,
                ],

                'this_week' => [

                    'earnings' =>
                        number_format(
                            (float) $thisWeekEarnings,
                            2,
                            '.',
                            ''
                        ),

                    'completed_rides' =>
                        $thisWeekCompletedRides,
                ],

                'this_month' => [

                    'earnings' =>
                        number_format(
                            (float) $thisMonthEarnings,
                            2,
                            '.',
                            ''
                        ),

                    'completed_rides' =>
                        $thisMonthCompletedRides,
                ],

                'recent_earnings' =>
                    new AmbulanceBookingCollection(
                        $recentEarnings
                    ),
            ]
        ]);
    }

}