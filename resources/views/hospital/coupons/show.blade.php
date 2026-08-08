@extends('layout.mainlayout')

@section('title', 'Coupon Details')

@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="page-title">
                <h4>Coupon Details</h4>
                <h6>View coupon and offer information</h6>
            </div>

            <div class="page-btn d-flex gap-2">

                <a href="{{ route('hospital.coupons.index') }}"
                   class="btn btn-light">
                    <i class="ti ti-arrow-left me-1"></i>
                    Back
                </a>

                <a href="{{ route('hospital.coupons.edit', $coupon->id) }}"
                   class="btn btn-primary">
                    <i class="ti ti-edit me-1"></i>
                    Edit
                </a>

            </div>
        </div>


        {{-- Success Message --}}
        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                <i class="ti ti-check me-1"></i>

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- Error Message --}}
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


        <div class="row">

            {{-- LEFT SIDE --}}
            <div class="col-lg-8">

                {{-- Coupon Overview --}}
                <div class="card mb-3">

                    <div class="card-header">

                        <div class="d-flex align-items-center justify-content-between">

                            <h5 class="card-title mb-0">
                                Coupon Information
                            </h5>

                            @if($coupon->status)

                                <span class="badge bg-success">
                                    <i class="ti ti-check me-1"></i>
                                    Active
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    <i class="ti ti-x me-1"></i>
                                    Inactive
                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="card-body">

                        <div class="row g-4">

                            {{-- Code --}}
                            <div class="col-md-6">

                                <div class="detail-item">

                                    <label class="text-muted d-block mb-1">
                                        Coupon Code
                                    </label>

                                    <div class="d-flex align-items-center gap-2">

                                        <span class="badge bg-light-primary text-primary fs-16 px-3 py-2">

                                            {{ $coupon->code }}

                                        </span>

                                        <button type="button"
                                                class="btn btn-sm btn-light"
                                                onclick="copyCouponCode()">

                                            <i class="ti ti-copy"></i>

                                        </button>

                                    </div>

                                </div>

                            </div>


                            {{-- Type --}}
                            <div class="col-md-6">

                                <label class="text-muted d-block mb-1">
                                    Type
                                </label>

                                @if($coupon->coupon_type === 'offer')

                                    <span class="badge bg-light-info text-info">
                                        <i class="ti ti-gift me-1"></i>
                                        Offer
                                    </span>

                                @else

                                    <span class="badge bg-light-secondary text-secondary">
                                        <i class="ti ti-ticket me-1"></i>
                                        Coupon
                                    </span>

                                @endif

                            </div>


                            {{-- Title --}}
                            <div class="col-md-12">

                                <label class="text-muted d-block mb-1">
                                    Title
                                </label>

                                <h5 class="mb-0">
                                    {{ $coupon->title }}
                                </h5>

                            </div>


                            {{-- Description --}}
                            <div class="col-md-12">

                                <label class="text-muted d-block mb-1">
                                    Description
                                </label>

                                <p class="mb-0">

                                    {{ $coupon->description ?: 'No description available.' }}

                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Discount Details --}}
                <div class="card mb-3">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Discount Details
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-4">

                            {{-- Discount --}}
                            <div class="col-md-4">

                                <label class="text-muted d-block mb-1">
                                    Discount
                                </label>

                                @if(
                                    $coupon->discount_type === 'free' ||
                                    $coupon->free_appointment
                                )

                                    <h5 class="text-success mb-0">
                                        FREE
                                    </h5>

                                @elseif($coupon->discount_type === 'percentage')

                                    <h5 class="text-success mb-0">

                                        {{ rtrim(rtrim(number_format($coupon->discount_value, 2), '0'), '.') }}%

                                        <small class="text-muted">
                                            OFF
                                        </small>

                                    </h5>

                                @elseif($coupon->discount_type === 'fixed')

                                    <h5 class="text-success mb-0">

                                        ₹{{ number_format($coupon->discount_value, 2) }}

                                        <small class="text-muted">
                                            OFF
                                        </small>

                                    </h5>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </div>


                            {{-- Applicable --}}
                            <div class="col-md-4">

                                <label class="text-muted d-block mb-1">
                                    Applicable To
                                </label>

                                @if($coupon->applicable_to === 'appointment')

                                    <span class="badge bg-light-primary text-primary">
                                        Doctor Appointment
                                    </span>

                                @elseif($coupon->applicable_to === 'medicine')

                                    <span class="badge bg-light-success text-success">
                                        Medicine
                                    </span>

                                @else

                                    <span class="badge bg-light-info text-info">
                                        All Services
                                    </span>

                                @endif

                            </div>


                            {{-- Minimum Amount --}}
                            <div class="col-md-4">

                                <label class="text-muted d-block mb-1">
                                    Minimum Amount
                                </label>

                                @if($coupon->min_order_amount > 0)

                                    <strong>
                                        ₹{{ number_format($coupon->min_order_amount, 2) }}
                                    </strong>

                                @else

                                    <span class="text-muted">
                                        No Minimum
                                    </span>

                                @endif

                            </div>


                            {{-- Maximum Discount --}}
                            @if($coupon->discount_type === 'percentage')

                                <div class="col-md-4">

                                    <label class="text-muted d-block mb-1">
                                        Maximum Discount
                                    </label>

                                    @if($coupon->max_discount)

                                        <strong>
                                            ₹{{ number_format($coupon->max_discount, 2) }}
                                        </strong>

                                    @else

                                        <span class="text-muted">
                                            No Limit
                                        </span>

                                    @endif

                                </div>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Eligibility --}}
                <div class="card mb-3">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Customer Eligibility
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            {{-- New Customer --}}
                            <div class="col-md-6">

                                <div class="border rounded p-3">

                                    <div class="d-flex align-items-center gap-2">

                                        @if($coupon->new_customer_only)

                                            <span class="avatar avatar-sm bg-light-success">

                                                <i class="ti ti-check text-success"></i>

                                            </span>

                                            <div>

                                                <strong>
                                                    New Customer Only
                                                </strong>

                                                <small class="text-muted d-block">
                                                    Only newly registered customers
                                                </small>

                                            </div>

                                        @else

                                            <span class="avatar avatar-sm bg-light-secondary">

                                                <i class="ti ti-minus text-muted"></i>

                                            </span>

                                            <div>

                                                <strong>
                                                    All Customers
                                                </strong>

                                                <small class="text-muted d-block">
                                                    Not restricted to new customers
                                                </small>

                                            </div>

                                        @endif

                                    </div>

                                </div>

                            </div>


                            {{-- First Appointment --}}
                            <div class="col-md-6">

                                <div class="border rounded p-3">

                                    <div class="d-flex align-items-center gap-2">

                                        @if($coupon->first_appointment_only)

                                            <span class="avatar avatar-sm bg-light-success">

                                                <i class="ti ti-check text-success"></i>

                                            </span>

                                            <div>

                                                <strong>
                                                    First Appointment Only
                                                </strong>

                                                <small class="text-muted d-block">
                                                    Valid for first appointment
                                                </small>

                                            </div>

                                        @else

                                            <span class="avatar avatar-sm bg-light-secondary">

                                                <i class="ti ti-minus text-muted"></i>

                                            </span>

                                            <div>

                                                <strong>
                                                    Multiple Appointments
                                                </strong>

                                                <small class="text-muted d-block">
                                                    Not restricted to first appointment
                                                </small>

                                            </div>

                                        @endif

                                    </div>

                                </div>

                            </div>


                            {{-- Free Appointment --}}
                            <div class="col-md-6">

                                <div class="border rounded p-3">

                                    <div class="d-flex align-items-center gap-2">

                                        @if($coupon->free_appointment)

                                            <span class="avatar avatar-sm bg-light-success">

                                                <i class="ti ti-check text-success"></i>

                                            </span>

                                            <div>

                                                <strong>
                                                    Free Appointment
                                                </strong>

                                                <small class="text-muted d-block">
                                                    Appointment fee is completely free
                                                </small>

                                            </div>

                                        @else

                                            <span class="avatar avatar-sm bg-light-secondary">

                                                <i class="ti ti-minus text-muted"></i>

                                            </span>

                                            <div>

                                                <strong>
                                                    Paid Appointment
                                                </strong>

                                                <small class="text-muted d-block">
                                                    Normal appointment pricing
                                                </small>

                                            </div>

                                        @endif

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Usage --}}
                <div class="card mb-3">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Usage Information
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-4">

                            <div class="col-md-4">

                                <label class="text-muted d-block mb-1">
                                    Used
                                </label>

                                <h5 class="mb-0">
                                    {{ $coupon->used_count }}
                                </h5>

                            </div>


                            <div class="col-md-4">

                                <label class="text-muted d-block mb-1">
                                    Total Usage Limit
                                </label>

                                <h5 class="mb-0">

                                    @if($coupon->usage_limit)
                                        {{ $coupon->usage_limit }}
                                    @else
                                        Unlimited
                                    @endif

                                </h5>

                            </div>


                            <div class="col-md-4">

                                <label class="text-muted d-block mb-1">
                                    Usage Per Customer
                                </label>

                                <h5 class="mb-0">
                                    {{ $coupon->usage_per_customer ?? 1 }}
                                </h5>

                            </div>

                        </div>


                        @if($coupon->usage_limit)

                            @php
                                $usagePercentage = $coupon->usage_limit > 0
                                    ? min(
                                        100,
                                        ($coupon->used_count / $coupon->usage_limit) * 100
                                    )
                                    : 0;
                            @endphp

                            <div class="mt-4">

                                <div class="d-flex justify-content-between mb-1">

                                    <small class="text-muted">
                                        Usage Progress
                                    </small>

                                    <small class="fw-semibold">
                                        {{ number_format($usagePercentage, 0) }}%
                                    </small>

                                </div>

                                <div class="progress"
                                     style="height: 8px;">

                                    <div class="progress-bar"
                                         role="progressbar"
                                         style="width: {{ $usagePercentage }}%;">

                                    </div>

                                </div>

                            </div>

                        @endif

                    </div>

                </div>


                {{-- Validity --}}
                <div class="card mb-3">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Validity
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-4">

                            <div class="col-md-6">

                                <label class="text-muted d-block mb-1">
                                    Starts At
                                </label>

                                @if($coupon->starts_at)

                                    <strong>
                                        {{ \Carbon\Carbon::parse($coupon->starts_at)->format('d M Y, h:i A') }}
                                    </strong>

                                @else

                                    <span class="text-muted">
                                        Immediately
                                    </span>

                                @endif

                            </div>


                            <div class="col-md-6">

                                <label class="text-muted d-block mb-1">
                                    Expires At
                                </label>

                                @if($coupon->expires_at)

                                    <strong>
                                        {{ \Carbon\Carbon::parse($coupon->expires_at)->format('d M Y, h:i A') }}
                                    </strong>

                                    @if(\Carbon\Carbon::parse($coupon->expires_at)->isPast())

                                        <span class="badge bg-light-danger text-danger ms-2">
                                            Expired
                                        </span>

                                    @endif

                                @else

                                    <span class="text-muted">
                                        No Expiry
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Coupon Usage History --}}
                @if(isset($coupon->usages) && $coupon->usages->count())

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Recent Usage
                            </h5>

                        </div>


                        <div class="card-body p-0">

                            <div class="table-responsive">

                                <table class="table mb-0">

                                    <thead>

                                        <tr>
                                            <th>#</th>
                                            <th>Customer</th>
                                            <th>Appointment</th>
                                            <th>Used At</th>
                                        </tr>

                                    </thead>


                                    <tbody>

                                        @foreach($coupon->usages->take(10) as $usage)

                                            <tr>

                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>


                                                <td>

                                                    @if($usage->customer)

                                                        {{ $usage->customer->name }}

                                                        @if($usage->customer->mobile)
                                                            <small class="text-muted d-block">
                                                                {{ $usage->customer->mobile }}
                                                            </small>
                                                        @endif

                                                    @else

                                                        <span class="text-muted">
                                                            Customer unavailable
                                                        </span>

                                                    @endif

                                                </td>


                                                <td>

                                                    @if($usage->appointment)

                                                        #{{ $usage->appointment->id }}

                                                    @else

                                                        <span class="text-muted">
                                                            -
                                                        </span>

                                                    @endif

                                                </td>


                                                <td>

                                                    {{ $usage->created_at?->format('d M Y, h:i A') }}

                                                </td>

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                @endif

            </div>


            {{-- RIGHT SIDE --}}
            <div class="col-lg-4">

                {{-- Coupon Card --}}
                <div class="card mb-3">

                    <div class="card-body text-center">

                        <div class="mb-3">

                            <span class="avatar avatar-xl bg-primary rounded-circle">

                                <i class="ti ti-ticket fs-30 text-white"></i>

                            </span>

                        </div>


                        <h4>
                            {{ $coupon->title }}
                        </h4>


                        <div class="my-3">

                            <span class="badge bg-light-primary text-primary fs-16 px-4 py-2">

                                {{ $coupon->code }}

                            </span>

                        </div>


                        <h2 class="text-success mb-2">

                            @if(
                                $coupon->discount_type === 'free' ||
                                $coupon->free_appointment
                            )

                                FREE APPOINTMENT

                            @elseif($coupon->discount_type === 'percentage')

                                {{ rtrim(rtrim(number_format($coupon->discount_value, 2), '0'), '.') }}% OFF

                            @elseif($coupon->discount_type === 'fixed')

                                ₹{{ number_format($coupon->discount_value, 2) }} OFF

                            @endif

                        </h2>


                        <p class="text-muted mb-0">

                            @if($coupon->applicable_to === 'appointment')

                                Doctor Appointment

                            @elseif($coupon->applicable_to === 'medicine')

                                Medicine

                            @else

                                All Services

                            @endif

                        </p>

                    </div>

                </div>


                {{-- Status Action --}}
                <div class="card mb-3">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Coupon Status
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="text-center mb-3">

                            @if($coupon->status)

                                <span class="badge bg-success fs-14 px-3 py-2">
                                    <i class="ti ti-check me-1"></i>
                                    Active
                                </span>

                            @else

                                <span class="badge bg-danger fs-14 px-3 py-2">
                                    <i class="ti ti-x me-1"></i>
                                    Inactive
                                </span>

                            @endif

                        </div>


                        <a href="{{ route('hospital.coupons.status', $coupon->id) }}"
                           class="btn {{ $coupon->status ? 'btn-danger' : 'btn-success' }} w-100"
                           onclick="return confirm('{{ $coupon->status ? 'Are you sure you want to deactivate this coupon?' : 'Are you sure you want to activate this coupon?' }}')">

                            @if($coupon->status)

                                <i class="ti ti-toggle-left me-1"></i>
                                Deactivate Coupon

                            @else

                                <i class="ti ti-toggle-right me-1"></i>
                                Activate Coupon

                            @endif

                        </a>

                    </div>

                </div>


                {{-- Dates --}}
                <div class="card mb-3">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Information
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Created
                            </small>

                            <strong>
                                {{ $coupon->created_at?->format('d M Y, h:i A') }}
                            </strong>

                        </div>


                        <div>

                            <small class="text-muted d-block">
                                Last Updated
                            </small>

                            <strong>
                                {{ $coupon->updated_at?->format('d M Y, h:i A') }}
                            </strong>

                        </div>

                    </div>

                </div>


                {{-- Delete --}}
                @if($coupon->used_count == 0)

                    <div class="card">

                        <div class="card-body">

                            <form method="POST"
                                  action="{{ route('hospital.coupons.destroy', $coupon->id) }}"
                                  onsubmit="return confirm('Are you sure you want to delete this coupon?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-outline-danger w-100">

                                    <i class="ti ti-trash me-1"></i>
                                    Delete Coupon

                                </button>

                            </form>

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>
</div>


<script>

function copyCouponCode()
{
    const code = @json($coupon->code);

    navigator.clipboard.writeText(code).then(function () {

        alert('Coupon code copied: ' + code);

    }).catch(function () {

        alert('Coupon code: ' + code);

    });
}

</script>

@endsection