<?php $page = 'medicine-categories'; ?>

@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            <!-- Page Header -->
            <div class="page-header">

                <div class="page-title">

                    <h4>Add Medicine Category</h4>

                    <h6>
                        Create New Medicine Category
                    </h6>

                </div>

                <div class="page-btn">

                    <a href="{{ route('admin.medicine-categories.index') }}" class="btn btn-secondary">

                        <i class="ti ti-arrow-left me-1"></i>

                        Back

                    </a>

                </div>

            </div>
            <!-- /Page Header -->


            <!-- Validation Errors -->

            @if ($errors->any())

                <div class="alert alert-danger alert-dismissible fade show">

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

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>

            @endif


            <!-- Form -->

            <form action="{{ route('admin.medicine-categories.store') }}" method="POST" enctype="multipart/form-data"
                id="medicineCategoryForm">

                @csrf


                <!-- Category Information -->

                <div class="card border-0 shadow-sm">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-category me-2"></i>

                            Medicine Category Information

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            <!-- Category Name -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Category Name

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="text" name="category_name" id="category_name" class="form-control"
                                    value="{{ old('category_name') }}" placeholder="Enter Medicine Category Name" required>

                            </div>


                            <!-- Slug -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Slug

                                </label>

                                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}"
                                    placeholder="Auto Generated" readonly>

                            </div>


                            <!-- Sort Order -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Sort Order

                                </label>

                                <input type="number" name="sort_order" class="form-control"
                                    value="{{ old('sort_order', 0) }}" min="0" placeholder="Enter Sort Order">

                            </div>


                            <!-- Status -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Status

                                </label>

                                <select name="status" class="form-select">

                                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>
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
                                    placeholder="Enter medicine category description...">{{ old('description') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- Media Upload -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-photo me-2"></i>

                            Category Image

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row">

                            <div class="col-lg-6">

                                <div class="border rounded-3 p-4 text-center h-100">

                                    <h6 class="mb-3">

                                        Medicine Category Image

                                    </h6>


                                    <img id="imagePreview" src="{{ asset('assets/img/no-image.png') }}"
                                        class="img-thumbnail mb-3" style="
                                            width: 180px;
                                            height: 180px;
                                            object-fit: cover;
                                        ">


                                    <input type="file" class="form-control" id="image" name="image"
                                        accept=".jpg,.jpeg,.png,.webp">


                                    <small class="text-muted d-block mt-2">

                                        Supported:
                                        JPG, JPEG, PNG, WEBP

                                    </small>

                                </div>

                            </div>


                            <!-- Image Information -->

                            <div class="col-lg-6">

                                <div class="border rounded-3 p-4 h-100">

                                    <h6 class="mb-3">

                                        Image Guidelines

                                    </h6>


                                    <div class="mb-3">

                                        <i class="ti ti-circle-check text-success me-2"></i>

                                        Upload a clear category image.

                                    </div>


                                    <div class="mb-3">

                                        <i class="ti ti-circle-check text-success me-2"></i>

                                        JPG, JPEG, PNG and WEBP are supported.

                                    </div>


                                    <div class="mb-3">

                                        <i class="ti ti-circle-check text-success me-2"></i>

                                        Maximum file size: 2 MB.

                                    </div>


                                    <div>

                                        <i class="ti ti-circle-check text-success me-2"></i>

                                        Square images are recommended.

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- Action Buttons -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('admin.medicine-categories.index') }}" class="btn btn-light">

                                <i class="ti ti-arrow-left me-1"></i>

                                Cancel

                            </a>


                            <button type="submit" id="submitBtn" class="btn btn-primary">

                                <span id="submitText">

                                    <i class="ti ti-device-floppy me-1"></i>

                                    Save Medicine Category

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

        document.addEventListener('DOMContentLoaded', function () {

            /*
            |--------------------------------------------------------------------------
            | Auto Slug
            |--------------------------------------------------------------------------
            */

            const categoryName =
                document.getElementById('category_name');

            const slug =
                document.getElementById('slug');


            if (categoryName) {

                categoryName.addEventListener(
                    'input',
                    function () {

                        slug.value = this.value
                            .toLowerCase()
                            .trim()
                            .replace(/[^a-z0-9]+/g, '-')
                            .replace(/^-+|-+$/g, '');

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Image Preview
            |--------------------------------------------------------------------------
            */

            const image =
                document.getElementById('image');

            const imagePreview =
                document.getElementById('imagePreview');


            if (image) {

                image.addEventListener(
                    'change',
                    function () {

                        if (
                            this.files &&
                            this.files[0]
                        ) {

                            const reader =
                                new FileReader();


                            reader.onload =
                                function (event) {

                                    imagePreview.src =
                                        event.target.result;

                                };


                            reader.readAsDataURL(
                                this.files[0]
                            );

                        }

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Loading Button
            |--------------------------------------------------------------------------
            */

            const form =
                document.getElementById(
                    'medicineCategoryForm'
                );


            if (form) {

                form.addEventListener(
                    'submit',
                    function () {

                        const submitBtn =
                            document.getElementById(
                                'submitBtn'
                            );

                        const submitText =
                            document.getElementById(
                                'submitText'
                            );

                        const loadingText =
                            document.getElementById(
                                'loadingText'
                            );


                        submitBtn.disabled = true;

                        submitText.classList.add(
                            'd-none'
                        );

                        loadingText.classList.remove(
                            'd-none'
                        );

                    }
                );

            }

        });

    </script>

@endsection
