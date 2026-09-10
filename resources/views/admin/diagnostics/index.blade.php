<?php $page = 'diagnostics'; ?>

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
    </style>

    <div class="page-wrapper">

        <div class="content">

            <!-- Page Header -->
            <div class="page-header">

                <div class="add-item d-flex">

                    <div class="page-title">

                        <h4>Diagnostics</h4>

                        <h6>Manage Diagnostics</h6>

                    </div>

                </div>

                <ul class="table-top-head">

                    <li>

                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"
                            href="{{ route('admin.diagnostics.index') }}">

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

                    <a href="{{ route('admin.diagnostics.create') }}" class="btn btn-added">

                        <i data-feather="plus-circle" class="me-2"></i>

                        Add Diagnostic

                    </a>

                </div>

            </div>
            <!-- /Page Header -->


            {{-- Success Message --}}
            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">

                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>

            @endif


            {{-- Error Message --}}
            @if(session('error'))

                <div class="alert alert-danger alert-dismissible fade show">

                    {{ session('error') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>

            @endif


            <div class="card table-list-card">

                <div class="card-body">
                    {{-- Filters --}}

                    <form method="GET" action="{{ route('admin.diagnostics.index') }}">

                        <div class="row g-3 align-items-end mb-4">

                            {{-- Search --}}
                            <div class="col-md-5">

                                <label class="form-label">
                                    Search
                                </label>

                                <input type="text" name="search" class="form-control"
                                    placeholder="Name, code, registration no, mobile, email or city"
                                    value="{{ request('search') }}">

                            </div>

                            {{-- Home Collection --}}
                            <div class="col-md-3">

                                <label class="form-label">
                                    Home Collection
                                </label>

                                <select name="home_collection" class="form-select">

                                    <option value="">
                                        All
                                    </option>

                                    <option value="1" {{ request('home_collection') === '1' ? 'selected' : '' }}>
                                        Yes
                                    </option>

                                    <option value="0" {{ request('home_collection') === '0' ? 'selected' : '' }}>
                                        No
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
                            <div class="col-md-2 d-flex gap-2">

                                <button type="submit" class="btn btn-primary">

                                    <i class="ti ti-search me-1"></i>
                                    Filter

                                </button>

                                <a href="{{ route('admin.diagnostics.index') }}" class="btn btn-secondary">

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

                                    <th width="80">Logo</th>

                                    <th width="100">Banner</th>

                                    <th>Diagnostic Name</th>

                                    <th>Code</th>

                                    <th>Registration No</th>

                                    <th>Mobile</th>

                                    <th>Email</th>

                                    <th>City</th>

                                    <th>Home Collection</th>

                                    <th width="100">Status</th>

                                    <th width="100" class="text-center">
                                        Action
                                    </th>

                                </tr>

                            </thead>

                            <tbody>
                                @forelse($diagnostics as $key => $diagnostic)

                                    <tr>

                                        <td>

                                            {{ $key + 1 }}

                                        </td>

                                        <td>

                                            @if($diagnostic->logo)

                                                <img src="{{ asset($diagnostic->logo) }}" class="img-thumbnail"
                                                    style="width:60px;height:60px;object-fit:cover;">

                                            @else

                                                <img src="{{ asset('assets/img/no-image.png') }}" class="img-thumbnail"
                                                    style="width:60px;height:60px;object-fit:cover;">

                                            @endif

                                        </td>

                                        <td>

                                            @if($diagnostic->banner)

                                                <img src="{{ asset($diagnostic->banner) }}" class="img-thumbnail"
                                                    style="width:80px;height:50px;object-fit:cover;">

                                            @else

                                                <img src="{{ asset('assets/img/no-image.png') }}" class="img-thumbnail"
                                                    style="width:80px;height:50px;object-fit:cover;">

                                            @endif

                                        </td>

                                        <td>

                                            <div>

                                                <strong>

                                                    {{ $diagnostic->diagnostic_name }}

                                                </strong>

                                                <br>

                                                <small class="text-muted">

                                                    {{ $diagnostic->address }}

                                                </small>

                                            </div>

                                        </td>

                                        <td>

                                            <span class="badge bg-light text-dark">

                                                {{ $diagnostic->diagnostic_code }}

                                            </span>

                                        </td>

                                        <td>

                                            {{ $diagnostic->registration_number ?: '-' }}

                                        </td>

                                        <td>

                                            {{ $diagnostic->mobile }}

                                        </td>

                                        <td>

                                            {{ $diagnostic->email ?: '-' }}

                                        </td>

                                        <td>

                                            {{ $diagnostic->city }}

                                        </td>

                                        <td>

                                            @if($diagnostic->home_collection)

                                                <span class="badge bg-success">

                                                    Yes

                                                </span>

                                            @else

                                                <span class="badge bg-danger">

                                                    No

                                                </span>

                                            @endif

                                        </td>

                                        <td>

                                            @if($diagnostic->status)

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

                                                <a href="javascript:void(0);" class="btn btn-sm btn-light"
                                                    data-bs-toggle="dropdown">

                                                    <i class="ti ti-dots-vertical"></i>

                                                </a>

                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.diagnostics.show', $diagnostic->id) }}">

                                                        <i class="ti ti-eye me-2"></i>

                                                        View

                                                    </a>

                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.diagnostics.edit', $diagnostic->id) }}">

                                                        <i class="ti ti-edit me-2"></i>

                                                        Edit

                                                    </a>

                                                    <form action="{{ route('admin.diagnostics.status', $diagnostic->id) }}"
                                                        method="POST">

                                                        @csrf

                                                        <button type="submit" class="dropdown-item">

                                                            @if($diagnostic->status)

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
                                                        data-bs-target="#deleteModal{{ $diagnostic->id }}">

                                                        <i class="ti ti-trash me-2"></i>

                                                        Delete

                                                    </a>

                                                </div>

                                            </div>

                                        </td>

                                    </tr>


                                    <!-- Delete Modal -->

                                    <div class="modal fade" id="deleteModal{{ $diagnostic->id }}" tabindex="-1">

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <h5 class="modal-title">

                                                        Delete Diagnostic

                                                    </h5>

                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                                </div>

                                                <div class="modal-body">

                                                    Are you sure you want to delete

                                                    <strong>

                                                        {{ $diagnostic->diagnostic_name }}

                                                    </strong> ?

                                                </div>

                                                <div class="modal-footer">

                                                    <button class="btn btn-secondary" data-bs-dismiss="modal">

                                                        Cancel

                                                    </button>

                                                    <form action="{{ route('admin.diagnostics.destroy', $diagnostic->id) }}"
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

                                            <h6>No Diagnostics Found</h6>

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

            // DataTable
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

                        searchPlaceholder: "Search Diagnostics..."

                    }

                });

            }

            // Feather Icons
            if (typeof feather !== "undefined") {

                feather.replace();

            }

        });

    </script>

@endsection