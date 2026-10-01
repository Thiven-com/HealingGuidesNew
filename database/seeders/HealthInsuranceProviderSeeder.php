<?php

namespace Database\Seeders;

use App\Models\HealthInsuranceProvider;
use Illuminate\Database\Seeder;

class HealthInsuranceProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path(
            'seed-data/health_insurance_providers.json'
        );

        if (!file_exists($path)) {
            $this->command->error(
                'health_insurance_providers.json file not found.'
            );

            return;
        }

        $providers = json_decode(
            file_get_contents($path),
            true
        );

        if (!is_array($providers)) {
            $this->command->error(
                'Invalid health_insurance_providers.json file.'
            );

            return;
        }

        foreach ($providers as $provider) {

            HealthInsuranceProvider::updateOrCreate(
                [
                    'slug' => $provider['slug'],
                ],
                $provider
            );
        }

        $this->command->info(
            count($providers) .
            ' health insurance providers seeded successfully.'
        );
    }
}