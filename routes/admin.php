<?php

use App\Http\Controllers\Admin\AmbulanceController;
use App\Http\Controllers\Admin\AmbulanceTypeController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DiagnosticController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\HospitalController;
use App\Http\Controllers\Admin\LabTestController;
use App\Http\Controllers\Admin\MedicineCategoryController;
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\Admin\SpecializationController;
use Illuminate\Support\Facades\Route;





Route::get('/', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin-login', [AuthController::class, 'login'])->name('admin.loginAction');

Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');
Route::get('logout', [AuthController::class, 'logout']);

Route::group(['middleware' => 'admin'], function () {
    Route::get('dashboard', [AuthController::class, 'dashboard'])
        ->name('admin.dashboard');

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





