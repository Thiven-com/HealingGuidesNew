<?php

namespace Database\Seeders;

use App\Models\Diagnostic;
use App\Models\DiagnosticLabTest;
use App\Models\LabTest;
use Illuminate\Database\Seeder;

class DiagnosticLabTestSeeder extends Seeder
{
    public function run(): void
    {
        DiagnosticLabTest::truncate();

        $diagnostics = Diagnostic::all();
        $labTests = LabTest::all();

        foreach ($diagnostics as $diagnostic) {

            foreach ($labTests as $labTest) {

                $price = rand(200, 3000);

                $offerPrice = rand(0, 1)
                    ? $price - rand(20, 300)
                    : null;

                if ($offerPrice !== null && $offerPrice < 50) {
                    $offerPrice = 50;
                }

                $reportTime = rand(2, 24);
                $reportTimeType = 'Hours';

                if (rand(1, 10) <= 2) {
                    $reportTime = rand(1, 5);
                    $reportTimeType = 'Days';
                }

                DiagnosticLabTest::create([

                    'diagnostic_id' => $diagnostic->id,

                    'lab_test_id' => $labTest->id,

                    'price' => $price,

                    'offer_price' => $offerPrice,

                    'report_time' => $reportTime,

                    'report_time_type' => $reportTimeType,

                    'home_collection' => rand(0, 1),

                    'status' => 1,

                ]);
            }
        }
    }
}