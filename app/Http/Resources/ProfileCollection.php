<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProfileCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($customer) {

            return [
                'id' => $customer->id,
                'customer_code' => $customer->customer_code,
                'name' => $customer->name,
                'mobile' => $customer->mobile,
                'alternate_mobile' => $customer->alternate_mobile,
                'email' => $customer->email,
                'gender' => $customer->gender,
                'dob' => $customer->dob,
                'age' => $customer->age,
                'blood_group' => $customer->blood_group,
                'height' => $customer->height,
                'weight' => $customer->weight,
                'occupation' => $customer->occupation,
                'aadhaar_no' => $customer->aadhaar_no,
                'photo' => !empty($customer->photo)
                    ? asset($customer->photo)
                    : null,
                'address' => $customer->address,
                'country' => $customer->country,
                'state' => $customer->state,
                'city' => $customer->city,
                'pincode' => $customer->pincode,
                'emergency_contact_name' => $customer->emergency_contact_name,
                'emergency_contact_mobile' => $customer->emergency_contact_mobile,
                'is_verified' => (int) $customer->is_verified,
                'status' => (int) $customer->status,
                'family_members' => $customer->familyMembers->map(function ($member) {
                    return [
                        'id' => $member->id,
                        'name' => $member->name,
                        'relationship' => $member->relationship,
                        'gender' => $member->gender,
                        'mobile' => $member->mobile,
                        'dob' => $member->dob,
                        'age' => $member->age,
                        'blood_group' => $member->blood_group,
                        'height' => $member->height,
                        'weight' => $member->weight,
                        'occupation' => $member->occupation,
                        'aadhaar_no' => $member->aadhaar_no,
                        'photo' => !empty($member->photo)
                            ? asset($member->photo)
                            : null,
                        'status' => (int) $member->status,
                        'created_at' => $member->created_at?->format('Y-m-d H:i:s'),
                        'updated_at' => $member->updated_at?->format('Y-m-d H:i:s'),
                    ];
                })->values(),
                'created_at' => $customer->created_at?->format('Y-m-d H:i:s'),
                'updated_at' => $customer->updated_at?->format('Y-m-d H:i:s'),
            ];

        })->toArray();
    }
}
