<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HospitalTieup;
use App\Models\Tieup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TieupController extends Controller
{
    public function index()
    {
        $tieups = Tieup::latest()->paginate(10);

        return view('admin.tieups.index', compact('tieups'));
    }

    /**
     * Store a new tieup.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tieups,name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
        ]);

        $slug = $this->generateUniqueSlug($request->name);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('tieups', 'public');
        }

        Tieup::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()
            ->route('admin.tieups.index')
            ->with('success', 'Tieup added successfully.');
    }

    /**
     * Show edit page.
     */
    public function edit($id)
    {
        $tieup = Tieup::findOrFail($id);

        return view('admin.tieups.edit', compact('tieup'));
    }

    /**
     * Update tieup.
     */
    public function update(Request $request, $id)
    {
        $tieup = Tieup::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:tieups,name,' . $tieup->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
        ]);

        $slug = $this->generateUniqueSlug(
            $request->name,
            $tieup->id
        );

        $imagePath = $tieup->image;

        if ($request->hasFile('image')) {

            if (
                !empty($tieup->image) &&
                Storage::disk('public')->exists($tieup->image)
            ) {
                Storage::disk('public')->delete($tieup->image);
            }

            $imagePath = $request->file('image')
                ->store('tieups', 'public');
        }

        $tieup->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()
            ->route('admin.tieups.index')
            ->with('success', 'Tieup updated successfully.');
    }

    /**
     * Delete tieup.
     */
    public function destroy($id)
    {
        $tieup = Tieup::findOrFail($id);

        if (
            !empty($tieup->image) &&
            Storage::disk('public')->exists($tieup->image)
        ) {
            Storage::disk('public')->delete($tieup->image);
        }

        $tieup->delete();

        return redirect()
            ->route('admin.tieups.index')
            ->with('success', 'Tieup deleted successfully.');
    }

    /**
     * Generate unique slug.
     */
    private function generateUniqueSlug($name, $id = null)
    {
        $slug = Str::slug($name);

        $originalSlug = $slug;
        $count = 1;

        while (
            Tieup::where('slug', $slug)
                ->when($id, function ($query) use ($id) {
                    $query->where('id', '!=', $id);
                })
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function tieupsDetails($tieup)
    {
        $tieup = Tieup::findOrFail($tieup);

        $hospitalTieups = HospitalTieup::with('hospital')
            ->where('tieup_id', $tieup->id)
            ->latest()
            ->get();

        return view(
            'admin.hospitals.tieups-details',
            compact('tieup', 'hospitalTieups')
        );
    }
}
