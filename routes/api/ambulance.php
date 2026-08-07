<?php

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


    Route::get('booking-requests', [BookingController::class, 'bookingRequests']);

    Route::get('booking-details/{id}', [BookingController::class, 'bookingDetails']);

    Route::post('accept-booking', [BookingController::class, 'acceptBooking']);

    Route::post('reject-booking', [BookingController::class, 'rejectBooking']);

    Route::post('start-trip', [BookingController::class,'startTrip']);

    Route::post('complete-trip', [BookingController::class,'completeTrip']);

    Route::get('booking-history', [BookingController::class,'bookingHistory']);
});