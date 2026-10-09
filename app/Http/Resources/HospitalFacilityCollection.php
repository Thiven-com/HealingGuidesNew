<?php

namespace App\Http\Resources;

use App\Models\HospitalFacilitiesList;
use App\Models\HospitalGallery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HospitalFacilityCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($facility) {

            $facilityList = HospitalFacilitiesList::latest()->get();
            return [
                'id' => $facility->facility_id,

                'name' => $facility->facility->name ?? null,

                'image' => !empty($facility->facility->image)
                    ? asset($facility->facility->image)
                    : null,

                'description' => $facility->facility->description,
                'slug' => $facility->facility->slug,
                'short_description' => $facility->short_description ?? null,
                'hospital_facilities_list' => $facilityList->map(function ($item) {

                    $galleries = HospitalGallery::where(
                        'hospital_id',
                        $item->hospital_id
                    )
                        ->where(
                            'hospital_facility_list_id',
                            $item->id
                        )
                        ->latest()
                        ->get();

                    return [
                        'id' => $item->id,

                        'title' => $item->title,

                        'image' => $item->image
                            ? asset($item->image)
                            : null,

                        'description' => $item->description,
                        'actual_price' => $item->actual_price !== null
                            ? (float) $item->actual_price
                            : null,

                        'offer_price' => $item->offer_price !== null
                            ? (float) $item->offer_price
                            : null,
                        'gallery' => $galleries->map(function ($gallery) {
                            return [


                                'file_type' => $gallery->file_type,

                                'file_path' => $gallery->file_path
                                    ? asset($gallery->file_path)
                                    : null,
                            ];
                        })->values()->toArray(),

                        'created_at' => $item->created_at,

                        'updated_at' => $item->updated_at,
                    ];

                })->values()->toArray(),
            ];
        })->values()->toArray();
    }
}
