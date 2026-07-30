<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LabTestCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($labTest) {

            return [

                'id' => $labTest->id,

                'test_name' => $labTest->test_name,

                'test_code' => $labTest->test_code,

                'slug' => $labTest->slug,
                'image' => $labTest->image,

                'description' => $labTest->description,

                'sample_type' => $labTest->sample_type,

                'preparation' => $labTest->preparation,

                'report_time' => $labTest->report_time,

                'report_time_type' => $labTest->report_time_type,

                'fasting_required' => (bool) $labTest->fasting_required,

                'home_collection' => (bool) $labTest->home_collection,

                'status' => $labTest->status,

            ];

        })->toArray();
    }
}