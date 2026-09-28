<?php $page = 'packages'; ?>

@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">

    <div class="content">

        {{-- =========================================================
            PAGE HEADER
        ========================================================== --}}

        <div class="page-header">

            <div class="add-item d-flex">

                <div class="page-title">

                    <h4>Packages</h4>

                    <h6>Manage Membership Packages and Benefits</h6>

                </div>

            </div>


            <div class="page-btn">

                <a href="{{ route('admin.packages.create') }}"
                   class="btn btn-primary">

                    <i class="ti ti-plus me-1"></i>

                    Add Package

                </a>

            </div>

        </div>


        {{-- =========================================================
            SUCCESS MESSAGE
        ========================================================== --}}

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- =========================================================
            ERROR MESSAGE
        ========================================================== --}}

        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show">

                {{ session('error') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- =========================================================
            VALIDATION ERRORS
        ========================================================== --}}

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
            FILTERS
        ========================================================== --}}

        <div class="card mb-3">

            <div class="card-header">

                <h5 class="card-title mb-0">

                    <i class="ti ti-filter me-1"></i>

                    Filters

                </h5>

            </div>


            <div class="card-body">

                <form method="GET"
                      action="{{ route('admin.packages.index') }}">

                    <div class="row g-3">


                        {{-- SEARCH --}}

                        <div class="col-md-4">

                            <label class="form-label">
                                Search
                            </label>

                            <input type="text"
                                   name="search"
                                   class="form-control"
                                   placeholder="Package Name / Slug"
                                   value="{{ request('search') }}">

                        </div>


                        {{-- STATUS --}}

                        <div class="col-md-3">

                            <label class="form-label">
                                Status
                            </label>

                            <select name="status"
                                    class="form-select">

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


                        {{-- PRICE --}}

                        <div class="col-md-2">

                            <label class="form-label">
                                Price
                            </label>

                            <select name="price"
                                    class="form-select">

                                <option value="">
                                    All Price
                                </option>

                                <option value="free"
                                    {{ request('price') == 'free' ? 'selected' : '' }}>

                                    Free

                                </option>

                                <option value="paid"
                                    {{ request('price') == 'paid' ? 'selected' : '' }}>

                                    Paid

                                </option>

                            </select>

                        </div>


                        {{-- BUTTONS --}}

                        <div class="col-md-3 d-flex align-items-end">

                            <button type="submit"
                                    class="btn btn-primary me-1">

                                <i class="ti ti-search"></i>

                            </button>


                            <a href="{{ route('admin.packages.index') }}"
                               class="btn btn-light">

                                <i class="ti ti-refresh"></i>

                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- =========================================================
            PACKAGE TABLE
        ========================================================== --}}

        <div class="card">

            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="card-title mb-0">

                        Package List

                    </h5>


                    <span class="badge bg-primary">

                        {{ $packages->total() }}

                        Packages

                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>Package</th>

                                <th>Price</th>

                                <th>Duration</th>

                                <th>Benefits</th>

                                <th>Status</th>

                                <th>Action</th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($packages as $package)

                                <tr>


                                    {{-- =================================================
                                        NUMBER
                                    ================================================== --}}

                                    <td>

                                        {{ $packages->firstItem() + $loop->index }}

                                    </td>


                                    {{-- =================================================
                                        PACKAGE
                                    ================================================== --}}

                                    <td>

                                        <div class="d-flex align-items-center">


                                            @if(!empty($package->image))

                                                <img src="{{ asset('storage/' . $package->image) }}"
                                                     alt="{{ $package->name }}"
                                                     class="rounded"
                                                     width="45"
                                                     height="45"
                                                     style="object-fit:cover;">

                                            @else

                                                <div class="avatar avatar-md bg-light-primary">

                                                    <span class="avatar-title">

                                                        <i class="ti ti-package"></i>

                                                    </span>

                                                </div>

                                            @endif


                                            <div class="ms-2">

                                                <h6 class="mb-1">

                                                    {{ $package->name }}

                                                </h6>

                                                <small class="text-muted">

                                                    {{ $package->slug ?? '-' }}

                                                </small>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- =================================================
                                        PRICE
                                    ================================================== --}}

                                    <td>

                                        <strong>

                                            ₹{{ number_format($package->price ?? 0, 2) }}

                                        </strong>

                                    </td>


                                    {{-- =================================================
                                        DURATION
                                    ================================================== --}}

                                    <td>

                                        {{ $package->duration_days ?? 0 }}

                                        <span class="text-muted">
                                            Days
                                        </span>

                                    </td>


                                    {{-- =================================================
                                        BENEFITS
                                    ================================================== --}}

                                    <td>

                                        <span class="badge bg-info">

                                            {{ $package->benefits_count ?? $package->activeBenefits->count() }}

                                            Benefits

                                        </span>


                                        @if(isset($package->activeBenefits))

                                            <div class="mt-1">

                                                @foreach($package->activeBenefits->take(2) as $benefit)

                                                    <span class="badge bg-light text-dark me-1">

                                                        {{ $benefit->benefit_name }}

                                                    </span>

                                                @endforeach


                                                @if($package->activeBenefits->count() > 2)

                                                    <span class="text-muted small">

                                                        +{{ $package->activeBenefits->count() - 2 }}
                                                        more

                                                    </span>

                                                @endif

                                            </div>

                                        @endif

                                    </td>


                                    {{-- =================================================
                                        STATUS
                                    ================================================== --}}

                                    <td>

                                        @if($package->status)

                                            <span class="badge bg-success">

                                                Active

                                            </span>

                                        @else

                                            <span class="badge bg-danger">

                                                Inactive

                                            </span>

                                        @endif

                                    </td>


                                    {{-- =================================================
                                        ACTION
                                    ================================================== --}}

                                    <td>

                                        <div class="d-flex gap-1">


                                            {{-- VIEW --}}

                                            <a href="{{ route('admin.packages.show', $package->id) }}"
                                               class="btn btn-sm btn-light"
                                               title="View">

                                                <i class="ti ti-eye"></i>

                                            </a>


                                            {{-- EDIT --}}

                                            <a href="{{ route('admin.packages.edit', $package->id) }}"
                                               class="btn btn-sm btn-light"
                                               title="Edit">

                                                <i class="ti ti-edit"></i>

                                            </a>


                                            {{-- STATUS --}}

                                            <form action="{{ route('admin.packages.status', $package->id) }}"
                                                  method="POST"
                                                  class="d-inline">

                                                @csrf

                                                <button type="submit"
                                                        class="btn btn-sm btn-light"
                                                        title="{{ $package->status ? 'Deactivate' : 'Activate' }}">

                                                    @if($package->status)

                                                        <i class="ti ti-toggle-right text-success"></i>

                                                    @else

                                                        <i class="ti ti-toggle-left text-danger"></i>

                                                    @endif

                                                </button>

                                            </form>


                                            {{-- DELETE --}}

                                            <form action="{{ route('admin.packages.destroy', $package->id) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this package?');">

                                                @csrf

                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-light text-danger"
                                                        title="Delete">

                                                    <i class="ti ti-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td colspan="7"
                                        class="text-center py-5">

                                        <div class="text-muted">

                                            <i class="ti ti-package"
                                               style="font-size:40px;">
                                            </i>

                                            <p class="mt-2 mb-0">

                                                No packages found.

                                            </p>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- =========================================================
                PAGINATION
            ========================================================== --}}

            @if($packages->hasPages())

                <div class="card-footer">

                    {{ $packages->links() }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection