<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiagnosticBooking;
use Illuminate\Http\Request;

class LabTestBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = DiagnosticBooking::with([
            'customer',
            'familyMember',
            'diagnostic',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('booking_no', 'like', '%' . $search . '%')
                    ->orWhere('transaction_id', 'like', '%' . $search . '%')
                    ->orWhere('payment_id', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%')
                    ->orWhere('state', 'like', '%' . $search . '%')
                    ->orWhere('pincode', 'like', '%' . $search . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Collection Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('collection_type')) {

            $query->where(
                'collection_type',
                $request->collection_type
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment_status')) {

            $query->where(
                'payment_status',
                $request->payment_status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Booking Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('booking_status')) {

            $query->where(
                'booking_status',
                $request->booking_status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Booking Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('booking_date')) {

            $query->whereDate(
                'booking_date',
                $request->booking_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date Range
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from_date')) {

            $query->whereDate(
                'booking_date',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {

            $query->whereDate(
                'booking_date',
                '<=',
                $request->to_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $perPage = $request->get('perPage', 10);

        $bookings = $query
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();

        return view(
            'admin.lab-tests-bookings.index',
            compact('bookings')
        );
    }
    public function show($id)
    {
        $booking = DiagnosticBooking::with([
            'items.labTest',
        ])->findOrFail($id);

        return view(
            'admin.lab-tests-bookings.show',
            compact('booking')
        );
    }
}
