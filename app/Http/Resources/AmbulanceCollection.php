<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AmbulanceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($ambulance) {

            return [

                'id' => $ambulance->id,

                'ambulance_name' => $ambulance->ambulance_name,

                'ambulance_code' => $ambulance->ambulance_code,

                'vehicle_number' => $ambulance->vehicle_number,
                'image' => $ambulance->image,

                'registration_number' => $ambulance->registration_number,

                'ambulance_type_id' => $ambulance->ambulance_type_id,

                'ambulance_type_name' => optional($ambulance->ambulanceType)->ambulance_type_name,

                'hospital_id' => $ambulance->hospital_id,

                'hospital_name' => optional($ambulance->hospital)->hospital_name,

                'driver_name' => $ambulance->driver_name,

                'driver_mobile' => $ambulance->driver_mobile,

                'driver_license_number' => $ambulance->driver_license_number,

                'driver_photo' => $ambulance->driver_photo
                    ? asset($ambulance->driver_photo)
                    : null,

                'model' => $ambulance->model,

                'manufacturing_year' => $ambulance->manufacturing_year,

                'current_location' => $ambulance->current_location,

                'latitude' => $ambulance->latitude,

                'longitude' => $ambulance->longitude,

                'base_fare' => $ambulance->base_fare,

                'price_per_km' => $ambulance->price_per_km,

                'is_available' => (bool) $ambulance->is_available,

                'status' => (bool) $ambulance->status,

            ];

        })->toArray();
    }
}