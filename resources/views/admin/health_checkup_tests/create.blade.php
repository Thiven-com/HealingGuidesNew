@extends('layout.mainlayout')
@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- Page Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">

            <div>
                <h4 class="mb-1">Add Health Checkup Test</h4>
                <p class="text-muted mb-0">
                    Create a test that can be included in health checkup packages.
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

            <div class="alert alert-danger">

                <div class="d-flex align-items-start">

                    <i class="ti ti-alert-circle me-2 fs-5"></i>

                    <div>

                        <strong>Please fix the following errors:</strong>

                        <ul class="mb-0 mt-2">

                            @foreach ($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                    </div>

                </div>

            </div>

        @endif


        <form
            action="{{ route('admin.health-checkup-tests.store') }}"
            method="POST">

            @csrf


            {{-- Basic Information --}}
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
                                value="{{ old('name') }}"
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
                                class="form-control @error('display_order') is-invalid @enderror"
                                value="{{ old('display_order', 0) }}"
                                min="0"
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
                                value="{{ old('slug') }}"
                                placeholder="test-slug">

                            <small class="text-muted">
                                Leave empty to generate automatically from the test name.
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
                                value="{{ old('short_description') }}"
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
                                rows="5"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Enter test description">{{ old('description') }}</textarea>

                            @error('description')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- Sample & Report Information --}}
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
                                value="{{ old('sample_type') }}"
                                placeholder="Example: Blood, Urine, Stool">

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
                                value="{{ old('report_time') }}"
                                placeholder="Example: 24 Hours">

                            @error('report_time')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <small class="text-muted">
                                Example: 24 Hours, Same Day, 2 Days.
                            </small>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Status --}}
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
                                Enable this test so it can be selected in health checkup packages.
                            </p>

                        </div>


                        <div class="form-check form-switch">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="status"
                                value="1"
                                id="status"
                                {{ old('status', 1) ? 'checked' : '' }}>

                            <label
                                class="form-check-label"
                                for="status">

                                Active

                            </label>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Form Actions --}}
            <div class="d-flex align-items-center justify-content-end gap-2">

                <a
                    href="{{ route('admin.health-checkup-tests.index') }}"
                    class="btn btn-light">

                    Cancel

                </a>


                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="ti ti-check me-1"></i>

                    Save Test

                </button>

            </div>

        </form>

    </div>
</div>



<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Auto Generate Slug
    |--------------------------------------------------------------------------
    */

    const nameInput =
        document.getElementById('testName');

    const slugInput =
        document.getElementById('testSlug');

    if (nameInput && slugInput) {

        nameInput.addEventListener('input', function () {

            // Only generate automatically while slug is empty
            // or has previously been generated.
            if (
                slugInput.dataset.manual === 'true'
            ) {
                return;
            }

            slugInput.value =
                this.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');

        });


        slugInput.addEventListener('input', function () {

            this.dataset.manual =
                this.value.length > 0
                    ? 'true'
                    : 'false';

        });

    }

});

</script>

@endsection