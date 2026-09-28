<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\HospitalFacility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacilityController extends Controller
{
    /**
     * Get logged-in hospital ID
     */
    private function hospitalId()
    {
        $hospitalUser = Auth::guard('hospital')->user();

        if (!$hospitalUser) {
            abort(403, 'Unauthenticated.');
        }

        return $hospitalUser->hospital_id ?? $hospitalUser->id;
    }

    /**
     * Display hospital facilities
     */
    public function index()
    {
        $hospitalId = $this->hospitalId();

        $hospitalFacilities = HospitalFacility::with('facility')
            ->where('hospital_id', $hospitalId)
            ->latest()
            ->paginate(10);

        return view(
            'hospital.facilities.index',
            compact('hospitalFacilities')
        );
    }

    /**
     * Show create form
     */
    public function create()
    {
        $hospitalId = $this->hospitalId();

        /*
        |--------------------------------------------------------------------------
        | Get facilities already assigned to this hospital
        |--------------------------------------------------------------------------
        */

        $assignedFacilityIds = HospitalFacility::where(
            'hospital_id',
            $hospitalId
        )->pluck('facility_id');

        /*
        |--------------------------------------------------------------------------
        | Get available facilities
        |--------------------------------------------------------------------------
        */

        $facilities = Facility::whereNotIn(
            'id',
            $assignedFacilityIds
        )->orderBy('name')->get();

        return view(
            'hospital.facilities.create',
            compact('facilities')
        );
    }

    /**
     * Store hospital facility
     */
    public function store(Request $request)
    {
        $hospitalId = $this->hospitalId();

        $request->validate([
            'facility_id' => [
                'required',
                'exists:facilities,id',
                function ($attribute, $value, $fail) use ($hospitalId) {

                    $exists = HospitalFacility::where(
                        'hospital_id',
                        $hospitalId
                    )
                    ->where('facility_id', $value)
                    ->exists();

                    if ($exists) {
                        $fail('This facility is already assigned to your hospital.');
                    }
                },
            ],

            'description' => 'nullable|string',
        ]);

        HospitalFacility::create([
            'hospital_id' => $hospitalId,
            'facility_id' => $request->facility_id,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('hospital.facilities.index')
            ->with(
                'success',
                'Hospital Facility Added Successfully'
            );
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $hospitalId = $this->hospitalId();

        $hospitalFacility = HospitalFacility::with('facility')
            ->where('hospital_id', $hospitalId)
            ->where('id', $id)
            ->firstOrFail();

        $facilities = Facility::orderBy('name')->get();

        return view(
            'hospital.facilities.edit',
            compact(
                'hospitalFacility',
                'facilities'
            )
        );
    }

    /**
     * Update hospital facility
     */
    public function update(Request $request, $id)
    {
        $hospitalId = $this->hospitalId();

        $hospitalFacility = HospitalFacility::where(
            'hospital_id',
            $hospitalId
        )
        ->where('id', $id)
        ->firstOrFail();

        $request->validate([
            'facility_id' => [
                'required',
                'exists:facilities,id',
                function ($attribute, $value, $fail) use (
                    $hospitalId,
                    $hospitalFacility
                ) {

                    $exists = HospitalFacility::where(
                        'hospital_id',
                        $hospitalId
                    )
                    ->where('facility_id', $value)
                    ->where(
                        'id',
                        '!=',
                        $hospitalFacility->id
                    )
                    ->exists();

                    if ($exists) {
                        $fail('This facility is already assigned to your hospital.');
                    }
                },
            ],

            'description' => 'nullable|string',
        ]);

        $hospitalFacility->update([
            'facility_id' => $request->facility_id,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('hospital.facilities.index')
            ->with(
                'success',
                'Hospital Facility Updated Successfully'
            );
    }

    /**
     * Delete hospital facility
     */
    public function destroy($id)
    {
        $hospitalId = $this->hospitalId();

        $hospitalFacility = HospitalFacility::where(
            'hospital_id',
            $hospitalId
        )
        ->where('id', $id)
        ->firstOrFail();

        $hospitalFacility->delete();

        return redirect()
            ->route('hospital.facilities.index')
            ->with(
                'success',
                'Hospital Facility Deleted Successfully'
            );
    }
}