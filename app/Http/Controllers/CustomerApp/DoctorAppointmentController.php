<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorAppointmentCollection;
use App\Models\Doctor;
use App\Models\DoctorAppointment;
use App\Models\DoctorSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

            'consultation_type' => 'required|string',

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

        $appointment->consultation_fee = $doctor->consultation_fee;

        $appointment->discount = 0;

        $appointment->tax = 0;

        $appointment->total_amount = $doctor->consultation_fee;

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
}
