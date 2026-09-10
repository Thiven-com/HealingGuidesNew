<?php $page = 'ambulance-types'; ?>

@extends('layout.mainlayout')

@section('content')

    <style>
        .table-responsive {
            overflow-x: auto !important;
            overflow-y: visible !important;
        }

        .table {
            min-width: 1400px;
        }
    </style>

    <div class="page-wrapper">

        <div class="content">

            <!-- Page Header -->

            <div class="page-header">

                <div class="add-item d-flex">

                    <div class="page-title">

                        <h4>Ambulance Types</h4>

                        <h6>Manage Ambulance Types</h6>

                    </div>

                </div>

                <ul class="table-top-head">

                    <li>

                        <a href="{{ route('admin.ambulance-types.index') }}" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Refresh">

                            <i data-feather="rotate-ccw"></i>

                        </a>

                    </li>

                    <li>

                        <a id="collapse-header" data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse">

                            <i data-feather="chevron-up"></i>

                        </a>

                    </li>

                </ul>

                <div class="page-btn">

                    <a href="{{ route('admin.ambulance-types.create') }}" class="btn btn-added">

                        <i data-feather="plus-circle" class="me-2"></i>

                        Add Ambulance Type

                    </a>

                </div>

            </div>

            <!-- /Page Header -->

            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">

                    {{ session('success') }}

                    <button class="btn-close" data-bs-dismiss="alert"></button>

                </div>

            @endif

            @if(session('error'))

                <div class="alert alert-danger alert-dismissible fade show">

                    {{ session('error') }}

                    <button class="btn-close" data-bs-dismiss="alert"></button>

                </div>

            @endif

            <div class="card table-list-card">

                <div class="card-body">
                    {{-- Filters --}}

        <form method="GET"
            action="{{ route('admin.ambulance-types.index') }}">

            <div class="row g-3 align-items-end mb-4">

                {{-- Search --}}
                <div class="col-md-5">
                    <label class="form-label">
                        Search
                    </label>

                    <input type="text"
                        name="search"
                        class="form-control"
                        placeholder="Ambulance type, code or description"
                        value="{{ request('search') }}">
                </div>

                {{-- Status --}}
                <div class="col-md-3">
                    <label class="form-label">
                        Status
                    </label>

                    <select name="status" class="form-select">

                        <option value="">
                            All Status
                        </option>

                        <option value="1"
                            {{ request('status') === '1' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0"
                            {{ request('status') === '0' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>
                </div>

                {{-- Buttons --}}
                <div class="col-md-4 d-flex gap-2">

                    <button type="submit"
                        class="btn btn-primary">

                        <i class="ti ti-search me-1"></i>
                        Filter

                    </button>

                    <a href="{{ route('admin.ambulance-types.index') }}"
                        class="btn btn-secondary">

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

                                    <th>Ambulance Type</th>

                                    <th>Type Code</th>

                                    <th>Description</th>

                                    <th width="100">Status</th>

                                    <th width="100" class="text-center">

                                        Action

                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($ambulanceTypes as $key => $ambulanceType)

                                    <tr>

                                        <td>{{ $key + 1 }}</td>

                                        <td>

                                            @if($ambulanceType->image)

                                                <img src="{{ asset($ambulanceType->image) }}" class="img-thumbnail"
                                                    style="width:60px;height:60px;object-fit:cover;">

                                            @else

                                                <img src="{{ asset('assets/img/no-image.png') }}" class="img-thumbnail"
                                                    style="width:60px;height:60px;object-fit:cover;">

                                            @endif

                                        </td>

                                        <td>

                                            <div>

                                                <strong>

                                                    {{ $ambulanceType->ambulance_type_name }}

                                                </strong>

                                                <br>

                                                <small class="text-muted">

                                                    {{ \Illuminate\Support\Str::limit($ambulanceType->description, 50) }}

                                                </small>

                                            </div>

                                        </td>

                                        <td>

                                            <span class="badge bg-light text-dark">

                                                {{ $ambulanceType->ambulance_type_code }}

                                            </span>

                                        </td>

                                        <td>

                                            {{ \Illuminate\Support\Str::limit($ambulanceType->description, 60) }}

                                        </td>
                                        <td>

                                            @if($ambulanceType->status)

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
                                                        href="{{ route('admin.ambulance-types.show', $ambulanceType->id) }}">

                                                        <i class="ti ti-eye me-2"></i>

                                                        View

                                                    </a>

                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.ambulance-types.edit', $ambulanceType->id) }}">

                                                        <i class="ti ti-edit me-2"></i>

                                                        Edit

                                                    </a>

                                                    <form
                                                        action="{{ route('admin.ambulance-types.status', $ambulanceType->id) }}"
                                                        method="POST">

                                                        @csrf

                                                        <button type="submit" class="dropdown-item">

                                                            @if($ambulanceType->status)

                                                                <i class="ti ti-lock me-2"></i>

                                                                Inactive

                                                            @else

                                                                <i class="ti ti-lock-open me-2"></i>

                                                                Active

                                                            @endif

                                                        </button>

                                                    </form>

                                                    <a href="javascript:void(0)" class="dropdown-item text-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $ambulanceType->id }}">

                                                        <i class="ti ti-trash me-2"></i>

                                                        Delete

                                                    </a>

                                                </div>

                                            </div>

                                        </td>

                                    </tr>

                                    <!-- Delete Modal -->

                                    <div class="modal fade" id="deleteModal{{ $ambulanceType->id }}" tabindex="-1">

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <h5 class="modal-title">

                                                        Delete Ambulance Type

                                                    </h5>

                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                                </div>

                                                <div class="modal-body">

                                                    Are you sure you want to delete

                                                    <strong>

                                                        {{ $ambulanceType->ambulance_type_name }}

                                                    </strong>?

                                                </div>

                                                <div class="modal-footer">

                                                    <button class="btn btn-secondary" data-bs-dismiss="modal">

                                                        Cancel

                                                    </button>

                                                    <form
                                                        action="{{ route('admin.ambulance-types.destroy', $ambulanceType->id) }}"
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

                                        <td colspan="7" class="text-center py-5">

                                            <h6>No Ambulance Types Found</h6>

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

        $(document).ready(function () {

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

                        searchPlaceholder: "Search Ambulance Types..."

                    }

                });

            }

            if (typeof feather !== "undefined") {

                feather.replace();

            }

        });

    </script>

@endsection