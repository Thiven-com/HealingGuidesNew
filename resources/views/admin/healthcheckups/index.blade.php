<?php $page = 'health-checkups'; ?>

@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            <!-- Page Header -->
            <div class="page-header">

                <div class="add-item d-flex">

                    <div class="page-title">
                        <h4>Health Checkups</h4>
                        <h6>Manage Health Checkups</h6>
                    </div>

                </div>

                <ul class="table-top-head">

                    <li>
                        <a href="{{ route('admin.healthcheckups.index') }}" data-bs-toggle="tooltip" title="Refresh">
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

                    <button type="button" class="btn btn-added" data-bs-toggle="modal"
                        data-bs-target="#addHealthCheckupModal">
                        <i data-feather="plus-circle" class="me-2"></i>
                        Add Health Checkup
                    </button>

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


            {{-- Validation Errors --}}
            @if($errors->any())

                <div class="alert alert-danger alert-dismissible fade show">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>

            @endif


            <!-- Health Checkups Table -->
            <div class="card table-list-card">

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table datanew">

                            <thead>

                                <tr>

                                    <th width="60">#</th>

                                    <th width="100">Image</th>

                                    <th>Name</th>

                                    <th>Description</th>

                                    <th>Created</th>

                                    <th width="90" class="text-center">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($healthCheckups as $key => $healthCheckup)

                                                            <tr>

                                                                {{-- ID --}}
                                                                <td>
                                                                    {{ $healthCheckups->firstItem() + $key }}
                                                                </td>


                                                                {{-- Image --}}
                                                                <td>

                                                                    @if($healthCheckup->image)

                                                                        <img src="{{ asset($healthCheckup->image) }}" class="img-thumbnail"
                                                                            alt="{{ $healthCheckup->name }}" style="
                                                                                                                                width:60px;
                                                                                                                                height:60px;
                                                                                                                                object-fit:cover;
                                                                                                                            ">

                                                                    @else

                                                                        <img src="{{ asset('assets/img/no-image.png') }}" class="img-thumbnail"
                                                                            alt="No Image" style="
                                                                                                                                width:60px;
                                                                                                                                height:60px;
                                                                                                                                object-fit:cover;
                                                                                                                            ">

                                                                    @endif

                                                                </td>


                                                                {{-- Name --}}
                                                                <td>

                                                                    <strong>
                                                                        {{ $healthCheckup->name }}
                                                                    </strong>

                                                                </td>




                                                                {{-- Description --}}
                                                                <td>

                                                                    @if($healthCheckup->description)

                                                                                                    {{ \Illuminate\Support\Str::limit(
                                                                            $healthCheckup->description,
                                                                            100
                                                                        ) }}

                                                                    @else

                                                                        <span class="text-muted">
                                                                            -
                                                                        </span>

                                                                    @endif

                                                                </td>


                                                                {{-- Created --}}
                                                                <td>

                                                                    {{ $healthCheckup->created_at?->format('d M Y') }}

                                                                </td>


                                                                {{-- Action --}}
                                                                <td class="text-center">

                                                                    <div class="d-flex align-items-center justify-content-center gap-2">

                                                                        {{-- Edit --}}
                                                                        <a href="javascript:void(0)" class="btn btn-sm btn-light" data-bs-toggle="modal"
                                                                            data-bs-target="#editHealthCheckupModal{{ $healthCheckup->id }}"
                                                                            title="Edit">

                                                                            <i class="ti ti-edit"></i>

                                                                        </a>

                                                                        {{-- Delete --}}
                                                                        <a href="javascript:void(0)" class="btn btn-sm btn-light text-danger"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#deleteHealthCheckupModal{{ $healthCheckup->id }}"
                                                                            title="Delete">

                                                                            <i class="ti ti-trash"></i>

                                                                        </a>

                                                                    </div>

                                                                </td>

                                                            </tr>


                                                            {{-- ================================================= --}}
                                                            {{-- EDIT MODAL --}}
                                                            {{-- ================================================= --}}

                                                            <div class="modal fade" id="editHealthCheckupModal{{ $healthCheckup->id }}" tabindex="-1"
                                                                aria-hidden="true">

                                                                <div class="modal-dialog modal-lg modal-dialog-centered">

                                                                    <div class="modal-content">

                                                                        <form action="{{ route(
                                        'admin.healthcheckups.update',
                                        $healthCheckup->id
                                    ) }}" method="POST" enctype="multipart/form-data">

                                                                            @csrf
                                                                            @method('PUT')


                                                                            <div class="modal-header">

                                                                                <h5 class="modal-title">
                                                                                    Edit Health Checkup
                                                                                </h5>

                                                                                <button type="button" class="btn-close"
                                                                                    data-bs-dismiss="modal"></button>

                                                                            </div>


                                                                            <div class="modal-body">

                                                                                {{-- Name --}}
                                                                                <div class="mb-3">

                                                                                    <label class="form-label">
                                                                                        Name
                                                                                        <span class="text-danger">*</span>
                                                                                    </label>

                                                                                    <input type="text" name="name" class="form-control"
                                                                                        value="{{ $healthCheckup->name }}" required>

                                                                                </div>


                                                                                {{-- Slug --}}
                                                                                <div class="mb-3">

                                                                                    <label class="form-label">
                                                                                        Slug
                                                                                    </label>

                                                                                    <input type="text" name="slug" class="form-control"
                                                                                        value="{{ $healthCheckup->slug }}"
                                                                                        placeholder="health-checkup-slug">

                                                                                </div>


                                                                                {{-- Image --}}
                                                                                <div class="mb-3">

                                                                                    <label class="form-label">
                                                                                        Image
                                                                                    </label>

                                                                                    @if($healthCheckup->image)

                                                                                                                                    <div class="mb-2">

                                                                                                                                        <img src="{{ asset(
                                                                                            $healthCheckup->image
                                                                                        ) }}"
                                                                                                                                            alt="{{ $healthCheckup->name }}" class="img-thumbnail"
                                                                                                                                            style="
                                                                                                                                                                                                                                            width:120px;
                                                                                                                                                                                                                                            height:90px;
                                                                                                                                                                                                                                            object-fit:cover;
                                                                                                                                                                                                                                        ">

                                                                                                                                    </div>

                                                                                    @endif


                                                                                    <input type="file" name="image" class="form-control"
                                                                                        accept=".jpg,.jpeg,.png,.webp">

                                                                                    <small class="text-muted">
                                                                                        JPG, JPEG, PNG or WEBP. Max 5MB.
                                                                                    </small>

                                                                                </div>


                                                                                {{-- Description --}}
                                                                                <div class="mb-3">

                                                                                    <label class="form-label">
                                                                                        Description
                                                                                    </label>

                                                                                    <textarea name="description" rows="5" class="form-control"
                                                                                        placeholder="Enter description">{{ $healthCheckup->description }}</textarea>

                                                                                </div>

                                                                            </div>


                                                                            <div class="modal-footer">

                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                                    Cancel
                                                                                </button>

                                                                                <button type="submit" class="btn btn-primary">
                                                                                    Update Health Checkup
                                                                                </button>

                                                                            </div>

                                                                        </form>

                                                                    </div>

                                                                </div>

                                                            </div>


                                                            {{-- ================================================= --}}
                                                            {{-- DELETE MODAL --}}
                                                            {{-- ================================================= --}}

                                                            <div class="modal fade" id="deleteHealthCheckupModal{{ $healthCheckup->id }}" tabindex="-1"
                                                                aria-hidden="true">

                                                                <div class="modal-dialog modal-dialog-centered">

                                                                    <div class="modal-content">

                                                                        <div class="modal-header">

                                                                            <h5 class="modal-title">
                                                                                Delete Health Checkup
                                                                            </h5>

                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                                                        </div>


                                                                        <div class="modal-body">

                                                                            <p class="mb-0">

                                                                                Are you sure you want to delete

                                                                                <strong>
                                                                                    {{ $healthCheckup->name }}
                                                                                </strong>?

                                                                            </p>

                                                                        </div>


                                                                        <div class="modal-footer">

                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                                Cancel
                                                                            </button>


                                                                            <form action="{{ route(
                                        'admin.healthcheckups.destroy',
                                        $healthCheckup->id
                                    ) }}" method="POST">

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

                                            <h6>
                                                No Health Checkups Found
                                            </h6>

                                            <p class="text-muted mb-0">
                                                Add your first health checkup.
                                            </p>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- Pagination --}}
                    @if($healthCheckups->hasPages())

                        <div class="mt-3">

                            {{ $healthCheckups->links() }}

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ADD HEALTH CHECKUP MODAL --}}
    {{-- ========================================================= --}}

    <div class="modal fade" id="addHealthCheckupModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">

                <form action="{{ route('admin.healthcheckups.store') }}" method="POST" enctype="multipart/form-data">

                    @csrf


                    <div class="modal-header">

                        <h5 class="modal-title">
                            Add Health Checkup
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                    </div>


                    <div class="modal-body">

                        {{-- Name --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Name
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text" name="name" id="healthCheckupName" class="form-control"
                                value="{{ old('name') }}" placeholder="Enter health checkup name" required>

                        </div>


                        {{-- Slug --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Slug
                            </label>

                            <input type="text" name="slug" id="healthCheckupSlug" class="form-control"
                                value="{{ old('slug') }}" placeholder="health-checkup-slug" readonly>

                            <small class="text-muted">
                                Slug will be generated automatically from the name.
                            </small>

                        </div>


                        {{-- Image --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Image
                            </label>

                            <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">

                            <small class="text-muted">
                                JPG, JPEG, PNG or WEBP. Max 5MB.
                            </small>

                        </div>


                        {{-- Description --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea name="description" rows="5" class="form-control"
                                placeholder="Enter health checkup description">{{ old('description') }}</textarea>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" class="btn btn-primary">
                            Add Health Checkup
                        </button>

                    </div>

                </form>

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
                        searchPlaceholder: "Search Health Checkups..."
                    }

                });

            }

            if (typeof feather !== "undefined") {
                feather.replace();
            }

        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const nameInput = document.getElementById('healthCheckupName');
            const slugInput = document.getElementById('healthCheckupSlug');

            if (nameInput && slugInput) {

                nameInput.addEventListener('input', function () {

                    let slug = this.value
                        .toLowerCase()
                        .trim()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-');

                    slugInput.value = slug;
                });

            }

        });
    </script>

@endsection