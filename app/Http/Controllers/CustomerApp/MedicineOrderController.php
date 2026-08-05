<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\MedicineOrderCollection;
use App\Models\FamilyMember;
use App\Models\Medicine;
use App\Models\MedicineOrder;
use App\Models\MedicineOrderItem;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MedicineOrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Place Medicine Order
    |--------------------------------------------------------------------------
    */

    public function placeOrder(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make($request->all(), [

            'family_member_id' =>
                'nullable|exists:family_members,id',

            'hospital_id' =>
                'required|exists:hospitals,id',

            'prescription_id' =>
                'nullable|exists:prescriptions,id',

            'medicines' =>
                'required|array|min:1',

            'medicines.*.medicine_id' =>
                'required|exists:medicines,id',

            'medicines.*.quantity' =>
                'required|integer|min:1',

            'delivery_address' =>
                'required|string|max:1000',

            'delivery_city' =>
                'nullable|string|max:100',

            'delivery_state' =>
                'nullable|string|max:100',

            'delivery_pincode' =>
                'nullable|string|max:20',

            'delivery_latitude' =>
                'nullable|numeric|between:-90,90',

            'delivery_longitude' =>
                'nullable|numeric|between:-180,180',

            'notes' =>
                'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Family Member
        |--------------------------------------------------------------------------
        */

        $familyMember = FamilyMember::where(
            'id',
            $request->family_member_id
        )
            ->where(
                'customer_id',
                $customer->id
            )
            ->first();

        // if (!$familyMember) {

        //     return response()->json([
        //         'success' => 0,
        //         'message' => 'Family member not found.'
        //     ]);
        // }

        /*
        |--------------------------------------------------------------------------
        | Get Medicines
        |--------------------------------------------------------------------------
        */

        $medicineIds = collect($request->medicines)
            ->pluck('medicine_id')
            ->unique()
            ->values();

        $medicines = Medicine::whereIn('id', $medicineIds)->where('hospital_id', $request->hospital_id)->where('status', 1)->get()->keyBy('id');

        if ($medicines->count() !== $medicineIds->count()) {

            return response()->json([
                'success' => 0,
                'message' => 'One or more medicines are invalid or do not belong to the selected hospital.'
            ]);
        }

        if ($request->filled('prescription_id')) {

            $prescription = Prescription::where(
                'id',
                $request->prescription_id
            )
                ->where(
                    'customer_id',
                    $customer->id
                )
                ->where(
                    'family_member_id',
                    $familyMember->id
                )
                ->first();

            if (!$prescription) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Invalid prescription.'
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Prescription Check
        |--------------------------------------------------------------------------
        */

        // $prescriptionRequired = $medicines
        //     ->contains(function ($medicine) {
        //         return $medicine->prescription_required;
        //     });

        // if ($prescriptionRequired && !$request->filled('prescription_id')) {
        //     return response()->json([
        //         'success' => 0,
        //         'message' => 'Prescription is required for one or more medicines.'
        //     ]);
        // }

        DB::beginTransaction();

        try {

            $subtotal = 0;

            foreach ($request->medicines as $item) {

                $medicine = $medicines->get(
                    $item['medicine_id']
                );

                if ($medicine->stock_quantity < $item['quantity']) {

                    DB::rollBack();

                    return response()->json([
                        'success' => 0,
                        'message' =>
                            'Insufficient stock for ' .
                            $medicine->medicine_name
                    ]);
                }

                $subtotal +=
                    $medicine->selling_price *
                    $item['quantity'];
            }

            /*
            |--------------------------------------------------------------------------
            | Charges
            |--------------------------------------------------------------------------
            */

            $deliveryCharge = 0;

            $discount = 0;

            $tax = 0;

            $totalAmount =
                $subtotal +
                $deliveryCharge +
                $tax -
                $discount;

            /*
            |--------------------------------------------------------------------------
            | Order
            |--------------------------------------------------------------------------
            */

            $order = MedicineOrder::create([

                'order_no' =>
                    'MEDORD' .
                    now()->format('YmdHis') .
                    rand(100, 999),

                'customer_id' =>
                    $customer->id,

                'family_member_id' =>
                    $familyMember->id ?? null,

                'hospital_id' =>
                    $request->hospital_id,

                'prescription_id' =>
                    $request->prescription_id,

                'subtotal' =>
                    $subtotal,

                'delivery_charge' =>
                    $deliveryCharge,

                'discount' =>
                    $discount,

                'tax' =>
                    $tax,

                'total_amount' =>
                    $totalAmount,

                'delivery_address' =>
                    $request->delivery_address,

                'delivery_city' =>
                    $request->delivery_city,

                'delivery_state' =>
                    $request->delivery_state,

                'delivery_pincode' =>
                    $request->delivery_pincode,

                'delivery_latitude' =>
                    $request->delivery_latitude,

                'delivery_longitude' =>
                    $request->delivery_longitude,

                'payment_status' =>
                    'pending',

                'order_status' =>
                    'pending',

                'notes' =>
                    $request->notes,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Order Items
            |--------------------------------------------------------------------------
            */

            foreach ($request->medicines as $item) {

                $medicine = $medicines->get(
                    $item['medicine_id']
                );

                $quantity =
                    $item['quantity'];

                $price =
                    $medicine->selling_price;

                $total =
                    $price * $quantity;

                MedicineOrderItem::create([

                    'medicine_order_id' =>
                        $order->id,

                    'medicine_id' =>
                        $medicine->id,

                    'medicine_name' =>
                        $medicine->medicine_name,

                    'medicine_code' =>
                        $medicine->medicine_code,

                    'strength' =>
                        $medicine->strength,

                    'pack_size' =>
                        $medicine->pack_size,

                    'quantity' =>
                        $quantity,

                    'mrp' =>
                        $medicine->mrp,

                    'price' =>
                        $price,

                    'total' =>
                        $total,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Reduce Stock
                |--------------------------------------------------------------------------
                */

                $medicine->decrement(
                    'stock_quantity',
                    $quantity
                );
            }

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | Load Relations
            |--------------------------------------------------------------------------
            */

            // $order->load([
            //     'hospital',
            //     'familyMember',
            //     'items.medicine'
            // ]);

            return response()->json([
                'success' => 1,
                'message' => 'Medicine order placed successfully.',
                'data' => $order
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function myOrders(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $orders = MedicineOrder::with([
            'hospital',
            'familyMember',
            'items.medicine'
        ])
            ->where('customer_id', $customer->id);

        /*
        |--------------------------------------------------------------------------
        | Order ID
        |--------------------------------------------------------------------------
        */

        if ($request->filled('id')) {
            $orders->where('id', $request->id);
        }

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
        | Hospital
        |--------------------------------------------------------------------------
        */

        if ($request->filled('hospital_id')) {
            $orders->where(
                'hospital_id',
                $request->hospital_id
            );
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

        $orders = $orders
            ->latest()
            ->paginate(20);

        if ($orders->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'No Medicine Orders Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Medicine orders fetched successfully.',
            'data' => new MedicineOrderCollection($orders)
        ]);
    }

    public function orderDetails($id)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $order = MedicineOrder::with([
            'hospital',
            'familyMember',
            'prescription',
            'items.medicine'
        ])
            ->where('id', $id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => 0,
                'message' => 'Medicine order not found.'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Medicine order details fetched successfully.',
            'data' => new MedicineOrderCollection(
                collect([$order])
            )
        ]);
    }
    public function cancelOrder(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [

            'order_id' =>
                'required|exists:medicine_orders,id',

            'cancel_reason' =>
                'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $order = MedicineOrder::with('items.medicine')
            ->where('id', $request->order_id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => 0,
                'message' => 'Medicine order not found.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Already Cancelled
        |--------------------------------------------------------------------------
        */

        if ($order->order_status == 'cancelled') {
            return response()->json([
                'success' => 0,
                'message' => 'Medicine order is already cancelled.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Completed
        |--------------------------------------------------------------------------
        */

        if ($order->order_status == 'delivered') {
            return response()->json([
                'success' => 0,
                'message' => 'Delivered order cannot be cancelled.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Cancellation Allowed Status
        |--------------------------------------------------------------------------
        */

        if (
            !in_array($order->order_status, [
                'pending',
                'accepted'
            ])
        ) {

            return response()->json([
                'success' => 0,
                'message' => 'Medicine order cannot be cancelled at this stage.'
            ]);
        }

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Restore Medicine Stock
            |--------------------------------------------------------------------------
            */

            foreach ($order->items as $item) {

                if ($item->medicine) {

                    $item->medicine->increment(
                        'stock_quantity',
                        $item->quantity
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Cancel Order
            |--------------------------------------------------------------------------
            */

            $order->update([

                'order_status' => 'cancelled',

                'cancel_reason' =>
                    $request->cancel_reason,

                'cancelled_at' =>
                    now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => 1,
                'message' => 'Medicine order cancelled successfully.'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}