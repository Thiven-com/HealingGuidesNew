<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DiagnosticLabTestsCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($item) {

            return [

                'id' => $item->id,

                'lab_test_id' => $item->labTest->id,

                'test_name' => $item->labTest->test_name,

                'test_code' => $item->labTest->test_code,

                'slug' => $item->labTest->slug,

                'image' => $item->labTest->image
                    ? asset($item->labTest->image)
                    : null,

                'description' => $item->labTest->description,

                'sample_type' => $item->labTest->sample_type,

                'preparation' => $item->labTest->preparation,

                // pricing from DiagnosticLabTest
                'price' => $item->price,

                'offer_price' => $item->offer_price,

                'report_time' => $item->report_time,

                'report_time_type' => $item->report_time_type,

                'home_collection' => (bool) $item->home_collection,

                'status' => $item->status,

            ];

        })->toArray();
    }
}
