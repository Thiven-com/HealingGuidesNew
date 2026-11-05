<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SiteSettingCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($setting) {
            return [
                'id' => $setting->id,

                'logo' => $setting->logo ? asset($setting->logo) : null,
                'site_logo' => $setting->site_logo ? asset($setting->site_logo) : null,
                'favicon' => $setting->favicon ? asset($setting->favicon) : null,
                'admin_logo' => $setting->admin_logo ? asset($setting->admin_logo) : null,
                'image' => $setting->image ? asset($setting->image) : null,

                'site_name' => $setting->site_name,
                'description' => $setting->description,
                'short_description' => $setting->short_description,

                'address' => $setting->address,
                'email' => $setting->email,
                'phone' => $setting->phone,
                'customer_care_no' => $setting->customer_care_no,
                'help_line_no' => $setting->help_line_no,

                'gst' => $setting->gst,
                'company' => $setting->company,
                'site_url' => $setting->site_url,

                'social_media' => [
                    'facebook' => $setting->facebook,
                    'linkedin' => $setting->linkedin,
                    'youtube' => $setting->youtube,
                    'pinterest' => $setting->pinterest,
                    'instagram' => $setting->instagram,
                    'twitter' => $setting->twitter,
                ],
            ];
        })->all(); // Important: convert Collection into array
    }
}
