<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerAppointmentDetailCollection;
use App\Http\Resources\DoctorAppointmentCollection;
use App\Models\AppNotification;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Doctor;
use App\Models\DoctorAppointment;
use App\Models\DoctorSchedule;
use App\Models\VideoRoom;
use App\Services\NotificationService;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

            'consultation_type' =>
                'required|in:hospital_visit,video,chat,home_visit',

            'visit_address' =>
                'required_if:consultation_type,home_visit',

            'visit_latitude' => 'nullable',

            'visit_longitude' => 'nullable',

            'coupon_code' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Future Date / Time
        |--------------------------------------------------------------------------
        */

        $appointmentDateTime = Carbon::parse(
            $request->appointment_date . ' ' . $request->appointment_time
        );

        if ($appointmentDateTime->lte(now())) {
            return response()->json([
                'success' => 0,
                'message' => 'Please select a future date and time.'
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Check Slot Already Booked
        |--------------------------------------------------------------------------
        */

        $alreadyBooked = DoctorAppointment::where(
            'doctor_id',
            $request->doctor_id
        )
            ->whereDate(
                'appointment_date',
                $request->appointment_date
            )
            ->whereTime(
                'appointment_time',
                $request->appointment_time
            )
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
        | Doctor
        |--------------------------------------------------------------------------
        */

        $doctor = Doctor::find($request->doctor_id);

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Doctor Details Not Found'
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Doctor Schedule
        |--------------------------------------------------------------------------
        */

        $schedule = DoctorSchedule::where(
            'id',
            $request->doctor_schedule_id
        )
            ->where(
                'doctor_id',
                $request->doctor_id
            )
            ->where(
                'status',
                1
            )
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

        $start = Carbon::parse(
            $schedule->available_from
        );

        $end = Carbon::parse(
            $schedule->available_to
        );

        $isValidSlot = false;

        while ($start < $end) {

            // Skip lunch break
            if (
                $start->format('H:i') >= '13:00' &&
                $start->format('H:i') < '14:00'
            ) {
                $start->addMinutes(
                    $schedule->slot_duration
                );

                continue;
            }

            if (
                $start->format('H:i') === $requestedTime
            ) {
                $isValidSlot = true;
                break;
            }

            $start->addMinutes(
                $schedule->slot_duration
            );
        }


        if (!$isValidSlot) {
            return response()->json([
                'success' => 0,
                'message' =>
                    'Please select a valid available time slot.'
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Consultation Fee
        |--------------------------------------------------------------------------
        */

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


        $fee = (float) $fee;


        /*
        |--------------------------------------------------------------------------
        | Coupon Variables
        |--------------------------------------------------------------------------
        */

        $coupon = null;

        $discount = 0;

        $totalAmount = $fee;


        /*
        |--------------------------------------------------------------------------
        | Apply Coupon
        |--------------------------------------------------------------------------
        */

        if ($request->filled('coupon_code')) {

            $coupon = Coupon::where(
                'code',
                $request->coupon_code
            )
                ->where(
                    'status',
                    1
                )
                ->first();

            /*
            |--------------------------------------------------------------------------
            | Coupon Exists
            |--------------------------------------------------------------------------
            */

            if (!$coupon) {

                return response()->json([
                    'success' => 0,
                    'message' => 'Invalid coupon code.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Coupon Start Date
            |--------------------------------------------------------------------------
            */

            if (
                $coupon->starts_at &&
                now()->lt($coupon->starts_at)
            ) {

                return response()->json([
                    'success' => 0,
                    'message' => 'This coupon is not active yet.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Coupon Expiry
            |--------------------------------------------------------------------------
            */

            if (
                $coupon->expires_at &&
                now()->gt($coupon->expires_at)
            ) {

                return response()->json([
                    'success' => 0,
                    'message' => 'This coupon has expired.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Hospital Check
            |--------------------------------------------------------------------------
            */

            if (
                $coupon->hospital_id !== null &&
                (int) $coupon->hospital_id !==
                (int) $doctor->hospital_id
            ) {

                return response()->json([
                    'success' => 0,
                    'message' =>
                        'This coupon is not valid for this hospital.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Applicable To
            |--------------------------------------------------------------------------
            */

            if (
                $coupon->applicable_to &&
                $coupon->applicable_to !== 'all' &&
                $coupon->applicable_to !== 'appointment'
            ) {

                return response()->json([
                    'success' => 0,
                    'message' =>
                        'This coupon cannot be used for appointments.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Total Usage Limit
            |--------------------------------------------------------------------------
            */

            if (
                $coupon->usage_limit !== null &&
                $coupon->used_count >=
                $coupon->usage_limit
            ) {

                return response()->json([
                    'success' => 0,
                    'message' =>
                        'This coupon usage limit has been reached.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Customer Usage
            |--------------------------------------------------------------------------
            */

            $customerUsageCount = CouponUsage::where(
                'coupon_id',
                $coupon->id
            )
                ->where(
                    'customer_id',
                    $customer->id
                )
                ->count();


            if (
                $coupon->usage_per_customer !== null &&
                $customerUsageCount >=
                $coupon->usage_per_customer
            ) {

                return response()->json([
                    'success' => 0,
                    'message' =>
                        'You have already used this coupon.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | New Customer Check
            |--------------------------------------------------------------------------
            */

            if ($coupon->new_customer_only) {

                $hasPreviousAppointment =
                    DoctorAppointment::where(
                        'customer_id',
                        $customer->id
                    )
                        ->whereNotIn(
                            'appointment_status',
                            [
                                'cancelled',
                                'rejected'
                            ]
                        )
                        ->exists();


                if ($hasPreviousAppointment) {

                    return response()->json([
                        'success' => 0,
                        'message' =>
                            'This coupon is available only for new customers.'
                    ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | First Appointment Check
            |--------------------------------------------------------------------------
            */

            if ($coupon->first_appointment_only) {

                $hasPreviousAppointment =
                    DoctorAppointment::where(
                        'customer_id',
                        $customer->id
                    )
                        ->whereNotIn(
                            'appointment_status',
                            [
                                'cancelled',
                                'rejected'
                            ]
                        )
                        ->exists();


                if ($hasPreviousAppointment) {

                    return response()->json([
                        'success' => 0,
                        'message' =>
                            'This coupon is available only for your first appointment.'
                    ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Minimum Amount
            |--------------------------------------------------------------------------
            */

            if (
                $coupon->min_order_amount !== null &&
                $fee < (float) $coupon->min_order_amount
            ) {

                return response()->json([
                    'success' => 0,
                    'message' =>
                        'Minimum appointment amount is ₹' .
                        number_format(
                            $coupon->min_order_amount,
                            2
                        )
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Calculate Discount
            |--------------------------------------------------------------------------
            */

            if (
                $coupon->free_appointment ||
                $coupon->discount_type === 'free'
            ) {

                /*
                 * Free Appointment
                 */

                $discount = $fee;
            } elseif (
                $coupon->discount_type === 'percentage'
            ) {

                $discount =
                    ($fee *
                        (float) $coupon->discount_value)
                    / 100;


                /*
                |--------------------------------------------------------------------------
                | Max Discount
                |--------------------------------------------------------------------------
                */

                if (
                    $coupon->max_discount !== null &&
                    $discount >
                    (float) $coupon->max_discount
                ) {

                    $discount =
                        (float) $coupon->max_discount;
                }
            } elseif (
                $coupon->discount_type === 'fixed'
            ) {

                $discount =
                    (float) $coupon->discount_value;
            }


            /*
            |--------------------------------------------------------------------------
            | Safety
            |--------------------------------------------------------------------------
            */

            if ($discount > $fee) {
                $discount = $fee;
            }

            if ($discount < 0) {
                $discount = 0;
            }


            /*
            |--------------------------------------------------------------------------
            | Final Amount
            |--------------------------------------------------------------------------
            */

            $totalAmount = $fee - $discount;

            if ($totalAmount < 0) {
                $totalAmount = 0;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Create Appointment
        |--------------------------------------------------------------------------
        */

        $appointment = new DoctorAppointment();

        $appointment->appointment_no =
            'APT' .
            now()->format('YmdHis') .
            rand(100, 999);

        $appointment->doctor_id =
            $doctor->id;

        $appointment->hospital_id =
            $doctor->hospital_id;

        $appointment->customer_id =
            $customer->id;

        $appointment->family_member_id =
            $request->family_member_id;

        $appointment->doctor_schedule_id =
            $request->doctor_schedule_id;

        $appointment->appointment_date =
            $request->appointment_date;

        $appointment->appointment_time =
            $request->appointment_time;

        $appointment->consultation_type =
            $request->consultation_type;


        /*
        |--------------------------------------------------------------------------
        | Home Visit
        |--------------------------------------------------------------------------
        */

        if (
            $request->consultation_type ===
            'home_visit'
        ) {

            $appointment->visit_address =
                $request->visit_address;

            $appointment->visit_latitude =
                $request->visit_latitude;

            $appointment->visit_longitude =
                $request->visit_longitude;

            $appointment->visit_status =
                'scheduled';
        }


        /*
        |--------------------------------------------------------------------------
        | Video
        |--------------------------------------------------------------------------
        */

        if (
            $request->consultation_type ===
            'video'
        ) {

            $appointment->meeting_status =
                'pending';
        }


        /*
        |--------------------------------------------------------------------------
        | Chat
        |--------------------------------------------------------------------------
        */

        if (
            $request->consultation_type ===
            'chat'
        ) {

            $appointment->chat_started_at =
                null;

            $appointment->chat_ended_at =
                null;
        }


        /*
        |--------------------------------------------------------------------------
        | Amounts
        |--------------------------------------------------------------------------
        */

        $appointment->consultation_fee =
            $fee;

        $appointment->discount =
            $discount;

        $appointment->tax =
            0;

        $appointment->total_amount =
            $totalAmount;


        /*
        |--------------------------------------------------------------------------
        | Payment Status
        |--------------------------------------------------------------------------
        */

        if ($totalAmount <= 0) {

            $appointment->payment_status =
                'paid';

        } else {

            $appointment->payment_status =
                'pending';
        }


        /*
        |--------------------------------------------------------------------------
        | Appointment Status
        |--------------------------------------------------------------------------
        */

        $appointment->appointment_status =
            'pending';


        /*
        |--------------------------------------------------------------------------
        | Token
        |--------------------------------------------------------------------------
        */

        $appointment->token_no =
            DoctorAppointment::where(
                'doctor_id',
                $doctor->id
            )
                ->whereDate(
                    'appointment_date',
                    $request->appointment_date
                )
                ->count() + 1;


        $appointment->save();


        /*
        |--------------------------------------------------------------------------
        | Coupon Usage
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | We create coupon usage only after appointment
        | has been successfully created.
        |
        */

        if ($coupon) {

            CouponUsage::create([

                'coupon_id' =>
                    $coupon->id,

                'customer_id' =>
                    $customer->id,

                'hospital_id' =>
                    $doctor->hospital_id,

                'appointment_id' =>
                    $appointment->id,

                'medicine_order_id' =>
                    null,

                'coupon_code' =>
                    $coupon->code,

                'discount_amount' =>
                    $discount,

                'used_at' =>
                    now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | Update Coupon Used Count
            |--------------------------------------------------------------------------
            */

            $coupon->increment(
                'used_count'
            );
        }

        try {

            $data = $this->sendWhatsAppMessage(
                $customer->mobile,
                'customer_app_book',
                [
                    'field_1' => $customer->name,
                    'field_2' => $doctor->doctor_name,
                    'field_3' => Carbon::parse($appointment->appointment_date)->format('d M Y'),
                    'field_4' => Carbon::parse($appointment->appointment_time)->format('h:i A'),
                    'field_5' => $doctor->hospital->hospital_name ?? ' '
                ]
            );

            // $whatsappService = new WhatsAppService();

            // $result = $whatsappService->sendTemplateMessage($data);

            // Log::info($result);

        } catch (\Throwable $e) {

            Log::error('WhatsApp send failed: ' . $e->getMessage());
        }

        NotificationService::send(
            'customer',
            $customer->id,
            'appointment_booked',
            'Appointment Booked',
            'Your appointment with Dr. ' . $doctor->doctor_name . ' has been booked successfully.',
            'doctor_appointment',
            $appointment->id,
            'appointment_details',
            [
                'appointment_id' => $appointment->id,
                'appointment_no' => $appointment->appointment_no,
                'doctor_id' => $doctor->id,
                'doctor_name' => $doctor->doctor_name,
                'hospital_id' => $doctor->hospital_id,
                'appointment_date' => $appointment->appointment_date,
                'appointment_time' => $appointment->appointment_time,
                'consultation_type' => $appointment->consultation_type,
                'total_amount' => $appointment->total_amount,
            ]
        );
        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => 1,

            'message' =>
                'Doctor appointment booked successfully.',

            'data' => [

                'appointment' =>
                    $appointment,

                'coupon' => $coupon ? [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'title' => $coupon->title,
                    'discount' => $discount,
                ] : null,

                'consultation_fee' =>
                    $fee,

                'discount' =>
                    $discount,

                'total_amount' =>
                    $totalAmount,

                'payment_required' =>
                    $totalAmount > 0,
            ]
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

    public function appointmentDetails($id)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $appointments = DoctorAppointment::with([

            // Doctor
            'doctor.hospitalSpecialization.specialization',

            // Hospital
            'hospital',

            // Family Member
            'familyMember',

            // Schedule
            'schedule',

            // Patient Vitals
            'patientVitals',

            // Prescription
            'prescription.medicines',

            // Recommended Lab Tests
            'prescription.recommendedLabTests.labTest',

            // Medical Reports
            'medicalReports',

            // Video Room
            'videoRoom',

        ])
            ->where('customer_id', $customer->id)
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
            'data' => new CustomerAppointmentDetailCollection(
                $appointments
            ),
            'message' => 'Appointment Details Fetched Successfully'
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

        NotificationService::send(
            'customer',
            $customer->id,
            'appointment_cancelled',
            'Appointment Cancelled',
            'Your appointment with Dr. ' .
            ($appointment->doctor->doctor_name ?? 'Doctor') .
            ' has been cancelled successfully.',
            'doctor_appointment',
            $appointment->id,
            'appointment_details',
            [
                'appointment_id' => $appointment->id,
                'appointment_no' => $appointment->appointment_no,

                'doctor_id' => $appointment->doctor_id,
                'doctor_name' => $appointment->doctor->doctor_name ?? null,

                'hospital_id' => $appointment->hospital_id,

                'appointment_date' => $appointment->appointment_date,
                'appointment_time' => $appointment->appointment_time,

                'cancel_reason' => $appointment->cancel_reason,

                'cancelled_at' => $appointment->cancelled_at,
            ]
        );

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
        $oldDate = $appointment->appointment_date;
        $oldTime = $appointment->appointment_time;
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

        NotificationService::send(
            'customer',
            $customer->id,
            'appointment_rescheduled',
            'Appointment Rescheduled',
            'Your appointment with Dr. ' .
            ($appointment->doctor->doctor_name ?? 'Doctor') .
            ' has been rescheduled to ' .
            Carbon::parse($appointment->appointment_date)->format('d M Y') .
            ' at ' .
            Carbon::parse($appointment->appointment_time)->format('h:i A') .
            '.',
            'doctor_appointment',
            $appointment->id,
            'appointment_details',
            [
                'appointment_id' => $appointment->id,

                'appointment_no' => $appointment->appointment_no,

                'doctor_id' => $appointment->doctor_id,

                'doctor_name' =>
                    $appointment->doctor->doctor_name ?? null,

                'hospital_id' =>
                    $appointment->hospital_id,

                'old_appointment_date' => $oldDate,

                'old_appointment_time' => $oldTime,

                'new_appointment_date' =>
                    $appointment->appointment_date,

                'new_appointment_time' =>
                    $appointment->appointment_time,

                'appointment_status' =>
                    $appointment->appointment_status,

                'rescheduled_at' => now(),
            ]
        );

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

    private function sendWhatsAppMessage($cust_mobile, $templateName, array $fields = [])
    {
        return [
            "from_phone_number_id" => "1219830927885128",
            "phone_number" => '91' . $cust_mobile,
            "template_name" => $templateName,
            "template_language" => "en",
            "field_1" => $fields['field_1'] ?? '',
            "field_2" => $fields['field_2'] ?? '',
            "field_3" => $fields['field_3'] ?? '',
            "field_4" => $fields['field_4'] ?? '',
            "field_5" => $fields['field_5'] ?? '',

            "button_0" => $fields['field_1'] ?? '',
            "copy_code" => $fields['field_1'] ?? '',
        ];
    }
}
