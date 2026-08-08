<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AmbulanceBookingCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'bookings' => $this->collection->map(function ($booking) {

                return [

                    'id' => $booking->id,

                    'booking_no' => $booking->booking_no,

                    /*
                    |--------------------------------------------------------------------------
                    | Family Member / Patient
                    |--------------------------------------------------------------------------
                    */

                    'family_member' => $booking->familyMember ? [

                        'id' => $booking->familyMember->id,

                        'name' => $booking->familyMember->name ?? null,
                        'mobile' => $booking->familyMember->mobile ?? null,

                        'relation' => $booking->familyMember->relation ?? null,

                        'gender' => $booking->familyMember->gender ?? null,

                        'dob' => $booking->familyMember->dob ?? null,
                        'age' => $booking->familyMember->dob
                            ? Carbon::parse($booking->familyMember->dob)->age
                            : null,

                        'blood_group' => $booking->familyMember->blood_group ?? null,

                    ] : null,

                    /*
                    |--------------------------------------------------------------------------
                    | Hospital
                    |--------------------------------------------------------------------------
                    */

                    'hospital' => $booking->hospital ? [

                        'id' => $booking->hospital->id,

                        'hospital_name' =>
                            $booking->hospital->hospital_name ?? null,

                        'mobile' =>
                            $booking->hospital->mobile ?? null,

                        'email' =>
                            $booking->hospital->email ?? null,

                        'address' =>
                            $booking->hospital->address ?? null,

                        'city' =>
                            $booking->hospital->city ?? null,

                        'state' =>
                            $booking->hospital->state ?? null,

                        'pincode' =>
                            $booking->hospital->pincode ?? null,

                        'latitude' =>
                            $booking->hospital->latitude ?? null,

                        'longitude' =>
                            $booking->hospital->longitude ?? null,

                    ] : null,

                    /*
                    |--------------------------------------------------------------------------
                    | Ambulance Type
                    |--------------------------------------------------------------------------
                    */

                    'ambulance_type' => $booking->ambulanceType ? [

                        'id' => $booking->ambulanceType->id,

                        'type_name' =>
                            $booking->ambulanceType->ambulance_type_name ?? null,

                        'image' =>
                            $booking->ambulanceType->image ?? null,

                        'description' =>
                            $booking->ambulanceType->description ?? null,

                    ] : null,

                    /*
                    |--------------------------------------------------------------------------
                    | Assigned Ambulance
                    |--------------------------------------------------------------------------
                    */

                    'ambulance' => $booking->ambulance ? [

                        'id' => $booking->ambulance->id,

                        'ambulance_name' =>
                            $booking->ambulance->ambulance_name,

                        'ambulance_code' =>
                            $booking->ambulance->ambulance_code,

                        'image' =>
                            $booking->ambulance->image,

                        'vehicle_number' =>
                            $booking->ambulance->vehicle_number,

                        'registration_number' =>
                            $booking->ambulance->registration_number,

                        /*
                        |--------------------------------------------------------------------------
                        | Driver
                        |--------------------------------------------------------------------------
                        */

                        'driver_name' =>
                            $booking->ambulance->driver_name,

                        'driver_mobile' =>
                            $booking->ambulance->driver_mobile,

                        'driver_photo' =>
                            $booking->ambulance->driver_photo,

                        /*
                        |--------------------------------------------------------------------------
                        | Vehicle
                        |--------------------------------------------------------------------------
                        */

                        'model' =>
                            $booking->ambulance->model,

                        'manufacturing_year' =>
                            $booking->ambulance->manufacturing_year,

                        /*
                        |--------------------------------------------------------------------------
                        | Current Location
                        |--------------------------------------------------------------------------
                        */

                        'current_location' =>
                            $booking->ambulance->current_location,

                        'latitude' =>
                            $booking->ambulance->latitude,

                        'longitude' =>
                            $booking->ambulance->longitude,

                    ] : null,

                    /*
                    |--------------------------------------------------------------------------
                    | Pickup
                    |--------------------------------------------------------------------------
                    */

                    'pickup' => [

                        'address' =>
                            $booking->pickup_address,

                        'city' =>
                            $booking->pickup_city,

                        'state' =>
                            $booking->pickup_state,

                        'pincode' =>
                            $booking->pickup_pincode,

                        'latitude' =>
                            $booking->pickup_latitude,

                        'longitude' =>
                            $booking->pickup_longitude,
                        'pickup_code' =>
                            $booking->pickup_code,

                    ],

                    /*
                    |--------------------------------------------------------------------------
                    | Destination
                    |--------------------------------------------------------------------------
                    */

                    'destination' => [

                        'address' =>
                            $booking->destination_address,

                        'city' =>
                            $booking->destination_city,

                        'state' =>
                            $booking->destination_state,

                        'pincode' =>
                            $booking->destination_pincode,

                        'latitude' =>
                            $booking->destination_latitude,

                        'longitude' =>
                            $booking->destination_longitude,

                    ],

                    /*
                    |--------------------------------------------------------------------------
                    | Emergency
                    |--------------------------------------------------------------------------
                    */

                    'is_emergency' =>
                        (bool) $booking->is_emergency,

                    'emergency_notes' =>
                        $booking->emergency_notes,

                    /*
                    |--------------------------------------------------------------------------
                    | Distance
                    |--------------------------------------------------------------------------
                    */

                    'distance_km' =>
                        $booking->distance_km,

                    /*
                    |--------------------------------------------------------------------------
                    | Fare
                    |--------------------------------------------------------------------------
                    */

                    'base_amount' =>
                        $booking->base_amount,

                    'price_per_km' =>
                        $booking->price_per_km,

                    'distance_amount' =>
                        $booking->distance_amount,

                    'extra_charge' =>
                        $booking->extra_charge,

                    'discount' =>
                        $booking->discount,

                    'tax' =>
                        $booking->tax,

                    'total_amount' =>
                        $booking->total_amount,

                    /*
                    |--------------------------------------------------------------------------
                    | Payment
                    |--------------------------------------------------------------------------
                    */

                    'payment_method' =>
                        $booking->payment_method,

                    'transaction_id' =>
                        $booking->transaction_id,

                    'payment_id' =>
                        $booking->payment_id,

                    'payment_status' =>
                        $booking->payment_status,

                    /*
                    |--------------------------------------------------------------------------
                    | Booking Status
                    |--------------------------------------------------------------------------
                    */

                    'booking_status' =>
                        $booking->booking_status,

                    'notes' =>
                        $booking->notes,

                    'cancel_reason' =>
                        $booking->cancel_reason,

                    /*
                    |--------------------------------------------------------------------------
                    | Status Dates
                    |--------------------------------------------------------------------------
                    */

                    'accepted_at' =>
                        $booking->accepted_at,

                    'assigned_at' =>
                        $booking->assigned_at,

                    'cancelled_at' =>
                        $booking->cancelled_at,

                    'completed_at' =>
                        $booking->completed_at,

                    'created_at' =>
                        $booking->created_at,

                    'updated_at' =>
                        $booking->updated_at,
                ];
            }),
        ];
    }
}