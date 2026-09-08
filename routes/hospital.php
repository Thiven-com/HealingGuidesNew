<?php


use App\Http\Controllers\Hospital\CouponController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Hospital\AccountController;
use App\Http\Controllers\Hospital\DashboardController;
use App\Http\Controllers\Hospital\DoctorController;
use App\Http\Controllers\Hospital\AppointmentController;
use App\Http\Controllers\Hospital\DiagnosticController;
use App\Http\Controllers\Hospital\AmbulanceController;
use App\Http\Controllers\Hospital\AmbulanceBookingController;
use App\Http\Controllers\Hospital\DoctorScheduleController;
use App\Http\Controllers\Hospital\MedicineController;
use App\Http\Controllers\Hospital\MedicineOrderController;
use App\Http\Controllers\Hospital\PatientController;
use App\Http\Controllers\Hospital\ProfileController;



Route::name('hospital.')
    ->group(function () {
        Route::get('login', [
            AccountController::class,
            'login'
        ])->name('login');

        Route::post('send-otp', [
            AccountController::class,
            'sendOtp'
        ])->name('send-otp');

        Route::get('verify-otp', [
            AccountController::class,
            'verifyOtpPage'
        ])->name('verify-otp');

        Route::post('verify-otp', [
            AccountController::class,
            'verifyOtp'
        ])->name('verify-otp.submit');

        Route::post('resend-otp', [
            AccountController::class,
            'resendOtp'
        ])->name('resend-otp');


        /*
        |--------------------------------------------------------------------------
        | Authenticated Hospital Panel
        |--------------------------------------------------------------------------
        */

        Route::middleware(['hospitalAuth'])->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Logout
            |--------------------------------------------------------------------------
            */

            Route::any('logout', [
                AccountController::class,
                'logout'
            ])->name('logout');
            Route::get('profile', [
                ProfileController::class,
                'index'
            ])->name('profile');


            Route::post('profile/update', [
                ProfileController::class,
                'update'
            ])->name('profile.update');


            /*
            |--------------------------------------------------------------------------
            | Dashboard
            |--------------------------------------------------------------------------
            */

            Route::get('dashboard', [
                DashboardController::class,
                'index'
            ])->name('dashboard');


            /*
            |--------------------------------------------------------------------------
            | Doctors
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'doctors',
                DoctorController::class
            );

            Route::post('doctors/status', [
                DoctorController::class,
                'updateStatus'
            ])->name('doctors.status');


            /*
            |--------------------------------------------------------------------------
            | Appointments
            |--------------------------------------------------------------------------
            */

            Route::get('appointments', [
                AppointmentController::class,
                'index'
            ])->name('appointments.index');

            Route::get('appointments/{id}', [
                AppointmentController::class,
                'show'
            ])->name('appointments.show');

            Route::post('appointments/status', [
                AppointmentController::class,
                'updateStatus'
            ])->name('appointments.status');


            Route::get('appointments/{id}/reschedule', [
                AppointmentController::class,
                'reschedule'
            ])->name('appointments.reschedule');
            Route::get('appointments/{id}/reschedule-slots', [
                AppointmentController::class,
                'getRescheduleSlots'
            ])->name('appointments.reschedule.slots');

            Route::put('appointments/{id}/reschedule', [
                AppointmentController::class,
                'updateReschedule'
            ])->name('appointments.reschedule.update');


            /*
            |--------------------------------------------------------------------------
            | Ambulances
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'ambulances',
                AmbulanceController::class
            );

            Route::post('ambulances/availability', [
                AmbulanceController::class,
                'updateAvailability'
            ])->name('ambulances.availability');
            Route::post('ambulances/status', [
                AmbulanceController::class,
                'updateStatus'
            ])->name('ambulances.status');


            /*
            |--------------------------------------------------------------------------
            | Ambulance Bookings
            |--------------------------------------------------------------------------
            */

            Route::get('ambulance-bookings', [
                AmbulanceBookingController::class,
                'index'
            ])->name('ambulance-bookings.index');

            Route::get('ambulance-bookings/{id}', [
                AmbulanceBookingController::class,
                'show'
            ])->name('ambulance-bookings.show');

            Route::post('ambulance-bookings/assign', [
                AmbulanceBookingController::class,
                'assignAmbulance'
            ])->name('ambulance-bookings.assign');

            Route::post('ambulance-bookings/reject', [
                AmbulanceBookingController::class,
                'reject'
            ])->name('ambulance-bookings.reject');

            Route::get('ambulance-bookings/{id}/track', [
                AmbulanceBookingController::class,
                'track'
            ])->name('ambulance-bookings.track');

            Route::post('ambulance-bookings/payment-status', [
                AmbulanceBookingController::class,
                'updatePaymentStatus'
            ])->name('ambulance-bookings.payment-status');
            Route::get('ambulance-bookings/{id}/location', [
                AmbulanceBookingController::class,
                'location'
            ])->name('ambulance-bookings.location');


            Route::get('/doctor-schedules/create', [DoctorScheduleController::class, 'create'])->name('hospital.doctor-schedules.create');
            Route::post('doctor-schedules', [DoctorScheduleController::class, 'store'])->name('doctor-schedules.store');
            Route::get('doctor-schedules/{doctorSchedule}/edit', [DoctorScheduleController::class, 'edit'])->name('doctor-schedules.edit');

            Route::put('doctor-schedules/{doctorSchedule}', [DoctorScheduleController::class, 'update'])->name('doctor-schedules.update');
            Route::get('patients', [PatientController::class, 'index'])->name('patients.all');
            Route::get('patients/{type}/{id}', [PatientController::class, 'show'])->name('patients.show');


            /*
            |--------------------------------------------------------------------------
            | Medicines
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'medicines',
                MedicineController::class
            );

            Route::post('medicines/stock', [
                MedicineController::class,
                'updateStock'
            ])->name('medicines.stock');

            Route::post('medicines/status', [
                MedicineController::class,
                'updateStatus'
            ])->name('medicines.status');


            /*
            |--------------------------------------------------------------------------
            | Medicine Orders
            |--------------------------------------------------------------------------
            */

            Route::get('medicine-orders', [
                MedicineOrderController::class,
                'index'
            ])->name('medicine-orders.index');

            Route::get('medicine-orders/{id}', [
                MedicineOrderController::class,
                'show'
            ])->name('medicine-orders.show');

            Route::post('medicine-orders/accept', [
                MedicineOrderController::class,
                'accept'
            ])->name('medicine-orders.accept');

            Route::post('medicine-orders/reject', [
                MedicineOrderController::class,
                'reject'
            ])->name('medicine-orders.reject');

            Route::post('medicine-orders/process', [
                MedicineOrderController::class,
                'process'
            ])->name('medicine-orders.process');

            Route::post('medicine-orders/ready', [
                MedicineOrderController::class,
                'ready'
            ])->name('medicine-orders.ready');

            Route::post('medicine-orders/dispatch', [
                MedicineOrderController::class,
                'dispatch'
            ])->name('medicine-orders.dispatch');

            Route::post('medicine-orders/deliver', [
                MedicineOrderController::class,
                'deliver'
            ])->name('medicine-orders.deliver');

            //coupons
            Route::resource('coupons', CouponController::class);

            Route::get(
                'coupons/{id}/status',
                [CouponController::class, 'status']
            )->name('coupons.status');
        });
    });