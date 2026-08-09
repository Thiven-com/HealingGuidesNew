<?php

namespace App\Services;

use App\Models\AppNotification;

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
        return AppNotification::create([
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
    }
}