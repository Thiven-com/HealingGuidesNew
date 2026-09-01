<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicineOrder;
use App\Models\MedicineOrderItem;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $query = MedicineOrder::query();

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


        /*
        |--------------------------------------------------------------------------
        | Orders
        |--------------------------------------------------------------------------
        */

        $orders = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();


        /* |-------------------------------------------------------------------------- | Order Counts |-------------------------------------------------------------------------- */
        $pendingCount = MedicineOrder::where('order_status', 'pending')->count();
        $processingCount = MedicineOrder::where('order_status', 'processing')->count();
        $deliveryCount = MedicineOrder::where('order_status', 'delivered')->count();


        return view(
            'admin.medicine-orders.index',
            compact('orders', 'pendingCount', 'processingCount', 'deliveryCount')
        );
    }


    /**
     * Show Order
     */
    public function show($id)
    {
        $order = MedicineOrder::where('id', $id)->firstOrFail();
        $items = MedicineOrderItem::where('medicine_order_id', $order->id)->get();
        return view('admin.medicine-orders.show', compact('order', 'items'));
    }

    /** * Accept Order */
    public function accept(Request $request)
    {
        $order = MedicineOrder::where('id', $request->id)->firstOrFail();
        if ($order->order_status !== 'pending') {
            return back()->with('error', 'This order cannot be accepted.');
        }
        $order->update(['order_status' => 'accepted', 'accepted_at' => now(),]);
        return back()->with('success', 'Medicine order accepted successfully.');
    } /** * Reject Order */
    public function reject(Request $request)
    {
        $validated = $request->validate(['id' => ['required', 'integer',], 'cancel_reason' => ['required', 'string', 'max:1000',],]);
        $order = MedicineOrder::where('id', $validated['id'])->firstOrFail();
        if ($order->order_status !== 'pending') {
            return back()->with('error', 'This order cannot be rejected.');
        }
        $order->update(['order_status' => 'rejected', 'cancel_reason' => $validated['cancel_reason'], 'rejected_at' => now(),]);
        return back()->with('success', 'Medicine order rejected successfully.');
    }

    /** * Process Order */
    public function process(Request $request)
    {
        $order = MedicineOrder::where('id', $request->id)->firstOrFail();
        if ($order->order_status !== 'accepted') {
            return back()->with('error', 'Only accepted orders can be processed.');
        }
        $order->update(['order_status' => 'processing',]);
        return back()->with('success', 'Order moved to processing.');
    } /** * Mark Order Ready */
    public function ready(Request $request)
    {
        $order = MedicineOrder::where('id', $request->id)->firstOrFail();
        if ($order->order_status !== 'processing') {
            return back()->with('error', 'Only processing orders can be marked ready.');
        }
        $order->update(['order_status' => 'ready',]);
        return back()->with('success', 'Order marked as ready.');
    } /** * Dispatch Order */
    public function dispatch(Request $request)
    {
        $order = MedicineOrder::where('id', $request->id)->firstOrFail();
        if ($order->order_status !== 'ready') {
            return back()->with('error', 'Only ready orders can be dispatched.');
        }
        $order->update(['order_status' => 'dispatched',]);
        return back()->with('success', 'Order dispatched successfully.');
    }
}