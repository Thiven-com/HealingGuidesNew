<?php

use App\Http\Controllers\Admin\AmbulanceController;
use App\Http\Controllers\Admin\AmbulanceTypeController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DiagnosticController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\HospitalController;
use App\Http\Controllers\Admin\LabTestController;
use App\Http\Controllers\Admin\MedicineCategoryController;
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\Admin\SpecializationController;
use App\Http\Controllers\Admin\AmbulanceBookingController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\DoctorAppointmentController;
use App\Http\Controllers\Admin\LabTestBookingController;
use App\Http\Controllers\Admin\MedicineOrderController;
use App\Http\Controllers\Admin\PatientMedicalReportController;
use Illuminate\Support\Facades\Route;





Route::get('/', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin-login', [AuthController::class, 'login'])->name('admin.loginAction');

Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');
Route::get('logout', [AuthController::class, 'logout']);

Route::group(['middleware' => 'admin'], function () {

    Route::get('/customers', [CustomerController::class, 'index'])
        ->name('admin.customers.index');

    Route::get('/customers/{id}', [CustomerController::class, 'show'])
        ->name('admin.customers.show');

    Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])
        ->name('admin.customers.edit');

    Route::put('/customers/{id}', [CustomerController::class, 'update'])
        ->name('admin.customers.update');

    Route::post('/customers/{id}/status', [CustomerController::class, 'status'])
        ->name('admin.customers.status');

    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])
        ->name('admin.customers.destroy');

    Route::get('dashboard', [AuthController::class, 'dashboard'])
        ->name('admin.dashboard');

    /*
       |--------------------------------------------------------------------------
       | Ambulance Bookings
       |--------------------------------------------------------------------------
       */

    Route::get('ambulance-bookings', [
        AmbulanceBookingController::class,
        'index'
    ])->name('admin.ambulance-bookings.index');

    Route::get('ambulance-bookings/{id}', [
        AmbulanceBookingController::class,
        'show'
    ])->name('admin.ambulance-bookings.show');

    Route::post('ambulance-bookings/assign', [
        AmbulanceBookingController::class,
        'assignAmbulance'
    ])->name('admin.ambulance-bookings.assign');

    Route::post('ambulance-bookings/reject', [
        AmbulanceBookingController::class,
        'reject'
    ])->name('admin.ambulance-bookings.reject');

    Route::get('ambulance-bookings/{id}/track', [
        AmbulanceBookingController::class,
        'track'
    ])->name('admin.ambulance-bookings.track');

    Route::post('ambulance-bookings/payment-status', [
        AmbulanceBookingController::class,
        'updatePaymentStatus'
    ])->name('admin.ambulance-bookings.payment-status');
    Route::get('ambulance-bookings/{id}/location', [
        AmbulanceBookingController::class,
        'location'
    ])->name('admin.ambulance-bookings.location');


    /*
           |--------------------------------------------------------------------------
           | Medicine Orders
           |--------------------------------------------------------------------------
           */

    Route::get('medicine-orders', [
        MedicineOrderController::class,
        'index'
    ])->name('admin.medicine-orders.index');

    Route::get('medicine-orders/{id}', [
        MedicineOrderController::class,
        'show'
    ])->name('admin.medicine-orders.show');

    Route::post('medicine-orders/accept', [
        MedicineOrderController::class,
        'accept'
    ])->name('admin.medicine-orders.accept');

    Route::post('medicine-orders/reject', [
        MedicineOrderController::class,
        'reject'
    ])->name('admin.medicine-orders.reject');

    Route::post('medicine-orders/process', [
        MedicineOrderController::class,
        'process'
    ])->name('admin.medicine-orders.process');

    Route::post('medicine-orders/ready', [
        MedicineOrderController::class,
        'ready'
    ])->name('admin.medicine-orders.ready');

    Route::post('medicine-orders/dispatch', [
        MedicineOrderController::class,
        'dispatch'
    ])->name('admin.medicine-orders.dispatch');

    Route::post('medicine-orders/deliver', [
        MedicineOrderController::class,
        'deliver'
    ])->name('admin.medicine-orders.deliver');


    Route::resource('lab-tests-bookings', LabTestBookingController::class)->names('admin.lab-tests-bookings');


    /*
    |--------------------------------------------------------------------------
    | Appointments
    |--------------------------------------------------------------------------
    */

    Route::get('appointments', [
        AppointmentController::class,
        'index'
    ])->name('admin.appointments.index');

    Route::get('appointments/{id}', [
        AppointmentController::class,
        'show'
    ])->name('admin.appointments.show');

    Route::post('appointments/status', [
        AppointmentController::class,
        'updateStatus'
    ])->name('admin.appointments.status');


    Route::get('appointments/{id}/reschedule', [
        AppointmentController::class,
        'reschedule'
    ])->name('admin.appointments.reschedule');
    Route::get('appointments/{id}/reschedule-slots', [
        AppointmentController::class,
        'getRescheduleSlots'
    ])->name('admin.appointments.reschedule.slots');

    Route::put('appointments/{id}/reschedule', [
        AppointmentController::class,
        'updateReschedule'
    ])->name('admin.appointments.reschedule.update');

    Route::get('patient-medical-reports', [PatientMedicalReportController::class, 'index'])->name('admin.patient-medical-reports.index');
    Route::get('patient-medical-reports/{id}', [PatientMedicalReportController::class, 'show'])->name('admin.patient-medical-reports.show');
    Route::get('patient-medical-reports/{id}/edit', [PatientMedicalReportController::class, 'edit'])->name('admin.patient-medical-reports.edit');
    Route::put('patient-medical-reports/{id}', [PatientMedicalReportController::class, 'update'])->name('admin.patient-medical-reports.update');
    Route::delete('patient-medical-reports/{id}', [PatientMedicalReportController::class, 'destroy'])->name('admin.patient-medical-reports.destroy');


    Route::resource('hospitals', HospitalController::class)->names('admin.hospitals');
    Route::post('hospitals/status/{hospital}', [HospitalController::class, 'status'])->name('admin.hospitals.status');

    Route::resource('specializations', SpecializationController::class)->names('admin.specializations');

    Route::post('specializations/status/{id}', [SpecializationController::class, 'status'])->name('admin.specializations.status');

    Route::resource('diagnostics', DiagnosticController::class)->names('admin.diagnostics');

    Route::post('diagnostics/status/{id}', [DiagnosticController::class, 'status'])
        ->name('admin.diagnostics.status');

    Route::resource('lab-tests', LabTestController::class)->names('admin.lab-tests');

    Route::post('lab-tests/status/{id}', [LabTestController::class, 'status'])->name('admin.lab-tests.status');

    Route::resource('ambulance-types', AmbulanceTypeController::class)->names('admin.ambulance-types');

    Route::post('ambulance-types/status/{id}', [AmbulanceTypeController::class, 'status'])->name('admin.ambulance-types.status');

    Route::resource('ambulances', AmbulanceController::class)->names('admin.ambulances');

    Route::post('ambulances/status/{id}', [AmbulanceController::class, 'status'])->name('admin.ambulances.status');

    Route::post('ambulances/availability/{id}', [AmbulanceController::class, 'availabilityStatus'])->name('admin.ambulances.availability');

    Route::resource('medicine-categories', MedicineCategoryController::class)->names('admin.medicine-categories');
    Route::post('medicine-categories/{id}/status', [MedicineCategoryController::class, 'status'])->name('admin.medicine-categories.status');

    Route::resource('medicines', MedicineController::class)->names('admin.medicines');

    Route::post('medicines/{id}/status', [MedicineController::class, 'status'])->name('admin.medicines.status');


    Route::get('coupons/{id}/status', [CouponController::class, 'status'])->name('admin.coupons.status');

    Route::resource('coupons', CouponController::class)->names('admin.coupons');


    Route::get('settings/company', 'SiteSettingController@site')->name('admin.settings.company');
    Route::post('setting/company/update', 'SiteSettingController@company_setting_update')->name('admin.settings.company.update');

    Route::resource('doctors', DoctorController::class)->names('admin.doctors');
    Route::post('doctors/{id}/status', [DoctorController::class, 'status'])->name('admin.doctors.status');

});

Route::get('forgot-password', [AuthController::class, 'showForgotForm'])
    ->name('admin.password.request');

Route::post('send-otp', [AuthController::class, 'sendOtp'])
    ->name('admin.password.sendOtp');

Route::get('verify-otp', [AuthController::class, 'showVerifyForm'])
    ->name('admin.password.verifyForm');

Route::post('verify-otp', [AuthController::class, 'verifyOtp'])
    ->name('admin.password.verifyOtp');

Route::post('reset-password-otp', [AuthController::class, 'resetPassword'])
    ->name('admin.password.resetOtp');





