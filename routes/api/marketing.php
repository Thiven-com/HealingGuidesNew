<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MarketingApp\AccountController;
use App\Http\Controllers\MarketingApp\ProfileController;
use App\Http\Controllers\MarketingApp\DashboardController;
use App\Http\Controllers\MarketingApp\LeadController;



Route::post('login', [AccountController::class, 'login']);

Route::post('verifyMobile', [AccountController::class, 'verifyMobile']);

Route::post('resendOtp', [AccountController::class, 'resendOtp']);


Route::group(['middleware' => ['marketingtokenCheck']], function () {

    Route::get('logout', [AccountController::class, 'logout']);

    Route::get('profile', [ProfileController::class, 'profile']);

    Route::post('update-profile', [ProfileController::class, 'updateProfile']);

    Route::post('update-location', [ProfileController::class, 'updateLocation']);
    //Dashboard
    Route::get('dashboard', [DashboardController::class, 'dashboard']);


    /*
    |--------------------------------------------------------------------------
    | Leads
    |--------------------------------------------------------------------------
    */

    Route::get('leads', [LeadController::class,'leads']);

    Route::get('lead-details/{id}', [LeadController::class,'leadDetails']);

    Route::post('add-lead', [LeadController::class,'addLead']);

    Route::post('update-lead', [LeadController::class,'updateLead']);

    Route::post('update-lead-status', [LeadController::class,'updateStatus']);
});