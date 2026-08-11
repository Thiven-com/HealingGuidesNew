<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    /**
     * Get customer addresses
     */
    public function addresses(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $addresses = Address::where('customer_id', $user->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => 1,
            'message' => 'Addresses fetched successfully',
            'data' => $addresses
        ], 200);
    }

    /**
     * Get single address
     */
    public function address(Request $request, $id)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $address = Address::where('id', $id)
            ->where('customer_id', $user->id)
            ->first();

        if (!$address) {
            return response()->json([
                'success' => 0,
                'message' => 'Address not found'
            ], 404);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Address fetched successfully',
            'data' => $address
        ], 200);
    }

    /**
     * Add address
     */
    public function addAddress(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'address' => 'required|string',
            'landmark' => 'nullable|string',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ]);
        }

        $address = Address::create([
            'customer_id' => $user->id,
            'address' => $request->address,
            'landmark' => $request->landmark,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country ?? 'India',
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Address added successfully',
            'data' => $address
        ], 201);
    }

    /**
     * Edit address
     */
    public function editAddress(Request $request, $id)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $address = Address::where('id', $id)
            ->where('customer_id', $user->id)
            ->first();

        if (!$address) {
            return response()->json([
                'success' => 0,
                'message' => 'Address not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'address' => 'required|string',
            'landmark' => 'nullable|string',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ]);
        }

        $address->update([
            'address' => $request->address,
            'landmark' => $request->landmark,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country ?? 'India',
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Address updated successfully',
            'data' => $address->fresh()
        ], 200);
    }
}