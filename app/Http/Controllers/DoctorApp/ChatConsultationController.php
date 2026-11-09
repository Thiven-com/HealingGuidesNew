<?php

namespace App\Http\Controllers\DoctorApp;

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
     * Get appointment belonging to authenticated doctor.
     */
    private function getDoctorAppointment($appointmentId)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return null;
        }

        return DoctorAppointment::with([
            'doctor',
            'customer',
            'hospital',
        ])
            ->where('id', $appointmentId)
            ->where('doctor_id', $doctor->id)
            ->where('consultation_type', 'chat')
            ->where('payment_status', 'paid')
            ->whereIn('appointment_status', ['confirmed', 'completed'])
            ->first();
    }

    /**
     * Check whether chat duration has expired.
     */
    private function isChatExpired($appointment): bool
    {
        if (!$appointment->chat_started_at) {
            return false;
        }

        if (!$appointment->chat_expires_at) {
            return true;
        }

        return now()->greaterThanOrEqualTo($appointment->chat_expires_at);
    }

    /**
     * Check whether messaging is allowed.
     */
    private function canSendMessage($appointment): bool
    {
        return $appointment->customer_chat_joined_at !== null
            && $appointment->doctor_chat_joined_at !== null
            && $appointment->chat_started_at !== null
            && $appointment->chat_expires_at !== null
            && !$appointment->chat_ended_at
            && !$appointment->customer_chat_ended_at
            && !$appointment->doctor_chat_ended_at
            && now()->lessThan($appointment->chat_expires_at);
    }

    /**
     * Send notification safely.
     */
    private function sendChatNotification(
        $recipientType,
        $recipientId,
        $title,
        $message,
        $appointment,
        $notificationType,
        array $extraData = []
    ): void {
        try {
            NotificationService::send(
                $recipientType,
                $recipientId,
                'chat',
                $title,
                $message,
                'doctor_appointment',
                $appointment->id,
                'chat_conversation',
                array_merge([
                    'appointment_id' => $appointment->id,
                    'appointment_no' => $appointment->appointment_no,

                    'customer_id' => $appointment->customer_id,
                    'customer_name' => $appointment->customer->name ?? null,

                    'doctor_id' => $appointment->doctor_id,
                    'doctor_name' => $appointment->doctor->doctor_name ?? null,

                    'consultation_type' => $appointment->consultation_type,

                    'customer_chat_joined_at' => $appointment->customer_chat_joined_at,
                    'doctor_chat_joined_at' => $appointment->doctor_chat_joined_at,

                    'chat_started_at' => $appointment->chat_started_at,
                    'chat_expires_at' => $appointment->chat_expires_at,

                    'notification_type' => $notificationType,
                ], $extraData)
            );
        } catch (\Throwable $e) {
            Log::error('Chat notification failed', [
                'appointment_id' => $appointment->id,
                'recipient_type' => $recipientType,
                'recipient_id' => $recipientId,
                'notification_type' => $notificationType,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Join doctor chat consultation.
     *
     * Timer starts only after both doctor and customer join.
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

        $appointment = $this->getDoctorAppointment(
            $request->appointment_id
        );

        if (!$appointment) {
            return response()->json([
                'success' => 0,
                'message' => 'Chat appointment not found or unavailable.',
            ], 404);
        }

        if (
            $appointment->chat_ended_at ||
            $appointment->doctor_chat_ended_at ||
            $appointment->customer_chat_ended_at
        ) {
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

        $result = DB::transaction(function () use ($appointment) {

            $locked = DoctorAppointment::where('id', $appointment->id)
                ->lockForUpdate()
                ->first();

            if (
                $locked->chat_ended_at ||
                $locked->doctor_chat_ended_at ||
                $locked->customer_chat_ended_at
            ) {
                return [
                    'error' => 'Chat consultation has already ended.',
                ];
            }

            if (
                $locked->chat_expires_at &&
                now()->greaterThanOrEqualTo($locked->chat_expires_at)
            ) {
                return [
                    'error' => 'Chat consultation time has expired.',
                ];
            }

            $firstDoctorJoin = false;

            if (!$locked->doctor_chat_joined_at) {
                $locked->doctor_chat_joined_at = now();
                $firstDoctorJoin = true;
            }

            // Start timer only when both participants have joined.
            if (
                $locked->customer_chat_joined_at &&
                $locked->doctor_chat_joined_at &&
                !$locked->chat_started_at
            ) {
                $locked->chat_duration_minutes =
                    $locked->chat_duration_minutes ?: 10;

                $locked->chat_started_at = now();

                $locked->chat_expires_at = now()->addMinutes(
                    $locked->chat_duration_minutes
                );
            }

            $locked->save();

            return [
                'first_doctor_join' => $firstDoctorJoin,
                'customer_waiting' => !$locked->customer_chat_joined_at,
            ];
        });

        if (isset($result['error'])) {
            return response()->json([
                'success' => 0,
                'message' => $result['error'],
            ]);
        }

        $appointment->refresh();

        // Notify customer if doctor joins first.
        if (
            $result['first_doctor_join'] &&
            $result['customer_waiting'] &&
            $appointment->customer
        ) {
            $this->sendChatNotification(
                'customer',
                $appointment->customer_id,
                'Doctor Waiting in Chat',
                'Dr. ' .
                ($appointment->doctor->doctor_name ?? 'Doctor') .
                ' is waiting for you in the chat consultation.',
                $appointment,
                'doctor_waiting'
            );
        }

        $active = $this->canSendMessage($appointment);

        return response()->json([
            'success' => 1,
            'message' => $active
                ? 'Chat started successfully.'
                : 'Doctor joined successfully. Waiting for customer to join.',
            'data' => [
                'appointment_id' => $appointment->id,

                'customer_chat_joined_at' => $appointment->customer_chat_joined_at,
                'doctor_chat_joined_at' => $appointment->doctor_chat_joined_at,

                'customer_chat_ended_at' => $appointment->customer_chat_ended_at,
                'doctor_chat_ended_at' => $appointment->doctor_chat_ended_at,

                'chat_started_at' => $appointment->chat_started_at,
                'chat_expires_at' => $appointment->chat_expires_at,
                'chat_ended_at' => $appointment->chat_ended_at,

                'chat_duration_minutes' => $appointment->chat_duration_minutes,

                'remaining_seconds' => $active
                    ? max(
                        0,
                        now()->diffInSeconds(
                            $appointment->chat_expires_at,
                            false
                        )
                    )
                    : 0,

                'chat_status' => $active
                    ? 'active'
                    : 'waiting_for_customer',
            ],
        ]);
    }

    /**
     * Send doctor message to customer.
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

        $appointment = $this->getDoctorAppointment(
            $request->appointment_id
        );

        if (!$appointment) {
            return response()->json([
                'success' => 0,
                'message' => 'Chat appointment not found.',
            ], 404);
        }

        if (!$appointment->doctor_chat_joined_at) {
            return response()->json([
                'success' => 0,
                'message' => 'Please join the chat first.',
            ]);
        }

        if (
            !$appointment->customer_chat_joined_at ||
            !$appointment->chat_started_at
        ) {
            return response()->json([
                'success' => 0,
                'message' => 'Waiting for customer to join the chat.',
            ]);
        }

        if (!$this->canSendMessage($appointment)) {
            return response()->json([
                'success' => 0,
                'message' => 'Chat has ended or expired. Messages are disabled.',
                'chat_expires_at' => $appointment->chat_expires_at,
            ]);
        }

        $doctor = auth('sanctum')->user();

        $chatMessage = ChatMessage::create([
            'appointment_id' => $appointment->id,
            'sender_type' => 'doctor',
            'sender_id' => $doctor->id,
            'message' => $request->message,
            'message_type' => 'text',
        ]);

        // Notify customer after successfully saving the message.
        if ($appointment->customer) {
            $this->sendChatNotification(
                'customer',
                $appointment->customer_id,
                'New Chat Message',
                'You have received a new message from Dr. ' .
                ($appointment->doctor->doctor_name ?? 'Doctor') . '.',
                $appointment,
                'new_message',
                [
                    'message_id' => $chatMessage->id,
                    'message' => $chatMessage->message,
                    'message_type' => $chatMessage->message_type,
                ]
            );
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

        $appointment = $this->getDoctorAppointment(
            $request->appointment_id
        );

        if (!$appointment) {
            return response()->json([
                'success' => 0,
                'message' => 'Chat appointment not found.',
            ], 404);
        }

        // Mark customer messages as read by doctor.
        ChatMessage::where('appointment_id', $appointment->id)
            ->where('sender_type', 'customer')
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);

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

                    'customer_id' => $appointment->customer_id,
                    'customer_name' => $appointment->customer->name ?? null,

                    'doctor_id' => $appointment->doctor_id,
                    'doctor_name' => $appointment->doctor->doctor_name ?? null,

                    'customer_chat_joined_at' => $appointment->customer_chat_joined_at,
                    'doctor_chat_joined_at' => $appointment->doctor_chat_joined_at,

                    'customer_chat_ended_at' => $appointment->customer_chat_ended_at,
                    'doctor_chat_ended_at' => $appointment->doctor_chat_ended_at,

                    'chat_duration_minutes' => $appointment->chat_duration_minutes,

                    'chat_started_at' => $appointment->chat_started_at,
                    'chat_expires_at' => $appointment->chat_expires_at,
                    'chat_ended_at' => $appointment->chat_ended_at,

                    'remaining_seconds' => $active
                        ? max(
                            0,
                            now()->diffInSeconds(
                                $appointment->chat_expires_at,
                                false
                            )
                        )
                        : 0,

                    'is_active' => $active,
                ],

                'messages' => $messages,
            ],
        ]);
    }

    /**
     * Doctor ends chat.
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

        $appointment = $this->getDoctorAppointment(
            $request->appointment_id
        );

        if (!$appointment) {
            return response()->json([
                'success' => 0,
                'message' => 'Chat appointment not found.',
            ], 404);
        }

        if (!$appointment->doctor_chat_joined_at) {
            return response()->json([
                'success' => 0,
                'message' => 'Doctor has not joined the chat.',
            ]);
        }

        DB::transaction(function () use ($appointment) {

            $locked = DoctorAppointment::where('id', $appointment->id)
                ->lockForUpdate()
                ->first();

            if (!$locked->doctor_chat_ended_at) {
                $locked->doctor_chat_ended_at = now();
            }

            // First participant to end closes the shared chat.
            if (!$locked->chat_ended_at) {
                $locked->chat_ended_at = now();
                $locked->appointment_status = 'completed';
            }

            $locked->save();
        });

        $appointment->refresh();

        return response()->json([
            'success' => 1,
            'message' => 'Doctor ended the chat successfully.',
            'data' => [
                'customer_chat_joined_at' => $appointment->customer_chat_joined_at,
                'doctor_chat_joined_at' => $appointment->doctor_chat_joined_at,

                'customer_chat_ended_at' => $appointment->customer_chat_ended_at,
                'doctor_chat_ended_at' => $appointment->doctor_chat_ended_at,

                'chat_started_at' => $appointment->chat_started_at,
                'chat_expires_at' => $appointment->chat_expires_at,
                'chat_ended_at' => $appointment->chat_ended_at,

                'chat_status' => 'ended',
            ],
        ]);
    }
}
