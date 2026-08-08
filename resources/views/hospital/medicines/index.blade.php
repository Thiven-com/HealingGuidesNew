@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            {{-- =========================================================
            PAGE HEADER
            ========================================================== --}}

            <div class="page-header">

                <div class="page-title">

                    <h4>Medicines</h4>

                    <h6>Manage hospital medicines</h6>

                </div>

                <div class="page-btn">

                    <a href="{{ route('hospital.medicines.create') }}" class="btn btn-primary">

                        <i class="ti ti-plus me-1"></i>

                        Add Medicine

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

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            @if(session('error'))

                <div class="alert alert-danger alert-dismissible fade show">

                    <i class="ti ti-alert-circle me-1"></i>

                    {{ session('error') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            @if($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- =========================================================
            MEDICINE LIST
            ========================================================== --}}

            <div class="card">

                <div class="card-header">

                    <div class="d-flex
                                align-items-center
                                justify-content-between
                                flex-wrap
                                gap-2">

                        <div>

                            <h5 class="card-title mb-0">
                                Medicine List
                            </h5>

                            <small class="text-muted">

                                {{ $medicines->total() }}
                                medicines

                            </small>

                        </div>

                    </div>

                </div>


                {{-- =====================================================
                FILTERS
                ====================================================== --}}

                <div class="card-body border-bottom">

                    <form method="GET" action="{{ route('hospital.medicines.index') }}">

                        <div class="row g-3">


                            {{-- SEARCH --}}

                            <div class="col-xl-4 col-md-6">

                                <label class="form-label">
                                    Search
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="ti ti-search"></i>
                                    </span>

                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                        placeholder="Medicine name, code, brand...">

                                </div>

                            </div>

                            {{-- STATUS --}}

                            <div class="col-xl-2 col-md-3">

                                <label class="form-label">
                                    Status
                                </label>

                                <select name="status" class="form-select">

                                    <option value="">
                                        All
                                    </option>

                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>

                                        Active

                                    </option>

                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>

                                        Inactive

                                    </option>

                                </select>

                            </div>


                            {{-- BUTTONS --}}

                            <div class="col-xl-4 col-md-12">

                                <label class="form-label d-block">
                                    &nbsp;
                                </label>

                                <div class="d-flex gap-2">

                                    <button type="submit" class="btn btn-primary">

                                        <i class="ti ti-search me-1"></i>

                                        Search

                                    </button>


                                    <a href="{{ route('hospital.medicines.index') }}" class="btn btn-light">

                                        <i class="ti ti-refresh me-1"></i>

                                        Reset

                                    </a>

                                </div>

                            </div>

                        </div>

                    </form>

                </div>


                {{-- =====================================================
                TABLE
                ====================================================== --}}

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th>
                                        #
                                    </th>

                                    <th>
                                        Medicine
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
                                        Status
                                    </th>

                                    <th class="text-end">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($medicines as $medicine)

                                                            <tr>

                                                                {{-- =================================
                                                                NUMBER
                                                                ================================== --}}

                                                                <td>

                                                                    {{ $medicines->firstItem() + $loop->index }}

                                                                </td>


                                                                {{-- =================================
                                                                MEDICINE
                                                                ================================== --}}

                                                                <td>

                                                                    <div class="d-flex
                                                                                    align-items-center">

                                                                        @if($medicine->image)

                                                                            <div class="avatar
                                                                                                avatar-md
                                                                                                me-2">

                                                                                <img src="{{ asset($medicine->image) }}"
                                                                                    alt="{{ $medicine->medicine_name }}" class="img-fluid rounded">

                                                                            </div>

                                                                        @else

                                                                            <div class="
                                                                                        avatar
                                                                                        avatar-md
                                                                                        bg-light-primary
                                                                                        text-primary
                                                                                        me-2
                                                                                    ">

                                                                                <i class="ti ti-pill"></i>

                                                                            </div>

                                                                        @endif


                                                                        <div>

                                                                            <a href="{{ route(
                                        'hospital.medicines.show',
                                        $medicine->id
                                    ) }}" class="fw-semibold text-dark">

                                                                                {{ $medicine->medicine_name }}

                                                                            </a>


                                                                            @if($medicine->generic_name)

                                                                                <small class="d-block text-muted">

                                                                                    {{ $medicine->generic_name }}

                                                                                </small>

                                                                            @endif


                                                                            @if($medicine->medicine_code)

                                                                                <small class="d-block text-muted">

                                                                                    Code:
                                                                                    {{ $medicine->medicine_code }}

                                                                                </small>

                                                                            @endif

                                                                        </div>

                                                                    </div>

                                                                </td>


                                                                {{-- =================================
                                                                TYPE
                                                                ================================== --}}

                                                                <td>

                                                                    {{ $medicine->medicine_type ?? '-' }}

                                                                </td>


                                                                {{-- =================================
                                                                STRENGTH
                                                                ================================== --}}

                                                                <td>

                                                                    {{ $medicine->strength ?? '-' }}

                                                                </td>


                                                                {{-- =================================
                                                                MRP
                                                                ================================== --}}

                                                                <td>

                                                                    @if($medicine->mrp !== null)

                                                                                                    ₹{{ number_format(
                                                                            $medicine->mrp,
                                                                            2
                                                                        ) }}

                                                                    @else

                                                                        -

                                                                    @endif

                                                                </td>


                                                                {{-- =================================
                                                                SELLING PRICE
                                                                ================================== --}}

                                                                <td>

                                                                    <span class="fw-semibold text-primary">

                                                                        ₹{{ number_format(
                                        $medicine->selling_price,
                                        2
                                    ) }}

                                                                    </span>

                                                                </td>


                                                                {{-- =================================
                                                                STOCK
                                                                ================================== --}}

                                                                <td>

                                                                    @php

                                                                        $stock =
                                                                            (int) $medicine->stock_quantity;

                                                                    @endphp


                                                                    @if($stock <= 0)

                                                                        <span class="badge bg-danger">

                                                                            Out of Stock

                                                                        </span>

                                                                        <small class="d-block text-danger mt-1">

                                                                            0 units

                                                                        </small>


                                                                    @elseif($stock <= 10)

                                                                        <span class="badge bg-warning">

                                                                            Low Stock

                                                                        </span>

                                                                        <small class="d-block text-muted mt-1">

                                                                            {{ $stock }} units

                                                                        </small>


                                                                    @else

                                                                        <span class="badge bg-success">

                                                                            In Stock

                                                                        </span>

                                                                        <small class="d-block text-muted mt-1">

                                                                            {{ $stock }} units

                                                                        </small>

                                                                    @endif

                                                                </td>

                                                                {{-- =================================
                                                                STATUS
                                                                ================================== --}}

                                                                <td>

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

                                                                </td>


                                                                {{-- =================================
                                                                ACTION
                                                                ================================== --}}

                                                                <td class="text-end">

                                                                    <div class="dropdown">

                                                                        <button type="button" class="btn btn-sm btn-light" data-bs-toggle="dropdown">

                                                                            <i class="ti ti-dots-vertical"></i>

                                                                        </button>


                                                                        <ul class="dropdown-menu dropdown-menu-end">


                                                                            {{-- VIEW --}}

                                                                            <li>

                                                                                <a class="dropdown-item" href="{{ route(
                                        'hospital.medicines.show',
                                        $medicine->id
                                    ) }}">

                                                                                    <i class="ti ti-eye me-2"></i>

                                                                                    View Details

                                                                                </a>

                                                                            </li>


                                                                            {{-- EDIT --}}

                                                                            <li>

                                                                                <a class="dropdown-item" href="{{ route(
                                        'hospital.medicines.edit',
                                        $medicine->id
                                    ) }}">

                                                                                    <i class="ti ti-edit me-2"></i>

                                                                                    Edit

                                                                                </a>

                                                                            </li>


                                                                            {{-- STOCK --}}

                                                                            <li>

                                                                                <a href="javascript:void(0);" class="dropdown-item"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#stockModal{{ $medicine->id }}">

                                                                                    <i class="ti ti-package me-2"></i>

                                                                                    Update Stock

                                                                                </a>

                                                                            </li>


                                                                            <li>
                                                                                <hr class="dropdown-divider">
                                                                            </li>


                                                                            {{-- STATUS --}}

                                                                            <li>

                                                                                <a href="javascript:void(0);" class="dropdown-item" onclick="changeMedicineStatus(
                                                                                           {{ $medicine->id }},
                                                                                           {{ $medicine->status ? 0 : 1 }}
                                                                                       )">

                                                                                    @if($medicine->status)

                                                                                        <i class="ti ti-ban me-2 text-danger"></i>

                                                                                        Deactivate

                                                                                    @else

                                                                                        <i class="ti ti-circle-check me-2 text-success"></i>

                                                                                        Activate

                                                                                    @endif

                                                                                </a>

                                                                            </li>


                                                                            {{-- DELETE --}}

                                                                            <li>

                                                                                <a href="javascript:void(0);" class="dropdown-item text-danger" onclick="deleteMedicine(
                                                                                           {{ $medicine->id }}
                                                                                       )">

                                                                                    <i class="ti ti-trash me-2"></i>

                                                                                    Delete

                                                                                </a>

                                                                            </li>

                                                                        </ul>

                                                                    </div>

                                                                </td>

                                                            </tr>


                                                            {{-- =================================================
                                                            STOCK MODAL
                                                            ================================================== --}}

                                                            <div class="modal fade" id="stockModal{{ $medicine->id }}" tabindex="-1">

                                                                <div class="modal-dialog modal-dialog-centered">

                                                                    <div class="modal-content">


                                                                        <div class="modal-header">

                                                                            <h5 class="modal-title">

                                                                                Update Stock

                                                                            </h5>

                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                                            </button>

                                                                        </div>


                                                                        <form method="POST" action="{{ route(
                                        'hospital.medicines.stock'
                                    ) }}">

                                                                            @csrf


                                                                            <div class="modal-body">

                                                                                <input type="hidden" name="id" value="{{ $medicine->id }}">


                                                                                <div class="mb-3">

                                                                                    <label class="form-label">

                                                                                        Medicine

                                                                                    </label>

                                                                                    <input type="text" class="form-control"
                                                                                        value="{{ $medicine->medicine_name }}" readonly>

                                                                                </div>


                                                                                <div class="mb-3">

                                                                                    <label class="form-label">

                                                                                        Current Stock

                                                                                    </label>

                                                                                    <input type="text" class="form-control"
                                                                                        value="{{ $medicine->stock_quantity }}" readonly>

                                                                                </div>


                                                                                <div class="mb-0">

                                                                                    <label class="form-label">

                                                                                        New Stock Quantity
                                                                                        <span class="text-danger">*</span>

                                                                                    </label>

                                                                                    <input type="number" name="stock_quantity" class="form-control"
                                                                                        min="0" value="{{ $medicine->stock_quantity }}" required>

                                                                                </div>

                                                                            </div>


                                                                            <div class="modal-footer">

                                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">

                                                                                    Cancel

                                                                                </button>


                                                                                <button type="submit" class="btn btn-primary">

                                                                                    <i class="ti ti-check me-1"></i>

                                                                                    Update Stock

                                                                                </button>

                                                                            </div>

                                                                        </form>

                                                                    </div>

                                                                </div>

                                                            </div>

                                @empty

                                                            <tr>

                                                                <td colspan="10" class="text-center py-5">

                                                                    <div>

                                                                        <i class="ti ti-pill" style="font-size:50px;">
                                                                        </i>

                                                                        <h5 class="mt-3">

                                                                            No medicines found

                                                                        </h5>

                                                                        <p class="text-muted mb-3">

                                                                            Add your first medicine
                                                                            to the hospital pharmacy.

                                                                        </p>


                                                                        <a href="{{ route(
                                        'hospital.medicines.create'
                                    ) }}" class="btn btn-primary">

                                                                            <i class="ti ti-plus me-1"></i>

                                                                            Add Medicine

                                                                        </a>

                                                                    </div>

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

                @if($medicines->hasPages())

                    <div class="card-footer">

                        <div class="d-flex
                                        align-items-center
                                        justify-content-between
                                        flex-wrap
                                        gap-2">

                            <div class="text-muted">

                                Showing

                                <strong>
                                    {{ $medicines->firstItem() ?? 0 }}
                                </strong>

                                to

                                <strong>
                                    {{ $medicines->lastItem() ?? 0 }}
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

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- =============================================================
    STATUS FORM
    ============================================================= --}}

    <form id="medicineStatusForm" method="POST" action="{{ route('hospital.medicines.status') }}" style="display:none;">

        @csrf

        <input type="hidden" name="id" id="statusMedicineId">

        <input type="hidden" name="status" id="statusMedicineValue">

    </form>


    {{-- =============================================================
    DELETE FORM
    ============================================================= --}}

    <form id="medicineDeleteForm" method="POST" style="display:none;">

        @csrf

        @method('DELETE')

    </form>


    {{-- =============================================================
    JAVASCRIPT
    ============================================================= --}}

    <script>

        function changeMedicineStatus(
            id,
            status
        ) {

            let message =
                status == 1
                    ? 'Are you sure you want to activate this medicine?'
                    : 'Are you sure you want to deactivate this medicine?';


            if (!confirm(message)) {
                return;
            }


            document.getElementById(
                'statusMedicineId'
            ).value = id;


            document.getElementById(
                'statusMedicineValue'
            ).value = status;


            document.getElementById(
                'medicineStatusForm'
            ).submit();
        }


        function deleteMedicine(id) {

            if (
                !confirm(
                    'Are you sure you want to delete this medicine?'
                )
            ) {

                return;

            }


            let form =
                document.getElementById(
                    'medicineDeleteForm'
                );


            form.action =
                "{{ url('hospital/medicines') }}/" +
                id;


            form.submit();

        }

    </script>

@endsection