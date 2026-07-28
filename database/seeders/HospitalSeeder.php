<?php

namespace Database\Seeders;

use App\Models\Hospital;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HospitalSeeder extends Seeder
{
    public function run(): void
    {
        Hospital::truncate();

        $path = database_path('seed-data/hospitals.json');

        if (! File::exists($path)) {
            $this->command->error('hospitals.json not found.');
            return;
        }

        $hospitals = json_decode(File::get($path), true);

        foreach ($hospitals as $hospital) {

            Hospital::create([

                'hospital_name' => $hospital['hospital_name'],
                'hospital_slug' => Str::slug($hospital['hospital_name']),
                'hospital_code' => $hospital['hospital_code'],
                'hospital_type' => $hospital['hospital_type'],
                'registration_number' => $hospital['registration_number'],
                'gst_number' => $hospital['gst_number'],
                'pan_number' => $hospital['pan_number'],
                'email' => $hospital['email'],
                'mobile' => $hospital['mobile'],
                'phone' => $hospital['phone'],
                'logo' => $hospital['logo'],
                'banner' => $hospital['banner'],
                'address' => $hospital['address'],
                'country' => $hospital['country'],
                'state' => $hospital['state'],
                'city' => $hospital['city'],
                'pincode' => $hospital['pincode'],
                'latitude' => $hospital['latitude'],
                'longitude' => $hospital['longitude'],
                'opening_time' => $hospital['opening_time'],
                'closing_time' => $hospital['closing_time'],
                'emergency_available' => $hospital['emergency_available'],
                'status' => $hospital['status'],

            ]);
        }

        $this->command->info(count($hospitals) . ' hospitals imported successfully.');
    }
}