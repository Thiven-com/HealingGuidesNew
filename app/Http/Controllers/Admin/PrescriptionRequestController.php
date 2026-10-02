<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrescriptionRequest;
use Illuminate\Http\Request;

class PrescriptionRequestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | List Prescription Requests
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = PrescriptionRequest::with([
            'customer',
            'familyMember',
            'prescription',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                if (is_numeric($search)) {
                    $q->orWhere('id', $search);
                }

                $q->orWhere(
                    'request_type',
                    'like',
                    "%{$search}%"
                );

                $q->orWhere(
                    'status',
                    'like',
                    "%{$search}%"
                );

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Request Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('request_type')) {

            $query->where(
                'request_type',
                $request->request_type
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        $requests = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.prescription_requests.index',
            compact('requests')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Show Prescription Request
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $prescriptionRequest =
            PrescriptionRequest::with([
                'customer',
                'familyMember',
                'prescription',

                'quotations',
                'quotations.hospital',
                'quotations.diagnostic',
                'quotations.medicineItems',
                'quotations.medicineItems.medicine',
                'quotations.labTestItems',
                'quotations.labTestItems.labTest',

                'medicineOrders',
                'diagnosticBookings',
            ])->findOrFail($id);

        return view(
            'admin.prescription_requests.show',
            compact('prescriptionRequest')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Mark Request As Reviewing
    |--------------------------------------------------------------------------
    */

    public function review($id)
    {
        $prescriptionRequest =
            PrescriptionRequest::findOrFail($id);

        if ($prescriptionRequest->status !== 'pending') {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'This prescription request cannot be moved to reviewing.'
                );
        }

        $prescriptionRequest->update([
            'status' => 'reviewing',
            'reviewed_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Prescription request moved to reviewing.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Reject Request
    |--------------------------------------------------------------------------
    */

    public function reject(Request $request, $id)
    {
        $prescriptionRequest =
            PrescriptionRequest::findOrFail($id);

        if (
            in_array($prescriptionRequest->status, [
                'completed',
                'cancelled',
                'paid',
            ])
        ) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'This prescription request cannot be rejected.'
                );
        }

        $request->validate([
            'admin_notes' =>
                'nullable|string|max:2000',
        ]);

        $prescriptionRequest->update([

            'status' => 'rejected',

            'admin_notes' =>
                $request->admin_notes,

            'rejected_at' =>
                now(),
        ]);

        return redirect()
            ->route(
                'admin.prescription-requests.show',
                $prescriptionRequest->id
            )
            ->with(
                'success',
                'Prescription request rejected successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Cancel Request
    |--------------------------------------------------------------------------
    */

    public function cancel($id)
    {
        $prescriptionRequest =
            PrescriptionRequest::findOrFail($id);

        if (
            in_array($prescriptionRequest->status, [
                'completed',
                'cancelled',
            ])
        ) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'This prescription request cannot be cancelled.'
                );
        }

        $prescriptionRequest->update([
            'status' => 'cancelled',
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Prescription request cancelled successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Request
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $prescriptionRequest =
            PrescriptionRequest::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Do Not Delete Paid / Completed Requests
        |--------------------------------------------------------------------------
        */

        if (
            in_array($prescriptionRequest->status, [
                'paid',
                'completed',
            ])
        ) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Paid or completed prescription requests cannot be deleted.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Related Quotations
        |--------------------------------------------------------------------------
        */

        foreach ($prescriptionRequest->quotations as $quotation) {

            $quotation->medicineItems()->delete();

            $quotation->labTestItems()->delete();

            $quotation->delete();
        }

        $prescriptionRequest->delete();

        return redirect()
            ->route(
                'admin.prescription-requests.index'
            )
            ->with(
                'success',
                'Prescription request deleted successfully.'
            );
    }
}