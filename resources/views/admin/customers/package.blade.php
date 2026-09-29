@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">

    <div class="content">

        {{-- ==========================================================
            PAGE HEADER
        =========================================================== --}}

        <div class="page-header">

            <div class="page-title">

                <h4>Customer Package</h4>

                <h6>
                    Manage Customer Package & Benefits
                </h6>

            </div>


            <div class="page-btn">

                <a
                    href="{{ route('admin.customers.index') }}"
                    class="btn btn-light"
                >

                    <i class="ti ti-arrow-left me-1"></i>

                    Back

                </a>

            </div>

        </div>


        {{-- ==========================================================
            ALERTS
        =========================================================== --}}

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show">

                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        @if(session('warning'))

            <div class="alert alert-warning alert-dismissible fade show">

                {{ session('warning') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        {{-- ==========================================================
            CUSTOMER INFORMATION
        =========================================================== --}}

        <div class="card mb-4">

            <div class="card-body">

                <div class="row align-items-center">

                    {{-- Customer --}}

                    <div class="col-md-8">

                        <div class="d-flex align-items-center">

                            @if($customer->photo)

                                <img
                                    src="{{ asset($customer->photo) }}"
                                    alt="{{ $customer->name }}"
                                    class="customer-photo"
                                >

                            @else

                                <span class="customer-placeholder">

                                    <i class="ti ti-user"></i>

                                </span>

                            @endif


                            <div class="ms-3">

                                <h5 class="mb-1">

                                    {{ $customer->name ?? 'N/A' }}

                                </h5>


                                <div class="text-muted">

                                    Customer Code:

                                    <strong>
                                        {{ $customer->customer_code ?? 'N/A' }}
                                    </strong>

                                </div>


                                @if($customer->mobile)

                                    <div class="text-muted mt-1">

                                        <i class="ti ti-phone me-1"></i>

                                        {{ $customer->mobile }}

                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- Status --}}

                    <div class="col-md-4 text-md-end mt-3 mt-md-0">

                        @if($customer->package)

                            <span class="badge bg-success">

                                <i class="ti ti-circle-check me-1"></i>

                                Package Active

                            </span>

                        @else

                            <span class="badge bg-warning text-dark">

                                No Package

                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- ==========================================================
            MAIN CONTENT
        =========================================================== --}}

        <div class="row">


            {{-- ======================================================
                LEFT SIDE
            ======================================================= --}}

            <div class="col-xl-4 col-lg-5">


                {{-- ==================================================
                    CURRENT PACKAGE
                =================================================== --}}

                <div class="card">

                    <div class="card-header">

                        <div class="d-flex justify-content-between align-items-center">

                            <h5 class="card-title mb-0">

                                <i class="ti ti-crown me-2"></i>

                                Current Package

                            </h5>


                            @if($customer->package)

                                <span class="badge bg-success">

                                    Active

                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="card-body">

                        @if($customer->package)

                            <div class="border rounded p-3">

                                <div class="row">

                                    <div class="col-8">

                                        <h5 class="mb-1">

                                            {{ $customer->package->name }}

                                        </h5>


                                        @if($customer->package->description)

                                            <p class="text-muted mb-0">

                                                {{ $customer->package->description }}

                                            </p>

                                        @endif

                                    </div>


                                    <div class="col-4 text-end">

                                        <h5 class="text-primary mb-0">

                                            ₹{{ number_format($customer->package->price, 2) }}

                                        </h5>

                                    </div>

                                </div>


                                <hr>


                                <div class="row">

                                    <div class="col-6">

                                        <small class="text-muted d-block">

                                            Duration

                                        </small>

                                        <strong>

                                            {{ $customer->package->duration_days }}

                                            Days

                                        </strong>

                                    </div>


                                    <div class="col-6">

                                        <small class="text-muted d-block">

                                            Status

                                        </small>

                                        <strong class="text-success">

                                            Active

                                        </strong>

                                    </div>

                                </div>


                                <div class="row mt-3">

                                    <div class="col-6">

                                        <small class="text-muted d-block">

                                            Start Date

                                        </small>

                                        <strong>

                                            @if($customer->package_start_date)

                                                {{ \Carbon\Carbon::parse($customer->package_start_date)->format('d M Y') }}

                                            @else

                                                -

                                            @endif

                                        </strong>

                                    </div>


                                    <div class="col-6">

                                        <small class="text-muted d-block">

                                            Expiry Date

                                        </small>

                                        <strong>

                                            @if($customer->package_expiry_date)

                                                {{ \Carbon\Carbon::parse($customer->package_expiry_date)->format('d M Y') }}

                                            @else

                                                -

                                            @endif

                                        </strong>

                                    </div>

                                </div>

                            </div>

                        @else

                            <div class="text-center py-4">

                                <i
                                    class="ti ti-package text-muted"
                                    style="font-size:40px;"
                                ></i>

                                <h6 class="mt-2">

                                    No Package Assigned

                                </h6>

                                <p class="text-muted mb-0">

                                    No package is currently assigned
                                    to this customer.

                                </p>

                            </div>

                        @endif

                    </div>

                </div>


                {{-- ==================================================
                    BENEFIT USAGE
                =================================================== --}}

                <div class="card">

                    <div class="card-header">

                        <div class="d-flex justify-content-between align-items-center">

                            <h5 class="card-title mb-0">

                                <i class="ti ti-gift me-2"></i>

                                Benefit Usage

                            </h5>


                            <span class="badge bg-light text-dark">

                                {{ $customer->packageBenefits->count() }}

                            </span>

                        </div>

                    </div>


                    <div class="card-body p-0">

                        @forelse($customer->packageBenefits as $benefit)

                            @php

                                $total =
                                    (int) ($benefit->total_quantity ?? 0);

                                $used =
                                    (int) ($benefit->used_quantity ?? 0);

                                $remaining =
                                    max(0, $total - $used);

                                $percentage =
                                    $total > 0
                                        ? min(
                                            100,
                                            round(
                                                ($used / $total) * 100
                                            )
                                        )
                                        : 0;

                            @endphp


                            <div class="p-3 border-bottom">

                                <div class="d-flex justify-content-between">

                                    <div>

                                        <strong>

                                            {{ $benefit->benefit_name }}

                                        </strong>

                                        <br>

                                        <small class="text-muted">

                                            {{ $benefit->benefit_type }}

                                        </small>

                                    </div>


                                    @if($remaining > 0)

                                        <span class="badge bg-success">

                                            {{ $remaining }} Left

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            Used

                                        </span>

                                    @endif

                                </div>


                                <div
                                    class="progress mt-3"
                                    style="height:6px;"
                                >

                                    <div
                                        class="progress-bar"
                                        style="width: {{ $percentage }}%;"
                                    ></div>

                                </div>


                                <div class="d-flex justify-content-between mt-2">

                                    <small class="text-muted">

                                        Used:

                                        <strong>
                                            {{ $used }}
                                        </strong>

                                    </small>


                                    <small class="text-muted">

                                        Total:

                                        <strong>
                                            {{ $total }}
                                        </strong>

                                    </small>

                                </div>

                            </div>

                        @empty

                            <div class="text-center py-4">

                                <i
                                    class="ti ti-gift text-muted"
                                    style="font-size:35px;"
                                ></i>

                                <h6 class="mt-2">

                                    No Benefits Found

                                </h6>

                                <p class="text-muted mb-0">

                                    This customer has no package benefits.

                                </p>

                            </div>

                        @endforelse

                    </div>

                </div>

            </div>


            {{-- ======================================================
                RIGHT SIDE
            ======================================================= --}}

            <div class="col-xl-8 col-lg-7">


                {{-- ==================================================
                    AVAILABLE PACKAGES
                =================================================== --}}

                <div class="card">

                    <div class="card-header">

                        <div>

                            <h5 class="card-title mb-1">

                                <i class="ti ti-package me-2"></i>

                                Available Packages

                            </h5>

                            <p class="text-muted mb-0">

                                Select a package to assign to this customer.

                            </p>

                        </div>

                    </div>


                    <div class="card-body">

                        <form
                            method="POST"
                            action="{{ route('customers.package.update', $customer->id) }}"
                            id="packageForm"
                        >

                            @csrf


                            <div class="row">

                                @forelse($packages as $package)

                                    @php

                                        $isCurrent =
                                            (int) $customer->package_id ===
                                            (int) $package->id;

                                    @endphp


                                    <div class="col-xl-6 col-md-6 mb-4">

                                        <label
                                            class="w-100"
                                            style="cursor:pointer;"
                                        >

                                            <input
                                                type="radio"
                                                name="package_id"
                                                value="{{ $package->id }}"
                                                class="package-radio"
                                                {{ $isCurrent ? 'checked' : '' }}
                                            >


                                            <div
                                                class="border rounded p-3 package-option
                                                {{ $isCurrent ? 'border-primary bg-light' : '' }}"
                                            >

                                                {{-- Header --}}

                                                <div
                                                    class="d-flex justify-content-between align-items-start"
                                                >

                                                    <div>

                                                        <h5 class="mb-1">

                                                            {{ $package->name }}

                                                        </h5>


                                                        <small class="text-muted">

                                                            {{ $package->duration_days }}

                                                            Days

                                                        </small>

                                                    </div>


                                                    @if($isCurrent)

                                                        <span class="badge bg-primary">

                                                            Current

                                                        </span>

                                                    @endif

                                                </div>


                                                {{-- Price --}}

                                                <h3 class="text-primary mt-3 mb-2">

                                                    ₹{{ number_format($package->price, 2) }}

                                                </h3>


                                                @if($package->description)

                                                    <p class="text-muted small">

                                                        {{ $package->description }}

                                                    </p>

                                                @endif


                                                <hr>


                                                {{-- Benefits --}}

                                                <h6 class="mb-3">

                                                    Package Benefits

                                                </h6>


                                                @forelse($package->activeBenefits as $benefit)

                                                    <div
                                                        class="d-flex justify-content-between
                                                               align-items-center mb-2"
                                                    >

                                                        <span>

                                                            <i
                                                                class="ti ti-circle-check text-success me-1"
                                                            ></i>

                                                            {{ $benefit->benefit_name }}

                                                        </span>


                                                        <span class="badge bg-light text-dark">

                                                            {{ $benefit->quantity }}

                                                        </span>

                                                    </div>

                                                @empty

                                                    <p class="text-muted small mb-0">

                                                        No benefits configured.

                                                    </p>

                                                @endforelse


                                                <div class="mt-3">

                                                    @if($isCurrent)

                                                        <span class="text-primary">

                                                            <i class="ti ti-circle-check me-1"></i>

                                                            Current Package

                                                        </span>

                                                    @else

                                                        <span class="text-muted">

                                                            <i class="ti ti-circle me-1"></i>

                                                            Select Package

                                                        </span>

                                                    @endif

                                                </div>

                                            </div>

                                        </label>

                                    </div>

                                @empty

                                    <div class="col-12">

                                        <div class="text-center py-4">

                                            <i
                                                class="ti ti-package text-muted"
                                                style="font-size:40px;"
                                            ></i>

                                            <h6 class="mt-2">

                                                No Packages Found

                                            </h6>

                                            <p class="text-muted mb-0">

                                                Please create an active package first.

                                            </p>

                                        </div>

                                    </div>

                                @endforelse

                            </div>


                            @if($packages->count())

                                <div class="border-top pt-3">

                                    <div
                                        class="d-flex justify-content-between
                                               align-items-center"
                                    >

                                        <div>

                                            <h6 class="mb-1">

                                                Update Customer Package

                                            </h6>

                                            <small class="text-muted">

                                                The selected package benefits
                                                will be assigned to the customer.

                                            </small>

                                        </div>


                                        <button
                                            type="submit"
                                            class="btn btn-primary"
                                            id="updatePackageBtn"
                                        >

                                            <i class="ti ti-refresh me-1"></i>

                                            Update Package

                                        </button>

                                    </div>

                                </div>

                            @endif

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ==============================================================
    SMALL PAGE-SPECIFIC STYLES
    Only for package selection behavior.
=============================================================== --}}

<style>

.package-radio {

    position: absolute;

    opacity: 0;

}


.package-option {

    transition: all .2s ease;

}


.package-option:hover {

    border-color: #405189 !important;

}


.package-radio:checked + .package-option {

    border: 2px solid #405189 !important;

    background-color: #f8f9ff !important;

}


</style>


@push('scripts')

<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | Package Selection
    |--------------------------------------------------------------------------
    */

    $('.package-radio').on('change', function () {

        $('.package-option')
            .removeClass('border-primary bg-light');

        $(this)
            .next('.package-option')
            .addClass('border-primary bg-light');

    });


    /*
    |--------------------------------------------------------------------------
    | Submit
    |--------------------------------------------------------------------------
    */

    $('#packageForm').on('submit', function () {

        const button =
            $('#updatePackageBtn');

        button
            .prop('disabled', true)
            .html(
                '<span class="spinner-border spinner-border-sm me-1"></span>' +
                'Updating...'
            );

    });


    /*
    |--------------------------------------------------------------------------
    | Feather / Theme Icons
    |--------------------------------------------------------------------------
    */

    if (typeof feather !== 'undefined') {

        feather.replace();

    }

});

</script>

@endpush

@endsection
