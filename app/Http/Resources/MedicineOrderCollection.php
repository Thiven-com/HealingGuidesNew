<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MedicineOrderCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [

            'orders' => $this->collection->map(function ($order) {

                return [

                    'id' => $order->id,

                    'order_no' => $order->order_no,

                    /*
                    |--------------------------------------------------------------------------
                    | Hospital
                    |--------------------------------------------------------------------------
                    */

                    'hospital' => $order->hospital ? [

                        'id' =>
                            $order->hospital->id,

                        'hospital_name' =>
                            $order->hospital->hospital_name,

                        'logo' =>
                            $order->hospital->logo,

                        'mobile' =>
                            $order->hospital->mobile,

                        'address' =>
                            $order->hospital->address,

                        'city' =>
                            $order->hospital->city,

                        'state' =>
                            $order->hospital->state,

                        'pincode' =>
                            $order->hospital->pincode,

                    ] : null,

                    /*
                    |--------------------------------------------------------------------------
                    | Family Member
                    |--------------------------------------------------------------------------
                    */

                    'family_member' => $order->familyMember ? [

                        'id' =>
                            $order->familyMember->id,

                        'name' =>
                            $order->familyMember->name,
                        'mobile' =>
                            $order->familyMember->mobile,

                        'relation' =>
                            $order->familyMember->relation,

                    ] : null,

                    /*
                    |--------------------------------------------------------------------------
                    | Prescription
                    |--------------------------------------------------------------------------
                    */

                    'prescription_id' =>
                        $order->prescription_id,

                    /*
                    |--------------------------------------------------------------------------
                    | Items
                    |--------------------------------------------------------------------------
                    */

                    'items' => $order->items->map(function ($item) {

                        return [

                            'id' =>
                                $item->id,

                            'medicine_id' =>
                                $item->medicine_id,

                            'medicine_name' =>
                                $item->medicine_name,

                            'medicine_code' =>
                                $item->medicine_code,

                            'image' =>
                                $item->medicine?->image,

                            'strength' =>
                                $item->strength,

                            'pack_size' =>
                                $item->pack_size,

                            'quantity' =>
                                $item->quantity,

                            'mrp' =>
                                $item->mrp,

                            'price' =>
                                $item->price,

                            'total' =>
                                $item->total,
                        ];
                    }),

                    /*
                    |--------------------------------------------------------------------------
                    | Amount
                    |--------------------------------------------------------------------------
                    */

                    'subtotal' =>
                        $order->subtotal,

                    'delivery_charge' =>
                        $order->delivery_charge,

                    'discount' =>
                        $order->discount,

                    'tax' =>
                        $order->tax,

                    'total_amount' =>
                        $order->total_amount,

                    /*
                    |--------------------------------------------------------------------------
                    | Delivery Address
                    |--------------------------------------------------------------------------
                    */

                    'delivery_address' =>
                        $order->delivery_address,

                    'delivery_city' =>
                        $order->delivery_city,

                    'delivery_state' =>
                        $order->delivery_state,

                    'delivery_pincode' =>
                        $order->delivery_pincode,

                    'delivery_latitude' =>
                        $order->delivery_latitude,

                    'delivery_longitude' =>
                        $order->delivery_longitude,

                    /*
                    |--------------------------------------------------------------------------
                    | Payment
                    |--------------------------------------------------------------------------
                    */

                    'payment_method' =>
                        $order->payment_method,

                    'payment_status' =>
                        $order->payment_status,

                    'transaction_id' =>
                        $order->transaction_id,

                    'payment_id' =>
                        $order->payment_id,

                    /*
                    |--------------------------------------------------------------------------
                    | Status
                    |--------------------------------------------------------------------------
                    */

                    'order_status' =>
                        $order->order_status,

                    'notes' =>
                        $order->notes,

                    'cancel_reason' =>
                        $order->cancel_reason,

                    'accepted_at' =>
                        $order->accepted_at,

                    'cancelled_at' =>
                        $order->cancelled_at,

                    'delivered_at' =>
                        $order->delivered_at,

                    'created_at' =>
                        $order->created_at,
                ];
            }),
        ];
    }
}