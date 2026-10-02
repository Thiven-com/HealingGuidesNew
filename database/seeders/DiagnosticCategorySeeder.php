<?php

namespace Database\Seeders;

use App\Models\DiagnosticCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DiagnosticCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [

            [
                'name' => 'Laboratory Tests',
                'short_description' => 'Blood, urine, stool and other laboratory investigations.',
                'description' => 'Laboratory diagnostic tests used for routine screening, diagnosis and health monitoring.',
            ],

            [
                'name' => 'Radiology & Imaging',
                'short_description' => 'Medical imaging and radiology investigations.',
                'description' => 'Diagnostic imaging services including X-Ray, ultrasound, CT scan, MRI and other imaging investigations.',
            ],

            [
                'name' => 'Cardiology',
                'short_description' => 'Heart and cardiovascular diagnostic tests.',
                'description' => 'Diagnostic investigations for evaluating heart and cardiovascular health.',
            ],

            [
                'name' => 'Neurology',
                'short_description' => 'Brain, nerve and neurological investigations.',
                'description' => 'Diagnostic tests and investigations related to the brain, spinal cord and nervous system.',
            ],

            [
                'name' => 'Pathology',
                'short_description' => 'Pathology and tissue-based diagnostic services.',
                'description' => 'Pathology investigations including tissue, cell and laboratory-based diagnostic examinations.',
            ],

            [
                'name' => 'Microbiology',
                'short_description' => 'Infection and microorganism testing.',
                'description' => 'Diagnostic investigations for detecting bacteria, viruses, fungi, parasites and other microorganisms.',
            ],

            [
                'name' => 'Genetics & Molecular Diagnostics',
                'short_description' => 'Genetic and molecular diagnostic investigations.',
                'description' => 'Advanced diagnostic testing based on genetic, molecular and genomic analysis.',
            ],

            [
                'name' => 'Health Checkups',
                'short_description' => 'Preventive and comprehensive health checkups.',
                'description' => 'Complete health screening packages designed for preventive healthcare and regular health monitoring.',
            ],

            [
                'name' => 'Specialized Diagnostics',
                'short_description' => 'Advanced and specialty diagnostic investigations.',
                'description' => 'Specialized diagnostic services for specific medical conditions and clinical requirements.',
            ],

            [
                'name' => 'Other Diagnostics',
                'short_description' => 'Other diagnostic services and investigations.',
                'description' => 'Miscellaneous diagnostic services that do not fall under the other major categories.',
            ],

        ];

        foreach ($categories as $index => $category) {

            DiagnosticCategory::updateOrCreate(
                [
                    'slug' => Str::slug($category['name']),
                ],
                [
                    'name' => $category['name'],

                    'short_description' =>
                        $category['short_description'],

                    'description' =>
                        $category['description'],

                    'display_order' =>
                        $index + 1,

                    'status' => 1,
                ]
            );
        }
    }
}