<?php

use App\Http\Controllers\HospitalApp\AppointmentController;
use App\Http\Controllers\HospitalApp\ProfileController;
use App\Http\Controllers\HospitalApp\DashboardController;
use App\Http\Controllers\HospitalApp\DoctorController;
use App\Http\Controllers\HospitalApp\DiagnosticController;
use App\Http\Controllers\HospitalApp\AmbulanceController;
use App\Http\Controllers\HospitalApp\AmbulanceBookingController;
use App\Http\Controllers\HospitalApp\MedicineController;
use App\Http\Controllers\HospitalApp\MedicinePrescriptionController;
use App\Http\Controllers\HospitalApp\MedicineOrderController;

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::post('login', 'AccountController@login');
Route::post('verifyMobile', 'AccountController@verifyMobile');
Route::post('resendOtp', 'AccountController@resendOtp');


Route::group(['middleware' => ['hospitaltokenCheck']], function () {
    //Profile
    Route::get('profile', [ProfileController::class, 'profile']);
});