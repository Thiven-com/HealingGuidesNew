@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            <!-- Page Header -->

            <div class="page-header">

                <div class="page-title">

                    <h4>Edit Ambulance Type</h4>

                    <h6>Update Ambulance Type Details</h6>

                </div>

                <div class="page-btn">

                    <a href="{{ route('admin.ambulance-types.index') }}" class="btn btn-secondary">

                        <i class="ti ti-arrow-left me-1"></i>

                        Back

                    </a>

                </div>

            </div>

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

            <form action="{{ route('admin.ambulance-types.update', $ambulanceType->id) }}" method="POST"
                enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <!-- Basic Information -->

                <div class="card border-0 shadow-sm">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-ambulance me-2"></i>

                            Ambulance Type Information

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Ambulance Type Name -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Ambulance Type Name

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="text" id="ambulance_type_name" name="ambulance_type_name" class="form-control"
                                    value="{{ old('ambulance_type_name', $ambulanceType->ambulance_type_name) }}"
                                    placeholder="Enter Ambulance Type Name">

                            </div>

                            <!-- Ambulance Type Code -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Ambulance Type Code

                                </label>

                                <input type="text" class="form-control" value="{{ $ambulanceType->ambulance_type_code }}"
                                    readonly>

                            </div>

                            <!-- Slug -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Slug

                                </label>

                                <input type="text" id="slug" name="slug" class="form-control"
                                    value="{{ old('slug', $ambulanceType->slug) }}" readonly>

                            </div>

                        </div>

                    </div>

                </div>
                <!-- Ambulance Type Details -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-notes me-2"></i>

                            Ambulance Type Details

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Description -->

                            <div class="col-lg-12 mb-3">

                                <label class="form-label">

                                    Description

                                </label>

                                <textarea name="description" rows="5" class="form-control"
                                    placeholder="Enter Ambulance Type Description">{{ old('description', $ambulanceType->description) }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Ambulance Type Image -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-photo me-2"></i>

                            Ambulance Type Image

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row justify-content-center">

                            <div class="col-lg-6">

                                <div class="border rounded-3 p-4 text-center">

                                    <h6 class="mb-3">

                                        Current Image

                                    </h6>

                                    <img id="imagePreview"
                                        src="{{ $ambulanceType->image ? asset($ambulanceType->image) : asset('assets/img/no-image.png') }}"
                                        class="img-thumbnail mb-3" style="width:180px;height:180px;object-fit:contain;">

                                    <input type="file" id="image" name="image" class="form-control" accept="image/*">

                                    <small class="text-muted d-block mt-2">

                                        Leave empty to keep the existing image.

                                    </small>

                                    @if($ambulanceType->image)

                                        <div class="mt-3">

                                            <a href="{{ asset($ambulanceType->image) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">

                                                <i class="ti ti-eye me-1"></i>

                                                View Full Image

                                            </a>

                                        </div>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
                <!-- Ambulance Type Options -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-settings me-2"></i>

                            Ambulance Type Options

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Status -->

                            <div class="col-lg-4 mb-3">

                                <label class="form-label">

                                    Status

                                </label>

                                <select name="status" class="form-select">

                                    <option value="1" {{ old('status', $ambulanceType->status) == 1 ? 'selected' : '' }}>

                                        Active

                                    </option>

                                    <option value="0" {{ old('status', $ambulanceType->status) == 0 ? 'selected' : '' }}>

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

                            <a href="{{ route('admin.ambulance-types.index') }}" class="btn btn-light">

                                <i class="ti ti-arrow-left me-1"></i>

                                Cancel

                            </a>

                            <button type="submit" id="submitBtn" class="btn btn-primary">

                                <span id="submitText">

                                    <i class="ti ti-device-floppy me-1"></i>

                                    Update Ambulance Type

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

        document.addEventListener("DOMContentLoaded", function () {

            // Auto Slug Generator

            const ambulanceTypeName = document.getElementById('ambulance_type_name');
            const slug = document.getElementById('slug');

            if (ambulanceTypeName) {

                ambulanceTypeName.addEventListener('input', function () {

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

                    if (this.files && this.files[0]) {

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