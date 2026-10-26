<?php

namespace App\Services;

use App\Http\Controllers\Plugins\FireBaseController;
use App\Models\Ambulance;
use App\Models\AppNotification;
use App\Models\Customer;
use App\Models\Doctor;
use App\Models\Hospital;
use Illuminate\Support\Facades\Log;
use Throwable;

class NotificationService
{
    public static function send(
        string $notifiableType,
        int $notifiableId,
        string $type,
        string $title,
        string $message,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $action = null,
        array $data = []
    ) {
        $notification = AppNotification::create([
            'notifiable_type' => $notifiableType,

            'notifiable_id' => $notifiableId,

            'type' => $type,

            'title' => $title,

            'message' => $message,

            'reference_type' => $referenceType,

            'reference_id' => $referenceId,

            'action' => $action,

            'data' => $data,

            'is_read' => 0,

            'read_at' => null,

            'status' => 1,
        ]);

        // 2. Map recipient types to models
        $models = [
            'customer' => Customer::class,
            'doctor' => Doctor::class,
            'ambulance' => Ambulance::class,
            'hospital' => Hospital::class,
        ];

        if (!isset($models[$notifiableType])) {
            Log::warning('Unsupported notification recipient type', [
                'notifiable_type' => $notifiableType,
                'notifiable_id' => $notifiableId,
            ]);

            return $notification;
        }

        // 3. Get recipient
        $recipient = $models[$notifiableType]::find($notifiableId);

        if (!$recipient) {
            Log::warning('Notification recipient not found', [
                'notifiable_type' => $notifiableType,
                'notifiable_id' => $notifiableId,
            ]);

            return $notification;
        }

        // 4. Get Android and iOS tokens
        $tokens = array_unique(array_filter([
            $recipient->firebase_token ?? null,
            $recipient->ios_firebase_token ?? null,
        ]));

        if (empty($tokens)) {
            Log::info('No Firebase tokens found', [
                'notifiable_type' => $notifiableType,
                'notifiable_id' => $notifiableId,
            ]);

            return $notification;
        }

        // 5. Send Firebase notification using if condition

        if (!empty($recipient->firebase_token)) {

            $deviceToken = $recipient->firebase_token;

            $fire_base = [
                'title' => $title,
                'body' => $message,
                'message' => $message,
                'devicetoken' => $deviceToken,
                'user_id' => $notifiableId,
                'user_type' => $notifiableType,
                'module_type' => $type,
                'entity_id' => $referenceId,
                'sound' => 'ringtone',
                'data' => $data,
            ];

            try {
                $send = (new FireBaseController())
                    ->sendUpdatedFCMNotification($fire_base);

                Log::info('FCM notification response', [
                    'notifiable_type' => $notifiableType,
                    'notifiable_id' => $notifiableId,
                    'response' => $send,
                ]);

                if (
                    is_array($send) &&
                    (
                        isset($send['error']) ||
                        (isset($send['success']) && $send['success'] === 0)
                    )
                ) {
                    Log::error('FCM notification failed', [
                        'notifiable_type' => $notifiableType,
                        'notifiable_id' => $notifiableId,
                        'error' => $send,
                    ]);
                }
            } catch (Throwable $e) {
                Log::error('FCM notification exception', [
                    'notifiable_type' => $notifiableType,
                    'notifiable_id' => $notifiableId,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if (!empty($recipient->ios_firebase_token)) {

            $deviceToken = $recipient->ios_firebase_token;

            $fire_base = [
                'title' => $title,
                'body' => $message,
                'message' => $message,
                'devicetoken' => $deviceToken,
                'user_id' => $notifiableId,
                'user_type' => $notifiableType,
                'module_type' => $type,
                'entity_id' => $referenceId,
                'sound' => 'ringtone',
                'data' => $data,
            ];

            try {
                $send = (new FireBaseController())
                    ->sendUpdatedFCMNotification($fire_base);

                Log::info('iOS FCM notification response', [
                    'notifiable_type' => $notifiableType,
                    'notifiable_id' => $notifiableId,
                    'response' => $send,
                ]);

                if (
                    is_array($send) &&
                    (
                        isset($send['error']) ||
                        (isset($send['success']) && $send['success'] === 0)
                    )
                ) {
                    Log::error('iOS FCM notification failed', [
                        'notifiable_type' => $notifiableType,
                        'notifiable_id' => $notifiableId,
                        'error' => $send,
                    ]);
                }
            } catch (Throwable $e) {
                Log::error('iOS FCM notification exception', [
                    'notifiable_type' => $notifiableType,
                    'notifiable_id' => $notifiableId,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $notification;
    }
}