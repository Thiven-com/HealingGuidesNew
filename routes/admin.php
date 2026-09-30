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
use App\Http\Controllers\Admin\CustomerPackageController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DiagnosticController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\HealthCheckupPackageController;
use App\Http\Controllers\Admin\HealthCheckupTestController;
use App\Http\Controllers\Admin\HospitalController;
use App\Http\Controllers\Admin\HospitalFacilitiesListController;
use App\Http\Controllers\Admin\LabTestController;
use App\Http\Controllers\Admin\MarketingStaffController;
use App\Http\Controllers\Admin\MedicineCategoryController;
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ProcedureController;
use App\Http\Controllers\Admin\SpecializationController;
use App\Http\Controllers\Admin\AmbulanceBookingController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\DoctorAppointmentController;
use App\Http\Controllers\Admin\DoctorScheduleController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\HealthCheckupController;
use App\Http\Controllers\Admin\HospitalGalleryController;
use App\Http\Controllers\Admin\InsuranceController;
use App\Http\Controllers\Admin\LabTestBookingController;
use App\Http\Controllers\Admin\MarketingLeadController;
use App\Http\Controllers\Admin\MedicineOrderController;
use App\Http\Controllers\Admin\PatientMedicalReportController;
use App\Http\Controllers\Admin\MembershipRegistrationController;
use App\Http\Controllers\Admin\SpecializationCategoryController;
use App\Http\Controllers\Admin\TieupController;
use App\Http\Controllers\Admin\TieupsListController;
use Illuminate\Support\Facades\Route;





