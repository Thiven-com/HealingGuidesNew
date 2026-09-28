<?php

namespace App\Http\Resources;

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
            $galleries = HospitalGallery::where(
                'facility_id',
                $facility->facility_id
            )
                ->where(
                    'hospital_id',
                    $facility->hospital_id
                )
                ->latest()
                ->get();
            return [
                'id' => $facility->facility_id,

                'name' => $facility->facility->name ?? null,

                'image' => !empty($facility->facility->image)
                    ? asset($facility->facility->image)
                    : null,

                'description' => $facility->facility->description,
                'slug' => $facility->facility->slug,
                'short_description' => $facility->short_description ?? null,
                'gallery' => $galleries->map(function ($gallery) {

                    return [

                        'id' => $gallery->id,

                        'hospital_id' => $gallery->hospital_id,

                        'facility_id' => $gallery->facility_id,

                        'file_type' => $gallery->file_type,

                        'file_path' => $gallery->file_path
                            ? asset($gallery->file_path)
                            : null,

                        'created_at' => $gallery->created_at,

                        'updated_at' => $gallery->updated_at,

                    ];

                })->values()->toArray(),
            ];
        })->values()->toArray();
    }
}
