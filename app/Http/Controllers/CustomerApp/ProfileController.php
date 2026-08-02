<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileCollection;
use App\Models\FamilyMember;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
}
