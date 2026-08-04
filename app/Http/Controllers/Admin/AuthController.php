<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ambulance;
use App\Models\Customer;
use App\Models\Diagnostic;
use App\Models\Doctor;
use App\Models\DoctorAppointment;
use App\Models\Hospital;
use App\Models\Medicine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {

            return redirect()->route('admin.dashboard');
        }
        return redirect()->back()->withErrors(['Invalid Credentials']);
    }

    public function dashboard()
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfYear = Carbon::now()->startOfYear();

        /*
        |--------------------------------------------------------------------------
        | Main Dashboard Counts
        |--------------------------------------------------------------------------
        */

        $data = [
            'hospitals' => Hospital::count(),
            'patients' => Customer::count(),
            'doctors' => Doctor::count(),
            'staff' => Admin::count(),

            // All appointments
            'total_appointments' => DoctorAppointment::count(),

            // Today's appointments
            'appointments' => DoctorAppointment::whereDate('created_at', $today)->count(),

            'medicines' => Medicine::count(),
            'diagnostics' => Diagnostic::count(),
            'ambulances' => Ambulance::count(),
        ];


        /*
        |--------------------------------------------------------------------------
        | Patient Growth
        |--------------------------------------------------------------------------
        */

        $currentMonthPatients = Customer::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        $previousMonthDate = now()->subMonth();

        $previousMonthPatients = Customer::whereYear(
            'created_at',
            $previousMonthDate->year
        )
            ->whereMonth(
                'created_at',
                $previousMonthDate->month
            )
            ->count();

        if ($previousMonthPatients > 0) {

            $patientGrowth = round(
                (($currentMonthPatients - $previousMonthPatients)
                    / $previousMonthPatients) * 100,
                1
            );

        } else {

            $patientGrowth = $currentMonthPatients > 0 ? 100 : 0;
        }

        $data['patient_growth'] = $patientGrowth;


        $todayAppointments = DoctorAppointment::whereDate('created_at', $today);

        $data['today_appointments'] = (clone $todayAppointments)->count();

        $data['pending_appointments'] = (clone $todayAppointments)
            ->where('appointment_status', 'pending')
            ->count();

        $data['confirmed_appointments'] = (clone $todayAppointments)
            ->where('appointment_status', 'confirmed')
            ->count();

        $data['completed_appointments'] = (clone $todayAppointments)
            ->where('appointment_status', 'completed')
            ->count();

        $data['cancelled_appointments'] = (clone $todayAppointments)
            ->where('appointment_status', 'cancelled')
            ->count();



        $data['available_ambulances'] = Ambulance::where(
            'status',
            'available'
        )->count();

        $data['on_duty_ambulances'] = Ambulance::where(
            'status',
            'on_duty'
        )->count();

        $data['maintenance_ambulances'] = Ambulance::where(
            'status',
            'maintenance'
        )->count();

        $data['available_medicines'] = Medicine::where(
            'stock_quantity',
            '>',
            0
        )->count();

        $data['low_stock_medicines'] = Medicine::where(
            'stock_quantity',
            '>',
            0
        )
            ->where('stock_quantity', '<=', 10)
            ->count();

        $data['out_of_stock_medicines'] = Medicine::where(
            'stock_quantity',
            '<=',
            0
        )->count();

        // $data['expired_medicines'] = Medicine::whereDate(
        //     'expiry_date',
        //     '<',
        //     $today
        // )->count();



        $recentAppointments = DoctorAppointment::with([
            'customer',
            'doctor'
        ])
            ->latest()
            ->take(5)
            ->get();

        $recentPatients = Customer::latest()
            ->take(5)
            ->get();

        $monthlyAppointments = [];

        for ($month = 1; $month <= 12; $month++) {

            $monthlyAppointments[] = DoctorAppointment::whereYear(
                'created_at',
                now()->year
            )
                ->whereMonth(
                    'created_at',
                    $month
                )
                ->count();
        }

        $appointmentMonths = [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec'
        ];

        $data['today_revenue'] = 0;
        $data['week_revenue'] = 0;
        $data['month_revenue'] = 0;
        $data['year_revenue'] = 0;
        $data['pending_bills'] = 0;

        $data['revenue'] = $data['month_revenue'];
        $data['pending_diagnostics'] = Diagnostic::where(
            'status',
            'pending'
        )->count();

        $data['completed_diagnostics'] = Diagnostic::where(
            'status',
            'completed'
        )->count();


        return view('admin.auth.dashboard', compact(
            'data',
            'recentAppointments',
            'recentPatients',
            'monthlyAppointments',
            'appointmentMonths'
        ));
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function showForgotForm()
    {
        return view('admin.auth.forgot-password');
    }
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email'
        ]);

        $admin = Admin::where('email', $request->email)->first();
        if (!isset($admin->id)) {
            return back()->withErrors(['email' => 'Invalid Email']);
        }
        $otp = 123456;
        // $otp = rand(100000, 999999);

        $admin->otp = $otp;
        $admin->save();

        // Mail::to($request->email)->send(new AdminPasswordResetOtpMail($otp));
        session(['email' => $request->email]);
        return redirect()->route('admin.password.verifyForm')
            ->with('email', $request->email)
            ->with('success', 'OTP sent to your email.');
    }
    public function showVerifyForm()
    {
        if (!session('email')) {

            return redirect()->route('admin.password.request');
        }

        return view('admin.auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required'
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !$admin->otp) {
            session(['email' => $request->email]);

            return redirect(route('admin.password.verifyForm'))->withErrors(['otp' => 'Invalid OTP']);
        }

        if ($request->otp != $admin->otp) {
            session(['email' => $request->email]);

            return redirect(route('admin.password.verifyForm'))->withErrors(['otp' => 'Invalid OTP']);
        }

        return view('admin.auth.reset-password', [
            'email' => $request->email
        ]);
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);


        $admin = Admin::where('email', $request->email)->first();

        $admin->password = bcrypt($request->password);
        $admin->otp = null;
        $admin->save();
        session()->forget('email');

        return redirect()->route('admin.login')
            ->with('success', 'Password reset successfully.');
    }


}
