<?php $page = 'medicine-categories'; ?>

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

        .category-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>

    <div class="page-wrapper">

        <div class="content">

            <!-- Page Header -->
            <div class="page-header">

                <div class="add-item d-flex">

                    <div class="page-title">

                        <h4>Medicine Categories</h4>

                        <h6>
                            Manage Medicine Categories
                        </h6>

                    </div>

                </div>


                <ul class="table-top-head">

                    <!-- Refresh -->

                    <li>

                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"
                            href="{{ route('admin.medicine-categories.index') }}">

                            <i data-feather="rotate-ccw" class="feather-rotate-ccw"></i>

                        </a>

                    </li>


                    <!-- Collapse -->

                    <li>

                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header">

                            <i data-feather="chevron-up" class="feather-chevron-up"></i>

                        </a>

                    </li>

                </ul>


                <!-- Add Category -->

                <div class="page-btn">

                    <a href="{{ route('admin.medicine-categories.create') }}" class="btn btn-added">

                        <i data-feather="plus-circle" class="me-2"></i>

                        Add Medicine Category

                    </a>

                </div>

            </div>
            <!-- /Page Header -->


            <!-- Success Message -->

            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">

                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>

            @endif


            <!-- Error Message -->

            @if(session('error'))

                <div class="alert alert-danger alert-dismissible fade show">

                    {{ session('error') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>

            @endif


            <!-- Medicine Categories Table -->

            <div class="card table-list-card">

                <div class="card-body">
                    {{-- Filters --}}

                    <form method="GET" action="{{ route('admin.medicine-categories.index') }}">

                        <div class="row g-3 align-items-end mb-4">

                            {{-- Search --}}
                            <div class="col-md-5">

                                <label class="form-label">
                                    Search
                                </label>

                                <input type="text" name="search" class="form-control"
                                    placeholder="Category name or description" value="{{ request('search') }}">

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

                                <a href="{{ route('admin.medicine-categories.index') }}" class="btn btn-secondary">

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

                                    <th width="60">
                                        #
                                    </th>

                                    <th width="90">
                                        Image
                                    </th>

                                    <th>
                                        Category Name
                                    </th>

                                    <th>
                                        Slug
                                    </th>

                                    <th>
                                        Description
                                    </th>

                                    <th width="100">
                                        Sort Order
                                    </th>

                                    <th width="100">
                                        Status
                                    </th>

                                    <th width="100" class="text-center">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($categories as $key => $category)

                                                            <tr>

                                                                <!-- ID -->

                                                                <td>

                                                                    {{ $key + 1 }}

                                                                </td>


                                                                <!-- Image -->

                                                                <td>

                                                                    @if($category->image)

                                                                        <img src="{{ asset($category->image) }}" alt="{{ $category->category_name }}"
                                                                            class="img-thumbnail category-image">

                                                                    @else

                                                                        <img src="{{ asset('assets/img/no-image.png') }}" alt="No Image"
                                                                            class="img-thumbnail category-image">

                                                                    @endif

                                                                </td>


                                                                <!-- Category Name -->

                                                                <td>

                                                                    <strong>

                                                                        {{ $category->category_name }}

                                                                    </strong>

                                                                </td>


                                                                <!-- Slug -->

                                                                <td>

                                                                    <span class="text-muted">

                                                                        {{ $category->slug }}

                                                                    </span>

                                                                </td>


                                                                <!-- Description -->

                                                                <td>

                                                                    @if($category->description)

                                                                                                    {{ Str::limit(
                                                                            $category->description,
                                                                            60
                                                                        ) }}

                                                                    @else

                                                                        <span class="text-muted">
                                                                            -
                                                                        </span>

                                                                    @endif

                                                                </td>


                                                                <!-- Sort Order -->

                                                                <td>

                                                                    <span class="badge bg-light text-dark">

                                                                        {{ $category->sort_order ?? 0 }}

                                                                    </span>

                                                                </td>


                                                                <!-- Status -->

                                                                <td>

                                                                    @if($category->status)

                                                                        <span class="badge bg-success">

                                                                            Active

                                                                        </span>

                                                                    @else

                                                                        <span class="badge bg-danger">

                                                                            Inactive

                                                                        </span>

                                                                    @endif

                                                                </td>


                                                                <!-- Actions -->

                                                                <td class="text-center">

                                                                    <div class="dropdown">

                                                                        <a href="javascript:void(0);" class="btn btn-sm btn-light"
                                                                            data-bs-toggle="dropdown">

                                                                            <i class="ti ti-dots-vertical"></i>

                                                                        </a>


                                                                        <div class="dropdown-menu dropdown-menu-end">

                                                                            <!-- Edit -->

                                                                            <a class="dropdown-item" href="{{ route(
                                        'admin.medicine-categories.edit',
                                        $category->id
                                    ) }}">

                                                                                <i class="ti ti-edit me-2"></i>

                                                                                Edit

                                                                            </a>


                                                                            <!-- Status -->

                                                                            <form action="{{ route(
                                        'admin.medicine-categories.status',
                                        $category->id
                                    ) }}" method="POST">

                                                                                @csrf

                                                                                <button type="submit" class="dropdown-item">

                                                                                    @if($category->status)

                                                                                        <i class="ti ti-lock me-2"></i>

                                                                                        Inactive

                                                                                    @else

                                                                                        <i class="ti ti-lock-open me-2"></i>

                                                                                        Active

                                                                                    @endif

                                                                                </button>

                                                                            </form>


                                                                            <!-- Delete -->

                                                                            <a href="javascript:void(0)" class="dropdown-item text-danger"
                                                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $category->id }}">

                                                                                <i class="ti ti-trash me-2"></i>

                                                                                Delete

                                                                            </a>

                                                                        </div>

                                                                    </div>

                                                                </td>

                                                            </tr>


                                                            <!-- Delete Modal -->

                                                            <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1">

                                                                <div class="modal-dialog modal-dialog-centered">

                                                                    <div class="modal-content">

                                                                        <div class="modal-header">

                                                                            <h5 class="modal-title">

                                                                                Delete Medicine Category

                                                                            </h5>

                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                                                        </div>


                                                                        <div class="modal-body">

                                                                            Are you sure you want to delete

                                                                            <strong>

                                                                                {{ $category->category_name }}

                                                                            </strong>?

                                                                            <div class="alert alert-warning mt-3 mb-0">

                                                                                <i class="ti ti-alert-triangle me-1"></i>

                                                                                This category cannot be deleted
                                                                                if medicines are assigned to it.

                                                                            </div>

                                                                        </div>


                                                                        <div class="modal-footer">

                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                                                                                Cancel

                                                                            </button>


                                                                            <form action="{{ route(
                                        'admin.medicine-categories.destroy',
                                        $category->id
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

                                                                <td colspan="8" class="text-center py-5">

                                                                    <div class="mb-3">

                                                                        <i class="ti ti-pill-off" style="
                                                                                        font-size: 50px;
                                                                                        color: #adb5bd;
                                                                                    "></i>

                                                                    </div>

                                                                    <h6>

                                                                        No Medicine Categories Found

                                                                    </h6>

                                                                    <p class="text-muted">

                                                                        Add your first medicine category.

                                                                    </p>

                                                                    <a href="{{ route(
                                        'admin.medicine-categories.create'
                                    ) }}" class="btn btn-added btn-sm">

                                                                        <i data-feather="plus-circle" class="me-1"></i>

                                                                        Add Category

                                                                    </a>

                                                                </td>

                                                            </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>
            <!-- /Medicine Categories Table -->

        </div>

    </div>


    <script>

        $(document).ready(function () {

            /*
            |--------------------------------------------------------------------------
            | DataTable
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
                            targets: [1, 7],
                            orderable: false
                        }

                    ],

                    language: {

                        search: "",

                        searchPlaceholder:
                            "Search Medicine Categories..."

                    }

                });

            }


            /*
            |--------------------------------------------------------------------------
            | Feather Icons
            |--------------------------------------------------------------------------
            */

            if (typeof feather !== "undefined") {

                feather.replace();

            }

        });

    </script>

@endsection