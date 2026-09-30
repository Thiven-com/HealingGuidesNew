@extends('admin.layouts.app')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            {{-- Page Header --}}
            <div class="d-flex align-items-center justify-content-between mb-4">

                <div>
                    <h4 class="mb-1">Health Checkup Packages</h4>

                    <p class="text-muted mb-0">
                        Manage health checkup packages and their included tests.
                    </p>
                </div>

                <a href="{{ route('admin.health-checkup-packages.create') }}" class="btn btn-primary">

                    <i class="ti ti-plus me-1"></i>
                    Add Package

                </a>

            </div>


            {{-- Alerts --}}
            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">

                    <i class="ti ti-check me-2"></i>

                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            @if(session('error'))

                <div class="alert alert-danger alert-dismissible fade show">

                    <i class="ti ti-alert-circle me-2"></i>

                    {{ session('error') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            {{-- Main Card --}}
            <div class="card">

                <div class="card-header">

                    <div class="row align-items-center">

                        <div class="col-md-6">

                            <h5 class="card-title mb-0">
                                All Packages
                            </h5>

                        </div>


                        <div class="col-md-6">

                            <div class="d-flex justify-content-end">

                                <div class="input-group" style="max-width: 300px;">

                                    <span class="input-group-text">
                                        <i class="ti ti-search"></i>
                                    </span>

                                    <input type="text" id="packageSearch" class="form-control"
                                        placeholder="Search packages...">

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0" id="packagesTable">

                            <thead class="table-light">

                                <tr>

                                    <th width="70">
                                        #
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

                                    <th class="text-end">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($packages as $package)

                                                            <tr>

                                                                {{-- Serial --}}
                                                                <td>
                                                                    {{ $loop->iteration }}
                                                                </td>


                                                                {{-- Package --}}
                                                                <td>

                                                                    <div class="d-flex align-items-center">

                                                                        @if($package->image)

                                                                            <img src="{{ asset($package->image) }}" alt="{{ $package->name }}"
                                                                                class="rounded" style="
                                                                                            width:55px;
                                                                                            height:55px;
                                                                                            object-fit:cover;
                                                                                        ">

                                                                        @else

                                                                            <div class="rounded bg-primary-subtle d-flex align-items-center justify-content-center"
                                                                                style="
                                                                                            width:55px;
                                                                                            height:55px;
                                                                                        ">

                                                                                <i class="ti ti-package text-primary fs-4"></i>

                                                                            </div>

                                                                        @endif


                                                                        <div class="ms-3">

                                                                            <h6 class="mb-1">
                                                                                {{ $package->name }}
                                                                            </h6>

                                                                            @if($package->short_description)

                                                                                                                    <small class="text-muted">

                                                                                                                        {{ Str::limit(
                                                                                    $package->short_description,
                                                                                    65
                                                                                ) }}

                                                                                                                    </small>

                                                                            @endif

                                                                        </div>

                                                                    </div>

                                                                </td>


                                                                {{-- Health Checkup --}}
                                                                <td>

                                                                    @if($package->healthCheckup)

                                                                        <span class="badge bg-info-subtle text-info">

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

                                                                        @if(
                                                                                                                isset($package->original_price) &&
                                                                                                                $package->original_price > $package->price
                                                                                                            )

                                                                                                            <small class="text-muted text-decoration-line-through">

                                                                                                                ₹{{ number_format(
                                                                                $package->original_price,
                                                                                2
                                                                            ) }}

                                                                                                            </small>

                                                                                                            <br>

                                                                        @endif


                                                                        <strong class="text-dark">

                                                                            ₹{{ number_format(
                                        $package->price,
                                        2
                                    ) }}

                                                                        </strong>


                                                                        @if(
                                                                                isset($package->original_price) &&
                                                                                $package->original_price > $package->price
                                                                            )

                                                                            @php

                                                                                $discount =
                                                                                    (($package->original_price - $package->price)
                                                                                        / $package->original_price)
                                                                                    * 100;

                                                                            @endphp

                                                                            <span class="badge bg-success-subtle text-success ms-1">

                                                                                {{ round($discount) }}% OFF

                                                                            </span>

                                                                        @endif

                                                                    </div>

                                                                </td>


                                                                {{-- Tests --}}
                                                                <td>

                                                                    <span class="badge bg-primary-subtle text-primary">

                                                                        {{ $package->tests_count }}

                                                                        {{ $package->tests_count == 1
                                        ? 'Test'
                                        : 'Tests'
                                                                            }}

                                                                    </span>

                                                                </td>


                                                                {{-- Status --}}
                                                                <td>

                                                                    <div class="form-check form-switch">

                                                                        <input type="checkbox" class="form-check-input package-status"
                                                                            data-id="{{ $package->id }}" {{ $package->status ? 'checked' : '' }}>

                                                                    </div>

                                                                </td>


                                                                {{-- Actions --}}
                                                                <td class="text-end">

                                                                    <div class="dropdown">

                                                                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">

                                                                            <i class="ti ti-dots-vertical"></i>

                                                                        </button>


                                                                        <ul class="dropdown-menu dropdown-menu-end">


                                                                            {{-- View --}}
                                                                            <li>

                                                                                <a class="dropdown-item" href="{{ route(
                                        'admin.health-checkup-packages.show',
                                        $package->id
                                    ) }}">

                                                                                    <i class="ti ti-eye me-2"></i>

                                                                                    View

                                                                                </a>

                                                                            </li>


                                                                            {{-- Edit --}}
                                                                            <li>

                                                                                <a class="dropdown-item" href="{{ route(
                                        'admin.health-checkup-packages.edit',
                                        $package->id
                                    ) }}">

                                                                                    <i class="ti ti-edit me-2"></i>

                                                                                    Edit

                                                                                </a>

                                                                            </li>


                                                                            <li>
                                                                                <hr class="dropdown-divider">
                                                                            </li>


                                                                            {{-- Delete --}}
                                                                            <li>

                                                                                <form action="{{ route(
                                        'admin.health-checkup-packages.destroy',
                                        $package->id
                                    ) }}" method="POST" class="delete-package-form">

                                                                                    @csrf

                                                                                    @method('DELETE')

                                                                                    <button type="submit" class="dropdown-item text-danger">

                                                                                        <i class="ti ti-trash me-2"></i>

                                                                                        Delete

                                                                                    </button>

                                                                                </form>

                                                                            </li>

                                                                        </ul>

                                                                    </div>

                                                                </td>

                                                            </tr>

                                @empty

                                                            <tr>

                                                                <td colspan="7" class="text-center py-5">

                                                                    <div class="mb-3">

                                                                        <i class="ti ti-package-off" style="font-size:50px;">
                                                                        </i>

                                                                    </div>

                                                                    <h6>
                                                                        No health checkup packages found
                                                                    </h6>

                                                                    <p class="text-muted mb-3">
                                                                        Create your first health checkup package.
                                                                    </p>

                                                                    <a href="{{ route(
                                        'admin.health-checkup-packages.create'
                                    ) }}" class="btn btn-primary">

                                                                        <i class="ti ti-plus me-1"></i>

                                                                        Add Package

                                                                    </a>

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

