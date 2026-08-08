@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">

    <div class="content">

        {{-- =========================================================
        PAGE HEADER
        ========================================================== --}}

        <div class="page-header">

            <div class="page-title">

                <h4>Ambulance Bookings</h4>

                <h6>
                    Manage ambulance booking requests
                </h6>

            </div>

        </div>


        {{-- =========================================================
        ALERTS
        ========================================================== --}}

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                <i class="ti ti-circle-check me-2"></i>

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show">

                <i class="ti ti-alert-circle me-2"></i>

                {{ session('error') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- =========================================================
        FILTER
        ========================================================== --}}

        <div class="card">

            <div class="card-body">

                <form method="GET"
                      action="{{ route(
                          'hospital.ambulance-bookings.index'
                      ) }}">

                    <div class="row g-3">


                        {{-- SEARCH --}}

                        <div class="col-xl-3 col-lg-3 col-md-6">

                            <label class="form-label">
                                Search
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="ti ti-search"></i>

                                </span>

                                <input type="text"
                                       name="search"
                                       class="form-control"
                                       placeholder="Booking number / patient"
                                       value="{{ request('search') }}">

                            </div>

                        </div>


                        {{-- BOOKING STATUS --}}

                        <div class="col-xl-3 col-lg-3 col-md-6">

                            <label class="form-label">
                                Booking Status
                            </label>

                            <select name="status"
                                    class="form-select">

                                <option value="">
                                    All Status
                                </option>

                                <option value="pending"
                                    {{ request('status') == 'pending'
                                        ? 'selected'
                                        : '' }}>

                                    Pending

                                </option>

                                <option value="accepted"
                                    {{ request('status') == 'accepted'
                                        ? 'selected'
                                        : '' }}>

                                    Accepted

                                </option>

                                <option value="ambulance_assigned"
                                    {{ request('status') == 'ambulance_assigned'
                                        ? 'selected'
                                        : '' }}>

                                    Ambulance Assigned

                                </option>

                                <option value="on_the_way"
                                    {{ request('status') == 'on_the_way'
                                        ? 'selected'
                                        : '' }}>

                                    On The Way

                                </option>

                                <option value="arrived"
                                    {{ request('status') == 'arrived'
                                        ? 'selected'
                                        : '' }}>

                                    Arrived

                                </option>

                                <option value="patient_picked"
                                    {{ request('status') == 'patient_picked'
                                        ? 'selected'
                                        : '' }}>

                                    Patient Picked

                                </option>

                                <option value="completed"
                                    {{ request('status') == 'completed'
                                        ? 'selected'
                                        : '' }}>

                                    Completed

                                </option>

                                <option value="rejected"
                                    {{ request('status') == 'rejected'
                                        ? 'selected'
                                        : '' }}>

                                    Rejected

                                </option>

                                <option value="cancelled"
                                    {{ request('status') == 'cancelled'
                                        ? 'selected'
                                        : '' }}>

                                    Cancelled

                                </option>

                            </select>

                        </div>


                        {{-- PAYMENT STATUS --}}

                        <div class="col-xl-3 col-lg-3 col-md-6">

                            <label class="form-label">
                                Payment Status
                            </label>

                            <select name="payment_status"
                                    class="form-select">

                                <option value="">
                                    All Payment Status
                                </option>

                                <option value="pending"
                                    {{ request('payment_status') == 'pending'
                                        ? 'selected'
                                        : '' }}>

                                    Pending

                                </option>

                                <option value="paid"
                                    {{ request('payment_status') == 'paid'
                                        ? 'selected'
                                        : '' }}>

                                    Paid

                                </option>

                                <option value="failed"
                                    {{ request('payment_status') == 'failed'
                                        ? 'selected'
                                        : '' }}>

                                    Failed

                                </option>

                                <option value="refunded"
                                    {{ request('payment_status') == 'refunded'
                                        ? 'selected'
                                        : '' }}>

                                    Refunded

                                </option>

                            </select>

                        </div>


                        {{-- DATE --}}

                        <div class="col-xl-2 col-lg-3 col-md-6">

                            <label class="form-label">
                                Date
                            </label>

                            <input type="date"
                                   name="date"
                                   class="form-control"
                                   value="{{ request('date') }}">

                        </div>


                        {{-- BUTTONS --}}

                        <div class="col-xl-1 col-lg-3 col-md-12 d-flex align-items-end">

                            <div class="d-flex gap-2">

                                <button type="submit"
                                        class="btn btn-primary">

                                    <i class="ti ti-search"></i>

                                </button>


                                <a href="{{ route(
                                    'hospital.ambulance-bookings.index'
                                ) }}"
                                   class="btn btn-light">

                                    <i class="ti ti-refresh"></i>

                                </a>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- =========================================================
        BOOKING TABLE
        ========================================================== --}}

        <div class="card">

            <div class="card-header">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <h5 class="card-title mb-0">

                            Ambulance Booking Requests

                        </h5>

                        <small class="text-muted">

                            {{ $bookings->total() }}

                            total bookings

                        </small>

                    </div>

                </div>

            </div>


            <div class="card-body">

                <div class="table-responsive">

                    <table class="table datanew">

                        <thead>

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Booking
                                </th>

                                <th>
                                    Pickup
                                </th>

                                <th>
                                    Destination
                                </th>

                                <th>
                                    Ambulance
                                </th>

                                <th>
                                    Amount
                                </th>

                                <th>
                                    Payment
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Date
                                </th>

                                <th class="text-end">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($bookings as $booking)

                                <tr>


                                    {{-- =================================================
                                    NUMBER
                                    ================================================== --}}

                                    <td>

                                        {{ $bookings->firstItem() + $loop->index }}

                                    </td>


                                    {{-- =================================================
                                    BOOKING
                                    ================================================== --}}

                                    <td>

                                        <div>

                                            <a href="{{ route(
                                                'hospital.ambulance-bookings.show',
                                                $booking->id
                                            ) }}"
                                               class="fw-semibold text-primary">

                                                {{ $booking->booking_no }}

                                            </a>


                                            @if($booking->is_emergency)

                                                <span class="badge bg-danger ms-1">

                                                    Emergency

                                                </span>

                                            @endif

                                        </div>


                                        @if($booking->appointment_no)

                                            <small class="text-muted">

                                                Appointment:
                                                {{ $booking->appointment_no }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- =================================================
                                    PICKUP
                                    ================================================== --}}

                                    <td>

                                        <div class="text-truncate"
                                             style="max-width:180px;"
                                             title="{{ $booking->pickup_address }}">

                                            <i class="ti ti-map-pin text-danger me-1"></i>

                                            {{ $booking->pickup_address ?? '-' }}

                                        </div>


                                        @if($booking->pickup_city)

                                            <small class="text-muted">

                                                {{ $booking->pickup_city }}

                                                @if($booking->pickup_pincode)

                                                    -
                                                    {{ $booking->pickup_pincode }}

                                                @endif

                                            </small>

                                        @endif

                                    </td>


                                    {{-- =================================================
                                    DESTINATION
                                    ================================================== --}}

                                    <td>

                                        <div class="text-truncate"
                                             style="max-width:180px;"
                                             title="{{ $booking->destination_address }}">

                                            <i class="ti ti-map-pin text-success me-1"></i>

                                            {{ $booking->destination_address ?? '-' }}

                                        </div>


                                        @if($booking->destination_city)

                                            <small class="text-muted">

                                                {{ $booking->destination_city }}

                                                @if($booking->destination_pincode)

                                                    -
                                                    {{ $booking->destination_pincode }}

                                                @endif

                                            </small>

                                        @endif

                                    </td>


                                    {{-- =================================================
                                    AMBULANCE
                                    ================================================== --}}

                                    <td>

                                        @if($booking->ambulance)

                                            <div class="d-flex align-items-center">

                                                <div class="avatar avatar-sm
                                                            bg-light-primary
                                                            rounded
                                                            me-2">

                                                    <i class="ti ti-ambulance text-primary"></i>

                                                </div>


                                                <div>

                                                    <h6 class="mb-0">

                                                        {{ $booking->ambulance->ambulance_name
                                                            ?? 'Ambulance' }}

                                                    </h6>


                                                    <small class="text-muted">

                                                        {{ $booking->ambulance->vehicle_number
                                                            ?? '-' }}

                                                    </small>

                                                </div>

                                            </div>

                                        @else

                                            <span class="badge bg-warning-subtle text-warning">

                                                Not Assigned

                                            </span>

                                        @endif

                                    </td>


                                    {{-- =================================================
                                    AMOUNT
                                    ================================================== --}}

                                    <td>

                                        <span class="fw-semibold">

                                            ₹{{ number_format(
                                                (float)($booking->total_amount ?? 0),
                                                2
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- =================================================
                                    PAYMENT STATUS
                                    ================================================== --}}

                                    <td>

                                        @switch($booking->payment_status)

                                            @case('paid')

                                                <span class="badge bg-success">

                                                    Paid

                                                </span>

                                                @break


                                            @case('failed')

                                                <span class="badge bg-danger">

                                                    Failed

                                                </span>

                                                @break


                                            @case('refunded')

                                                <span class="badge bg-info">

                                                    Refunded

                                                </span>

                                                @break


                                            @default

                                                <span class="badge bg-warning">

                                                    Pending

                                                </span>

                                        @endswitch

                                    </td>


                                    {{-- =================================================
                                    BOOKING STATUS
                                    ================================================== --}}

                                    <td>

                                        @switch($booking->booking_status)


                                            @case('pending')

                                                <span class="badge bg-warning">

                                                    Pending

                                                </span>

                                                @break


                                            @case('accepted')

                                                <span class="badge bg-info">

                                                    Accepted

                                                </span>

                                                @break


                                            @case('ambulance_assigned')

                                                <span class="badge bg-primary">

                                                    Ambulance Assigned

                                                </span>

                                                @break


                                            @case('on_the_way')

                                                <span class="badge bg-warning">

                                                    On The Way

                                                </span>

                                                @break


                                            @case('arrived')

                                                <span class="badge bg-info">

                                                    Arrived

                                                </span>

                                                @break


                                            @case('patient_picked')

                                                <span class="badge bg-primary">

                                                    Patient Picked

                                                </span>

                                                @break


                                            @case('completed')

                                                <span class="badge bg-success">

                                                    Completed

                                                </span>

                                                @break


                                            @case('rejected')

                                                <span class="badge bg-danger">

                                                    Rejected

                                                </span>

                                                @break


                                            @case('cancelled')

                                                <span class="badge bg-secondary">

                                                    Cancelled

                                                </span>

                                                @break


                                            @default

                                                <span class="badge bg-light text-dark">

                                                    {{ ucwords(
                                                        str_replace(
                                                            '_',
                                                            ' ',
                                                            $booking->booking_status ?? '-'
                                                        )
                                                    ) }}

                                                </span>

                                        @endswitch

                                    </td>


                                    {{-- =================================================
                                    DATE
                                    ================================================== --}}

                                    <td>

                                        @if($booking->booking_date)

                                            <span class="fw-semibold">

                                                {{ \Carbon\Carbon::parse(
                                                    $booking->booking_date
                                                )->format('d M Y') }}

                                            </span>

                                        @else

                                            -

                                        @endif


                                        @if($booking->booking_time)

                                            <small class="d-block text-muted">

                                                {{ \Carbon\Carbon::parse(
                                                    $booking->booking_time
                                                )->format('h:i A') }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- =================================================
                                    ACTION
                                    ================================================== --}}

                                    <td>

                                        <div class="action-table">

                                            <a href="javascript:void(0);"
                                               class="action-btn bg-light border dropdown-toggle"
                                               data-bs-toggle="dropdown">

                                                <i class="ti ti-dots-vertical"></i>

                                            </a>


                                            <ul class="dropdown-menu dropdown-menu-end">


                                                {{-- VIEW DETAILS --}}

                                                <li>

                                                    <a class="dropdown-item"
                                                       href="{{ route(
                                                           'hospital.ambulance-bookings.show',
                                                           $booking->id
                                                       ) }}">

                                                        <i class="ti ti-eye me-2"></i>

                                                        View Details

                                                    </a>

                                                </li>


                                                {{-- TRACK AMBULANCE --}}

                                                @if(
                                                    $booking->ambulance_id &&
                                                    in_array(
                                                        $booking->booking_status,
                                                        [
                                                            'ambulance_assigned',
                                                            'on_the_way',
                                                            'arrived',
                                                            'patient_picked'
                                                        ]
                                                    )
                                                )

                                                    <li>

                                                        <a class="dropdown-item"
                                                           href="{{ route(
                                                               'hospital.ambulance-bookings.track',
                                                               $booking->id
                                                           ) }}" target="_blank">

                                                            <i class="ti ti-map-2 me-2"></i>

                                                            Track Ambulance

                                                        </a>

                                                    </li>

                                                @endif


                                            </ul>

                                        </div>

                                    </td>


                                </tr>

                            @empty


                                {{-- =================================================
                                EMPTY
                                ================================================== --}}

                                <tr>

                                    <td colspan="10"
                                        class="text-center py-5">

                                        <div>

                                            <div class="avatar avatar-xl
                                                        bg-light-primary
                                                        rounded-circle
                                                        mx-auto
                                                        mb-3">

                                                <i class="ti ti-ambulance-off text-primary"
                                                   style="font-size:35px;">
                                                </i>

                                            </div>


                                            <h5>
                                                No Ambulance Bookings Found
                                            </h5>


                                            <p class="text-muted mb-0">

                                                No ambulance bookings match
                                                your current filters.

                                            </p>

                                        </div>

                                    </td>

                                </tr>


                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- =========================================================
            PAGINATION
            ========================================================== --}}

            @if($bookings->hasPages())

                <div class="card-footer">

                    {{ $bookings->withQueryString()->links('pagination::bootstrap-5') }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection