<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hospital;
use App\Models\HospitalEmergencyConnect;

class HospitalEmergencyConnectController extends Controller
{
    public function index(Hospital $hospital)
    {
        $emergencyConnects = HospitalEmergencyConnect::where(
            'hospital_id',
            $hospital->id
        )
            ->latest()
            ->get();

        return view(
            'admin.hospitals.emergency-connect',
            compact(
                'hospital',
                'emergencyConnects'
            )
        );
    }

    public function show(Hospital $hospital, $slug)
    {
        $emergencyConnects = HospitalEmergencyConnect::where(
            'hospital_id',
            $hospital->id
        )
            ->where('slug', $slug)
            ->latest()
            ->get();

        return view(
            'admin.hospitals.emergency-connect',
            compact(
                'hospital',
                'slug',
                'emergencyConnects'
            )
        );
    }


    /**
     * Store emergency contact
     */
    public function store(Request $request, Hospital $hospital)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|in:front-office,diagnostics,room-service,ambulance',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'designation' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
            'contact_number' => 'nullable|string|max:20',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {

            $uploadPath = public_path('uploads/emergency-connect');

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $image = $request->file('image');

            $imageName = time() . '_' . $image->getClientOriginalName();

            $image->move($uploadPath, $imageName);

            $imagePath = 'uploads/emergency-connect/' . $imageName;
        }

        HospitalEmergencyConnect::create([
            'hospital_id' => $hospital->id,
            'slug' => $request->slug,
            'name' => $request->name,
            'image' => $imagePath,
            'designation' => $request->designation,
            'department' => $request->department,
            'whatsapp_number' => $request->whatsapp_number,
            'contact_number' => $request->contact_number,
        ]);

        return redirect()
            ->route('admin.emergency-connect.show', [
                'hospital' => $hospital->id,
                'slug' => $request->slug,
            ])
            ->with('success', 'Emergency contact added successfully.');
    }

    public function update(Request $request, HospitalEmergencyConnect $emergencyConnect)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'designation' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
            'contact_number' => 'nullable|string|max:20',
        ]);

        $data = [
            'name' => $request->name,
            'designation' => $request->designation,
            'department' => $request->department,
            'whatsapp_number' => $request->whatsapp_number,
            'contact_number' => $request->contact_number,
        ];

        if ($request->hasFile('image')) {

            if (
                $emergencyConnect->image &&
                file_exists(public_path($emergencyConnect->image))
            ) {
                unlink(public_path($emergencyConnect->image));
            }

            $uploadPath = public_path('uploads/emergency-connect');

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $image = $request->file('image');

            $imageName = time() . '_' . $image->getClientOriginalName();

            $image->move($uploadPath, $imageName);

            $data['image'] =
                'uploads/emergency-connect/' . $imageName;
        }

        $emergencyConnect->update($data);

        return redirect()
            ->route(
                'admin.emergency-connect.index',
                $emergencyConnect->hospital_id
            )
            ->with(
                'success',
                'Emergency contact updated successfully.'
            );
    }

    public function destroy(HospitalEmergencyConnect $emergencyConnect)
    {
        $hospitalId = $emergencyConnect->hospital_id;
        $slug = $emergencyConnect->slug;

        if (
            $emergencyConnect->image &&
            file_exists(public_path($emergencyConnect->image))
        ) {
            unlink(public_path($emergencyConnect->image));
        }

        $emergencyConnect->delete();

        return redirect()
            ->route('admin.emergency-connect.show', [
                'hospital' => $hospitalId,
                'slug' => $slug,
            ])
            ->with(
                'success',
                'Emergency contact deleted successfully.'
            );
    }
}