@endsection


@push('scripts')

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            /*
            |--------------------------------------------------------------------------
            | Search
            |--------------------------------------------------------------------------
            */

            const searchInput =
                document.getElementById('packageSearch');

            const table =
                document.getElementById('packagesTable');


            if (searchInput && table) {

                searchInput.addEventListener('keyup', function () {

                    const value =
                        this.value.toLowerCase();

                    const rows =
                        table.querySelectorAll('tbody tr');

                    rows.forEach(function (row) {

                        const text =
                            row.textContent.toLowerCase();

                        row.style.display =
                            text.includes(value)
                                ? ''
                                : 'none';

                    });

                });

            }


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            document
                .querySelectorAll('.package-status')
                .forEach(function (checkbox) {

                    checkbox.addEventListener('change', function () {

                        const id =
                            this.dataset.id;

                        const checked =
                            this.checked;

                        const csrf =
                            document.querySelector(
                                'meta[name="csrf-token"]'
                            );


                        fetch(
                            "{{ url('admin/health-checkup-packages') }}/" +
                            id +
                            "/status",
                            {
                                method: 'POST',

                                headers: {

                                    'X-CSRF-TOKEN':
                                        csrf
                                            ? csrf.getAttribute('content')
                                            : '',

                                    'Accept':
                                        'application/json',

                                    'Content-Type':
                                        'application/json'

                                },

                                body: JSON.stringify({

                                    status:
                                        checked ? 1 : 0

                                })

                            }
                        )
                            .then(response => response.json())
                            .then(data => {

                                if (!data.success) {

                                    checkbox.checked =
                                        !checked;

                                    alert(
                                        data.message ||
                                        'Unable to update status.'
                                    );

                                }

                            })
                            .catch(function () {

                                checkbox.checked =
                                    !checked;

                                alert(
                                    'Something went wrong while updating status.'
                                );

                            });

                    });

                });


            /*
            |--------------------------------------------------------------------------
            | Delete Confirmation
            |--------------------------------------------------------------------------
            */

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