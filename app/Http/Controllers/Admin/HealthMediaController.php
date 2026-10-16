<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HealthMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class HealthMediaController extends Controller
{
     /**
     * Display all health media.
     */
    public function index(Request $request)
    {
        $query = HealthMedia::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $healthMedia = $query->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.health-media.index', compact('healthMedia'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.health-media.create');
    }

    /**
     * Store health media.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $imagePath = $request->file('image')
            ->store('health-media', 'public');

        HealthMedia::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.health-media.index')
            ->with('success', 'Health media added successfully.');
    }

    /**
     * Display a health media record.
     */
    public function show(HealthMedia $healthMedia)
    {
        return view('admin.health-media.show', compact('healthMedia'));
    }

    /**
     * Show edit form.
     */
    public function edit(HealthMedia $healthMedia)
    {
        return view('admin.health-media.edit', compact('healthMedia'));
    }

    /**
     * Update health media.
     */
    public function update(Request $request, HealthMedia $healthMedia)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
        ];

        if ($request->hasFile('image')) {
            $newImagePath = $request->file('image')
                ->store('health-media', 'public');

            if ($healthMedia->image) {
                Storage::disk('public')->delete($healthMedia->image);
            }

            $data['image'] = $newImagePath;
        }

        $healthMedia->update($data);

        return redirect()
            ->route('admin.health-media.index')
            ->with('success', 'Health media updated successfully.');
    }

    /**
     * Delete health media.
     */
    public function destroy(HealthMedia $healthMedia)
    {
        if ($healthMedia->image) {
            Storage::disk('public')->delete($healthMedia->image);
        }

        $healthMedia->delete();

        return redirect()
            ->route('admin.health-media.index')
            ->with('success', 'Health media deleted successfully.');
    }
}
