<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DiagnosticCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($diagnostic) {

            return [

                'id' => $diagnostic->id,

                'diagnostic_name' => $diagnostic->diagnostic_name,

                'diagnostic_code' => $diagnostic->diagnostic_code,

                'slug' => $diagnostic->slug,

                'registration_number' => $diagnostic->registration_number,

                'email' => $diagnostic->email,

                'mobile' => $diagnostic->mobile,

                'phone' => $diagnostic->phone,

                'address' => $diagnostic->address,

                'country' => $diagnostic->country,

                'state' => $diagnostic->state,

                'city' => $diagnostic->city,

                'pincode' => $diagnostic->pincode,

                'latitude' => $diagnostic->latitude,

                'longitude' => $diagnostic->longitude,

                'opening_time' => $diagnostic->opening_time,

                'closing_time' => $diagnostic->closing_time,

                'home_collection' => (bool) $diagnostic->home_collection,

                'logo' => $diagnostic->logo
                    ? asset($diagnostic->logo)
                    : null,

                'banner' => $diagnostic->banner
                    ? asset($diagnostic->banner)
                    : null,

                'status' => $diagnostic->status,
                'rating' => rand(35, 50) / 10,
                'rating_count' => rand(60, 100),
                'distance' => number_format(rand(5, 100) / 10, 1) . ' KM',

            ];

        })->toArray();
    }
}