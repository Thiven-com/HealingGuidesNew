<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\MedicineOrder;
use App\Models\MedicineOrderItem;
use App\Models\Medicine;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MedicineOrderController extends Controller
{
    /**
     * Logged-in hospital
     */
    private function hospital()
    {
        return Auth::guard('hospital')->user();
    }


    /**
     * Order List
     */
    public function index(Request $request)
    {
        $hospital = $this->hospital();

        $query = MedicineOrder::where(
            'hospital_id',
            $hospital->id
        );


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'order_no',
                    'like',
                    "%{$search}%"
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Order Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('order_status')) {

            $query->where(
                'order_status',
                $request->order_status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Payment Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment_status')) {

            $query->where(
                'payment_status',
                $request->payment_status
            );
        }


        $orders = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();


        return view(
            'hospital.medicine-orders.index',
            compact('orders')
        );
    }


    /**
     * Show Order
     */
    public function show($id)
    {
        $hospital = $this->hospital();

        $order = MedicineOrder::where(
            'hospital_id',
            $hospital->id
        )
            ->where(
                'id',
                $id
            )
            ->firstOrFail();


        $items = MedicineOrderItem::where(
            'medicine_order_id',
            $order->id
        )->get();


        return view(
            'hospital.medicine-orders.show',
            compact(
                'order',
                'items'
            )
        );
    }


    /**
     * Accept Order
     */
    public function accept(Request $request)
    {
        $hospital = $this->hospital();

        $order = MedicineOrder::where(
            'hospital_id',
            $hospital->id
        )
            ->where(
                'id',
                $request->id
            )
            ->firstOrFail();


        if ($order->order_status !== 'pending') {

            return back()->with(
                'error',
                'This order cannot be accepted.'
            );
        }


        $order->update([

            'order_status' => 'accepted',

            'accepted_at' => now(),

        ]);

        try {
            if ($order->customer_id) {
                NotificationService::send(
                    'customer',
                    $order->customer_id,
                    'medicine_order',
                    'Medicine Order Accepted',
                    'Good news! Your medicine order ' . $order->order_no .
                    ' has been accepted by the hospital and will proceed for processing. ' .
                    'Please check the app for further order updates.',
                    'medicine_order',
                    $order->id,
                    'medicine_order_details',
                    [
                        'order_id' => $order->id,
                        'order_no' => $order->order_no,
                        'customer_id' => $order->customer_id,
                        'hospital_id' => $order->hospital_id,
                        'order_status' => $order->order_status,
                    ]
                );
            }
        } catch (\Throwable $e) {
            Log::error('Medicine order accepted notification failed: ' . $e->getMessage());
        }

        return back()->with(
            'success',
            'Medicine order accepted successfully.'
        );
    }


    /**
     * Reject Order
     */
    public function reject(Request $request)
    {
        $hospital = $this->hospital();


        $validated = $request->validate([

            'id' => [
                'required',
                'integer',
            ],

            'cancel_reason' => [
                'required',
                'string',
                'max:1000',
            ],

        ]);


        $order = MedicineOrder::where(
            'hospital_id',
            $hospital->id
        )
            ->where(
                'id',
                $validated['id']
            )
            ->firstOrFail();


        if ($order->order_status !== 'pending') {

            return back()->with(
                'error',
                'This order cannot be rejected.'
            );
        }


        $order->update([

            'order_status' => 'rejected',

            'cancel_reason' =>
                $validated['cancel_reason'],

            'rejected_at' => now(),

        ]);
        try {
            if ($order->customer_id) {
                NotificationService::send(
                    'customer',
                    $order->customer_id,
                    'medicine_order',
                    'Medicine Order Rejected',
                    'Your medicine order ' . $order->order_no .
                    ' has been rejected by the hospital. Reason: ' .
                    ($order->cancel_reason ?: 'No reason provided') .
                    '. Please contact the hospital or support team if you need assistance.',
                    'medicine_order_rejected',
                    $order->id,
                    'medicine_order_details',
                    [
                        'order_id' => $order->id,
                        'order_no' => $order->order_no,
                        'customer_id' => $order->customer_id,
                        'hospital_id' => $order->hospital_id,
                        'order_status' => $order->order_status,
                        'cancel_reason' => $order->cancel_reason,
                    ]
                );
            }
        } catch (\Throwable $e) {
            Log::error('Medicine order rejected notification failed: ' . $e->getMessage());
        }


        return back()->with(
            'success',
            'Medicine order rejected successfully.'
        );
    }


    /**
     * Process Order
     */
    public function process(Request $request)
    {
        $hospital = $this->hospital();


        $order = MedicineOrder::where(
            'hospital_id',
            $hospital->id
        )
            ->where(
                'id',
                $request->id
            )
            ->firstOrFail();


        if ($order->order_status !== 'accepted') {

            return back()->with(
                'error',
                'Only accepted orders can be processed.'
            );
        }


        $order->update([

            'order_status' => 'processing',

        ]);

        try {
            if ($order->customer_id) {
                NotificationService::send(
                    'customer',
                    $order->customer_id,
                    'medicine_order',
                    'Medicine Order Processing',
                    'Your medicine order ' . $order->order_no .
                    ' has been accepted and is now being prepared by the hospital. ' .
                    'We will notify you when your order is ready.',
                    'medicine_order_processing',
                    $order->id,
                    'medicine_order_details',
                    [
                        'order_id' => $order->id,
                        'order_no' => $order->order_no,
                        'customer_id' => $order->customer_id,
                        'hospital_id' => $order->hospital_id,
                        'order_status' => $order->order_status,
                    ]
                );
            }
        } catch (\Throwable $e) {
            Log::error('Medicine order processing notification failed: ' . $e->getMessage());
        }


        return back()->with(
            'success',
            'Order moved to processing.'
        );
    }


    /**
     * Mark Order Ready
     */
    public function ready(Request $request)
    {
        $hospital = $this->hospital();


        $order = MedicineOrder::where(
            'hospital_id',
            $hospital->id
        )
            ->where(
                'id',
                $request->id
            )
            ->firstOrFail();


        if ($order->order_status !== 'processing') {

            return back()->with(
                'error',
                'Only processing orders can be marked ready.'
            );
        }


        $order->update([

            'order_status' => 'ready',

        ]);

        try {
            if ($order->customer_id) {
                NotificationService::send(
                    'customer',
                    $order->customer_id,
                    'medicine_order',
                    'Medicine Order Ready',
                    'Your medicine order ' . $order->order_no .
                    ' is now ready. Please check your order details for pickup or delivery information.',
                    'medicine_order_ready',
                    $order->id,
                    'medicine_order_details',
                    [
                        'order_id' => $order->id,
                        'order_no' => $order->order_no,
                        'customer_id' => $order->customer_id,
                        'hospital_id' => $order->hospital_id,
                        'order_status' => $order->order_status,
                    ]
                );
            }
        } catch (\Throwable $e) {
            Log::error('Medicine order ready notification failed: ' . $e->getMessage());
        }


        return back()->with(
            'success',
            'Order marked as ready.'
        );
    }


    /**
     * Dispatch Order
     */
    public function dispatch(Request $request)
    {
        $hospital = $this->hospital();


        $order = MedicineOrder::where(
            'hospital_id',
            $hospital->id
        )
            ->where(
                'id',
                $request->id
            )
            ->firstOrFail();


        if ($order->order_status !== 'ready') {

            return back()->with(
                'error',
                'Only ready orders can be dispatched.'
            );
        }


        $order->update([

            'order_status' => 'dispatched',

        ]);
        try {
            if ($order->customer_id) {
                NotificationService::send(
                    'customer',
                    $order->customer_id,
                    'medicine_order',
                    'Medicine Order Dispatched',
                    'Your medicine order ' . $order->order_no .
                    ' has been dispatched and is on its way. Please check your order details for updates.',
                    'medicine_order_dispatched',
                    $order->id,
                    'medicine_order_details',
                    [
                        'order_id' => $order->id,
                        'order_no' => $order->order_no,
                        'customer_id' => $order->customer_id,
                        'hospital_id' => $order->hospital_id,
                        'order_status' => $order->order_status,
                    ]
                );
            }
        } catch (\Throwable $e) {
            Log::error('Medicine order dispatched notification failed: ' . $e->getMessage());
        }


        return back()->with(
            'success',
            'Order dispatched successfully.'
        );
    }


    /**
     * Deliver Order
     */
    public function deliver(Request $request)
    {
        $hospital = $this->hospital();


        $order = MedicineOrder::where(
            'hospital_id',
            $hospital->id
        )
            ->where(
                'id',
                $request->id
            )
            ->firstOrFail();


        if ($order->order_status !== 'dispatched') {

            return back()->with(
                'error',
                'Only dispatched orders can be delivered.'
            );
        }


        $order->update([

            'order_status' => 'delivered',

            'delivered_at' => now(),

        ]);
        try {
            if ($order->customer_id) {
                NotificationService::send(
                    'customer',
                    $order->customer_id,
                    'medicine_order',
                    'Medicine Order Delivered',
                    'Your medicine order ' . $order->order_no .
                    ' has been delivered successfully. Thank you for choosing our services.',
                    'medicine_order_delivered',
                    $order->id,
                    'medicine_order_details',
                    [
                        'order_id' => $order->id,
                        'order_no' => $order->order_no,
                        'customer_id' => $order->customer_id,
                        'hospital_id' => $order->hospital_id,
                        'order_status' => $order->order_status,
                    ]
                );
            }
        } catch (\Throwable $e) {
            Log::error('Medicine order delivered notification failed: ' . $e->getMessage());
        }


        return back()->with(
            'success',
            'Order marked as delivered.'
        );
    }
}