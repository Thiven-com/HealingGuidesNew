@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            {{-- Page Header --}}
            <div class="d-flex align-items-center justify-content-between mb-4">

                <div>
                    <h4 class="mb-1">Health Checkup Tests</h4>
                    <p class="text-muted mb-0">
                        Manage tests used in health checkup packages.
                    </p>
                </div>

                <a href="{{ route('admin.health-checkup-tests.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>
                    Add Test
                </a>

            </div>


            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif


            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif


            {{-- Card --}}
            <div class="card">

                <div class="card-header">

                    <div class="row align-items-center">

                        <div class="col-md-6">

                            <h5 class="card-title mb-0">
                                All Tests
                            </h5>

                        </div>

                        <div class="col-md-6">

                            <div class="d-flex justify-content-end">

                                <div class="input-group" style="max-width: 300px;">

                                    <span class="input-group-text">
                                        <i class="ti ti-search"></i>
                                    </span>

                                    <input type="text" id="testSearch" class="form-control" placeholder="Search tests...">

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0" id="testsTable">

                            <thead class="table-light">

                                <tr>

                                    <th width="70">
                                        #
                                    </th>

                                    <th>
                                        Test Name
                                    </th>

                                    <th>
                                        Sample Type
                                    </th>

                                    <th>
                                        Report Time
                                    </th>

                                    <th>
                                        Packages
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

                                @forelse($tests as $test)

                                    <tr>

                                        {{-- ID --}}
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>


                                        {{-- Name --}}
                                        <td>

                                            <div>

                                                <h6 class="mb-1">
                                                    {{ $test->name }}
                                                </h6>

                                                @if($test->short_description)

                                                    <small class="text-muted">
                                                        {{ Str::limit($test->short_description, 70) }}
                                                    </small>

                                                @endif

                                            </div>

                                        </td>


                                        {{-- Sample --}}
                                        <td>

                                            @if($test->sample_type)

                                                <span class="badge bg-light text-dark">
                                                    {{ $test->sample_type }}
                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Report Time --}}
                                        <td>

                                            @if($test->report_time)

                                                {{ $test->report_time }}

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Packages --}}
                                        <td>

                                            <span class="badge bg-info-subtle text-info">

                                                {{ $test->packages_count }}

                                                {{ $test->packages_count == 1 ? 'Package' : 'Packages' }}

                                            </span>

                                        </td>


                                        {{-- Status --}}
                                        <td>

                                            <div class="form-check form-switch">

                                                <input type="checkbox" class="form-check-input test-status"
                                                    data-id="{{ $test->id }}" {{ $test->status ? 'checked' : '' }}>

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

                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.health-checkup-tests.show', $test->id) }}">

                                                            <i class="ti ti-eye me-2"></i>
                                                            View

                                                        </a>

                                                    </li>


                                                    {{-- Edit --}}
                                                    <li>

                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.health-checkup-tests.edit', $test->id) }}">

                                                            <i class="ti ti-edit me-2"></i>
                                                            Edit

                                                        </a>

                                                    </li>


                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>


                                                    {{-- Delete --}}
                                                    <li>

                                                        <form
                                                            action="{{ route('admin.health-checkup-tests.destroy', $test->id) }}"
                                                            method="POST" class="delete-test-form">

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

                                                <i class="ti ti-flask" style="font-size: 50px;"></i>

                                            </div>

                                            <h6>
                                                No health checkup tests found
                                            </h6>

                                            <p class="text-muted mb-3">
                                                Add your first health checkup test.
                                            </p>

                                            <a href="{{ route('admin.health-checkup-tests.create') }}" class="btn btn-primary">

                                                <i class="ti ti-plus me-1"></i>
                                                Add Test

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


    <script>

        document.addEventListener('DOMContentLoaded', function () {

            /*
            |--------------------------------------------------------------------------
            | Search
            |--------------------------------------------------------------------------
            */

            const searchInput =
                document.getElementById('testSearch');

            const table =
                document.getElementById('testsTable');

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
                .querySelectorAll('.test-status')
                .forEach(function (checkbox) {

                    checkbox.addEventListener('change', function () {

                        const id =
                            this.dataset.id;

                        const checked =
                            this.checked;

                        fetch(
                            "{{ url('admin/health-checkup-tests') }}/" +
                            id +
                            "/status",
                            {
                                method: 'POST',

                                headers: {
                                    'X-CSRF-TOKEN':
                                        document
                                            .querySelector(
                                                'meta[name="csrf-token"]'
                                            )
                                            .getAttribute('content'),

                                    'Accept':
                                        'application/json',

                                    'Content-Type':
                                        'application/json'
                                },

                                body: JSON.stringify({
                                    status: checked ? 1 : 0
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
                            .catch(() => {

                                checkbox.checked =
                                    !checked;

                                alert(
                                    'Something went wrong.'
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
                .querySelectorAll('.delete-test-form')
                .forEach(function (form) {

                    form.addEventListener('submit', function (event) {

                        if (!confirm(
                            'Are you sure you want to delete this test?'
                        )) {

                            event.preventDefault();

                        }

                    });

                });

        });

    </script>

@endsection