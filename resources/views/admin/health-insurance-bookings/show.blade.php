@extends('layout.mainlayout')

@section('content')
    <div class="page-wrapper">
        <div class="container-fluid py-4">

            {{-- Page Header --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                <div>
                    <h3 class="fw-bold mb-1">Booking Details</h3>
                    <p class="text-muted mb-0">
                        Reference: {{ $booking->booking_reference }}
                    </p>
                </div>

                <a href="{{ route('admin.health-insurance-bookings.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Bookings
                </a>
            </div>

            <div class="row g-4">

                {{-- Customer Details --}}
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-semibold">Customer Details</h5>
                        </div>

                        <div class="card-body">
                            <p>
                                <strong>Name:</strong>
                                {{ $booking->customer_name }}
                            </p>

                            <p>
                                <strong>Phone:</strong>
                                {{ $booking->customer_phone }}
                            </p>

                            <p>
                                <strong>Email:</strong>
                                {{ $booking->customer_email ?? 'N/A' }}
                            </p>

                            <p>
                                <strong>Date of Birth:</strong>
                                {{ $booking->date_of_birth
        ? \Carbon\Carbon::parse($booking->date_of_birth)->format('d M Y')
        : 'N/A' }}
                            </p>

                            <p>
                                <strong>Members:</strong>
                                {{ $booking->members_count }}
                            </p>

                            <p class="mb-0">
                                <strong>Notes:</strong>
                                {{ $booking->notes ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Insurance Details --}}
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-semibold">Insurance Details</h5>
                        </div>

                        <div class="card-body">
                            <p>
                                <strong>Provider:</strong>
                                {{ optional($booking->provider)->name
        ?? optional($booking->provider)->provider_name
        ?? 'N/A' }}
                            </p>

                            <p>
                                <strong>Plan:</strong>
                                {{ optional($booking->plan)->name
        ?? optional($booking->plan)->plan_name
        ?? 'N/A' }}
                            </p>

                            <p>
                                <strong>Booking Reference:</strong>
                                {{ $booking->booking_reference }}
                            </p>

                            <p>
                                <strong>Booking Date:</strong>
                                {{ $booking->created_at
        ? $booking->created_at->format('d M Y, h:i A')
        : 'N/A' }}
                            </p>

                            <p class="mb-0">
                                <strong>Booking Status:</strong>

                                @if($booking->status === 'confirmed')
                                    <span class="badge bg-success">Confirmed</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Payment Details --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-semibold">Payment Details</h5>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-0">
                                    <tbody>
                                        <tr>
                                            <th width="30%">Price</th>
                                            <td>
                                                ₹{{ number_format((float) $booking->price, 2) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Discount</th>
                                            <td>
                                                ₹{{ number_format((float) $booking->discount_price, 2) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Tax</th>
                                            <td>
                                                ₹{{ number_format((float) $booking->tax_price, 2) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Total Amount</th>
                                            <td class="fw-bold text-primary">
                                                ₹{{ number_format((float) $booking->total_amount, 2) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Payment Status</th>
                                            <td>
                                                @if($booking->payment_status === 'paid')
                                                    <span class="badge bg-success">Paid</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">
                                                        {{ ucfirst($booking->payment_status) }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Payment Method</th>
                                            <td>
                                                {{ $booking->payment_method
        ? ucfirst($booking->payment_method)
        : 'N/A' }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Transaction ID</th>
                                            <td>
                                                {{ $booking->transaction_id ?? 'N/A' }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Cancellation Reason</th>
                                            <td>
                                                {{ $booking->cancel_reason ?? 'N/A' }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Cancelled At</th>
                                            <td>
                                                {{ $booking->cancelled_at
        ? \Carbon\Carbon::parse($booking->cancelled_at)->format('d M Y, h:i A')
        : 'N/A' }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
@endsection