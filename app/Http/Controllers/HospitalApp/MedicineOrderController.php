<?php

namespace App\Http\Controllers\HospitalApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\MedicineOrderCollection;
use App\Models\Medicine;
use App\Models\MedicineOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MedicineOrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Medicine Orders
    |--------------------------------------------------------------------------
    */

    public function orders(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $orders = MedicineOrder::with([
            'customer',
            'familyMember',
            'hospital',
            'prescription',
            'items.medicine'
        ])
            ->where('hospital_id', $hospital->id);

        /*
        |--------------------------------------------------------------------------
        | Order Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('order_status')) {
            $orders->where(
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
            $orders->where(
                'payment_status',
                $request->payment_status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Customer
        |--------------------------------------------------------------------------
        */

        if ($request->filled('customer_id')) {
            $orders->where(
                'customer_id',
                $request->customer_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Family Member
        |--------------------------------------------------------------------------
        */

        if ($request->filled('family_member_id')) {
            $orders->where(
                'family_member_id',
                $request->family_member_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $orders->where(function ($query) use ($search) {

                $query->where(
                    'order_no',
                    'LIKE',
                    '%' . $search . '%'
                )
                    ->orWhereHas(
                        'customer',
                        function ($customerQuery) use ($search) {

                            $customerQuery
                                ->where(
                                    'name',
                                    'LIKE',
                                    '%' . $search . '%'
                                )
                                ->orWhere(
                                    'mobile',
                                    'LIKE',
                                    '%' . $search . '%'
                                );
                        }
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date')) {
            $orders->whereDate(
                'created_at',
                $request->date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $orders = $orders
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => 1,
            'message' => 'Medicine Orders Fetched Successfully',
            'data' => new MedicineOrderCollection($orders)
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Medicine Order Details
    |--------------------------------------------------------------------------
    */

    public function orderDetails($id)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $order = MedicineOrder::with([
            'customer',
            'familyMember',
            'hospital',
            'prescription',
            'items.medicine'
        ])
            ->where('id', $id)
            ->where('hospital_id', $hospital->id)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => 0,
                'message' => 'Medicine Order Not Found'
            ]);
        }

        return $this->orderResponse(
            $order,
            'Medicine Order Details Fetched Successfully'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Accept Medicine Order
    |--------------------------------------------------------------------------
    */
    public function acceptOrder(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        try {

            DB::beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | Get Order
            |--------------------------------------------------------------------------
            */

            $order = MedicineOrder::with('items')
                ->where('id', $request->order_id)
                ->where('hospital_id', $hospital->id)
                ->lockForUpdate()
                ->first();

            if (!$order) {

                DB::rollBack();

                return response()->json([
                    'success' => 0,
                    'message' => 'Medicine Order Not Found'
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | Check Status
            |--------------------------------------------------------------------------
            */

            if ($order->order_status !== 'pending') {

                DB::rollBack();

                return response()->json([
                    'success' => 0,
                    'message' => 'Only Pending Orders Can Be Accepted'
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Check Order Items
            |--------------------------------------------------------------------------
            */

            if ($order->items->isEmpty()) {

                DB::rollBack();

                return response()->json([
                    'success' => 0,
                    'message' => 'No Medicines Found In This Order'
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Check Stock
            |--------------------------------------------------------------------------
            */

            foreach ($order->items as $item) {

                $medicine = Medicine::where('id', $item->medicine_id)
                    ->where('hospital_id', $hospital->id)
                    ->where('status', 1)
                    ->lockForUpdate()
                    ->first();

                if (!$medicine) {

                    DB::rollBack();

                    return response()->json([
                        'success' => 0,
                        'message' =>
                            $item->medicine_name .
                            ' Is Not Available'
                    ]);
                }

                if ($medicine->stock_quantity < $item->quantity) {

                    DB::rollBack();

                    return response()->json([
                        'success' => 0,
                        'message' =>
                            'Insufficient Stock For ' .
                            $item->medicine_name,

                        'data' => [
                            'medicine_id' =>
                                $medicine->id,

                            'medicine_name' =>
                                $medicine->medicine_name,

                            'required_quantity' =>
                                $item->quantity,

                            'available_quantity' =>
                                $medicine->stock_quantity,
                        ]
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Deduct Stock
            |--------------------------------------------------------------------------
            */

            foreach ($order->items as $item) {

                $medicine = Medicine::where('id', $item->medicine_id)
                    ->where('hospital_id', $hospital->id)
                    ->lockForUpdate()
                    ->first();

                $medicine->stock_quantity =
                    $medicine->stock_quantity -
                    $item->quantity;

                $medicine->save();
            }

            /*
            |--------------------------------------------------------------------------
            | Accept Order
            |--------------------------------------------------------------------------
            */

            $order->order_status = 'accepted';

            $order->accepted_at = now();

            $order->save();

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | Response
            |--------------------------------------------------------------------------
            */
            return response()->json([
                'success' => 1,
                'message' => 'Medicine Order Accepted Successfully',
                'data' => new MedicineOrderCollection(
                    collect([$order])
                )
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => 0,
                'message' => 'Something Went Wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Reject Medicine Order
    |--------------------------------------------------------------------------
    */

    public function rejectOrder(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [

            'order_id' =>
                'required|integer',

            'reason' =>
                'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        $order = MedicineOrder::where(
            'id',
            $request->order_id
        )
            ->where(
                'hospital_id',
                $hospital->id
            )
            ->first();

        if (!$order) {
            return response()->json([
                'success' => 0,
                'message' => 'Medicine Order Not Found'
            ]);
        }

        if ($order->order_status !== 'pending') {
            return response()->json([
                'success' => 0,
                'message' => 'Only Pending Orders Can Be Rejected'
            ]);
        }

        $order->order_status = 'rejected';

        $order->cancel_reason =
            $request->reason;

        $order->rejected_at = now();

        $order->save();

        return $this->orderResponse(
            $order,
            'Medicine Order Rejected Successfully'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Start Processing
    |--------------------------------------------------------------------------
    */

    public function processingOrder(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        $order = MedicineOrder::where(
            'id',
            $request->order_id
        )
            ->where(
                'hospital_id',
                $hospital->id
            )
            ->first();

        if (!$order) {
            return response()->json([
                'success' => 0,
                'message' => 'Medicine Order Not Found'
            ]);
        }

        if ($order->order_status !== 'accepted') {
            return response()->json([
                'success' => 0,
                'message' => 'Order Must Be Accepted First'
            ]);
        }

        $order->order_status = 'processing';

        $order->save();

        return $this->orderResponse(
            $order,
            'Medicine Order Processing Started'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Mark Order Ready
    |--------------------------------------------------------------------------
    */

    public function readyOrder(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        $order = MedicineOrder::where(
            'id',
            $request->order_id
        )
            ->where(
                'hospital_id',
                $hospital->id
            )
            ->first();

        if (!$order) {
            return response()->json([
                'success' => 0,
                'message' => 'Medicine Order Not Found'
            ]);
        }

        if ($order->order_status !== 'processing') {
            return response()->json([
                'success' => 0,
                'message' => 'Order Must Be Processing First'
            ]);
        }

        $order->order_status = 'ready';

        $order->save();

        return $this->orderResponse(
            $order,
            'Medicine Order Is Ready'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Dispatch Medicine Order
    |--------------------------------------------------------------------------
    */

    public function dispatchOrder(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        $order = MedicineOrder::where(
            'id',
            $request->order_id
        )
            ->where(
                'hospital_id',
                $hospital->id
            )
            ->first();

        if (!$order) {
            return response()->json([
                'success' => 0,
                'message' => 'Medicine Order Not Found'
            ]);
        }

        if ($order->order_status !== 'ready') {
            return response()->json([
                'success' => 0,
                'message' => 'Order Must Be Ready Before Dispatch'
            ]);
        }

        $order->order_status = 'dispatched';

        $order->save();

        return $this->orderResponse(
            $order,
            'Medicine Order Dispatched Successfully'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Deliver Medicine Order
    |--------------------------------------------------------------------------
    */

    public function deliverOrder(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        $order = MedicineOrder::where(
            'id',
            $request->order_id
        )
            ->where(
                'hospital_id',
                $hospital->id
            )
            ->first();

        if (!$order) {
            return response()->json([
                'success' => 0,
                'message' => 'Medicine Order Not Found'
            ]);
        }

        if ($order->order_status !== 'dispatched') {
            return response()->json([
                'success' => 0,
                'message' => 'Order Must Be Dispatched First'
            ]);
        }

        $order->order_status = 'delivered';

        $order->delivered_at = now();

        $order->save();

        return $this->orderResponse(
            $order,
            'Medicine Order Delivered Successfully'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Common Response
    |--------------------------------------------------------------------------
    */

    private function orderResponse(
        MedicineOrder $order,
        string $message
    ) {

        $order->load([
            'customer',
            'familyMember',
            'hospital',
            'prescription',
            'items.medicine'
        ]);

        return response()->json([
            'success' => 1,
            'message' => $message,

            'data' => new MedicineOrderCollection(
                collect([$order])
            )
        ]);
    }
}