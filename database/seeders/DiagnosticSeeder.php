<?php

namespace Database\Seeders;

use App\Models\Diagnostic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DiagnosticSeeder extends Seeder
{
    public function run(): void
    {
        $json = File::get(database_path('seed-data/diagnostics.json'));

        $diagnostics = json_decode($json, true);

        foreach ($diagnostics as $index => $diagnostic) {

            Diagnostic::create([

                'diagnostic_name' => $diagnostic['diagnostic_name'],

                'diagnostic_code' => 'DIA' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),

                'slug' => Str::slug($diagnostic['diagnostic_name']),

                'registration_number' => $diagnostic['registration_number'] ?? null,

                'email' => $diagnostic['email'] ?? null,

                'mobile' => $diagnostic['mobile'] ?? null,

                'phone' => $diagnostic['phone'] ?? null,

                'address' => $diagnostic['address'] ?? null,

                'country' => $diagnostic['country'] ?? 'India',

                'state' => $diagnostic['state'] ?? null,

                'city' => $diagnostic['city'] ?? null,

                'pincode' => $diagnostic['pincode'] ?? null,

                'latitude' => $diagnostic['latitude'] ?? null,

                'longitude' => $diagnostic['longitude'] ?? null,

                'opening_time' => $diagnostic['opening_time'] ?? null,

                'closing_time' => $diagnostic['closing_time'] ?? null,

                'home_collection' => $diagnostic['home_collection'] ?? 0,

                'logo' => $diagnostic['logo'] ?? null,

                'banner' => $diagnostic['banner'] ?? null,

                'status' => $diagnostic['status'] ?? 1,
            ]);
        }
    }
}