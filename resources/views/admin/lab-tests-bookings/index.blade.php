@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            {{-- Page Header --}}
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="page-title">Diagnostic Bookings</h4>
                        <p class="text-muted mb-0">
                            Manage all diagnostic test bookings
                        </p>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="card">
                <div class="card-body">

                    <form method="GET" action="{{ route('admin.lab-tests-bookings.index') }}">

                        <div class="row g-3">

                            {{-- Search --}}
                            <div class="col-md-4">
                                <label class="form-label">Search</label>

                                <input type="text" name="search" class="form-control"
                                    placeholder="Booking No, Transaction ID, City..." value="{{ request('search') }}">
                            </div>

                            {{-- Collection Type --}}
                            <div class="col-md-2">
                                <label class="form-label">Collection Type</label>

                                <select name="collection_type" class="form-select">

                                    <option value="">All</option>

                                    <option value="home_collection" {{ request('collection_type') == 'home_collection' ? 'selected' : '' }}>
                                        Home Collection
                                    </option>

                                    <option value="lab_visit" {{ request('collection_type') == 'lab_visit' ? 'selected' : '' }}>
                                        Lab Visit
                                    </option>

                                </select>
                            </div>

                            {{-- Payment Status --}}
                            <div class="col-md-2">
                                <label class="form-label">Payment Status</label>

                                <select name="payment_status" class="form-select">

                                    <option value="">All</option>

                                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>

                                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>
                                        Paid
                                    </option>

                                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>
                                        Failed
                                    </option>

                                    <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>
                                        Refunded
                                    </option>

                                </select>
                            </div>

                            {{-- Booking Status --}}
                            <div class="col-md-2">
                                <label class="form-label">Booking Status</label>

                                <select name="booking_status" class="form-select">

                                    <option value="">All</option>

                                    <option value="pending" {{ request('booking_status') == 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>

                                    <option value="confirmed" {{ request('booking_status') == 'confirmed' ? 'selected' : '' }}>
                                        Confirmed
                                    </option>

                                    <option value="completed" {{ request('booking_status') == 'completed' ? 'selected' : '' }}>
                                        Completed
                                    </option>

                                    <option value="cancelled" {{ request('booking_status') == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled
                                    </option>

                                </select>
                            </div>

                            {{-- Filter Button --}}
                            <div class="col-md-2 d-flex align-items-end">

                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="ti ti-filter"></i>
                                    Filter
                                </button>

                                <a href="{{ route('admin.lab-tests-bookings.index') }}" class="btn btn-light">
                                    Reset
                                </a>

                            </div>

                        </div>

                        {{-- Date Filters --}}
                        <div class="row g-3 mt-1">

                            <div class="col-md-3">
                                <label class="form-label">Booking Date</label>

                                <input type="date" name="booking_date" class="form-control"
                                    value="{{ request('booking_date') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">From Date</label>

                                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">To Date</label>

                                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                            </div>

                        </div>

                    </form>

                </div>
            </div>

            {{-- Booking Table --}}
            <div class="card mt-4">

                <div class="card-header">
                    <div class="row align-items-center">

                        <div class="col">
                            <h5 class="card-title mb-0">
                                Diagnostic Booking List
                            </h5>
                        </div>

                        <div class="col-auto">
                            <span class="text-muted">
                                Total: {{ $bookings->total() }}
                            </span>
                        </div>

                    </div>
                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>
                                <tr>

                                    <th>#</th>

                                    <th>Booking No</th>

                                    <th>Customer</th>

                                    <th>Family Member</th>

                                    <th>Diagnostic</th>

                                    <th>Collection Type</th>

                                    <th>Booking Date</th>

                                    <th>Total Amount</th>

                                    <th>Payment</th>

                                    <th>Status</th>

                                    <th>Action</th>

                                </tr>
                            </thead>

                            <tbody>

                                @forelse($bookings as $booking)

                                                        <tr>

                                                            {{-- ID --}}
                                                            <td>
                                                                {{ $booking->id }}
                                                            </td>

                                                            {{-- Booking Number --}}
                                                            <td>
                                                                <strong>
                                                                    {{ $booking->booking_no ?? '-' }}
                                                                </strong>
                                                            </td>

                                                            {{-- Customer --}}
                                                            <td>
                                                                {{ $booking->customer_name ?? '-' }}
                                                            </td>

                                                            {{-- Family Member --}}
                                                            <td>
                                                                 {{ $booking->familyMember?->name ?? '-' }}
                                                            </td>

                                                            {{-- Diagnostic --}}
                                                            <td>
                                                               {{ $booking->diagnostic->diagnostic_name ?? '-' }}
                                                            </td>

                                                            {{-- Collection Type --}}
                                                            <td>

                                                                @if($booking->collection_type == 'home_collection')

                                                                    <span class="badge bg-info">
                                                                        Home Collection
                                                                    </span>

                                                                @elseif($booking->collection_type == 'lab_visit')

                                                                    <span class="badge bg-primary">
                                                                        Lab Visit
                                                                    </span>

                                                                @else

                                                                    <span class="text-muted">-</span>

                                                                @endif

                                                            </td>

                                                            {{-- Booking Date --}}
                                                            <td>
                                                                {{ $booking->booking_date
                                    ? \Carbon\Carbon::parse($booking->booking_date)->format('d M Y')
                                    : '-' }}

                                                                @if($booking->booking_time)
                                                                    <br>
                                                                    <small class="text-muted">
                                                                        {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}
                                                                    </small>
                                                                @endif
                                                            </td>

                                                            {{-- Total --}}
                                                            <td>
                                                                ₹{{ number_format($booking->total_amount ?? 0, 2) }}
                                                            </td>

                                                            {{-- Payment --}}
                                                            <td>

                                                                @if($booking->payment_status == 'paid')

                                                                    <span class="badge bg-success">
                                                                        Paid
                                                                    </span>

                                                                @elseif($booking->payment_status == 'pending')

                                                                    <span class="badge bg-warning">
                                                                        Pending
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

                                                                    <span class="text-muted">-</span>

                                                                @endif

                                                            </td>

                                                            {{-- Booking Status --}}
                                                            <td>

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

                                                            </td>

                                                            {{-- Action --}}
                                                            <td>

                                                                <a href="{{ route('admin.lab-tests-bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                                                                    <i class="ti ti-eye"></i>
                                                                </a>

                                                            </td>

                                                        </tr>

                                @empty

                                    <tr>
                                        <td colspan="11" class="text-center py-5">

                                            <div class="text-muted">
                                                No diagnostic bookings found.
                                            </div>

                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

                {{-- Pagination --}}
                @if($bookings->hasPages())

                    <div class="card-footer">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                Showing
                                {{ $bookings->firstItem() ?? 0 }}
                                to
                                {{ $bookings->lastItem() ?? 0 }}
                                of
                                {{ $bookings->total() }}
                                bookings
                            </div>

                            <div>
                                {{ $bookings->links() }}
                            </div>

                        </div>

                    </div>

                @endif

            </div>

        </div>
    </div>

@endsection