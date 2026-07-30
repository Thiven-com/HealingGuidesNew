<?php

namespace Database\Seeders;

use App\Models\LabTest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LabTestSeeder extends Seeder
{
    public function run(): void
    {
        $json = File::get(database_path('seed-data/lab_tests.json'));

        $labTests = json_decode($json, true);

        foreach ($labTests as $index => $test) {

            LabTest::create([

                'test_name' => $test['test_name'],

                'test_code' => 'LAB' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),

                'slug' => Str::slug($test['test_name']),

                'description' => $test['description'] ?? null,

                'sample_type' => $test['sample_type'] ?? null,

                'preparation' => $test['preparation'] ?? null,

                'report_time' => $test['report_time'] ?? null,

                'report_time_type' => $test['report_time_type'] ?? 'Hours',

                'fasting_required' => $test['fasting_required'] ?? 0,

                'home_collection' => $test['home_collection'] ?? 1,

                'status' => $test['status'] ?? 1,

            ]);
        }
    }
}