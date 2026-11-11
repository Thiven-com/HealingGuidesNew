<?php

namespace App\Http\Controllers\DoctorApp;

use App\Http\Controllers\Controller;
use App\Models\DoctorAppointment;
use App\Models\VideoRoom;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VideoCallController extends Controller
{
    //
    public function joinVideoRoom(Request $request)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
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
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$appointment) {
            return response()->json([
                'success' => 0,
                'message' => 'Appointment not found.'
            ]);
        }

        $room = VideoRoom::where('appointment_id', $appointment->id)->first();

        if (!$room) {
            return response()->json([
                'success' => 0,
                'message' => 'Video room not found.'
            ]);
        }

        $room->doctor_online = true;
        $room->doctor_joined_at = now();
        $room->doctor_ip = $request->ip();

        if ($room->customer_joined_at) {

            $room->status = 'live';

            if (!$room->started_at) {
                $room->started_at = now();
            }

        } else {

            $room->status = 'doctor_joined';
        }

        $room->save();

        NotificationService::send(
            'customer',
            $appointment->customer_id,
            'video_consultation',
            'Doctor Joined Video Consultation',
            'Doctor ' . $doctor->name . ' has joined the video consultation for appointment ' . $appointment->appointment_no . '.',
            'video_consultation_doctor_joined',
            $appointment->id,
            'appointment_details',
            [
                'appointment_id' => $appointment->id,
                'appointment_no' => $appointment->appointment_no,
                'doctor_id' => $doctor->id,
                'doctor_name' => $doctor->name,
                'customer_id' => $appointment->customer_id,
                'consultation_type' => $appointment->consultation_type,
                'appointment_date' => $appointment->appointment_date,
                'appointment_time' => $appointment->appointment_time,
                'room_id' => $room->room_id,
                'meeting_status' => $room->status,
            ]
        );



        return response()->json([
            'success' => 1,
            'message' => 'Doctor joined successfully.',
            'data' => $room
        ]);
    }

    public function videoRoomStatus($roomId)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $room = VideoRoom::with([
            'appointment',
            'customer'
        ])
            ->where('room_id', $roomId)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$room) {

            return response()->json([
                'success' => 0,
                'message' => 'Video room not found.'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Video room status fetched successfully.',
            'data' => $room
        ]);
    }
    public function leaveVideoRoom(Request $request)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
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

        $room = VideoRoom::where('appointment_id', $request->appointment_id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$room) {

            return response()->json([
                'success' => 0,
                'message' => 'Video room not found.'
            ]);
        }

        $room->doctor_online = false;
        // $room->doctor_left_at = now();

        if ($room->status != 'ended') {
            $room->status = 'waiting';
        }

        $room->save();

        NotificationService::send(
            'customer',
            $room->customer_id,
            'video_consultation',
            'Doctor Left Video Consultation',
            'Doctor ' . $doctor->name . ' has left the video consultation for appointment ' . $room->appointment->appointment_no . '.',
            'video_consultation_doctor_left',
            $room->appointment_id,
            'appointment_details',
            [
                'appointment_id' => $room->appointment_id,
                'appointment_no' => $room->appointment->appointment_no,
                'doctor_id' => $doctor->id,
                'doctor_name' => $doctor->name,
                'customer_id' => $room->customer_id,
                'room_id' => $room->room_id,
                'meeting_status' => $room->status,
            ]
        );

        return response()->json([
            'success' => 1,
            'message' => 'Doctor left video room.'
        ]);
    }
    public function endVideoCall(Request $request)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
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
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$appointment) {

            return response()->json([
                'success' => 0,
                'message' => 'Appointment not found.'
            ]);
        }

        $room = VideoRoom::where('appointment_id', $appointment->id)->first();

        if (!$room) {

            return response()->json([
                'success' => 0,
                'message' => 'Video room not found.'
            ]);
        }

        $room->status = 'ended';
        $room->ended_at = now();
        $room->ended_by = 'doctor';
        $room->doctor_online = false;
        $room->customer_online = false;

        if ($room->started_at) {
            $room->duration = now()->diffInSeconds($room->started_at);
        }

        $room->save();

        $appointment->appointment_status = 'completed';
        $appointment->meeting_status = 'completed';
        $appointment->meeting_ended_at = now();
        $appointment->save();

        NotificationService::send(
            'customer',
            $appointment->customer_id,
            'video_consultation',
            'Video Consultation Completed',
            'Doctor ' . $doctor->name . ' has completed the video consultation for appointment ' . $appointment->appointment_no . '.',
            'video_consultation_completed',
            $appointment->id,
            'appointment_details',
            [
                'appointment_id' => $appointment->id,
                'appointment_no' => $appointment->appointment_no,
                'doctor_id' => $doctor->id,
                'doctor_name' => $doctor->name,
                'customer_id' => $appointment->customer_id,
                'consultation_type' => $appointment->consultation_type,
                'appointment_date' => $appointment->appointment_date,
                'appointment_time' => $appointment->appointment_time,
                'room_id' => $room->room_id,
                'meeting_status' => $room->status,
                'duration' => $room->duration,
            ]
        );

        return response()->json([
            'success' => 1,
            'message' => 'Video consultation completed successfully.'
        ]);
    }
}
