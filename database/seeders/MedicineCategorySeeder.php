<?php

namespace Database\Seeders;

use App\Models\MedicineCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MedicineCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [

            [
                'category_name' => 'Pain Relief',
                'image' => null,
                'description' => 'Medicines for pain, headache, body pain and fever relief.',
                'sort_order' => 1,
            ],

            [
                'category_name' => 'Diabetes',
                'image' => null,
                'description' => 'Medicines commonly used for diabetes management.',
                'sort_order' => 2,
            ],

            [
                'category_name' => 'Antibiotics',
                'image' => null,
                'description' => 'Prescription medicines used for bacterial infections.',
                'sort_order' => 3,
            ],

            [
                'category_name' => 'Vitamins & Supplements',
                'image' => null,
                'description' => 'Vitamins, minerals and nutritional supplements.',
                'sort_order' => 4,
            ],

            [
                'category_name' => 'Cold & Cough',
                'image' => null,
                'description' => 'Medicines for cold, cough, throat irritation and congestion.',
                'sort_order' => 5,
            ],

            [
                'category_name' => 'Digestive Care',
                'image' => null,
                'description' => 'Medicines for acidity, indigestion and digestive problems.',
                'sort_order' => 6,
            ],

            [
                'category_name' => 'Allergy',
                'image' => null,
                'description' => 'Medicines commonly used for allergy symptoms.',
                'sort_order' => 7,
            ],

            [
                'category_name' => 'Skin Care',
                'image' => null,
                'description' => 'Creams, ointments and medicines for common skin conditions.',
                'sort_order' => 8,
            ],

            [
                'category_name' => 'First Aid',
                'image' => null,
                'description' => 'Basic first aid and wound care products.',
                'sort_order' => 9,
            ],

            [
                'category_name' => 'Heart Care',
                'image' => null,
                'description' => 'Medicines used in cardiovascular care.',
                'sort_order' => 10,
            ],

            [
                'category_name' => 'Blood Pressure',
                'image' => null,
                'description' => 'Medicines used for blood pressure management.',
                'sort_order' => 11,
            ],

            [
                'category_name' => 'Respiratory Care',
                'image' => null,
                'description' => 'Medicines and products used for respiratory care.',
                'sort_order' => 12,
            ],
        ];

        foreach ($categories as $category) {

            MedicineCategory::updateOrCreate(

                [
                    'slug' => Str::slug(
                        $category['category_name']
                    )
                ],

                [
                    'category_name' =>
                        $category['category_name'],

                    'image' =>
                        $category['image'],

                    'description' =>
                        $category['description'],

                    'sort_order' =>
                        $category['sort_order'],

                    'status' => 1,
                ]
            );
        }
    }
}