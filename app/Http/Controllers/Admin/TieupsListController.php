<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TieupsList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TieupsListController extends Controller
{
    /**
     * Display all tie-ups.
     */
    public function index()
    {
        $tieups = TieupsList::latest()->paginate(10);

        return view(
            'admin.hospitals.tieups-list',
            compact('tieups')
        );
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.hospitals.tieups-list.create');
    }

    /**
     * Store tie-up.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'hospital_id' => 'required|exists:hospitals,id',
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'description' => 'nullable|string',
        ]);

        $data = [
            'hospital_id' => $validated['hospital_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store(
                'hospital_tieups',
                'public'
            );
        }

        $tieupList = TieupsList::create($data);

        return redirect()
            ->back()
            ->with('success', 'Hospital tie-up added successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $tieup = TieupsList::findOrFail($id);

        return view(
            'admin.hospitals.tieups-list.edit',
            compact('tieup')
        );
    }

    /**
     * Update tie-up.
     */
    public function update(Request $request, $id)
    {
        $tieupList = TieupsList::findOrFail($id);

        $validated = $request->validate([
            'hospital_id' => 'required|exists:hospitals,id',
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'description' => 'nullable|string',
        ]);

        $data = [
            'hospital_id' => $validated['hospital_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ];

        // Upload new image
        if ($request->hasFile('image')) {

            // Delete old image
            if (
                !empty($tieupList->image) &&
                Storage::disk('public')->exists($tieupList->image)
            ) {
                Storage::disk('public')->delete($tieupList->image);
            }

            // Store new image
            $data['image'] = $request->file('image')->store(
                'hospital_tieups',
                'public'
            );
        }

        $tieupList->update($data);

        return redirect()
            ->back()
            ->with('success', 'Hospital tie-up updated successfully.');
    }

    /**
     * Delete tie-up.
     */
    public function destroy($id)
    {
        $tieup = TieupsList::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Delete Image
        |--------------------------------------------------------------------------
        */

        if (
            !empty($tieup->image) &&
            Storage::disk('public')->exists($tieup->image)
        ) {
            Storage::disk('public')->delete($tieup->image);
        }

        $tieup->delete();

        return redirect()
            ->back()
            ->with('success', 'Hospital tie-up deleted successfully.');
    }

}