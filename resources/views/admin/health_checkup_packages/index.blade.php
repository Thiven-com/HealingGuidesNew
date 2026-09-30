@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- ========================================================= --}}
        {{-- Page Header --}}
        {{-- ========================================================= --}}

        <div class="d-flex align-items-center justify-content-between mb-4">

            <div>
                <h4 class="mb-1">
                    Health Checkup Packages
                </h4>

                <p class="text-muted mb-0">
                    Manage health checkup packages and their included tests.
                </p>
            </div>

            <a
                href="{{ route('admin.health-checkup-packages.create') }}"
                class="btn btn-primary">

                <i class="ti ti-plus me-1"></i>

                Add Package

            </a>

        </div>


        {{-- ========================================================= --}}
        {{-- Success / Error Messages --}}
        {{-- ========================================================= --}}

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                <i class="ti ti-check me-1"></i>

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show">

                <i class="ti ti-alert-circle me-1"></i>

                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- Statistics --}}
        {{-- ========================================================= --}}

        <div class="row mb-4">

            <div class="col-md-3">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div
                                class="avatar avatar-lg bg-primary-subtle rounded">

                                <i class="ti ti-package text-primary fs-3"></i>

                            </div>

                            <div class="ms-3">

                                <span class="text-muted">
                                    Total Packages
                                </span>

                                <h4 class="mb-0">
                                    {{ $packages->total() }}
                                </h4>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div
                                class="avatar avatar-lg bg-success-subtle rounded">

                                <i class="ti ti-circle-check text-success fs-3"></i>

                            </div>

                            <div class="ms-3">

                                <span class="text-muted">
                                    Active
                                </span>

                                <h4 class="mb-0">

                                    {{ \App\Models\HealthCheckupPackage::where(
                                        'status',
                                        1
                                    )->count() }}

                                </h4>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div
                                class="avatar avatar-lg bg-warning-subtle rounded">

                                <i class="ti ti-discount-2 text-warning fs-3"></i>

                            </div>

                            <div class="ms-3">

                                <span class="text-muted">
                                    Offers
                                </span>

                                <h4 class="mb-0">

                                    {{ \App\Models\HealthCheckupPackage::whereColumn(
                                        'price',
                                        '<',
                                        'mrp'
                                    )->count() }}

                                </h4>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div
                                class="avatar avatar-lg bg-info-subtle rounded">

                                <i class="ti ti-test-pipe text-info fs-3"></i>

                            </div>

                            <div class="ms-3">

                                <span class="text-muted">
                                    Health Checkups
                                </span>

                                <h4 class="mb-0">

                                    {{ \App\Models\HealthCheckup::count() }}

                                </h4>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Packages Table --}}
        {{-- ========================================================= --}}

        <div class="card">

            <div class="card-header">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <h5 class="card-title mb-1">
                            Package List
                        </h5>

                        <p class="text-muted mb-0">
                            All available health checkup packages.
                        </p>

                    </div>

                </div>

            </div>


            <div class="card-body">

                {{-- Search --}}
                <form
                    method="GET"
                    action="{{ route(
                        'admin.health-checkup-packages.index'
                    ) }}"
                    class="mb-4">

                    <div class="row g-2">

                        <div class="col-md-6">

                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="ti ti-search"></i>

                                </span>

                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    class="form-control"
                                    placeholder="Search package...">

                            </div>

                        </div>


                        <div class="col-md-3">

                            <select
                                name="health_checkup_id"
                                class="form-select">

                                <option value="">
                                    All Health Checkups
                                </option>

                                @foreach($healthCheckups as $healthCheckup)

                                    <option
                                        value="{{ $healthCheckup->id }}"
                                        {{ request('health_checkup_id') == $healthCheckup->id
                                            ? 'selected'
                                            : ''
                                        }}>

                                        {{ $healthCheckup->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-2">

                            <select
                                name="status"
                                class="form-select">

                                <option value="">
                                    All Status
                                </option>

                                <option
                                    value="1"
                                    {{ request('status') === '1'
                                        ? 'selected'
                                        : ''
                                    }}>

                                    Active

                                </option>

                                <option
                                    value="0"
                                    {{ request('status') === '0'
                                        ? 'selected'
                                        : ''
                                    }}>

                                    Inactive

                                </option>

                            </select>

                        </div>


                        <div class="col-md-1">

                            <button
                                type="submit"
                                class="btn btn-primary w-100">

                                <i class="ti ti-search"></i>

                            </button>

                        </div>

                    </div>

                </form>


                {{-- Table --}}
                @if($packages->count())

                    <div class="table-responsive">

                        <table
                            class="table table-hover align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th width="60">
                                        #
                                    </th>

                                    <th width="80">
                                        Image
                                    </th>

                                    <th>
                                        Package
                                    </th>

                                    <th>
                                        Health Checkup
                                    </th>

                                    <th>
                                        Price
                                    </th>

                                    <th>
                                        Tests
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th width="150">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($packages as $package)

                                    <tr>

                                        {{-- Number --}}
                                        <td>

                                            {{ $packages->firstItem() + $loop->index }}

                                        </td>


                                        {{-- Image --}}
                                        <td>

                                            @if($package->image)

                                                <img
                                                    src="{{ asset(
                                                        $package->image
                                                    ) }}"
                                                    alt="{{ $package->name }}"
                                                    class="rounded"
                                                    style="
                                                        width:55px;
                                                        height:55px;
                                                        object-fit:cover;
                                                    ">

                                            @else

                                                <div
                                                    class="rounded bg-primary-subtle d-flex align-items-center justify-content-center"
                                                    style="
                                                        width:55px;
                                                        height:55px;
                                                    ">

                                                    <i class="ti ti-package text-primary fs-4"></i>

                                                </div>

                                            @endif

                                        </td>


                                        {{-- Package --}}
                                        <td>

                                            <div>

                                                <h6 class="mb-1">

                                                    {{ $package->name }}

                                                </h6>


                                                @if($package->short_description)

                                                    <small
                                                        class="text-muted">

                                                        {{ \Illuminate\Support\Str::limit(
                                                            $package->short_description,
                                                            55
                                                        ) }}

                                                    </small>

                                                @endif

                                            </div>

                                        </td>


                                        {{-- Health Checkup --}}
                                        <td>

                                            @if($package->healthCheckup)

                                                <span
                                                    class="badge bg-info-subtle text-info">

                                                    <i class="ti ti-stethoscope me-1"></i>

                                                    {{ $package->healthCheckup->name }}

                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Price --}}
                                        <td>

                                            <div>

                                                <strong>

                                                    ₹{{ number_format(
                                                        $package->price,
                                                        2
                                                    ) }}

                                                </strong>


                                                @if(
                                                    $package->mrp >
                                                    $package->price
                                                )

                                                    <div>

                                                        <small
                                                            class="text-muted text-decoration-line-through">

                                                            ₹{{ number_format(
                                                                $package->mrp,
                                                                2
                                                            ) }}

                                                        </small>

                                                    </div>

                                                    @php

                                                        $discount =
                                                            (
                                                                (
                                                                    $package->mrp -
                                                                    $package->price
                                                                )
                                                                /
                                                                $package->mrp
                                                            ) * 100;

                                                    @endphp

                                                    <span
                                                        class="badge bg-success-subtle text-success">

                                                        {{ round($discount) }}% OFF

                                                    </span>

                                                @endif

                                            </div>

                                        </td>


                                        {{-- Tests --}}
                                        <td>

                                            <span
                                                class="badge bg-primary-subtle text-primary">

                                                <i class="ti ti-test-pipe me-1"></i>

                                                {{ $package->tests_count ?? $package->tests->count() }}

                                            </span>

                                        </td>


                                        {{-- Status --}}
                                        <td>

                                            @if($package->status)

                                                <span
                                                    class="badge bg-success-subtle text-success">

                                                    <i class="ti ti-circle-check me-1"></i>

                                                    Active

                                                </span>

                                            @else

                                                <span
                                                    class="badge bg-danger-subtle text-danger">

                                                    <i class="ti ti-circle-x me-1"></i>

                                                    Inactive

                                                </span>

                                            @endif

                                        </td>


                                        {{-- Actions --}}
                                        <td>

                                            <div class="d-flex gap-1">

                                                {{-- View --}}
                                                <a
                                                    href="{{ route(
                                                        'admin.health-checkup-packages.show',
                                                        $package->id
                                                    ) }}"
                                                    class="btn btn-sm btn-icon btn-light"
                                                    title="View">

                                                    <i class="ti ti-eye"></i>

                                                </a>


                                                {{-- Edit --}}
                                                <a
                                                    href="{{ route(
                                                        'admin.health-checkup-packages.edit',
                                                        $package->id
                                                    ) }}"
                                                    class="btn btn-sm btn-icon btn-light"
                                                    title="Edit">

                                                    <i class="ti ti-edit"></i>

                                                </a>


                                                {{-- Delete --}}
                                                <form
                                                    action="{{ route(
                                                        'admin.health-checkup-packages.destroy',
                                                        $package->id
                                                    ) }}"
                                                    method="POST"
                                                    class="delete-package-form">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-icon btn-light text-danger"
                                                        title="Delete">

                                                        <i class="ti ti-trash"></i>

                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- Pagination --}}
                    <div class="mt-4">

                        {{ $packages->withQueryString()->links() }}

                    </div>

                @else

                    <div class="text-center py-5">

                        <div class="mb-3">

                            <i
                                class="ti ti-package-off"
                                style="font-size:60px;">
                            </i>

                        </div>

                        <h5>
                            No Health Checkup Packages Found
                        </h5>

                        <p class="text-muted mb-3">

                            Create your first health checkup package
                            and assign tests to it.

                        </p>

                        <a
                            href="{{ route(
                                'admin.health-checkup-packages.create'
                            ) }}"
                            class="btn btn-primary">

                            <i class="ti ti-plus me-1"></i>

                            Add Package

                        </a>

                    </div>

                @endif

            </div>

        </div>

    </div>
</div>

@endsection


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    document
        .querySelectorAll('.delete-package-form')
        .forEach(function (form) {

            form.addEventListener('submit', function (event) {

                if (!confirm(
                    'Are you sure you want to delete this health checkup package?'
                )) {

                    event.preventDefault();

                }

            });

        });

});

</script>

@endpush