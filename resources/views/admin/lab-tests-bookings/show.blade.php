@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            {{-- Page Header --}}
            <div class="page-header">
                <div class="row align-items-center">

                    <div class="col">
                        <h4 class="page-title">
                            Diagnostic Booking Details
                        </h4>
                    </div>

                    <div class="col-auto">
                        <a href="{{ route('admin.lab-tests-bookings.index') }}" class="btn btn-light">
                            <i class="ti ti-arrow-left me-1"></i>
                            Back
                        </a>
                    </div>

                </div>
            </div>


            {{-- Booking Information --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Booking Information
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-3 mb-3">
                            <label class="text-muted">
                                Booking No
                            </label>

                            <h6>
                                {{ $booking->booking_no ?? '-' }}
                            </h6>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="text-muted">
                                Booking Date
                            </label>

                            <h6>
                                {{ $booking->booking_date
        ? $booking->booking_date->format('d M Y')
        : '-' }}
                            </h6>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="text-muted">
                                Booking Time
                            </label>

                            <h6>
                                {{ $booking->booking_time
        ? \Carbon\Carbon::parse($booking->booking_time)->format('h:i A')
        : '-' }}
                            </h6>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="text-muted">
                                Collection Type
                            </label>

                            <h6>

                                @if($booking->collection_type == 'home_collection')

                                    <span class="badge bg-info">
                                        Home Collection
                                    </span>

                                @elseif($booking->collection_type == 'lab_visit')

                                    <span class="badge bg-primary">
                                        Lab Visit
                                    </span>

                                @else

                                    -

                                @endif

                            </h6>
                        </div>


                        <div class="col-md-3 mb-3">
                            <label class="text-muted">
                                Customer ID
                            </label>

                            <h6>
                                {{ $booking->customer_id ?? '-' }}
                            </h6>
                        </div>


                        <div class="col-md-3 mb-3">
                            <label class="text-muted">
                                Family Member ID
                            </label>

                            <h6>
                                {{ $booking->family_member_id ?? '-' }}
                            </h6>
                        </div>


                        <div class="col-md-3 mb-3">
                            <label class="text-muted">
                                Diagnostic ID
                            </label>

                            <h6>
                                {{ $booking->diagnostic_id ?? '-' }}
                            </h6>
                        </div>


                        <div class="col-md-3 mb-3">
                            <label class="text-muted">
                                Booking Status
                            </label>

                            <h6>

                                @php
                                    $statusClass = match ($booking->booking_status) {
                                        'confirmed' => 'bg-success',
                                        'completed' => 'bg-primary',
                                        'cancelled' => 'bg-danger',
                                        default => 'bg-warning'
                                    };
                                @endphp

                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst($booking->booking_status ?? 'Pending') }}
                                </span>

                            </h6>
                        </div>

                    </div>

                </div>
            </div>


            {{-- Customer / Address Information --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Address Information
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="text-muted">
                                Address
                            </label>

                            <p class="mb-0">
                                {{ $booking->address ?? '-' }}
                            </p>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="text-muted">
                                City
                            </label>

                            <p class="mb-0">
                                {{ $booking->city ?? '-' }}
                            </p>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="text-muted">
                                State
                            </label>

                            <p class="mb-0">
                                {{ $booking->state ?? '-' }}
                            </p>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="text-muted">
                                Pincode
                            </label>

                            <p class="mb-0">
                                {{ $booking->pincode ?? '-' }}
                            </p>
                        </div>

                    </div>

                </div>
            </div>


            {{-- Lab Tests --}}
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Booked Lab Tests
                    </h5>
                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Lab Test</th>
                                    <th>Price</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($booking->items as $key => $item)

                                    <tr>

                                        <td>
                                            {{ $key + 1 }}
                                        </td>

                                        <td>
                                            {{ $item->labTest->name ?? '-' }}
                                        </td>

                                        <td>
                                            ₹{{ number_format($item->price ?? 0, 2) }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">
                                            No lab tests found.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- Payment & Amount --}}
            <div class="row">

                <div class="col-md-6">

                    <div class="card">

                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Payment Information
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="d-flex justify-content-between mb-3">
                                <span>Payment Method</span>

                                <strong>
                                    {{ $booking->payment_method ?? '-' }}
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span>Transaction ID</span>

                                <strong>
                                    {{ $booking->transaction_id ?? '-' }}
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span>Payment ID</span>

                                <strong>
                                    {{ $booking->payment_id ?? '-' }}
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between">
                                <span>Payment Status</span>

                                @if($booking->payment_status == 'paid')

                                    <span class="badge bg-success">
                                        Paid
                                    </span>

                                @elseif($booking->payment_status == 'failed')

                                    <span class="badge bg-danger">
                                        Failed
                                    </span>

                                @elseif($booking->payment_status == 'refunded')

                                    <span class="badge bg-secondary">
                                        Refunded
                                    </span>

                                @else

                                    <span class="badge bg-warning">
                                        Pending
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="card">

                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Amount Details
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="d-flex justify-content-between mb-3">
                                <span>Subtotal</span>

                                <strong>
                                    ₹{{ number_format($booking->subtotal ?? 0, 2) }}
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span>Home Collection Charge</span>

                                <strong>
                                    ₹{{ number_format($booking->home_collection_charge ?? 0, 2) }}
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span>Discount</span>

                                <strong>
                                    - ₹{{ number_format($booking->discount ?? 0, 2) }}
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span>Tax</span>

                                <strong>
                                    ₹{{ number_format($booking->tax ?? 0, 2) }}
                                </strong>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between">

                                <strong>
                                    Total Amount
                                </strong>

                                <h5 class="mb-0">
                                    ₹{{ number_format($booking->total_amount ?? 0, 2) }}
                                </h5>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Notes --}}
            @if($booking->notes)

                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Notes
                        </h5>
                    </div>

                    <div class="card-body">
                        {{ $booking->notes }}
                    </div>

                </div>

            @endif


            {{-- Cancellation Reason --}}
            @if($booking->cancel_reason)

                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Cancellation Information
                        </h5>
                    </div>

                    <div class="card-body">

                        <p class="mb-1">
                            <strong>Reason:</strong>
                            {{ $booking->cancel_reason }}
                        </p>

                        @if($booking->cancelled_at)
                            <p class="mb-0">
                                <strong>Cancelled At:</strong>
                                {{ $booking->cancelled_at->format('d M Y h:i A') }}
                            </p>
                        @endif

                    </div>

                </div>

            @endif

        </div>

    </div>

@endsection