@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- PAGE HEADER --}}
        <div class="page-header">
            <div class="page-title">
                <h4>Ambulance Booking Details</h4>
                <h6>View and manage ambulance booking</h6>
            </div>

            <div class="page-btn">
                <a href="{{ route('hospital.ambulance-bookings.index') }}"
                   class="btn btn-light">
                    <i class="ti ti-arrow-left me-1"></i>
                    Back
                </a>
            </div>
        </div>


        {{-- SUCCESS --}}
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


        {{-- ERROR --}}
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


        <div class="row">

            {{-- ===================================================== --}}
            {{-- LEFT SIDE --}}
            {{-- ===================================================== --}}

            <div class="col-xl-8">

                {{-- BOOKING INFORMATION --}}
                <div class="card">

                    <div class="card-header">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <h5 class="card-title mb-1">
                                    Booking Information
                                </h5>

                                <span class="text-muted">
                                    {{ $booking->booking_no ?? '-' }}
                                </span>
                            </div>

                            <div>

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
                                            {{ ucfirst(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $booking->booking_status
                                                )
                                            ) }}
                                        </span>

                                @endswitch

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        <div class="row g-4">

                            {{-- BOOKING NUMBER --}}
                            <div class="col-md-4">

                                <div class="text-muted small">
                                    Booking Number
                                </div>

                                <div class="fw-semibold mt-1">
                                    {{ $booking->booking_no ?? '-' }}
                                </div>

                            </div>


                            {{-- CREATED --}}
                            <div class="col-md-4">

                                <div class="text-muted small">
                                    Created At
                                </div>

                                <div class="fw-semibold mt-1">

                                    @if($booking->created_at)

                                        {{ $booking->created_at->format(
                                            'd M Y, h:i A'
                                        ) }}

                                    @else

                                        -

                                    @endif

                                </div>

                            </div>


                            {{-- EMERGENCY --}}
                            <div class="col-md-4">

                                <div class="text-muted small">
                                    Emergency
                                </div>

                                <div class="mt-1">

                                    @if($booking->is_emergency)

                                        <span class="badge bg-danger">

                                            <i class="ti ti-alert-triangle me-1"></i>

                                            Emergency

                                        </span>

                                    @else

                                        <span class="badge bg-light text-dark">
                                            Normal
                                        </span>

                                    @endif

                                </div>

                            </div>


                            {{-- PAYMENT METHOD --}}
                            <div class="col-md-4">

                                <div class="text-muted small">
                                    Payment Method
                                </div>

                                <div class="fw-semibold mt-1">

                                    {{ $booking->payment_method
                                        ? ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $booking->payment_method
                                            )
                                        )
                                        : '-' }}

                                </div>

                            </div>


                            {{-- PAYMENT STATUS --}}
                            <div class="col-md-4">

                                <div class="text-muted small">
                                    Payment Status
                                </div>

                                <div class="mt-1">

                                    @if($booking->payment_status === 'paid')

                                        <span class="badge bg-success">
                                            Paid
                                        </span>

                                    @elseif($booking->payment_status === 'failed')

                                        <span class="badge bg-danger">
                                            Failed
                                        </span>

                                    @elseif($booking->payment_status === 'refunded')

                                        <span class="badge bg-info">
                                            Refunded
                                        </span>

                                    @else

                                        <span class="badge bg-warning">
                                            Pending
                                        </span>

                                    @endif

                                </div>

                            </div>


                            {{-- AMOUNT --}}
                            <div class="col-md-4">

                                <div class="text-muted small">
                                    Total Amount
                                </div>

                                <div class="fw-bold fs-18 mt-1">

                                    ₹{{ number_format(
                                        (float)($booking->total_amount ?? 0),
                                        2
                                    ) }}

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- PATIENT INFORMATION --}}
                {{-- ================================================= --}}

                @if(
                    isset($booking->customer) ||
                    isset($booking->familyMember)
                )

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Patient Information
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row g-4">

                                @if(isset($booking->customer))

                                    <div class="col-md-4">

                                        <span class="text-muted small">
                                            Customer
                                        </span>

                                        <h6 class="mt-1 mb-0">
                                            {{ $booking->customer->name ?? '-' }}
                                        </h6>

                                    </div>

                                    <div class="col-md-4">

                                        <span class="text-muted small">
                                            Mobile
                                        </span>

                                        <h6 class="mt-1 mb-0">
                                            {{ $booking->customer->mobile ?? '-' }}
                                        </h6>

                                    </div>

                                @endif


                                @if(isset($booking->familyMember))

                                    <div class="col-md-4">

                                        <span class="text-muted small">
                                            Family Member
                                        </span>

                                        <h6 class="mt-1 mb-0">
                                            {{ $booking->familyMember->name ?? '-' }}
                                        </h6>

                                    </div>

                                    <div class="col-md-4">

                                        <span class="text-muted small">
                                            Relation
                                        </span>

                                        <h6 class="mt-1 mb-0">
                                            {{ $booking->familyMember->relation ?? '-' }}
                                        </h6>

                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>

                @endif


                {{-- ================================================= --}}
                {{-- TRIP DETAILS --}}
                {{-- ================================================= --}}

                <div class="card">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Trip Details
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row">

                            {{-- PICKUP --}}
                            <div class="col-md-6">

                                <div class="d-flex">

                                    <div class="avatar avatar-md
                                                bg-danger-subtle
                                                rounded me-3">

                                        <i class="ti ti-map-pin text-danger"></i>

                                    </div>

                                    <div>

                                        <span class="text-muted d-block">
                                            Pickup Location
                                        </span>

                                        <h6 class="mb-1">
                                            {{ $booking->pickup_address ?? '-' }}
                                        </h6>

                                        <small class="text-muted">

                                            {{ $booking->pickup_city ?? '' }}

                                            @if($booking->pickup_state)
                                                , {{ $booking->pickup_state }}
                                            @endif

                                            @if($booking->pickup_pincode)
                                                - {{ $booking->pickup_pincode }}
                                            @endif

                                        </small>

                                    </div>

                                </div>

                            </div>


                            {{-- DESTINATION --}}
                            <div class="col-md-6 mt-4 mt-md-0">

                                <div class="d-flex">

                                    <div class="avatar avatar-md
                                                bg-success-subtle
                                                rounded me-3">

                                        <i class="ti ti-map-pin text-success"></i>

                                    </div>

                                    <div>

                                        <span class="text-muted d-block">
                                            Destination
                                        </span>

                                        <h6 class="mb-1">
                                            {{ $booking->destination_address ?? '-' }}
                                        </h6>

                                        <small class="text-muted">

                                            {{ $booking->destination_city ?? '' }}

                                            @if($booking->destination_state)
                                                , {{ $booking->destination_state }}
                                            @endif

                                            @if($booking->destination_pincode)
                                                - {{ $booking->destination_pincode }}
                                            @endif

                                        </small>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- DISTANCE --}}
                        @if($booking->distance_km)

                            <hr>

                            <div class="d-flex align-items-center">

                                <i class="ti ti-route me-2 text-primary"></i>

                                <span class="text-muted me-2">
                                    Distance:
                                </span>

                                <strong>
                                    {{ $booking->distance_km }} KM
                                </strong>

                            </div>

                        @endif

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- EMERGENCY NOTES --}}
                {{-- ================================================= --}}

                @if($booking->emergency_notes)

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Emergency Notes
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="alert alert-danger mb-0">

                                <i class="ti ti-alert-triangle me-2"></i>

                                {{ $booking->emergency_notes }}

                            </div>

                        </div>

                    </div>

                @endif


                {{-- ================================================= --}}
                {{-- BOOKING TIMELINE --}}
                {{-- ================================================= --}}

                <div class="card">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Booking Timeline
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="timeline">

                            {{-- CREATED --}}
                            @if($booking->created_at)

                                <div class="timeline-item">

                                    <div class="timeline-marker bg-primary"></div>

                                    <div class="timeline-content">

                                        <h6 class="mb-1">
                                            Booking Created
                                        </h6>

                                        <small class="text-muted">

                                            {{ $booking->created_at->format(
                                                'd M Y, h:i A'
                                            ) }}

                                        </small>

                                    </div>

                                </div>

                            @endif


                            {{-- ACCEPTED --}}
                            @if($booking->accepted_at)

                                <div class="timeline-item">

                                    <div class="timeline-marker bg-info"></div>

                                    <div class="timeline-content">

                                        <h6 class="mb-1">
                                            Booking Accepted
                                        </h6>

                                        <small class="text-muted">

                                            {{ $booking->accepted_at->format(
                                                'd M Y, h:i A'
                                            ) }}

                                        </small>

                                    </div>

                                </div>

                            @endif


                            {{-- AMBULANCE ASSIGNED --}}
                            @if($booking->assigned_at)

                                <div class="timeline-item">

                                    <div class="timeline-marker bg-primary"></div>

                                    <div class="timeline-content">

                                        <h6 class="mb-1">
                                            Ambulance Assigned
                                        </h6>

                                        <small class="text-muted">

                                            {{ $booking->assigned_at->format(
                                                'd M Y, h:i A'
                                            ) }}

                                        </small>

                                    </div>

                                </div>

                            @endif


                            {{-- ON THE WAY --}}
                            @if($booking->on_the_way_at)

                                <div class="timeline-item">

                                    <div class="timeline-marker bg-warning"></div>

                                    <div class="timeline-content">

                                        <h6 class="mb-1">
                                            Ambulance On The Way
                                        </h6>

                                        <small class="text-muted">

                                            {{ $booking->on_the_way_at->format(
                                                'd M Y, h:i A'
                                            ) }}

                                        </small>

                                    </div>

                                </div>

                            @endif


                            {{-- ARRIVED --}}
                            @if($booking->arrived_at)

                                <div class="timeline-item">

                                    <div class="timeline-marker bg-info"></div>

                                    <div class="timeline-content">

                                        <h6 class="mb-1">
                                            Ambulance Arrived
                                        </h6>

                                        <small class="text-muted">

                                            {{ $booking->arrived_at->format(
                                                'd M Y, h:i A'
                                            ) }}

                                        </small>

                                    </div>

                                </div>

                            @endif


                            {{-- PATIENT PICKED --}}
                            @if($booking->patient_picked_at)

                                <div class="timeline-item">

                                    <div class="timeline-marker bg-primary"></div>

                                    <div class="timeline-content">

                                        <h6 class="mb-1">
                                            Patient Picked
                                        </h6>

                                        <small class="text-muted">

                                            {{ $booking->patient_picked_at->format(
                                                'd M Y, h:i A'
                                            ) }}

                                        </small>

                                    </div>

                                </div>

                            @endif


                            {{-- COMPLETED --}}
                            @if($booking->completed_at)

                                <div class="timeline-item">

                                    <div class="timeline-marker bg-success"></div>

                                    <div class="timeline-content">

                                        <h6 class="mb-1">
                                            Trip Completed
                                        </h6>

                                        <small class="text-muted">

                                            {{ $booking->completed_at->format(
                                                'd M Y, h:i A'
                                            ) }}

                                        </small>

                                    </div>

                                </div>

                            @endif


                            {{-- REJECTED --}}
                            @if($booking->rejected_at)

                                <div class="timeline-item">

                                    <div class="timeline-marker bg-danger"></div>

                                    <div class="timeline-content">

                                        <h6 class="mb-1">
                                            Booking Rejected
                                        </h6>

                                        <small class="text-muted">

                                            {{ $booking->rejected_at->format(
                                                'd M Y, h:i A'
                                            ) }}

                                        </small>

                                    </div>

                                </div>

                            @endif


                            {{-- CANCELLED --}}
                            @if($booking->cancelled_at)

                                <div class="timeline-item">

                                    <div class="timeline-marker bg-secondary"></div>

                                    <div class="timeline-content">

                                        <h6 class="mb-1">
                                            Booking Cancelled
                                        </h6>

                                        <small class="text-muted">

                                            {{ $booking->cancelled_at->format(
                                                'd M Y, h:i A'
                                            ) }}

                                        </small>

                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- RIGHT SIDE --}}
            {{-- ===================================================== --}}

            <div class="col-xl-4">


                {{-- ================================================= --}}
                {{-- ASSIGNED AMBULANCE --}}
                {{-- ================================================= --}}

                <div class="card">

                    <div class="card-header">

                        <div class="d-flex justify-content-between align-items-center">

                            <h5 class="card-title mb-0">
                                Ambulance
                            </h5>

                            @if($booking->ambulance)

                                <span class="badge bg-success">
                                    Assigned
                                </span>

                            @else

                                <span class="badge bg-warning">
                                    Not Assigned
                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="card-body">

                        @if($booking->ambulance)

                            <div class="text-center mb-4">

                                <div class="avatar avatar-xl
                                            bg-light-primary
                                            rounded-circle
                                            mx-auto mb-3">

                                    <i class="ti ti-ambulance text-primary"
                                       style="font-size:35px;">
                                    </i>

                                </div>

                                <h5 class="mb-1">

                                    {{ $booking->ambulance->ambulance_name
                                        ?? 'Ambulance' }}

                                </h5>

                                <p class="text-muted mb-0">

                                    {{ $booking->ambulance->vehicle_number
                                        ?? '-' }}

                                </p>

                            </div>


                            <div class="border rounded p-3">

                                <div class="d-flex justify-content-between mb-3">

                                    <span class="text-muted">
                                        Vehicle Number
                                    </span>

                                    <strong>
                                        {{ $booking->ambulance->vehicle_number ?? '-' }}
                                    </strong>

                                </div>


                                <div class="d-flex justify-content-between mb-3">

                                    <span class="text-muted">
                                        Status
                                    </span>

                                    @if($booking->ambulance->status === 'active')

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            Inactive
                                        </span>

                                    @endif

                                </div>


                                <div class="d-flex justify-content-between">

                                    <span class="text-muted">
                                        Availability
                                    </span>

                                    @if($booking->ambulance->is_available)

                                        <span class="badge bg-success">
                                            Available
                                        </span>

                                    @else

                                        <span class="badge bg-warning">
                                            Busy
                                        </span>

                                    @endif

                                </div>

                            </div>


                            {{-- TRACK BUTTON --}}
                            @if(
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

                                <a href="{{ route(
                                    'hospital.ambulance-bookings.track',
                                    $booking->id
                                ) }}" target="_blank"
                                   class="btn btn-primary w-100 mt-3">

                                    <i class="ti ti-map-2 me-1"></i>

                                    Track Ambulance

                                </a>

                            @endif

                        @else


                            {{-- ===================================== --}}
                            {{-- ASSIGN AMBULANCE --}}
                            {{-- ===================================== --}}

                            @if(
                                in_array(
                                    $booking->booking_status,
                                    [
                                        'pending',
                                        'accepted'
                                    ]
                                )
                            )

                                <form method="POST"
                                      action="{{ route(
                                          'hospital.ambulance-bookings.assign'
                                      ) }}">

                                    @csrf

                                    <input type="hidden"
                                           name="booking_id"
                                           value="{{ $booking->id }}">


                                    <div class="mb-3">

                                        <label class="form-label">

                                            Select Ambulance

                                            <span class="text-danger">
                                                *
                                            </span>

                                        </label>


                                        <select name="ambulance_id"
                                                class="form-select"
                                                required>

                                            <option value="">
                                                Select Ambulance
                                            </option>


                                            @forelse($ambulances as $ambulance)

                                                <option value="{{ $ambulance->id }}">

                                                    {{ $ambulance->ambulance_name
                                                        ?? 'Ambulance' }}

                                                    -

                                                    {{ $ambulance->vehicle_number
                                                        ?? '-' }}

                                                </option>

                                            @empty

                                                <option value="">
                                                    No available ambulance
                                                </option>

                                            @endforelse

                                        </select>

                                    </div>


                                    <button type="submit"
                                            class="btn btn-primary w-100"
                                            @disabled($ambulances->isEmpty())>

                                        <i class="ti ti-ambulance me-1"></i>

                                        Assign Ambulance

                                    </button>

                                </form>

                            @else

                                <div class="text-center py-4">

                                    <i class="ti ti-ambulance-off text-muted"
                                       style="font-size:45px;">
                                    </i>

                                    <p class="text-muted mt-2 mb-0">

                                        Ambulance cannot be assigned
                                        for this booking.

                                    </p>

                                </div>

                            @endif

                        @endif

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- PAYMENT --}}
                {{-- ================================================= --}}

                <div class="card">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Payment
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Total Amount
                            </span>

                            <strong>

                                ₹{{ number_format(
                                    (float)($booking->total_amount ?? 0),
                                    2
                                ) }}

                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Payment Method
                            </span>

                            <span>

                                {{ $booking->payment_method
                                    ? ucfirst(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $booking->payment_method
                                        )
                                    )
                                    : '-' }}

                            </span>

                        </div>


                        <div class="d-flex justify-content-between">

                            <span class="text-muted">
                                Status
                            </span>


                            @if($booking->payment_status === 'paid')

                                <span class="badge bg-success">
                                    Paid
                                </span>

                            @elseif($booking->payment_status === 'failed')

                                <span class="badge bg-danger">
                                    Failed
                                </span>

                            @elseif($booking->payment_status === 'refunded')

                                <span class="badge bg-info">
                                    Refunded
                                </span>

                            @else

                                <span class="badge bg-warning">
                                    Pending
                                </span>

                            @endif

                        </div>


                        {{-- PAYMENT STATUS --}}
                        @if($booking->booking_status === 'completed')

                            <hr>

                            <form method="POST"
                                  action="{{ route(
                                      'hospital.ambulance-bookings.payment-status'
                                  ) }}">

                                @csrf

                                <input type="hidden"
                                       name="booking_id"
                                       value="{{ $booking->id }}">


                                <label class="form-label">
                                    Update Payment Status
                                </label>


                                <select name="payment_status"
                                        class="form-select mb-3"
                                        required>

                                    <option value="pending"
                                        {{ $booking->payment_status === 'pending'
                                            ? 'selected'
                                            : '' }}>
                                        Pending
                                    </option>

                                    <option value="paid"
                                        {{ $booking->payment_status === 'paid'
                                            ? 'selected'
                                            : '' }}>
                                        Paid
                                    </option>

                                    <option value="failed"
                                        {{ $booking->payment_status === 'failed'
                                            ? 'selected'
                                            : '' }}>
                                        Failed
                                    </option>

                                    <option value="refunded"
                                        {{ $booking->payment_status === 'refunded'
                                            ? 'selected'
                                            : '' }}>
                                        Refunded
                                    </option>

                                </select>


                                <button type="submit"
                                        class="btn btn-success w-100">

                                    <i class="ti ti-check me-1"></i>

                                    Update Payment

                                </button>

                            </form>

                        @endif

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- REJECT BOOKING --}}
                {{-- ================================================= --}}

                @if(
                    in_array(
                        $booking->booking_status,
                        [
                            'pending',
                            'accepted'
                        ]
                    )
                )

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Booking Actions
                            </h5>

                        </div>


                        <div class="card-body">

                            <form method="POST"
                                  action="{{ route(
                                      'hospital.ambulance-bookings.reject'
                                  ) }}">

                                @csrf

                                <input type="hidden"
                                       name="booking_id"
                                       value="{{ $booking->id }}">


                                <div class="mb-3">

                                    <label class="form-label">
                                        Reject Reason
                                    </label>

                                    <textarea name="reject_reason"
                                              class="form-control"
                                              rows="3"
                                              placeholder="Enter reason..."></textarea>

                                </div>


                                <button type="submit"
                                        class="btn btn-danger w-100"
                                        onclick="return confirm(
                                            'Are you sure you want to reject this booking?'
                                        )">

                                    <i class="ti ti-x me-1"></i>

                                    Reject Booking

                                </button>

                            </form>

                        </div>

                    </div>

                @endif


            </div>

        </div>

    </div>
</div>

@endsection