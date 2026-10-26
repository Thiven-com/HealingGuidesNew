<?php

namespace App\Http\Controllers\Plugins;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Services\FirebaseAuthService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class FireBaseController extends Controller
{
    //
    public function sendUpdatedFCMNotification($data)
    {
        try {
            // $token = $data['devicetoken'];
            $token = $data['device_token']
                ?? $data['devicetoken']
                ?? null;

            if (empty($token)) {
                Log::error('FCM device token missing', [
                    'received_keys' => array_keys($data),
                ]);

                return [
                    'success' => 0,
                    'message' => 'Device token is required.',
                    'error_code' => 400,
                ];
            }
            $accessTokenData = (new FirebaseAuthService())->accessToken();
            if (isset($data['time_to_live'])) {
                $timeToLive = (string) ($data['time_to_live']);
                // $iosTtl = (string) (time() + $data['time_to_live']);
            } else {
                $timeToLive = (string) ('3600');
                // $iosTtl = (string) (3600);
            }
            if ($accessTokenData['success'] == 1) {
                $accessToken = $accessTokenData['token'];
            } else {
                return "TokenError";
            }

            $projectId = config('services.firebase.project_id');

            $response = Http::withoutVerifying()->withToken($accessToken)
                ->post(
                    "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send",
                    [
                        'message' => [
                            'token' => $token,
                            'notification' => [
                                'title' => $data['title'] ?? 'Healing Guides',
                                'body' => $data['body'] ?? 'You have a new notification',
                            ],
                            'data' => [
                                'notify_type' => (string) ($data['notify_type'] ?? 'NA'),
                                'user_type' => (string) ($data['user_type'] ?? 'NA'),
                                'module_type' => (string) ($data['module_type'] ?? 'NA'),
                                'entity_id' => (string) ($data['entity_id'] ?? 'NA'),
                                'message' => (string) ($data['message'] ?? 'NA'),
                                'order_id' => (string) ($data['order_id'] ?? 'NA'),
                                'user_id' => (string) ($data['user_id'] ?? 'NA'),
                                'message_id' => (string) ($data['message_id'] ?? 'NA'),
                            ],
                            'android' => [
                                'priority' => 'high',
                                'ttl' => (string) ($data['time_to_live'] ?? 3600) . 's',
                                'notification' => [
                                    'channel_id' => 'healing_guides',
                                    'sound' => $data['sound'] ?? 'default',
                                ],
                            ],
                        ],
                    ]
                );

            if (!$response->successful()) {
                Log::error('FCM Notification Failed', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);
            }

            return $response->json();

            // $comment = new Comment();
            // $comment->user_id = $data['user_id'] ?? null;
            // $comment->user_type = $data['user_type'] ?? null;
            // $comment->title = $data['title'] ?? null;
            // $comment->body = $data['body'] ?? null;
            // $comment->message = $data['message'] ?? null;
            // $comment->module_type = $data['module_type'] ?? null;
            // $comment->entity_id = $data['entity_id'] ?? null;
            // $comment->status = "success";
            // $comment->save();
            // if ($response->successful()) {

            //     Log::info('FCM Notification Sent Successfully');

            // } else {

            //     Log::error(
            //         'FCM Notification Failed: ' . $response->body()
            //     );
            // }
            return $response->json();
        } catch (\Exception $e) {
            // $comment = new Comment();
            // $comment->user_id = $data['user_id'] ?? null;
            // $comment->user_type = $data['user_type'] ?? null;
            // $comment->title = $data['title'] ?? null;
            // $comment->body = $data['body'] ?? null;
            // $comment->message = $data['message'] ?? null;
            // $comment->module_type = $data['module_type'] ?? null;
            // $comment->entity_id = $data['entity_id'] ?? null;
            // $comment->error_message = $e->getMessage() ?? null;
            // $comment->status = "pending";
            // $comment->save();
            Log::error(
                'FCM Notification Error: ' . $e->getMessage()
            );
            return $e->getMessage();
        }
    }
}
