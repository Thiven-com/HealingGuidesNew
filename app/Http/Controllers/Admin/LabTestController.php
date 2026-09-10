<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LabTest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class LabTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $labTests = LabTest::query();

        // Search
        if ($request->filled('search')) {

            $search = trim($request->search);

            $labTests->where(function ($query) use ($search) {

                $query->where('test_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('test_code', 'LIKE', '%' . $search . '%')
                    ->orWhere('sample_type', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%');

            });
        }

        // Fasting Required
        if ($request->filled('fasting_required')) {

            $labTests->where(
                'fasting_required',
                $request->fasting_required
            );
        }

        // Home Collection
        if ($request->filled('home_collection')) {

            $labTests->where(
                'home_collection',
                $request->home_collection
            );
        }

        // Status
        if ($request->filled('status')) {

            $labTests->where(
                'status',
                $request->status
            );
        }

        // Pagination
        $labTests = $labTests
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.lab-tests.index',
            compact('labTests')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.lab-tests.create');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'test_name' => 'required|string|max:255|unique:lab_tests,test_name',
            'description' => 'nullable|string',
            'sample_type' => 'required|string|max:255',
            'preparation' => 'nullable|string',
            'report_time' => 'required|numeric|min:1',
            'report_time_type' => 'required|in:Hours,Days',
            'fasting_required' => 'required|boolean',
            'home_collection' => 'required|boolean',
            'status' => 'required|boolean',
        ]);
        $imagePath = null;

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $imageName = time() . '_' . Str::slug($request->test_name) . '.' . $image->getClientOriginalExtension();

            $destination = public_path('uploads/lab-tests');

            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            $image->move($destination, $imageName);

            $imagePath = 'uploads/lab-tests/' . $imageName;
        }
        // Generate Test Code
        $lastTest = LabTest::latest()->first();

        if ($lastTest && $lastTest->test_code) {

            $number = (int) substr($lastTest->test_code, 2);

            $number++;

        } else {

            $number = 1;

        }

        $testCode = 'LT' . str_pad($number, 5, '0', STR_PAD_LEFT);

        // Create Lab Test
        LabTest::create([

            'test_name' => $request->test_name,

            'test_code' => $testCode,

            'slug' => Str::slug($request->test_name),

            'description' => $request->description,

            'sample_type' => $request->sample_type,

            'preparation' => $request->preparation,

            'report_time' => $request->report_time,

            'report_time_type' => $request->report_time_type,

            'fasting_required' => $request->fasting_required,

            'home_collection' => $request->home_collection,

            'status' => $request->status,
            'image' => $imagePath,

        ]);

        return redirect()
            ->route('admin.lab-tests.index')
            ->with('success', 'Lab Test Created Successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $labTest = LabTest::findOrFail($id);

        return view('admin.lab-tests.show', compact('labTest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $labTest = LabTest::findOrFail($id);

        return view('admin.lab-tests.edit', compact('labTest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $labTest = LabTest::findOrFail($id);

        $request->validate([
            'test_name' => 'required|string|max:255|unique:lab_tests,test_name,' . $labTest->id,
            'description' => 'nullable|string',
            'sample_type' => 'required|string|max:255',
            'preparation' => 'nullable|string',
            'report_time' => 'required|numeric|min:1',
            'report_time_type' => 'required|in:Hours,Days',
            'fasting_required' => 'required|boolean',
            'home_collection' => 'required|boolean',
            'status' => 'required|boolean',
        ]);
        $imagePath = $labTest->image;

        if ($request->hasFile('image')) {

            if ($labTest->image && File::exists(public_path($labTest->image))) {
                File::delete(public_path($labTest->image));
            }

            $image = $request->file('image');

            $imageName = time() . '_' . Str::slug($request->test_name) . '.' . $image->getClientOriginalExtension();

            $destination = public_path('uploads/lab-tests');

            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            $image->move($destination, $imageName);

            $imagePath = 'uploads/lab-tests/' . $imageName;
        }

        $labTest->update([

            'test_name' => $request->test_name,

            'slug' => Str::slug($request->test_name),

            'description' => $request->description,

            'sample_type' => $request->sample_type,

            'preparation' => $request->preparation,

            'report_time' => $request->report_time,

            'report_time_type' => $request->report_time_type,

            'fasting_required' => $request->fasting_required,

            'home_collection' => $request->home_collection,

            'status' => $request->status,
            'image' => $imagePath,

        ]);

        return redirect()
            ->route('admin.lab-tests.index')
            ->with('success', 'Lab Test Updated Successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $labTest = LabTest::findOrFail($id);

        $labTest->delete();

        return redirect()
            ->route('admin.lab-tests.index')
            ->with('success', 'Lab Test Deleted Successfully.');
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function status(string $id)
    {
        $labTest = LabTest::findOrFail($id);

        $labTest->status = $labTest->status ? 0 : 1;

        $labTest->save();

        return redirect()
            ->route('admin.lab-tests.index')
            ->with('success', 'Lab Test Status Updated Successfully.');
    }
}