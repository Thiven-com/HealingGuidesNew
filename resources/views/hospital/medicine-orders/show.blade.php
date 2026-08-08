@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">

    <div class="content">

        {{-- =========================================================
        PAGE HEADER
        ========================================================== --}}

        <div class="page-header">

            <div class="page-title">

                <h4>Medicine Order Details</h4>

                <h6>View and manage medicine order</h6>

            </div>

            <div class="page-btn">

                <a href="{{ route('hospital.medicine-orders.index') }}"
                   class="btn btn-light">

                    <i class="ti ti-arrow-left me-1"></i>
                    Back to Orders

                </a>

            </div>

        </div>


        {{-- =========================================================
        ALERTS
        ========================================================== --}}

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                <i class="ti ti-circle-check me-1"></i>

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show">

                <i class="ti ti-alert-circle me-1"></i>

                {{ session('error') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- =========================================================
        ORDER HEADER
        ========================================================== --}}

        <div class="card">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-lg-8">

                        <div class="d-flex align-items-center">

                            <div class="
                                avatar
                                avatar-lg
                                bg-primary
                                rounded
                                d-flex
                                align-items-center
                                justify-content-center
                                me-3
                            ">

                                <i class="
                                    ti
                                    ti-shopping-cart
                                    text-white
                                    fs-24
                                "></i>

                            </div>

                            <div>

                                <h4 class="mb-1">

                                    Order #{{ $order->order_no }}

                                </h4>

                                <p class="text-muted mb-0">

                                    <i class="ti ti-calendar me-1"></i>

                                    {{ $order->created_at
                                        ? $order->created_at->format('d M Y, h:i A')
                                        : '-' }}

                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">

                        @php
                            $status = strtolower(
                                $order->order_status ?? 'pending'
                            );
                        @endphp


                        @switch($status)

                            @case('pending')

                                <span class="badge bg-warning fs-6">
                                    Pending
                                </span>

                                @break

                            @case('accepted')

                                <span class="badge bg-primary fs-6">
                                    Accepted
                                </span>

                                @break

                            @case('processing')

                                <span class="badge bg-info fs-6">
                                    Processing
                                </span>

                                @break

                            @case('ready')

                                <span class="badge bg-primary fs-6">
                                    Ready
                                </span>

                                @break

                            @case('dispatched')

                                <span class="badge bg-secondary fs-6">
                                    Dispatched
                                </span>

                                @break

                            @case('delivered')

                                <span class="badge bg-success fs-10">
                                    Delivered
                                </span>

                                @break

                            @case('rejected')

                                <span class="badge bg-danger fs-6">
                                    Rejected
                                </span>

                                @break

                            @case('cancelled')

                                <span class="badge bg-danger fs-6">
                                    Cancelled
                                </span>

                                @break

                            @default

                                <span class="badge bg-secondary fs-6">
                                    {{ ucfirst($status) }}
                                </span>

                        @endswitch

                    </div>

                </div>

            </div>

        </div>


        <div class="row">


            {{-- =====================================================
            CUSTOMER DETAILS
            ====================================================== --}}

            <div class="col-lg-6">

                <div class="card h-100">

                    <div class="card-header">

                        <h5 class="card-title mb-0">

                            <i class="ti ti-user me-2"></i>

                            Customer Details

                        </h5>

                    </div>

                    <div class="card-body">

                        @if($order->customer)

                            <div class="d-flex align-items-center mb-4">

                                <div class="
                                    avatar
                                    avatar-lg
                                    bg-light-primary
                                    rounded-circle
                                    d-flex
                                    align-items-center
                                    justify-content-center
                                    me-3
                                ">

                                    <i class="
                                        ti
                                        ti-user
                                        text-primary
                                        fs-24
                                    "></i>

                                </div>

                                <div>

                                    <h5 class="mb-1">

                                        {{ $order->customer->name ?? 'Customer' }}

                                    </h5>

                                    @if($order->customer->mobile)

                                        <p class="text-muted mb-0">

                                            <i class="ti ti-phone me-1"></i>

                                            {{ $order->customer->mobile }}

                                        </p>

                                    @endif

                                </div>

                            </div>

                        @else

                            <div class="text-muted">
                                Customer information unavailable.
                            </div>

                        @endif


                        @if($order->family_member_id)

                            <div class="mb-3">

                                <small class="text-muted d-block">
                                    Family Member
                                </small>

                                <strong>
                                    {{ $order->familyMember->name ?? '-' }}
                                </strong>

                            </div>

                        @endif


                        @if($order->prescription_id)

                            <div>

                                <small class="text-muted d-block">
                                    Prescription ID
                                </small>

                                <strong>
                                    #{{ $order->prescription_id }}
                                </strong>

                            </div>

                        @endif

                    </div>

                </div>

            </div>


            {{-- =====================================================
            DELIVERY DETAILS
            ====================================================== --}}

            <div class="col-lg-6">

                <div class="card h-100">

                    <div class="card-header">

                        <h5 class="card-title mb-0">

                            <i class="ti ti-map-pin me-2"></i>

                            Delivery Address

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Address
                            </small>

                            <strong>
                                {{ $order->delivery_address ?? '-' }}
                            </strong>

                        </div>


                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <small class="text-muted d-block">
                                    City
                                </small>

                                <strong>
                                    {{ $order->delivery_city ?? '-' }}
                                </strong>

                            </div>


                            <div class="col-md-6 mb-3">

                                <small class="text-muted d-block">
                                    State
                                </small>

                                <strong>
                                    {{ $order->delivery_state ?? '-' }}
                                </strong>

                            </div>


                            <div class="col-md-6">

                                <small class="text-muted d-block">
                                    Pincode
                                </small>

                                <strong>
                                    {{ $order->delivery_pincode ?? '-' }}
                                </strong>

                            </div>


                            @if(
                                $order->delivery_latitude &&
                                $order->delivery_longitude
                            )

                                <div class="col-md-6">

                                    <a href="https://www.google.com/maps?q={{ $order->delivery_latitude }},{{ $order->delivery_longitude }}"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-primary">

                                        <i class="ti ti-map me-1"></i>

                                        View Map

                                    </a>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


        </div>


        {{-- =========================================================
        ORDER ITEMS
        ========================================================== --}}

        <div class="card">

            <div class="card-header">

                <h5 class="card-title mb-0">

                    <i class="ti ti-pill me-2"></i>

                    Ordered Medicines

                </h5>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table mb-0">

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>Medicine</th>

                                <th>Code</th>

                                <th>Strength</th>

                                <th>Pack Size</th>

                                <th>Qty</th>

                                <th>MRP</th>

                                <th>Price</th>

                                <th>Total</th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($items as $item)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        <strong>

                                            {{ $item->medicine_name }}

                                        </strong>

                                    </td>


                                    <td>

                                        {{ $item->medicine_code ?? '-' }}

                                    </td>


                                    <td>

                                        {{ $item->strength ?? '-' }}

                                    </td>


                                    <td>

                                        {{ $item->pack_size ?? '-' }}

                                    </td>


                                    <td>

                                        <span class="badge bg-light-primary text-primary">

                                            {{ $item->quantity }}

                                        </span>

                                    </td>


                                    <td>

                                        ₹{{ number_format(
                                            $item->mrp ?? 0,
                                            2
                                        ) }}

                                    </td>


                                    <td>

                                        ₹{{ number_format(
                                            $item->price ?? 0,
                                            2
                                        ) }}

                                    </td>


                                    <td>

                                        <strong>

                                            ₹{{ number_format(
                                                $item->total ?? 0,
                                                2
                                            ) }}

                                        </strong>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="9"
                                        class="text-center py-4">

                                        <span class="text-muted">
                                            No medicines found in this order.
                                        </span>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <div class="row">


            {{-- =====================================================
            PAYMENT DETAILS
            ====================================================== --}}

            <div class="col-lg-6">

                <div class="card">

                    <div class="card-header">

                        <h5 class="card-title mb-0">

                            <i class="ti ti-credit-card me-2"></i>

                            Payment Details

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table table-borderless mb-0">

                                <tr>

                                    <td class="text-muted">
                                        Payment Method
                                    </td>

                                    <td class="text-end fw-semibold">
                                        {{ strtoupper(
                                            $order->payment_method ?? '-'
                                        ) }}
                                    </td>

                                </tr>


                                <tr>

                                    <td class="text-muted">
                                        Payment Status
                                    </td>

                                    <td class="text-end">

                                        @php
                                            $paymentStatus =
                                                strtolower(
                                                    $order->payment_status ?? 'pending'
                                                );
                                        @endphp


                                        @if($paymentStatus === 'paid')

                                            <span class="badge bg-success">
                                                Paid
                                            </span>

                                        @elseif($paymentStatus === 'failed')

                                            <span class="badge bg-danger">
                                                Failed
                                            </span>

                                        @elseif($paymentStatus === 'refunded')

                                            <span class="badge bg-info">
                                                Refunded
                                            </span>

                                        @else

                                            <span class="badge bg-warning">
                                                Pending
                                            </span>

                                        @endif

                                    </td>

                                </tr>


                                <tr>

                                    <td class="text-muted">
                                        Transaction ID
                                    </td>

                                    <td class="text-end fw-semibold">

                                        {{ $order->transaction_id ?? '-' }}

                                    </td>

                                </tr>


                                <tr>

                                    <td class="text-muted">
                                        Payment ID
                                    </td>

                                    <td class="text-end fw-semibold">

                                        {{ $order->payment_id ?? '-' }}

                                    </td>

                                </tr>

                            </table>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
            ORDER SUMMARY
            ====================================================== --}}

            <div class="col-lg-6">

                <div class="card">

                    <div class="card-header">

                        <h5 class="card-title mb-0">

                            <i class="ti ti-file-invoice me-2"></i>

                            Order Summary

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="d-flex
                                    justify-content-between
                                    mb-3">

                            <span class="text-muted">
                                Subtotal
                            </span>

                            <strong>

                                ₹{{ number_format(
                                    $order->subtotal ?? 0,
                                    2
                                ) }}

                            </strong>

                        </div>


                        <div class="d-flex
                                    justify-content-between
                                    mb-3">

                            <span class="text-muted">
                                Delivery Charge
                            </span>

                            <strong>

                                ₹{{ number_format(
                                    $order->delivery_charge ?? 0,
                                    2
                                ) }}

                            </strong>

                        </div>


                        <div class="d-flex
                                    justify-content-between
                                    mb-3">

                            <span class="text-muted">
                                Discount
                            </span>

                            <strong class="text-success">

                                - ₹{{ number_format(
                                    $order->discount ?? 0,
                                    2
                                ) }}

                            </strong>

                        </div>


                        <div class="d-flex
                                    justify-content-between
                                    mb-3">

                            <span class="text-muted">
                                Tax
                            </span>

                            <strong>

                                ₹{{ number_format(
                                    $order->tax ?? 0,
                                    2
                                ) }}

                            </strong>

                        </div>


                        <hr>


                        <div class="d-flex
                                    justify-content-between">

                            <h5 class="mb-0">
                                Total Amount
                            </h5>

                            <h4 class="text-primary mb-0">

                                ₹{{ number_format(
                                    $order->total_amount ?? 0,
                                    2
                                ) }}

                            </h4>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
        NOTES
        ========================================================== --}}

        @if($order->notes)

            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-0">

                        <i class="ti ti-notes me-2"></i>

                        Order Notes

                    </h5>

                </div>

                <div class="card-body">

                    {!! nl2br(e($order->notes)) !!}

                </div>

            </div>

        @endif


        {{-- =========================================================
        STATUS HISTORY
        ========================================================== --}}

        <div class="card">

            <div class="card-header">

                <h5 class="card-title mb-0">

                    <i class="ti ti-history me-2"></i>

                    Order Timeline

                </h5>

            </div>

            <div class="card-body">

                <div class="timeline">


                    {{-- CREATED --}}

                    @if($order->created_at)

                        <div class="d-flex mb-4">

                            <div class="me-3">

                                <span class="
                                    avatar
                                    avatar-sm
                                    bg-primary
                                    rounded-circle
                                    d-flex
                                    align-items-center
                                    justify-content-center
                                ">

                                    <i class="ti ti-plus text-white"></i>

                                </span>

                            </div>

                            <div>

                                <h6 class="mb-1">
                                    Order Placed
                                </h6>

                                <small class="text-muted">

                                    {{ $order->created_at->format(
                                        'd M Y, h:i A'
                                    ) }}

                                </small>

                            </div>

                        </div>

                    @endif


                    {{-- ACCEPTED --}}

                    @if($order->accepted_at)

                        <div class="d-flex mb-4">

                            <div class="me-3">

                                <span class="
                                    avatar
                                    avatar-sm
                                    bg-success
                                    rounded-circle
                                    d-flex
                                    align-items-center
                                    justify-content-center
                                ">

                                    <i class="ti ti-check text-white"></i>

                                </span>

                            </div>

                            <div>

                                <h6 class="mb-1">
                                    Order Accepted
                                </h6>

                                <small class="text-muted">

                                    {{ \Carbon\Carbon::parse(
                                        $order->accepted_at
                                    )->format(
                                        'd M Y, h:i A'
                                    ) }}

                                </small>

                            </div>

                        </div>

                    @endif


                    {{-- REJECTED --}}

                    @if($order->rejected_at)

                        <div class="d-flex mb-4">

                            <div class="me-3">

                                <span class="
                                    avatar
                                    avatar-sm
                                    bg-danger
                                    rounded-circle
                                    d-flex
                                    align-items-center
                                    justify-content-center
                                ">

                                    <i class="ti ti-x text-white"></i>

                                </span>

                            </div>

                            <div>

                                <h6 class="mb-1">
                                    Order Rejected
                                </h6>

                                <small class="text-muted">

                                    {{ \Carbon\Carbon::parse(
                                        $order->rejected_at
                                    )->format(
                                        'd M Y, h:i A'
                                    ) }}

                                </small>

                                @if($order->cancel_reason)

                                    <p class="text-danger mb-0 mt-1">

                                        {{ $order->cancel_reason }}

                                    </p>

                                @endif

                            </div>

                        </div>

                    @endif


                    {{-- DELIVERED --}}

                    @if($order->delivered_at)

                        <div class="d-flex mb-2">

                            <div class="me-3">

                                <span class="
                                    avatar
                                    avatar-sm
                                    bg-success
                                    rounded-circle
                                    d-flex
                                    align-items-center
                                    justify-content-center
                                ">

                                    <i class="ti ti-package text-white"></i>

                                </span>

                            </div>

                            <div>

                                <h6 class="mb-1">
                                    Order Delivered
                                </h6>

                                <small class="text-muted">

                                    {{ \Carbon\Carbon::parse(
                                        $order->delivered_at
                                    )->format(
                                        'd M Y, h:i A'
                                    ) }}

                                </small>

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- =========================================================
        STATUS ACTIONS
        ========================================================== --}}

        <div class="card">

            <div class="card-header">

                <h5 class="card-title mb-0">

                    <i class="ti ti-arrows-exchange me-2"></i>

                    Update Order Status

                </h5>

            </div>

            <div class="card-body">

                @if($status === 'pending')

                    <div class="d-flex flex-wrap gap-2">

                        {{-- ACCEPT --}}

                        <form method="POST"
                              action="{{ route(
                                  'hospital.medicine-orders.accept'
                              ) }}">

                            @csrf

                            <input type="hidden"
                                   name="id"
                                   value="{{ $order->id }}">

                            <button type="submit"
                                    class="btn btn-success"
                                    onclick="return confirm(
                                        'Accept this medicine order?'
                                    );">

                                <i class="ti ti-check me-1"></i>

                                Accept Order

                            </button>

                        </form>


                        {{-- REJECT --}}

                        <button type="button"
                                class="btn btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#rejectOrderModal">

                            <i class="ti ti-x me-1"></i>

                            Reject Order

                        </button>

                    </div>


                @elseif($status === 'accepted')

                    <form method="POST"
                          action="{{ route(
                              'hospital.medicine-orders.process'
                          ) }}">

                        @csrf

                        <input type="hidden"
                               name="id"
                               value="{{ $order->id }}">

                        <button type="submit"
                                class="btn btn-info"
                                onclick="return confirm(
                                    'Move this order to processing?'
                                );">

                            <i class="ti ti-player-play me-1"></i>

                            Start Processing

                        </button>

                    </form>


                @elseif($status === 'processing')

                    <form method="POST"
                          action="{{ route(
                              'hospital.medicine-orders.ready'
                          ) }}">

                        @csrf

                        <input type="hidden"
                               name="id"
                               value="{{ $order->id }}">

                        <button type="submit"
                                class="btn btn-primary"
                                onclick="return confirm(
                                    'Mark this order as ready?'
                                );">

                            <i class="ti ti-package me-1"></i>

                            Mark Ready

                        </button>

                    </form>


                @elseif($status === 'ready')

                    <form method="POST"
                          action="{{ route(
                              'hospital.medicine-orders.dispatch'
                          ) }}">

                        @csrf

                        <input type="hidden"
                               name="id"
                               value="{{ $order->id }}">

                        <button type="submit"
                                class="btn btn-primary"
                                onclick="return confirm(
                                    'Dispatch this order?'
                                );">

                            <i class="ti ti-truck me-1"></i>

                            Dispatch Order

                        </button>

                    </form>


                @elseif($status === 'dispatched')

                    <form method="POST"
                          action="{{ route(
                              'hospital.medicine-orders.deliver'
                          ) }}">

                        @csrf

                        <input type="hidden"
                               name="id"
                               value="{{ $order->id }}">

                        <button type="submit"
                                class="btn btn-success"
                                onclick="return confirm(
                                    'Mark this order as delivered?'
                                );">

                            <i class="ti ti-check me-1"></i>

                            Mark Delivered

                        </button>

                    </form>


                @elseif($status === 'delivered')

                    <div class="alert alert-success mb-0">

                        <i class="ti ti-circle-check me-1"></i>

                        This order has been delivered successfully.

                    </div>


                @elseif($status === 'rejected')

                    <div class="alert alert-danger mb-0">

                        <i class="ti ti-circle-x me-1"></i>

                        This order has been rejected.

                    </div>


                @elseif($status === 'cancelled')

                    <div class="alert alert-danger mb-0">

                        <i class="ti ti-ban me-1"></i>

                        This order has been cancelled.

                    </div>

                @else

                    <div class="alert alert-secondary mb-0">

                        Current status:
                        <strong>
                            {{ ucfirst($status) }}
                        </strong>

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>


{{-- =============================================================
REJECT ORDER MODAL
============================================================= --}}

<div class="modal fade"
     id="rejectOrderModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form method="POST"
                  action="{{ route(
                      'hospital.medicine-orders.reject'
                  ) }}">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">

                        <i class="ti ti-alert-circle text-danger me-2"></i>

                        Reject Medicine Order

                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <input type="hidden"
                           name="id"
                           value="{{ $order->id }}">


                    <div class="mb-3">

                        <label class="form-label">

                            Order Number

                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $order->order_no }}"
                               readonly>

                    </div>


                    <div class="mb-3">

                        <label class="form-label">

                            Rejection Reason

                            <span class="text-danger">*</span>

                        </label>

                        <textarea name="cancel_reason"
                                  class="form-control"
                                  rows="4"
                                  placeholder="Enter reason for rejecting this order..."
                                  required></textarea>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit"
                            class="btn btn-danger">

                        <i class="ti ti-x me-1"></i>

                        Reject Order

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection