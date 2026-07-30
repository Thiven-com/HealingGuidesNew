<?php

namespace Database\Seeders;

use App\Models\Ambulance;
use App\Models\AmbulanceType;
use App\Models\Hospital;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class AmbulanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ambulance::truncate();

        $path = database_path('seed-data/ambulances.json');

        if (!File::exists($path)) {
            $this->command->error('ambulances.json not found.');
            return;
        }

        $ambulances = json_decode(File::get($path), true);

        foreach ($ambulances as $ambulance) {

            $ambulanceType = AmbulanceType::where(
                'ambulance_type_code',
                $ambulance['ambulance_type_code']
            )->first();

            $hospital = Hospital::where(
                'hospital_code',
                $ambulance['hospital_code']
            )->first();

            if (!$ambulanceType) {
                $this->command->warn("Ambulance Type {$ambulance['ambulance_type_code']} not found.");
                continue;
            }

            Ambulance::create([

                'ambulance_type_id' => $ambulanceType->id,

                'hospital_id' => $hospital?->id,

                'ambulance_name' => $ambulance['ambulance_name'],

                'ambulance_code' => 'AMB' . str_pad(Ambulance::count() + 1, 3, '0', STR_PAD_LEFT),

                'vehicle_number' => $ambulance['vehicle_number'],

                'registration_number' => $ambulance['registration_number'],

                'driver_name' => $ambulance['driver_name'],

                'driver_mobile' => $ambulance['driver_mobile'],

                'driver_license_number' => $ambulance['driver_license_number'],

                'driver_photo' => $ambulance['driver_photo'],

                'model' => $ambulance['model'],

                'manufacturing_year' => $ambulance['manufacturing_year'],

                'current_location' => $ambulance['current_location'],

                'latitude' => $ambulance['latitude'],

                'longitude' => $ambulance['longitude'],

                'base_fare' => $ambulance['base_fare'],

                'price_per_km' => $ambulance['price_per_km'],

                'is_available' => $ambulance['is_available'],

                'status' => $ambulance['status'],

            ]);
        }

        $this->command->info(count($ambulances) . ' Ambulances Imported Successfully.');
    }
}