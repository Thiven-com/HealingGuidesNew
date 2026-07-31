@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            <!-- Page Header -->

            <div class="page-header">

                <div class="page-title">

                    <h4>Add Lab Test</h4>

                    <h6>Create New Lab Test</h6>

                </div>

                <div class="page-btn">

                    <a href="{{ route('admin.lab-tests.index') }}" class="btn btn-secondary">

                        <i class="ti ti-arrow-left me-1"></i>

                        Back

                    </a>

                </div>

            </div>

            @if ($errors->any())

                <div class="alert alert-danger alert-dismissible fade show">

                    <strong>Please fix the following errors:</strong>

                    <ul class="mb-0 mt-2">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>

            @endif

            <form action="{{ route('admin.lab-tests.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <!-- Basic Information -->

                <div class="card border-0 shadow-sm">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-flask me-2"></i>

                            Lab Test Information

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Test Name -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Test Name

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="text" id="test_name" name="test_name" class="form-control"
                                    value="{{ old('test_name') }}" placeholder="Enter Test Name">

                            </div>

                            <!-- Test Code -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Test Code

                                </label>

                                <input type="text" class="form-control" value="Auto Generated" readonly>

                            </div>

                            <!-- Slug -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Slug

                                </label>

                                <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug') }}"
                                    readonly>

                            </div>

                            <!-- Sample Type -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Sample Type

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="text" name="sample_type" class="form-control" value="{{ old('sample_type') }}"
                                    placeholder="Blood, Urine, Saliva etc.">

                            </div>

                        </div>

                    </div>

                </div>
                <!-- Test Details -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-notes me-2"></i>

                            Test Details

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Description -->

                            <div class="col-lg-12 mb-3">

                                <label class="form-label">

                                    Description

                                </label>

                                <textarea name="description" rows="4" class="form-control"
                                    placeholder="Enter Test Description">{{ old('description') }}</textarea>

                            </div>

                            <!-- Preparation -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Preparation

                                </label>

                                <textarea name="preparation" rows="3" class="form-control"
                                    placeholder="Preparation before the test">{{ old('preparation') }}</textarea>

                            </div>

                            <!-- Report Time -->

                            <div class="col-lg-3 mb-3">

                                <label class="form-label">

                                    Report Time

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="number" min="1" name="report_time" class="form-control"
                                    value="{{ old('report_time') }}" placeholder="24">

                            </div>

                            <!-- Report Time Type -->

                            <div class="col-lg-3 mb-3">

                                <label class="form-label">

                                    Report Time Type

                                </label>

                                <select name="report_time_type" class="form-select">

                                    <option value="Hours" {{ old('report_time_type') == 'Hours' ? 'selected' : '' }}>

                                        Hours

                                    </option>

                                    <option value="Days" {{ old('report_time_type') == 'Days' ? 'selected' : '' }}>

                                        Days

                                    </option>

                                </select>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- Image Upload -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-photo me-2"></i>

                            Lab Test Image

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row justify-content-center">

                            <div class="col-lg-6">

                                <div class="border rounded-3 p-4 text-center">

                                    <h6 class="mb-3">

                                        Upload Image

                                    </h6>

                                    <img id="imagePreview" src="{{ asset('assets/img/no-image.png') }}"
                                        class="img-thumbnail mb-3" style="width:180px;height:180px;object-fit:contain;">

                                    <input type="file" id="image" name="image" class="form-control" accept="image/*">

                                    <small class="text-muted d-block mt-2">

                                        Supported formats: JPG, PNG, WEBP

                                    </small>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
                <!-- Test Options -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-settings me-2"></i>

                            Test Options

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Fasting Required -->

                            <div class="col-lg-4 mb-3">

                                <label class="form-label">

                                    Fasting Required

                                </label>

                                <select name="fasting_required" class="form-select">

                                    <option value="1" {{ old('fasting_required', 1) == 1 ? 'selected' : '' }}>

                                        Yes

                                    </option>

                                    <option value="0" {{ old('fasting_required') === '0' ? 'selected' : '' }}>

                                        No

                                    </option>

                                </select>

                            </div>

                            <!-- Home Collection -->

                            <div class="col-lg-4 mb-3">

                                <label class="form-label">

                                    Home Collection

                                </label>

                                <select name="home_collection" class="form-select">

                                    <option value="1" {{ old('home_collection', 1) == 1 ? 'selected' : '' }}>

                                        Yes

                                    </option>

                                    <option value="0" {{ old('home_collection') === '0' ? 'selected' : '' }}>

                                        No

                                    </option>

                                </select>

                            </div>

                            <!-- Status -->

                            <div class="col-lg-4 mb-3">

                                <label class="form-label">

                                    Status

                                </label>

                                <select name="status" class="form-select">

                                    <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>

                                        Active

                                    </option>

                                    <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>

                                        Inactive

                                    </option>

                                </select>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- Action Buttons -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('admin.lab-tests.index') }}" class="btn btn-light">

                                <i class="ti ti-arrow-left me-1"></i>

                                Cancel

                            </a>

                            <button type="submit" id="submitBtn" class="btn btn-primary">

                                <span id="submitText">

                                    <i class="ti ti-device-floppy me-1"></i>

                                    Save Lab Test

                                </span>

                                <span id="loadingText" class="d-none">

                                    <span class="spinner-border spinner-border-sm me-2"></span>

                                    Saving...

                                </span>

                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>
    <script>

        document.addEventListener("DOMContentLoaded", function () {

            // Auto Slug Generator
            const testName = document.getElementById('test_name');
            const slug = document.getElementById('slug');

            if (testName) {

                testName.addEventListener('input', function () {

                    slug.value = this.value
                        .toLowerCase()
                        .trim()
                        .replace(/[^a-z0-9]+/g, '-')
                        .replace(/^-+|-+$/g, '');

                });

            }

            // Image Preview
            const image = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');

            if (image) {

                image.addEventListener('change', function () {

                    if (this.files.length) {

                        const reader = new FileReader();

                        reader.onload = function (e) {

                            imagePreview.src = e.target.result;

                        };

                        reader.readAsDataURL(this.files[0]);

                    }

                });

            }

            // Submit Loading Button
            const form = document.querySelector('form');

            if (form) {

                form.addEventListener('submit', function () {

                    document.getElementById('submitBtn').disabled = true;

                    document.getElementById('submitText').classList.add('d-none');

                    document.getElementById('loadingText').classList.remove('d-none');

                });

            }

        });

    </script>

@endsection