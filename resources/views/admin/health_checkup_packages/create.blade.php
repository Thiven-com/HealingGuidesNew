@extends('admin.layouts.app')

@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- Page Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">

            <div>
                <h4 class="mb-1">Add Health Checkup Package</h4>

                <p class="text-muted mb-0">
                    Create a health checkup package and select the tests included in it.
                </p>
            </div>

            <a href="{{ route('admin.health-checkup-packages.index') }}"
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

                        <strong>Please fix the following errors:</strong>

                        <ul class="mb-0 mt-2">

                            @foreach ($errors->all() as $error)

                                <li>{{ $error }}</li>

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
            action="{{ route('admin.health-checkup-packages.store') }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf


            {{-- ========================================================= --}}
            {{-- Basic Information --}}
            {{-- ========================================================= --}}

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="card-title mb-0">

                        <i class="ti ti-package me-2"></i>

                        Package Information

                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        {{-- Health Checkup --}}
                        <div class="col-md-6">

                            <label class="form-label">

                                Health Checkup

                                <span class="text-danger">*</span>

                            </label>

                            <select
                                name="health_checkup_id"
                                class="form-select @error('health_checkup_id') is-invalid @enderror"
                                required>

                                <option value="">
                                    Select Health Checkup
                                </option>

                                @foreach($healthCheckups as $healthCheckup)

                                    <option
                                        value="{{ $healthCheckup->id }}"
                                        {{ old('health_checkup_id') == $healthCheckup->id ? 'selected' : '' }}>

                                        {{ $healthCheckup->name }}

                                    </option>

                                @endforeach

                            </select>

                            @error('health_checkup_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Display Order --}}
                        <div class="col-md-3">

                            <label class="form-label">
                                Display Order
                            </label>

                            <input
                                type="number"
                                name="display_order"
                                min="0"
                                class="form-control @error('display_order') is-invalid @enderror"
                                value="{{ old('display_order', 0) }}"
                                placeholder="0">

                            @error('display_order')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Status --}}
                        <div class="col-md-3">

                            <label class="form-label d-block">
                                Status
                            </label>

                            <div class="form-check form-switch mt-2">

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


                        {{-- Package Name --}}
                        <div class="col-md-8">

                            <label class="form-label">

                                Package Name

                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="text"
                                name="name"
                                id="packageName"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="Example: Comprehensive Full Body Checkup"
                                required>

                            @error('name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Slug --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Slug
                            </label>

                            <input
                                type="text"
                                name="slug"
                                id="packageSlug"
                                class="form-control @error('slug') is-invalid @enderror"
                                value="{{ old('slug') }}"
                                placeholder="package-slug">

                            @error('slug')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <small class="text-muted">
                                Leave empty to generate automatically.
                            </small>

                        </div>


                        {{-- Image --}}
                        <div class="col-md-12">

                            <label class="form-label">
                                Package Image
                            </label>

                            <input
                                type="file"
                                name="image"
                                id="packageImage"
                                class="form-control @error('image') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.webp">

                            @error('image')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <small class="text-muted">
                                JPG, JPEG, PNG or WEBP. Maximum 5MB.
                            </small>


                            {{-- Image Preview --}}
                            <div
                                id="imagePreviewWrapper"
                                class="mt-3"
                                style="display:none;">

                                <img
                                    id="imagePreview"
                                    src=""
                                    alt="Package Preview"
                                    class="rounded border"
                                    style="
                                        width:180px;
                                        height:120px;
                                        object-fit:cover;
                                    ">

                            </div>

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
                                placeholder="Enter short package description">

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
                                placeholder="Enter complete package description">{{ old('description') }}</textarea>

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
            {{-- Pricing --}}
            {{-- ========================================================= --}}

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="card-title mb-0">

                        <i class="ti ti-currency-rupee me-2"></i>

                        Package Pricing

                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        {{-- Original Price --}}
                        <div class="col-md-6">

                            <label class="form-label">

                                Original Price

                                <span class="text-danger">*</span>

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ₹
                                </span>

                                <input
                                    type="number"
                                    name="original_price"
                                    id="originalPrice"
                                    step="0.01"
                                    min="0"
                                    class="form-control @error('original_price') is-invalid @enderror"
                                    value="{{ old('original_price', 0) }}"
                                    placeholder="0.00"
                                    required>

                            </div>

                            @error('original_price')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Offer Price --}}
                        <div class="col-md-6">

                            <label class="form-label">

                                Offer Price

                                <span class="text-danger">*</span>

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ₹
                                </span>

                                <input
                                    type="number"
                                    name="price"
                                    id="offerPrice"
                                    step="0.01"
                                    min="0"
                                    class="form-control @error('price') is-invalid @enderror"
                                    value="{{ old('price', 0) }}"
                                    placeholder="0.00"
                                    required>

                            </div>

                            @error('price')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Discount Preview --}}
                        <div class="col-md-12">

                            <div
                                id="discountBox"
                                class="alert alert-success mb-0"
                                style="display:none;">

                                <div class="d-flex align-items-center">

                                    <i class="ti ti-discount-2 me-2 fs-4"></i>

                                    <div>

                                        <strong>
                                            Discount:
                                        </strong>

                                        <span id="discountPercentage">
                                            0%
                                        </span>

                                        <span class="ms-2">
                                            You save ₹
                                            <span id="discountAmount">
                                                0.00
                                            </span>
                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- Package Tests --}}
            {{-- ========================================================= --}}

            <div class="card mb-4">

                <div class="card-header">

                    <div class="d-flex align-items-center justify-content-between">

                        <div>

                            <h5 class="card-title mb-1">

                                <i class="ti ti-test-pipe me-2"></i>

                                Package Tests

                            </h5>

                            <p class="text-muted mb-0">

                                Select the tests included in this package.

                            </p>

                        </div>


                        <span
                            class="badge bg-primary-subtle text-primary"
                            id="selectedTestsCount">

                            0 Tests

                        </span>

                    </div>

                </div>


                <div class="card-body">


                    @if($tests->count())


                        {{-- Search Tests --}}
                        <div class="mb-3">

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="ti ti-search"></i>
                                </span>

                                <input
                                    type="text"
                                    id="testSearch"
                                    class="form-control"
                                    placeholder="Search tests...">

                            </div>

                        </div>


                        {{-- Select All --}}
                        <div class="border rounded p-3 mb-3">

                            <div class="form-check">

                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    id="selectAllTests">

                                <label
                                    class="form-check-label fw-semibold"
                                    for="selectAllTests">

                                    Select All Tests

                                </label>

                            </div>

                        </div>


                        {{-- Tests --}}
                        <div
                            id="testsContainer"
                            class="row g-3">


                            @foreach($tests as $test)

                                <div
                                    class="col-md-6 col-lg-4 test-item"
                                    data-test-name="{{ strtolower($test->name) }}">

                                    <div
                                        class="border rounded p-3 h-100 test-card">

                                        <div class="form-check">

                                            <input
                                                type="checkbox"
                                                name="tests[]"
                                                value="{{ $test->id }}"
                                                id="test_{{ $test->id }}"
                                                class="form-check-input test-checkbox"
                                                {{ in_array(
                                                    $test->id,
                                                    old('tests', [])
                                                ) ? 'checked' : '' }}>

                                            <label
                                                class="form-check-label w-100"
                                                for="test_{{ $test->id }}">

                                                <span class="d-block fw-semibold">

                                                    {{ $test->name }}

                                                </span>


                                                @if($test->sample_type)

                                                    <small class="text-muted d-block mt-1">

                                                        <i class="ti ti-droplet me-1"></i>

                                                        {{ $test->sample_type }}

                                                    </small>

                                                @endif


                                                @if($test->report_time)

                                                    <small class="text-muted d-block mt-1">

                                                        <i class="ti ti-clock me-1"></i>

                                                        {{ $test->report_time }}

                                                    </small>

                                                @endif

                                            </label>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>


                    @else

                        <div class="text-center py-5">

                            <div class="mb-3">

                                <i
                                    class="ti ti-test-pipe-off"
                                    style="font-size:50px;">
                                </i>

                            </div>

                            <h6>
                                No active tests found
                            </h6>

                            <p class="text-muted mb-3">
                                Create health checkup tests before creating a package.
                            </p>

                            <a
                                href="{{ route('admin.health-checkup-tests.create') }}"
                                class="btn btn-primary">

                                <i class="ti ti-plus me-1"></i>

                                Add Test

                            </a>

                        </div>

                    @endif

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- Actions --}}
            {{-- ========================================================= --}}

            <div class="d-flex align-items-center justify-content-end gap-2 mb-4">

                <a
                    href="{{ route('admin.health-checkup-packages.index') }}"
                    class="btn btn-light">

                    Cancel

                </a>


                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="ti ti-device-floppy me-1"></i>

                    Save Package

                </button>

            </div>

        </form>

    </div>
