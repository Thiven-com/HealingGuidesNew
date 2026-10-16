<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HealthInsuranceBooking;
use Illuminate\Http\Request;

class HealthInsuranceBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = HealthInsuranceBooking::with([
            'provider',
            'plan',
        ]);

        // Search bookings and customer details
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('transaction_id', 'like', "%{$search}%");
            });
        }

        // Booking status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Payment status filter
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $bookings = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.health-insurance-bookings.index',
            compact('bookings')
        );
    }

    public function show($id)
    {
        $booking = HealthInsuranceBooking::with([
            'provider',
            'plan',
        ])->findOrFail($id);

        return view(
            'admin.health-insurance-bookings.show',
            compact('booking')
        );
    }
}
