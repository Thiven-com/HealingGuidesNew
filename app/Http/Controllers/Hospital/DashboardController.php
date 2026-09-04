<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;

use App\Models\Doctor;
use App\Models\DoctorAppointment;
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
            return redirect()->route('hospital.login');
        }

        $hospitalId = $hospital->id;


        /*
        |--------------------------------------------------------------------------
        | Doctors
        |--------------------------------------------------------------------------
        */

        $doctorQuery = Doctor::where('hospital_id', $hospitalId);

        $totalDoctors = (clone $doctorQuery)->count();

        $activeDoctors = (clone $doctorQuery)
            ->where('status', 1)
            ->count();

        $inactiveDoctors = (clone $doctorQuery)
            ->where('status', 0)
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

        $totalAppointments = (clone $appointmentQuery)->count();

        $todayAppointments = (clone $appointmentQuery)
            ->whereDate('appointment_date', today())
            ->count();

        $pendingAppointments = (clone $appointmentQuery)
            ->where('appointment_status', 'pending')
            ->count();

        $confirmedAppointments = (clone $appointmentQuery)
            ->whereIn('appointment_status', [
                'confirmed',
                'approved'
            ])
            ->count();

        $completedAppointments = (clone $appointmentQuery)
            ->where('appointment_status', 'completed')
            ->count();

        $cancelledAppointments = (clone $appointmentQuery)
            ->where('appointment_status', 'cancelled')
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

        $totalAmbulances = (clone $ambulanceQuery)->count();

        $availableAmbulances = (clone $ambulanceQuery)
            ->where('is_available', 1)
            ->where('status', 1)
            ->count();

        $busyAmbulances = (clone $ambulanceQuery)
            ->where('is_available', 0)
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
            (clone $ambulanceBookingQuery)->count();

        $pendingAmbulanceBookings =
            (clone $ambulanceBookingQuery)
                ->where('booking_status', 'pending')
                ->count();

        $completedAmbulanceBookings =
            (clone $ambulanceBookingQuery)
                ->where('booking_status', 'completed')
                ->count();

        $cancelledAmbulanceBookings =
            (clone $ambulanceBookingQuery)
                ->where('booking_status', 'cancelled')
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

        $totalMedicines = (clone $medicineQuery)->count();

        $lowStockMedicines = (clone $medicineQuery)
            ->where('stock_quantity', '<=', 10)
            ->where('stock_quantity', '>', 0)
            ->count();

        $outOfStockMedicines = (clone $medicineQuery)
            ->where('stock_quantity', '<=', 0)
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
            (clone $medicineOrderQuery)->count();

        $pendingMedicineOrders =
            (clone $medicineOrderQuery)
                ->where('order_status', 'pending')
                ->count();

        $processingMedicineOrders =
            (clone $medicineOrderQuery)
                ->whereIn('order_status', [
                    'processing',
                    'confirmed'
                ])
                ->count();

        $deliveredMedicineOrders =
            (clone $medicineOrderQuery)
                ->where('order_status', 'delivered')
                ->count();

        $cancelledMedicineOrders =
            (clone $medicineOrderQuery)
                ->where('order_status', 'cancelled')
                ->count();

        /*
        |--------------------------------------------------------------------------
        | Revenue
        |--------------------------------------------------------------------------
        */

        $appointmentRevenue = DoctorAppointment::where(
            'hospital_id',
            $hospitalId
        )
            ->where('payment_status', 'paid')
            ->sum('total_amount');


        $ambulanceRevenue = AmbulanceBooking::where(
            'hospital_id',
            $hospitalId
        )
            ->where('payment_status', 'paid')
            ->sum('total_amount');


        $medicineRevenue = MedicineOrder::where(
            'hospital_id',
            $hospitalId
        )
            ->where('payment_status', 'paid')
            ->sum('total_amount');


        $totalRevenue =
            $appointmentRevenue +
            $ambulanceRevenue +
            $medicineRevenue;


        /*
        |--------------------------------------------------------------------------
        | Monthly Appointment Chart - Last 6 Months
        |--------------------------------------------------------------------------
        */

        $appointmentChartLabels = [];
        $appointmentChartData = [];

        for ($i = 5; $i >= 0; $i--) {

            $date = now()->copy()->subMonths($i);

            $appointmentChartLabels[] =
                $date->format('M Y');

            $appointmentChartData[] =
                DoctorAppointment::where(
                    'hospital_id',
                    $hospitalId
                )
                    ->whereYear(
                        'appointment_date',
                        $date->year
                    )
                    ->whereMonth(
                        'appointment_date',
                        $date->month
                    )
                    ->count();
        }


        /*
        |--------------------------------------------------------------------------
        | Monthly Revenue Chart - Last 6 Months
        |--------------------------------------------------------------------------
        */

        $revenueChartLabels = [];
        $revenueChartData = [];

        for ($i = 5; $i >= 0; $i--) {

            $date = now()->copy()->subMonths($i);

            $revenueChartLabels[] =
                $date->format('M Y');


            $appointmentAmount = DoctorAppointment::where(
                'hospital_id',
                $hospitalId
            )
                ->where('payment_status', 'paid')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_amount');


            $ambulanceAmount = AmbulanceBooking::where(
                'hospital_id',
                $hospitalId
            )
                ->where('payment_status', 'paid')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_amount');


            $medicineAmount = MedicineOrder::where(
                'hospital_id',
                $hospitalId
            )
                ->where('payment_status', 'paid')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_amount');


            $revenueChartData[] =
                (float) (
                    $appointmentAmount +
                    $ambulanceAmount +
                    $medicineAmount
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Weekly Appointment Chart
        |--------------------------------------------------------------------------
        */

        $weeklyAppointmentLabels = [];
        $weeklyAppointmentData = [];

        for ($i = 6; $i >= 0; $i--) {

            $date = now()->copy()->subDays($i);

            $weeklyAppointmentLabels[] =
                $date->format('D');

            $weeklyAppointmentData[] =
                DoctorAppointment::where(
                    'hospital_id',
                    $hospitalId
                )
                    ->whereDate(
                        'appointment_date',
                        $date->toDateString()
                    )
                    ->count();
        }


        /*
        |--------------------------------------------------------------------------
        | Recent Appointments
        |--------------------------------------------------------------------------
        */

        $recentAppointments =
            (clone $appointmentQuery)
                ->with([
                    'doctor',
                    'familyMember',
                    'customer'
                ])
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
                ->with([
                    'familyMember',
                    'ambulanceType'
                ])
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


        return view(
            'hospital.dashboard',
            compact(

                'hospital',

                // Doctors
                'totalDoctors',
                'activeDoctors',
                'inactiveDoctors',

                // Appointments
                'totalAppointments',
                'todayAppointments',
                'pendingAppointments',
                'confirmedAppointments',
                'completedAppointments',
                'cancelledAppointments',

                // Ambulances
                'totalAmbulances',
                'availableAmbulances',
                'busyAmbulances',

                // Ambulance Bookings
                'totalAmbulanceBookings',
                'pendingAmbulanceBookings',
                'completedAmbulanceBookings',
                'cancelledAmbulanceBookings',
                'activeAmbulanceTrips',

                // Medicines
                'totalMedicines',
                'lowStockMedicines',
                'outOfStockMedicines',

                // Medicine Orders
                'totalMedicineOrders',
                'pendingMedicineOrders',
                'processingMedicineOrders',
                'deliveredMedicineOrders',
                'cancelledMedicineOrders',

                // Revenue
                'appointmentRevenue',
                'ambulanceRevenue',
                'medicineRevenue',
                'totalRevenue',

                // Charts
                'appointmentChartLabels',
                'appointmentChartData',

                'revenueChartLabels',
                'revenueChartData',

                'weeklyAppointmentLabels',
                'weeklyAppointmentData',

                // Recent
                'recentAppointments',
                'recentAmbulanceBookings',
                'recentMedicineOrders'
            )
        );
    }

    public function oldindex()
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