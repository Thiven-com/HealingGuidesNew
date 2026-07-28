<?php

namespace Database\Seeders;

use App\Models\Specialization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SpecializationSeeder extends Seeder
{
    public function run(): void
    {
        Specialization::truncate();

        $path = database_path('seed-data/specializations.json');

        if (!File::exists($path)) {

            $this->command->error('specializations.json not found.');

            return;
        }

        $specializations = json_decode(File::get($path), true);

        foreach ($specializations as $item) {

            Specialization::create([

                'specialization_name' => $item['specialization_name'],

                'slug' => Str::slug($item['specialization_name']),

                'icon' => $item['icon'] ?? null,

                'image' => $item['image'] ?? null,

                'description' => $item['description'] ?? null,

                'status' => $item['status'] ?? 1,

            ]);

        }

        $this->command->info(count($specializations).' Specializations Imported Successfully.');
    }
}