<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AmbulanceTypeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($ambulanceType) {

            return [

                'id' => $ambulanceType->id,

                'ambulance_type_name' => $ambulanceType->ambulance_type_name,

                'ambulance_type_code' => $ambulanceType->ambulance_type_code,

                'slug' => $ambulanceType->slug,

                'description' => $ambulanceType->description,

                'image' => $ambulanceType->image ? asset($ambulanceType->image) : null,

                'status' => (bool) $ambulanceType->status,

            ];

        })->toArray();
    }
}