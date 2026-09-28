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

                    <h4>Add Package</h4>

                    <h6>Create membership package and configure benefits</h6>

                </div>

            </div>

            <div class="page-btn">

                <a href="{{ route('admin.packages.index') }}"
                   class="btn btn-light">

                    <i class="ti ti-arrow-left me-1"></i>

                    Back

                </a>

            </div>

        </div>


        {{-- =========================================================
            ALERTS
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


        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show">

                {{ session('error') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        @if($errors->any())

            <div class="alert alert-danger alert-dismissible fade show">

                <strong>Please fix the following errors:</strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- =========================================================
            FORM
        ========================================================== --}}

        <form action="{{ route('admin.packages.store') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <div class="row">

                {{-- =================================================
                    LEFT SIDE
                ================================================== --}}

                <div class="col-lg-8">

                    {{-- =============================================
                        PACKAGE DETAILS
                    ============================================== --}}

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">

                                <i class="ti ti-package me-1"></i>

                                Package Details

                            </h5>

                        </div>


                        <div class="card-body">

                            <div class="row">


                                {{-- Package Name --}}

                                <div class="col-md-6">

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Package Name
                                            <span class="text-danger">*</span>

                                        </label>

                                        <input type="text"
                                               name="name"
                                               value="{{ old('name') }}"
                                               class="form-control"
                                               placeholder="Enter package name"
                                               required>

                                    </div>

                                </div>


                                {{-- Price --}}

                                <div class="col-md-6">

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Price
                                            <span class="text-danger">*</span>

                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text">
                                                ₹
                                            </span>

                                            <input type="number"
                                                   name="price"
                                                   value="{{ old('price') }}"
                                                   min="0"
                                                   step="0.01"
                                                   class="form-control"
                                                   placeholder="Enter price"
                                                   required>

                                        </div>

                                    </div>

                                </div>


                                {{-- Duration --}}

                                <div class="col-md-6">

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Duration
                                            <span class="text-danger">*</span>

                                        </label>

                                        <div class="input-group">

                                            <input type="number"
                                                   name="duration_days"
                                                   value="{{ old('duration_days', 365) }}"
                                                   min="1"
                                                   class="form-control"
                                                   required>

                                            <span class="input-group-text">
                                                Days
                                            </span>

                                        </div>

                                    </div>

                                </div>


                                {{-- Display Order --}}

                                <div class="col-md-6">

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Display Order

                                        </label>

                                        <input type="number"
                                               name="display_order"
                                               value="{{ old('display_order', 0) }}"
                                               min="0"
                                               class="form-control"
                                               placeholder="0">

                                    </div>

                                </div>


                                {{-- Description --}}

                                <div class="col-md-12">

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Description

                                        </label>

                                        <textarea name="description"
                                                  rows="4"
                                                  class="form-control"
                                                  placeholder="Enter package description">{{ old('description') }}</textarea>

                                    </div>

                                </div>


                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                        PACKAGE BENEFITS
                    ================================================== --}}

                    <div class="card">

                        <div class="card-header">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <h5 class="card-title mb-1">

                                        <i class="ti ti-gift me-1"></i>

                                        Package Benefits

                                    </h5>

                                    <small class="text-muted">

                                        Add the free services included with this package.

                                    </small>

                                </div>


                                <button type="button"
                                        id="addBenefit"
                                        class="btn btn-primary btn-sm">

                                    <i class="ti ti-plus me-1"></i>

                                    Add Benefit

                                </button>

                            </div>

                        </div>


                        <div class="card-body">

                            <div id="benefitsContainer">


                                {{-- =================================================
                                    FIRST BENEFIT
                                ================================================== --}}

                                <div class="benefit-row border rounded p-3 mb-3">

                                    <div class="row align-items-end">

                                        <div class="col-md-5">

                                            <div class="mb-3 mb-md-0">

                                                <label class="form-label">

                                                    Benefit
                                                    <span class="text-danger">*</span>

                                                </label>

                                                <select name="benefits[0][type]"
                                                        class="form-select benefit-select"
                                                        required>

                                                    <option value="">
                                                        Select Benefit
                                                    </option>

                                                    @foreach($benefitTypes as $key => $label)

                                                        <option value="{{ $key }}"
                                                            {{ old('benefits.0.type') == $key ? 'selected' : '' }}>

                                                            {{ $label }}

                                                        </option>

                                                    @endforeach

                                                </select>

                                            </div>

                                        </div>


                                        <div class="col-md-3">

                                            <div class="mb-3 mb-md-0">

                                                <label class="form-label">

                                                    Quantity
                                                    <span class="text-danger">*</span>

                                                </label>

                                                <input type="number"
                                                       name="benefits[0][quantity]"
                                                       value="{{ old('benefits.0.quantity', 1) }}"
                                                       min="1"
                                                       class="form-control"
                                                       required>

                                            </div>

                                        </div>


                                        <div class="col-md-3">

                                            <div class="mb-3 mb-md-0">

                                                <label class="form-label">

                                                    Description

                                                </label>

                                                <input type="text"
                                                       name="benefits[0][description]"
                                                       value="{{ old('benefits.0.description') }}"
                                                       class="form-control"
                                                       placeholder="Optional">

                                            </div>

                                        </div>


                                        <div class="col-md-1">

                                            <button type="button"
                                                    class="btn btn-outline-danger remove-benefit"
                                                    title="Remove">

                                                <i class="ti ti-trash"></i>

                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- Empty State --}}

                            <div id="emptyBenefits"
                                 class="text-center py-4 d-none">

                                <i class="ti ti-gift fs-1 text-muted"></i>

                                <p class="text-muted mb-0 mt-2">

                                    No benefits added.

                                </p>

                                <small class="text-muted">

                                    Click "Add Benefit" to add package benefits.

                                </small>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    RIGHT SIDE
                ================================================== --}}

                <div class="col-lg-4">


                    {{-- =================================================
                        PACKAGE IMAGE
                    ================================================== --}}

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">

                                <i class="ti ti-photo me-1"></i>

                                Package Image

                            </h5>

                        </div>


                        <div class="card-body">

                            <div class="mb-3">

                                <label class="form-label">

                                    Image

                                </label>

                                <input type="file"
                                       name="image"
                                       id="packageImage"
                                       class="form-control"
                                       accept="image/jpeg,image/png,image/jpg,image/webp">

                                <small class="text-muted">

                                    JPG, JPEG, PNG or WEBP

                                </small>

                            </div>


                            <div id="imagePreviewContainer"
                                 class="text-center d-none">

                                <img id="imagePreview"
                                     src=""
                                     class="img-fluid rounded"
                                     style="max-height:220px;">

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                        STATUS
                    ================================================== --}}

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">

                                <i class="ti ti-toggle-right me-1"></i>

                                Status

                            </h5>

                        </div>


                        <div class="card-body">

                            <div class="form-check form-switch">

                                <input type="checkbox"
                                       name="status"
                                       value="1"
                                       class="form-check-input"
                                       id="packageStatus"
                                       {{ old('status', 1) ? 'checked' : '' }}>

                                <label class="form-check-label"
                                       for="packageStatus">

                                    Active

                                </label>

                            </div>

                            <small class="text-muted">

                                Active packages will be visible to customers.

                            </small>

                        </div>

                    </div>


                    {{-- =================================================
                        ACTIONS
                    ================================================== --}}

                    <div class="card">

                        <div class="card-body">

                            <button type="submit"
                                    class="btn btn-primary w-100 mb-2">

                                <i class="ti ti-device-floppy me-1"></i>

                                Save Package

                            </button>


                            <a href="{{ route('admin.packages.index') }}"
                               class="btn btn-light w-100">

                                Cancel

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- =========================================================
    JAVASCRIPT
