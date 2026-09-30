@extends('layout.mainlayout')


@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- Page Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">

            <div>
                <h4 class="mb-1">Edit Health Checkup Test</h4>

                <p class="text-muted mb-0">
                    Update the health checkup test details.
                </p>
            </div>

            <a href="{{ route('admin.health-checkup-tests.index') }}"
               class="btn btn-light">

                <i class="ti ti-arrow-left me-1"></i>
                Back

            </a>

        </div>


        {{-- Validation Errors --}}
        @if ($errors->any())

            <div class="alert alert-danger alert-dismissible fade show">

                <div class="d-flex align-items-start">

                    <i class="ti ti-alert-circle me-2 fs-5"></i>

                    <div>

                        <strong>
                            Please fix the following errors:
                        </strong>

                        <ul class="mb-0 mt-2">

                            @foreach ($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        <form
            action="{{ route('admin.health-checkup-tests.update', $healthCheckupTest->id) }}"
            method="POST">

            @csrf

            @method('PUT')


            {{-- ========================================================= --}}
            {{-- Test Information --}}
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

                            <label class="form-label">

                                Test Name

                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="text"
                                name="name"
                                id="testName"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $healthCheckupTest->name) }}"
                                placeholder="Enter test name"
                                required>

                            @error('name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Display Order --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Display Order
                            </label>

                            <input
                                type="number"
                                name="display_order"
                                min="0"
                                class="form-control @error('display_order') is-invalid @enderror"
                                value="{{ old('display_order', $healthCheckupTest->display_order ?? 0) }}"
                                placeholder="0">

                            @error('display_order')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Slug --}}
                        <div class="col-md-12">

                            <label class="form-label">
                                Slug
                            </label>

                            <input
                                type="text"
                                name="slug"
                                id="testSlug"
                                class="form-control @error('slug') is-invalid @enderror"
                                value="{{ old('slug', $healthCheckupTest->slug) }}"
                                placeholder="test-slug">

                            <small class="text-muted">
                                Use a unique URL-friendly slug.
                            </small>

                            @error('slug')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Short Description --}}
                        <div class="col-md-12">

                            <label class="form-label">
                                Short Description
                            </label>

                            <input
                                type="text"
                                name="short_description"
                                class="form-control @error('short_description') is-invalid @enderror"
                                value="{{ old('short_description', $healthCheckupTest->short_description) }}"
                                placeholder="Enter a short description">

                            @error('short_description')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Description --}}
                        <div class="col-md-12">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea
                                name="description"
                                rows="6"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Enter test description">{{ old('description', $healthCheckupTest->description) }}</textarea>

                            @error('description')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

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

                            <label class="form-label">
                                Sample Type
                            </label>

                            <input
                                type="text"
                                name="sample_type"
                                class="form-control @error('sample_type') is-invalid @enderror"
                                value="{{ old('sample_type', $healthCheckupTest->sample_type) }}"
                                placeholder="Example: Blood, Urine, Serum">

                            @error('sample_type')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <small class="text-muted">
                                Example: Blood, Urine, Serum, Plasma.
                            </small>

                        </div>


                        {{-- Report Time --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Report Time
                            </label>

                            <input
                                type="text"
                                name="report_time"
                                class="form-control @error('report_time') is-invalid @enderror"
                                value="{{ old('report_time', $healthCheckupTest->report_time) }}"
                                placeholder="Example: 24 Hours">

                            @error('report_time')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <small class="text-muted">
                                Example: Same Day, 24 Hours, 2 Days.
                            </small>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- Package Usage --}}
            {{-- ========================================================= --}}

            @if($healthCheckupTest->packages_count ?? false)

                <div class="card mb-4">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div class="avatar avatar-md bg-info-subtle rounded me-3">

                                <i class="ti ti-package text-info fs-4"></i>

                            </div>

                            <div>

                                <h6 class="mb-1">
                                    Package Usage
                                </h6>

                                <p class="text-muted mb-0">

                                    This test is currently assigned to
                                    <strong>
                                        {{ $healthCheckupTest->packages_count }}
                                    </strong>
                                    package(s).

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- Settings --}}
            {{-- ========================================================= --}}

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="card-title mb-0">

                        <i class="ti ti-settings me-2"></i>

                        Settings

                    </h5>

                </div>


                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between">

                        <div>

                            <h6 class="mb-1">
                                Status
                            </h6>

                            <p class="text-muted mb-0">

                                Active tests can be selected when creating
                                health checkup packages.

                            </p>

                        </div>


                        <div class="form-check form-switch">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="status"
                                value="1"
                                id="status"
                                {{ old('status', $healthCheckupTest->status) ? 'checked' : '' }}>

                            <label
                                class="form-check-label"
                                for="status">

                                Active

                            </label>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- Actions --}}
            {{-- ========================================================= --}}

            <div class="d-flex align-items-center justify-content-end gap-2 mb-4">

                <a
                    href="{{ route('admin.health-checkup-tests.index') }}"
                    class="btn btn-light">

                    Cancel

                </a>


                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="ti ti-device-floppy me-1"></i>

                    Update Test

                </button>

            </div>

        </form>

    </div>
</div>

@endsection


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Slug
    |--------------------------------------------------------------------------
    */

    const nameInput =
        document.getElementById('testName');

    const slugInput =
        document.getElementById('testSlug');


    if (nameInput && slugInput) {

        let originalSlug =
            slugInput.value;


        nameInput.addEventListener('input', function () {

            /*
            |--------------------------------------------------------------------------
            | Only automatically update slug when the existing slug
            | matches the previously generated value.
            |--------------------------------------------------------------------------
            */

            const generatedOriginal =
                originalSlug;

            if (
                slugInput.value === generatedOriginal ||
                slugInput.value === ''
            ) {

                slugInput.value =
                    this.value
                        .toLowerCase()
                        .trim()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-');

            }

        });

    }

});

</script>

@endpush