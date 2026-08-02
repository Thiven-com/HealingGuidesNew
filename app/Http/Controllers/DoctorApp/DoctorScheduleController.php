<?php

namespace App\Http\Controllers\DoctorApp;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DoctorScheduleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Get Doctor Schedules
    |--------------------------------------------------------------------------
    */

    public function schedules(Request $request)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $schedules = DoctorSchedule::where('doctor_id', $doctor->id);

        if ($request->filled('day_of_week')) {

            $schedules->where(
                'day_of_week',
                $request->day_of_week
            );
        }

        if ($request->filled('consultation_type')) {

            $schedules->where(
                'consultation_type',
                $request->consultation_type
            );
        }

        if ($request->filled('status')) {

            $schedules->where(
                'status',
                $request->status
            );
        }

        $schedules = $schedules
            ->orderByRaw("
            FIELD(
                day_of_week,
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday',
                'Sunday'
            )
        ")
            ->orderBy('available_from')
            ->get();

        return response()->json([
            'success' => 1,
            'message' => 'Doctor schedules fetched successfully.',
            'data' => $schedules
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Save / Update Schedule
    |--------------------------------------------------------------------------
    */

    public function saveSchedule(Request $request)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [

            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',

            'available_from' => 'required|date_format:H:i',

            'available_to' => 'required|date_format:H:i|after:available_from',

            'slot_duration' => 'required|integer|min:5',

            'consultation_type' => 'required|string|max:100',

            'status' => 'nullable|boolean',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Find Existing Schedule
        |--------------------------------------------------------------------------
        */

        $schedule = DoctorSchedule::where('doctor_id', $doctor->id)
            ->where('day_of_week', $request->day_of_week)
            ->where('consultation_type', $request->consultation_type)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Create If Not Exists
        |--------------------------------------------------------------------------
        */

        if (!$schedule) {

            $schedule = new DoctorSchedule();

            $schedule->doctor_id = $doctor->id;

            $schedule->day_of_week = $request->day_of_week;

            $schedule->consultation_type = $request->consultation_type;
        }

        /*
        |--------------------------------------------------------------------------
        | Create / Update
        |--------------------------------------------------------------------------
        */

        $schedule->available_from = $request->available_from;

        $schedule->available_to = $request->available_to;

        $schedule->slot_duration = $request->slot_duration;

        if ($request->has('status')) {
            $schedule->status = $request->status;
        } elseif (!$schedule->exists) {
            $schedule->status = 1;
        }

        $isNew = !$schedule->exists;

        $schedule->save();

        return response()->json([
            'success' => 1,
            'message' => $isNew
                ? 'Doctor schedule created successfully.'
                : 'Doctor schedule updated successfully.',
            'data' => $schedule
        ]);
    }
}