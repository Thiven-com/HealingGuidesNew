<?php $page = 'ambulances'; ?>

@extends('layout.mainlayout')

@section('content')

    <style>
        .table-responsive {
            overflow-x: auto !important;
            overflow-y: visible !important;
        }

        .table {
            min-width: 1900px;
        }
    </style>

    <div class="page-wrapper">

        <div class="content">

            <!-- Page Header -->

            <div class="page-header">

                <div class="add-item d-flex">

                    <div class="page-title">

                        <h4>Ambulances</h4>

                        <h6>Manage Ambulances</h6>

                    </div>

                </div>

                <ul class="table-top-head">

                    <li>

                        <a href="{{ route('admin.ambulances.index') }}" data-bs-toggle="tooltip" title="Refresh">

                            <i data-feather="rotate-ccw"></i>

                        </a>

                    </li>

                    <li>

                        <a id="collapse-header" data-bs-toggle="tooltip" title="Collapse">

                            <i data-feather="chevron-up"></i>

                        </a>

                    </li>

                </ul>

                <div class="page-btn">

                    <a href="{{ route('admin.ambulances.create') }}" class="btn btn-added">

                        <i data-feather="plus-circle" class="me-2"></i>

                        Add Ambulance

                    </a>

                </div>

            </div>

            <!-- /Page Header -->

            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">

                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>

            @endif

            @if(session('error'))

                <div class="alert alert-danger alert-dismissible fade show">

                    {{ session('error') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>

            @endif

            <div class="card table-list-card">

                <div class="card-body">

                    {{-- Filters --}}

                    <form method="GET" action="{{ route('admin.ambulances.index') }}">

                        <div class="row g-3 align-items-end mb-4">

                            {{-- Search --}}
                            <div class="col-md-4">

                                <label class="form-label">
                                    Search
                                </label>

                                <input type="text" name="search" class="form-control"
                                    placeholder="Name, code, vehicle or driver" value="{{ request('search') }}">

                            </div>

                            {{-- Hospital --}}
                            <div class="col-md-2">

                                <label class="form-label">
                                    Hospital
                                </label>

                                <select name="hospital_id" class="form-select">

                                    <option value="">
                                        All Hospitals
                                    </option>

                                    @foreach($hospitals as $hospital)

                                        <option value="{{ $hospital->id }}" {{ request('hospital_id') == $hospital->id ? 'selected' : '' }}>

                                            {{ $hospital->hospital_name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            {{-- Ambulance Type --}}
                            <div class="col-md-2">

                                <label class="form-label">
                                    Ambulance Type
                                </label>

                                <select name="ambulance_type_id" class="form-select">

                                    <option value="">
                                        All Types
                                    </option>

                                    @foreach($ambulanceTypes as $type)

                                        <option value="{{ $type->id }}" {{ request('ambulance_type_id') == $type->id ? 'selected' : '' }}>

                                            {{ $type->ambulance_type_name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            {{-- Availability --}}
                            <div class="col-md-2">

                                <label class="form-label">
                                    Availability
                                </label>

                                <select name="is_available" class="form-select">

                                    <option value="">
                                        All Availability
                                    </option>

                                    <option value="1" {{ request('is_available') === '1' ? 'selected' : '' }}>
                                        Available
                                    </option>

                                    <option value="0" {{ request('is_available') === '0' ? 'selected' : '' }}>
                                        Not Available
                                    </option>

                                </select>

                            </div>

                            {{-- Status --}}
                            <div class="col-md-2">

                                <label class="form-label">
                                    Status
                                </label>

                                <select name="status" class="form-select">

                                    <option value="">
                                        All Status
                                    </option>

                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>
                                        Inactive
                                    </option>

                                </select>

                            </div>

                            {{-- Buttons --}}
                            <div class="col-md-12 d-flex gap-2">

                                <button type="submit" class="btn btn-primary">

                                    <i class="ti ti-search me-1"></i>
                                    Filter

                                </button>

                                <a href="{{ route('admin.ambulances.index') }}" class="btn btn-secondary">

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

                                    <th width="60">#</th>

                                    <th width="90">Image</th>

                                    <th>Ambulance</th>

                                    <th>Hospital</th>

                                    <th>Type</th>

                                    <th>Vehicle No</th>

                                    <th>Driver</th>

                                    <th>Base Fare</th>

                                    <th>Price/KM</th>

                                    <th>Availability</th>

                                    <th>Status</th>

                                    <th width="90" class="text-center">

                                        Action

                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($ambulances as $key => $ambulance)

                                    <tr>

                                        <td>

                                            {{ $key + 1 }}

                                        </td>

                                        <td>

                                            @if($ambulance->image)

                                                <img src="{{ asset($ambulance->image) }}" class="img-thumbnail"
                                                    style="width:60px;height:60px;object-fit:cover;">

                                            @else

                                                <img src="{{ asset('assets/img/no-image.png') }}" class="img-thumbnail"
                                                    style="width:60px;height:60px;object-fit:cover;">

                                            @endif

                                        </td>
                                        <td>

                                            <div>

                                                <strong>

                                                    {{ $ambulance->ambulance_name }}

                                                </strong>

                                                <br>

                                                <small class="text-muted">

                                                    {{ $ambulance->ambulance_code }}

                                                </small>

                                            </div>

                                        </td>

                                        <td>

                                            {{ optional($ambulance->hospital)->hospital_name ?? '-' }}

                                        </td>

                                        <td>

                                            {{ optional($ambulance->ambulanceType)->ambulance_type_name ?? '-' }}

                                        </td>

                                        <td>

                                            {{ $ambulance->vehicle_number }}

                                        </td>

                                        <td>

                                            <div>

                                                <strong>

                                                    {{ $ambulance->driver_name }}

                                                </strong>

                                                <br>

                                                <small class="text-muted">

                                                    {{ $ambulance->driver_mobile }}

                                                </small>

                                            </div>

                                        </td>

                                        <td>

                                            ₹ {{ number_format($ambulance->base_fare, 2) }}

                                        </td>

                                        <td>

                                            ₹ {{ number_format($ambulance->price_per_km, 2) }}

                                        </td>

                                        <td>

                                            @if($ambulance->is_available)

                                                <span class="badge bg-success">

                                                    Available

                                                </span>

                                            @else

                                                <span class="badge bg-warning text-dark">

                                                    Not Available

                                                </span>

                                            @endif

                                        </td>

                                        <td>

                                            @if($ambulance->status)

                                                <span class="badge bg-success">

                                                    Active

                                                </span>

                                            @else

                                                <span class="badge bg-danger">

                                                    Inactive

                                                </span>

                                            @endif

                                        </td>
                                        <td class="text-center">

                                            <div class="dropdown">

                                                <a href="javascript:void(0)" class="btn btn-sm btn-light"
                                                    data-bs-toggle="dropdown">

                                                    <i class="ti ti-dots-vertical"></i>

                                                </a>

                                                <div class="dropdown-menu dropdown-menu-end">

                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.ambulances.show', $ambulance->id) }}">

                                                        <i class="ti ti-eye me-2"></i>

                                                        View

                                                    </a>

                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.ambulances.edit', $ambulance->id) }}">

                                                        <i class="ti ti-edit me-2"></i>

                                                        Edit

                                                    </a>

                                                    <form action="{{ route('admin.ambulances.availability', $ambulance->id) }}"
                                                        method="POST">

                                                        @csrf

                                                        <button type="submit" class="dropdown-item">

                                                            @if($ambulance->is_available)

                                                                <i class="ti ti-ambulance me-2"></i>

                                                                Mark Unavailable

                                                            @else

                                                                <i class="ti ti-check me-2"></i>

                                                                Mark Available

                                                            @endif

                                                        </button>

                                                    </form>

                                                    <form action="{{ route('admin.ambulances.status', $ambulance->id) }}"
                                                        method="POST">

                                                        @csrf

                                                        <button type="submit" class="dropdown-item">

                                                            @if($ambulance->status)

                                                                <i class="ti ti-lock me-2"></i>

                                                                Deactivate

                                                            @else

                                                                <i class="ti ti-lock-open me-2"></i>

                                                                Activate

                                                            @endif

                                                        </button>

                                                    </form>

                                                    <a href="javascript:void(0)" class="dropdown-item text-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $ambulance->id }}">

                                                        <i class="ti ti-trash me-2"></i>

                                                        Delete

                                                    </a>

                                                </div>

                                            </div>

                                        </td>

                                    </tr>

                                    <!-- Delete Modal -->

                                    <div class="modal fade" id="deleteModal{{ $ambulance->id }}" tabindex="-1"
                                        aria-hidden="true">

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <h5 class="modal-title">

                                                        Delete Ambulance

                                                    </h5>

                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                                </div>

                                                <div class="modal-body">

                                                    Are you sure you want to delete
                                                    <strong>{{ $ambulance->ambulance_name }}</strong>?

                                                </div>

                                                <div class="modal-footer">

                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                                                        Cancel

                                                    </button>

                                                    <form action="{{ route('admin.ambulances.destroy', $ambulance->id) }}"
                                                        method="POST">

                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn btn-danger">

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

                                            <h6>No Ambulances Found</h6>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script>

        document.addEventListener("DOMContentLoaded", function () {

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

                    language: {

                        search: "",

                        searchPlaceholder: "Search Ambulances..."

                    }

                });

            }

            if (typeof feather !== "undefined") {

                feather.replace();

            }

        });

    </script>

@endsection