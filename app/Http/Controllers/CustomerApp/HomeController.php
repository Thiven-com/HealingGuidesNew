<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\Ambulance;
use App\Models\Diagnostic;
use App\Models\Hospital;
use App\Models\LabTest;
use App\Models\Medicine;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function search(Request $request)
    {
        $search = trim($request->search ?? '');

        if ($search === '') {
            return response()->json([
                'success' => 1,
                'data' => [
                    'hospitals' => [],
                    'diagnostics' => [],
                    'labtests' => [],
                    'ambulances' => [],
                    'medicines' => [],
                ],
            ]);
        }

        $data['hospitals'] = Hospital::where('status', 1)
        // $data['hospitals'] = Hospital::with('hospitalSpecializations.specialization')
            ->where('hospital_name', 'LIKE', '%' . $search . '%')
            ->inRandomOrder()
            ->limit(5)
            ->get();

        $data['diagnostics'] = Diagnostic::where('status', 1)
            ->where('diagnostic_name', 'LIKE', '%' . $search . '%')
            ->inRandomOrder()
            ->limit(5)
            ->get();

        $data['labtests'] = LabTest::where('status', 1)
            ->where('test_name', 'LIKE', '%' . $search . '%')
            ->inRandomOrder()
            ->limit(5)
            ->get();

        $data['ambulances'] = Ambulance::where('status', 1)
            ->where('ambulance_name', 'LIKE', '%' . $search . '%')
            ->inRandomOrder()
            ->limit(5)
            ->get();

        $data['medicines'] = Medicine::where('status', 1)
            ->where('medicine_name', 'LIKE', '%' . $search . '%')
            ->inRandomOrder()
            ->limit(5)
            ->get();

        return response()->json([
            'success' => 1,
            'data' => $data,
        ]);
    }

}
