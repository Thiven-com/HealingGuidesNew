<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DiagnosticBookingCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($booking) {

            return [

                /*
                |--------------------------------------------------------------------------
                | Booking
                |--------------------------------------------------------------------------
                */

                'id' => $booking->id,

                'booking_no' => $booking->booking_no,

                'collection_type' => $booking->collection_type,

                'booking_date' => $booking->booking_date
                    ? $booking->booking_date->format('Y-m-d')
                    : null,

                'booking_time' => $booking->booking_time,

                /*
                |--------------------------------------------------------------------------
                | Amount
                |--------------------------------------------------------------------------
                */

                'subtotal' => $booking->subtotal,

                'home_collection_charge' =>
                    $booking->home_collection_charge,

                'discount' => $booking->discount,

                'tax' => $booking->tax,

                'total_amount' => $booking->total_amount,

                /*
                |--------------------------------------------------------------------------
                | Payment
                |--------------------------------------------------------------------------
                */

                'payment_method' => $booking->payment_method,

                'transaction_id' => $booking->transaction_id,

                'payment_id' => $booking->payment_id,

                'payment_status' => $booking->payment_status,

                /*
                |--------------------------------------------------------------------------
                | Booking Status
                |--------------------------------------------------------------------------
                */

                'booking_status' => $booking->booking_status,

                /*
                |--------------------------------------------------------------------------
                | Diagnostic Center
                |--------------------------------------------------------------------------
                */

                'diagnostic' => $booking->diagnostic ? [

                    'id' => $booking->diagnostic->id,

                    'diagnostic_name' =>
                        $booking->diagnostic->diagnostic_name ?? null,

                    'email' =>
                        $booking->diagnostic->email ?? null,

                    'mobile' =>
                        $booking->diagnostic->mobile ?? null,

                    'image' =>
                        $booking->diagnostic->image
                            ? asset($booking->diagnostic->image)
                            : null,

                    'address' =>
                        $booking->diagnostic->address ?? null,

                    'city' =>
                        $booking->diagnostic->city ?? null,

                    'state' =>
                        $booking->diagnostic->state ?? null,

                    'pincode' =>
                        $booking->diagnostic->pincode ?? null,

                    'home_collection' =>
                        (bool) ($booking->diagnostic->home_collection ?? false),

                    'home_collection_charge' =>
                        $booking->diagnostic->home_collection_charge ?? 0,

                ] : null,

                /*
                |--------------------------------------------------------------------------
                | Patient
                |--------------------------------------------------------------------------
                */

                'family_member' => $booking->familyMember ? [

                    'id' =>
                        $booking->familyMember->id,

                    'name' =>
                        $booking->familyMember->name ?? null,

                    'relation' =>
                        $booking->familyMember->relation ?? null,

                    'gender' =>
                        $booking->familyMember->gender ?? null,

                    'dob' =>
                        $booking->familyMember->dob ?? null,

                ] : null,

                /*
                |--------------------------------------------------------------------------
                | Lab Tests
                |--------------------------------------------------------------------------
                */

                'lab_tests' => $booking->items->map(function ($item) {

                    return [

                        'booking_item_id' =>
                            $item->id,

                        'lab_test_id' =>
                            $item->lab_test_id,

                        'price' =>
                            $item->price,

                        'test' => $item->labTest ? [

                            'id' =>
                                $item->labTest->id,

                            'test_name' =>
                                $item->labTest->test_name,

                            'test_code' =>
                                $item->labTest->test_code,

                            'slug' =>
                                $item->labTest->slug,

                            'image' =>
                                $item->labTest->image
                                    ? asset($item->labTest->image)
                                    : null,

                            'description' =>
                                $item->labTest->description,

                            'sample_type' =>
                                $item->labTest->sample_type,

                            'preparation' =>
                                $item->labTest->preparation,

                            'report_time' =>
                                $item->labTest->report_time,

                            'report_time_type' =>
                                $item->labTest->report_time_type,

                            'fasting_required' =>
                                (bool) $item->labTest->fasting_required,

                            'home_collection' =>
                                (bool) $item->labTest->home_collection,

                        ] : null,

                    ];

                })->values(),

                /*
                |--------------------------------------------------------------------------
                | Home Collection Address
                |--------------------------------------------------------------------------
                */

                'collection_address' =>
                    $booking->collection_type == 'home_collection'
                        ? [

                            'address' =>
                                $booking->address,

                            'city' =>
                                $booking->city,

                            'state' =>
                                $booking->state,

                            'pincode' =>
                                $booking->pincode,

                            'latitude' =>
                                $booking->latitude,

                            'longitude' =>
                                $booking->longitude,

                        ]
                        : null,

                /*
                |--------------------------------------------------------------------------
                | Other
                |--------------------------------------------------------------------------
                */

                'notes' =>
                    $booking->notes,

                'cancel_reason' =>
                    $booking->cancel_reason,

                'cancelled_at' =>
                    $booking->cancelled_at,

                'created_at' =>
                    $booking->created_at,

                'updated_at' =>
                    $booking->updated_at,

            ];

        })->values()->all();
    }
}