<?php $page = 'medicines'; ?>

@extends('layout.mainlayout')

@section('content')

    <style>
        .table-responsive {
            overflow-x: auto !important;
            overflow-y: visible !important;
        }

        .table {
            min-width: 1800px;
        }

        .dropdown-menu {
            z-index: 99999 !important;
        }

        .medicine-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .medicine-name {
            font-weight: 600;
            color: #212529;
        }

        .medicine-code {
            font-size: 12px;
            color: #6c757d;
        }

        .stock-badge {
            min-width: 55px;
            display: inline-block;
            text-align: center;
        }
    </style>


    <div class="page-wrapper">

        <div class="content">

            {{-- ============================================================
            PAGE HEADER
            ============================================================ --}}

            <div class="page-header">

                <div class="add-item d-flex">

                    <div class="page-title">

                        <h4>Medicines</h4>

                        <h6>
                            Manage Hospital Medicines
                        </h6>

                    </div>

                </div>


                <ul class="table-top-head">

                    <li>

                        <a href="{{ route('admin.medicines.index') }}" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Refresh">

                            <i data-feather="rotate-ccw" class="feather-rotate-ccw"></i>

                        </a>

                    </li>


                    <li>

                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header">

                            <i data-feather="chevron-up" class="feather-chevron-up"></i>

                        </a>

                    </li>

                </ul>


                <div class="page-btn">

                    <a href="{{ route('admin.medicines.create') }}" class="btn btn-added">

                        <i data-feather="plus-circle" class="me-2"></i>

                        Add Medicine

                    </a>

                </div>

            </div>


            {{-- ============================================================
            SUCCESS / ERROR MESSAGES
            ============================================================ --}}

            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show" role="alert">

                    <i class="ti ti-circle-check me-1"></i>

                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>

            @endif


            @if(session('error'))

                <div class="alert alert-danger alert-dismissible fade show" role="alert">

                    <i class="ti ti-alert-circle me-1"></i>

                    {{ session('error') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>

            @endif


            {{-- ============================================================
            MEDICINES TABLE
            ============================================================ --}}

            <div class="card table-list-card">

                <div class="card-body">

                    <form method="GET" action="{{ route('admin.medicines.index') }}">

                        <div class="row g-3 align-items-end" style="margin-bottom: 15px;">

                            {{-- Search --}}
                            <div class="col-md-4">
                                <label class="form-label">Search</label>

                                <input type="text" name="search" class="form-control"
                                    placeholder="Medicine name, code, generic name, brand..."
                                    value="{{ request('search') }}">
                            </div>

                            {{-- Hospital --}}
                            <div class="col-md-3">
                                <label class="form-label">Hospital</label>

                                <select name="hospital_id" class="form-select">
                                    <option value="">All Hospitals</option>

                                    @foreach($hospitals as $hospital)
                                        <option value="{{ $hospital->id }}" {{ request('hospital_id') == $hospital->id ? 'selected' : '' }}>
                                            {{ $hospital->hospital_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Category --}}
                            <div class="col-md-3">
                                <label class="form-label">Category</label>

                                <select name="medicine_category_id" class="form-select">
                                    <option value="">All Categories</option>

                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('medicine_category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Type --}}
                            <div class="col-md-2">
                                <label class="form-label">Type</label>

                                <select name="medicine_type" class="form-select">
                                    <option value="">All Types</option>

                                    @foreach($medicineTypes as $type)
                                        <option value="{{ $type }}" {{ request('medicine_type') == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Prescription --}}
                            <div class="col-md-3">
                                <label class="form-label">Prescription</label>

                                <select name="prescription_required" class="form-select">
                                    <option value="">All</option>

                                    <option value="1" {{ request('prescription_required') === '1' ? 'selected' : '' }}>
                                        Required
                                    </option>

                                    <option value="0" {{ request('prescription_required') === '0' ? 'selected' : '' }}>
                                        Not Required
                                    </option>
                                </select>
                            </div>

                            {{-- Status --}}
                            <div class="col-md-3">
                                <label class="form-label">Status</label>

                                <select name="status" class="form-select">
                                    <option value="">All Status</option>

                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                            </div>

                            {{-- Buttons --}}
                            <div class="col-md-6 d-flex gap-2">

                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-search me-1"></i>
                                    Filter
                                </button>

                                <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary">
                                    <i class="ti ti-refresh me-1"></i>
                                    Reset
                                </a>

                            </div>

                        </div>

                    </form>

                    <div class="table-responsive">

                        <table class="table datanew">

                            <thead>

                                <tr>

                                    <th>
                                        #
                                    </th>

                                    <th>
                                        Medicine
                                    </th>

                                    <th>
                                        Hospital
                                    </th>

                                    <th>
                                        Category
                                    </th>

                                    <th>
                                        Type
                                    </th>

                                    <th>
                                        Strength
                                    </th>

                                    <th>
                                        MRP
                                    </th>

                                    <th>
                                        Selling Price
                                    </th>

                                    <th>
                                        Stock
                                    </th>

                                    <th>
                                        Prescription
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th class="text-center">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($medicines as $key => $medicine)

                                                            <tr>
                                                                <td>

                                                                    {{ $medicines->firstItem() + $key }}

                                                                </td>
                                                                <td>

                                                                    <div class="d-flex align-items-center gap-2">

                                                                        @if($medicine->image)

                                                                            <img src="{{ asset($medicine->image) }}" alt="{{ $medicine->medicine_name }}"
                                                                                class="medicine-img">

                                                                        @else

                                                                            <div
                                                                                class="medicine-img bg-light d-flex align-items-center justify-content-center">

                                                                                <i class="ti ti-pill text-muted fs-4"></i>

                                                                            </div>

                                                                        @endif


                                                                        <div>

                                                                            <div class="medicine-name">

                                                                                {{ $medicine->medicine_name }}

                                                                            </div>


                                                                            <div class="medicine-code">

                                                                                {{ $medicine->medicine_code }}

                                                                            </div>


                                                                            @if($medicine->brand_name)

                                                                                <small class="text-muted">

                                                                                    {{ $medicine->brand_name }}

                                                                                </small>

                                                                            @endif

                                                                        </div>

                                                                    </div>

                                                                </td>
                                                                <td>

                                                                    @if($medicine->hospital)

                                                                        <div>

                                                                            <span class="fw-medium">

                                                                                {{ $medicine->hospital->hospital_name }}

                                                                            </span>

                                                                        </div>

                                                                        @if($medicine->hospital->hospital_code)

                                                                            <small class="text-muted">

                                                                                {{ $medicine->hospital->hospital_code }}

                                                                            </small>

                                                                        @endif

                                                                    @else

                                                                        <span class="text-muted">
                                                                            -
                                                                        </span>

                                                                    @endif

                                                                </td>


                                                                {{-- Category --}}

                                                                <td>

                                                                    @if($medicine->category)

                                                                        <span class="badge bg-info-subtle text-info">

                                                                            {{ $medicine->category->category_name }}

                                                                        </span>

                                                                    @else

                                                                        <span class="text-muted">
                                                                            -
                                                                        </span>

                                                                    @endif

                                                                </td>


                                                                {{-- Medicine Type --}}

                                                                <td>

                                                                    @if($medicine->medicine_type)

                                                                                                    {{ ucfirst(
                                                                            $medicine->medicine_type
                                                                        ) }}

                                                                    @else

                                                                        <span class="text-muted">
                                                                            -
                                                                        </span>

                                                                    @endif

                                                                </td>


                                                                {{-- Strength --}}

                                                                <td>

                                                                    @if($medicine->strength)

                                                                        {{ $medicine->strength }}

                                                                    @else

                                                                        <span class="text-muted">
                                                                            -
                                                                        </span>

                                                                    @endif

                                                                </td>


                                                                {{-- MRP --}}

                                                                <td>

                                                                    <span class="text-muted">

                                                                        ₹{{ number_format(
                                        $medicine->mrp,
                                        2
                                    ) }}

                                                                    </span>

                                                                </td>


                                                                {{-- Selling Price --}}

                                                                <td>

                                                                    <span class="fw-semibold text-success">

                                                                        ₹{{ number_format(
                                        $medicine->selling_price,
                                        2
                                    ) }}

                                                                    </span>

                                                                    @if(
                                                                            $medicine->mrp > 0 &&
                                                                            $medicine->selling_price < $medicine->mrp
                                                                        )

                                                                        @php

                                                                            $discount =
                                                                                (($medicine->mrp -
                                                                                    $medicine->selling_price)
                                                                                    /
                                                                                    $medicine->mrp)
                                                                                * 100;

                                                                        @endphp

                                                                        <div>

                                                                            <small class="text-success">

                                                                                {{ round($discount) }}% off

                                                                            </small>

                                                                        </div>

                                                                    @endif

                                                                </td>


                                                                {{-- Stock --}}

                                                                <td>

                                                                    @if($medicine->stock_quantity <= 0)

                                                                        <span class="badge bg-danger stock-badge">

                                                                            Out of Stock

                                                                        </span>

                                                                    @elseif($medicine->stock_quantity <= 10)

                                                                        <span class="badge bg-warning text-dark stock-badge">

                                                                            {{ $medicine->stock_quantity }}

                                                                        </span>

                                                                        <div>

                                                                            <small class="text-danger">
                                                                                Low Stock
                                                                            </small>

                                                                        </div>

                                                                    @else

                                                                        <span class="badge bg-success-subtle text-success stock-badge">

                                                                            {{ $medicine->stock_quantity }}

                                                                        </span>

                                                                    @endif

                                                                </td>


                                                                {{-- Prescription Required --}}

                                                                <td>

                                                                    @if($medicine->prescription_required)

                                                                        <span class="badge bg-warning-subtle text-warning">

                                                                            <i class="ti ti-file-description me-1"></i>

                                                                            Required

                                                                        </span>

                                                                    @else

                                                                        <span class="badge bg-success-subtle text-success">

                                                                            Not Required

                                                                        </span>

                                                                    @endif

                                                                </td>


                                                                {{-- Status --}}

                                                                <td>

                                                                    @if($medicine->status)

                                                                        <span class="badge bg-success">

                                                                            Active

                                                                        </span>

                                                                    @else

                                                                        <span class="badge bg-danger">

                                                                            Inactive

                                                                        </span>

                                                                    @endif

                                                                </td>


                                                                {{-- ====================================================
                                                                ACTION
                                                                ==================================================== --}}

                                                                <td class="text-center">

                                                                    <div class="dropdown">

                                                                        <a href="javascript:void(0);" class="btn btn-sm btn-light"
                                                                            data-bs-toggle="dropdown" aria-expanded="false">

                                                                            <i class="ti ti-dots-vertical"></i>

                                                                        </a>


                                                                        <div class="dropdown-menu dropdown-menu-end">

                                                                            {{-- Edit --}}

                                                                            <a class="dropdown-item" href="{{ route(
                                        'admin.medicines.edit',
                                        $medicine->id
                                    ) }}">

                                                                                <i class="ti ti-edit me-2"></i>

                                                                                Edit

                                                                            </a>


                                                                            {{-- Status --}}

                                                                            <form action="{{ route(
                                        'admin.medicines.status',
                                        $medicine->id
                                    ) }}" method="POST">

                                                                                @csrf

                                                                                <button type="submit" class="dropdown-item">

                                                                                    @if($medicine->status)

                                                                                        <i class="ti ti-lock me-2"></i>

                                                                                        Make Inactive

                                                                                    @else

                                                                                        <i class="ti ti-lock-open me-2"></i>

                                                                                        Make Active

                                                                                    @endif

                                                                                </button>

                                                                            </form>


                                                                            <div class="dropdown-divider"></div>


                                                                            {{-- Delete --}}

                                                                            <a href="javascript:void(0);" class="dropdown-item text-danger"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#deleteMedicineModal{{ $medicine->id }}">

                                                                                <i class="ti ti-trash me-2"></i>

                                                                                Delete

                                                                            </a>

                                                                        </div>

                                                                    </div>

                                                                </td>

                                                            </tr>


                                                            {{-- ========================================================
                                                            DELETE MODAL
                                                            ======================================================== --}}

                                                            <div class="modal fade" id="deleteMedicineModal{{ $medicine->id }}" tabindex="-1"
                                                                aria-hidden="true">

                                                                <div class="modal-dialog modal-dialog-centered">

                                                                    <div class="modal-content">

                                                                        <div class="modal-header">

                                                                            <h5 class="modal-title">

                                                                                Delete Medicine

                                                                            </h5>

                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                                                        </div>


                                                                        <div class="modal-body">

                                                                            <div class="text-center mb-3">

                                                                                <div
                                                                                    class="avatar avatar-xl bg-danger-transparent rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">

                                                                                    <i class="ti ti-trash fs-2 text-danger"></i>

                                                                                </div>

                                                                                <h5>

                                                                                    Delete Medicine?

                                                                                </h5>

                                                                                <p class="text-muted">

                                                                                    Are you sure you want
                                                                                    to delete

                                                                                    <strong>

                                                                                        {{ $medicine->medicine_name }}

                                                                                    </strong>?

                                                                                </p>

                                                                            </div>


                                                                            <div class="alert alert-warning mb-0">

                                                                                <i class="ti ti-alert-triangle me-1"></i>

                                                                                This action cannot be undone.

                                                                            </div>

                                                                        </div>


                                                                        <div class="modal-footer">

                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                                                                                Cancel

                                                                            </button>


                                                                            <form action="{{ route(
                                        'admin.medicines.destroy',
                                        $medicine->id
                                    ) }}" method="POST">

                                                                                @csrf
                                                                                @method('DELETE')

                                                                                <button type="submit" class="btn btn-danger">

                                                                                    <i class="ti ti-trash me-1"></i>

                                                                                    Delete

                                                                                </button>

                                                                            </form>

                                                                        </div>

                                                                    </div>

                                                                </div>

                                                            </div>

                                @empty

                                                            <tr>

                                                                <td colspan="12" class="text-center py-5">

                                                                    <div class="mb-3">

                                                                        <i class="ti ti-pill-off"
                                                                            style="
                                                                                                                                                                                                                                                                                        font-size: 55px;
                                                                                                                                                                                                                                                                                        color: #adb5bd;
                                                                                                                                                                                                                                                                                    "></i>

                                                                    </div>


                                                                    <h5>

                                                                        No Medicines Found

                                                                    </h5>


                                                                    <p class="text-muted mb-3">

                                                                        There are no medicines
                                                                        available yet.

                                                                    </p>


                                                                    <a href="{{ route(
                                        'admin.medicines.create'
                                    ) }}" class="btn btn-added">

                                                                        <i data-feather="plus-circle" class="me-1"></i>

                                                                        Add Medicine

                                                                    </a>

                                                                </td>

                                                            </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>
                    @if($medicines->hasPages())

                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-4">

                            <div class="text-muted">

                                Showing

                                <strong>
                                    {{ $medicines->firstItem() }}
                                </strong>

                                to

                                <strong>
                                    {{ $medicines->lastItem() }}
                                </strong>

                                of

                                <strong>
                                    {{ $medicines->total() }}
                                </strong>

                                medicines

                            </div>

                            <div>

                                {{ $medicines->links('pagination::bootstrap-5') }}

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>
    <script>

        $(document).ready(function () {

            /*
            |--------------------------------------------------------------------------
            | Medicines DataTable
            |--------------------------------------------------------------------------
            */

            if ($('.datanew').length) {

                $('.datanew').DataTable({

                    responsive: true,

                    autoWidth: false,

                    ordering: true,

                    pageLength: 10,

                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"]
                    ],

                    columnDefs: [

                        {
                            targets: [1, 11],
                            orderable: false
                        }

                    ],

                    language: {

                        search: "",

                        searchPlaceholder:
                            "Search medicines..."

                    }

                });

            }


            /*
            |--------------------------------------------------------------------------
            | Feather Icons
            |--------------------------------------------------------------------------
            */

            if (typeof feather !== 'undefined') {

                feather.replace();

            }

        });

    </script>
@endsection