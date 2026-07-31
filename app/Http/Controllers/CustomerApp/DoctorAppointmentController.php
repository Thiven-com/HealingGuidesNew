<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorAppointmentCollection;
use App\Models\Doctor;
use App\Models\DoctorAppointment;
use App\Models\DoctorSchedule;
use App\Models\VideoRoom;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DoctorAppointmentController extends Controller
{
    //
    public function bookDoctorAppointment(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [

            'doctor_id' => 'required|exists:doctors,id',

            'doctor_schedule_id' => 'required|exists:doctor_schedules,id',

            'family_member_id' => 'required|exists:family_members,id',

            'appointment_date' => 'required|date',

            'appointment_time' => 'required',

            'consultation_type' => 'required|in:hospital_visit,video,chat,home_visit',

            'visit_address' => 'required_if:consultation_type,home_visit',

            'visit_latitude' => 'nullable',

            'visit_longitude' => 'nullable',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }
        $appointmentDateTime = Carbon::parse(
            $request->appointment_date . ' ' . $request->appointment_time
        );

        if ($appointmentDateTime->lte(now())) {
            return response()->json([
                'success' => 0,
                'message' => 'Please select a future date and time.'
            ]);
        }

        // Check slot already booked

        $alreadyBooked = DoctorAppointment::where('doctor_id', $request->doctor_id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->whereTime('appointment_time', $request->appointment_time)
            ->whereNotIn('appointment_status', ['cancelled'])
            ->exists();

        if ($alreadyBooked) {
            return response()->json([
                'success' => 0,
                'message' => 'Selected slot already booked.'
            ]);
        }

        $doctor = Doctor::find($request->doctor_id);
        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Doctor Details Not Found'
            ]);
        }
        $schedule = DoctorSchedule::where('id', $request->doctor_schedule_id)
            ->where('doctor_id', $request->doctor_id)
            ->where('status', 1)
            ->first();

        if (!$schedule) {
            return response()->json([
                'success' => 0,
                'message' => 'Doctor schedule not found.'
            ]);
        }

        $requestedTime = Carbon::parse($request->appointment_time)->format('H:i');

        $start = Carbon::parse($schedule->available_from);
        $end = Carbon::parse($schedule->available_to);

        $isValidSlot = false;

        while ($start < $end) {

            // Skip lunch break (optional)
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
        switch ($request->consultation_type) {

            case 'video':
                $fee = $doctor->video_consultation_fee;
                break;

            case 'chat':
                $fee = $doctor->chat_consultation_fee;
                break;

            case 'home_visit':
                $fee = $doctor->home_visit_fee;
                break;

            default:
                $fee = $doctor->consultation_fee;
                break;
        }

        $appointment = new DoctorAppointment();

        $appointment->appointment_no = 'APT' . now()->format('YmdHis') . rand(100, 999);

        $appointment->doctor_id = $doctor->id;

        $appointment->hospital_id = $doctor->hospital_id;

        $appointment->customer_id = $customer->id;

        $appointment->family_member_id = $request->family_member_id;

        $appointment->doctor_schedule_id = $request->doctor_schedule_id;

        $appointment->appointment_date = $request->appointment_date;

        $appointment->appointment_time = $request->appointment_time;

        $appointment->consultation_type = $request->consultation_type;
        if ($request->consultation_type == 'home_visit') {

            $appointment->visit_address = $request->visit_address;
            $appointment->visit_latitude = $request->visit_latitude;
            $appointment->visit_longitude = $request->visit_longitude;
            $appointment->visit_status = 'scheduled';
        }
        if ($request->consultation_type == 'video') {
            $appointment->meeting_status = 'pending';
        }
        if ($request->consultation_type == 'chat') {
            $appointment->chat_started_at = null;
            $appointment->chat_ended_at = null;
        }
        $appointment->consultation_fee = $fee;
        $appointment->discount = 0;
        $appointment->tax = 0;
        $appointment->total_amount = $fee;

        $appointment->payment_status = 'pending';

        $appointment->appointment_status = 'pending';

        $appointment->token_no = DoctorAppointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->count() + 1;

        $appointment->save();

        return response()->json([
            'success' => 1,
            'message' => 'Doctor appointment booked successfully.',
            'data' => $appointment
        ]);
    }

    public function myAppointments(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
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
            ->where('customer_id', $user->id);

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

    public function cancelAppointment(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:doctor_appointments,id',
            'cancel_reason' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $appointment = DoctorAppointment::where('id', $request->appointment_id)
            ->where('customer_id', $customer->id)
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

        if (Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time)->isPast()) {
            return response()->json([
                'success' => 0,
                'message' => 'Past appointments cannot be cancelled.'
            ]);
        }

        $appointment->appointment_status = 'cancelled';
        $appointment->cancel_reason = $request->cancel_reason;
        $appointment->cancelled_at = now();
        $appointment->save();

        return response()->json([
            'success' => 1,
            'message' => 'Appointment cancelled successfully.'
        ]);
    }

    public function rescheduleAppointment(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
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

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $appointment = DoctorAppointment::where('id', $request->appointment_id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$appointment) {
            return response()->json([
                'success' => 0,
                'message' => 'Appointment not found.'
            ]);
        }
        if (
            $appointment->doctor_schedule_id == $request->doctor_schedule_id &&
            $appointment->appointment_date == $request->appointment_date &&
            Carbon::parse($appointment->appointment_time)->format('H:i') ==
            Carbon::parse($request->appointment_time)->format('H:i')
        ) {
            return response()->json([
                'success' => 0,
                'message' => 'This appointment is already scheduled for the selected date and time.'
            ]);
        }
        $oldAppointmentDateTime = Carbon::parse(
            $appointment->appointment_date . ' ' . $appointment->appointment_time
        );

        if ($oldAppointmentDateTime->isPast()) {
            return response()->json([
                'success' => 0,
                'message' => 'Past appointments cannot be rescheduled.'
            ]);
        }

        if ($appointment->appointment_status == 'cancelled') {
            return response()->json([
                'success' => 0,
                'message' => 'Cancelled appointment cannot be rescheduled.'
            ]);
        }

        $appointmentDateTime = Carbon::parse(
            $request->appointment_date . ' ' . $request->appointment_time
        );

        if ($appointmentDateTime->lte(now())) {
            return response()->json([
                'success' => 0,
                'message' => 'Please select a future date and time.'
            ]);
        }

        $schedule = DoctorSchedule::where('id', $request->doctor_schedule_id)
            ->where('doctor_id', $appointment->doctor_id)
            ->where('status', 1)
            ->first();

        if (!$schedule) {
            return response()->json([
                'success' => 0,
                'message' => 'Doctor schedule not found.'
            ]);
        }

        $requestedTime = Carbon::parse($request->appointment_time)->format('H:i');

        $start = Carbon::parse($schedule->available_from);
        $end = Carbon::parse($schedule->available_to);

        $isValidSlot = false;

        while ($start < $end) {

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
                'message' => 'Invalid appointment slot.'
            ]);
        }

        $alreadyBooked = DoctorAppointment::where('doctor_id', $appointment->doctor_id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->whereTime('appointment_time', $request->appointment_time)
            ->where('id', '!=', $appointment->id)
            ->whereNotIn('appointment_status', ['cancelled'])
            ->exists();

        if ($alreadyBooked) {
            return response()->json([
                'success' => 0,
                'message' => 'Selected slot already booked.'
            ]);
        }

        $appointment->doctor_schedule_id = $request->doctor_schedule_id;
        $appointment->appointment_date = $request->appointment_date;
        $appointment->appointment_time = $request->appointment_time;
        $appointment->appointment_status = 'pending';

        $appointment->token_no = DoctorAppointment::where('doctor_id', $appointment->doctor_id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->where('id', '!=', $appointment->id)
            ->count() + 1;

        $appointment->save();

        return response()->json([
            'success' => 1,
            'message' => 'Appointment rescheduled successfully.',
            'data' => $appointment
        ]);
    }

    public function payAppointment(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [

            'appointment_id' => 'required|exists:doctor_appointments,id',

            'payment_method' => 'required|in:cash,razorpay,stripe',

            'transaction_id' => 'nullable|string',

            'payment_id' => 'nullable|string',

        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $appointment = DoctorAppointment::where('id', $request->appointment_id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$appointment) {

            return response()->json([
                'success' => 0,
                'message' => 'Appointment not found.'
            ]);
        }

        if ($appointment->payment_status == 'paid') {

            return response()->json([
                'success' => 0,
                'message' => 'Appointment payment already completed.'
            ]);
        }

        if ($appointment->appointment_status == 'cancelled') {

            return response()->json([
                'success' => 0,
                'message' => 'Cancelled appointment cannot be paid.'
            ]);
        }

        if ($appointment->appointment_status == 'completed') {

            return response()->json([
                'success' => 0,
                'message' => 'Completed appointment cannot be paid.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Verify Payment Gateway
        |--------------------------------------------------------------------------
        |
        | Razorpay / Stripe Verification
        | (Implement gateway verification here)
        |
        */

        $paymentVerified = true;

        if (!$paymentVerified) {

            return response()->json([
                'success' => 0,
                'message' => 'Payment verification failed.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Update Appointment
        |--------------------------------------------------------------------------
        */

        $appointment->payment_status = 'paid';

        $appointment->appointment_status = 'confirmed';

        /*
        |--------------------------------------------------------------------------
        | Consultation Type
        |--------------------------------------------------------------------------
        */

        switch ($appointment->consultation_type) {

            case 'hospital_visit':

                // Nothing required

                break;

            case 'video':

                $roomId = (string) Str::uuid();

                $room = VideoRoom::create([
                    'appointment_id' => $appointment->id,
                    'room_id' => $roomId,
                    'doctor_id' => $appointment->doctor_id,
                    'customer_id' => $appointment->customer_id,
                    'status' => 'waiting'
                ]);

                $appointment->meeting_provider = 'self';

                $appointment->meeting_id = $roomId;

                $appointment->meeting_link = url('/video-call/' . $roomId);

                $appointment->meeting_status = 'waiting';

                break;

            case 'chat':

                // Chat room creation will be implemented later

                break;

            case 'home_visit':

                $appointment->visit_status = 'scheduled';

                break;
        }

        $appointment->save();

        return response()->json([

            'success' => 1,

            'message' => 'Payment completed successfully.',

            'data' => [

                'appointment_id' => $appointment->id,

                'appointment_no' => $appointment->appointment_no,

                'consultation_type' => $appointment->consultation_type,

                'payment_status' => $appointment->payment_status,

                'appointment_status' => $appointment->appointment_status,

                'meeting_provider' => $appointment->meeting_provider,

                'meeting_id' => $appointment->meeting_id,

                'meeting_link' => $appointment->meeting_link,

                'visit_status' => $appointment->visit_status,

            ]

        ]);
    }
    public function joinVideoRoom(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:doctor_appointments,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $appointment = DoctorAppointment::where('id', $request->appointment_id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$appointment) {

            return response()->json([
                'success' => 0,
                'message' => 'Appointment not found.'
            ]);
        }

        if ($appointment->consultation_type != 'video') {

            return response()->json([
                'success' => 0,
                'message' => 'This is not a video consultation.'
            ]);
        }

        if ($appointment->payment_status != 'paid') {

            return response()->json([
                'success' => 0,
                'message' => 'Please complete payment.'
            ]);
        }

        if ($appointment->appointment_status != 'confirmed') {

            return response()->json([
                'success' => 0,
                'message' => 'Appointment is not confirmed.'
            ]);
        }

        $room = VideoRoom::where('appointment_id', $appointment->id)->first();

        if (!$room) {

            return response()->json([
                'success' => 0,
                'message' => 'Video room not found.'
            ]);
        }

        if ($room->status == 'ended') {

            return response()->json([
                'success' => 0,
                'message' => 'Video consultation already completed.'
            ]);
        }

        if (!$room->customer_joined_at) {

            $room->customer_joined_at = now();

            $room->customer_online = true;

            $room->customer_ip = $request->ip();

            if ($room->doctor_joined_at) {

                $room->status = 'live';

                $room->started_at = now();
            } else {

                $room->status = 'customer_joined';
            }

            $room->save();
        }

        return response()->json([

            'success' => 1,

            'message' => 'Joined successfully.',

            'data' => [

                'room_id' => $room->room_id,

                'status' => $room->status,

                'meeting_link' => $appointment->meeting_link,

                'doctor_joined' => $room->doctor_online,

                'customer_joined' => $room->customer_online

            ]
        ]);
    }
}
