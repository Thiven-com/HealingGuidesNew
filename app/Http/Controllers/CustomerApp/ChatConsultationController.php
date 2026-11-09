<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\DoctorAppointment;
use App\Models\ChatMessage;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ChatConsultationController extends Controller
{
    /**
     * Get customer appointment.
     */
    private function getCustomerAppointment($appointmentId)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return null;
        }

        return DoctorAppointment::with(['doctor', 'customer', 'hospital'])
            ->where('id', $appointmentId)
            ->where('customer_id', $customer->id)
            ->where('consultation_type', 'chat')
            ->where('payment_status', 'paid')
            ->whereIn('appointment_status', ['confirmed', 'completed'])
            ->first();
    }

    /**
     * Check chat expiration.
     */
    private function isChatExpired($appointment): bool
    {
        if (!$appointment->chat_started_at || !$appointment->chat_expires_at) {
            return false;
        }

        return now()->greaterThanOrEqualTo($appointment->chat_expires_at);
    }

    /**
     * Check whether chat is available for messaging.
     */
    private function canSendMessage($appointment): bool
    {
        return $appointment->chat_started_at
            && !$appointment->chat_ended_at
            && !$appointment->customer_chat_ended_at
            && !$appointment->doctor_chat_ended_at
            && !$this->isChatExpired($appointment);
    }

    /**
     * Join customer chat.
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
            ], 422);
        }

        $customer = auth('sanctum')->user();

        $appointment = $this->getCustomerAppointment(
            $request->appointment_id
        );

        if (!$appointment) {
            return response()->json([
                'success' => 0,
                'message' => 'Chat appointment not found or unavailable.',
            ], 404);
        }

        if ($appointment->chat_ended_at || $appointment->customer_chat_ended_at) {
            return response()->json([
                'success' => 0,
                'message' => 'Chat consultation has already ended.',
            ]);
        }

        if ($this->isChatExpired($appointment)) {
            return response()->json([
                'success' => 0,
                'message' => 'Chat consultation time has expired.',
                'chat_expires_at' => $appointment->chat_expires_at,
            ]);
        }

        DB::transaction(function () use ($appointment) {

            $appointment = DoctorAppointment::where('id', $appointment->id)
                ->lockForUpdate()
                ->first();

            if (!$appointment->customer_chat_joined_at) {
                $appointment->customer_chat_joined_at = now();
            }

            // Start shared chat only after both participants join.
            if (
                $appointment->customer_chat_joined_at &&
                $appointment->doctor_chat_joined_at &&
                !$appointment->chat_started_at
            ) {
                $appointment->chat_started_at = now();

                $appointment->chat_duration_minutes =
                    $appointment->chat_duration_minutes ?: 10;

                $appointment->chat_expires_at = now()->addMinutes(
                    $appointment->chat_duration_minutes
                );
            }

            $appointment->save();
        });

        $appointment->refresh();

        // Notify doctor that customer is waiting
        if (
            $appointment->customer_chat_joined_at &&
            !$appointment->doctor_chat_joined_at &&
            !$appointment->chat_ended_at &&
            !$appointment->chat_started_at
        ) {
            try {
                NotificationService::send(
                    'doctor',
                    $appointment->doctor_id,
                    'chat',
                    'Customer Waiting in Chat',
                    'A customer is waiting for you in the chat consultation.',
                    'doctor_appointment',
                    $appointment->id,
                    'chat_conversation',
                    [
                        'appointment_id' => $appointment->id,
                        'appointment_no' => $appointment->appointment_no,

                        'customer_id' => $appointment->customer_id,
                        'customer_name' => $appointment->customer->name ?? null,

                        'doctor_id' => $appointment->doctor_id,
                        'doctor_name' => $appointment->doctor->doctor_name ?? null,

                        'consultation_type' => 'chat',

                        'customer_chat_joined_at' => $appointment->customer_chat_joined_at,

                        'notification_type' => 'customer_waiting',
                    ]
                );
            } catch (\Throwable $e) {
                Log::error('Customer waiting notification failed', [
                    'appointment_id' => $appointment->id,
                    'doctor_id' => $appointment->doctor_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'success' => 1,
            'message' => $appointment->chat_started_at
                ? 'Chat joined successfully.'
                : 'You joined successfully. Waiting for doctor to join.',
            'data' => [
                'appointment_id' => $appointment->id,

                'customer_chat_joined_at' => $appointment->customer_chat_joined_at,
                'doctor_chat_joined_at' => $appointment->doctor_chat_joined_at,

                'chat_started_at' => $appointment->chat_started_at,
                'chat_expires_at' => $appointment->chat_expires_at,

                'chat_ended_at' => $appointment->chat_ended_at,

                'chat_duration_minutes' => $appointment->chat_duration_minutes,

                'remaining_seconds' => $this->canSendMessage($appointment)
                    ? max(0, now()->diffInSeconds($appointment->chat_expires_at, false))
                    : 0,

                'chat_status' => $appointment->chat_started_at
                    ? 'active'
                    : 'waiting_for_doctor',
            ],
        ]);
    }

    /**
     * Send customer message.
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
            ], 422);
        }

        $customer = auth('sanctum')->user();

        $appointment = $this->getCustomerAppointment(
            $request->appointment_id
        );

        if (!$appointment) {
            return response()->json([
                'success' => 0,
                'message' => 'Chat appointment not found.',
            ], 404);
        }

        if (!$appointment->customer_chat_joined_at) {
            return response()->json([
                'success' => 0,
                'message' => 'Please join the chat first.',
            ]);
        }

        if (!$appointment->doctor_chat_joined_at || !$appointment->chat_started_at) {
            return response()->json([
                'success' => 0,
                'message' => 'Waiting for doctor to join the chat.',
            ]);
        }

        if (!$this->canSendMessage($appointment)) {
            return response()->json([
                'success' => 0,
                'message' => 'Chat has ended or expired. Messages are disabled.',
                'chat_expires_at' => $appointment->chat_expires_at,
            ]);
        }

        $chatMessage = ChatMessage::create([
            'appointment_id' => $appointment->id,
            'sender_type' => 'customer',
            'sender_id' => $customer->id,
            'message' => $request->message,
            'message_type' => 'text',
        ]);

        // Notify doctor.
        try {
            if ($appointment->doctor) {
                NotificationService::send(
                    'doctor',
                    $appointment->doctor_id,
                    'chat',
                    'New Chat Message',
                    'You have received a new message from ' .
                    ($customer->name ?? 'Customer') . '.',
                    'doctor_appointment',
                    $appointment->id,
                    'chat_conversation',
                    [
                        'appointment_id' => $appointment->id,
                        'appointment_no' => $appointment->appointment_no,

                        'customer_id' => $customer->id,
                        'customer_name' => $customer->name,

                        'doctor_id' => $appointment->doctor_id,
                        'doctor_name' => $appointment->doctor->doctor_name ?? null,

                        'message_id' => $chatMessage->id,
                        'message' => $chatMessage->message,
                        'message_type' => $chatMessage->message_type,

                        'consultation_type' => $appointment->consultation_type,

                        'chat_started_at' => $appointment->chat_started_at,
                        'chat_expires_at' => $appointment->chat_expires_at,
                    ]
                );
            }
        } catch (\Throwable $e) {
            Log::error('Customer chat notification failed', [
                'appointment_id' => $appointment->id,
                'doctor_id' => $appointment->doctor_id,
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Message sent successfully.',
            'data' => $chatMessage,
        ]);
    }

    /**
     * Get chat messages.
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
            ], 422);
        }

        $appointment = $this->getCustomerAppointment(
            $request->appointment_id
        );

        if (!$appointment) {
            return response()->json([
                'success' => 0,
                'message' => 'Chat appointment not found.',
            ], 404);
        }

        // Mark doctor's messages as read by customer.
        ChatMessage::where('appointment_id', $appointment->id)
            ->where('sender_type', 'doctor')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = ChatMessage::where('appointment_id', $appointment->id)
            ->orderBy('id', 'asc')
            ->paginate(50);

        $active = $this->canSendMessage($appointment);

        return response()->json([
            'success' => 1,
            'data' => [
                'appointment' => [
                    'id' => $appointment->id,
                    'appointment_no' => $appointment->appointment_no,

                    'customer_chat_joined_at' => $appointment->customer_chat_joined_at,
                    'doctor_chat_joined_at' => $appointment->doctor_chat_joined_at,

                    'customer_chat_ended_at' => $appointment->customer_chat_ended_at,
                    'doctor_chat_ended_at' => $appointment->doctor_chat_ended_at,

                    'chat_started_at' => $appointment->chat_started_at,
                    'chat_expires_at' => $appointment->chat_expires_at,
                    'chat_ended_at' => $appointment->chat_ended_at,

                    'chat_duration_minutes' => $appointment->chat_duration_minutes,

                    'remaining_seconds' => $active
                        ? max(0, now()->diffInSeconds($appointment->chat_expires_at, false))
                        : 0,

                    'is_active' => $active,
                ],

                'messages' => $messages,
            ],
        ]);
    }

    /**
     * Customer ends chat.
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
            ], 422);
        }

        $appointment = $this->getCustomerAppointment(
            $request->appointment_id
        );

        if (!$appointment) {
            return response()->json([
                'success' => 0,
                'message' => 'Chat appointment not found.',
            ], 404);
        }

        if (!$appointment->customer_chat_joined_at) {
            return response()->json([
                'success' => 0,
                'message' => 'Customer has not joined the chat.',
            ]);
        }

        if (!$appointment->customer_chat_ended_at) {
            DB::transaction(function () use ($appointment) {

                $locked = DoctorAppointment::where('id', $appointment->id)
                    ->lockForUpdate()
                    ->first();

                if (!$locked->customer_chat_ended_at) {
                    $locked->customer_chat_ended_at = now();

                    // First participant to end closes shared chat.
                    if (!$locked->chat_ended_at) {
                        $locked->chat_ended_at = now();
                        $locked->appointment_status = 'completed';
                    }

                    $locked->save();
                }
            });
        }

        $appointment->refresh();

        return response()->json([
            'success' => 1,
            'message' => 'Customer ended the chat.',
            'data' => [
                'customer_chat_joined_at' => $appointment->customer_chat_joined_at,
                'customer_chat_ended_at' => $appointment->customer_chat_ended_at,

                'doctor_chat_joined_at' => $appointment->doctor_chat_joined_at,
                'doctor_chat_ended_at' => $appointment->doctor_chat_ended_at,

                'chat_started_at' => $appointment->chat_started_at,
                'chat_expires_at' => $appointment->chat_expires_at,
                'chat_ended_at' => $appointment->chat_ended_at,

                'chat_status' => 'ended',
            ],
        ]);
    }
}
