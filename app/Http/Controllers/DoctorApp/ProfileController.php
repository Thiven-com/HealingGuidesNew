<?php

namespace App\Http\Controllers\DoctorApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorCollection;
use App\Http\Resources\SpecializationCollection;
use App\Models\DoctorAppointment;
use App\Models\DoctorSchedule;
use App\Models\Specialization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    //
    public function profile()
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => new DoctorCollection(collect([$doctor])),
            'message' => 'Doctor fetched successfully'
        ]);
    }

    public function dashboard()
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ]);
        }

        $today = now()->toDateString();

        $data = [

            'today_appointments' => DoctorAppointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', $today)
                ->count(),

            'pending_appointments' => DoctorAppointment::where('doctor_id', $doctor->id)
                ->where('appointment_status', 'pending')
                ->count(),

            'confirmed_appointments' => DoctorAppointment::where('doctor_id', $doctor->id)
                ->where('appointment_status', 'confirmed')
                ->count(),

            'completed_appointments' => DoctorAppointment::where('doctor_id', $doctor->id)
                ->where('appointment_status', 'completed')
                ->count(),

            'cancelled_appointments' => DoctorAppointment::where('doctor_id', $doctor->id)
                ->where('appointment_status', 'cancelled')
                ->count(),

            'today_video_consultations' => DoctorAppointment::where('doctor_id', $doctor->id)
                ->where('consultation_type', 'video')
                ->whereDate('appointment_date', $today)
                ->count(),

            'today_chat_consultations' => DoctorAppointment::where('doctor_id', $doctor->id)
                ->where('consultation_type', 'chat')
                ->whereDate('appointment_date', $today)
                ->count(),

            'today_home_visits' => DoctorAppointment::where('doctor_id', $doctor->id)
                ->where('consultation_type', 'home_visit')
                ->whereDate('appointment_date', $today)
                ->count(),

            'today_hospital_visits' => DoctorAppointment::where('doctor_id', $doctor->id)
                ->where('consultation_type', 'hospital_visit')
                ->whereDate('appointment_date', $today)
                ->count(),

        ];

        return response()->json([
            'success' => 1,
            'message' => 'Dashboard fetched successfully.',
            'data' => $data
        ]);
    }

    public function todaySummary()
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ]);
        }

        $today = today();

        $summary = [

            'total' => DoctorAppointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', $today)
                ->count(),

            'pending' => DoctorAppointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', $today)
                ->where('appointment_status', 'pending')
                ->count(),

            'confirmed' => DoctorAppointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', $today)
                ->where('appointment_status', 'confirmed')
                ->count(),

            'completed' => DoctorAppointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', $today)
                ->where('appointment_status', 'completed')
                ->count(),

            'cancelled' => DoctorAppointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', $today)
                ->where('appointment_status', 'cancelled')
                ->count(),

        ];

        return response()->json([
            'success' => 1,
            'data' => $summary
        ]);
    }
    public function statistics()
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ]);
        }

        $statistics = [

            'total_patients' => DoctorAppointment::where('doctor_id', $doctor->id)
                ->distinct('customer_id')
                ->count('customer_id'),

            'total_appointments' => DoctorAppointment::where('doctor_id', $doctor->id)
                ->count(),

            'completed_consultations' => DoctorAppointment::where('doctor_id', $doctor->id)
                ->where('appointment_status', 'completed')
                ->count(),

            'video_consultations' => DoctorAppointment::where('doctor_id', $doctor->id)
                ->where('consultation_type', 'video')
                ->count(),

            'hospital_visits' => DoctorAppointment::where('doctor_id', $doctor->id)
                ->where('consultation_type', 'hospital_visit')
                ->count(),

            'chat_consultations' => DoctorAppointment::where('doctor_id', $doctor->id)
                ->where('consultation_type', 'chat')
                ->count(),

            'home_visits' => DoctorAppointment::where('doctor_id', $doctor->id)
                ->where('consultation_type', 'home_visit')
                ->count(),

            'total_earnings' => DoctorAppointment::where('doctor_id', $doctor->id)
                ->where('payment_status', 'paid')
                ->sum('total_amount'),

            'this_month_earnings' => DoctorAppointment::where('doctor_id', $doctor->id)
                ->where('payment_status', 'paid')
                ->whereMonth('appointment_date', now()->month)
                ->whereYear('appointment_date', now()->year)
                ->sum('total_amount'),

        ];

        return response()->json([
            'success' => 1,
            'message' => 'Statistics fetched successfully.',
            'data' => $statistics
        ]);
    }

    public function specializations(Request $request)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $specializations = Specialization::where('status', 1);
        if ($request->filled('id')) {
            $specializations->where('id', $request->id);
        }
        $specializations = $specializations->latest()->paginate(20);

        if ($specializations->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'No Specializations Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => new SpecializationCollection($specializations),
            'message' => 'Specializations Fetched Successfully'
        ]);
    }
    public function availableSlots(Request $request)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }
        $validator = Validator::make($request->all(), [
            // 'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        $day = Carbon::parse($request->appointment_date)->format('l');

        $schedule = DoctorSchedule::where('doctor_id', $doctor->id)
            ->where('day_of_week', $day)
            ->where('status', 1)
            ->first();

        if (!$schedule) {
            return response()->json([
                'success' => 0,
                'message' => 'Doctor is not available on this day.',
                'data' => []
            ]);
        }

        // Get all appointments except cancelled
        $appointments = DoctorAppointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->where('appointment_status', '!=', 'cancelled')
            ->get(['appointment_time', 'appointment_status']);

        // Prepare slot status lookup
        $slotStatuses = [];

        foreach ($appointments as $appointment) {
            $time = Carbon::parse($appointment->appointment_time)->format('H:i');

            switch ($appointment->appointment_status) {
                case 'pending':
                    $slotStatuses[$time] = 'Blocked';
                    break;

                case 'confirmed':
                    $slotStatuses[$time] = 'Booked';
                    break;

                default:
                    $slotStatuses[$time] = 'Booked';
                    break;
            }
        }

        $start = Carbon::parse($schedule->available_from);
        $end = Carbon::parse($schedule->available_to);

        $breakStart = Carbon::createFromTimeString('13:00:00');
        $breakEnd = Carbon::createFromTimeString('14:00:00');

        $slots = [];

        while ($start < $end) {

            // Skip lunch break (1 PM - 2 PM)
            if ($start >= $breakStart && $start < $breakEnd) {
                $start->addMinutes($schedule->slot_duration);
                continue;
            }

            $time = $start->format('H:i');

            $slots[] = [
                'slot' => $time,
                'slot_time' => $start->format('h:i A'),
                'status' => $slotStatuses[$time] ?? 'Available',
            ];

            $start->addMinutes($schedule->slot_duration);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Available Slots',
            'data' => [
                'doctor_id' => $request->doctor_id,
                'doctor_schedule_id' => $schedule->id,
                'appointment_date' => $request->appointment_date,
                'consultation_type' => $schedule->consultation_type,
                'slot_duration' => $schedule->slot_duration,
                'slots' => $slots,
            ]
        ]);
    }

    public function updateFees(Request $request)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [

            'consultation_fee' => 'nullable|numeric|min:0',

            'video_consultation_fee' => 'nullable|numeric|min:0',

            'chat_consultation_fee' => 'nullable|numeric|min:0',

            'home_visit_fee' => 'nullable|numeric|min:0',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check At Least One Fee
        |--------------------------------------------------------------------------
        */

        if (
            !$request->has('consultation_fee') &&
            !$request->has('video_consultation_fee') &&
            !$request->has('chat_consultation_fee') &&
            !$request->has('home_visit_fee')
        ) {
            return response()->json([
                'success' => 0,
                'message' => 'Please provide at least one consultation fee.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Update Only Provided Fees
        |--------------------------------------------------------------------------
        */

        if ($request->has('consultation_fee')) {
            $doctor->consultation_fee = $request->consultation_fee;
        }

        if ($request->has('video_consultation_fee')) {
            $doctor->video_consultation_fee = $request->video_consultation_fee;
        }

        if ($request->has('chat_consultation_fee')) {
            $doctor->chat_consultation_fee = $request->chat_consultation_fee;
        }

        if ($request->has('home_visit_fee')) {
            $doctor->home_visit_fee = $request->home_visit_fee;
        }

        $doctor->save();

        return response()->json([
            'success' => 1,
            'message' => 'Consultation fees updated successfully.',
            'data' => [
                'consultation_fee' => $doctor->consultation_fee,
                'video_consultation_fee' => $doctor->video_consultation_fee,
                'chat_consultation_fee' => $doctor->chat_consultation_fee,
                'home_visit_fee' => $doctor->home_visit_fee,
            ]
        ]);
    }
}
