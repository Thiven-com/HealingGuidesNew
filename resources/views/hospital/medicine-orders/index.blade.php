@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">

    <div class="content">

        {{-- =========================================================
        PAGE HEADER
        ========================================================== --}}

        <div class="page-header">

            <div class="page-title">
                <h4>Medicine Orders</h4>
                <h6>Manage medicine orders</h6>
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
        SUMMARY CARDS
        ========================================================== --}}

        <div class="row">

            <div class="col-xl-3 col-sm-6 col-12">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex
                                    align-items-center
                                    justify-content-between">

                            <div>

                                <p class="mb-1 text-muted">
                                    Total Orders
                                </p>

                                <h4 class="mb-0">
                                    {{ $orders->total() }}
                                </h4>

                            </div>

                            <div class="
                                avatar
                                avatar-lg
                                bg-primary
                                rounded
                                d-flex
                                align-items-center
                                justify-content-center
                            ">

                                <i class="ti ti-shopping-cart text-white fs-24"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-xl-3 col-sm-6 col-12">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex
                                    align-items-center
                                    justify-content-between">

                            <div>

                                <p class="mb-1 text-muted">
                                    Pending
                                </p>

                                <h4 class="mb-0">

                                    {{ \App\Models\MedicineOrder::where(
                                        'hospital_id',
                                        Auth::guard('hospital')->id()
                                    )->where(
                                        'order_status',
                                        'pending'
                                    )->count() }}

                                </h4>

                            </div>

                            <div class="
                                avatar
                                avatar-lg
                                bg-warning
                                rounded
                                d-flex
                                align-items-center
                                justify-content-center
                            ">

                                <i class="ti ti-clock text-white fs-24"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-xl-3 col-sm-6 col-12">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex
                                    align-items-center
                                    justify-content-between">

                            <div>

                                <p class="mb-1 text-muted">
                                    Processing
                                </p>

                                <h4 class="mb-0">

                                    {{ \App\Models\MedicineOrder::where(
                                        'hospital_id',
                                        Auth::guard('hospital')->id()
                                    )->where(
                                        'order_status',
                                        'processing'
                                    )->count() }}

                                </h4>

                            </div>

                            <div class="
                                avatar
                                avatar-lg
                                bg-info
                                rounded
                                d-flex
                                align-items-center
                                justify-content-center
                            ">

                                <i class="ti ti-loader text-white fs-24"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-xl-3 col-sm-6 col-12">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex
                                    align-items-center
                                    justify-content-between">

                            <div>

                                <p class="mb-1 text-muted">
                                    Delivered
                                </p>

                                <h4 class="mb-0">

                                    {{ \App\Models\MedicineOrder::where(
                                        'hospital_id',
                                        Auth::guard('hospital')->id()
                                    )->where(
                                        'order_status',
                                        'delivered'
                                    )->count() }}

                                </h4>

                            </div>

                            <div class="
                                avatar
                                avatar-lg
                                bg-success
                                rounded
                                d-flex
                                align-items-center
                                justify-content-center
                            ">

                                <i class="ti ti-check text-white fs-24"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
        FILTER / SEARCH
        ========================================================== --}}

        <div class="card">

            <div class="card-body">

                <form method="GET"
                      action="{{ route(
                          'hospital.medicine-orders.index'
                      ) }}">

                    <div class="row align-items-end">


                        {{-- SEARCH --}}

                        <div class="col-lg-4 col-md-6 mb-3">

                            <label class="form-label">
                                Search Order
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="ti ti-search"></i>
                                </span>

                                <input type="text"
                                       name="search"
                                       class="form-control"
                                       placeholder="Order number..."
                                       value="{{ request('search') }}">

                            </div>

                        </div>


                        {{-- ORDER STATUS --}}

                        <div class="col-lg-3 col-md-6 mb-3">

                            <label class="form-label">
                                Order Status
                            </label>

                            <select name="order_status"
                                    class="form-select">

                                <option value="">
                                    All Status
                                </option>

                                <option value="pending"
                                    {{ request('order_status') == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>

                                <option value="accepted"
                                    {{ request('order_status') == 'accepted' ? 'selected' : '' }}>
                                    Accepted
                                </option>

                                <option value="processing"
                                    {{ request('order_status') == 'processing' ? 'selected' : '' }}>
                                    Processing
                                </option>

                                <option value="ready"
                                    {{ request('order_status') == 'ready' ? 'selected' : '' }}>
                                    Ready
                                </option>

                                <option value="dispatched"
                                    {{ request('order_status') == 'dispatched' ? 'selected' : '' }}>
                                    Dispatched
                                </option>

                                <option value="delivered"
                                    {{ request('order_status') == 'delivered' ? 'selected' : '' }}>
                                    Delivered
                                </option>

                                <option value="rejected"
                                    {{ request('order_status') == 'rejected' ? 'selected' : '' }}>
                                    Rejected
                                </option>

                                <option value="cancelled"
                                    {{ request('order_status') == 'cancelled' ? 'selected' : '' }}>
                                    Cancelled
                                </option>

                            </select>

                        </div>


                        {{-- PAYMENT STATUS --}}

                        <div class="col-lg-3 col-md-6 mb-3">

                            <label class="form-label">
                                Payment Status
                            </label>

                            <select name="payment_status"
                                    class="form-select">

                                <option value="">
                                    All Payments
                                </option>

                                <option value="pending"
                                    {{ request('payment_status') == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>

                                <option value="paid"
                                    {{ request('payment_status') == 'paid' ? 'selected' : '' }}>
                                    Paid
                                </option>

                                <option value="failed"
                                    {{ request('payment_status') == 'failed' ? 'selected' : '' }}>
                                    Failed
                                </option>

                                <option value="refunded"
                                    {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>
                                    Refunded
                                </option>

                            </select>

                        </div>


                        {{-- BUTTONS --}}

                        <div class="col-lg-2 col-md-6 mb-3">

                            <div class="d-flex gap-2">

                                <button type="submit"
                                        class="btn btn-primary">

                                    <i class="ti ti-search me-1"></i>

                                    Search

                                </button>

                                <a href="{{ route(
                                    'hospital.medicine-orders.index'
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
        ORDERS TABLE
        ========================================================== --}}

        <div class="card">

            <div class="card-header">

                <div class="d-flex
                            align-items-center
                            justify-content-between">

                    <div>

                        <h5 class="card-title mb-0">
                            Medicine Orders
                        </h5>

                    </div>

                    <span class="text-muted">

                        {{ $orders->total() }}
                        orders

                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table datanew mb-0">

                        <thead>

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Order No
                                </th>

                                <th>
                                    Customer
                                </th>

                                <th>
                                    Order Date
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

                                <th class="text-end">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($orders as $order)

                                <tr>

                                    {{-- NUMBER --}}

                                    <td>

                                        {{ $orders->firstItem() + $loop->index }}

                                    </td>


                                    {{-- ORDER NUMBER --}}

                                    <td>

                                        <a href="{{ route(
                                            'hospital.medicine-orders.show',
                                            $order->id
                                        ) }}"
                                           class="fw-semibold text-primary">

                                            {{ $order->order_no }}

                                        </a>

                                    </td>


                                    {{-- CUSTOMER --}}

                                    <td>

                                        <div class="d-flex
                                                    align-items-center">

                                            <div class="
                                                avatar
                                                avatar-sm
                                                bg-light
                                                rounded-circle
                                                d-flex
                                                align-items-center
                                                justify-content-center
                                                me-2
                                            ">

                                                <i class="
                                                    ti
                                                    ti-user
                                                    text-primary
                                                "></i>

                                            </div>

                                            <div>

                                                @if($order->customer)

                                                    <h6 class="mb-0">

                                                        {{ $order->customer->name ?? 'Customer' }}

                                                    </h6>

                                                    @if($order->customer->mobile)

                                                        <small class="text-muted">

                                                            {{ $order->customer->mobile }}

                                                        </small>

                                                    @endif

                                                @else

                                                    <h6 class="mb-0">
                                                        Customer
                                                    </h6>

                                                @endif

                                            </div>

                                        </div>

                                    </td>


                                    {{-- DATE --}}

                                    <td>

                                        <div>

                                            <span class="d-block">

                                                {{ $order->created_at
                                                    ? $order->created_at->format('d M Y')
                                                    : '-' }}

                                            </span>

                                            <small class="text-muted">

                                                {{ $order->created_at
                                                    ? $order->created_at->format('h:i A')
                                                    : '' }}

                                            </small>

                                        </div>

                                    </td>


                                    {{-- TOTAL --}}

                                    <td>

                                        <span class="fw-semibold">

                                            ₹{{ number_format(
                                                $order->total_amount ?? 0,
                                                2
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- PAYMENT STATUS --}}

                                    <td>

                                        @php

                                            $paymentStatus =
                                                strtolower(
                                                    $order->payment_status ?? 'pending'
                                                );

                                        @endphp


                                        @if($paymentStatus === 'paid')

                                            <span class="
                                                badge
                                                bg-success
                                            ">

                                                <i class="
                                                    ti
                                                    ti-check
                                                    me-1
                                                "></i>

                                                Paid

                                            </span>

                                        @elseif($paymentStatus === 'failed')

                                            <span class="
                                                badge
                                                bg-danger
                                            ">

                                                Failed

                                            </span>

                                        @elseif($paymentStatus === 'refunded')

                                            <span class="
                                                badge
                                                bg-info
                                            ">

                                                Refunded

                                            </span>

                                        @else

                                            <span class="
                                                badge
                                                bg-warning
                                            ">

                                                Pending

                                            </span>

                                        @endif

                                    </td>


                                    {{-- ORDER STATUS --}}

                                    <td>

                                        @php

                                            $status =
                                                strtolower(
                                                    $order->order_status ?? 'pending'
                                                );

                                        @endphp


                                        @switch($status)

                                            @case('pending')

                                                <span class="
                                                    badge
                                                    bg-warning
                                                ">
                                                    Pending
                                                </span>

                                                @break


                                            @case('accepted')

                                                <span class="
                                                    badge
                                                    bg-primary
                                                ">
                                                    Accepted
                                                </span>

                                                @break


                                            @case('processing')

                                                <span class="
                                                    badge
                                                    bg-info
                                                ">
                                                    Processing
                                                </span>

                                                @break


                                            @case('ready')

                                                <span class="
                                                    badge
                                                    bg-primary
                                                ">
                                                    Ready
                                                </span>

                                                @break


                                            @case('dispatched')

                                                <span class="
                                                    badge
                                                    bg-secondary
                                                ">
                                                    Dispatched
                                                </span>

                                                @break


                                            @case('delivered')

                                                <span class="
                                                    badge
                                                    bg-success
                                                ">
                                                    Delivered
                                                </span>

                                                @break


                                            @case('rejected')

                                                <span class="
                                                    badge
                                                    bg-danger
                                                ">
                                                    Rejected
                                                </span>

                                                @break


                                            @case('cancelled')

                                                <span class="
                                                    badge
                                                    bg-danger
                                                ">
                                                    Cancelled
                                                </span>

                                                @break


                                            @default

                                                <span class="
                                                    badge
                                                    bg-secondary
                                                ">

                                                    {{ ucfirst($status) }}

                                                </span>

                                        @endswitch

                                    </td>


                                    {{-- ACTION --}}

                                    <td class="text-end">

                                        <a href="{{ route(
                                            'hospital.medicine-orders.show',
                                            $order->id
                                        ) }}"
                                           class="
                                                btn
                                                btn-sm
                                                btn-light
                                                d-inline-flex
                                                align-items-center
                                           "
                                           title="View Order">

                                            <i class="
                                                ti
                                                ti-eye
                                            "></i>

                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="8"
                                        class="text-center py-5">

                                        <div class="mb-3">

                                            <i class="
                                                ti
                                                ti-shopping-cart-off
                                            "
                                               style="
                                                   font-size:60px;
                                                   opacity:.4;
                                               ">
                                            </i>

                                        </div>

                                        <h5>
                                            No medicine orders found
                                        </h5>

                                        <p class="text-muted mb-0">

                                            There are no medicine orders
                                            matching your search.

                                        </p>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- =====================================================
            PAGINATION
            ====================================================== --}}

            @if($orders->hasPages())

                <div class="card-footer">

                    <div class="d-flex
                                align-items-center
                                justify-content-between
                                flex-wrap
                                gap-2">

                        <div class="text-muted">

                            Showing

                            <strong>
                                {{ $orders->firstItem() }}
                            </strong>

                            to

                            <strong>
                                {{ $orders->lastItem() }}
                            </strong>

                            of

                            <strong>
                                {{ $orders->total() }}
                            </strong>

                            orders

                        </div>


                        <div>

                            {{ $orders->links() }}

                        </div>

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection