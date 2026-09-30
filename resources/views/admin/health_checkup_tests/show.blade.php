@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- Page Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">

            <div>
                <h4 class="mb-1">Health Checkup Test</h4>

                <p class="text-muted mb-0">
                    View test details and package usage.
                </p>
            </div>

            <div class="d-flex gap-2">

                <a
                    href="{{ route('admin.health-checkup-tests.edit', $healthCheckupTest->id) }}"
                    class="btn btn-primary">

                    <i class="ti ti-edit me-1"></i>
                    Edit

                </a>

                <a
                    href="{{ route('admin.health-checkup-tests.index') }}"
                    class="btn btn-light">

                    <i class="ti ti-arrow-left me-1"></i>
                    Back

                </a>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Test Overview --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="card-title mb-0">
                    <i class="ti ti-flask me-2"></i>
                    Test Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-4">


                    {{-- Test Name --}}
                    <div class="col-md-8">

                        <label class="text-muted d-block mb-1">
                            Test Name
                        </label>

                        <h5 class="mb-0">
                            {{ $healthCheckupTest->name }}
                        </h5>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-4">

                        <label class="text-muted d-block mb-1">
                            Status
                        </label>

                        @if($healthCheckupTest->status)

                            <span class="badge bg-success-subtle text-success">
                                <i class="ti ti-check me-1"></i>
                                Active
                            </span>

                        @else

                            <span class="badge bg-danger-subtle text-danger">
                                <i class="ti ti-x me-1"></i>
                                Inactive
                            </span>

                        @endif

                    </div>


                    {{-- Slug --}}
                    <div class="col-md-6">

                        <label class="text-muted d-block mb-1">
                            Slug
                        </label>

                        <div class="text-dark">
                            {{ $healthCheckupTest->slug ?: '—' }}
                        </div>

                    </div>


                    {{-- Display Order --}}
                    <div class="col-md-6">

                        <label class="text-muted d-block mb-1">
                            Display Order
                        </label>

                        <div class="text-dark">
                            {{ $healthCheckupTest->display_order ?? 0 }}
                        </div>

                    </div>


                    {{-- Short Description --}}
                    <div class="col-md-12">

                        <label class="text-muted d-block mb-1">
                            Short Description
                        </label>

                        <div class="text-dark">

                            @if($healthCheckupTest->short_description)

                                {{ $healthCheckupTest->short_description }}

                            @else

                                <span class="text-muted">
                                    No short description added.
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Description --}}
                    <div class="col-md-12">

                        <label class="text-muted d-block mb-2">
                            Description
                        </label>

                        @if($healthCheckupTest->description)

                            <div class="border rounded p-3 bg-light-subtle">

                                {!! nl2br(e($healthCheckupTest->description)) !!}

                            </div>

                        @else

                            <div class="text-muted">
                                No description added.
                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Sample & Report --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="card-title mb-0">
                    <i class="ti ti-test-pipe me-2"></i>
                    Sample & Report Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-4">


                    {{-- Sample Type --}}
                    <div class="col-md-6">

                        <label class="text-muted d-block mb-1">
                            Sample Type
                        </label>

                        @if($healthCheckupTest->sample_type)

                            <span class="badge bg-light text-dark border">

                                <i class="ti ti-droplet me-1"></i>

                                {{ $healthCheckupTest->sample_type }}

                            </span>

                        @else

                            <span class="text-muted">
                                Not specified
                            </span>

                        @endif

                    </div>


                    {{-- Report Time --}}
                    <div class="col-md-6">

                        <label class="text-muted d-block mb-1">
                            Report Time
                        </label>

                        @if($healthCheckupTest->report_time)

                            <span class="badge bg-light text-dark border">

                                <i class="ti ti-clock me-1"></i>

                                {{ $healthCheckupTest->report_time }}

                            </span>

                        @else

                            <span class="text-muted">
                                Not specified
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Package Usage --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <h5 class="card-title mb-1">

                            <i class="ti ti-package me-2"></i>

                            Used In Packages

                        </h5>

                        <p class="text-muted mb-0">

                            Health checkup packages containing this test.

                        </p>

                    </div>


                    <span class="badge bg-primary-subtle text-primary">

                        {{ $healthCheckupTest->packages->count() }}

                        {{ $healthCheckupTest->packages->count() == 1 ? 'Package' : 'Packages' }}

                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                @if($healthCheckupTest->packages->count())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

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
                                        Package Price
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($healthCheckupTest->packages as $package)

                                    <tr>

                                        <td>
                                            {{ $loop->iteration }}
                                        </td>


                                        <td>

                                            <div>

                                                <h6 class="mb-1">

                                                    {{ $package->name }}

                                                </h6>

                                                @if($package->slug)

                                                    <small class="text-muted">

                                                        {{ $package->slug }}

                                                    </small>

                                                @endif

                                            </div>

                                        </td>


                                        <td>

                                            @if($package->healthCheckup)

                                                {{ $package->healthCheckup->name }}

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        <td>

                                            @if(isset($package->price))

                                                ₹{{ number_format($package->price, 2) }}

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        <td>

                                            @if($package->status)

                                                <span class="badge bg-success-subtle text-success">
                                                    Active
                                                </span>

                                            @else

                                                <span class="badge bg-danger-subtle text-danger">
                                                    Inactive
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="text-center py-5">

                        <div class="mb-3">

                            <i
                                class="ti ti-package-off"
                                style="font-size: 50px;">
                            </i>

                        </div>

                        <h6>
                            This test is not assigned to any package
                        </h6>

                        <p class="text-muted mb-0">
                            You can select this test while creating or editing a health checkup package.
                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Metadata --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="card-title mb-0">
                    <i class="ti ti-info-circle me-2"></i>
                    Record Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-6">

                        <label class="text-muted d-block mb-1">
                            Created At
                        </label>

                        <div>

                            {{ $healthCheckupTest->created_at
                                ? $healthCheckupTest->created_at->format('d M Y, h:i A')
                                : '—'
                            }}

                        </div>

                    </div>


                    <div class="col-md-6">

                        <label class="text-muted d-block mb-1">
                            Last Updated
                        </label>

                        <div>

                            {{ $healthCheckupTest->updated_at
                                ? $healthCheckupTest->updated_at->format('d M Y, h:i A')
                                : '—'
                            }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Actions --}}
        {{-- ========================================================= --}}

        <div class="d-flex justify-content-end gap-2 mb-4">

            <a
                href="{{ route('admin.health-checkup-tests.edit', $healthCheckupTest->id) }}"
                class="btn btn-primary">

                <i class="ti ti-edit me-1"></i>

                Edit Test

            </a>


            <form
                action="{{ route('admin.health-checkup-tests.destroy', $healthCheckupTest->id) }}"
                method="POST"
                id="deleteTestForm">

                @csrf

                @method('DELETE')

                <button
                    type="submit"
                    class="btn btn-outline-danger">

                    <i class="ti ti-trash me-1"></i>

                    Delete

                </button>

            </form>

        </div>

    </div>
</div>

@endsection


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const deleteForm =
        document.getElementById('deleteTestForm');

    if (deleteForm) {

        deleteForm.addEventListener('submit', function (event) {

            const confirmed = confirm(
                'Are you sure you want to delete this health checkup test?'
            );

            if (!confirmed) {

                event.preventDefault();

            }

        });

    }

});

</script>

@endpush