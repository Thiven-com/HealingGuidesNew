@extends('layout.mainlayout')

@section('content')
    <div class="page-wrapper">
        <div class="container-fluid py-4">

            {{-- Page Header --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                <div>
                    <h3 class="fw-bold mb-1">Health Insurance Plan Bookings</h3>
                    <p class="text-muted mb-0">
                        Manage all health insurance plan bookings.
                    </p>
                </div>

                <span class="badge bg-primary fs-6 px-3 py-2">
                    Total: {{ $bookings->total() }}
                </span>
            </div>

            {{-- Filters --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.health-insurance-bookings.index') }}">
                        <div class="row g-3 align-items-end">

                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Search</label>
                                <input
                                    type="text"
                                    name="search"
                                    class="form-control"
                                    placeholder="Booking reference, customer, phone..."
                                    value="{{ request('search') }}"
                                >
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Booking Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                                    <option value="confirmed" @selected(request('status') === 'confirmed')>Confirmed</option>
                                    <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Payment Status</label>
                                <select name="payment_status" class="form-select">
                                    <option value="">All Payments</option>
                                    <option value="pending" @selected(request('payment_status') === 'pending')>Pending</option>
                                    <option value="paid" @selected(request('payment_status') === 'paid')>Paid</option>
                                </select>
                            </div>

                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-1"></i> Search
                                </button>

                                <a
                                    href="{{ route('admin.health-insurance-bookings.index') }}"
                                    class="btn btn-outline-secondary"
                                    title="Reset filters"
                                >
                                    <i class="fas fa-redo"></i>
                                </a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            {{-- Bookings Table --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-3">#</th>
                                    <th>Booking Reference</th>
                                    <th>Customer</th>
                                    <th>Provider & Plan</th>
                                    <th>Members</th>
                                    <th>Total Amount</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Booked On</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($bookings as $booking)
                                    <tr>
                                        <td class="px-3">
                                            {{ $bookings->firstItem() + $loop->index }}
                                        </td>

                                        <td>
                                            <div class="fw-semibold">
                                                {{ $booking->booking_reference }}
                                            </div>

                                            @if($booking->transaction_id)
                                                <small class="text-muted">
                                                    Transaction: {{ $booking->transaction_id }}
                                                </small>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="fw-semibold">
                                                {{ $booking->customer_name }}
                                            </div>
                                            <div class="small text-muted">
                                                {{ $booking->customer_phone }}
                                            </div>
                                            @if($booking->customer_email)
                                                <div class="small text-muted">
                                                    {{ $booking->customer_email }}
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="fw-semibold">
                                                {{ $booking->provider->name
                                                    ?? $booking->provider->provider_name
                                                    ?? 'N/A' }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $booking->plan->name
                                                    ?? $booking->plan->plan_name
                                                    ?? 'N/A' }}
                                            </small>
                                        </td>

                                        <td>{{ $booking->members_count }}</td>

                                        <td class="fw-semibold">
                                            ₹{{ number_format((float) $booking->total_amount, 2) }}
                                        </td>

                                        <td>
                                            @if($booking->payment_status === 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                    {{ ucfirst($booking->payment_status) }}
                                                </span>
                                            @endif

                                            @if($booking->payment_method)
                                                <div class="small text-muted mt-1">
                                                    {{ ucfirst($booking->payment_method) }}
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            @if($booking->status === 'confirmed')
                                                <span class="badge bg-success">Confirmed</span>
                                            @elseif($booking->status === 'cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            @endif
                                        </td>

                                        <td>
                                            {{ $booking->created_at?->format('d M Y') ?? 'N/A' }}
                                            <div class="small text-muted">
                                                {{ $booking->created_at?->format('h:i A') ?? '' }}
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <a
                                                href="{{ route('admin.health-insurance-bookings.show', $booking->id) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                title="View Booking"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-5">
                                            <i class="fas fa-file-medical fa-2x text-muted mb-3"></i>
                                            <h6 class="fw-semibold">No bookings found</h6>
                                            <p class="text-muted mb-0">
                                                Health insurance bookings will appear here.
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($bookings->hasPages())
                        <div class="d-flex flex-wrap justify-content-between align-items-center p-3">
                            <div class="text-muted small mb-2 mb-md-0">
                                Showing {{ $bookings->firstItem() }} to {{ $bookings->lastItem() }}
                                of {{ $bookings->total() }} bookings
                            </div>

                            {{ $bookings->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection