<?php

namespace App\Http\Resources\MarketingApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MarketingStaffCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($staff) {

            return [
                'id' => $staff->id,

                'name' => $staff->name,

                'employee_code' => $staff->employee_code,

                'mobile' => $staff->mobile,

                'email' => $staff->email,

                'photo' => $staff->photo
                    ? asset($staff->photo)
                    : null,

                'dob' => $staff->dob
                    ? \Carbon\Carbon::parse($staff->dob)->format('Y-m-d')
                    : null,

                'gender' => $staff->gender,

                /*
                |--------------------------------------------------------------------------
                | Address
                |--------------------------------------------------------------------------
                */

                'address' => $staff->address,

                'country' => $staff->country,

                'state' => $staff->state,

                'city' => $staff->city,

                'pincode' => $staff->pincode,

                /*
                |--------------------------------------------------------------------------
                | Employment
                |--------------------------------------------------------------------------
                */

                'designation' => $staff->designation,

                'joining_date' => $staff->joining_date
                    ? \Carbon\Carbon::parse($staff->joining_date)->format('Y-m-d')
                    : null,

                /*
                |--------------------------------------------------------------------------
                | Location
                |--------------------------------------------------------------------------
                */

                'latitude' => $staff->latitude,

                'longitude' => $staff->longitude,

                /*
                |--------------------------------------------------------------------------
                | Availability
                |--------------------------------------------------------------------------
                */

                'is_available' => (bool) $staff->is_available,

                /*
                |--------------------------------------------------------------------------
                | Status
                |--------------------------------------------------------------------------
                */

                'status' => (bool) $staff->status,

                'last_login_at' => $staff->last_login_at,

                'created_at' => $staff->created_at
                    ? $staff->created_at->format('Y-m-d H:i:s')
                    : null,

                'updated_at' => $staff->updated_at
                    ? $staff->updated_at->format('Y-m-d H:i:s')
                    : null,
            ];

        })->values()->all();
    }
}