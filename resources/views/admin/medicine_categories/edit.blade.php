<?php $page = 'medicine-categories'; ?>

@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            <!-- Page Header -->
            <div class="page-header">

                <div class="page-title">

                    <h4>Edit Medicine Category</h4>

                    <h6>
                        Update Medicine Category Information
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

            <form action="{{ route(
        'admin.medicine-categories.update',
        $category->id
    ) }}" method="POST" enctype="multipart/form-data" id="medicineCategoryForm">

                @csrf
                @method('PUT')


                <!-- Category Information -->

                <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-category me-2"></i>

                            Medicine Category Information

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            <!-- Category Name -->

                            <div class="col-lg-6 col-md-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Category Name

                                        <span class="text-danger">
                                            *
                                        </span>

                                    </label>

                                    <input type="text" name="category_name" id="category_name" class="form-control" value="{{ old(
        'category_name',
        $category->category_name
    ) }}" placeholder="Enter Medicine Category Name" required>

                                </div>

                            </div>


                            <!-- Slug -->

                            <div class="col-lg-6 col-md-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Slug
                                    </label>

                                    <input type="text" id="slug" class="form-control" value="{{ old(
        'slug',
        $category->slug
    ) }}" readonly>

                                    <small class="text-muted">

                                        Slug is generated automatically.

                                    </small>

                                </div>

                            </div>


                            <!-- Sort Order -->

                            <div class="col-lg-6 col-md-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Sort Order

                                    </label>

                                    <input type="number" name="sort_order" class="form-control" value="{{ old(
        'sort_order',
        $category->sort_order ?? 0
    ) }}" min="0">

                                </div>

                            </div>


                            <!-- Status -->

                            <div class="col-lg-6 col-md-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Status

                                        <span class="text-danger">
                                            *
                                        </span>

                                    </label>

                                    <select name="status" class="form-select" required>

                                        <option value="1" {{
        old(
            'status',
            $category->status
        ) == 1
        ? 'selected'
        : ''
                                            }}>
                                            Active
                                        </option>

                                        <option value="0" {{
        old(
            'status',
            $category->status
        ) == 0
        ? 'selected'
        : ''
                                            }}>
                                            Inactive
                                        </option>

                                    </select>

                                </div>

                            </div>


                            <!-- Description -->

                            <div class="col-lg-12">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Description

                                    </label>

                                    <textarea name="description" class="form-control" rows="5"
                                        placeholder="Enter Medicine Category Description">{{ old(
        'description',
        $category->description
    ) }}</textarea>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- Category Image -->

                <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-photo me-2"></i>

                            Category Image

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row">

                            <div class="col-lg-6 col-md-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Change Category Image

                                    </label>

                                    <input type="file" name="image" id="image" class="form-control"
                                        accept=".jpg,.jpeg,.png,.webp">

                                    <small class="text-muted">

                                        JPG, JPEG, PNG, WEBP.
                                        Maximum size 5 MB.

                                    </small>

                                </div>

                            </div>


                            <!-- Image Preview -->

                            <div class="col-lg-6 col-md-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Current Image

                                    </label>

                                    <div>

                                        @if($category->image)

                                                                            <img src="{{ asset(
                                                $category->image
                                            ) }}" id="imagePreview" class="img-thumbnail" style="
                                                                                        width: 180px;
                                                                                        height: 180px;
                                                                                        object-fit: cover;
                                                                                    " alt="{{ $category->category_name }}">

                                        @else

                                                                            <img src="{{ asset(
                                                'assets/img/no-image.png'
                                            ) }}" id="imagePreview" class="img-thumbnail" style="
                                                                                        width: 180px;
                                                                                        height: 180px;
                                                                                        object-fit: cover;
                                                                                    " alt="No Image">

                                        @endif

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- Action Buttons -->

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route(
        'admin.medicine-categories.index'
    ) }}" class="btn btn-secondary">

                                Cancel

                            </a>


                            <button type="submit" class="btn btn-primary" id="submitBtn">

                                <span id="submitText">

                                    <i class="ti ti-device-floppy me-1"></i>

                                    Update Medicine Category

                                </span>


                                <span id="loadingText" class="d-none">

                                    <span class="spinner-border spinner-border-sm me-2"></span>

                                    Updating...

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
            | Category Name → Slug Preview
            |--------------------------------------------------------------------------
            */

            const categoryName =
                document.getElementById('category_name');

            const slug =
                document.getElementById('slug');


            if (categoryName && slug) {

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


            if (image && imagePreview) {

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
            | Submit Loading
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