========================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    let benefitIndex = 1;

    const container =
        document.getElementById('benefitsContainer');

    const addButton =
        document.getElementById('addBenefit');

    const emptyBenefits =
        document.getElementById('emptyBenefits');


    /*
    |--------------------------------------------------------------------------
    | Benefit Options
    |--------------------------------------------------------------------------
    */

    const benefitOptions = `

        <option value="">
            Select Benefit
        </option>

        @foreach($benefitTypes as $key => $label)

            <option value="{{ $key }}">
                {{ $label }}
            </option>

        @endforeach

    `;


    /*
    |--------------------------------------------------------------------------
    | Add Benefit
    |--------------------------------------------------------------------------
    */

    addButton.addEventListener('click', function () {

        const row =
            document.createElement('div');

        row.className =
            'benefit-row border rounded p-3 mb-3';


        row.innerHTML = `

            <div class="row align-items-end">

                <div class="col-md-5">

                    <div class="mb-3 mb-md-0">

                        <label class="form-label">

                            Benefit
                            <span class="text-danger">*</span>

                        </label>

                        <select name="benefits[${benefitIndex}][type]"
                                class="form-select benefit-select"
                                required>

                            ${benefitOptions}

                        </select>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="mb-3 mb-md-0">

                        <label class="form-label">

                            Quantity
                            <span class="text-danger">*</span>

                        </label>

                        <input type="number"
                               name="benefits[${benefitIndex}][quantity]"
                               value="1"
                               min="1"
                               class="form-control"
                               required>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="mb-3 mb-md-0">

                        <label class="form-label">

                            Description

                        </label>

                        <input type="text"
                               name="benefits[${benefitIndex}][description]"
                               class="form-control"
                               placeholder="Optional">

                    </div>

                </div>


                <div class="col-md-1">

                    <button type="button"
                            class="btn btn-outline-danger remove-benefit"
                            title="Remove">

                        <i class="ti ti-trash"></i>

                    </button>

                </div>

            </div>

        `;


        container.appendChild(row);

        benefitIndex++;

        updateEmptyState();

    });


    /*
    |--------------------------------------------------------------------------
    | Remove Benefit
    |--------------------------------------------------------------------------
    */

    document.addEventListener('click', function (event) {

        const button =
            event.target.closest('.remove-benefit');


        if (!button) {
            return;
        }


        const row =
            button.closest('.benefit-row');


        if (row) {

            row.remove();

            updateEmptyState();

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Prevent Duplicate Benefits
    |--------------------------------------------------------------------------
    */

    document.addEventListener('change', function (event) {

        if (
            !event.target.classList.contains(
                'benefit-select'
            )
        ) {
            return;
        }


        const selectedValue =
            event.target.value;


        if (!selectedValue) {
            return;
        }


        let duplicate = false;


        document
            .querySelectorAll('.benefit-select')
            .forEach(function (select) {

                if (
                    select !== event.target &&
                    select.value === selectedValue
                ) {

                    duplicate = true;

                }

            });


        if (duplicate) {

            alert(
                'This benefit has already been added.'
            );

            event.target.value = '';

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Empty State
    |--------------------------------------------------------------------------
    */

    function updateEmptyState()
    {
        const rows =
            container.querySelectorAll(
                '.benefit-row'
            );


        if (rows.length === 0) {

            emptyBenefits.classList.remove(
                'd-none'
            );

        } else {

            emptyBenefits.classList.add(
                'd-none'
            );

        }
    }


    /*
    |--------------------------------------------------------------------------
    | Image Preview
    |--------------------------------------------------------------------------
    */

    const imageInput =
        document.getElementById(
            'packageImage'
        );

    const imagePreview =
        document.getElementById(
            'imagePreview'
        );

    const imagePreviewContainer =
        document.getElementById(
            'imagePreviewContainer'
        );


    imageInput.addEventListener('change', function (event) {

        const file =
            event.target.files[0];


        if (!file) {

            imagePreviewContainer.classList.add(
                'd-none'
            );

            return;

        }


        const reader =
            new FileReader();


        reader.onload = function (e) {

            imagePreview.src =
                e.target.result;

            imagePreviewContainer.classList.remove(
                'd-none'
            );

        };


        reader.readAsDataURL(file);

    });

});

</script>

@endsection