<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileCollection;
use App\Models\AppNotification;
use App\Models\FamilyMember;
use App\Models\Insurance;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    //
    public function profile()
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        return response()->json([
            'success' => 1,
            'data' => new ProfileCollection(collect([$user])),
            'message' => 'User fetched successfully'
        ]);
    }
    public function updateProfile(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $user->id,
            'alternate_mobile' => 'nullable|digits:10',
            'gender' => 'nullable',
            'dob' => 'nullable|date',
            'blood_group' => 'nullable|string|max:10',
            'height' => 'nullable|string|max:20',
            'weight' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:255',
            'aadhaar_no' => 'nullable|digits:12',
            'address' => 'nullable|string',
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'pincode' => 'nullable|digits:6',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_mobile' => 'nullable|digits:10',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }
        $isFirstNameUpdate = empty($user->name)
            && $request->filled('name');


        $user->fill($request->only($user->getFillable()));
        if ($request->filled('dob')) {
            $user->age = Carbon::parse($request->dob)->age;
        }
        $user->country = $request->input('country') ?? "India";
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imageName = 'customer_' . time() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('uploads/customers'), $imageName);

            $user->photo = 'uploads/customers/' . $imageName;
        }

        $user->save();
        if ($isFirstNameUpdate) {
            try {

                $data = $this->sendWhatsAppMessage(
                    $user->mobile,
                    'customer_welcome',
                    [
                        'field_1' => $user->name ?? "User",
                    ],
                    'https://healingguides.in/logo/logo.png'
                );

                // $whatsappService = new WhatsAppService();

                // $result = $whatsappService->sendTemplateMessage($data);

                // Log::info($result);

            } catch (\Throwable $e) {

                Log::error('WhatsApp send failed: ' . $e->getMessage());
            }
        }
        return response()->json([
            'success' => 1,
            'data' => new ProfileCollection(collect([$user])),
            'message' => 'Profile updated successfully'
        ]);
    }
    public function storeFamilyMember(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'relationship' => 'required|string',
            'gender' => 'required|string',
            'dob' => 'nullable|date',
            'blood_group' => 'nullable|string',
            'height' => 'nullable|string',
            'weight' => 'nullable|string',
            'occupation' => 'nullable|string',
            'aadhaar_no' => 'nullable|string',
            'photo' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $familyMember = FamilyMember::create([
            'customer_id' => $user->id,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'relationship' => $request->relationship,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'age' => $request->filled('dob') ? Carbon::parse($request->dob)->age : null,
            'blood_group' => $request->blood_group,
            'height' => $request->height,
            'weight' => $request->weight,
            'occupation' => $request->occupation,
            'aadhaar_no' => $request->aadhaar_no,
            'photo' => $request->photo,
            'status' => 1,
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Family member added successfully',
            'data' => $familyMember
        ]);
    }

    public function updateFamilyMember(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:family_members,id',
            'name' => 'required|string',
            'relationship' => 'required|string',
            'gender' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $familyMember = FamilyMember::find($request->id);
        if (!isset($familyMember->id)) {
            return response()->json([
                'success' => 0,
                'message' => 'Details Not Found'
            ]);
        }
        $familyMember->update([
            'name' => $request->name,
            'relationship' => $request->relationship,
            'gender' => $request->gender,
            'mobile' => $request->mobile,
            'dob' => $request->dob,
            'age' => $request->filled('dob') ? Carbon::parse($request->dob)->age : null,
            'blood_group' => $request->blood_group,
            'height' => $request->height,
            'weight' => $request->weight,
            'occupation' => $request->occupation,
            'aadhaar_no' => $request->aadhaar_no,
            'photo' => $request->photo,
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Family member updated successfully',
            'data' => $familyMember
        ]);
    }

    public function deleteFamilyMember(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:family_members,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $familyMember = FamilyMember::find($request->id);

        if (!$familyMember) {
            return response()->json([
                'success' => 0,
                'message' => 'Family member not found'
            ]);
        }

        // Optional: Ensure the logged-in customer owns this family member
        if (auth('sanctum')->check() && $familyMember->customer_id != auth('sanctum')->id()) {
            return response()->json([
                'success' => 0,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Delete photo if stored locally (optional)
        if ($familyMember->photo && file_exists(public_path($familyMember->photo))) {
            @unlink(public_path($familyMember->photo));
        }

        $familyMember->delete();

        return response()->json([
            'success' => 1,
            'message' => 'Family member deleted successfully'
        ]);
    }

    /**
     * Get Customer Notifications
     */
    public function notifications(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $notifications = AppNotification::where('notifiable_type', 'customer')
            ->where('notifiable_id', $user->id)
            ->where('status', 1)
            ->orderByDesc('created_at')
            ->inRandomOrder()->take(20)->get();

        $data = $notifications->map(function ($notification) {

            return [
                'id' => $notification->id,

                'type' => $notification->type,

                'title' => $notification->title,

                'message' => $notification->message,

                'reference_type' => $notification->reference_type,

                'reference_id' => $notification->reference_id,

                'action' => $notification->action,

                'data' => $notification->data,

                'is_read' => (bool) $notification->is_read,

                'read_at' => $notification->read_at,

                'created_at' => $notification->created_at,
            ];
        });

        return response()->json([
            'success' => 1,
            'message' => 'Notifications fetched successfully.',
            'data' => $data,
            'unread_count' => AppNotification::where('notifiable_type', 'customer')
                ->where('notifiable_id', $user->id)
                ->where('status', 1)
                ->where('is_read', 0)
                ->count()
        ]);
    }

    public function addInsurance(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [

            'insurance_provider' => 'required|string|max:255',

            'policy_number' => 'required|string|max:255',

            'policy_type' => 'nullable|string|max:255',

            'policy_holder_name' => 'required|string|max:255',

            'member_id' => 'nullable|string|max:255',

            'coverage_amount' => 'nullable|numeric|min:0',

            'start_date' => 'nullable|date',

            'expiry_date' => 'nullable|date|after_or_equal:start_date',

            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',

            'notes' => 'nullable|string',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        $document = null;

        if ($request->hasFile('document')) {

            $file = $request->file('document');

            $document = $file->store(
                'insurances',
                'public'
            );
        }

        $status = 'active';

        if ($request->expiry_date) {

            if (Carbon::parse($request->expiry_date)->lt(now()->startOfDay())) {
                $status = 'expired';
            }
        }

        $insurance = Insurance::create([

            'customer_id' => $user->id,

            'insurance_provider' => $request->insurance_provider,

            'policy_number' => $request->policy_number,

            'policy_type' => $request->policy_type,

            'policy_holder_name' => $request->policy_holder_name,

            'member_id' => $request->member_id,

            'coverage_amount' => $request->coverage_amount,

            'start_date' => $request->start_date,

            'expiry_date' => $request->expiry_date,

            'document' => $document,

            'notes' => $request->notes,

            'status' => $status,
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Insurance added successfully.',
            'data' => [
                'id' => $insurance->id,
                'insurance_provider' => $insurance->insurance_provider,
                'policy_number' => $insurance->policy_number,
                'policy_type' => $insurance->policy_type,
                'policy_holder_name' => $insurance->policy_holder_name,
                'member_id' => $insurance->member_id,
                'coverage_amount' => $insurance->coverage_amount,
                'start_date' => $insurance->start_date,
                'expiry_date' => $insurance->expiry_date,
                'document' => $insurance->document
                    ? asset($insurance->document)
                    : null,
                'notes' => $insurance->notes,
                'status' => $insurance->status,
                'created_at' => $insurance->created_at,
            ]
        ]);
    }
    public function insurances(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $insurances = Insurance::where('customer_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        $data = $insurances->map(function ($insurance) {

            $status = $insurance->status;

            if (
                $insurance->expiry_date &&
                Carbon::parse($insurance->expiry_date)->lt(now()->startOfDay())
            ) {
                $status = 'expired';
            }

            return [
                'id' => $insurance->id,

                'insurance_provider' => $insurance->insurance_provider,

                'policy_number' => $insurance->policy_number,

                'policy_type' => $insurance->policy_type,

                'policy_holder_name' => $insurance->policy_holder_name,

                'member_id' => $insurance->member_id,

                'coverage_amount' => $insurance->coverage_amount,

                'start_date' => $insurance->start_date
                    ? $insurance->start_date->format('Y-m-d')
                    : null,

                'expiry_date' => $insurance->expiry_date
                    ? $insurance->expiry_date->format('Y-m-d')
                    : null,

                'document' => $insurance->document
                    ? asset($insurance->document)
                    : null,

                'notes' => $insurance->notes,

                'status' => $status,

                'created_at' => $insurance->created_at,
            ];
        });

        return response()->json([
            'success' => 1,
            'message' => 'Insurance details fetched successfully.',
            'data' => $data,
        ]);
    }
    public function insuranceDetails(Request $request, $id)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $insurance = Insurance::where('id', $id)
            ->where('customer_id', $user->id)
            ->first();

        if (!$insurance) {
            return response()->json([
                'success' => 0,
                'message' => 'Insurance details not found.'
            ]);
        }

        $status = $insurance->status;

        if (
            $insurance->expiry_date &&
            Carbon::parse($insurance->expiry_date)->lt(now()->startOfDay())
        ) {
            $status = 'expired';
        }

        return response()->json([
            'success' => 1,
            'message' => 'Insurance details fetched successfully.',
            'data' => [

                'id' => $insurance->id,

                'insurance_provider' => $insurance->insurance_provider,

                'policy_number' => $insurance->policy_number,

                'policy_type' => $insurance->policy_type,

                'policy_holder_name' => $insurance->policy_holder_name,

                'member_id' => $insurance->member_id,

                'coverage_amount' => $insurance->coverage_amount,

                'start_date' => $insurance->start_date
                    ? $insurance->start_date->format('Y-m-d')
                    : null,

                'expiry_date' => $insurance->expiry_date
                    ? $insurance->expiry_date->format('Y-m-d')
                    : null,

                'document' => $insurance->document
                    ? asset($insurance->document)
                    : null,

                'notes' => $insurance->notes,

                'status' => $status,

                'created_at' => $insurance->created_at,
                'updated_at' => $insurance->updated_at,
            ]
        ]);
    }
    public function deleteInsurance(Request $request, $id)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $insurance = Insurance::where('id', $id)
            ->where('customer_id', $user->id)
            ->first();

        if (!$insurance) {
            return response()->json([
                'success' => 0,
                'message' => 'Insurance details not found.'
            ]);
        }

        if ($insurance->document) {
            Storage::disk('public')->delete($insurance->document);
        }

        $insurance->delete();

        return response()->json([
            'success' => 1,
            'message' => 'Insurance deleted successfully.'
        ]);
    }

    private function sendWhatsAppMessage($cust_mobile, $templateName, array $fields = [], $headerImage = null)
    {
        return [
            "from_phone_number_id" => "1219830927885128",
            "phone_number" => '91' . $cust_mobile,
            "template_name" => $templateName,
            "template_language" => "en",
            "header_image" => $headerImage ?? '',
            "field_1" => $fields['field_1'] ?? '',
            "field_2" => $fields['field_2'] ?? '',
            "field_3" => $fields['field_3'] ?? '',
            "field_4" => $fields['field_4'] ?? '',
            "field_5" => $fields['field_5'] ?? '',

            "button_0" => $fields['field_1'] ?? '',
            "copy_code" => $fields['field_1'] ?? '',
        ];
    }
}
