<?php

use App\Http\Controllers\CustomerApp\AmbulanceController;
use App\Http\Controllers\CustomerApp\CCAvenueController;
use App\Http\Controllers\CustomerApp\DiagnosticController;
use App\Http\Controllers\CustomerApp\DoctorAppointmentController;
use App\Http\Controllers\CustomerApp\DoctorController;
use App\Http\Controllers\CustomerApp\LocationController;
use App\Http\Controllers\CustomerApp\ProfileController;
use App\Http\Controllers\CustomerApp\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('home', 'HomeController@index');
Route::post('login', 'AccountController@login');
Route::post('verifyMobile', 'AccountController@verifyMobile');
Route::post('resendOtp', 'AccountController@resendOtp');

Route::group(['middleware' => ['customertokenCheck']], function () {
    //Profile
    Route::get('logout', 'AccountController@logout');
    Route::get('profile', 'ProfileController@profile');
    Route::post('updateProfile', 'ProfileController@updateProfile');
    Route::post('/family-member/store', [ProfileController::class, 'storeFamilyMember']);
    Route::post('/family-member/update', [ProfileController::class, 'updateFamilyMember']);
    Route::post('/delete-family-member', [ProfileController::class, 'deleteFamilyMember']);

    //hospitals
    Route::any('hospitals', "HospitalController@hospitals");
    Route::any('specializations', "HospitalController@specializations");

    //Doctors
    Route::any('doctors', "DoctorController@doctors");
    Route::any('doctor-slots', [DoctorController::class, 'availableSlots']);

    //diagnostics
    Route::any('diagnostics', [DiagnosticController::class, 'diagnostics']);
    Route::any('lab-tests', [DiagnosticController::class, 'labTests']);
    Route::get('lab-tests/{id}/diagnostics', [DiagnosticController::class, 'labTestDiagnostics']);
    Route::get('diagnostics/{id}/lab-tests', [DiagnosticController::class, 'diagnosticLabTests']);

    // Ambulance Types
    Route::any('ambulance-types', [AmbulanceController::class, 'ambulanceTypes']);
    Route::get('ambulance-types/{id}', [AmbulanceController::class, 'ambulanceTypeDetails']);

    // Ambulances
    Route::any('ambulances', [AmbulanceController::class, 'ambulances']);
    Route::get('ambulances/{id}', [AmbulanceController::class, 'ambulanceDetails']);

    //Doctor Appointments
    Route::post('book-doctor-appointment', [DoctorAppointmentController::class, 'bookDoctorAppointment']);
    Route::any('myAppointments', [DoctorAppointmentController::class, 'myAppointments']);
    Route::post('cancel-appointment', [DoctorAppointmentController::class, 'cancelAppointment']);
    Route::post('reschedule-appointment', [DoctorAppointmentController::class, 'rescheduleAppointment']);
});
Route::any('/states', [LocationController::class, 'states']);
Route::any('/postalDetails', [LocationController::class, 'postalDetails']);
Route::get('/districts', [LocationController::class, 'districts']);