</div>

@endsection


@push('styles')

<style>

    .test-card {
        transition: all .2s ease;
        cursor: pointer;
    }

    .test-card:hover {
        border-color: var(--bs-primary) !important;
        background: rgba(var(--bs-primary-rgb), .03);
    }

    .test-card.selected {
        border-color: var(--bs-primary) !important;
        background: rgba(var(--bs-primary-rgb), .05);
    }

</style>

@endpush


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Slug Generation
    |--------------------------------------------------------------------------
    */

    const packageName =
        document.getElementById('packageName');

    const packageSlug =
        document.getElementById('packageSlug');


    if (packageName && packageSlug) {

        packageName.addEventListener('input', function () {

            if (
                packageSlug.dataset.manual === 'true'
            ) {
                return;
            }

            packageSlug.value =
                this.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');

        });


        packageSlug.addEventListener('input', function () {

            this.dataset.manual =
                this.value.length > 0
                    ? 'true'
                    : 'false';

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Image Preview
    |--------------------------------------------------------------------------
    */

    const imageInput =
        document.getElementById('packageImage');

    const imagePreview =
        document.getElementById('imagePreview');

    const imagePreviewWrapper =
        document.getElementById('imagePreviewWrapper');


    if (
        imageInput &&
        imagePreview &&
        imagePreviewWrapper
    ) {

        imageInput.addEventListener('change', function () {

            const file =
                this.files[0];

            if (!file) {

                imagePreviewWrapper.style.display =
                    'none';

                return;

            }


            if (!file.type.startsWith('image/')) {

                imagePreviewWrapper.style.display =
                    'none';

                return;

            }


            const reader =
                new FileReader();


            reader.onload = function (event) {

                imagePreview.src =
                    event.target.result;

                imagePreviewWrapper.style.display =
                    'block';

            };


            reader.readAsDataURL(file);

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Price / Discount
    |--------------------------------------------------------------------------
    */

    const originalPrice =
        document.getElementById('originalPrice');

    const offerPrice =
        document.getElementById('offerPrice');

    const discountBox =
        document.getElementById('discountBox');

    const discountPercentage =
        document.getElementById('discountPercentage');

    const discountAmount =
        document.getElementById('discountAmount');


    function calculateDiscount() {

        const original =
            parseFloat(originalPrice?.value) || 0;

        const offer =
            parseFloat(offerPrice?.value) || 0;


        if (
            original > 0 &&
            offer >= 0 &&
            offer < original
        ) {

            const saved =
                original - offer;

            const percentage =
                (saved / original) * 100;


            discountPercentage.textContent =
                percentage.toFixed(2) + '%';

            discountAmount.textContent =
                saved.toFixed(2);

            discountBox.style.display =
                'block';

        } else {

            discountBox.style.display =
                'none';

        }

    }


    if (originalPrice) {

        originalPrice.addEventListener(
            'input',
            calculateDiscount
        );

    }


    if (offerPrice) {

        offerPrice.addEventListener(
            'input',
            calculateDiscount
        );

    }


    calculateDiscount();


    /*
    |--------------------------------------------------------------------------
    | Tests
    |--------------------------------------------------------------------------
    */

    const testCheckboxes =
        document.querySelectorAll('.test-checkbox');

    const selectAllTests =
        document.getElementById('selectAllTests');

    const selectedTestsCount =
        document.getElementById('selectedTestsCount');


    function updateSelectedTests() {

        const checked =
            document.querySelectorAll(
                '.test-checkbox:checked'
            );

        const count =
            checked.length;


        if (selectedTestsCount) {

            selectedTestsCount.textContent =
                count +
                (count === 1
                    ? ' Test'
                    : ' Tests');

        }


        document
            .querySelectorAll('.test-card')
            .forEach(function (card) {

                const checkbox =
                    card.querySelector(
                        '.test-checkbox'
                    );

                if (
                    checkbox &&
                    checkbox.checked
                ) {

                    card.classList.add(
                        'selected'
                    );

                } else {

                    card.classList.remove(
                        'selected'
                    );

                }

            });


        if (selectAllTests) {

            if (
                testCheckboxes.length > 0 &&
                checked.length === testCheckboxes.length
            ) {

                selectAllTests.checked =
                    true;

                selectAllTests.indeterminate =
                    false;

            } else if (checked.length > 0) {

                selectAllTests.checked =
                    false;

                selectAllTests.indeterminate =
                    true;

            } else {

                selectAllTests.checked =
                    false;

                selectAllTests.indeterminate =
                    false;

            }

        }

    }


    testCheckboxes.forEach(function (checkbox) {

        checkbox.addEventListener(
            'change',
            updateSelectedTests
        );

    });


    if (selectAllTests) {

        selectAllTests.addEventListener(
            'change',
            function () {

                testCheckboxes.forEach(
                    function (checkbox) {

                        checkbox.checked =
                            selectAllTests.checked;

                    }
                );

                updateSelectedTests();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Search Tests
    |--------------------------------------------------------------------------
    */

    const testSearch =
        document.getElementById('testSearch');


    if (testSearch) {

        testSearch.addEventListener(
            'input',
            function () {

                const search =
                    this.value
                        .toLowerCase()
                        .trim();


                document
                    .querySelectorAll('.test-item')
                    .forEach(function (item) {

                        const name =
                            item.dataset.testName || '';

                        item.style.display =
                            name.includes(search)
                                ? ''
                                : 'none';

                    });

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Initial State
    |--------------------------------------------------------------------------
    */

    updateSelectedTests();

});

</script>

@endpush