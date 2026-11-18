<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Hospital;
use App\Models\Diagnostic;
use App\Models\DiagnosticBooking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\NotificationService;

class DiagnosticBookingController extends Controller
{
    /**
     * Get hospital diagnostic IDs
     */
    private function getDiagnosticIds()
    {
        $hospitalId = Auth::guard('hospital')->id();

        abort_unless($hospitalId, 403);

        // Fetch diagnostics belonging to logged-in hospital
        $diagnosticIds = Diagnostic::where('hospital_id', $hospitalId)
            ->pluck('id')
            ->toArray();

        return $diagnosticIds;
    }

    /**
     * Diagnostic Booking List
     */
    public function index(Request $request)
    {
        $diagnosticIds = $this->getDiagnosticIds();

        $query = DiagnosticBooking::with([
            'customer',
            'familyMember',
            'diagnostic'
        ])->whereIn('diagnostic_id', $diagnosticIds);

        // Search booking number
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('booking_no', 'LIKE', "%{$search}%")
                    ->orWhere('customer_id', 'LIKE', "%{$search}%");
            });
        }

        // Booking status filter
        if ($request->filled('booking_status')) {
            $query->where('booking_status', $request->booking_status);
        }

        // Payment status filter
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Collection type filter
        if ($request->filled('collection_type')) {
            $query->where('collection_type', $request->collection_type);
        }

        // Booking date filter
        if ($request->filled('booking_date')) {
            $query->whereDate('booking_date', $request->booking_date);
        }

        $bookings = $query->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'hospital.diagnostic-bookings.index',
            compact('bookings')
        );
    }

    /**
     * Edit Booking
     */
    public function edit($id)
    {
        $diagnosticIds = $this->getDiagnosticIds();

        $booking = DiagnosticBooking::with([
            'customer',
            'familyMember',
            'diagnostic'
        ])
            ->whereIn('diagnostic_id', $diagnosticIds)
            ->findOrFail($id);

        return view(
            'hospital.diagnostic-bookings.edit',
            compact('booking')
        );
    }

    /**
     * Update Booking
     */
    public function update(Request $request, $id)
    {
        $diagnosticIds = $this->getDiagnosticIds();

        $booking = DiagnosticBooking::whereIn(
            'diagnostic_id',
            $diagnosticIds
        )->findOrFail($id);

        $validated = $request->validate([
            'collection_type' => 'required|in:home_collection,lab_visit',
            'booking_date' => 'nullable|date',
            'booking_time' => 'nullable',
            'booking_status' => 'required|string|max:100',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'cancel_reason' => 'nullable|string',
        ]);

        $oldStatus = $booking->booking_status;
        $oldPaymentStatus = $booking->payment_status;

        if (
            $validated['booking_status'] === 'cancelled' &&
            empty($validated['cancel_reason'])
        ) {
            return back()
                ->withErrors(['cancel_reason' => 'Cancel reason is required.'])
                ->withInput();
        }

        $booking->update([
            'collection_type' => $validated['collection_type'],
            'booking_date' => $validated['booking_date'] ?? null,
            'booking_time' => $validated['booking_time'] ?? null,
            'booking_status' => $validated['booking_status'],
            'payment_status' => $validated['payment_status'],
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'pincode' => $validated['pincode'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'cancel_reason' => $validated['cancel_reason'] ?? null,
            'cancelled_at' => $validated['booking_status'] === 'cancelled'
                ? ($booking->cancelled_at ?? now())
                : null,
        ]);

        // Booking status notification
        if ($oldStatus !== $booking->booking_status) {
            try {
                if ($booking->customer_id) {
                    NotificationService::send(
                        'customer',
                        $booking->customer_id,
                        'diagnostic',
                        'Diagnostic Booking Status Updated',
                        'Your diagnostic booking ' . $booking->booking_no .
                        ' status has been changed from ' .
                        ucfirst(str_replace('_', ' ', $oldStatus)) . ' to ' .
                        ucfirst(str_replace('_', ' ', $booking->booking_status)) . '.' .
                        ($booking->cancel_reason
                            ? ' Reason: ' . $booking->cancel_reason
                            : '') .
                        ' Please check your lab booking details for the latest updates.',
                        'diagnostic_booking',
                        $booking->id,
                        'lab_booking_details',
                        [
                            'booking_id' => $booking->id,
                            'booking_no' => $booking->booking_no,
                            'old_status' => $oldStatus,
                            'booking_status' => $booking->booking_status,
                            'diagnostic_id' => $booking->diagnostic_id,
                        ]
                    );
                }
            } catch (\Throwable $e) {
                Log::error('Diagnostic booking notification failed: ' . $e->getMessage());
            }
        }

        // Payment status notification
        if ($oldPaymentStatus !== $booking->payment_status) {
            try {
                if ($booking->customer_id) {
                    NotificationService::send(
                        'customer',
                        $booking->customer_id,
                        'diagnostic',
                        'Diagnostic Payment Status Updated',
                        'The payment status for your diagnostic booking ' .
                        $booking->booking_no . ' has been changed from ' .
                        ucfirst(str_replace('_', ' ', $oldPaymentStatus)) . ' to ' .
                        ucfirst(str_replace('_', ' ', $booking->payment_status)) .
                        '. Amount: ₹' .
                        number_format((float) $booking->total_amount, 2) .
                        '. Please check your booking details for the latest payment information.',
                        'diagnostic_booking',
                        $booking->id,
                        'lab_booking_details',
                        [
                            'booking_id' => $booking->id,
                            'booking_no' => $booking->booking_no,
                            'old_payment_status' => $oldPaymentStatus,
                            'payment_status' => $booking->payment_status,
                        ]
                    );
                }
            } catch (\Throwable $e) {
                Log::error('Diagnostic payment notification failed: ' . $e->getMessage());
            }
        }

        return redirect()
            ->route('hospital.diagnostic-bookings.index')
            ->with('success', 'Diagnostic booking updated successfully.');
    }
}
