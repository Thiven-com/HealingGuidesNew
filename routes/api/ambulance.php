<?php

use App\Http\Controllers\AmbulanceApp\EarningController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AmbulanceApp\AccountController;
use App\Http\Controllers\AmbulanceApp\ProfileController;
use App\Http\Controllers\AmbulanceApp\DashboardController;
use App\Http\Controllers\AmbulanceApp\BookingController;


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::post('login', [AccountController::class, 'login']);

Route::post('verifyMobile', [AccountController::class, 'verifyMobile']);

Route::post('resendOtp', [AccountController::class, 'resendOtp']);


/*
|--------------------------------------------------------------------------
| Authenticated APIs
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['ambulancetokenCheck']], function () {

    Route::get('logout', [AccountController::class, 'logout']);

    Route::get('profile', [ProfileController::class, 'profile']);
    Route::post('update-profile', [ProfileController::class, 'updateProfile']);
    Route::post('update-location', [ProfileController::class, 'updateLocation']);

    Route::post('update-availability', [ProfileController::class, 'updateAvailability']);


    Route::get('dashboard', [DashboardController::class, 'dashboard']);


    Route::get('bookings', [BookingController::class,'bookings']);

    Route::get('booking-details/{id}', [BookingController::class, 'bookingDetails']);

    Route::post('start-trip', [BookingController::class, 'startTrip']);
    Route::post('update-trip-location', [BookingController::class, 'updateTripLocation']);
    Route::post('complete-trip', [BookingController::class, 'completeTrip']);
    Route::get('active-trip', [BookingController::class, 'activeTrip']);

    Route::get('earnings', [EarningController::class, 'earnings']);
});