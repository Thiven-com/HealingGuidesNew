<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\ChatbotConversation;
use App\Models\ChatbotFaq;
use App\Models\ChatbotIntent;
use App\Models\ChatbotMessage;
use App\Models\ChatbotMessageOption;
use App\Models\ChatbotRecommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    /**
     * Start new chatbot conversation
     */
    public function start(Request $request)
    {
        try {

            $user = auth('sanctum')->user();

            if (!$user) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Please Login'
                ], 401);
            }

            $conversation = ChatbotConversation::create([
                'customer_id' => $user->id,
                'conversation_id' => (string) Str::uuid(),
                'status' => 'active',
            ]);

            /*
             * Welcome message
             */
            $message = ChatbotMessage::create([
                'conversation_id' => $conversation->id,
                'sender_type' => 'bot',
                'message' => 'Hello! 👋 How can I help you today?',
                'message_type' => 'text',
            ]);

            /*
             * Initial options
             */
            $options = [
                [
                    'title' => 'I have pain',
                    'value' => 'pain',
                    'action' => 'intent',
                    'sort_order' => 1,
                ],
                [
                    'title' => 'I have headache',
                    'value' => 'headache',
                    'action' => 'intent',
                    'sort_order' => 2,
                ],
                [
                    'title' => 'Find a doctor',
                    'value' => 'doctor',
                    'action' => 'doctor',
                    'sort_order' => 3,
                ],
                [
                    'title' => 'Book a lab test',
                    'value' => 'lab_test',
                    'action' => 'diagnostic',
                    'sort_order' => 4,
                ],
                [
                    'title' => 'Need an ambulance',
                    'value' => 'ambulance',
                    'action' => 'ambulance',
                    'sort_order' => 5,
                ],
                [
                    'title' => 'My medicines',
                    'value' => 'medicine',
                    'action' => 'medicine',
                    'sort_order' => 6,
                ],
            ];

            foreach ($options as $option) {

                ChatbotMessageOption::create([
                    'message_id' => $message->id,
                    'title' => $option['title'],
                    'value' => $option['value'],
                    'action' => $option['action'],
                    'sort_order' => $option['sort_order'],
                ]);
            }

            return response()->json([
                'success' => 1,
                'message' => 'Chatbot started successfully',
                'data' => [
                    'conversation_id' => $conversation->conversation_id,
                    'message' => [
                        'id' => $message->id,
                        'sender_type' => $message->sender_type,
                        'message' => $message->message,
                        'message_type' => $message->message_type,
                        'options' => $message->options()->orderBy('sort_order')->get(),
                    ]
                ]
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => 0,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Send customer message
     */
    public function sendMessage(Request $request)
    {
        try {

            $user = auth('sanctum')->user();

            if (!$user) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Please Login'
                ], 401);
            }

            $request->validate([
                'conversation_id' => 'required',
                'message' => 'required|string|max:1000',
            ]);

            $conversation = ChatbotConversation::where(
                'conversation_id',
                $request->conversation_id
            )
                ->where('customer_id', $user->id)
                ->first();

            if (!$conversation) {

                return response()->json([
                    'success' => 0,
                    'message' => 'Conversation not found'
                ], 404);
            }

            DB::beginTransaction();

            /*
             * Save customer message
             */
            $customerMessage = ChatbotMessage::create([
                'conversation_id' => $conversation->id,
                'sender_type' => 'customer',
                'message' => $request->message,
                'message_type' => 'text',
            ]);

            /*
             * Find intent
             */
            $intent = $this->findIntent($request->message);

            /*
             * Find FAQ
             */
            $faq = $this->findFaq($request->message);

            /*
             * Generate bot response
             */
            $responseData = $this->generateResponse(
                $request->message,
                $intent,
                $faq,
                $conversation
            );

            /*
             * Save bot message
             */
            $botMessage = ChatbotMessage::create([
                'conversation_id' => $conversation->id,
                'sender_type' => 'bot',
                'message' => $responseData['message'],
                'message_type' => $responseData['message_type'] ?? 'text',
            ]);

            /*
             * Save options
             */
            if (!empty($responseData['options'])) {

                foreach ($responseData['options'] as $key => $option) {

                    ChatbotMessageOption::create([
                        'message_id' => $botMessage->id,
                        'title' => $option['title'],
                        'value' => $option['value'] ?? null,
                        'action' => $option['action'] ?? null,
                        'sort_order' => $key + 1,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => 1,
                'message' => 'Message sent successfully',
                'data' => [
                    'conversation_id' => $conversation->conversation_id,

                    'customer_message' => [
                        'id' => $customerMessage->id,
                        'sender_type' => 'customer',
                        'message' => $customerMessage->message,
                    ],

                    'bot_message' => [
                        'id' => $botMessage->id,
                        'sender_type' => 'bot',
                        'message' => $botMessage->message,
                        'message_type' => $botMessage->message_type,
                        'options' => $botMessage->options()
                            ->orderBy('sort_order')
                            ->get(),
                    ],

                    'intent' => $intent ? [
                        'id' => $intent->id,
                        'name' => $intent->name,
                        'slug' => $intent->slug,
                        'service' => $intent->service,
                    ] : null,

                    'recommendations' => $responseData['recommendations'] ?? [],
                ]
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => 0,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Find chatbot intent
     */
    private function findIntent($message)
    {
        $message = strtolower(trim($message));

        $intents = ChatbotIntent::where('status', 1)->get();

        foreach ($intents as $intent) {

            if (!$intent->keywords) {
                continue;
            }

            $keywords = is_array($intent->keywords)
                ? $intent->keywords
                : json_decode($intent->keywords, true);

            if (!is_array($keywords)) {
                $keywords = explode(',', $intent->keywords);
            }

            foreach ($keywords as $keyword) {

                $keyword = strtolower(trim($keyword));

                if ($keyword && str_contains($message, $keyword)) {
                    return $intent;
                }
            }
        }

        return null;
    }


    /**
     * Find FAQ
     */
    private function findFaq($message)
    {
        $message = strtolower(trim($message));

        $faqs = ChatbotFaq::where('status', 1)->get();

        foreach ($faqs as $faq) {

            /*
             * If FAQ has keywords
             */
            if (!empty($faq->keywords)) {

                $keywords = is_array($faq->keywords)
                    ? $faq->keywords
                    : json_decode($faq->keywords, true);

                if (!is_array($keywords)) {
                    $keywords = explode(',', $faq->keywords);
                }

                foreach ($keywords as $keyword) {

                    $keyword = strtolower(trim($keyword));

                    if ($keyword && str_contains($message, $keyword)) {
                        return $faq;
                    }
                }
            }

            /*
             * Question matching
             */
            if (
                !empty($faq->question) &&
                str_contains(
                    $message,
                    strtolower($faq->question)
                )
            ) {
                return $faq;
            }
        }

        return null;
    }


    /**
     * Generate chatbot response
     */
    private function generateResponse(
        $message,
        $intent,
        $faq,
        $conversation
    ) {

        /*
         * FAQ answer has highest priority
         */
        if ($faq) {

            return [
                'message' => $faq->answer,
                'message_type' => 'text',
                'options' => [
                    [
                        'title' => 'Find a Doctor',
                        'value' => 'doctor',
                        'action' => 'doctor',
                    ],
                    [
                        'title' => 'Book Lab Test',
                        'value' => 'lab_test',
                        'action' => 'diagnostic',
                    ],
                    [
                        'title' => 'Back to Main Menu',
                        'value' => 'main_menu',
                        'action' => 'main_menu',
                    ],
                ],
                'recommendations' => [],
            ];
        }


        /*
         * No intent
         */
        if (!$intent) {

            return [
                'message' => 'I can help you with doctors, hospitals, lab tests, medicines, ambulance services and your health records. Please tell me what you need help with.',
                'message_type' => 'text',
                'options' => [
                    [
                        'title' => 'Find a Doctor',
                        'value' => 'doctor',
                        'action' => 'doctor',
                    ],
                    [
                        'title' => 'Lab Tests',
                        'value' => 'lab_test',
                        'action' => 'diagnostic',
                    ],
                    [
                        'title' => 'Medicines',
                        'value' => 'medicine',
                        'action' => 'medicine',
                    ],
                    [
                        'title' => 'Ambulance',
                        'value' => 'ambulance',
                        'action' => 'ambulance',
                    ],
                ],
                'recommendations' => [],
            ];
        }


        /*
         * Intent based responses
         */
        switch ($intent->slug) {

            case 'pain':

                return [
                    'message' => 'I understand you are experiencing pain. Where exactly are you having pain?',
                    'message_type' => 'text',

                    'options' => [
                        [
                            'title' => 'Head',
                            'value' => 'head_pain',
                            'action' => 'intent',
                        ],
                        [
                            'title' => 'Chest',
                            'value' => 'chest_pain',
                            'action' => 'intent',
                        ],
                        [
                            'title' => 'Stomach',
                            'value' => 'stomach_pain',
                            'action' => 'intent',
                        ],
                        [
                            'title' => 'Back',
                            'value' => 'back_pain',
                            'action' => 'intent',
                        ],
                        [
                            'title' => 'Other',
                            'value' => 'other_pain',
                            'action' => 'intent',
                        ],
                    ],

                    'recommendations' => [],
                ];


            case 'headache':

                return [
                    'message' => 'For headache, I can help you find the right doctor. Would you like to consult a doctor?',
                    'message_type' => 'text',

                    'options' => [
                        [
                            'title' => 'Find a Doctor',
                            'value' => 'doctor',
                            'action' => 'doctor',
                        ],
                        [
                            'title' => 'View Hospitals',
                            'value' => 'hospital',
                            'action' => 'hospital',
                        ],
                        [
                            'title' => 'Back to Menu',
                            'value' => 'main_menu',
                            'action' => 'main_menu',
                        ],
                    ],

                    'recommendations' => [],
                ];


            case 'doctor':

                return [
                    'message' => 'Sure. I can help you find a doctor. Please select a specialization.',
                    'message_type' => 'action',

                    'options' => [
                        [
                            'title' => 'General Physician',
                            'value' => 'general_physician',
                            'action' => 'doctor',
                        ],
                        [
                            'title' => 'Cardiologist',
                            'value' => 'cardiologist',
                            'action' => 'doctor',
                        ],
                        [
                            'title' => 'Neurologist',
                            'value' => 'neurologist',
                            'action' => 'doctor',
                        ],
                        [
                            'title' => 'Orthopedic',
                            'value' => 'orthopedic',
                            'action' => 'doctor',
                        ],
                    ],

                    'recommendations' => [],
                ];


            case 'lab_test':

                return [
                    'message' => 'Sure. I can help you find a lab test. What type of test are you looking for?',
                    'message_type' => 'action',

                    'options' => [
                        [
                            'title' => 'Blood Test',
                            'value' => 'blood_test',
                            'action' => 'diagnostic',
                        ],
                        [
                            'title' => 'Urine Test',
                            'value' => 'urine_test',
                            'action' => 'diagnostic',
                        ],
                        [
                            'title' => 'Full Body Checkup',
                            'value' => 'full_body',
                            'action' => 'diagnostic',
                        ],
                    ],

                    'recommendations' => [],
                ];


            case 'ambulance':

                return [
                    'message' => 'I can help you request an ambulance. Would you like to request one now?',
                    'message_type' => 'action',

                    'options' => [
                        [
                            'title' => 'Request Ambulance',
                            'value' => 'request_ambulance',
                            'action' => 'ambulance',
                        ],
                        [
                            'title' => 'View Ambulance Types',
                            'value' => 'ambulance_types',
                            'action' => 'ambulance',
                        ],
                    ],

                    'recommendations' => [],
                ];


            case 'medicine':

                return [
                    'message' => 'I can help you find medicines. What would you like to do?',
                    'message_type' => 'action',

                    'options' => [
                        [
                            'title' => 'Search Medicines',
                            'value' => 'search_medicine',
                            'action' => 'medicine',
                        ],
                        [
                            'title' => 'My Medicine Orders',
                            'value' => 'medicine_orders',
                            'action' => 'medicine_orders',
                        ],
                    ],

                    'recommendations' => [],
                ];


            default:

                return [
                    'message' => 'I understand. Let me help you find the right healthcare service.',
                    'message_type' => 'text',

                    'options' => [
                        [
                            'title' => 'Find Doctor',
                            'value' => 'doctor',
                            'action' => 'doctor',
                        ],
                        [
                            'title' => 'Lab Test',
                            'value' => 'lab_test',
                            'action' => 'diagnostic',
                        ],
                        [
                            'title' => 'Ambulance',
                            'value' => 'ambulance',
                            'action' => 'ambulance',
                        ],
                    ],

                    'recommendations' => [],
                ];
        }
    }
    private function createRecommendations(
        $conversation,
        $customer,
        $type,
        $search = null
    ) {
        $recommendations = [];

        switch ($type) {

            /*
            |--------------------------------------------------------------------------
            | Doctors
            |--------------------------------------------------------------------------
            */
            case 'doctor':

                $query = Doctor::query()
                    ->where('status', 1);

                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('specialization', 'LIKE', "%{$search}%");
                    });
                }

                $doctors = $query
                    ->limit(5)
                    ->get();

                foreach ($doctors as $index => $doctor) {

                    $recommendations[] = ChatbotRecommendation::create([
                        'conversation_id' => $conversation->id,
                        'customer_id' => $customer->id,
                        'recommendation_type' => 'doctor',
                        'reference_id' => $doctor->id,
                        'title' => $doctor->name,
                        'description' => $doctor->specialization ?? 'Doctor',
                        'metadata' => [
                            'doctor_id' => $doctor->id,
                            'specialization' => $doctor->specialization ?? null,
                        ],
                        'sort_order' => $index + 1,
                    ]);
                }

                break;


            /*
            |--------------------------------------------------------------------------
            | Hospitals
            |--------------------------------------------------------------------------
            */
            case 'hospital':

                $hospitals = Hospital::where('status', 1)
                    ->limit(5)
                    ->get();

                foreach ($hospitals as $index => $hospital) {

                    $recommendations[] = ChatbotRecommendation::create([
                        'conversation_id' => $conversation->id,
                        'customer_id' => $customer->id,
                        'recommendation_type' => 'hospital',
                        'reference_id' => $hospital->id,
                        'title' => $hospital->hospital_name,
                        'description' => $hospital->address ?? '',
                        'metadata' => [
                            'hospital_id' => $hospital->id,
                            'city' => $hospital->city ?? null,
                            'state' => $hospital->state ?? null,
                        ],
                        'sort_order' => $index + 1,
                    ]);
                }

                break;


            /*
            |--------------------------------------------------------------------------
            | Diagnostics
            |--------------------------------------------------------------------------
            */
            case 'diagnostic':

                $diagnostics = Diagnostic::where('status', 1)
                    ->limit(5)
                    ->get();

                foreach ($diagnostics as $index => $diagnostic) {

                    $recommendations[] = ChatbotRecommendation::create([
                        'conversation_id' => $conversation->id,
                        'customer_id' => $customer->id,
                        'recommendation_type' => 'diagnostic',
                        'reference_id' => $diagnostic->id,
                        'title' => $diagnostic->name,
                        'description' => $diagnostic->address ?? '',
                        'metadata' => [
                            'diagnostic_id' => $diagnostic->id,
                        ],
                        'sort_order' => $index + 1,
                    ]);
                }

                break;


            /*
            |--------------------------------------------------------------------------
            | Medicines
            |--------------------------------------------------------------------------
            */
            case 'medicine':

                $medicines = Medicine::where('status', 1)
                    ->limit(5)
                    ->get();

                foreach ($medicines as $index => $medicine) {

                    $recommendations[] = ChatbotRecommendation::create([
                        'conversation_id' => $conversation->id,
                        'customer_id' => $customer->id,
                        'recommendation_type' => 'medicine',
                        'reference_id' => $medicine->id,
                        'title' => $medicine->name,
                        'description' => $medicine->description ?? '',
                        'metadata' => [
                            'medicine_id' => $medicine->id,
                        ],
                        'sort_order' => $index + 1,
                    ]);
                }

                break;


            /*
            |--------------------------------------------------------------------------
            | Ambulance
            |--------------------------------------------------------------------------
            */
            case 'ambulance':

                $ambulances = Ambulance::where('status', 1)
                    ->limit(5)
                    ->get();

                foreach ($ambulances as $index => $ambulance) {

                    $recommendations[] = ChatbotRecommendation::create([
                        'conversation_id' => $conversation->id,
                        'customer_id' => $customer->id,
                        'recommendation_type' => 'ambulance',
                        'reference_id' => $ambulance->id,
                        'title' => $ambulance->name ?? 'Ambulance',
                        'description' => $ambulance->description ?? '',
                        'metadata' => [
                            'ambulance_id' => $ambulance->id,
                        ],
                        'sort_order' => $index + 1,
                    ]);
                }

                break;
        }

        return $recommendations;
    }

    /**
     * Get all conversations of logged in customer
     */
    public function history(Request $request)
    {
        try {

            $user = auth('sanctum')->user();

            if (!$user) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Please Login'
                ], 401);
            }

            $conversations = ChatbotConversation::where(
                'customer_id',
                $user->id
            )
                ->latest()
                ->get();

            return response()->json([
                'success' => 1,
                'message' => 'Chatbot history fetched successfully',
                'data' => $conversations
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => 0,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Get complete messages of one conversation
     */
    public function conversationHistory($conversation_id)
    {
        try {

            $user = auth('sanctum')->user();

            if (!$user) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Please Login'
                ], 401);
            }

            $conversation = ChatbotConversation::where(
                'conversation_id',
                $conversation_id
            )
                ->where('customer_id', $user->id)
                ->first();

            if (!$conversation) {

                return response()->json([
                    'success' => 0,
                    'message' => 'Conversation not found'
                ], 404);
            }

            $messages = ChatbotMessage::where(
                'conversation_id',
                $conversation->id
            )
                ->with([
                    'options' => function ($query) {
                        $query->orderBy('sort_order');
                    }
                ])
                ->orderBy('created_at')
                ->get();

            $recommendations = ChatbotRecommendation::where(
                'conversation_id',
                $conversation->id
            )
                ->orderBy('sort_order')
                ->get();

            return response()->json([
                'success' => 1,
                'message' => 'Conversation history fetched successfully',

                'data' => [
                    'conversation' => $conversation,

                    'messages' => $messages,

                    'recommendations' => $recommendations,
                ]
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => 0,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}