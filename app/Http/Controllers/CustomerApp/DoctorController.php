<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorCollection;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\DoctorSchedule;
use App\Models\DoctorAppointment;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{
    //
    public function doctors(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $query = Doctor::with([
            'hospital',
            'hospitalSpecialization.specialization'
        ])->where('status', 1);

        if ($request->filled('search')) {
            $query->where('doctor_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('hospital_id')) {
            $query->where('hospital_id', $request->hospital_id);
        }
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }
        if ($request->filled('accept_free_booking')) {
            $query->where('accept_free_booking', $request->accept_free_booking);
        }

        if ($request->filled('specialization_id')) {
            $query->whereHas('hospitalSpecialization', function ($q) use ($request) {
                $q->where('specialization_id', $request->specialization_id);
            });
        }

        if ($request->filled('experience')) {
            $query->where('experience', '>=', $request->experience);
        }

        /*
  |--------------------------------------------------------------------------
  | Consultation Type Filter
  |--------------------------------------------------------------------------
  |
  | type=consultation
  | type=video
  | type=chat
  | type=home_visit
  |
  */

        if ($request->filled('type')) {

            switch ($request->type) {

                case 'consultation':

                    $query->where('consultation_enabled', 1);

                    break;

                case 'video':

                    $query->where('video_consultation_enabled', 1);

                    break;

                case 'chat':

                    $query->where('chat_consultation_enabled', 1);

                    break;

                case 'home_visit':

                    $query->where('home_visit_enabled', 1);

                    break;

                // default:

                //     return response()->json([
                //         'success' => 0,
                //         'message' => 'Invalid consultation type. Use consultation, video, chat or home_visit.'
                //     ], 422);
                default:
                    $query->where('consultation_enabled', 1);
                    break;
            }
        }

        if ($request->filled('percentage')) {
            $percentage = (float) $request->percentage;

            if ($percentage < 0 || $percentage > 100) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Percentage must be between 0 and 100.'
                ], 422);
            }

            switch ($request->type) {

                case 'consultation':
                    $query->whereRaw("
                actual_fee > 0
                AND consultation_fee < actual_fee
                AND ((actual_fee - consultation_fee) / actual_fee) * 100 >= ?
            ", [$percentage]);
                    break;

                case 'video':
                    $query->whereRaw("
                actual_video_consultation_fee > 0
                AND video_consultation_fee < actual_video_consultation_fee
                AND ((actual_video_consultation_fee - video_consultation_fee) / actual_video_consultation_fee) * 100 >= ?
            ", [$percentage]);
                    break;

                case 'chat':
                    $query->whereRaw("
                actual_chat_consultation_fee > 0
                AND chat_consultation_fee < actual_chat_consultation_fee
                AND ((actual_chat_consultation_fee - chat_consultation_fee) / actual_chat_consultation_fee) * 100 >= ?
            ", [$percentage]);
                    break;

                case 'home_visit':
                    $query->whereRaw("
                actual_home_visit_fee > 0
                AND home_visit_fee < actual_home_visit_fee
                AND ((actual_home_visit_fee - home_visit_fee) / actual_home_visit_fee) * 100 >= ?
            ", [$percentage]);
                    break;

                // default:

                //     return response()->json([
                //         'success' => 0,
                //         'message' => 'Invalid consultation type. Use consultation, video, chat or home_visit.'
                //     ], 422);

                default:

                    // Default percentage filter = Consultation
                    $query->whereRaw("
                actual_fee > 0
                AND consultation_fee < actual_fee
                AND ((actual_fee - consultation_fee) / actual_fee) * 100 >= ?
            ", [$percentage]);

                    break;
            }
        }


        $doctors = $query->latest()->paginate(20);
        if ($doctors->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'No Doctors Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Doctors Fetched Successfully',
            'data' => new DoctorCollection($doctors)
        ]);
    }

    public function availableSlots(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        $day = Carbon::parse($request->appointment_date)->format('l');

        $schedule = DoctorSchedule::where('doctor_id', $request->doctor_id)
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
        $appointments = DoctorAppointment::where('doctor_id', $request->doctor_id)
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



    // public function availableSlots(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'doctor_id' => 'required|exists:doctors,id',
    //         'appointment_date' => 'required|date',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => 0,
    //             'message' => $validator->errors()->first(),
    //         ]);
    //     }

    //     $day = Carbon::parse($request->appointment_date)->format('l');

    //     $schedule = DoctorSchedule::where('doctor_id', $request->doctor_id)
    //         ->where('day_of_week', $day)
    //         ->where('status', 1)
    //         ->first();

    //     if (!$schedule) {
    //         return response()->json([
    //             'success' => 0,
    //             'message' => 'Doctor is not available on this day.',
    //             'data' => []
    //         ]);
    //     }

    //     $bookedSlots = DoctorAppointment::where('doctor_id', $request->doctor_id)
    //         ->whereDate('appointment_date', $request->appointment_date)
    //         ->pluck('appointment_time')
    //         ->map(function ($time) {
    //             return Carbon::parse($time)->format('H:i');
    //         })
    //         ->toArray();
    //     // dd($bookedSlots);
    //     $start = Carbon::parse($schedule->available_from);
    //     $end = Carbon::parse($schedule->available_to);
    //     $breakStart = Carbon::createFromTimeString('13:00:00');
    //     $breakEnd = Carbon::createFromTimeString('14:00:00');
    //     $slots = [];

    //     while ($start < $end) {

    //         // Skip break time (1 PM to 2 PM)
    //         if ($start >= $breakStart && $start < $breakEnd) {
    //             $start->addMinutes($schedule->slot_duration);
    //             continue;
    //         }

    //         $time = $start->format('H:i');
    //         $slot_time = $start->format('H:i A');

    //         $slots[] = [
    //             'slot' => $time,
    //             'slot_time' => $slot_time,
    //             'status' => in_array($time, $bookedSlots) ? 'Booked' : 'Available',
    //         ];

    //         $start->addMinutes($schedule->slot_duration);
    //     }

    //     return response()->json([
    //         'success' => 1,
    //         'message' => 'Available Slots',
    //         'data' => [
    //             'doctor_id' => $request->doctor_id,
    //             'doctor_schedule_id' => $schedule->id,
    //             'appointment_date' => $request->appointment_date,
    //             'consultation_type' => $schedule->consultation_type,
    //             'slot_duration' => $schedule->slot_duration,
    //             'slots' => $slots,
    //         ]
    //     ]);
    // }
}
