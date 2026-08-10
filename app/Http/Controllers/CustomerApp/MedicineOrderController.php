<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\MedicineOrderCollection;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\FamilyMember;
use App\Models\Medicine;
use App\Models\MedicineOrder;
use App\Models\MedicineOrderItem;
use App\Models\Prescription;
use App\Services\NotificationService;
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

            /*
            |--------------------------------------------------------------------------
            | Coupon
            |--------------------------------------------------------------------------
            */

            'coupon_code' =>
                'nullable|string|max:100',
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

        $familyMember = null;

        if ($request->filled('family_member_id')) {

            $familyMember = FamilyMember::where(
                'id',
                $request->family_member_id
            )
                ->where(
                    'customer_id',
                    $customer->id
                )
                ->first();

            if (!$familyMember) {

                return response()->json([
                    'success' => 0,
                    'message' => 'Family member not found.'
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Get Medicines
        |--------------------------------------------------------------------------
        */

        $medicineIds = collect($request->medicines)
            ->pluck('medicine_id')
            ->unique()
            ->values();

        $medicines = Medicine::whereIn(
            'id',
            $medicineIds
        )
            ->where(
                'hospital_id',
                $request->hospital_id
            )
            ->where(
                'status',
                1
            )
            ->get()
            ->keyBy('id');


        if (
            $medicines->count() !==
            $medicineIds->count()
        ) {

            return response()->json([
                'success' => 0,
                'message' =>
                    'One or more medicines are invalid or do not belong to the selected hospital.'
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Transaction
        |--------------------------------------------------------------------------
        */

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Calculate Subtotal
            |--------------------------------------------------------------------------
            */

            $subtotal = 0;


            foreach ($request->medicines as $item) {

                $medicine = $medicines->get(
                    $item['medicine_id']
                );


                if (!$medicine) {

                    DB::rollBack();

                    return response()->json([
                        'success' => 0,
                        'message' => 'Medicine not found.'
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Stock Check
                |--------------------------------------------------------------------------
                */

                if (
                    $medicine->stock_quantity <
                    $item['quantity']
                ) {

                    DB::rollBack();

                    return response()->json([
                        'success' => 0,
                        'message' =>
                            'Insufficient stock for ' .
                            $medicine->medicine_name
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Subtotal
                |--------------------------------------------------------------------------
                */

                $subtotal +=
                    (float) $medicine->selling_price *
                    (int) $item['quantity'];
            }


            /*
            |--------------------------------------------------------------------------
            | Charges
            |--------------------------------------------------------------------------
            */

            $deliveryCharge = 0;

            $tax = 0;

            $discount = 0;

            $coupon = null;


            /*
            |--------------------------------------------------------------------------
            | Apply Coupon
            |--------------------------------------------------------------------------
            */

            if ($request->filled('coupon_code')) {

                /*
                |--------------------------------------------------------------------------
                | Find Coupon
                |--------------------------------------------------------------------------
                */

                $coupon = Coupon::where(
                    'code',
                    $request->coupon_code
                )
                    ->where(
                        'status',
                        1
                    )
                    ->first();


                if (!$coupon) {

                    DB::rollBack();

                    return response()->json([
                        'success' => 0,
                        'message' => 'Invalid coupon code.'
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Coupon Start Date
                |--------------------------------------------------------------------------
                */

                if (
                    $coupon->starts_at &&
                    now()->lt($coupon->starts_at)
                ) {

                    DB::rollBack();

                    return response()->json([
                        'success' => 0,
                        'message' =>
                            'This coupon is not active yet.'
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Coupon Expiry
                |--------------------------------------------------------------------------
                */

                if (
                    $coupon->expires_at &&
                    now()->gt($coupon->expires_at)
                ) {

                    DB::rollBack();

                    return response()->json([
                        'success' => 0,
                        'message' =>
                            'This coupon has expired.'
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Hospital Check
                |--------------------------------------------------------------------------
                */

                if (
                    $coupon->hospital_id !== null &&
                    (int) $coupon->hospital_id !==
                    (int) $request->hospital_id
                ) {

                    DB::rollBack();

                    return response()->json([
                        'success' => 0,
                        'message' =>
                            'This coupon is not valid for this hospital.'
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Applicable To
                |--------------------------------------------------------------------------
                */

                if (
                    $coupon->applicable_to &&
                    $coupon->applicable_to !== 'all' &&
                    $coupon->applicable_to !== 'medicine'
                ) {

                    DB::rollBack();

                    return response()->json([
                        'success' => 0,
                        'message' =>
                            'This coupon cannot be used for medicine orders.'
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Usage Limit
                |--------------------------------------------------------------------------
                */

                if (
                    $coupon->usage_limit !== null &&
                    $coupon->used_count >=
                    $coupon->usage_limit
                ) {

                    DB::rollBack();

                    return response()->json([
                        'success' => 0,
                        'message' =>
                            'This coupon usage limit has been reached.'
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Customer Usage
                |--------------------------------------------------------------------------
                */

                $customerUsageCount =
                    CouponUsage::where(
                        'coupon_id',
                        $coupon->id
                    )
                        ->where(
                            'customer_id',
                            $customer->id
                        )
                        ->count();


                if (
                    $coupon->usage_per_customer !== null &&
                    $customerUsageCount >=
                    $coupon->usage_per_customer
                ) {

                    DB::rollBack();

                    return response()->json([
                        'success' => 0,
                        'message' =>
                            'You have already used this coupon.'
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | New Customer Check
                |--------------------------------------------------------------------------
                |
                | For medicine coupons:
                | New customer = no previous valid medicine order.
                |
                */

                if ($coupon->new_customer_only) {

                    $hasPreviousOrder =
                        MedicineOrder::where(
                            'customer_id',
                            $customer->id
                        )
                            ->whereNotIn(
                                'order_status',
                                [
                                    'cancelled',
                                    'rejected'
                                ]
                            )
                            ->exists();


                    if ($hasPreviousOrder) {

                        DB::rollBack();

                        return response()->json([
                            'success' => 0,
                            'message' =>
                                'This coupon is available only for new customers.'
                        ]);
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | First Appointment Only
                |--------------------------------------------------------------------------
                |
                | Not valid for medicine orders.
                |
                */

                if ($coupon->first_appointment_only) {

                    DB::rollBack();

                    return response()->json([
                        'success' => 0,
                        'message' =>
                            'This coupon is only available for appointments.'
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Free Appointment
                |--------------------------------------------------------------------------
                |
                | Not valid for medicine orders.
                |
                */

                if ($coupon->free_appointment) {

                    DB::rollBack();

                    return response()->json([
                        'success' => 0,
                        'message' =>
                            'This coupon is only available for appointments.'
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Minimum Order Amount
                |--------------------------------------------------------------------------
                */

                if (
                    $coupon->min_order_amount !== null &&
                    $subtotal <
                    (float) $coupon->min_order_amount
                ) {

                    DB::rollBack();

                    return response()->json([
                        'success' => 0,
                        'message' =>
                            'Minimum order amount is ₹' .
                            number_format(
                                $coupon->min_order_amount,
                                2
                            )
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Calculate Discount
                |--------------------------------------------------------------------------
                */

                if (
                    $coupon->discount_type ===
                    'percentage'
                ) {

                    $discount =
                        (
                            $subtotal *
                            (float) $coupon->discount_value
                        ) / 100;


                    /*
                    |--------------------------------------------------------------------------
                    | Max Discount
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $coupon->max_discount !== null &&
                        $discount >
                        (float) $coupon->max_discount
                    ) {

                        $discount =
                            (float) $coupon->max_discount;
                    }
                } elseif (
                    $coupon->discount_type ===
                    'fixed'
                ) {

                    $discount =
                        (float) $coupon->discount_value;
                } elseif (
                    $coupon->discount_type ===
                    'free'
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Free Medicine Order
                    |--------------------------------------------------------------------------
                    */

                    $discount = $subtotal;
                }


                /*
                |--------------------------------------------------------------------------
                | Discount Safety
                |--------------------------------------------------------------------------
                */

                if ($discount > $subtotal) {
                    $discount = $subtotal;
                }

                if ($discount < 0) {
                    $discount = 0;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Total Amount
            |--------------------------------------------------------------------------
            */

            $totalAmount =
                $subtotal +
                $deliveryCharge +
                $tax -
                $discount;


            if ($totalAmount < 0) {
                $totalAmount = 0;
            }


            /*
            |--------------------------------------------------------------------------
            | Payment Status
            |--------------------------------------------------------------------------
            */

            $paymentStatus =
                $totalAmount <= 0
                ? 'paid'
                : 'pending';


            /*
            |--------------------------------------------------------------------------
            | Create Medicine Order
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
                    $paymentStatus,

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
                    (int) $item['quantity'];

                $price =
                    (float) $medicine->selling_price;

                $itemTotal =
                    $price * $quantity;


                /*
                |--------------------------------------------------------------------------
                | Create Item
                |--------------------------------------------------------------------------
                */

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
                        $itemTotal,
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


            /*
            |--------------------------------------------------------------------------
            | Coupon Usage
            |--------------------------------------------------------------------------
            |
            | Since the medicine order has now been created,
            | record coupon usage.
            |
            */

            if ($coupon) {

                CouponUsage::create([

                    'coupon_id' =>
                        $coupon->id,

                    'customer_id' =>
                        $customer->id,

                    'hospital_id' =>
                        $request->hospital_id,

                    'appointment_id' =>
                        null,

                    'medicine_order_id' =>
                        $order->id,

                    'coupon_code' =>
                        $coupon->code,

                    'discount_amount' =>
                        $discount,

                    'used_at' =>
                        now(),
                ]);


                /*
                |--------------------------------------------------------------------------
                | Increase Used Count
                |--------------------------------------------------------------------------
                */

                $coupon->increment(
                    'used_count'
                );
            }

            NotificationService::send(
                'customer',
                $customer->id,
                'medicine_order_created',
                'Medicine Order Placed',
                'Your medicine order ' .
                $order->order_no .
                ' has been placed successfully.',
                'medicine_order',
                $order->id,
                'medicine_order_details',
                [
                    'medicine_order_id' => $order->id,

                    'order_no' => $order->order_no,

                    'hospital_id' => $order->hospital_id,

                    'prescription_id' => $order->prescription_id,

                    'subtotal' => $order->subtotal,

                    'delivery_charge' => $order->delivery_charge,

                    'discount' => $order->discount,

                    'tax' => $order->tax,

                    'total_amount' => $order->total_amount,

                    'payment_method' => $order->payment_method,

                    'payment_status' => $order->payment_status,

                    'order_status' => $order->order_status,

                    'delivery_address' => $order->delivery_address,

                    'delivery_city' => $order->delivery_city,

                    'delivery_state' => $order->delivery_state,

                    'delivery_pincode' => $order->delivery_pincode,
                ]
            );


            /*
            |--------------------------------------------------------------------------
            | Commit
            |--------------------------------------------------------------------------
            */

            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | Response
            |--------------------------------------------------------------------------
            */

            return response()->json([

                'success' => 1,

                'message' =>
                    'Medicine order placed successfully.',

                'data' => [

                    'order' =>
                        $order,

                    'coupon' => $coupon
                        ? [
                            'id' =>
                                $coupon->id,

                            'code' =>
                                $coupon->code,

                            'title' =>
                                $coupon->title,

                            'discount' =>
                                round(
                                    $discount,
                                    2
                                ),
                        ]
                        : null,

                    'subtotal' =>
                        round(
                            $subtotal,
                            2
                        ),

                    'delivery_charge' =>
                        round(
                            $deliveryCharge,
                            2
                        ),

                    'tax' =>
                        round(
                            $tax,
                            2
                        ),

                    'discount' =>
                        round(
                            $discount,
                            2
                        ),

                    'total_amount' =>
                        round(
                            $totalAmount,
                            2
                        ),

                    'payment_required' =>
                        $totalAmount > 0,
                ]
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function oldplaceOrder(Request $request)
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

            // 'prescription_id' =>
            //     'nullable|exists:prescriptions,id',

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

        // if ($request->filled('prescription_id')) {

        //     $prescription = Prescription::where(
        //         'id',
        //         $request->prescription_id
        //     )
        //         ->where(
        //             'customer_id',
        //             $customer->id
        //         )
        //         ->where(
        //             'family_member_id',
        //             $familyMember->id
        //         )
        //         ->first();

        //     if (!$prescription) {
        //         return response()->json([
        //             'success' => 0,
        //             'message' => 'Invalid prescription.'
        //         ]);
        //     }
        // }

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

    public function payMedicineOrder(Request $request)
    {
        $customer = auth('sanctum')->user();

        /*
        |--------------------------------------------------------------------------
        | Authentication
        |--------------------------------------------------------------------------
        */

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

            'order_id' => 'required|exists:medicine_orders,id',

            'payment_method' => 'required|in:cash,razorpay,stripe',

            'transaction_id' => 'nullable|string|max:255',

            'payment_id' => 'nullable|string|max:255',

        ]);


        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Find Order
        |--------------------------------------------------------------------------
        */

        $order = MedicineOrder::where('id', $request->order_id)
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
        | Already Paid
        |--------------------------------------------------------------------------
        */

        if ($order->payment_status === 'paid') {

            return response()->json([
                'success' => 0,
                'message' => 'Medicine order payment already completed.'
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Cancelled Order
        |--------------------------------------------------------------------------
        */

        if ($order->order_status === 'cancelled') {

            return response()->json([
                'success' => 0,
                'message' => 'Cancelled medicine order cannot be paid.'
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Delivered Order
        |--------------------------------------------------------------------------
        */

        if ($order->order_status === 'delivered') {

            return response()->json([
                'success' => 0,
                'message' => 'Delivered medicine order cannot be paid.'
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Payment Verification
        |--------------------------------------------------------------------------
        |
        | Razorpay / Stripe verification should be implemented here.
        |
        */

        $paymentVerified = true;


        if (!$paymentVerified) {

            return response()->json([
                'success' => 0,
                'message' => 'Payment verification failed.'
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Update Order
        |--------------------------------------------------------------------------
        */

        DB::beginTransaction();

        try {

            $order->payment_method = $request->payment_method;

            $order->transaction_id = $request->transaction_id;

            $order->payment_id = $request->payment_id;

            $order->payment_status = 'paid';


            /*
            |--------------------------------------------------------------------------
            | Order Status
            |--------------------------------------------------------------------------
            */

            $order->order_status = 'pending';


            $order->save();


            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | Success Response
            |--------------------------------------------------------------------------
            */

            return response()->json([

                'success' => 1,

                'message' => 'Medicine order payment completed successfully.',

                'data' => [

                    'order_id' => $order->id,

                    'order_no' => $order->order_no,

                    'customer_id' => $order->customer_id,

                    'total_amount' => $order->total_amount,

                    'payment_method' => $order->payment_method,

                    'transaction_id' => $order->transaction_id,

                    'payment_id' => $order->payment_id,

                    'payment_status' => $order->payment_status,

                    'order_status' => $order->order_status,

                ]

            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            \Log::error('Medicine order payment failed', [

                'order_id' => $order->id ?? null,

                'customer_id' => $customer->id,

                'error' => $e->getMessage(),

            ]);


            return response()->json([

                'success' => 0,

                'message' => 'Something went wrong while completing the payment.'

            ], 500);
        }
    }
}