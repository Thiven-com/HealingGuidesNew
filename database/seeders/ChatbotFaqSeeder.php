<?php

namespace Database\Seeders;

use App\Models\ChatbotFaq;
use Illuminate\Database\Seeder;

class ChatbotFaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [

            /*
            |--------------------------------------------------------------------------
            | GENERAL
            |--------------------------------------------------------------------------
            */

            [
                'question' => 'How can you help me?',
                'answer' => 'I can help you find doctors, hospitals, diagnostic tests, medicines, ambulances, appointments, prescriptions, medical reports and health information.',
                'keywords' => [
                    'help',
                    'what can you do',
                    'how can you help',
                    'services',
                    'support'
                ],
                'category' => 'general',
            ],

            [
                'question' => 'What services are available?',
                'answer' => 'You can use the app to find doctors and hospitals, book appointments, book diagnostic tests, order medicines, request ambulances, manage medical reports, prescriptions and health records.',
                'keywords' => [
                    'services',
                    'available services',
                    'what services',
                    'features'
                ],
                'category' => 'general',
            ],

            /*
            |--------------------------------------------------------------------------
            | SYMPTOMS
            |--------------------------------------------------------------------------
            */

            [
                'question' => 'I have a headache',
                'answer' => 'I understand. I can help you find the right healthcare service. How long have you had the headache?',
                'keywords' => [
                    'headache',
                    'head pain',
                    'pain in head',
                    'head is hurting',
                    'head hurts'
                ],
                'category' => 'symptom',
            ],

            [
                'question' => 'I have fever',
                'answer' => 'I understand. How long have you had the fever?',
                'keywords' => [
                    'fever',
                    'temperature',
                    'high temperature',
                    'body temperature'
                ],
                'category' => 'symptom',
            ],

            [
                'question' => 'I have stomach pain',
                'answer' => 'I understand. Where exactly are you experiencing the stomach pain and how long have you had it?',
                'keywords' => [
                    'stomach pain',
                    'stomach ache',
                    'pain in stomach',
                    'abdominal pain',
                    'abdomen pain'
                ],
                'category' => 'symptom',
            ],

            [
                'question' => 'I have chest pain',
                'answer' => 'Chest pain can sometimes require urgent medical attention. If the pain is severe, sudden, or associated with breathing difficulty, sweating, dizziness or pain spreading to the arm or jaw, seek emergency medical care immediately. Otherwise, I can help you find a doctor.',
                'keywords' => [
                    'chest pain',
                    'pain in chest',
                    'chest hurts'
                ],
                'category' => 'symptom',
            ],

            [
                'question' => 'I have cough',
                'answer' => 'How long have you had the cough? Do you also have fever, breathing difficulty or chest discomfort?',
                'keywords' => [
                    'cough',
                    'coughing',
                    'dry cough',
                    'continuous cough'
                ],
                'category' => 'symptom',
            ],

            [
                'question' => 'I have cold',
                'answer' => 'How long have you had the cold? Do you also have fever, cough or breathing difficulty?',
                'keywords' => [
                    'cold',
                    'common cold',
                    'running nose',
                    'blocked nose',
                    'stuffy nose'
                ],
                'category' => 'symptom',
            ],

            [
                'question' => 'I have back pain',
                'answer' => 'How long have you had the back pain, and is it mild, moderate or severe?',
                'keywords' => [
                    'back pain',
                    'back ache',
                    'pain in back',
                    'lower back pain'
                ],
                'category' => 'symptom',
            ],

            [
                'question' => 'I have body pain',
                'answer' => 'How long have you had the body pain? Please also let me know if you have fever, weakness or any other symptoms.',
                'keywords' => [
                    'body pain',
                    'body ache',
                    'body aches',
                    'pain all over body'
                ],
                'category' => 'symptom',
            ],

            [
                'question' => 'I feel dizzy',
                'answer' => 'How long have you been feeling dizzy? If you are fainting, have severe weakness or other serious symptoms, please seek medical attention promptly.',
                'keywords' => [
                    'dizzy',
                    'dizziness',
                    'feeling dizzy',
                    'light headed',
                    'lightheaded'
                ],
                'category' => 'symptom',
            ],

            [
                'question' => 'I have vomiting',
                'answer' => 'How many times have you vomited, and when did it start? Please seek medical attention if you cannot keep fluids down or have severe weakness.',
                'keywords' => [
                    'vomiting',
                    'vomit',
                    'throwing up',
                    'feeling vomiting'
                ],
                'category' => 'symptom',
            ],

            [
                'question' => 'I have diarrhea',
                'answer' => 'How long have you had diarrhea? Please maintain hydration and seek medical care if there is blood in the stool, severe weakness or signs of dehydration.',
                'keywords' => [
                    'diarrhea',
                    'loose motion',
                    'loose motions',
                    'loose stools'
                ],
                'category' => 'symptom',
            ],

            /*
            |--------------------------------------------------------------------------
            | DOCTORS
            |--------------------------------------------------------------------------
            */

            [
                'question' => 'I want to find a doctor',
                'answer' => 'Sure. I can help you find a doctor. Which specialty or type of doctor are you looking for?',
                'keywords' => [
                    'find doctor',
                    'search doctor',
                    'doctor',
                    'doctors',
                    'need doctor',
                    'want doctor'
                ],
                'category' => 'doctor',
            ],

            [
                'question' => 'I want to book a doctor appointment',
                'answer' => 'Sure. I can help you book a doctor appointment. Which specialty would you like to consult?',
                'keywords' => [
                    'book doctor',
                    'doctor appointment',
                    'book appointment',
                    'appointment with doctor',
                    'consult doctor'
                ],
                'category' => 'appointment',
            ],

            [
                'question' => 'Which doctor should I consult?',
                'answer' => 'Tell me your main symptom or health concern and I can help you identify the appropriate type of healthcare service.',
                'keywords' => [
                    'which doctor',
                    'what doctor',
                    'doctor for',
                    'who should i consult',
                    'which specialist'
                ],
                'category' => 'doctor',
            ],

            /*
            |--------------------------------------------------------------------------
            | HOSPITAL
            |--------------------------------------------------------------------------
            */

            [
                'question' => 'I want to find a hospital',
                'answer' => 'Sure. I can help you find hospitals. Please provide your location or pincode.',
                'keywords' => [
                    'find hospital',
                    'search hospital',
                    'hospital',
                    'hospitals',
                    'nearby hospital',
                    'hospital near me'
                ],
                'category' => 'hospital',
            ],

            [
                'question' => 'Which hospital is near me?',
                'answer' => 'I can help you find nearby hospitals. Please share your location or pincode.',
                'keywords' => [
                    'hospital near me',
                    'nearby hospital',
                    'nearest hospital',
                    'hospital nearby'
                ],
                'category' => 'hospital',
            ],

            /*
            |--------------------------------------------------------------------------
            | DIAGNOSTICS
            |--------------------------------------------------------------------------
            */

            [
                'question' => 'I want to book a lab test',
                'answer' => 'Sure. I can help you find and book diagnostic tests. Which test are you looking for?',
                'keywords' => [
                    'lab test',
                    'lab tests',
                    'book lab test',
                    'diagnostic test',
                    'diagnostic',
                    'blood test'
                ],
                'category' => 'diagnostic',
            ],

            [
                'question' => 'I need a blood test',
                'answer' => 'Sure. I can help you find available blood tests and diagnostic centres. Which blood test are you looking for?',
                'keywords' => [
                    'blood test',
                    'blood tests',
                    'blood checkup',
                    'blood examination'
                ],
                'category' => 'diagnostic',
            ],

            [
                'question' => 'How can I find diagnostic centres?',
                'answer' => 'I can help you find diagnostic centres near your location. Please provide your location or pincode.',
                'keywords' => [
                    'diagnostic centre',
                    'diagnostic center',
                    'lab near me',
                    'labs near me',
                    'find lab'
                ],
                'category' => 'diagnostic',
            ],

            /*
            |--------------------------------------------------------------------------
            | MEDICINES
            |--------------------------------------------------------------------------
            */

            [
                'question' => 'I need medicine',
                'answer' => 'Sure. I can help you search for medicines. Please enter the medicine name.',
                'keywords' => [
                    'medicine',
                    'medicines',
                    'need medicine',
                    'buy medicine',
                    'want medicine'
                ],
                'category' => 'medicine',
            ],

            [
                'question' => 'I want to order medicine',
                'answer' => 'Sure. Tell me the medicine name and I can help you search for it.',
                'keywords' => [
                    'order medicine',
                    'medicine order',
                    'buy medicines',
                    'purchase medicine'
                ],
                'category' => 'medicine',
            ],

            [
                'question' => 'Where can I find my medicine orders?',
                'answer' => 'You can view your medicine orders from the My Orders section of the app.',
                'keywords' => [
                    'medicine orders',
                    'my medicine orders',
                    'order history',
                    'medicine order history'
                ],
                'category' => 'medicine',
            ],

            /*
            |--------------------------------------------------------------------------
            | AMBULANCE
            |--------------------------------------------------------------------------
            */

            [
                'question' => 'I need an ambulance',
                'answer' => 'I can help you request an ambulance. Please share your current location so we can find available ambulances nearby.',
                'keywords' => [
                    'ambulance',
                    'need ambulance',
                    'book ambulance',
                    'ambulance needed',
                    'emergency ambulance'
                ],
                'category' => 'ambulance',
            ],

            [
                'question' => 'How can I book an ambulance?',
                'answer' => 'You can request an ambulance from the Ambulance section. I can also help you start an ambulance request.',
                'keywords' => [
                    'book ambulance',
                    'ambulance booking',
                    'request ambulance',
                    'ambulance request'
                ],
                'category' => 'ambulance',
            ],

            /*
            |--------------------------------------------------------------------------
            | APPOINTMENTS
            |--------------------------------------------------------------------------
            */

            [
                'question' => 'How can I see my appointments?',
                'answer' => 'You can view your appointments from the My Appointments section.',
                'keywords' => [
                    'my appointments',
                    'appointments',
                    'appointment history',
                    'bookings'
                ],
                'category' => 'appointment',
            ],

            [
                'question' => 'I want to cancel my appointment',
                'answer' => 'Sure. I can help you with appointment cancellation. Please select the appointment you want to cancel.',
                'keywords' => [
                    'cancel appointment',
                    'cancel my appointment',
                    'appointment cancellation'
                ],
                'category' => 'appointment',
            ],

            [
                'question' => 'I want to reschedule my appointment',
                'answer' => 'Sure. Please select the appointment you want to reschedule and I can help you find another available slot.',
                'keywords' => [
                    'reschedule appointment',
                    'change appointment',
                    'change doctor appointment',
                    'appointment reschedule'
                ],
                'category' => 'appointment',
            ],

            [
                'question' => 'I want to join my video consultation',
                'answer' => 'You can join the video consultation from your appointment details when the appointment is ready.',
                'keywords' => [
                    'join video',
                    'video consultation',
                    'video appointment',
                    'online consultation',
                    'join appointment'
                ],
                'category' => 'appointment',
            ],

            /*
            |--------------------------------------------------------------------------
            | MEDICAL REPORTS
            |--------------------------------------------------------------------------
            */

            [
                'question' => 'I want to upload a medical report',
                'answer' => 'You can upload your medical report from the Medical Reports section. Please select the report file and enter the required details.',
                'keywords' => [
                    'upload medical report',
                    'upload report',
                    'medical report',
                    'add medical report',
                    'upload health report'
                ],
                'category' => 'medical_report',
            ],

            [
                'question' => 'Where can I see my medical reports?',
                'answer' => 'You can view your uploaded medical reports from the Medical Reports section.',
                'keywords' => [
                    'my medical reports',
                    'medical reports',
                    'reports',
                    'health reports',
                    'medical history'
                ],
                'category' => 'medical_report',
            ],

            /*
            |--------------------------------------------------------------------------
            | PRESCRIPTIONS
            |--------------------------------------------------------------------------
            */

            [
                'question' => 'Where can I see my prescriptions?',
                'answer' => 'You can view your prescriptions from the My Prescriptions section.',
                'keywords' => [
                    'prescription',
                    'prescriptions',
                    'my prescription',
                    'my prescriptions',
                    'prescription history'
                ],
                'category' => 'prescription',
            ],

            [
                'question' => 'I want to view my prescription',
                'answer' => 'Sure. Open the My Prescriptions section to view your available prescriptions.',
                'keywords' => [
                    'view prescription',
                    'see prescription',
                    'prescription details'
                ],
                'category' => 'prescription',
            ],

            /*
            |--------------------------------------------------------------------------
            | VITALS
            |--------------------------------------------------------------------------
            */

            [
                'question' => 'I want to see my vitals',
                'answer' => 'You can view your recorded health vitals from the My Vitals section.',
                'keywords' => [
                    'my vitals',
                    'vitals',
                    'health vitals',
                    'health measurements'
                ],
                'category' => 'vitals',
            ],

            /*
            |--------------------------------------------------------------------------
            | COUPONS
            |--------------------------------------------------------------------------
            */

            [
                'question' => 'Do I have any coupons?',
                'answer' => 'You can view your available coupons from the Coupons section.',
                'keywords' => [
                    'coupons',
                    'my coupons',
                    'available coupons',
                    'discount coupon'
                ],
                'category' => 'coupon',
            ],

            [
                'question' => 'How can I use a coupon?',
                'answer' => 'Enter or select a valid coupon during the applicable booking or order process. The system will check whether the coupon is valid.',
                'keywords' => [
                    'use coupon',
                    'apply coupon',
                    'coupon code',
                    'coupon',
                    'discount code'
                ],
                'category' => 'coupon',
            ],

            /*
            |--------------------------------------------------------------------------
            | PROFILE
            |--------------------------------------------------------------------------
            */

            [
                'question' => 'How can I update my profile?',
                'answer' => 'You can update your personal details from the Profile section of the app.',
                'keywords' => [
                    'update profile',
                    'edit profile',
                    'change profile',
                    'profile details'
                ],
                'category' => 'profile',
            ],

            [
                'question' => 'How can I add a family member?',
                'answer' => 'You can add a family member from the Family Members section of your profile.',
                'keywords' => [
                    'family member',
                    'add family member',
                    'family',
                    'add member'
                ],
                'category' => 'profile',
            ],

        ];

        foreach ($faqs as $faq) {

            ChatbotFaq::updateOrCreate(
                [
                    'question' => $faq['question'],
                ],
                [
                    'answer' => $faq['answer'],
                    'keywords' => $faq['keywords'],
                    'category' => $faq['category'],
                    'status' => true,
                    'sort_order' => 0,
                ]
            );
        }
    }
}