<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;

use App\Models\Doctor;
use App\Models\DoctorAppointment;
use App\Models\Diagnostic;
use App\Models\DiagnosticBooking;
use App\Models\Ambulance;
use App\Models\AmbulanceBooking;
use App\Models\Medicine;
use App\Models\MedicineOrder;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $hospital = Auth::guard('hospital')->user();

        if (!$hospital) {

            return redirect()
                ->route('hospital.login');
        }


        $hospitalId = $hospital->id;


        /*
        |--------------------------------------------------------------------------
        | Doctors
        |--------------------------------------------------------------------------
        */

        $doctorQuery = Doctor::where(
            'hospital_id',
            $hospitalId
        );

        $totalDoctors = (clone $doctorQuery)
            ->count();

        $activeDoctors = (clone $doctorQuery)
            ->where('status', 1)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Appointments
        |--------------------------------------------------------------------------
        */

        $appointmentQuery = DoctorAppointment::where(
            'hospital_id',
            $hospitalId
        );

        $totalAppointments = (clone $appointmentQuery)
            ->count();

        $todayAppointments = (clone $appointmentQuery)
            ->whereDate('created_at', today())
            ->count();



        /*
        |--------------------------------------------------------------------------
        | Ambulances
        |--------------------------------------------------------------------------
        */

        $ambulanceQuery = Ambulance::where(
            'hospital_id',
            $hospitalId
        );

        $totalAmbulances = (clone $ambulanceQuery)
            ->count();

        $availableAmbulances = (clone $ambulanceQuery)
            ->where('is_available', 1)
            ->where('status', 1)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Ambulance Bookings
        |--------------------------------------------------------------------------
        */

        $ambulanceBookingQuery = AmbulanceBooking::where(
            'hospital_id',
            $hospitalId
        );

        $totalAmbulanceBookings =
            (clone $ambulanceBookingQuery)
                ->count();

        $pendingAmbulanceBookings =
            (clone $ambulanceBookingQuery)
                ->where('booking_status', 'pending')
                ->count();

        $activeAmbulanceTrips =
            (clone $ambulanceBookingQuery)
                ->whereIn('booking_status', [
                    'assigned',
                    'accepted',
                    'ongoing',
                ])
                ->count();


        /*
        |--------------------------------------------------------------------------
        | Medicines
        |--------------------------------------------------------------------------
        */

        $medicineQuery = Medicine::where(
            'hospital_id',
            $hospitalId
        );

        $totalMedicines = (clone $medicineQuery)
            ->count();

        /*
         * Change stock_quantity below if your medicines
         * table uses another stock column.
         */

        $lowStockMedicines = (clone $medicineQuery)
            ->where('stock_quantity', '<=', 10)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Medicine Orders
        |--------------------------------------------------------------------------
        */

        $medicineOrderQuery = MedicineOrder::where(
            'hospital_id',
            $hospitalId
        );

        $totalMedicineOrders =
            (clone $medicineOrderQuery)
                ->count();

        $pendingMedicineOrders =
            (clone $medicineOrderQuery)
                ->where('order_status', 'pending')
                ->count();


        /*
        |--------------------------------------------------------------------------
        | Recent Appointments
        |--------------------------------------------------------------------------
        */

        $recentAppointments =
            (clone $appointmentQuery)
                ->latest('id')
                ->limit(5)
                ->get();


        /*
        |--------------------------------------------------------------------------
        | Recent Ambulance Requests
        |--------------------------------------------------------------------------
        */

        $recentAmbulanceBookings =
            (clone $ambulanceBookingQuery)
                ->latest('id')
                ->limit(5)
                ->get();


        /*
        |--------------------------------------------------------------------------
        | Recent Medicine Orders
        |--------------------------------------------------------------------------
        */

        $recentMedicineOrders =
            (clone $medicineOrderQuery)
                ->latest('id')
                ->limit(5)
                ->get();


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'hospital.dashboard',
            compact(
                'hospital',

                'totalDoctors',
                'activeDoctors',

                'totalAppointments',
                'todayAppointments',
                'totalAmbulances',
                'availableAmbulances',

                'totalAmbulanceBookings',
                'pendingAmbulanceBookings',
                'activeAmbulanceTrips',

                'totalMedicines',
                'lowStockMedicines',

                'totalMedicineOrders',
                'pendingMedicineOrders',

                'recentAppointments',
                'recentAmbulanceBookings',
                'recentMedicineOrders'
            )
        );
    }
}