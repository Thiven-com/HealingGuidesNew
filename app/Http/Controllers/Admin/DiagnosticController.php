<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Diagnostic;
use App\Models\DiagnosticLabTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DiagnosticController extends Controller
{
    public function index()
    {
        $diagnostics = Diagnostic::latest()->paginate(15);

        return view('admin.diagnostics.index', compact('diagnostics'));
    }

    public function create()
    {
        return view('admin.diagnostics.create');
    }

    public function store(Request $request)
    {
        $request->validate([
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
        ]);

        $last = Diagnostic::latest()->first();

        if ($last) {
            $number = (int) substr($last->diagnostic_code, 3) + 1;
        } else {
            $number = 1;
        }

        $diagnostic = new Diagnostic();

        $diagnostic->diagnostic_code = 'DGN' . str_pad($number, 4, '0', STR_PAD_LEFT);

        $diagnostic->diagnostic_name = $request->diagnostic_name;

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

        if ($request->hasFile('logo')) {

            $file = $request->file('logo');

            $name = time() . '_logo.' . $file->getClientOriginalExtension();

            $file->move(public_path('uploads/diagnostics/logo'), $name);

            $diagnostic->logo = 'uploads/diagnostics/logo/' . $name;
        }

        if ($request->hasFile('banner')) {

            $file = $request->file('banner');

            $name = time() . '_banner.' . $file->getClientOriginalExtension();

            $file->move(public_path('uploads/diagnostics/banner'), $name);

            $diagnostic->banner = 'uploads/diagnostics/banner/' . $name;
        }

        $diagnostic->save();

        return redirect()->route('admin.diagnostics.index')
            ->with('success', 'Diagnostic Created Successfully');
    }

    public function show($id)
    {
        $diagnostic = Diagnostic::findOrFail($id);

        return view('admin.diagnostics.show', compact('diagnostic'));
    }

    public function edit($id)
    {
        $diagnostic = Diagnostic::findOrFail($id);

        return view('admin.diagnostics.edit', compact('diagnostic'));
    }

    public function update(Request $request, $id)
    {
        $diagnostic = Diagnostic::findOrFail($id);

        $request->validate([
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
        ]);

        $diagnostic->diagnostic_name = $request->diagnostic_name;

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

        $diagnostic->status = $request->status;

        if ($request->hasFile('logo')) {

            if ($diagnostic->logo && File::exists(public_path($diagnostic->logo))) {
                File::delete(public_path($diagnostic->logo));
            }

            $file = $request->file('logo');

            $name = time() . '_logo.' . $file->getClientOriginalExtension();

            $file->move(public_path('uploads/diagnostics/logo'), $name);

            $diagnostic->logo = 'uploads/diagnostics/logo/' . $name;
        }

        if ($request->hasFile('banner')) {

            if ($diagnostic->banner && File::exists(public_path($diagnostic->banner))) {
                File::delete(public_path($diagnostic->banner));
            }

            $file = $request->file('banner');

            $name = time() . '_banner.' . $file->getClientOriginalExtension();

            $file->move(public_path('uploads/diagnostics/banner'), $name);

            $diagnostic->banner = 'uploads/diagnostics/banner/' . $name;
        }

        $diagnostic->save();

        return redirect()->route('admin.diagnostics.index')
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