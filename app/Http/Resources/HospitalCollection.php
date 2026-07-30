<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HospitalCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($hospital) {
            return [
                'id' => $hospital->id,
                'hospital_name' => $hospital->hospital_name,
                'hospital_code' => $hospital->hospital_code,
                'hospital_type' => $hospital->hospital_type,
                'mobile' => $hospital->mobile,
                'email' => $hospital->email,
                'city' => $hospital->city,
                'state' => $hospital->state,
                'country' => $hospital->country,
                'address' => $hospital->address,
                'logo' => $hospital->logo ? asset($hospital->logo) : null,
                'banner' => $hospital->banner ? asset($hospital->banner) : null,
                'specializations' => $hospital->hospitalSpecializations->map(function ($item) {
                    return [
                        'id' => $item->specialization->id,
                        'name' => $item->specialization->specialization_name,
                        'icon' => $item->specialization->icon ? asset($item->specialization->icon) : null,
                        'image' => $item->specialization->image ? asset($item->specialization->image) : null,
                    ];
                })->values(),
                'rating' => rand(35, 50) / 10,
                'rating_count' => rand(60, 100),
                'distance' => number_format(rand(5, 100) / 10, 1) . ' KM',

            ];
        })->toArray();
    }
}
