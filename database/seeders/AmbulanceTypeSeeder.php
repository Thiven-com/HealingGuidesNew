<?php

namespace Database\Seeders;

use App\Models\AmbulanceType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AmbulanceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AmbulanceType::truncate();

        $path = database_path('seed-data/ambulance_types.json');

        if (!File::exists($path)) {
            $this->command->error('ambulance_types.json not found.');
            return;
        }

        $types = json_decode(File::get($path), true);

        foreach ($types as $index => $type) {

            AmbulanceType::create([

                'ambulance_type_name' => $type['ambulance_type_name'],

                'ambulance_type_code' => 'AMBT' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),

                'slug' => Str::slug($type['ambulance_type_name']),

                'description' => $type['description'],

                'image' => $type['image'],

                'status' => $type['status'],

            ]);
        }

        $this->command->info(count($types) . ' Ambulance Types Imported Successfully.');
    }
}