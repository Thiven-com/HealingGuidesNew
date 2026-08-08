@extends('layout.mainlayout')

@section('title', 'Coupon Details')

@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="page-title">
                <h4>Coupon Details</h4>
                <h6>View coupon information and usage</h6>
            </div>

            <div class="page-btn d-flex gap-2">

                <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                   class="btn btn-primary">
                    <i class="ti ti-edit me-1"></i>
                    Edit
                </a>

                <a href="{{ route('admin.coupons.index') }}"
                   class="btn btn-light">
                    <i class="ti ti-arrow-left me-1"></i>
                    Back
                </a>

            </div>
        </div>


        {{-- Success --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>
            </div>
        @endif


        <div class="row">

            {{-- LEFT --}}
            <div class="col-lg-8">

                {{-- Coupon Overview --}}
                <div class="card mb-3">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Coupon Overview
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row g-4">

                            {{-- Code --}}
                            <div class="col-md-6">

                                <label class="text-muted d-block mb-1">
                                    Coupon Code
                                </label>

                                <div class="d-flex align-items-center gap-2">

                                    <span class="badge bg-light-primary text-primary fs-16 px-3 py-2">
                                        {{ $coupon->code }}
                                    </span>

                                </div>

                            </div>


                            {{-- Title --}}
                            <div class="col-md-6">

                                <label class="text-muted d-block mb-1">
                                    Title
                                </label>

                                <strong>
                                    {{ $coupon->title }}
                                </strong>

                            </div>


                            {{-- Type --}}
                            <div class="col-md-4">

                                <label class="text-muted d-block mb-1">
                                    Type
                                </label>

                                @if($coupon->coupon_type === 'offer')

                                    <span class="badge bg-primary">
                                        Offer
                                    </span>

                                @else

                                    <span class="badge bg-light text-dark">
                                        Coupon
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


                            {{-- Status --}}
                            <div class="col-md-4">

                                <label class="text-muted d-block mb-1">
                                    Status
                                </label>

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


                            {{-- Discount --}}
                            <div class="col-md-6">

                                <label class="text-muted d-block mb-1">
                                    Discount
                                </label>

                                @if(
                                    $coupon->discount_type === 'free' ||
                                    $coupon->free_appointment
                                )

                                    <h4 class="text-success mb-0">
                                        FREE APPOINTMENT
                                    </h4>

                                @elseif($coupon->discount_type === 'percentage')

                                    <h4 class="text-success mb-0">
                                        {{ rtrim(rtrim(number_format($coupon->discount_value, 2), '0'), '.') }}%
                                        OFF
                                    </h4>

                                    @if($coupon->max_discount)
                                        <small class="text-muted">
                                            Maximum:
                                            ₹{{ number_format($coupon->max_discount, 2) }}
                                        </small>
                                    @endif

                                @else

                                    <h4 class="text-success mb-0">
                                        ₹{{ number_format($coupon->discount_value, 2) }}
                                        OFF
                                    </h4>

                                @endif

                            </div>


                            {{-- Minimum --}}
                            <div class="col-md-6">

                                <label class="text-muted d-block mb-1">
                                    Minimum Amount
                                </label>

                                @if($coupon->min_order_amount > 0)

                                    <strong>
                                        ₹{{ number_format($coupon->min_order_amount, 2) }}
                                    </strong>

                                @else

                                    <span class="text-muted">
                                        No minimum amount
                                    </span>

                                @endif

                            </div>


                            {{-- Description --}}
                            <div class="col-md-12">

                                <label class="text-muted d-block mb-1">
                                    Description
                                </label>

                                @if($coupon->description)

                                    <p class="mb-0">
                                        {{ $coupon->description }}
                                    </p>

                                @else

                                    <span class="text-muted">
                                        No description
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Customer Eligibility --}}
                <div class="card mb-3">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Customer Eligibility
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-4">

                                <div class="border rounded p-3">

                                    <div class="mb-2">

                                        @if($coupon->new_customer_only)

                                            <span class="badge bg-success">
                                                Enabled
                                            </span>

                                        @else

                                            <span class="badge bg-light text-dark">
                                                Disabled
                                            </span>

                                        @endif

                                    </div>

                                    <strong class="d-block">
                                        New Customer Only
                                    </strong>

                                    <small class="text-muted">
                                        Coupon eligibility for new customers.
                                    </small>

                                </div>

                            </div>


                            <div class="col-md-4">

                                <div class="border rounded p-3">

                                    <div class="mb-2">

                                        @if($coupon->first_appointment_only)

                                            <span class="badge bg-success">
                                                Enabled
                                            </span>

                                        @else

                                            <span class="badge bg-light text-dark">
                                                Disabled
                                            </span>

                                        @endif

                                    </div>

                                    <strong class="d-block">
                                        First Appointment
                                    </strong>

                                    <small class="text-muted">
                                        Valid only for first appointment.
                                    </small>

                                </div>

                            </div>


                            <div class="col-md-4">

                                <div class="border rounded p-3">

                                    <div class="mb-2">

                                        @if($coupon->free_appointment)

                                            <span class="badge bg-success">
                                                Enabled
                                            </span>

                                        @else

                                            <span class="badge bg-light text-dark">
                                                Disabled
                                            </span>

                                        @endif

                                    </div>

                                    <strong class="d-block">
                                        Free Appointment
                                    </strong>

                                    <small class="text-muted">
                                        Appointment becomes free.
                                    </small>

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

                                <h4 class="mb-0">
                                    {{ $coupon->used_count }}
                                </h4>

                            </div>


                            <div class="col-md-4">

                                <label class="text-muted d-block mb-1">
                                    Usage Limit
                                </label>

                                <h4 class="mb-0">

                                    @if($coupon->usage_limit)
                                        {{ $coupon->usage_limit }}
                                    @else
                                        Unlimited
                                    @endif

                                </h4>

                            </div>


                            <div class="col-md-4">

                                <label class="text-muted d-block mb-1">
                                    Per Customer
                                </label>

                                <h4 class="mb-0">
                                    {{ $coupon->usage_per_customer }}
                                </h4>

                            </div>

                        </div>


                        @if($coupon->usage_limit)

                            @php
                                $usagePercentage =
                                    $coupon->usage_limit > 0
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


                {{-- Usage History --}}
                <div class="card">

                    <div class="card-header">

                        <div class="d-flex justify-content-between align-items-center">

                            <h5 class="card-title mb-0">
                                Usage History
                            </h5>

                            <span class="badge bg-light text-dark">
                                {{ $coupon->usages->count() }}
                                Usage(s)
                            </span>

                        </div>

                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table">

                                <thead>

                                    <tr>

                                        <th>#</th>

                                        <th>Customer</th>

                                        <th>Reference</th>

                                        <th>Discount</th>

                                        <th>Used At</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($coupon->usages as $usage)

                                        <tr>

                                            <td>
                                                {{ $loop->iteration }}
                                            </td>


                                            <td>

                                                @if($usage->customer)

                                                    <strong>
                                                        {{ $usage->customer->name ?? 'Customer' }}
                                                    </strong>

                                                    @if($usage->customer->mobile ?? null)

                                                        <div class="small text-muted">
                                                            {{ $usage->customer->mobile }}
                                                        </div>

                                                    @endif

                                                @else

                                                    <span class="text-muted">
                                                        Customer #{{ $usage->customer_id }}
                                                    </span>

                                                @endif

                                            </td>


                                            <td>

                                                @if($usage->appointment_id)

                                                    <span class="badge bg-light-primary text-primary">
                                                        Appointment #{{ $usage->appointment_id }}
                                                    </span>

                                                @elseif($usage->medicine_order_id)

                                                    <span class="badge bg-light-success text-success">
                                                        Medicine Order #{{ $usage->medicine_order_id }}
                                                    </span>

                                                @else

                                                    <span class="text-muted">
                                                        -
                                                    </span>

                                                @endif

                                            </td>


                                            <td>

                                                <strong class="text-success">
                                                    ₹{{ number_format($usage->discount_amount, 2) }}
                                                </strong>

                                            </td>


                                            <td>

                                                @if($usage->used_at)

                                                    {{ $usage->used_at->format('d M Y') }}

                                                    <div class="small text-muted">
                                                        {{ $usage->used_at->format('h:i A') }}
                                                    </div>

                                                @else

                                                    {{ $usage->created_at?->format('d M Y') }}

                                                    <div class="small text-muted">
                                                        {{ $usage->created_at?->format('h:i A') }}
                                                    </div>

                                                @endif

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="5"
                                                class="text-center py-4">

                                                <div class="text-muted">

                                                    <i class="ti ti-ticket-off fs-1 d-block mb-2"></i>

                                                    No usage history found.

                                                </div>

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>


            {{-- RIGHT --}}
            <div class="col-lg-4">

                {{-- Coupon Card --}}
                <div class="card mb-3">

                    <div class="card-body text-center">

                        <div class="mb-3">

                            <span class="avatar avatar-xl bg-primary rounded-circle">

                                <i class="ti ti-ticket fs-28 text-white"></i>

                            </span>

                        </div>


                        <h5>
                            {{ $coupon->title }}
                        </h5>


                        <div class="my-3">

                            <span class="badge bg-light-primary text-primary fs-16 px-4 py-2">
                                {{ $coupon->code }}
                            </span>

                        </div>


                        <h3 class="text-success">

                            @if(
                                $coupon->free_appointment ||
                                $coupon->discount_type === 'free'
                            )

                                FREE

                            @elseif($coupon->discount_type === 'percentage')

                                {{ rtrim(rtrim(number_format($coupon->discount_value, 2), '0'), '.') }}%

                            @else

                                ₹{{ number_format($coupon->discount_value, 2) }}

                            @endif

                        </h3>


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


                {{-- Validity --}}
                <div class="card mb-3">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Validity
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Starts At
                            </small>

                            <strong>

                                @if($coupon->starts_at)

                                    {{ $coupon->starts_at->format('d M Y, h:i A') }}

                                @else

                                    Immediately

                                @endif

                            </strong>

                        </div>


                        <div>

                            <small class="text-muted d-block">
                                Expires At
                            </small>

                            <strong>

                                @if($coupon->expires_at)

                                    {{ $coupon->expires_at->format('d M Y, h:i A') }}

                                @else

                                    No Expiry

                                @endif

                            </strong>

                        </div>

                    </div>

                </div>


                {{-- Actions --}}
                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Actions
                        </h5>
                    </div>

                    <div class="card-body">

                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                           class="btn btn-primary w-100 mb-2">

                            <i class="ti ti-edit me-1"></i>
                            Edit Coupon

                        </a>


                        <a href="{{ route('admin.coupons.status', $coupon->id) }}"
                           class="btn {{ $coupon->status ? 'btn-warning' : 'btn-success' }} w-100 mb-2"
                           onclick="return confirm('{{ $coupon->status ? 'Deactivate this coupon?' : 'Activate this coupon?' }}')">

                            @if($coupon->status)

                                <i class="ti ti-toggle-left me-1"></i>
                                Deactivate

                            @else

                                <i class="ti ti-toggle-right me-1"></i>
                                Activate

                            @endif

                        </a>


                        @if($coupon->used_count == 0)

                            <form method="POST"
                                  action="{{ route('admin.coupons.destroy', $coupon->id) }}"
                                  onsubmit="return confirm('Are you sure you want to delete this coupon?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-danger w-100">

                                    <i class="ti ti-trash me-1"></i>
                                    Delete Coupon

                                </button>

                            </form>

                        @else

                            <button type="button"
                                    class="btn btn-light text-muted w-100"
                                    disabled>

                                <i class="ti ti-lock me-1"></i>
                                Cannot Delete — Already Used

                            </button>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

@endsection