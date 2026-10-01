<?php

namespace App\Http\Resources;

use App\Models\Diagnostic;
use App\Models\HospitalEmergencyConnect;
use App\Models\TieupsList;
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
                'banner' => $hospital->banner
                    ? array_map(fn($banner) => asset(trim($banner)), array_filter(explode(',', $hospital->banner)))
                    : [],
                'specializations' => $hospital->hospitalSpecializations->map(function ($item) {
                    if (!$item->specialization) {
                        return null;
                    }
                    return [
                        'id' => $item->specialization->id,
                        'name' => $item->specialization->specialization_name,
                        'category_id' => $item->specializationCategory
                            ? $item->specializationCategory->id
                            : null,

                        'category_name' => $item->specializationCategory
                            ? $item->specializationCategory->category_name
                            : null,
                        'icon' => $item->specialization->icon ? asset($item->specialization->icon) : null,
                        'image' => $item->specialization->image ? asset($item->specialization->image) : null,
                    ];
                })->values(),
                'facilities' => new HospitalFacilityCollection($hospital->facilities),
                'diagnostics' => new DiagnosticCollection(Diagnostic::where('hospital_id',$hospital->id)->get()),
                'tieups' => $hospital->hospitalTieups
                    ->map(function ($item) use ($hospital) {
                        if (!$item->tieup) {
                            return null;
                        }
                        // Hospital-specific tieup lists
                        $tieupsList = TieupsList::where('hospital_id', $hospital->id)
                            ->latest()
                            ->get();
                        return [
                            'id' => $item->tieup->id,
                            'name' => $item->tieup->name,
                            'slug' => $item->tieup->slug,
                            'image' => $item->tieup->image
                                ? asset($item->tieup->image)
                                : null,
                            'description' => $item->tieup->description ?? null,
                            // Hospital Tieup Lists
                            'tieups_list' => $tieupsList
                                ->map(function ($list) {
                                    return [
                                        'id' => $list->id,

                                        'health_insurance_providers_id' => $list->health_insurance_providers_id,

                                        'title' => $list->title,

                                        'image' => $list->image
                                            ? asset($list->image)
                                            : null,

                                        'description' => $list->description,

                                        'created_at' => $list->created_at,
                                    ];
                                })
                                ->values(),
                        ];
                    })->values(),
                    

                /*
            |--------------------------------------------------------------------------
            | Emergency Connect
            |--------------------------------------------------------------------------
            */
                'emergency_connect' => HospitalEmergencyConnect::where(
                    'hospital_id',
                    $hospital->id
                )
                    ->latest()
                    ->get()
                    ->groupBy('slug')
                    ->map(function ($items, $slug) {
                        return [
                            'slug' => $slug,

                            'name' => $items->first()->name,

                            'contacts' => $items->map(function ($emergencyConnect) {
                                return [
                                    'id' => $emergencyConnect->id,
                                    'hospital_id' => $emergencyConnect->hospital_id,
                                    'slug' => $emergencyConnect->slug,
                                    'name' => $emergencyConnect->name,

                                    'image' => $emergencyConnect->image
                                        ? asset($emergencyConnect->image)
                                        : null,

                                    'designation' => $emergencyConnect->designation,
                                    'department' => $emergencyConnect->department,

                                    'whatsapp_number' =>
                                        $emergencyConnect->whatsapp_number,

                                    'contact_number' =>
                                        $emergencyConnect->contact_number,

                                    'created_at' => $emergencyConnect->created_at,
                                    'updated_at' => $emergencyConnect->updated_at,
                                ];
                            })->values(),
                        ];
                    })
                    ->values(),
                'rating' => rand(35, 50) / 10,
                'rating_count' => rand(60, 100),
                'distance' => number_format(rand(5, 100) / 10, 1) . ' KM',

            ];
        })->toArray();
    }
}
