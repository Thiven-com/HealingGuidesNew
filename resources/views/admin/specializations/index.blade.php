<?php $page = 'specializations'; ?>

@extends('layout.mainlayout')

@section('content')
    <style>
        .table-responsive {
            overflow: visible !important;
        }

        .dropdown-menu {
            z-index: 99999 !important;
        }
    </style>
    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Specializations</h4>
                        <h6>Manage Medical Specializations</h6>
                    </div>
                </div>

                <ul class="table-top-head">
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"
                            href="{{ route('admin.specializations.index') }}">
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
                    <a href="{{ route('admin.specializations.create') }}" class="btn btn-added">
                        <i data-feather="plus-circle" class="me-2"></i>
                        Add Specialization
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
                    <form method="GET" action="{{ route('admin.specializations.index') }}">
                        <div class="row g-3 align-items-end" style="margin-bottom: 15px;">

                            {{-- Search --}}
                            <div class="col-md-5">
                                <label class="form-label">Search</label>
                                <input type="text" name="search" class="form-control"
                                    placeholder="Search specialization, slug or description"
                                    value="{{ request('search') }}">
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
                            <div class="col-md-4 d-flex gap-2">

                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-search me-1"></i>
                                    Filter
                                </button>

                                <a href="{{ route('admin.specializations.index') }}" class="btn btn-secondary">
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

                                    <th width="80">Image</th>

                                    <th width="70">Icon</th>

                                    <th>Specialization</th>

                                    <th>Slug</th>

                                    <th>Description</th>

                                    <th width="100">Status</th>

                                    <th width="100" class="text-center">
                                        Action
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($specializations as $key => $specialization)
                                    <tr>

                                        <td>{{ $key + 1 }}</td>

                                        <td>

                                            @if($specialization->image)

                                                <img src="{{ asset($specialization->image) }}" class="img-thumbnail"
                                                    style="width:60px;height:60px;object-fit:cover;">

                                            @else

                                                <img src="{{ asset('assets/img/no-image.png') }}" class="img-thumbnail"
                                                    style="width:60px;height:60px;object-fit:cover;">

                                            @endif

                                        </td>

                                        <td>

                                            @if($specialization->icon)

                                                <img src="{{ asset($specialization->icon) }}" class="img-thumbnail"
                                                    style="width:45px;height:45px;object-fit:contain;">

                                            @else

                                                <span class="badge bg-light text-muted">
                                                    N/A
                                                </span>

                                            @endif

                                        </td>

                                        <td>

                                            <strong>
                                                {{ $specialization->specialization_name }}
                                            </strong>

                                        </td>

                                        <td>

                                            <span class="text-muted">
                                                {{ $specialization->slug }}
                                            </span>

                                        </td>

                                        <td>

                                            {{ Str::limit($specialization->description, 60) }}

                                        </td>

                                        <td>

                                            @if($specialization->status)

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
                                                        href="{{ route('admin.specializations.show', $specialization->id) }}">

                                                        <i class="ti ti-eye me-2"></i>

                                                        View

                                                    </a>

                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.specializations.edit', $specialization->id) }}">

                                                        <i class="ti ti-edit me-2"></i>

                                                        Edit

                                                    </a>

                                                    <form
                                                        action="{{ route('admin.specializations.status', $specialization->id) }}"
                                                        method="POST">

                                                        @csrf

                                                        <button type="submit" class="dropdown-item">

                                                            @if($specialization->status)

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
                                                        data-bs-target="#deleteModal{{ $specialization->id }}">

                                                        <i class="ti ti-trash me-2"></i>

                                                        Delete

                                                    </a>

                                                </div>

                                            </div>

                                        </td>

                                    </tr>


                                    <!-- Delete Modal -->

                                    <div class="modal fade" id="deleteModal{{ $specialization->id }}" tabindex="-1">

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <h5 class="modal-title">

                                                        Delete Specialization

                                                    </h5>

                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                                </div>

                                                <div class="modal-body">

                                                    Are you sure you want to delete

                                                    <strong>

                                                        {{ $specialization->specialization_name }}

                                                    </strong> ?

                                                </div>

                                                <div class="modal-footer">

                                                    <button class="btn btn-secondary" data-bs-dismiss="modal">

                                                        Cancel

                                                    </button>

                                                    <form
                                                        action="{{ route('admin.specializations.destroy', $specialization->id) }}"
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

                                        <td colspan="8" class="text-center py-5">

                                            {{-- <img src="{{ asset('assets/img/icons/empty.svg') }}" width="120" class="mb-3">
                                            --}}

                                            <h6>No Specializations Found</h6>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>
                    <div>
                        {{ $specializations->links('pagination::bootstrap-5') }}
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection


@push('scripts')

    <script>

        $(document).ready(function () {

            // DataTable
            if ($('.datanew').length) {

                $('.datanew').DataTable({
                    responsive: true,
                    autoWidth: false,
                    ordering: true,
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                    language: {
                        search: "",
                        searchPlaceholder: "Search..."
                    }
                });

            }

            // Feather Icons
            if (typeof feather !== "undefined") {
                feather.replace();
            }

        });

    </script>

@endpush