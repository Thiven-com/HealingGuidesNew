<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChatbotIntent;

class ChatbotIntentSeeder extends Seeder
{
    public function run()
    {
        $intents = [

            [
                'name' => 'Health Problem',
                'slug' => 'health_problem',
                'description' => 'Customer has a health problem or symptom',
                'keywords' => [
                    'pain',
                    'headache',
                    'fever',
                    'cold',
                    'cough',
                    'vomiting',
                    'vomit',
                    'stomach pain',
                    'chest pain',
                    'body pain',
                    'back pain',
                    'throat pain',
                    'dizziness',
                    'weakness',
                    'breathing problem',
                ],
                'service' => 'doctor',
                'status' => 1,
            ],

            [
                'name' => 'Find Doctor',
                'slug' => 'find_doctor',
                'description' => 'Customer wants to find or book a doctor',
                'keywords' => [
                    'doctor',
                    'physician',
                    'specialist',
                    'consult doctor',
                    'book doctor',
                    'doctor appointment',
                ],
                'service' => 'doctor',
                'status' => 1,
            ],

            [
                'name' => 'Find Hospital',
                'slug' => 'find_hospital',
                'description' => 'Customer wants to find a hospital',
                'keywords' => [
                    'hospital',
                    'nearby hospital',
                    'hospital near me',
                    'find hospital',
                ],
                'service' => 'hospital',
                'status' => 1,
            ],

            [
                'name' => 'Diagnostic Test',
                'slug' => 'diagnostic',
                'description' => 'Customer wants a diagnostic or lab test',
                'keywords' => [
                    'lab',
                    'lab test',
                    'diagnostic',
                    'diagnostics',
                    'blood test',
                    'test',
                    'health test',
                ],
                'service' => 'diagnostic',
                'status' => 1,
            ],

            [
                'name' => 'Medicine',
                'slug' => 'medicine',
                'description' => 'Customer wants to search or order medicine',
                'keywords' => [
                    'medicine',
                    'tablet',
                    'capsule',
                    'drug',
                    'pharmacy',
                    'medicine order',
                ],
                'service' => 'medicine',
                'status' => 1,
            ],

            [
                'name' => 'Ambulance',
                'slug' => 'ambulance',
                'description' => 'Customer wants an ambulance',
                'keywords' => [
                    'ambulance',
                    'emergency ambulance',
                    'need ambulance',
                    'book ambulance',
                ],
                'service' => 'ambulance',
                'status' => 1,
            ],

            [
                'name' => 'General',
                'slug' => 'general',
                'description' => 'General chatbot questions',
                'keywords' => [
                    'hello',
                    'hi',
                    'help',
                    'what can you do',
                    'services',
                ],
                'service' => null,
                'status' => 1,
            ],

        ];

        foreach ($intents as $intent) {

            ChatbotIntent::updateOrCreate(
                [
                    'slug' => $intent['slug']
                ],
                [
                    'name' => $intent['name'],
                    'description' => $intent['description'],
                    'keywords' => $intent['keywords'],
                    'service' => $intent['service'],
                    'status' => $intent['status'],
                ]
            );
        }
    }
}