<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MarketingApp\AccountController;
use App\Http\Controllers\MarketingApp\ProfileController;
use App\Http\Controllers\MarketingApp\DashboardController;
use App\Http\Controllers\MarketingApp\LeadController;
use App\Http\Controllers\MarketingApp\VisitController;
use App\Http\Controllers\MarketingApp\FollowupController;
use App\Http\Controllers\MarketingApp\OnboardingController;
use App\Http\Controllers\MarketingApp\ConversionController;



Route::post('login', [AccountController::class,'login']);

Route::post('verifyMobile', [AccountController::class,'verifyMobile']);

Route::post('resendOtp', [AccountController::class,'resendOtp']);


Route::group(['middleware' => ['marketingtokenCheck']], function () {

    Route::get('logout', [AccountController::class,'logout']);

    Route::get('profile', [ProfileController::class,'profile']);

    Route::post('update-profile', [ProfileController::class,'updateProfile']);

    Route::post('update-location', [ProfileController::class,'updateLocation']);
    //Dashboard
    Route::get('dashboard', [DashboardController::class,'dashboard']);


    /*
    |--------------------------------------------------------------------------
    | Leads
    |--------------------------------------------------------------------------
    */

    Route::get('leads', [
        LeadController::class,
        'leads'
    ]);

    Route::get('lead-details/{id}', [
        LeadController::class,
        'leadDetails'
    ]);

    Route::post('add-lead', [
        LeadController::class,
        'addLead'
    ]);

    Route::post('update-lead', [
        LeadController::class,
        'updateLead'
    ]);

    Route::post('update-lead-status', [
        LeadController::class,
        'updateStatus'
    ]);


    /*
    |--------------------------------------------------------------------------
    | Lead Visits
    |--------------------------------------------------------------------------
    */

    Route::get('visits', [
        VisitController::class,
        'visits'
    ]);

    Route::get('visit-details/{id}', [
        VisitController::class,
        'visitDetails'
    ]);

    Route::post('start-visit', [
        VisitController::class,
        'startVisit'
    ]);

    Route::post('complete-visit', [
        VisitController::class,
        'completeVisit'
    ]);


    /*
    |--------------------------------------------------------------------------
    | Follow Ups
    |--------------------------------------------------------------------------
    */

    Route::get('followups', [
        FollowupController::class,
        'followups'
    ]);

    Route::get('followup-details/{id}', [
        FollowupController::class,
        'followupDetails'
    ]);

    Route::post('add-followup', [
        FollowupController::class,
        'addFollowup'
    ]);

    Route::post('complete-followup', [
        FollowupController::class,
        'completeFollowup'
    ]);

    Route::post('reschedule-followup', [
        FollowupController::class,
        'rescheduleFollowup'
    ]);


    /*
    |--------------------------------------------------------------------------
    | Hospital / Diagnostic Onboarding
    |--------------------------------------------------------------------------
    */

    Route::post('hospital-onboarding', [
        OnboardingController::class,
        'hospitalOnboarding'
    ]);

    Route::post('diagnostic-onboarding', [
        OnboardingController::class,
        'diagnosticOnboarding'
    ]);

    Route::get('onboardings', [
        OnboardingController::class,
        'onboardings'
    ]);

    Route::get('onboarding-details/{id}', [
        OnboardingController::class,
        'onboardingDetails'
    ]);


    /*
    |--------------------------------------------------------------------------
    | Conversions
    |--------------------------------------------------------------------------
    */

    Route::get('conversions', [
        ConversionController::class,
        'conversions'
    ]);

    Route::get('conversion-details/{id}', [
        ConversionController::class,
        'conversionDetails'
    ]);

});