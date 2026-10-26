<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Plugins\FireBaseController;
use App\Jobs\SendAdminNotificationJob;
use App\Models\AppNotification;
use App\Models\Customer;
use App\Models\Merchant;
use App\Models\Franchise;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminNotificationController extends Controller
{
    /**
     * Notification history
     */
    public function index(Request $request)
    {
        $notifications = AppNotification::where('sender', 'admin')
            ->when($request->user_type, function ($query) use ($request) {
                $query->where('user_type', $request->user_type);
            })
            ->when($request->type, function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        return view(
            'admin.notifications.index',
            compact('notifications')
        );
    }


    /**
     * Create notification page
     */
    public function create()
    {
        return view('admin.notifications.create');
    }


    /**
     * Send notification
     */
    public function send(Request $request)
    {
        $request->validate([
            'target' => [
                'required',
                'in:customer,merchant,franchise,all'
            ],

            'user_ids' => [
                'nullable',
                'string'
            ],

            'title' => [
                'required',
                'string',
                'max:255'
            ],

            'message' => [
                'required',
                'string',
                'max:5000'
            ],

            'type' => [
                'required',
                'string',
                'max:100'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Selected Customer IDs
        |--------------------------------------------------------------------------
        */

        $userIds = null;

        if ($request->target === 'customer' && $request->filled('user_ids')) {

            $userIds = collect(
                explode(',', $request->user_ids)
            )
                ->map(fn($id) => trim($id))
                ->filter(fn($id) => is_numeric($id))
                ->map(fn($id) => (int) $id)
                ->unique()
                ->values()
                ->toArray();

            if (empty($userIds)) {

                return back()
                    ->withInput()
                    ->with('error', 'Please enter valid customer IDs.');
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Dispatch Job
        |--------------------------------------------------------------------------
        */

        SendAdminNotificationJob::dispatch(
            target: $request->target,
            title: $request->title,
            message: $request->message,
            type: $request->type,
            userIds: $userIds
        )->onQueue('notifications');


        return redirect()
            ->route('admin.dashboard')
            ->with(
                'success',
                'Notification sending has been started in background.'
            );
    }


    /**
     * Customers
     */
    private function sendToCustomers(
        string $title,
        string $message,
        string $type,
    ): int {

        $count = 0;
        $userIds = ["WGW1978058", "WGI3094269"];
        Customer::query()
            ->select([
                'id',
                'name',
                'firebase_token',
                'ios_firebase_token'
            ])
            // ->whereIn('userid', $userIds)
            ->where(function ($query) {

                $query->whereNotNull('firebase_token')
                    ->where('firebase_token', '!=', '');

                $query->orWhere(function ($q) {

                    $q->whereNotNull('ios_firebase_token')
                        ->where('ios_firebase_token', '!=', '');

                });

            })
            ->chunkById(100, function ($customers) use ($title, $message, $type, &$count) {

                foreach ($customers as $customer) {

                    $this->sendNotification(
                        $customer,
                        'customer',
                        $title,
                        $message,
                        $type
                    );

                    $count++;
                }
            });

        return $count;
    }


    /**
     * Merchants
     */
    private function sendToMerchants(
        string $title,
        string $message,
        string $type
    ): int {

        $count = 0;

        Merchant::query()
            ->select([
                'id',
                'shop_name',
                'firebase_token'
            ])
            ->whereNotNull('firebase_token')
            ->where('firebase_token', '!=', '')
            ->chunkById(100, function ($merchants) use ($title, $message, $type, &$count) {

                foreach ($merchants as $merchant) {

                    $this->sendNotification(
                        $merchant,
                        'merchant',
                        $title,
                        $message,
                        $type
                    );

                    $count++;
                }
            });

        return $count;
    }


    /**
     * Franchise / Partners
     */
    private function sendToFranchises(
        string $title,
        string $message,
        string $type,
    ): int {

        $count = 0;

        Franchise::query()
            ->select([
                'id',
                'firebase_token'
            ])
            ->whereNotNull('firebase_token')
            ->where('firebase_token', '!=', '')
            ->chunkById(100, function ($franchises) use ($title, $message, $type, &$count) {

                foreach ($franchises as $franchise) {

                    $this->sendNotification(
                        $franchise,
                        'franchise',
                        $title,
                        $message,
                        $type
                    );

                    $count++;
                }
            });

        return $count;
    }


    /**
     * Specific User
     */
    private function sendToSpecificUser(
        string $target,
        int $userId,
        string $title,
        string $message,
        string $type
    ): bool {

        switch ($target) {

            case 'customer':

                $user = Customer::find($userId);

                if (!$user) {
                    return false;
                }

                // Customer must have at least one token
                if (
                    empty($user->firebase_token) &&
                    empty($user->ios_firebase_token)
                ) {
                    return false;
                }

                $this->sendNotification(
                    $user,
                    'customer',
                    $title,
                    $message,
                    $type
                );

                return true;


            case 'merchant':

                $user = Merchant::find($userId);

                if (!$user) {
                    return false;
                }

                if (empty($user->firebase_token)) {
                    return false;
                }

                $this->sendNotification(
                    $user,
                    'merchant',
                    $title,
                    $message,
                    $type
                );

                return true;


            case 'franchise':

                $user = Franchise::find($userId);

                if (!$user) {
                    return false;
                }

                if (empty($user->firebase_token)) {
                    return false;
                }

                $this->sendNotification(
                    $user,
                    'franchise',
                    $title,
                    $message,
                    $type
                );

                return true;
        }

        return false;
    }


    /**
     * Common Notification Method
     */
    private function sendNotification(
        $user,
        string $userType,
        string $title,
        string $message,
        string $type,
        $entityId = null
    ) {

        try {

            /*
            |--------------------------------------------------------------------------
            | CUSTOMER
            |--------------------------------------------------------------------------
            |
            | Customer:
            | Android = firebase_token
            | iOS     = ios_firebase_token
            |
            */

            if ($userType === 'customer') {

                // No token at all
                if (
                    empty($user->firebase_token) &&
                    empty($user->ios_firebase_token)
                ) {
                    return false;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | MERCHANT / FRANCHISE
            |--------------------------------------------------------------------------
            |
            | Only Android FCM token
            |
            */

            if (
                $userType === 'merchant' ||
                $userType === 'franchise'
            ) {

                if (empty($user->firebase_token)) {
                    return false;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Save Database Notification
            |--------------------------------------------------------------------------
            */

            $notification = new AppNotification();

            $notification->title = $title;
            $notification->message = $message;
            $notification->date = Carbon::now();
            $notification->user_type = $userType;
            $notification->type = $type;
            $notification->user_id = $user->id;
            $notification->sender_id = 0;
            $notification->sender = 'admin';

            $notification->save();


            /*
            |--------------------------------------------------------------------------
            | Android FCM
            |--------------------------------------------------------------------------
            */

            if (!empty($user->firebase_token)) {

                $firebaseData = [
                    'title' => $title,
                    'body' => $message,
                    'message' => $message,
                    'devicetoken' => $user->firebase_token,
                    'user_id' => $user->id,
                    'user_type' => $userType,
                    'entity_id' => $entityId,
                ];

                try {

                    (new FireBaseController())
                        ->sendUpdatedFCMNotification(
                            $firebaseData
                        );

                } catch (\Throwable $e) {

                    Log::error(
                        'Android FCM notification failed',
                        [
                            'user_type' => $userType,
                            'user_id' => $user->id,
                            'error' => $e->getMessage(),
                        ]
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | iOS FCM - CUSTOMER ONLY
            |--------------------------------------------------------------------------
            */

            if (
                $userType === 'customer' &&
                !empty($user->ios_firebase_token)
            ) {

                $firebaseData = [
                    'title' => $title,
                    'body' => $message,
                    'message' => $message,
                    'devicetoken' => $user->ios_firebase_token,
                    'user_id' => $user->id,
                    'user_type' => $userType,
                    'entity_id' => $entityId,
                ];

                try {

                    (new FireBaseController())
                        ->sendUpdatedFCMNotification(
                            $firebaseData
                        );

                } catch (\Throwable $e) {

                    Log::error(
                        'iOS FCM notification failed',
                        [
                            'user_type' => $userType,
                            'user_id' => $user->id,
                            'error' => $e->getMessage(),
                        ]
                    );
                }
            }


            return $notification;

        } catch (\Throwable $e) {

            Log::error(
                'Notification failed',
                [
                    'user_type' => $userType,
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]
            );

            return false;
        }
    }
}