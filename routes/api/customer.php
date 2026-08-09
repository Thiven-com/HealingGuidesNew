<?php

use App\Http\Controllers\CustomerApp\AmbulanceBookingController;
use App\Http\Controllers\CustomerApp\AmbulanceController;
use App\Http\Controllers\CustomerApp\ChatbotController;
use App\Http\Controllers\CustomerApp\CouponController;
use App\Http\Controllers\CustomerApp\DiagnosticBookingController;
use App\Http\Controllers\CustomerApp\DiagnosticController;
use App\Http\Controllers\CustomerApp\DoctorAppointmentController;
use App\Http\Controllers\CustomerApp\DoctorController;
use App\Http\Controllers\CustomerApp\HealthRecordController;
use App\Http\Controllers\CustomerApp\LocationController;
use App\Http\Controllers\CustomerApp\MedicineController;
use App\Http\Controllers\CustomerApp\MedicineOrderController;
use App\Http\Controllers\CustomerApp\PatientMedicalReportController;
use App\Http\Controllers\CustomerApp\ProfileController;
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
Route::post('login', 'AccountController@login');
Route::post('verifyMobile', 'AccountController@verifyMobile');
Route::post('resendOtp', 'AccountController@resendOtp');

Route::group(['middleware' => ['customertokenCheck']], function () {
    //Profile
    Route::get('logout', 'AccountController@logout');
    Route::get('profile', 'ProfileController@profile');
    Route::get('notifications', 'ProfileController@notifications');
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

    // Ambulance Booking
    Route::post('request-ambulance', [AmbulanceBookingController::class, 'requestAmbulance']);

    Route::get('my-ambulance-bookings', [AmbulanceBookingController::class, 'myBookings']);

    Route::get('ambulance-booking-details/{id}', [AmbulanceBookingController::class, 'bookingDetails']);

    Route::post('cancel-ambulance-booking', [AmbulanceBookingController::class, 'cancelBooking']);

    Route::post('pay-ambulance-booking', [AmbulanceBookingController::class, 'payBooking']);

    //Doctor Appointments
    Route::post('book-doctor-appointment', [DoctorAppointmentController::class, 'bookDoctorAppointment']);
    Route::any('myAppointments', [DoctorAppointmentController::class, 'myAppointments']);
    Route::get('appointment-details/{id}', [DoctorAppointmentController::class, 'appointmentDetails']);

    Route::post('cancel-appointment', [DoctorAppointmentController::class, 'cancelAppointment']);
    Route::post('reschedule-appointment', [DoctorAppointmentController::class, 'rescheduleAppointment']);
    Route::post('pay-appointment', [DoctorAppointmentController::class, 'payAppointment']);
    Route::post('join-video-room', [DoctorAppointmentController::class, 'joinVideoRoom']);


    // Diagnostic Booking
    Route::post('book-lab-test', [DiagnosticBookingController::class, 'bookLabTest']);

    Route::get('my-lab-bookings', [DiagnosticBookingController::class, 'myBookings']);

    Route::get('lab-booking-details/{id}', [DiagnosticBookingController::class, 'bookingDetails']);
    Route::post('pay-lab-booking', [DiagnosticBookingController::class, 'payBooking']);
    Route::post('cancel-lab-booking', [DiagnosticBookingController::class, 'cancelBooking']);


    // Patient Medical Reports
    Route::post('upload-medical-report', [PatientMedicalReportController::class, 'uploadReport']);

    Route::get('medical-reports', [PatientMedicalReportController::class, 'reports']);

    Route::get('medical-report/{id}', [PatientMedicalReportController::class, 'reportDetails']);

    Route::post('update-medical-report', [PatientMedicalReportController::class, 'updateReport']);

    Route::post('delete-medical-report', [PatientMedicalReportController::class, 'deleteReport']);

    // Customer Health Records
// Health Records
    Route::get('my-prescriptions', [HealthRecordController::class, 'prescriptions']);
    Route::get('prescription-details/{id}', [HealthRecordController::class, 'prescriptionDetails']);

    Route::get('my-vitals', [HealthRecordController::class, 'vitals']);
    Route::get('vital-details/{id}', [HealthRecordController::class, 'vitalDetails']);

    //Medicines
    Route::get('medicine-categories', [MedicineController::class, 'categories']);

    Route::any('medicines', [MedicineController::class, 'medicines']);

    Route::get('medicine-details/{id}', [MedicineController::class, 'medicineDetails']);

    // Medicine Orders
    Route::post('place-medicine-order', [MedicineOrderController::class, 'placeOrder']);
    Route::get('my-medicine-orders', [MedicineOrderController::class, 'myOrders']);

    Route::get('medicine-order-details/{id}', [MedicineOrderController::class, 'orderDetails']);

    Route::post('cancel-medicine-order', [MedicineOrderController::class, 'cancelOrder']);
    Route::post('payMedicineOrder', [MedicineOrderController::class, 'payMedicineOrder']);


    //Coupons
    Route::get('/coupons', [CouponController::class, 'myCoupons']);

    Route::post('/coupons/validate', [CouponController::class, 'validate']);

    Route::get('/coupons/usage-history', [CouponController::class, 'usageHistory']);
    //Chatbot
    Route::post('chatbot/start', [ChatbotController::class, 'start']);

    Route::post('chatbot/message', [ChatbotController::class, 'sendMessage']);

    Route::any('chatbot/history', [ChatbotController::class, 'history']);
    Route::get('chatbot/history/{conversation_id}',[ChatbotController::class, 'conversationHistory']


);

});
Route::any('/states', [LocationController::class, 'states']);
Route::any('/postalDetails', [LocationController::class, 'postalDetails']);
Route::get('/districts', [LocationController::class, 'districts']);