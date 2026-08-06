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
    Route::get('logout', 'AccountController@logout');
    Route::get('profile', [ProfileController::class, 'profile']);

    Route::post('update-profile', [ProfileController::class, 'updateProfile']);
    //Dashboard
    Route::get('dashboard', [DashboardController::class, 'dashboard']);
    //Doctors
    Route::any('doctors', [DoctorController::class, 'doctors']);

    Route::get('doctor-details/{id}', [DoctorController::class, 'doctorDetails']);

    Route::post('create-doctor', [DoctorController::class, 'createDoctor']);

    Route::post('update-doctor', [DoctorController::class, 'updateDoctor']);

    Route::post('update-doctor-status', [DoctorController::class, 'updateStatus']);
    //Diagnostics/Lab tests
    Route::get('lab-tests', [DiagnosticController::class, 'labTests']);
    Route::any('diagnostics', [DiagnosticController::class, 'diagnostics']);
    Route::get('diagnostics/{id}/lab-tests', [DiagnosticController::class, 'diagnosticLabTests']);

    //Ambulances
    Route::any('ambulance-types', [AmbulanceController::class, 'ambulanceTypes']);

    Route::any('ambulances', [AmbulanceController::class, 'ambulances']);

    Route::get('ambulance-details/{id}', [AmbulanceController::class, 'ambulanceDetails']);

    Route::post('add-ambulance', [AmbulanceController::class, 'addAmbulance']);

    Route::post('update-ambulance', [AmbulanceController::class, 'updateAmbulance']);

    Route::post('update-ambulance-availability', [AmbulanceController::class, 'updateAvailability']);
    //Ambulance Requests
    Route::get('ambulance-bookings', [AmbulanceBookingController::class, 'bookings']);
    Route::get('ambulance-booking-details/{id}', [AmbulanceBookingController::class, 'bookingDetails']);
    Route::post('assign-ambulance', [AmbulanceBookingController::class, 'assignAmbulance']);
    Route::post('reject-ambulance-request', [AmbulanceBookingController::class, 'rejectRequest']);

    Route::post('ambulance-trip-start', [AmbulanceBookingController::class, 'startTrip']);

    Route::post('ambulance-trip-complete', [AmbulanceBookingController::class, 'completeTrip']);
    //Medicine Inventory
    Route::any('medicine-categories', [MedicineController::class, 'categories']);

    Route::any('medicines', [MedicineController::class, 'medicines']);

    Route::get('medicine-details/{id}', [MedicineController::class, 'medicineDetails']);

    Route::post('add-medicine', [MedicineController::class, 'addMedicine']);

    Route::post('update-medicine', [MedicineController::class, 'updateMedicine']);

    Route::post('update-medicine-stock', [MedicineController::class, 'updateStock']);

    Route::post('update-medicine-status', [MedicineController::class, 'updateStatus']);
    //Medicine Orders
    Route::get('medicine-orders', [MedicineOrderController::class, 'orders']);
    Route::get('medicine-order-details/{id}', [MedicineOrderController::class, 'orderDetails']);
    Route::post('accept-medicine-order', [MedicineOrderController::class, 'acceptOrder']);
    Route::post('reject-medicine-order', [MedicineOrderController::class, 'rejectOrder']);

    Route::post('process-medicine-order', [MedicineOrderController::class, 'processingOrder']);

    Route::post('ready-medicine-order', [MedicineOrderController::class, 'readyOrder']);

    Route::post('dispatch-medicine-order', [MedicineOrderController::class, 'dispatchOrder']);

    Route::post('deliver-medicine-order', [MedicineOrderController::class, 'deliverOrder']);
    //Appointments
    Route::any('appointments', [AppointmentController::class, 'appointments']);

    Route::get('appointment-details/{id}', [AppointmentController::class, 'appointmentDetails']);
    Route::get('appointment-summary', [AppointmentController::class, 'appointmentSummary']);
});