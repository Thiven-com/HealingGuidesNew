@extends('layout.mainlayout')

@section('content')
    <div class="page-wrapper">

        <div class="container-fluid">

            {{-- Page Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
                <h4>Diagnostic Bookings</h4>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Search and Filters --}}
            <div class="card mb-4">
                <div class="card-body">

                    <form method="GET" action="{{ route('hospital.diagnostic-bookings.index') }}">

                        <div class="row g-3">

                            <div class="col-md-2">
                                <label>Search</label>
                                <input type="text" name="search" class="form-control" placeholder="Booking No / Customer ID"
                                    value="{{ request('search') }}">
                            </div>

                            <div class="col-md-2">
                                <label>Booking Status</label>
                                <select name="booking_status" class="form-select">
                                    <option value="">All Status</option>
                                    @foreach(['pending', 'confirmed', 'completed', 'cancelled'] as $status)
                                        <option value="{{ $status }}" @selected(request('booking_status') == $status)>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>Payment Status</label>
                                <select name="payment_status" class="form-select">
                                    <option value="">All Payments</option>
                                    @foreach(['pending', 'paid', 'failed', 'refunded'] as $status)
                                        <option value="{{ $status }}" @selected(request('payment_status') == $status)>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>Collection Type</label>
                                <select name="collection_type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="home_collection"
                                        @selected(request('collection_type') == 'home_collection')>
                                        Home Collection
                                    </option>
                                    <option value="lab_visit" @selected(request('collection_type') == 'lab_visit')>
                                        Lab Visit
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>Booking Date</label>
                                <input type="date" name="booking_date" class="form-control"
                                    value="{{ request('booking_date') }}">
                            </div>

                            <div class="col-md-1 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>

                            <div class="col-md-1 d-flex align-items-end">
                                <a href="{{ route('hospital.diagnostic-bookings.index') }}"
                                    class="btn btn-secondary btn-sm">
                                    Reset
                                </a>
                            </div>

                        </div>
                    </form>

                </div>
            </div>

            {{-- Booking Table --}}
            <div class="card">
                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle">

                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Booking No</th>
                                    <th>Customer ID</th>
                                    <th>Diagnostic</th>
                                    <th>Collection Type</th>
                                    <th>Booking Date</th>
                                    <th>Booking Time</th>
                                    <th>Total Amount</th>
                                    <th>Payment Status</th>
                                    <th>Booking Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($bookings as $key => $booking)

                                                        <tr>
                                                            <td>{{ $bookings->firstItem() + $key }}</td>

                                                            <td>
                                                                {{ $booking->booking_no ?? 'N/A' }}
                                                            </td>

                                                            <td>
                                                                {{ $booking->customer_id ?? 'N/A' }}
                                                            </td>

                                                            <td>
                                                                {{ $booking->diagnostic->name ?? $booking->diagnostic_id }}
                                                            </td>

                                                            <td>
                                                                {{ ucfirst(str_replace('_', ' ', $booking->collection_type ?? 'N/A')) }}
                                                            </td>

                                                            <td>
                                                                {{ $booking->booking_date
                                    ? \Carbon\Carbon::parse($booking->booking_date)->format('d-m-Y')
                                    : 'N/A' }}
                                                            </td>

                                                            <td>
                                                                {{ $booking->booking_time
                                    ? \Carbon\Carbon::parse($booking->booking_time)->format('h:i A')
                                    : 'N/A' }}
                                                            </td>

                                                            <td>
                                                                ₹{{ number_format($booking->total_amount ?? 0, 2) }}
                                                            </td>

                                                            <td>
                                                                @php
                                                                    $paymentClass = match ($booking->payment_status) {
                                                                        'paid' => 'bg-success',
                                                                        'failed' => 'bg-danger',
                                                                        'refunded' => 'bg-info',
                                                                        default => 'bg-warning'
                                                                    };
                                                                @endphp

                                                                <span class="badge {{ $paymentClass }}">
                                                                    {{ ucfirst($booking->payment_status ?? 'pending') }}
                                                                </span>
                                                            </td>

                                                            <td>
                                                                @php
                                                                    $statusClass = match ($booking->booking_status) {
                                                                        'completed' => 'bg-success',
                                                                        'cancelled' => 'bg-danger',
                                                                        'confirmed' => 'bg-primary',
                                                                        default => 'bg-warning'
                                                                    };
                                                                @endphp

                                                                <span class="badge {{ $statusClass }}">
                                                                    {{ ucfirst($booking->booking_status ?? 'pending') }}
                                                                </span>
                                                            </td>

                                                            <td>
                                                                <a href="{{ route('hospital.diagnostic-bookings.edit', $booking->id) }}"
                                                                    class="btn btn-sm btn-primary">
                                                                    <i class="fa fa-edit"></i> Edit
                                                                </a>
                                                            </td>
                                                        </tr>

                                @empty

                                    <tr>
                                        <td colspan="11" class="text-center">
                                            No diagnostic bookings found.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    <div class="mt-3">
                        {{ $bookings->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection