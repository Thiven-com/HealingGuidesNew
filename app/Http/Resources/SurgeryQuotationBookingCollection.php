<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SurgeryQuotationBookingCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($booking) {

            return [

                'id' =>
                    $booking->id,

                'booking_no' =>
                    $booking->booking_no,

                'customer_id' =>
                    $booking->customer_id,

                'family_member_id' =>
                    $booking->family_member_id,

                'surgery_quotation_id' =>
                    $booking->surgery_quotation_id,

                'hospital_id' =>
                    $booking->hospital_id,

                'surgery_id' =>
                    $booking->surgery_id,

                'amount' =>
                    (float) $booking->amount,

                'discount' =>
                    (float) $booking->discount,

                'tax' =>
                    (float) $booking->tax,

                'total_amount' =>
                    (float) $booking->total_amount,

                'payment_method' =>
                    $booking->payment_method,

                'payment_id' =>
                    $booking->payment_id,

                'transaction_id' =>
                    $booking->transaction_id,

                'payment_status' =>
                    $booking->payment_status,

                'booking_status' =>
                    $booking->booking_status,

                'booking_date' =>
                    $booking->booking_date,

                'booking_time' =>
                    $booking->booking_time,

                'notes' =>
                    $booking->notes,

                /*
                |--------------------------------------------------------------------------
                | Family Member
                |--------------------------------------------------------------------------
                */

                'family_member' => $booking->familyMember ? [

                    'id' =>
                        $booking->familyMember->id,

                    'name' =>
                        $booking->familyMember->name,

                    'mobile' =>
                        $booking->familyMember->mobile,

                    'relationship' =>
                        $booking->familyMember->relationship,

                    'gender' =>
                        $booking->familyMember->gender,

                    'dob' =>
                        $booking->familyMember->dob,

                    'age' =>
                        $booking->familyMember->age,

                    'blood_group' =>
                        $booking->familyMember->blood_group,

                    'photo' =>
                        $booking->familyMember->photo
                            ? asset(
                                $booking->familyMember->photo
                            )
                            : null,

                ] : null,

                /*
                |--------------------------------------------------------------------------
                | Surgery
                |--------------------------------------------------------------------------
                */

                'surgery' => $booking->surgery ? [

                    'id' =>
                        $booking->surgery->id,

                    'name' =>
                        $booking->surgery->name,

                    'slug' =>
                        $booking->surgery->slug,

                    'image' =>
                        $booking->surgery->image
                            ? asset(
                                $booking->surgery->image
                            )
                            : null,

                ] : null,

                /*
                |--------------------------------------------------------------------------
                | Hospital
                |--------------------------------------------------------------------------
                */

                'hospital' => $booking->quotation &&
                    $booking->quotation->hospital
                    ? [

                        'id' =>
                            $booking->quotation->hospital->id,

                        'hospital_name' =>
                            $booking->quotation
                                ->hospital
                                ->hospital_name,

                    ]
                    : null,

                /*
                |--------------------------------------------------------------------------
                | Quotation
                |--------------------------------------------------------------------------
                */

                'quotation' => $booking->quotation ? [

                    'id' =>
                        $booking->quotation->id,

                    'hospital_id' =>
                        $booking->quotation->hospital_id,

                    'hospital_name' =>
                        $booking->quotation->hospital_name,

                    'amount' =>
                        (float) $booking->quotation->amount,

                    'discount' =>
                        (float) $booking->quotation->discount,

                    'tax' =>
                        (float) $booking->quotation->tax,

                    'total_amount' =>
                        (float) $booking->quotation->total_amount,

                    'quotation_details' =>
                        $booking->quotation->quotation_details,

                    'included_services' =>
                        $booking->quotation->included_services,

                    'excluded_services' =>
                        $booking->quotation->excluded_services,

                    'valid_until' =>
                        $booking->quotation->valid_until,

                    'status' =>
                        $booking->quotation->status,

                ] : null,

                /*
                |--------------------------------------------------------------------------
                | Payment
                |--------------------------------------------------------------------------
                */

                'payment' => $booking->payment ? [

                    'id' =>
                        $booking->payment->id,

                    'payment_no' =>
                        $booking->payment->payment_no,

                    'amount' =>
                        (float) $booking->payment->amount,

                    'discount' =>
                        (float) $booking->payment->discount,

                    'tax' =>
                        (float) $booking->payment->tax,

                    'paid_amount' =>
                        (float) $booking->payment->paid_amount,

                    'balance_amount' =>
                        (float) $booking->payment->balance_amount,

                    'currency' =>
                        $booking->payment->currency,

                    'payment_gateway' =>
                        $booking->payment->payment_gateway,

                    'payment_method' =>
                        $booking->payment->payment_method,

                    'gateway_order_id' =>
                        $booking->payment->gateway_order_id,

                    'gateway_payment_id' =>
                        $booking->payment->gateway_payment_id,

                    'transaction_id' =>
                        $booking->payment->transaction_id,

                    'invoice_no' =>
                        $booking->payment->invoice_no,

                    'payment_status' =>
                        $booking->payment->payment_status,

                    'paid_at' =>
                        $booking->payment->paid_at,

                ] : null,

                'created_at' =>
                    $booking->created_at,

                'updated_at' =>
                    $booking->updated_at,

            ];

        })->toArray();
    }
}