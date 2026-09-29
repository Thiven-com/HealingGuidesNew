<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HealthCheckup;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HealthCheckupController extends Controller
{
    public function index()
    {
        $healthCheckups = HealthCheckup::latest()->paginate(10);

        return view(
            'admin.healthcheckups.index',
            compact('healthCheckups')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:health_checkups,slug',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'description' => 'nullable|string',
        ]);

        $data = [
            'name' => $validated['name'],
            'slug' => !empty($validated['slug'])
                ? Str::slug($validated['slug'])
                : Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store(
                'health_checkups',
                'public'
            );
        }

        HealthCheckup::create($data);

        return redirect()
            ->back()
            ->with('success', 'Health checkup added successfully.');
    }

    public function update(Request $request, $id)
    {
        $healthCheckup = HealthCheckup::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:health_checkups,slug,' . $id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'description' => 'nullable|string',
        ]);

        $healthCheckup->name = $validated['name'];

        $healthCheckup->slug = !empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['name']);

        $healthCheckup->description = $validated['description'] ?? null;

        if ($request->hasFile('image')) {

            if (
                !empty($healthCheckup->image) &&
                Storage::disk('public')->exists($healthCheckup->image)
            ) {
                Storage::disk('public')->delete($healthCheckup->image);
            }

            $healthCheckup->image = $request->file('image')->store(
                'health_checkups',
                'public'
            );
        }

        $healthCheckup->save();

        return redirect()
            ->back()
            ->with('success', 'Health checkup updated successfully.');
    }

    public function destroy($id)
    {
        $healthCheckup = HealthCheckup::findOrFail($id);

        if (
            !empty($healthCheckup->image) &&
            Storage::disk('public')->exists($healthCheckup->image)
        ) {
            Storage::disk('public')->delete($healthCheckup->image);
        }

        $healthCheckup->delete();

        return redirect()
            ->back()
            ->with('success', 'Health checkup deleted successfully.');
    }
}
