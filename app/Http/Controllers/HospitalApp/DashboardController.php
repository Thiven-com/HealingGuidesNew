<?php

namespace App\Http\Controllers\HospitalApp;

use App\Http\Controllers\Controller;
use App\Models\Ambulance;
use App\Models\AmbulanceBooking;
use App\Models\DiagnosticBooking;
use App\Models\Doctor;
use App\Models\Medicine;
use App\Models\MedicineOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    public function dashboard(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $hospitalId = $hospital->id;

        /*
        |--------------------------------------------------------------------------
        | Dates
        |--------------------------------------------------------------------------
        */

        $today = Carbon::today();

        $monthStart = Carbon::now()->startOfMonth();

        $monthEnd = Carbon::now()->endOfMonth();

        /*
        |--------------------------------------------------------------------------
        | Doctors
        |--------------------------------------------------------------------------
        */

        $totalDoctors = Doctor::where(
            'hospital_id',
            $hospitalId
        )->count();

        $activeDoctors = Doctor::where(
            'hospital_id',
            $hospitalId
        )
            ->where('status', 1)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Diagnostic Bookings
        |--------------------------------------------------------------------------
        */

        $totalLabBookings = DiagnosticBooking::where(
            'diagnostic_id',
            $hospitalId
        )->count();

        $todayLabBookings = DiagnosticBooking::where(
            'diagnostic_id',
            $hospitalId
        )
            ->whereDate(
                'booking_date',
                $today
            )
            ->count();

        $pendingLabBookings = DiagnosticBooking::where(
            'diagnostic_id',
            $hospitalId
        )
            ->where(
                'booking_status',
                'pending'
            )
            ->count();

        $completedLabBookings = DiagnosticBooking::where(
            'diagnostic_id',
            $hospitalId
        )
            ->where(
                'booking_status',
                'completed'
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Ambulances
        |--------------------------------------------------------------------------
        */

        $totalAmbulances = Ambulance::where(
            'hospital_id',
            $hospitalId
        )->count();

        $availableAmbulances = Ambulance::where(
            'hospital_id',
            $hospitalId
        )
            ->where('is_available', 1)
            ->where('status', 1)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Ambulance Bookings
        |--------------------------------------------------------------------------
        */

        $totalAmbulanceBookings = AmbulanceBooking::where(
            'hospital_id',
            $hospitalId
        )->count();

        $pendingAmbulanceBookings = AmbulanceBooking::where(
            'hospital_id',
            $hospitalId
        )
            ->where(
                'booking_status',
                'pending'
            )
            ->count();

        $todayAmbulanceBookings = AmbulanceBooking::where(
            'hospital_id',
            $hospitalId
        )
            ->whereDate(
                'created_at',
                $today
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Medicines
        |--------------------------------------------------------------------------
        */

        $totalMedicines = Medicine::where(
            'hospital_id',
            $hospitalId
        )->count();

        $availableMedicines = Medicine::where(
            'hospital_id',
            $hospitalId
        )
            ->where('status', 1)
            ->where('stock_quantity', '>', 0)
            ->count();

        $outOfStockMedicines = Medicine::where(
            'hospital_id',
            $hospitalId
        )
            ->where('stock_quantity', '<=', 0)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Medicine Orders
        |--------------------------------------------------------------------------
        */

        $totalMedicineOrders = MedicineOrder::where(
            'hospital_id',
            $hospitalId
        )->count();

        $todayMedicineOrders = MedicineOrder::where(
            'hospital_id',
            $hospitalId
        )
            ->whereDate(
                'created_at',
                $today
            )
            ->count();

        $pendingMedicineOrders = MedicineOrder::where(
            'hospital_id',
            $hospitalId
        )
            ->where(
                'order_status',
                'pending'
            )
            ->count();

        $acceptedMedicineOrders = MedicineOrder::where(
            'hospital_id',
            $hospitalId
        )
            ->where(
                'order_status',
                'accepted'
            )
            ->count();

        $preparingMedicineOrders = MedicineOrder::where(
            'hospital_id',
            $hospitalId
        )
            ->where(
                'order_status',
                'preparing'
            )
            ->count();

        $outForDeliveryOrders = MedicineOrder::where(
            'hospital_id',
            $hospitalId
        )
            ->where(
                'order_status',
                'out_for_delivery'
            )
            ->count();

        $deliveredMedicineOrders = MedicineOrder::where(
            'hospital_id',
            $hospitalId
        )
            ->where(
                'order_status',
                'delivered'
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Monthly Statistics
        |--------------------------------------------------------------------------
        */

        $monthlyLabBookings = DiagnosticBooking::where(
            'diagnostic_id',
            $hospitalId
        )
            ->whereBetween(
                'created_at',
                [$monthStart, $monthEnd]
            )
            ->count();

        $monthlyAmbulanceBookings = AmbulanceBooking::where(
            'hospital_id',
            $hospitalId
        )
            ->whereBetween(
                'created_at',
                [$monthStart, $monthEnd]
            )
            ->count();

        $monthlyMedicineOrders = MedicineOrder::where(
            'hospital_id',
            $hospitalId
        )
            ->whereBetween(
                'created_at',
                [$monthStart, $monthEnd]
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Recent Lab Bookings
        |--------------------------------------------------------------------------
        */

        $recentLabBookings = DiagnosticBooking::with([
            'customer',
            'familyMember',
            'items.labTest'
        ])
            ->where(
                'diagnostic_id',
                $hospitalId
            )
            ->latest()
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Recent Ambulance Requests
        |--------------------------------------------------------------------------
        */

        $recentAmbulanceBookings = AmbulanceBooking::with([
            'customer',
            'familyMember',
            'ambulance'
        ])
            ->where(
                'hospital_id',
                $hospitalId
            )
            ->latest()
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Recent Medicine Orders
        |--------------------------------------------------------------------------
        */

        $recentMedicineOrders = MedicineOrder::with([
            'customer',
            'familyMember',
            'items.medicine'
        ])
            ->where(
                'hospital_id',
                $hospitalId
            )
            ->latest()
            ->limit(5)
            ->get();
        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => 1,

            'message' =>
                'Hospital dashboard fetched successfully.',

            'data' => [

                /*
                |--------------------------------------------------------------------------
                | Doctors
                |--------------------------------------------------------------------------
                */

                'doctors' => [

                    'total' =>
                        $totalDoctors,

                    'active' =>
                        $activeDoctors,
                ],

                /*
                |--------------------------------------------------------------------------
                | Diagnostics
                |--------------------------------------------------------------------------
                */

                'lab_bookings' => [

                    'total' =>
                        $totalLabBookings,

                    'today' =>
                        $todayLabBookings,

                    'pending' =>
                        $pendingLabBookings,

                    'completed' =>
                        $completedLabBookings,

                    'this_month' =>
                        $monthlyLabBookings,
                ],

                /*
                |--------------------------------------------------------------------------
                | Ambulances
                |--------------------------------------------------------------------------
                */

                'ambulances' => [

                    'total' =>
                        $totalAmbulances,

                    'available' =>
                        $availableAmbulances,
                ],

                'ambulance_bookings' => [

                    'total' =>
                        $totalAmbulanceBookings,

                    'today' =>
                        $todayAmbulanceBookings,

                    'pending' =>
                        $pendingAmbulanceBookings,

                    'this_month' =>
                        $monthlyAmbulanceBookings,
                ],

                /*
                |--------------------------------------------------------------------------
                | Medicines
                |--------------------------------------------------------------------------
                */

                'medicines' => [

                    'total' =>
                        $totalMedicines,

                    'available' =>
                        $availableMedicines,

                    'out_of_stock' =>
                        $outOfStockMedicines,
                ],

               
                /*
                |--------------------------------------------------------------------------
                | Medicine Orders
                |--------------------------------------------------------------------------
                */

                'medicine_orders' => [

                    'total' =>
                        $totalMedicineOrders,

                    'today' =>
                        $todayMedicineOrders,

                    'pending' =>
                        $pendingMedicineOrders,

                    'accepted' =>
                        $acceptedMedicineOrders,

                    'preparing' =>
                        $preparingMedicineOrders,

                    'out_for_delivery' =>
                        $outForDeliveryOrders,

                    'delivered' =>
                        $deliveredMedicineOrders,

                    'this_month' =>
                        $monthlyMedicineOrders,
                ],

                /*
                |--------------------------------------------------------------------------
                | Recent Activities
                |--------------------------------------------------------------------------
                */

                'recent_lab_bookings' =>
                    $recentLabBookings,

                'recent_ambulance_bookings' =>
                    $recentAmbulanceBookings,

                'recent_medicine_orders' =>
                    $recentMedicineOrders,
            ]
        ]);
    }
}