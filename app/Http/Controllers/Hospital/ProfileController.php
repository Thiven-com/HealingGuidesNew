<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Hospital Profile
     */
    public function index()
    {
        $hospital = Auth::guard('hospital')->user();

        return view(
            'hospital.profile',
            compact('hospital')
        );
    }


    /**
     * Update Hospital Profile
     */
    public function update(Request $request)
    {
        $hospital = Auth::guard('hospital')->user();

        $validated = $request->validate([
            'hospital_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'about' => 'nullable|string',
        ]);

        $hospital->update($validated);

        return back()->with(
            'success',
            'Hospital profile updated successfully.'
        );
    }
}