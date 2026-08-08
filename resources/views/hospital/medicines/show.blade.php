@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">

    <div class="content">

        {{-- =========================================================
        PAGE HEADER
        ========================================================== --}}

        <div class="page-header">

            <div class="page-title">

                <h4>Medicine Details</h4>

                <h6>View medicine information</h6>

            </div>

            <div class="page-btn d-flex gap-2">

                <a href="{{ route('hospital.medicines.index') }}"
                   class="btn btn-light">

                    <i class="ti ti-arrow-left me-1"></i>
                    Back

                </a>

                <a href="{{ route(
                    'hospital.medicines.edit',
                    $medicine->id
                ) }}"
                   class="btn btn-primary">

                    <i class="ti ti-edit me-1"></i>
                    Edit

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
        MEDICINE SUMMARY
        ========================================================== --}}

        <div class="card">

            <div class="card-body">

                <div class="row align-items-center">

                    {{-- IMAGE --}}

                    <div class="col-lg-2 col-md-3 text-center">

                        @if($medicine->image)

                            <img src="{{ asset($medicine->image) }}"
                                 alt="{{ $medicine->medicine_name }}"
                                 style="
                                    width:150px;
                                    height:150px;
                                    object-fit:cover;
                                    border-radius:12px;
                                    border:1px solid #e5e7eb;
                                 ">

                        @else

                            <div class="
                                d-flex
                                align-items-center
                                justify-content-center
                                bg-light
                                rounded
                                mx-auto
                            "
                                 style="
                                    width:150px;
                                    height:150px;
                                 ">

                                <i class="ti ti-pill"
                                   style="font-size:65px;">
                                </i>

                            </div>

                        @endif

                    </div>


                    {{-- BASIC DETAILS --}}

                    <div class="col-lg-7 col-md-6 mt-3 mt-md-0">

                        <h3 class="mb-1">

                            {{ $medicine->medicine_name }}

                        </h3>


                        @if($medicine->generic_name)

                            <p class="text-muted mb-2">

                                {{ $medicine->generic_name }}

                            </p>

                        @endif


                        <div class="d-flex
                                    flex-wrap
                                    gap-2
                                    mb-3">

                            @if($medicine->medicine_type)

                                <span class="badge bg-light-primary text-primary">

                                    {{ $medicine->medicine_type }}

                                </span>

                            @endif


                            @if($medicine->strength)

                                <span class="badge bg-light-info text-info">

                                    {{ $medicine->strength }}

                                </span>

                            @endif


                            @if($medicine->status)

                                <span class="badge bg-success">

                                    <i class="ti ti-circle-check me-1"></i>

                                    Active

                                </span>

                            @else

                                <span class="badge bg-danger">

                                    <i class="ti ti-circle-x me-1"></i>

                                    Inactive

                                </span>

                            @endif


                            @if($medicine->prescription_required)

                                <span class="badge bg-warning">

                                    Prescription Required

                                </span>

                            @else

                                <span class="badge bg-light-success text-success">

                                    No Prescription Required

                                </span>

                            @endif

                        </div>


                        <div class="row">

                            <div class="col-md-6 mb-2">

                                <small class="text-muted d-block">
                                    Medicine Code
                                </small>

                                <strong>
                                    {{ $medicine->medicine_code ?? '-' }}
                                </strong>

                            </div>


                            <div class="col-md-6 mb-2">

                                <small class="text-muted d-block">
                                    Brand Name
                                </small>

                                <strong>
                                    {{ $medicine->brand_name ?? '-' }}
                                </strong>

                            </div>


                            <div class="col-md-6 mb-2">

                                <small class="text-muted d-block">
                                    Manufacturer
                                </small>

                                <strong>
                                    {{ $medicine->manufacturer ?? '-' }}
                                </strong>

                            </div>


                            <div class="col-md-6 mb-2">

                                <small class="text-muted d-block">
                                    Pack Size
                                </small>

                                <strong>
                                    {{ $medicine->pack_size ?? '-' }}
                                </strong>

                            </div>

                        </div>

                    </div>


                    {{-- PRICE / STOCK --}}

                    <div class="col-lg-3 col-md-3 mt-3 mt-lg-0">

                        <div class="border rounded p-3">

                            <div class="mb-3">

                                <small class="text-muted d-block">
                                    Selling Price
                                </small>

                                <h4 class="text-primary mb-0">

                                    ₹{{ number_format(
                                        $medicine->selling_price,
                                        2
                                    ) }}

                                </h4>

                            </div>


                            <div class="mb-3">

                                <small class="text-muted d-block">
                                    MRP
                                </small>

                                <strong>

                                    @if($medicine->mrp !== null)

                                        ₹{{ number_format(
                                            $medicine->mrp,
                                            2
                                        ) }}

                                    @else

                                        -

                                    @endif

                                </strong>

                            </div>


                            <div>

                                <small class="text-muted d-block">
                                    Current Stock
                                </small>

                                @php
                                    $stock = (int) $medicine->stock_quantity;
                                @endphp

                                @if($stock <= 0)

                                    <span class="badge bg-danger">
                                        Out of Stock
                                    </span>

                                @elseif($stock <= 10)

                                    <span class="badge bg-warning">
                                        Low Stock
                                    </span>

                                @else

                                    <span class="badge bg-success">
                                        In Stock
                                    </span>

                                @endif

                                <strong class="ms-1">

                                    {{ $stock }} units

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
        INFORMATION CARDS
        ========================================================== --}}

        <div class="row">


            {{-- DESCRIPTION --}}

            <div class="col-lg-6">

                <div class="card h-100">

                    <div class="card-header">

                        <h5 class="card-title mb-0">

                            <i class="ti ti-file-description me-2"></i>

                            Description

                        </h5>

                    </div>

                    <div class="card-body">

                        @if($medicine->description)

                            <p class="mb-0">

                                {!! nl2br(e($medicine->description)) !!}

                            </p>

                        @else

                            <span class="text-muted">
                                No description available.
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- COMPOSITION --}}

            <div class="col-lg-6">

                <div class="card h-100">

                    <div class="card-header">

                        <h5 class="card-title mb-0">

                            <i class="ti ti-flask me-2"></i>

                            Composition

                        </h5>

                    </div>

                    <div class="card-body">

                        @if($medicine->composition)

                            <p class="mb-0">

                                {!! nl2br(e($medicine->composition)) !!}

                            </p>

                        @else

                            <span class="text-muted">
                                No composition information available.
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- USAGE --}}

            <div class="col-lg-6">

                <div class="card h-100">

                    <div class="card-header">

                        <h5 class="card-title mb-0">

                            <i class="ti ti-notes me-2"></i>

                            Usage Instructions

                        </h5>

                    </div>

                    <div class="card-body">

                        @if($medicine->usage_instructions)

                            <p class="mb-0">

                                {!! nl2br(e(
                                    $medicine->usage_instructions
                                )) !!}

                            </p>

                        @else

                            <span class="text-muted">
                                No usage instructions available.
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- SIDE EFFECTS --}}

            <div class="col-lg-6">

                <div class="card h-100">

                    <div class="card-header">

                        <h5 class="card-title mb-0">

                            <i class="ti ti-alert-triangle me-2"></i>

                            Side Effects

                        </h5>

                    </div>

                    <div class="card-body">

                        @if($medicine->side_effects)

                            <p class="mb-0">

                                {!! nl2br(e(
                                    $medicine->side_effects
                                )) !!}

                            </p>

                        @else

                            <span class="text-muted">
                                No side effects information available.
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- STORAGE --}}

            <div class="col-lg-6">

                <div class="card">

                    <div class="card-header">

                        <h5 class="card-title mb-0">

                            <i class="ti ti-temperature me-2"></i>

                            Storage Instructions

                        </h5>

                    </div>

                    <div class="card-body">

                        @if($medicine->storage_instructions)

                            <p class="mb-0">

                                {!! nl2br(e(
                                    $medicine->storage_instructions
                                )) !!}

                            </p>

                        @else

                            <span class="text-muted">
                                No storage instructions available.
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- MEDICINE INFORMATION --}}

            <div class="col-lg-6">

                <div class="card">

                    <div class="card-header">

                        <h5 class="card-title mb-0">

                            <i class="ti ti-info-circle me-2"></i>

                            Additional Information

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table table-borderless mb-0">

                                <tr>

                                    <td class="text-muted">
                                        Generic Name
                                    </td>

                                    <td class="fw-semibold">
                                        {{ $medicine->generic_name ?? '-' }}
                                    </td>

                                </tr>

                                <tr>

                                    <td class="text-muted">
                                        Brand Name
                                    </td>

                                    <td class="fw-semibold">
                                        {{ $medicine->brand_name ?? '-' }}
                                    </td>

                                </tr>

                                <tr>

                                    <td class="text-muted">
                                        Manufacturer
                                    </td>

                                    <td class="fw-semibold">
                                        {{ $medicine->manufacturer ?? '-' }}
                                    </td>

                                </tr>

                                <tr>

                                    <td class="text-muted">
                                        Medicine Type
                                    </td>

                                    <td class="fw-semibold">
                                        {{ $medicine->medicine_type ?? '-' }}
                                    </td>

                                </tr>

                                <tr>

                                    <td class="text-muted">
                                        Strength
                                    </td>

                                    <td class="fw-semibold">
                                        {{ $medicine->strength ?? '-' }}
                                    </td>

                                </tr>

                                <tr>

                                    <td class="text-muted">
                                        Pack Size
                                    </td>

                                    <td class="fw-semibold">
                                        {{ $medicine->pack_size ?? '-' }}
                                    </td>

                                </tr>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
        STATUS / ACTION
        ========================================================== --}}

        <div class="card">

            <div class="card-header">

                <h5 class="card-title mb-0">
                    Medicine Status
                </h5>

            </div>

            <div class="card-body">

                <div class="d-flex
                            align-items-center
                            justify-content-between
                            flex-wrap
                            gap-3">

                    <div>

                        @if($medicine->status)

                            <span class="badge bg-success fs-6">

                                <i class="ti ti-circle-check me-1"></i>

                                Active

                            </span>

                            <p class="text-muted mb-0 mt-2">

                                This medicine is currently available
                                in the hospital pharmacy.

                            </p>

                        @else

                            <span class="badge bg-danger fs-6">

                                <i class="ti ti-circle-x me-1"></i>

                                Inactive

                            </span>

                            <p class="text-muted mb-0 mt-2">

                                This medicine is currently inactive.

                            </p>

                        @endif

                    </div>


                    <div class="d-flex gap-2">

                        {{-- UPDATE STOCK --}}

                        <button type="button"
                                class="btn btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#stockModal">

                            <i class="ti ti-package me-1"></i>

                            Update Stock

                        </button>


                        {{-- STATUS --}}

                        <form method="POST"
                              action="{{ route(
                                  'hospital.medicines.status'
                              ) }}">

                            @csrf

                            <input type="hidden"
                                   name="id"
                                   value="{{ $medicine->id }}">

                            <input type="hidden"
                                   name="status"
                                   value="{{ $medicine->status ? 0 : 1 }}">

                            @if($medicine->status)

                                <button type="submit"
                                        class="btn btn-outline-danger"
                                        onclick="return confirm(
                                            'Are you sure you want to deactivate this medicine?'
                                        );">

                                    <i class="ti ti-ban me-1"></i>

                                    Deactivate

                                </button>

                            @else

                                <button type="submit"
                                        class="btn btn-outline-success"
                                        onclick="return confirm(
                                            'Are you sure you want to activate this medicine?'
                                        );">

                                    <i class="ti ti-circle-check me-1"></i>

                                    Activate

                                </button>

                            @endif

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =============================================================
STOCK MODAL
============================================================= --}}

<div class="modal fade"
     id="stockModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="ti ti-package me-2"></i>

                    Update Stock

                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>


            <form method="POST"
                  action="{{ route(
                      'hospital.medicines.stock'
                  ) }}">

                @csrf


                <div class="modal-body">

                    <input type="hidden"
                           name="id"
                           value="{{ $medicine->id }}">


                    <div class="mb-3">

                        <label class="form-label">
                            Medicine
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $medicine->medicine_name }}"
                               readonly>

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Current Stock
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $medicine->stock_quantity }} units"
                               readonly>

                    </div>


                    <div>

                        <label class="form-label">

                            New Stock Quantity

                            <span class="text-danger">*</span>

                        </label>

                        <input type="number"
                               name="stock_quantity"
                               class="form-control"
                               value="{{ $medicine->stock_quantity }}"
                               min="0"
                               required>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="ti ti-check me-1"></i>

                        Update Stock

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection