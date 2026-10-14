<?php $page = 'lab-tests'; ?>

@extends('layout.mainlayout')

@section('content')

    <style>
        .table-responsive {
            overflow-x: auto !important;
            overflow-y: visible !important;
        }

        .table {
            min-width: 1700px;
        }
    </style>

    <div class="page-wrapper">

        <div class="content">

            <!-- Page Header -->
            <div class="page-header">

                <div class="add-item d-flex">

                    <div class="page-title">

                        <h4>Lab Tests</h4>

                        <h6>Manage Lab Tests</h6>

                    </div>

                </div>

                <ul class="table-top-head">

                    <li>

                        <a href="{{ route('admin.lab-tests.index') }}" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Refresh">

                            <i data-feather="rotate-ccw"></i>

                        </a>

                    </li>

                    <li>

                        <a id="collapse-header" data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse">

                            <i data-feather="chevron-up"></i>

                        </a>

                    </li>

                </ul>

                <div class="page-btn d-flex gap-2">

                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#importLabTestsModal">
                        <i class="ti ti-file-import me-1"></i>
                        Import Excel
                    </button>

                    <a href="{{ route('admin.lab-tests.create') }}" class="btn btn-added">
                        <i data-feather="plus-circle" class="me-2"></i>
                        Add Lab Test
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

                    <form method="GET" action="{{ route('admin.lab-tests.index') }}">

                        <div class="row g-3 align-items-end mb-4">

                            {{-- Search --}}
                            <div class="col-md-4">

                                <label class="form-label">
                                    Search
                                </label>

                                <input type="text" name="search" class="form-control"
                                    placeholder="Test name, code, sample type or description"
                                    value="{{ request('search') }}">

                            </div>

                            {{-- Fasting --}}
                            <div class="col-md-2">

                                <label class="form-label">
                                    Fasting
                                </label>

                                <select name="fasting_required" class="form-select">

                                    <option value="">
                                        All
                                    </option>

                                    <option value="1" {{ request('fasting_required') === '1' ? 'selected' : '' }}>
                                        Required
                                    </option>

                                    <option value="0" {{ request('fasting_required') === '0' ? 'selected' : '' }}>
                                        Not Required
                                    </option>

                                </select>

                            </div>

                            {{-- Home Collection --}}
                            <div class="col-md-2">

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

                                <a href="{{ route('admin.lab-tests.index') }}" class="btn btn-secondary">

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

                                    <th>Test Name</th>

                                    <th>Diagnostic Category</th>

                                    <th>Test Code</th>

                                    <th>Sample Type</th>

                                    <th>Report Time</th>

                                    <th>Fasting</th>

                                    <th>Home Collection</th>

                                    <th width="100">Status</th>

                                    <th width="100" class="text-center">Action</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($labTests as $key => $labTest)

                                    <tr>

                                        <td>{{ $key + 1 }}</td>

                                        <td>

                                            @if($labTest->image)

                                                <img src="{{ asset($labTest->image) }}" class="img-thumbnail"
                                                    style="width:60px;height:60px;object-fit:fill;">

                                            @else

                                                <img src="{{ asset('assets/img/no-image.png') }}" class="img-thumbnail"
                                                    style="width:60px;height:60px;object-fit:cover;">

                                            @endif

                                        </td>

                                        <td>

                                            <div>

                                                <strong>{{ $labTest->test_name }}</strong>

                                                <br>

                                                <small class="text-muted">

                                                    {{ \Illuminate\Support\Str::limit($labTest->description, 50) }}

                                                </small>

                                            </div>

                                        </td>

                                        <td>
                                            @if($labTest->diagnosticCategory)
                                                <span class="badge bg-info text-light">
                                                    {{ $labTest->diagnosticCategory->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>

                                        <td>

                                            <span class="badge bg-light text-dark">

                                                {{ $labTest->test_code }}

                                            </span>

                                        </td>

                                        <td>

                                            {{ $labTest->sample_type }}

                                        </td>

                                        <td>

                                            {{ $labTest->report_time }}
                                            {{ $labTest->report_time_type }}

                                        </td>

                                        <td>

                                            @if($labTest->fasting_required)

                                                <span class="badge bg-success">

                                                    Required

                                                </span>

                                            @else

                                                <span class="badge bg-danger">

                                                    Not Required

                                                </span>

                                            @endif

                                        </td>

                                        <td>

                                            @if($labTest->home_collection)

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

                                            @if($labTest->status)

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
                                                        href="{{ route('admin.lab-tests.show', $labTest->id) }}">

                                                        <i class="ti ti-eye me-2"></i>

                                                        View

                                                    </a>

                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.lab-tests.edit', $labTest->id) }}">

                                                        <i class="ti ti-edit me-2"></i>

                                                        Edit

                                                    </a>

                                                    <form action="{{ route('admin.lab-tests.status', $labTest->id) }}"
                                                        method="POST">

                                                        @csrf

                                                        <button type="submit" class="dropdown-item">

                                                            @if($labTest->status)

                                                                <i class="ti ti-lock me-2"></i>

                                                                Inactive

                                                            @else

                                                                <i class="ti ti-lock-open me-2"></i>

                                                                Active

                                                            @endif

                                                        </button>

                                                    </form>

                                                    <a href="javascript:void(0)" class="dropdown-item text-danger"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $labTest->id }}">

                                                        <i class="ti ti-trash me-2"></i>

                                                        Delete

                                                    </a>

                                                </div>

                                            </div>

                                        </td>

                                    </tr>

                                    <!-- Delete Modal -->

                                    <div class="modal fade" id="deleteModal{{ $labTest->id }}" tabindex="-1">

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <h5 class="modal-title">

                                                        Delete Lab Test

                                                    </h5>

                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                                </div>

                                                <div class="modal-body">

                                                    Are you sure you want to delete

                                                    <strong>

                                                        {{ $labTest->test_name }}

                                                    </strong>?

                                                </div>

                                                <div class="modal-footer">

                                                    <button class="btn btn-secondary" data-bs-dismiss="modal">

                                                        Cancel

                                                    </button>

                                                    <form action="{{ route('admin.lab-tests.destroy', $labTest->id) }}"
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

                                        <td colspan="10" class="text-center py-5">

                                            <h6>No Lab Tests Found</h6>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>
                    <div>
                        {{ $labTests->links('pagination::bootstrap-5') }}
                    </div>

                </div>

            </div>

        </div>

    </div>

    ```blade
    <div class="modal fade" id="importLabTestsModal" tabindex="-1" aria-labelledby="importLabTestsLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form action="{{ route('admin.lab-tests.import') }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title" id="importLabTestsLabel">
                            Import Lab Tests
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <div class="d-flex justify-content-between align-items-center
                                        mb-3 p-3 border rounded">

                            <div>
                                <h6 class="mb-1">Sample Excel Template</h6>
                                <small class="text-muted">
                                    Includes sample data and embedded images.
                                </small>
                            </div>

                            <a href="{{ asset('samples/lab_tests_import_sample.xlsx') }}"
                                class="btn btn-outline-success btn-sm flex-shrink-0" download>
                                <i class="ti ti-download me-1"></i>
                                Download
                            </a>
                        </div>

                        <div class="alert alert-info">
                            <strong>Required Excel headings</strong>

                            <ul class="mb-0 mt-2">
                                <li>test_name</li>
                                <li>test_code</li>
                                <li>slug</li>
                                <li>image</li>
                                <li>description</li>
                                <li>sample_type</li>
                                <li>preparation</li>
                                <li>report_time</li>
                                <li>report_time_type</li>
                                <li>fasting_required</li>
                                <li>home_collection</li>
                                <li>status</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Excel File <span class="text-danger">*</span>
                            </label>

                            <input type="file" name="excel_file" class="form-control" accept=".xlsx" required>

                            <small class="text-muted">
                                XLSX format only. Maximum size: 20 MB.
                            </small>
                        </div>

                        <div class="alert alert-warning mb-0">
                            Insert actual embedded pictures in the image column.
                            Use Hours or Days for report_time_type and yes/no or
                            1/0 for the boolean fields.
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" class="btn btn-success">
                            <i class="ti ti-upload me-1"></i>
                            Import Lab Tests
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
    ```

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
                        searchPlaceholder: "Search Lab Tests..."
                    }

                });

            }

            if (typeof feather !== "undefined") {

                feather.replace();

            }

        });

    </script>

@endsection