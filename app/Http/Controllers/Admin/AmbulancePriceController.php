<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ambulance;
use App\Models\AmbulancePrice;
use Illuminate\Http\Request;

class AmbulancePriceController extends Controller
{
    /**
     * Display ambulance pricing.
     */
    public function index($ambulanceId)
    {
        $ambulance = Ambulance::with([
            'hospital',
            'ambulanceType',
            'prices'
        ])->findOrFail($ambulanceId);

        $localPrices = $ambulance->prices
            ->where('trip_type', 'local')
            ->sortBy('max_distance_km');

        $outstationPrices = $ambulance->prices
            ->where('trip_type', 'outstation')
            ->sortBy('max_distance_km');

        return view(
            'admin.ambulances.prices.index',
            compact(
                'ambulance',
                'localPrices',
                'outstationPrices'
            )
        );
    }


    /**
     * Store pricing.
     */
    public function store(
        Request $request,
        $ambulanceId
    ) {
        $ambulance = Ambulance::findOrFail($ambulanceId);

        $request->validate([
            'trip_type' => [
                'required',
                'in:local,outstation'
            ],

            'max_distance_km' => [
                'required',
                'numeric',
                'min:0.01'
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0'
            ],

            'status' => [
                'nullable',
                'boolean'
            ],
        ]);

        $exists = AmbulancePrice::where(
            'ambulance_id',
            $ambulance->id
        )
            ->where(
                'trip_type',
                $request->trip_type
            )
            ->where(
                'max_distance_km',
                $request->max_distance_km
            )
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'This pricing slab already exists.'
                );
        }

        AmbulancePrice::create([
            'ambulance_id' => $ambulance->id,
            'trip_type' => $request->trip_type,
            'max_distance_km' => $request->max_distance_km,
            'amount' => $request->amount,
            'status' => $request->boolean('status', true),
        ]);

        return back()->with(
            'success',
            'Ambulance price added successfully.'
        );
    }


    /**
     * Update pricing.
     */
    public function update(
        Request $request,
        $ambulanceId,
        $priceId
    ) {
        $ambulance = Ambulance::findOrFail($ambulanceId);

        $price = AmbulancePrice::where(
            'ambulance_id',
            $ambulance->id
        )
            ->findOrFail($priceId);

        $request->validate([
            'trip_type' => [
                'required',
                'in:local,outstation'
            ],

            'max_distance_km' => [
                'required',
                'numeric',
                'min:0.01'
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0'
            ],

            'status' => [
                'nullable',
                'boolean'
            ],
        ]);

        $exists = AmbulancePrice::where(
            'ambulance_id',
            $ambulance->id
        )
            ->where(
                'trip_type',
                $request->trip_type
            )
            ->where(
                'max_distance_km',
                $request->max_distance_km
            )
            ->where(
                'id',
                '!=',
                $price->id
            )
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'This pricing slab already exists.'
                );
        }

        $price->update([
            'trip_type' => $request->trip_type,
            'max_distance_km' => $request->max_distance_km,
            'amount' => $request->amount,
            'status' => $request->boolean('status', true),
        ]);

        return back()->with(
            'success',
            'Ambulance price updated successfully.'
        );
    }


    /**
     * Delete pricing.
     */
    public function destroy(
        $ambulanceId,
        $priceId
    ) {
        $ambulance = Ambulance::findOrFail($ambulanceId);

        $price = AmbulancePrice::where(
            'ambulance_id',
            $ambulance->id
        )
            ->findOrFail($priceId);

        $price->delete();

        return back()->with(
            'success',
            'Ambulance price deleted successfully.'
        );
    }


    /**
     * Toggle price status.
     */
    public function status(
        $ambulanceId,
        $priceId
    ) {
        $ambulance = Ambulance::findOrFail($ambulanceId);

        $price = AmbulancePrice::where(
            'ambulance_id',
            $ambulance->id
        )
            ->findOrFail($priceId);

        $price->status = !$price->status;
        $price->save();

        return back()->with(
            'success',
            'Ambulance price status updated successfully.'
        );
    }
}