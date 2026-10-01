<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiagnosticLabTest;
use Illuminate\Http\Request;

class DiagnosticLabTestController extends Controller
{

    public function update(Request $request, $id)
    {
        $diagnosticLabTest = DiagnosticLabTest::findOrFail($id);

        $request->validate([
            'price' => 'nullable|numeric|min:0',
            'offer_price' => 'nullable|numeric|min:0',
            'report_time' => 'nullable|string|max:100',
            'report_time_type' => 'nullable|string|max:50',
            'home_collection' => 'required|boolean',
            'status' => 'required|boolean',
            'free_ambulances' => 'nullable|integer|min:0',
        ]);

        $diagnosticLabTest->price = $request->price;
        $diagnosticLabTest->offer_price = $request->offer_price;
        $diagnosticLabTest->report_time = $request->report_time;
        $diagnosticLabTest->report_time_type = $request->report_time_type;
        $diagnosticLabTest->home_collection = $request->home_collection;
        $diagnosticLabTest->status = $request->status;
        $diagnosticLabTest->free_ambulances = $request->free_ambulances;

        $diagnosticLabTest->save();

        return redirect()
            ->back()
            ->with('success', 'Lab test updated successfully.');
    }

    public function destroy($id)
    {
        $diagnosticLabTest = DiagnosticLabTest::findOrFail($id);

        $diagnosticLabTest->delete();

        return redirect()
            ->back()
            ->with('success', 'Lab test deleted successfully.');
    }
}
