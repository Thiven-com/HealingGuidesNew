<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Diagnostic;
use App\Models\DiagnosticCategory;
use App\Models\DiagnosticLabTest;
use App\Models\Hospital;
use App\Models\LabTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DiagnosticController extends Controller
{
    public function index(Request $request)
    {
        $diagnostics = Diagnostic::query();

        // Search
        if ($request->filled('search')) {

            $search = trim($request->search);

            $diagnostics->where(function ($query) use ($search) {

                $query->where('diagnostic_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('diagnostic_code', 'LIKE', '%' . $search . '%')
                    ->orWhere('registration_number', 'LIKE', '%' . $search . '%')
                    ->orWhere('mobile', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%')
                    ->orWhere('city', 'LIKE', '%' . $search . '%');

            });
        }

        // Home Collection
        if ($request->filled('home_collection')) {

            $diagnostics->where(
                'home_collection',
                $request->home_collection
            );
        }

        // Status
        if ($request->filled('status')) {

            $diagnostics->where(
                'status',
                $request->status
            );
        }

        // Pagination
        $diagnostics = $diagnostics
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.diagnostics.index',
            compact('diagnostics')
        );
    }

    public function create()
    {

        $hospitals = Hospital::orderBy('hospital_name', 'asc')->get();
        $labTests = LabTest::orderBy('test_name', 'asc')->get();
        $diagnostic_categories = DiagnosticCategory::orderBy(
            'name',
            'asc'
        )->get();

        // No diagnostic exists yet, so there are no selected lab tests
        $diagnosticLabTests = collect();

        // Selected lab test IDs
        $selectedLabTests = [];

        return view('admin.diagnostics.create', compact(
            'hospitals',
            'labTests',
            'selectedLabTests',
            'diagnosticLabTests',
            'diagnostic_categories',
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hospital_id' => 'required|exists:hospitals,id',
            'diagnostic_category_id' => [
                'required',
                'exists:diagnostic_categories,id',
            ],
            'diagnostic_name' => 'required|max:255',
            'registration_number' => 'nullable|max:100',
            'email' => 'nullable|email',
            'mobile' => 'required|max:15',
            'phone' => 'nullable|max:20',
            'address' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'pincode' => 'required',
            'opening_time' => 'required',
            'closing_time' => 'required',
            'home_collection' => 'required',

            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',

            // Lab Tests
            'lab_test_ids' => 'nullable|array',
            'lab_test_ids.*' => 'exists:lab_tests,id',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Generate Diagnostic Code
        |--------------------------------------------------------------------------
        */

        $last = Diagnostic::latest()->first();

        if ($last) {
            $number = (int) substr($last->diagnostic_code, 3) + 1;
        } else {
            $number = 1;
        }

        /*
        |--------------------------------------------------------------------------
        | Create Diagnostic
        |--------------------------------------------------------------------------
        */

        $diagnostic = new Diagnostic();

        $diagnostic->diagnostic_code =
            'DGN' . str_pad($number, 4, '0', STR_PAD_LEFT);

        $diagnostic->hospital_id = $request->hospital_id;

        $diagnostic->diagnostic_name = $request->diagnostic_name;
        $diagnostic->diagnostic_category_id =
            $request->diagnostic_category_id;

        $diagnostic->slug = Str::slug($request->diagnostic_name);

        $diagnostic->registration_number =
            $request->registration_number;

        $diagnostic->email = $request->email;

        $diagnostic->mobile = $request->mobile;

        $diagnostic->phone = $request->phone;

        $diagnostic->address = $request->address;

        $diagnostic->country = $request->country;

        $diagnostic->state = $request->state;

        $diagnostic->city = $request->city;

        $diagnostic->pincode = $request->pincode;

        $diagnostic->latitude = $request->latitude;

        $diagnostic->longitude = $request->longitude;

        $diagnostic->opening_time = $request->opening_time;

        $diagnostic->closing_time = $request->closing_time;

        $diagnostic->home_collection = $request->home_collection;

        $diagnostic->status = $request->status ?? 1;


        /*
        |--------------------------------------------------------------------------
        | Logo Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {

            $file = $request->file('logo');

            $name = time() . '_logo.' .
                $file->getClientOriginalExtension();

            $file->move(
                public_path('uploads/diagnostics/logo'),
                $name
            );

            $diagnostic->logo =
                'uploads/diagnostics/logo/' . $name;
        }

        /*
        |--------------------------------------------------------------------------
        | Banner Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('banner')) {

            $file = $request->file('banner');

            $name = time() . '_banner.' .
                $file->getClientOriginalExtension();

            $file->move(
                public_path('uploads/diagnostics/banner'),
                $name
            );

            $diagnostic->banner =
                'uploads/diagnostics/banner/' . $name;
        }

        /*
        |--------------------------------------------------------------------------
        | Save Diagnostic
        |--------------------------------------------------------------------------
        */

        $diagnostic->save();

        /*
        |--------------------------------------------------------------------------
        | Store Selected Lab Tests
        | diagnostic_lab_tests table
        |--------------------------------------------------------------------------
        */

        $selectedLabTestIds = $request->input('lab_test_ids', []);

        foreach ($selectedLabTestIds as $labTestId) {

            DiagnosticLabTest::create([
                'diagnostic_id' => $diagnostic->id,
                'lab_test_id' => $labTestId,

                'price' => null,
                'offer_price' => null,
                'report_time' => null,
                'report_time_type' => null,

                'home_collection' => 1,
                'status' => 1,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.diagnostics.index')
            ->with('success', 'Diagnostic Created Successfully');
    }

    public function show($id)
    {
        $diagnostic = Diagnostic::findOrFail($id);

        $hospitals = Hospital::orderBy('hospital_name', 'asc')->get();

        // Get all records directly from diagnostic_lab_tests
        // for this diagnostic
        $diagnosticLabTests = DiagnosticLabTest::where(
            'diagnostic_id',
            $diagnostic->id
        )
            ->with('labTest')
            ->orderBy('id', 'desc')
            ->get();

        // Selected lab test IDs
        $selectedLabTests = $diagnosticLabTests
            ->pluck('lab_test_id')
            ->map(fn($id) => (int) $id)
            ->toArray();


        return view(
            'admin.diagnostics.show',
            compact(
                'diagnostic',
                'hospitals',
                'diagnosticLabTests',
                'selectedLabTests'
            )
        );
    }

    public function edit($id)
    {
        $diagnostic = Diagnostic::findOrFail($id);

        $hospitals = Hospital::orderBy('hospital_name', 'asc')->get();

        $labTests = LabTest::orderBy('test_name', 'asc')->get();

        $diagnostic_categories =
            DiagnosticCategory::orderBy(
                'name',
                'asc'
            )->get();

        $diagnosticLabTests = DiagnosticLabTest::where(
            'diagnostic_id',
            $diagnostic->id
        )
            ->get()
            ->keyBy('lab_test_id');

        // Get selected lab test IDs
        $selectedLabTests = $diagnosticLabTests
            ->keys()
            ->map(fn($id) => (int) $id)
            ->toArray();

        // Make sure it is always an array
        if (!is_array($selectedLabTests)) {
            $selectedLabTests = [];
        }

        return view('admin.diagnostics.edit', compact(
            'diagnostic',
            'hospitals',
            'labTests',
            'selectedLabTests',
            'diagnostic_categories',
        ));
    }

    public function update(Request $request, $id)
    {
        $diagnostic = Diagnostic::findOrFail($id);

        $request->validate([
            'hospital_id' => 'required|exists:hospitals,id',
            'diagnostic_name' => 'required|max:255',
            'diagnostic_category_id' => [
                'required',
                'exists:diagnostic_categories,id',
            ],
            'registration_number' => 'nullable|max:100',
            'email' => 'nullable|email',
            'mobile' => 'required|max:15',
            'phone' => 'nullable|max:20',
            'address' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'pincode' => 'required',
            'opening_time' => 'required',
            'closing_time' => 'required',
            'home_collection' => 'required',

            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',

            // Lab Tests
            'lab_test_ids' => 'nullable|array',
            'lab_test_ids.*' => 'exists:lab_tests,id',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Diagnostic Details
        |--------------------------------------------------------------------------
        */

        $diagnostic->diagnostic_name = $request->diagnostic_name;
        $diagnostic->diagnostic_category_id =
            $request->diagnostic_category_id;
        $diagnostic->hospital_id = $request->hospital_id;
        $diagnostic->slug = Str::slug($request->diagnostic_name);
        $diagnostic->registration_number = $request->registration_number;
        $diagnostic->email = $request->email;
        $diagnostic->mobile = $request->mobile;
        $diagnostic->phone = $request->phone;
        $diagnostic->address = $request->address;
        $diagnostic->country = $request->country;
        $diagnostic->state = $request->state;
        $diagnostic->city = $request->city;
        $diagnostic->pincode = $request->pincode;
        $diagnostic->latitude = $request->latitude;
        $diagnostic->longitude = $request->longitude;
        $diagnostic->opening_time = $request->opening_time;
        $diagnostic->closing_time = $request->closing_time;
        $diagnostic->home_collection = $request->home_collection;
        $diagnostic->status = $request->status ?? 1;

        /*
        |--------------------------------------------------------------------------
        | Logo
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {

            if (
                $diagnostic->logo &&
                File::exists(public_path($diagnostic->logo))
            ) {
                File::delete(public_path($diagnostic->logo));
            }

            $file = $request->file('logo');

            $name = time() . '_logo.' .
                $file->getClientOriginalExtension();

            $file->move(
                public_path('uploads/diagnostics/logo'),
                $name
            );

            $diagnostic->logo =
                'uploads/diagnostics/logo/' . $name;
        }

        /*
        |--------------------------------------------------------------------------
        | Banner
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('banner')) {

            if (
                $diagnostic->banner &&
                File::exists(public_path($diagnostic->banner))
            ) {
                File::delete(public_path($diagnostic->banner));
            }

            $file = $request->file('banner');

            $name = time() . '_banner.' .
                $file->getClientOriginalExtension();

            $file->move(
                public_path('uploads/diagnostics/banner'),
                $name
            );

            $diagnostic->banner =
                'uploads/diagnostics/banner/' . $name;
        }

        /*
        |--------------------------------------------------------------------------
        | Save Diagnostic
        |--------------------------------------------------------------------------
        */

        $diagnostic->save();

        /*
        |--------------------------------------------------------------------------
        | Update Diagnostic Lab Tests
        |--------------------------------------------------------------------------
        */

        $selectedLabTestIds = $request->input('lab_test_ids', []);

        /*
        | Delete old lab test mappings
        */
        DiagnosticLabTest::where(
            'diagnostic_id',
            $diagnostic->id
        )->delete();

        /*
        | Insert currently selected lab tests
        */
        foreach ($selectedLabTestIds as $labTestId) {

            DiagnosticLabTest::create([
                'diagnostic_id' => $diagnostic->id,
                'lab_test_id' => $labTestId,

                // Default values
                'price' => null,
                'offer_price' => null,
                'report_time' => null,
                'report_time_type' => null,
                'home_collection' => 1,
                'status' => 1,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.diagnostics.index')
            ->with('success', 'Diagnostic Updated Successfully');
    }

    public function destroy($id)
    {
        $exists = DiagnosticLabTest::where('diagnostic_id', $id)->exists();

        if ($exists) {
            return back()->with('error', 'Cannot delete. Diagnostic contains Lab Tests.');
        }

        $diagnostic = Diagnostic::findOrFail($id);

        if ($diagnostic->logo && File::exists(public_path($diagnostic->logo))) {
            File::delete(public_path($diagnostic->logo));
        }

        if ($diagnostic->banner && File::exists(public_path($diagnostic->banner))) {
            File::delete(public_path($diagnostic->banner));
        }

        $diagnostic->delete();

        return back()->with('success', 'Diagnostic Deleted Successfully');
    }

    public function status($id)
    {
        $diagnostic = Diagnostic::findOrFail($id);

        $diagnostic->status = $diagnostic->status ? 0 : 1;

        $diagnostic->save();

        return back()->with('success', 'Status Updated Successfully');
    }
}