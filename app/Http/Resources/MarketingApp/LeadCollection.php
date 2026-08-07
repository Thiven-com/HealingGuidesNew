<?php

namespace App\Http\Resources\MarketingApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LeadCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'leads' => $this->collection->map(function ($lead) {

                return [

                    /*
                    |--------------------------------------------------------------------------
                    | Basic Information
                    |--------------------------------------------------------------------------
                    */

                    'id' => $lead->id,

                    'marketing_staff_id' =>
                        $lead->marketing_staff_id,

                    'lead_type' =>
                        $lead->lead_type,

                    'name' =>
                        $lead->name,

                    'mobile' =>
                        $lead->mobile,

                    'alternate_mobile' =>
                        $lead->alternate_mobile,

                    'email' =>
                        $lead->email,


                    /*
                    |--------------------------------------------------------------------------
                    | Hospital Information
                    |--------------------------------------------------------------------------
                    */

                    'organization_name' =>
                        $lead->organization_name,

                    'contact_person' =>
                        $lead->contact_person,


                    /*
                    |--------------------------------------------------------------------------
                    | Doctor Information
                    |--------------------------------------------------------------------------
                    */

                    'specialization' =>
                        $lead->specialization,

                    'qualification' =>
                        $lead->qualification,


                    /*
                    |--------------------------------------------------------------------------
                    | Address
                    |--------------------------------------------------------------------------
                    */

                    'address' =>
                        $lead->address,

                    'country' =>
                        $lead->country,

                    'state' =>
                        $lead->state,

                    'city' =>
                        $lead->city,

                    'pincode' =>
                        $lead->pincode,

                    'latitude' =>
                        $lead->latitude,

                    'longitude' =>
                        $lead->longitude,


                    /*
                    |--------------------------------------------------------------------------
                    | Lead Information
                    |--------------------------------------------------------------------------
                    */

                    'source' =>
                        $lead->source,

                    'priority' =>
                        $lead->priority,

                    'lead_status' =>
                        $lead->lead_status,


                    /*
                    |--------------------------------------------------------------------------
                    | Follow Up
                    |--------------------------------------------------------------------------
                    */

                    'next_followup_at' =>
                        $lead->next_followup_at
                        ? $lead->next_followup_at->format(
                            'Y-m-d H:i:s'
                        )
                        : null,


                    /*
                    |--------------------------------------------------------------------------
                    | Notes
                    |--------------------------------------------------------------------------
                    */

                    'notes' =>
                        $lead->notes,


                    /*
                    |--------------------------------------------------------------------------
                    | Conversion
                    |--------------------------------------------------------------------------
                    */

                    'converted_id' =>
                        $lead->converted_id,

                    'converted_at' =>
                        $lead->converted_at
                        ? $lead->converted_at->format(
                            'Y-m-d H:i:s'
                        )
                        : null,


                    /*
                    |--------------------------------------------------------------------------
                    | Marketing Staff
                    |--------------------------------------------------------------------------
                    */

                    'marketing_staff' =>
                        $lead->marketingStaff
                        ? [
                            'id' =>
                                $lead->marketingStaff->id,

                            'name' =>
                                $lead->marketingStaff->name,

                            'employee_code' =>
                                $lead->marketingStaff->employee_code,

                            'mobile' =>
                                $lead->marketingStaff->mobile,

                            'photo' =>
                                $lead->marketingStaff->photo
                                ? asset(
                                    $lead->marketingStaff->photo
                                )
                                : null,

                            'designation' =>
                                $lead->marketingStaff->designation,
                        ]
                        : null,


                    /*
                    |--------------------------------------------------------------------------
                    | Status
                    |--------------------------------------------------------------------------
                    */

                    'status' =>
                        (bool) $lead->status,


                    /*
                    |--------------------------------------------------------------------------
                    | Dates
                    |--------------------------------------------------------------------------
                    */

                    'created_at' =>
                        $lead->created_at
                        ? $lead->created_at->format(
                            'Y-m-d H:i:s'
                        )
                        : null,

                    'updated_at' =>
                        $lead->updated_at
                        ? $lead->updated_at->format(
                            'Y-m-d H:i:s'
                        )
                        : null,
                ];

            })->values(),
        ];
    }
}