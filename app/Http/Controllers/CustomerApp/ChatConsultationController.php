<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\DoctorAppointment;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatConsultationController extends Controller
{
    private function getCustomerAppointment($appointmentId)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return null;
        }

        return DoctorAppointment::where('id', $appointmentId)
            ->where('customer_id', $customer->id)
            ->where('consultation_type', 'chat')
            ->where('payment_status', 'paid')
            ->where('appointment_status', 'confirmed')
            ->first();
    }

    /**
     * Join chat consultation
     */
    public function joinChat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:doctor_appointments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        $appointment = $this->getCustomerAppointment(
            $request->appointment_id
        );

        if (!$appointment) {
            return response()->json([
                'success' => 0,
                'message' => 'Chat appointment not found or unavailable.',
            ]);
        }

        if ($appointment->chat_ended_at) {
            return response()->json([
                'success' => 0,
                'message' => 'Chat consultation has already ended.',
            ]);
        }

        if (!$appointment->chat_started_at) {
            $appointment->chat_started_at = now();
            $appointment->save();
        }

        return response()->json([
            'success' => 1,
            'message' => 'Joined chat successfully.',
            'data' => [
                'appointment_id' => $appointment->id,
                'chat_started_at' => $appointment->chat_started_at,
                'chat_ended_at' => $appointment->chat_ended_at,
                'chat_status' => 'active',
            ],
        ]);
    }

    /**
     * Send customer message
     */
    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:doctor_appointments,id',
            'message' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        $appointment = $this->getCustomerAppointment(
            $request->appointment_id
        );

        if (!$appointment) {
            return response()->json([
                'success' => 0,
                'message' => 'Chat appointment not found.',
            ]);
        }

        if (!$appointment->chat_started_at) {
            return response()->json([
                'success' => 0,
                'message' => 'Please join the chat first.',
            ]);
        }

        if ($appointment->chat_ended_at) {
            return response()->json([
                'success' => 0,
                'message' => 'Chat consultation has ended.',
            ]);
        }

        $customer = auth('sanctum')->user();

        $chatMessage = ChatMessage::create([
            'appointment_id' => $appointment->id,
            'sender_type' => 'customer',
            'sender_id' => $customer->id,
            'message' => $request->message,
            'message_type' => 'text',
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Message sent successfully.',
            'data' => $chatMessage,
        ]);
    }

    /**
     * Get chat messages
     */
    public function messages(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:doctor_appointments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        $appointment = $this->getCustomerAppointment(
            $request->appointment_id
        );

        if (!$appointment) {
            return response()->json([
                'success' => 0,
                'message' => 'Chat appointment not found.',
            ]);
        }

        $messages = ChatMessage::where(
            'appointment_id',
            $appointment->id
        )
            ->orderBy('id')
            ->paginate(50);

        return response()->json([
            'success' => 1,
            'data' => [
                'appointment' => [
                    'id' => $appointment->id,
                    'chat_started_at' => $appointment->chat_started_at,
                    'chat_ended_at' => $appointment->chat_ended_at,
                    'is_active' => !$appointment->chat_ended_at,
                ],
                'messages' => $messages,
            ],
        ]);
    }

    /**
     * End customer chat
     */
    public function endChat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:doctor_appointments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        $appointment = $this->getCustomerAppointment(
            $request->appointment_id
        );

        if (!$appointment) {
            return response()->json([
                'success' => 0,
                'message' => 'Chat appointment not found.',
            ]);
        }

        if (!$appointment->chat_started_at) {
            return response()->json([
                'success' => 0,
                'message' => 'Chat has not started.',
            ]);
        }

        if (!$appointment->chat_ended_at) {
            $appointment->chat_ended_at = now();
            $appointment->appointment_status = 'completed';
            $appointment->save();
        }

        return response()->json([
            'success' => 1,
            'message' => 'Chat consultation ended successfully.',
            'data' => [
                'chat_started_at' => $appointment->chat_started_at,
                'chat_ended_at' => $appointment->chat_ended_at,
            ],
        ]);
    }
}