<?php $page = 'surgeries'; ?>

@extends('layout.mainlayout')

@section('content')

    <style>
        .table-responsive {
            overflow-x: auto !important;
            overflow-y: visible !important;
        }

        .table {
            min-width: 1600px;
        }

        .surgery-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }
    </style>

    <div class="page-wrapper">

        <div class="content">

            {{-- Page Header --}}
            <div class="page-header">

                <div class="add-item d-flex">

                    <div class="page-title">

                        <h4>Surgeries</h4>

                        <h6>Manage Surgeries</h6>

                    </div>

                </div>

                <ul class="table-top-head">

                    <li>

                        <a href="{{ route('admin.surgeries.index') }}" data-bs-toggle="tooltip" title="Refresh">

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

                    <a href="{{ route('admin.surgeries.create') }}" class="btn btn-added">

                        <i data-feather="plus-circle" class="me-2"></i>

                        Add Surgery

                    </a>

                </div>

            </div>


            {{-- Success --}}
            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">

                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>

            @endif


            {{-- Error --}}
            @if(session('error'))

                <div class="alert alert-danger alert-dismissible fade show">

                    {{ session('error') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>

            @endif


            {{-- Validation Errors --}}
            @if($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <div class="card table-list-card">

                <div class="card-body">


                    {{-- Filters --}}
                    <form method="GET" action="{{ route('admin.surgeries.index') }}">

                        <div class="row g-3 align-items-end mb-4">


                            {{-- Search --}}
                            <div class="col-md-5">

                                <label class="form-label">
                                    Search
                                </label>

                                <input type="text" name="search" class="form-control" placeholder="Search surgery name..."
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

                                <a href="{{ route('admin.surgeries.index') }}" class="btn btn-secondary">

                                    <i class="ti ti-refresh me-1"></i>

                                    Reset

                                </a>

                            </div>

                        </div>

                    </form>


                    {{-- Table --}}
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
                                        Surgery
                                    </th>
                                    <th>
                                        Duration
                                    </th>

                                    <th>
                                        Recovery Time
                                    </th>

                                    <th>
                                        Order
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th width="100" class="text-center">

                                        Action

                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($surgeries as $key => $surgery)

                                    <tr>

                                        {{-- ID --}}
                                        <td>

                                            {{ $surgeries->firstItem() + $key }}

                                        </td>


                                        {{-- Image --}}
                                        <td>

                                            @if($surgery->image)

                                                <img src="{{ asset($surgery->image) }}" class="surgery-image">

                                            @else

                                                <img src="{{ asset('assets/img/no-image.png') }}" class="surgery-image">

                                            @endif

                                        </td>


                                        {{-- Name --}}
                                        <td>

                                            <div>

                                                <strong>

                                                    {{ $surgery->name }}

                                                </strong>

                                                <br>

                                                <small class="text-muted">

                                                    {{ $surgery->slug }}

                                                </small>

                                            </div>

                                        </td>
                                        {{-- Duration --}}
                                        <td>

                                            {{ $surgery->duration ?: '-' }}

                                        </td>


                                        {{-- Recovery --}}
                                        <td>

                                            {{ $surgery->recovery_time ?: '-' }}

                                        </td>


                                        {{-- Display Order --}}
                                        <td>

                                            {{ $surgery->display_order }}

                                        </td>


                                        {{-- Status --}}
                                        <td>

                                            @if($surgery->status)

                                                <span class="badge bg-success">

                                                    Active

                                                </span>

                                            @else

                                                <span class="badge bg-danger">

                                                    Inactive

                                                </span>

                                            @endif

                                        </td>


                                        {{-- Action --}}
                                        <td class="text-center">

                                            <div class="dropdown">

                                                <a href="javascript:void(0)" class="btn btn-sm btn-light"
                                                    data-bs-toggle="dropdown">

                                                    <i class="ti ti-dots-vertical"></i>

                                                </a>


                                                <div class="dropdown-menu dropdown-menu-end">


                                                    {{-- View --}}
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.surgeries.show', $surgery->id) }}">

                                                        <i class="ti ti-eye me-2"></i>

                                                        View

                                                    </a>


                                                    {{-- Edit --}}
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.surgeries.edit', $surgery->id) }}">

                                                        <i class="ti ti-edit me-2"></i>

                                                        Edit

                                                    </a>


                                                    {{-- Status --}}
                                                    <form action="{{ route('admin.surgeries.status', $surgery->id) }}"
                                                        method="POST">

                                                        @csrf

                                                        <button type="submit" class="dropdown-item">

                                                            @if($surgery->status)

                                                                <i class="ti ti-lock me-2"></i>

                                                                Deactivate

                                                            @else

                                                                <i class="ti ti-lock-open me-2"></i>

                                                                Activate

                                                            @endif

                                                        </button>

                                                    </form>


                                                    {{-- Delete --}}
                                                    <a href="javascript:void(0)" class="dropdown-item text-danger"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $surgery->id }}">

                                                        <i class="ti ti-trash me-2"></i>

                                                        Delete

                                                    </a>

                                                </div>

                                            </div>

                                        </td>

                                    </tr>


                                    {{-- Delete Modal --}}
                                    <div class="modal fade" id="deleteModal{{ $surgery->id }}" tabindex="-1" aria-hidden="true">

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <h5 class="modal-title">

                                                        Delete Surgery

                                                    </h5>

                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                                </div>


                                                <div class="modal-body">

                                                    Are you sure you want to delete

                                                    <strong>
                                                        {{ $surgery->name }}
                                                    </strong>?

                                                </div>


                                                <div class="modal-footer">

                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                                                        Cancel

                                                    </button>


                                                    <form action="{{ route('admin.surgeries.destroy', $surgery->id) }}"
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

                                            <h6>
                                                No Surgeries Found
                                            </h6>

                                            <p class="text-muted mb-0">

                                                Add your first surgery.

                                            </p>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- Pagination --}}
                    @if($surgeries->hasPages())

                        <div class="mt-4">

                            {{ $surgeries->links() }}

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    <script>

        document.addEventListener(
            "DOMContentLoaded",
            function () {

                if (typeof feather !== "undefined") {

                    feather.replace();

                }

            }
        );

    </script>

@endsection
