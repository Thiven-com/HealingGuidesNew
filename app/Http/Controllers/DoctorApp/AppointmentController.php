<?php

namespace App\Http\Controllers\DoctorApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorAppointmentCollection;
use App\Http\Resources\DoctorAppointmentDetailCollection;
use App\Http\Resources\LabTestCollection;
use App\Models\DoctorAppointment;
use App\Models\DoctorSchedule;
use App\Models\LabTest;
use App\Models\VideoRoom;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    //
    public function appointments(Request $request)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $appointments = DoctorAppointment::with([
            'doctor.hospitalSpecialization.specialization',
            'doctor.hospital',
            'familyMember'
        ])
            ->where('doctor_id', $doctor->id);

        if ($request->filled('id')) {
            $appointments->where('id', $request->id);
        }

        if ($request->filled('appointment_status')) {
            $appointments->where('appointment_status', $request->appointment_status);
        }

        if ($request->filled('payment_status')) {
            $appointments->where('payment_status', $request->payment_status);
        }

        if ($request->filled('appointment_date')) {
            $appointments->whereDate('appointment_date', $request->appointment_date);
        }

        $appointments = $appointments->latest()->paginate(20);

        if ($appointments->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'No Appointments Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => new DoctorAppointmentCollection($appointments),
            'message' => 'Appointments Fetched Successfully'
        ]);
    }

    public function appointmentDetails($id)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $appointments = DoctorAppointment::with([

            'doctor.hospitalSpecialization.specialization',

            'doctor.hospital',

            'customer',

            'familyMember',

            'doctorSchedule',

            'patientVitals',

            'prescription.medicines',

            'prescription.recommendedLabTests.labTest',

            'medicalReports',

            'videoRoom',

        ])
            ->where('doctor_id', $doctor->id)
            ->where('id', $id)
            ->get();

        if ($appointments->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'Appointment Not Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => new DoctorAppointmentDetailCollection($appointments),
            'message' => 'Appointment Details Fetched Successfully'
        ]);
    }

    public function acceptAppointment(Request $request)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:doctor_appointments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $appointment = DoctorAppointment::where('id', $request->appointment_id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$appointment) {
            return response()->json([
                'success' => 0,
                'message' => 'Appointment not found.'
            ]);
        }

        if ($appointment->appointment_status != 'pending') {
            return response()->json([
                'success' => 0,
                'message' => 'Only pending appointments can be accepted.'
            ]);
        }

        $appointment->appointment_status = 'confirmed';
        $appointment->save();

        return response()->json([
            'success' => 1,
            'message' => 'Appointment accepted successfully.'
        ]);
    }

    public function rejectAppointment(Request $request)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:doctor_appointments,id',
            'reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $appointment = DoctorAppointment::where('id', $request->appointment_id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$appointment) {
            return response()->json([
                'success' => 0,
                'message' => 'Appointment not found.'
            ]);
        }

        $appointment->appointment_status = 'rejected';
        $appointment->remarks = $request->reason;
        $appointment->save();

        return response()->json([
            'success' => 1,
            'message' => 'Appointment rejected successfully.'
        ]);
    }
    public function startConsultation(Request $request)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:doctor_appointments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $appointment = DoctorAppointment::where('id', $request->appointment_id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$appointment) {
            return response()->json([
                'success' => 0,
                'message' => 'Appointment not found.'
            ]);
        }

        if ($appointment->appointment_status != 'confirmed') {
            return response()->json([
                'success' => 0,
                'message' => 'Appointment is not confirmed.'
            ]);
        }

        $appointment->appointment_status = 'in_progress';

        if ($appointment->consultation_type == 'video') {
            $appointment->meeting_started_at = now();
            $appointment->meeting_status = 'live';
        }

        if ($appointment->consultation_type == 'chat') {
            $appointment->chat_started_at = now();
        }

        if ($appointment->consultation_type == 'home_visit') {
            $appointment->doctor_started_at = now();
            $appointment->visit_status = 'doctor_started';
        }

        $appointment->save();

        return response()->json([
            'success' => 1,
            'message' => 'Consultation started successfully.'
        ]);
    }
    public function completeConsultation(Request $request)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:doctor_appointments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $appointment = DoctorAppointment::where('id', $request->appointment_id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$appointment) {
            return response()->json([
                'success' => 0,
                'message' => 'Appointment not found.'
            ]);
        }

        $appointment->appointment_status = 'completed';

        if ($appointment->consultation_type == 'video') {
            $appointment->meeting_status = 'ended';
            $appointment->meeting_ended_at = now();
        }

        if ($appointment->consultation_type == 'chat') {
            $appointment->chat_ended_at = now();
        }

        if ($appointment->consultation_type == 'home_visit') {
            $appointment->visit_completed_at = now();
            $appointment->visit_status = 'completed';
        }

        $appointment->save();

        return response()->json([
            'success' => 1,
            'message' => 'Consultation completed successfully.'
        ]);
    }

    public function cancelAppointment(Request $request)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:doctor_appointments,id',
            'cancel_reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $appointment = DoctorAppointment::where('id', $request->appointment_id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$appointment) {
            return response()->json([
                'success' => 0,
                'message' => 'Appointment not found.'
            ]);
        }

        if ($appointment->appointment_status == 'cancelled') {
            return response()->json([
                'success' => 0,
                'message' => 'Appointment already cancelled.'
            ]);
        }

        if ($appointment->appointment_status == 'completed') {
            return response()->json([
                'success' => 0,
                'message' => 'Completed appointment cannot be cancelled.'
            ]);
        }

        if ($appointment->appointment_status == 'in_progress') {
            return response()->json([
                'success' => 0,
                'message' => 'Ongoing consultation cannot be cancelled.'
            ]);
        }

        $appointmentDateTime = Carbon::parse(
            $appointment->appointment_date . ' ' . $appointment->appointment_time
        );

        if ($appointmentDateTime->isPast()) {
            return response()->json([
                'success' => 0,
                'message' => 'Past appointments cannot be cancelled.'
            ]);
        }

        $appointment->appointment_status = 'cancelled';
        $appointment->cancel_reason = $request->cancel_reason;
        $appointment->cancelled_at = now();

        // // Recommended if these columns exist
        // $appointment->cancelled_by = 'doctor';

        $appointment->save();

        /*
        |--------------------------------------------------------------------------
        | Video Consultation
        |--------------------------------------------------------------------------
        */

        if ($appointment->consultation_type == 'video') {

            $room = VideoRoom::where('appointment_id', $appointment->id)->first();

            if ($room && !in_array($room->status, ['ended', 'cancelled'])) {

                $room->status = 'cancelled';
                $room->ended_at = now();
                $room->ended_by = 'doctor';
                $room->end_reason = $request->cancel_reason;
                $room->doctor_online = false;
                $room->customer_online = false;

                $room->save();
            }

            $appointment->meeting_status = 'cancelled';
            $appointment->save();
        }

        return response()->json([
            'success' => 1,
            'message' => 'Appointment cancelled successfully.',
            'data' => $appointment
        ]);
    }

    public function rescheduleAppointment(Request $request)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [

            'appointment_id' => 'required|exists:doctor_appointments,id',

            'doctor_schedule_id' => 'required|exists:doctor_schedules,id',

            'appointment_date' => 'required|date',

            'appointment_time' => 'required',

            'reschedule_reason' => 'nullable|string|max:500',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Get Doctor Appointment
        |--------------------------------------------------------------------------
        */

        $appointment = DoctorAppointment::where('id', $request->appointment_id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$appointment) {
            return response()->json([
                'success' => 0,
                'message' => 'Appointment not found.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Status Validation
        |--------------------------------------------------------------------------
        */

        if ($appointment->appointment_status == 'cancelled') {
            return response()->json([
                'success' => 0,
                'message' => 'Cancelled appointment cannot be rescheduled.'
            ]);
        }

        if ($appointment->appointment_status == 'completed') {
            return response()->json([
                'success' => 0,
                'message' => 'Completed appointment cannot be rescheduled.'
            ]);
        }

        if ($appointment->appointment_status == 'in_progress') {
            return response()->json([
                'success' => 0,
                'message' => 'Ongoing consultation cannot be rescheduled.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Old Appointment
        |--------------------------------------------------------------------------
        */

        // $oldAppointmentDateTime = Carbon::parse(
        //     $appointment->appointment_date . ' ' . $appointment->appointment_time
        // );

        // if ($oldAppointmentDateTime->isPast()) {
        //     return response()->json([
        //         'success' => 0,
        //         'message' => 'Past appointments cannot be rescheduled.'
        //     ]);
        // }

        /*
        |--------------------------------------------------------------------------
        | New Date Time
        |--------------------------------------------------------------------------
        */

        $newAppointmentDateTime = Carbon::parse(
            $request->appointment_date . ' ' . $request->appointment_time
        );

        if ($newAppointmentDateTime->lte(now())) {
            return response()->json([
                'success' => 0,
                'message' => 'Please select a future date and time.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Same Slot Check
        |--------------------------------------------------------------------------
        */

        if (
            $appointment->doctor_schedule_id == $request->doctor_schedule_id &&
            Carbon::parse($appointment->appointment_date)->format('Y-m-d')
            == Carbon::parse($request->appointment_date)->format('Y-m-d') &&
            Carbon::parse($appointment->appointment_time)->format('H:i')
            == Carbon::parse($request->appointment_time)->format('H:i')
        ) {

            return response()->json([
                'success' => 0,
                'message' => 'Appointment is already scheduled for the selected date and time.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Doctor Schedule
        |--------------------------------------------------------------------------
        */

        $schedule = DoctorSchedule::where('id', $request->doctor_schedule_id)
            ->where('doctor_id', $doctor->id)
            ->where('status', 1)
            ->first();

        if (!$schedule) {
            return response()->json([
                'success' => 0,
                'message' => 'Doctor schedule not found.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Slot
        |--------------------------------------------------------------------------
        */

        $requestedTime = Carbon::parse(
            $request->appointment_time
        )->format('H:i');

        $start = Carbon::parse($schedule->available_from);

        $end = Carbon::parse($schedule->available_to);

        $isValidSlot = false;

        while ($start < $end) {

            // Lunch Break
            if (
                $start->format('H:i') >= '13:00' &&
                $start->format('H:i') < '14:00'
            ) {

                $start->addMinutes($schedule->slot_duration);

                continue;
            }

            if ($start->format('H:i') == $requestedTime) {

                $isValidSlot = true;

                break;
            }

            $start->addMinutes($schedule->slot_duration);
        }

        if (!$isValidSlot) {
            return response()->json([
                'success' => 0,
                'message' => 'Please select a valid available time slot.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Slot Already Booked
        |--------------------------------------------------------------------------
        */

        $alreadyBooked = DoctorAppointment::where('doctor_id', $doctor->id)

            ->whereDate(
                'appointment_date',
                $request->appointment_date
            )

            ->whereTime(
                'appointment_time',
                $request->appointment_time
            )

            ->where('id', '!=', $appointment->id)

            ->whereNotIn('appointment_status', [
                'cancelled',
                'rejected'
            ])

            ->exists();

        if ($alreadyBooked) {
            return response()->json([
                'success' => 0,
                'message' => 'Selected slot already booked.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Store Previous Appointment Information
        |--------------------------------------------------------------------------
        */

        $oldDate = $appointment->appointment_date;

        $oldTime = $appointment->appointment_time;

        /*
        |--------------------------------------------------------------------------
        | Update Appointment
        |--------------------------------------------------------------------------
        */

        $appointment->doctor_schedule_id = $request->doctor_schedule_id;

        $appointment->appointment_date = $request->appointment_date;

        $appointment->appointment_time = $request->appointment_time;

        /*
         * Since doctor initiated the reschedule,
         * customer should know that the schedule changed.
         */
        $appointment->appointment_status = 'rescheduled';

        // $appointment->rescheduled_by = 'doctor';

        // $appointment->rescheduled_at = now();

        // $appointment->reschedule_reason = $request->reschedule_reason;

        /*
        |--------------------------------------------------------------------------
        | Generate New Token
        |--------------------------------------------------------------------------
        */

        $appointment->token_no = DoctorAppointment::where(
            'doctor_id',
            $doctor->id
        )
            ->whereDate(
                'appointment_date',
                $request->appointment_date
            )
            ->where('id', '!=', $appointment->id)
            ->whereNotIn('appointment_status', [
                'cancelled',
                'rejected'
            ])
            ->count() + 1;

        $appointment->save();

        /*
        |--------------------------------------------------------------------------
        | Video Consultation
        |--------------------------------------------------------------------------
        |
        | Keep the same room because this is the same appointment.
        | Only the scheduled appointment date/time has changed.
        |
        */

        if ($appointment->consultation_type == 'video') {

            $room = VideoRoom::where(
                'appointment_id',
                $appointment->id
            )->first();

            if ($room) {

                $room->status = 'waiting';

                $room->doctor_online = false;

                $room->customer_online = false;

                $room->doctor_joined_at = null;

                $room->customer_joined_at = null;

                $room->doctor_left_at = null;

                $room->customer_left_at = null;

                $room->started_at = null;

                $room->ended_at = null;

                $room->duration = 0;

                $room->ended_by = null;

                $room->end_reason = null;

                $room->save();
            }

            $appointment->meeting_status = 'waiting';

            $appointment->meeting_started_at = null;

            $appointment->meeting_ended_at = null;

            $appointment->save();
        }

        return response()->json([
            'success' => 1,
            'message' => 'Appointment rescheduled successfully.',
            'data' => [
                'appointment' => $appointment,

                'previous_schedule' => [
                    'appointment_date' => $oldDate,
                    'appointment_time' => $oldTime,
                ],

                'new_schedule' => [
                    'appointment_date' => $appointment->appointment_date,
                    'appointment_time' => $appointment->appointment_time,
                ]
            ]
        ]);
    }

        public function labTests(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $labTests = LabTest::where('status', 1);

        if ($request->filled('id')) {
            $labTests->where('id', $request->id);
        }

        if ($request->filled('search')) {
            $labTests->where('test_name', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->filled('home_collection')) {
            $labTests->where('home_collection', $request->home_collection);
        }

        if ($request->filled('fasting_required')) {
            $labTests->where('fasting_required', $request->fasting_required);
        }

        $labTests = $labTests->latest()->paginate(20);

        if ($labTests->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'No Lab Tests Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => new LabTestCollection($labTests),
            'message' => 'Lab Tests Fetched Successfully'
        ]);
    }
}