Route::get('/', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin-login', [AuthController::class, 'login'])->name('admin.loginAction');

Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');
Route::get('logout', [AuthController::class, 'logout']);

Route::group(['middleware' => 'admin'], function () {

    Route::get(
        '/health-checkups',
        [HealthCheckupController::class, 'index']
    )->name('admin.healthcheckups.index');

    Route::post(
        '/health-checkups',
        [HealthCheckupController::class, 'store']
    )->name('admin.healthcheckups.store');

    Route::put(
        '/health-checkups/{id}',
        [HealthCheckupController::class, 'update']
    )->name('admin.healthcheckups.update');

    Route::delete(
        '/health-checkups/{id}',
        [HealthCheckupController::class, 'destroy']
    )->name('admin.healthcheckups.destroy');

    // Route::post(
    //     '/tieups',
    //     [TieupsListController::class, 'store']
    // )->name('admin.tieuplist.store');

    // Route::put(
    //     '/tieups/{id}',
    //     [TieupsListController::class, 'update']
    // )->name('admin.tieuplist.update');

    // Route::delete(
    //     '/tieups/{id}',
    //     [TieupsListController::class, 'destroy']
    // )->name('admin.tieuplist.destroy');

    Route::resource('tieuplist', TieupsListController::class)
        ->names('admin.tieuplist');


    Route::get(
        'specialization-categories',
        [SpecializationCategoryController::class, 'index']
    )->name('admin.specialization-categories.index');

    Route::get(
        'specialization-categories/create',
        [SpecializationCategoryController::class, 'create']
    )->name('admin.specialization-categories.create');

    Route::post(
        'specialization-categories',
        [SpecializationCategoryController::class, 'store']
    )->name('admin.specialization-categories.store');

    Route::get(
        'specialization-categories/{id}/edit',
        [SpecializationCategoryController::class, 'edit']
    )->name('admin.specialization-categories.edit');

    Route::put(
        'specialization-categories/{id}',
        [SpecializationCategoryController::class, 'update']
    )->name('admin.specialization-categories.update');

    Route::delete(
        'specialization-categories/{id}',
        [SpecializationCategoryController::class, 'destroy']
    )->name('admin.specialization-categories.destroy');


    Route::get(
        'hospital-facilities-list',
        [HospitalFacilitiesListController::class, 'index']
    )->name('admin.hospital-facilities-list.index');

    Route::get(
        'hospital-facilities-list/create',
        [HospitalFacilitiesListController::class, 'create']
    )->name('admin.hospital-facilities-list.create');

    Route::post(
        'hospital-facilities-list',
        [HospitalFacilitiesListController::class, 'store']
    )->name('admin.hospital-facilities-list.store');

    Route::get(
        'hospital-facilities-list/{id}/edit',
        [HospitalFacilitiesListController::class, 'edit']
    )->name('admin.hospital-facilities-list.edit');

    Route::put(
        'hospital-facilities-list/{id}',
        [HospitalFacilitiesListController::class, 'update']
    )->name('admin.hospital-facilities-list.update');

    Route::delete(
        'hospital-facilities-list/{id}',
        [HospitalFacilitiesListController::class, 'destroy']
    )->name('admin.hospital-facilities-list.destroy');

    Route::resource('facilities', FacilityController::class)
        ->names('admin.facilities');
    Route::get(
        '/hospitals/facility/{facility}',
        [HospitalController::class, 'facilityDetails']
    )->name('admin.hospitals.facility.details');

    Route::get(
        '/hospital-galleries',
        [HospitalGalleryController::class, 'index']
    )->name('hospital-galleries.index');

    Route::post(
        '/hospital-galleries/store',
        [HospitalGalleryController::class, 'store']
    )->name('admin.hospital-galleries.store');

    Route::get(
        '/hospital-galleries/{id}',
        [HospitalGalleryController::class, 'show']
    )->name('hospital-galleries.show');

    Route::delete(
        '/hospital-galleries/{id}',
        [HospitalGalleryController::class, 'destroy']
    )->name('admin.hospital-galleries.destroy');


    Route::get(
        '/tieups',
        [TieupController::class, 'index']
    )->name('admin.tieups.index');

    Route::post(
        '/tieups',
        [TieupController::class, 'store']
    )->name('admin.tieups.store');

    Route::get(
        '/tieups/{id}/edit',
        [TieupController::class, 'edit']
    )->name('admin.tieups.edit');

    Route::put(
        '/tieups/{id}',
        [TieupController::class, 'update']
    )->name('admin.tieups.update');

    Route::delete(
        '/tieups/{id}',
        [TieupController::class, 'destroy']
    )->name('admin.tieups.destroy');


    Route::get(
        '/hospitals/tieups/{tieup}/details',
        [HospitalController::class, 'tieupsDetails']
    )->name('admin.hospitals.tieups-details');

    Route::delete(
        '/hospitals/{hospital}/banner/delete',
        [HospitalController::class, 'deleteBanner']
    )->name('admin.hospitals.banner.delete');


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

    Route::get('/insurances', [InsuranceController::class, 'index'])->name('admin.insurances.all');
    Route::get('/marketing-leads', [MarketingLeadController::class, 'index'])->name('admin.marketing-leads.all');
    Route::get('/marketing-staff', [MarketingStaffController::class, 'index'])->name('admin.marketing-staff.all');
    Route::get('/membership-registrations', [MembershipRegistrationController::class, 'index'])->name('admin.membership-registrations.all');
    Route::get('/doctor-schedules/create', [DoctorScheduleController::class, 'create'])->name('admin.doctor-schedules.create');

    Route::post('doctor-schedules', [DoctorScheduleController::class, 'store'])->name('admin.doctor-schedules.store');
    Route::get('doctor-schedules/{doctorSchedule}/edit', [DoctorScheduleController::class, 'edit'])->name('admin.doctor-schedules.edit');

    Route::put('doctor-schedules/{doctorSchedule}', [DoctorScheduleController::class, 'update'])->name('admin.doctor-schedules.update');


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

    Route::resource('packages', PackageController::class)->names('admin.packages');

    Route::post('packages/{id}/status', [PackageController::class, 'status'])->name('admin.packages.status');

    Route::get(
        'customers/{customer}/package',
        [CustomerPackageController::class, 'show']
    )->name('customers.package.show');

    Route::post('customers/{customer}/package', [CustomerPackageController::class, 'update'])->name('customers.package.update');

    Route::resource(
        'procedures',
        ProcedureController::class
    )->names('admin.procedures');
    Route::post('procedures/{id}/status', [CustomerPackageController::class, 'status'])
        ->name('admin.procedures.status');

    Route::prefix('health-checkup-tests')
        ->name('admin.health-checkup-tests.')
        ->controller(HealthCheckupTestController::class)
        ->group(function () {

            Route::get('/', 'index')
                ->name('index');

            Route::get('/create', 'create')
                ->name('create');

            Route::post('/', 'store')
                ->name('store');

            Route::get('/{healthCheckupTest}', 'show')
                ->name('show');

            Route::get('/{healthCheckupTest}/edit', 'edit')
                ->name('edit');

            Route::put('/{healthCheckupTest}', 'update')
                ->name('update');

            Route::delete('/{healthCheckupTest}', 'destroy')
                ->name('destroy');

            Route::post('/{healthCheckupTest}/status', 'status')
                ->name('status');
        });

    Route::resource(
        'health-checkup-packages',
        HealthCheckupPackageController::class
    )->names('admin.health-checkup-packages');
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





