<?php

namespace Database\Seeders;

use App\Models\Hospital;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Get Active Hospitals
        |--------------------------------------------------------------------------
        */

        $hospitals = Hospital::where('status', 1)->get();

        if ($hospitals->isEmpty()) {

            $this->command->warn(
                'No active hospitals found. Please seed hospitals first.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Medicine Data
        |--------------------------------------------------------------------------
        */

        $medicines = [

            /*
            |--------------------------------------------------------------------------
            | Pain Relief
            |--------------------------------------------------------------------------
            */

            [
                'category' => 'Pain Relief',
                'medicine_name' => 'Paracetamol 500mg',
                'generic_name' => 'Paracetamol',
                'brand_name' => 'Generic',
                'manufacturer' => 'Generic Pharma',
                'medicine_type' => 'Tablet',
                'strength' => '500 mg',
                'pack_size' => '10 Tablets',
                'description' => 'Paracetamol tablet commonly used for fever and mild to moderate pain.',
                'composition' => 'Paracetamol 500mg',
                'mrp' => 25.00,
                'selling_price' => 22.00,
                'stock_quantity' => 100,
                'prescription_required' => 0,
            ],

            [
                'category' => 'Pain Relief',
                'medicine_name' => 'Paracetamol 650mg',
                'generic_name' => 'Paracetamol',
                'brand_name' => 'Generic',
                'manufacturer' => 'Generic Pharma',
                'medicine_type' => 'Tablet',
                'strength' => '650 mg',
                'pack_size' => '15 Tablets',
                'description' => 'Paracetamol tablet for fever and pain relief.',
                'composition' => 'Paracetamol 650mg',
                'mrp' => 35.00,
                'selling_price' => 32.00,
                'stock_quantity' => 100,
                'prescription_required' => 0,
            ],

            [
                'category' => 'Pain Relief',
                'medicine_name' => 'Ibuprofen 400mg',
                'generic_name' => 'Ibuprofen',
                'brand_name' => 'Generic',
                'manufacturer' => 'Generic Pharma',
                'medicine_type' => 'Tablet',
                'strength' => '400 mg',
                'pack_size' => '10 Tablets',
                'description' => 'Non-steroidal anti-inflammatory medicine.',
                'composition' => 'Ibuprofen 400mg',
                'mrp' => 45.00,
                'selling_price' => 40.00,
                'stock_quantity' => 80,
                'prescription_required' => 1,
            ],

            /*
            |--------------------------------------------------------------------------
            | Diabetes
            |--------------------------------------------------------------------------
            */

            [
                'category' => 'Diabetes',
                'medicine_name' => 'Metformin 500mg',
                'generic_name' => 'Metformin',
                'brand_name' => 'Generic',
                'manufacturer' => 'Generic Pharma',
                'medicine_type' => 'Tablet',
                'strength' => '500 mg',
                'pack_size' => '20 Tablets',
                'description' => 'Medicine used as part of diabetes management.',
                'composition' => 'Metformin Hydrochloride 500mg',
                'mrp' => 50.00,
                'selling_price' => 45.00,
                'stock_quantity' => 100,
                'prescription_required' => 1,
            ],

            [
                'category' => 'Diabetes',
                'medicine_name' => 'Metformin 850mg',
                'generic_name' => 'Metformin',
                'brand_name' => 'Generic',
                'manufacturer' => 'Generic Pharma',
                'medicine_type' => 'Tablet',
                'strength' => '850 mg',
                'pack_size' => '10 Tablets',
                'description' => 'Prescription medicine used in diabetes management.',
                'composition' => 'Metformin Hydrochloride 850mg',
                'mrp' => 55.00,
                'selling_price' => 49.00,
                'stock_quantity' => 75,
                'prescription_required' => 1,
            ],

            /*
            |--------------------------------------------------------------------------
            | Antibiotics
            |--------------------------------------------------------------------------
            */

            [
                'category' => 'Antibiotics',
                'medicine_name' => 'Amoxicillin 500mg',
                'generic_name' => 'Amoxicillin',
                'brand_name' => 'Generic',
                'manufacturer' => 'Generic Pharma',
                'medicine_type' => 'Capsule',
                'strength' => '500 mg',
                'pack_size' => '10 Capsules',
                'description' => 'Prescription antibiotic medicine.',
                'composition' => 'Amoxicillin 500mg',
                'mrp' => 120.00,
                'selling_price' => 105.00,
                'stock_quantity' => 50,
                'prescription_required' => 1,
            ],

            [
                'category' => 'Antibiotics',
                'medicine_name' => 'Azithromycin 500mg',
                'generic_name' => 'Azithromycin',
                'brand_name' => 'Generic',
                'manufacturer' => 'Generic Pharma',
                'medicine_type' => 'Tablet',
                'strength' => '500 mg',
                'pack_size' => '3 Tablets',
                'description' => 'Prescription antibiotic medicine.',
                'composition' => 'Azithromycin 500mg',
                'mrp' => 95.00,
                'selling_price' => 85.00,
                'stock_quantity' => 50,
                'prescription_required' => 1,
            ],

            /*
            |--------------------------------------------------------------------------
            | Vitamins
            |--------------------------------------------------------------------------
            */

            [
                'category' => 'Vitamins & Supplements',
                'medicine_name' => 'Vitamin C 500mg',
                'generic_name' => 'Ascorbic Acid',
                'brand_name' => 'Generic',
                'manufacturer' => 'Generic Pharma',
                'medicine_type' => 'Tablet',
                'strength' => '500 mg',
                'pack_size' => '15 Tablets',
                'description' => 'Vitamin C nutritional supplement.',
                'composition' => 'Ascorbic Acid 500mg',
                'mrp' => 80.00,
                'selling_price' => 72.00,
                'stock_quantity' => 100,
                'prescription_required' => 0,
            ],

            [
                'category' => 'Vitamins & Supplements',
                'medicine_name' => 'Vitamin D3 60000 IU',
                'generic_name' => 'Cholecalciferol',
                'brand_name' => 'Generic',
                'manufacturer' => 'Generic Pharma',
                'medicine_type' => 'Capsule',
                'strength' => '60000 IU',
                'pack_size' => '4 Capsules',
                'description' => 'Vitamin D3 nutritional supplement.',
                'composition' => 'Cholecalciferol 60000 IU',
                'mrp' => 120.00,
                'selling_price' => 110.00,
                'stock_quantity' => 70,
                'prescription_required' => 0,
            ],

            /*
            |--------------------------------------------------------------------------
            | Cold & Cough
            |--------------------------------------------------------------------------
            */

            [
                'category' => 'Cold & Cough',
                'medicine_name' => 'Cough Syrup',
                'generic_name' => 'Cough Formula',
                'brand_name' => 'Generic',
                'manufacturer' => 'Generic Pharma',
                'medicine_type' => 'Syrup',
                'strength' => null,
                'pack_size' => '100 ml',
                'description' => 'Cough syrup for symptomatic cough relief.',
                'composition' => null,
                'mrp' => 95.00,
                'selling_price' => 85.00,
                'stock_quantity' => 60,
                'prescription_required' => 0,
            ],

            /*
            |--------------------------------------------------------------------------
            | Digestive Care
            |--------------------------------------------------------------------------
            */

            [
                'category' => 'Digestive Care',
                'medicine_name' => 'Pantoprazole 40mg',
                'generic_name' => 'Pantoprazole',
                'brand_name' => 'Generic',
                'manufacturer' => 'Generic Pharma',
                'medicine_type' => 'Tablet',
                'strength' => '40 mg',
                'pack_size' => '10 Tablets',
                'description' => 'Medicine commonly used for acid-related conditions.',
                'composition' => 'Pantoprazole 40mg',
                'mrp' => 75.00,
                'selling_price' => 65.00,
                'stock_quantity' => 100,
                'prescription_required' => 1,
            ],

            /*
            |--------------------------------------------------------------------------
            | Allergy
            |--------------------------------------------------------------------------
            */

            [
                'category' => 'Allergy',
                'medicine_name' => 'Cetirizine 10mg',
                'generic_name' => 'Cetirizine',
                'brand_name' => 'Generic',
                'manufacturer' => 'Generic Pharma',
                'medicine_type' => 'Tablet',
                'strength' => '10 mg',
                'pack_size' => '10 Tablets',
                'description' => 'Antihistamine medicine used for allergy symptoms.',
                'composition' => 'Cetirizine 10mg',
                'mrp' => 30.00,
                'selling_price' => 25.00,
                'stock_quantity' => 100,
                'prescription_required' => 0,
            ],

            /*
            |--------------------------------------------------------------------------
            | First Aid
            |--------------------------------------------------------------------------
            */

            [
                'category' => 'First Aid',
                'medicine_name' => 'Antiseptic Solution',
                'generic_name' => 'Antiseptic Solution',
                'brand_name' => 'Generic',
                'manufacturer' => 'Generic Pharma',
                'medicine_type' => 'Solution',
                'strength' => null,
                'pack_size' => '100 ml',
                'description' => 'Antiseptic solution for external first-aid use.',
                'composition' => null,
                'mrp' => 85.00,
                'selling_price' => 75.00,
                'stock_quantity' => 50,
                'prescription_required' => 0,
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Add Medicines To Every Hospital
        |--------------------------------------------------------------------------
        */

        foreach ($hospitals as $hospital) {

            foreach ($medicines as $index => $medicineData) {

                /*
                |--------------------------------------------------------------------------
                | Category
                |--------------------------------------------------------------------------
                */

                $category = MedicineCategory::where(
                    'category_name',
                    $medicineData['category']
                )->first();

                if (!$category) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Medicine Code
                |--------------------------------------------------------------------------
                */

                $code =
                    'MED-' .
                    $hospital->id .
                    '-' .
                    str_pad(
                        $index + 1,
                        4,
                        '0',
                        STR_PAD_LEFT
                    );

                /*
                |--------------------------------------------------------------------------
                | Slug
                |--------------------------------------------------------------------------
                */

                $slug = Str::slug(
                    $medicineData['medicine_name']
                );

                /*
                |--------------------------------------------------------------------------
                | Create / Update
                |--------------------------------------------------------------------------
                */

                Medicine::updateOrCreate(

                    [
                        'hospital_id' =>
                            $hospital->id,

                        'medicine_code' =>
                            $code,
                    ],

                    [
                        'medicine_category_id' =>
                            $category->id,

                        'medicine_name' =>
                            $medicineData['medicine_name'],

                        'slug' =>
                            $slug,

                        'generic_name' =>
                            $medicineData['generic_name'],

                        'brand_name' =>
                            $medicineData['brand_name'],

                        'manufacturer' =>
                            $medicineData['manufacturer'],

                        'medicine_type' =>
                            $medicineData['medicine_type'],

                        'strength' =>
                            $medicineData['strength'],

                        'pack_size' =>
                            $medicineData['pack_size'],

                        'image' =>
                            null,

                        'description' =>
                            $medicineData['description'],

                        'composition' =>
                            $medicineData['composition'],

                        'usage_instructions' =>
                            null,

                        'side_effects' =>
                            null,

                        'storage_instructions' =>
                            'Store in a cool and dry place away from direct sunlight.',

                        'mrp' =>
                            $medicineData['mrp'],

                        'selling_price' =>
                            $medicineData['selling_price'],

                        'stock_quantity' =>
                            $medicineData['stock_quantity'],

                        'prescription_required' =>
                            $medicineData['prescription_required'],

                        'status' => 1,
                    ]
                );
            }

            $this->command->info(
                'Medicines seeded for: ' .
                $hospital->hospital_name
            );
        }
    }
}