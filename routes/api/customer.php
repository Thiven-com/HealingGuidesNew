<?php

use App\Http\Controllers\CustomerApp\AddressController;
use App\Http\Controllers\CustomerApp\AmbulanceBookingController;
use App\Http\Controllers\CustomerApp\AmbulanceController;
use App\Http\Controllers\CustomerApp\BookAdmissionController;
use App\Http\Controllers\CustomerApp\CareServiceBookingController;
use App\Http\Controllers\CustomerApp\ChatbotController;
use App\Http\Controllers\CustomerApp\CouponController;
use App\Http\Controllers\CustomerApp\DiagnosticBookingController;
use App\Http\Controllers\CustomerApp\DiagnosticController;
use App\Http\Controllers\CustomerApp\DoctorAppointmentController;
use App\Http\Controllers\CustomerApp\DoctorController;
use App\Http\Controllers\CustomerApp\FamilyMemberHealthCheckupController;
use App\Http\Controllers\CustomerApp\HealthCheckupController;
use App\Http\Controllers\CustomerApp\HealthCheckupPackageBookingController;
use App\Http\Controllers\CustomerApp\HealthRecordController;
use App\Http\Controllers\CustomerApp\HomeController;
use App\Http\Controllers\CustomerApp\HomeVisitServiceCategoryController;
use App\Http\Controllers\CustomerApp\HomeVisitServiceController;
use App\Http\Controllers\CustomerApp\HospitalController;
use App\Http\Controllers\CustomerApp\HospitalTypeController;
use App\Http\Controllers\CustomerApp\LocationController;
use App\Http\Controllers\CustomerApp\MedicineController;
use App\Http\Controllers\CustomerApp\MedicineOrderController;
use App\Http\Controllers\CustomerApp\PatientMedicalReportController;
use App\Http\Controllers\CustomerApp\ProcedureController;
use App\Http\Controllers\CustomerApp\ProfileController;
use App\Http\Controllers\CustomerApp\SurgeryController;
use App\Http\Controllers\CustomerApp\SurgeryQuotationBookingController;
use App\Http\Controllers\CustomerApp\SurgeryQuotationController;
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
    Route::get(
        'specialization-categories',
        [HospitalController::class, 'specializationCategories']
    );

    Route::get('/hospital-types', [HospitalTypeController::class, 'index']);

    //health checkups
    Route::any('/health-checkups', [HealthCheckupController::class, 'index']);
    // Health Checkup Details + Packages
    Route::get(
        '/health-checkups/{id}',
        [HealthCheckupController::class, 'details']
    );

    // Health Checkup Packages
    Route::any(
        '/health-checkup-packages',
        [HealthCheckupController::class, 'packages']
    );

    // Health Checkup Package Details + Tests
    Route::get(
        '/health-checkup-packages/{id}',
        [HealthCheckupController::class, 'packageDetails']
    );

    // Package Tests
    Route::get(
        '/health-checkup-packages/{id}/tests',
        [HealthCheckupController::class, 'packageTests']
    );

    //BookAdmissionRequest
    Route::get(
        '/bookAdmissionRequest/alldata',
        [BookAdmissionController::class, 'alldata']
    );

    Route::post(
        '/bookAdmissionRequest',
        [BookAdmissionController::class, 'store']
    );

    Route::get(
        '/bookAdmissionRequest/list',
        [BookAdmissionController::class, 'index']
    );

    //Home Visit Service Category

    Route::get(
        '/home-visit-service-categories',
        [HomeVisitServiceCategoryController::class, 'index']
    );

    Route::get(
        '/home-visit-service-categories/{id}',
        [HomeVisitServiceCategoryController::class, 'show']
    );

    //HomeVisitService

    Route::get(
        '/home-visit-services',
        [HomeVisitServiceController::class, 'index']
    );

    Route::get(
        '/home-visit-services/{id}',
        [HomeVisitServiceController::class, 'show']
    );

    //Care Service Booking

    Route::post('/care-service-booking', [
        CareServiceBookingController::class,
        'store'
    ]);

    Route::get('/care-service-bookings', [
        CareServiceBookingController::class,
        'index'
    ]);

    Route::get('/care-service-booking/{id}', [
        CareServiceBookingController::class,
        'show'
    ]);

    Route::post('/care-service-booking/verify-payment', [
    CareServiceBookingController::class,
    'verifyPayment'
    ]);

    //Free Ambulance
    Route::get('/free-ambulance-labtest', [AmbulanceController::class, 'freeAmbulance']);

    Route::get(
        '/free-ambulance-diagnostics',
        [AmbulanceController::class, 'freeAmbulanceDiagnostics']
    );

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
    Route::get('chatbot/history/{conversation_id}', [ChatbotController::class, 'conversationHistory']);

    //Insurance
    Route::post('/insurance', [ProfileController::class, 'addInsurance']);

    Route::get('/insurances', [ProfileController::class, 'insurances']);

    Route::get('/insurance/{id}', [ProfileController::class, 'insuranceDetails']);

    Route::get('/delete_insurance/{id}', [ProfileController::class, 'deleteInsurance']);

    Route::get('/profile/package', [ProfileController::class, 'packageDetails']);

    Route::get('search', [HomeController::class, 'search']);

    //addresses
    Route::get('/addresses', [AddressController::class, 'addresses']);
    Route::get('/address/{id}', [AddressController::class, 'address']);
    Route::post('/address/add', [AddressController::class, 'addAddress']);
    Route::post('/address/edit/{id}', [AddressController::class, 'editAddress']);


    Route::get('/procedures', [ProcedureController::class, 'procedures']);

    Route::any(
        '/family-member-health-checkups',
        [FamilyMemberHealthCheckupController::class, 'index']
    );

    Route::get(
        '/family-member-health-checkups/{id}',
        [FamilyMemberHealthCheckupController::class, 'show']
    );

    Route::post('/health-checkup-package-bookings', [HealthCheckupPackageBookingController::class, 'store']);
    Route::post('/health-checkup-package-booking/payment/verify', [HealthCheckupPackageBookingController::class, 'verifyPayment']);
    Route::get('/health-checkup-package-bookings', [HealthCheckupPackageBookingController::class, 'index']);

    Route::get(
        '/surgeries',
        [SurgeryController::class, 'index']
    );

    Route::get(
        '/surgeries/{id}',
        [SurgeryController::class, 'show']
    );

    Route::post(
        '/surgery-quotation-request',
        [SurgeryQuotationController::class, 'store']
    );

    // My quotation requests
    Route::get(
        '/surgery-quotation-requests',
        [SurgeryQuotationController::class, 'index']
    );

    // Single request with quotations
    Route::get(
        '/surgery-quotation-requests/{id}',
        [SurgeryQuotationController::class, 'show']
    );

    // Accept quotation
    Route::post(
        '/surgery-quotations/{id}/accept',
        [SurgeryQuotationController::class, 'acceptQuotation']
    );

    // Reject quotation
    Route::post(
        '/surgery-quotations/{id}/reject',
        [SurgeryQuotationController::class, 'rejectQuotation']
    );

    Route::post('/surgery-quotation-bookings', [SurgeryQuotationBookingController::class, 'store']);
    Route::post('/surgery-quotation-bookings/verify-payment', [SurgeryQuotationBookingController::class, 'verifyPayment']);
    Route::get('/surgery-quotation-bookings', [SurgeryQuotationBookingController::class, 'bookings']);

});
Route::any('/states', [LocationController::class, 'states']);
Route::any('/postalDetails', [LocationController::class, 'postalDetails']);
Route::get('/districts', [LocationController::class, 'districts']);