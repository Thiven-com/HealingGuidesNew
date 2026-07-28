<?php

namespace Database\Seeders;

use App\Models\Hospital;
use App\Models\HospitalSpecialization;
use App\Models\Specialization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class HospitalSpecializationSeeder extends Seeder
{
    public function run(): void
    {
        HospitalSpecialization::truncate();

        $path = database_path('seed-data/hospital_specializations.json');

        if (!File::exists($path)) {

            $this->command->error('hospital_specializations.json not found.');

            return;
        }

        $data = json_decode(File::get($path), true);

        foreach ($data as $row) {

            $hospital = Hospital::where('hospital_code', $row['hospital_code'])->first();

            if (!$hospital) {
                $this->command->warn("Hospital not found: {$row['hospital_code']}");
                continue;
            }

            foreach ($row['specializations'] as $specializationName) {

                $specialization = Specialization::where(
                    'specialization_name',
                    $specializationName
                )->first();

                if (!$specialization) {
                    $this->command->warn("Specialization not found: {$specializationName}");
                    continue;
                }

                HospitalSpecialization::updateOrCreate(
                    [
                        'hospital_id' => $hospital->id,
                        'specialization_id' => $specialization->id,
                    ],
                    [
                        'status' => 1,
                    ]
                );
            }
        }

        $this->command->info('Hospital Specializations Imported Successfully.');
    }
}