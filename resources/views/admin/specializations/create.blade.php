@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="page-title">
                    <h4>Add Specialization</h4>
                    <h6>Create New Medical Specialization</h6>
                </div>

                <div class="page-btn">
                    <a href="{{ route('admin.specializations.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Back
                    </a>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Validation Errors -->
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

            <form action="{{ route('admin.specializations.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <!-- Basic Information -->
                <div class="card border-0 shadow-sm">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-stethoscope me-2"></i>

                            Specialization Information

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Specialization Name -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Specialization Name

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="text" name="specialization_name" id="specialization_name" class="form-control"
                                    value="{{ old('specialization_name') }}" placeholder="Enter Specialization Name">

                            </div>

                            <!-- Slug -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Slug

                                </label>

                                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}"
                                    placeholder="Auto Generated" readonly>

                            </div>

                            <!-- Status -->

                            <div class="col-lg-6 mb-3">

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

                            <!-- Description -->

                            <div class="col-lg-12 mb-3">

                                <label class="form-label">

                                    Description

                                </label>

                                <textarea name="description" rows="5" class="form-control"
                                    placeholder="Enter specialization description...">{{ old('description') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>
                <!-- Media Upload -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-photo me-2"></i>

                            Media Upload

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Icon Upload -->

                            <div class="col-lg-6">

                                <div class="border rounded-3 p-4 text-center h-100">

                                    <h6 class="mb-3">

                                        Specialization Icon

                                    </h6>

                                    <img id="iconPreview" src="{{ asset('assets/img/no-image.png') }}"
                                        class="img-thumbnail mb-3" style="width:120px;height:120px;object-fit:contain;">

                                    <input type="file" class="form-control" id="icon" name="icon" accept="image/*">

                                    <small class="text-muted d-block mt-2">

                                        Supported: JPG, PNG, SVG, WEBP

                                    </small>

                                </div>

                            </div>

                            <!-- Image Upload -->

                            <div class="col-lg-6">

                                <div class="border rounded-3 p-4 text-center h-100">

                                    <h6 class="mb-3">

                                        Banner Image

                                    </h6>

                                    <img id="imagePreview" src="{{ asset('assets/img/no-image.png') }}"
                                        class="img-thumbnail mb-3" style="width:220px;height:140px;object-fit:cover;">

                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">

                                    <small class="text-muted d-block mt-2">

                                        Recommended Size: 800 × 500 px

                                    </small>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
                <!-- Action Buttons -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('admin.specializations.index') }}" class="btn btn-light">

                                <i class="ti ti-arrow-left me-1"></i>

                                Cancel

                            </a>

                            <button type="submit" id="submitBtn" class="btn btn-primary">

                                <span id="submitText">

                                    <i class="ti ti-device-floppy me-1"></i>

                                    Save Specialization

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

        $(function () {

            // Auto Slug Generator
            $('#specialization_name').on('keyup change', function () {

                let slug = $(this).val()
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');

                $('#slug').val(slug);

            });


            // Image Preview
            $('#image').change(function () {

                const file = this.files[0];

                if (file) {

                    const reader = new FileReader();

                    reader.onload = function (e) {

                        $('#imagePreview').attr('src', e.target.result);

                    }

                    reader.readAsDataURL(file);

                }

            });


            // Icon Preview
            $('#icon').change(function () {

                const file = this.files[0];

                if (file) {

                    const reader = new FileReader();

                    reader.onload = function (e) {

                        $('#iconPreview').attr('src', e.target.result);

                    }

                    reader.readAsDataURL(file);

                }

            });


            // Loading Button
            $('form').submit(function () {

                $('#submitBtn').prop('disabled', true);

                $('#submitText').addClass('d-none');

                $('#loadingText').removeClass('d-none');

            });

        });

    </script>

@endsection