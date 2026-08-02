<?php

use App\Http\Controllers\DoctorApp\AppointmentController;
use App\Http\Controllers\DoctorApp\DoctorScheduleController;
use App\Http\Controllers\DoctorApp\PatientMedicalReportController;
use App\Http\Controllers\DoctorApp\PatientVitalController;
use App\Http\Controllers\DoctorApp\PrescriptionController;
use App\Http\Controllers\DoctorApp\ProfileController;
use App\Http\Controllers\DoctorApp\VideoCallController;
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
Route::post('login', 'AccountController@login');
Route::post('verifyMobile', 'AccountController@verifyMobile');
Route::post('resendOtp', 'AccountController@resendOtp');

Route::group(['middleware' => ['doctortokenCheck']], function () {
    //Profile
    Route::get('logout', 'AccountController@logout');
    Route::get('profile', [ProfileController::class, 'profile']);
    Route::get('dashboard', [ProfileController::class, 'dashboard']);
    Route::get('todaySummary', [ProfileController::class, 'todaySummary']);
    Route::get('statistics', [ProfileController::class, 'statistics']);
    Route::any('specializations', [ProfileController::class, 'specializations']);
    Route::any('doctor-slots', [ProfileController::class, 'availableSlots']);
    Route::post('updateFees', [ProfileController::class, 'updateFees']);
    //Doctor Module
    Route::get('schedules', [DoctorScheduleController::class, 'schedules']);

    Route::post('save-schedule', [DoctorScheduleController::class, 'saveSchedule']);

    //Patient Medical Reports
    Route::post('upload-medical-report', [PatientMedicalReportController::class, 'uploadReport']);

    Route::get('patient-medical-reports/{appointment_id}', [PatientMedicalReportController::class, 'reports']);

    Route::get('patient-medical-report/{id}', [PatientMedicalReportController::class, 'reportDetails']);

    Route::post('update-medical-report', [PatientMedicalReportController::class, 'updateReport']);

    Route::post('delete-medical-report', [PatientMedicalReportController::class, 'deleteReport']);


    //Appointments
    Route::any('appointments', [AppointmentController::class, 'appointments']);
    Route::get('appointment-details/{id}',[AppointmentController::class, 'appointmentDetails']);
    Route::post('accept-appointment', [AppointmentController::class, 'acceptAppointment']);
    Route::post('reject-appointment', [AppointmentController::class, 'rejectAppointment']);
    Route::post('start-consultation', [AppointmentController::class, 'startConsultation']);
    Route::post('complete-consultation', [AppointmentController::class, 'completeConsultation']);
    Route::post('cancel-appointment', [AppointmentController::class, 'cancelAppointment']);
    Route::post('reschedule-appointment', [AppointmentController::class, 'rescheduleAppointment']);

    //Room Video
    Route::post('join-video-room', [VideoCallController::class, 'joinVideoRoom']);
    Route::get('video-room-status/{room}', [VideoCallController::class, 'videoRoomStatus']);
    Route::post('leave-video-room', [VideoCallController::class, 'leaveVideoRoom']);
    Route::post('end-video-call', [VideoCallController::class, 'endVideoCall']);

    Route::post('save-patient-vitals', [PatientVitalController::class, 'savePatientVitals']);

    Route::get('patient-vitals/{appointment_id}', [PatientVitalController::class, 'patientVitals']);

    Route::get('patient-vitals-history/{customer_id}', [PatientVitalController::class, 'patientVitalsHistory']);

    Route::post('create-prescription', [PrescriptionController::class, 'createPrescription']);
    Route::post('update-prescription', [PrescriptionController::class, 'updatePrescription']);
    Route::get('prescription/{appointment_id}', [PrescriptionController::class, 'prescriptionDetails']);

});
