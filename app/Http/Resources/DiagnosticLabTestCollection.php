<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DiagnosticLabTestCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($item) {

            return [

                'id' => $item->id,

                'diagnostic_id' => $item->diagnostic->id,

                'diagnostic_name' => $item->diagnostic->diagnostic_name,

                'logo' => $item->diagnostic->logo
                    ? asset($item->diagnostic->logo)
                    : null,

                'address' => $item->diagnostic->address,

                'city' => $item->diagnostic->city,

                'price' => $item->price,

                'offer_price' => $item->offer_price,

                'report_time' => $item->report_time,

                'report_time_type' => $item->report_time_type,

                'home_collection' => (bool) $item->home_collection,

                'status' => $item->status,
                'rating' => rand(35, 50) / 10,
                'rating_count' => rand(60, 100),
                'distance' => number_format(rand(5, 100) / 10, 1) . ' KM',

            ];

        })->toArray();
    }
}