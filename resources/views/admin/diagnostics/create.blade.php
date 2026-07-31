@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            <!-- Page Header -->
            <div class="page-header">

                <div class="page-title">

                    <h4>Add Diagnostic</h4>

                    <h6>Create New Diagnostic Center</h6>

                </div>

                <div class="page-btn">

                    <a href="{{ route('admin.diagnostics.index') }}" class="btn btn-secondary">

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


            <form action="{{ route('admin.diagnostics.store') }}" method="POST" enctype="multipart/form-data">

                @csrf


                <!-- Basic Information -->

                <div class="card border-0 shadow-sm">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-building-hospital me-2"></i>

                            Diagnostic Information

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Diagnostic Name -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Diagnostic Name

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="text" name="diagnostic_name" id="diagnostic_name" class="form-control"
                                    value="{{ old('diagnostic_name') }}" placeholder="Enter Diagnostic Name">

                            </div>

                            <!-- Diagnostic Code -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Diagnostic Code

                                </label>

                                <input type="text" class="form-control" placeholder="Auto Generated" readonly>

                            </div>

                            <!-- Slug -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Slug

                                </label>

                                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}"
                                    placeholder="Auto Generated" readonly>

                            </div>

                            <!-- Registration Number -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Registration Number

                                </label>

                                <input type="text" name="registration_number" class="form-control"
                                    value="{{ old('registration_number') }}" placeholder="Registration Number">

                            </div>

                        </div>

                    </div>

                </div>
                <!-- Contact Information -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-phone me-2"></i>

                            Contact Information

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Email -->

                            <div class="col-lg-4 mb-3">

                                <label class="form-label">

                                    Email

                                </label>

                                <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                    placeholder="Enter Email Address">

                            </div>

                            <!-- Mobile -->

                            <div class="col-lg-4 mb-3">

                                <label class="form-label">

                                    Mobile Number

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}"
                                    placeholder="Enter Mobile Number">

                            </div>

                            <!-- Phone -->

                            <div class="col-lg-4 mb-3">

                                <label class="form-label">

                                    Phone Number

                                </label>

                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}"
                                    placeholder="Enter Phone Number">

                            </div>

                        </div>

                    </div>

                </div>


                <!-- Address Information -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-map-pin me-2"></i>

                            Address Information

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Address -->

                            <div class="col-lg-12 mb-3">

                                <label class="form-label">

                                    Address

                                </label>

                                <textarea name="address" rows="4" class="form-control"
                                    placeholder="Enter Complete Address">{{ old('address') }}</textarea>

                            </div>

                            <!-- Country -->

                            <div class="col-lg-3 mb-3">

                                <label class="form-label">

                                    Country

                                </label>

                                <input type="text" name="country" class="form-control" value="{{ old('country') }}"
                                    placeholder="Country">

                            </div>

                            <!-- State -->

                            <div class="col-lg-3 mb-3">

                                <label class="form-label">

                                    State

                                </label>

                                <input type="text" name="state" class="form-control" value="{{ old('state') }}"
                                    placeholder="State">

                            </div>

                            <!-- City -->

                            <div class="col-lg-3 mb-3">

                                <label class="form-label">

                                    City

                                </label>

                                <input type="text" name="city" class="form-control" value="{{ old('city') }}"
                                    placeholder="City">

                            </div>

                            <!-- Pincode -->

                            <div class="col-lg-3 mb-3">

                                <label class="form-label">

                                    Pincode

                                </label>

                                <input type="text" name="pincode" class="form-control" value="{{ old('pincode') }}"
                                    placeholder="Pincode">

                            </div>

                        </div>

                    </div>

                </div>
                <!-- Location & Working Hours -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-map-search me-2"></i>

                            Location & Working Hours

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Latitude -->

                            <div class="col-lg-3 mb-3">

                                <label class="form-label">

                                    Latitude

                                </label>

                                <input type="text" name="latitude" class="form-control" value="{{ old('latitude') }}"
                                    placeholder="Latitude">

                            </div>

                            <!-- Longitude -->

                            <div class="col-lg-3 mb-3">

                                <label class="form-label">

                                    Longitude

                                </label>

                                <input type="text" name="longitude" class="form-control" value="{{ old('longitude') }}"
                                    placeholder="Longitude">

                            </div>

                            <!-- Opening Time -->

                            <div class="col-lg-3 mb-3">

                                <label class="form-label">

                                    Opening Time

                                </label>

                                <input type="time" name="opening_time" class="form-control"
                                    value="{{ old('opening_time') }}">

                            </div>

                            <!-- Closing Time -->

                            <div class="col-lg-3 mb-3">

                                <label class="form-label">

                                    Closing Time

                                </label>

                                <input type="time" name="closing_time" class="form-control"
                                    value="{{ old('closing_time') }}">

                            </div>

                            <!-- Home Collection -->

                            <div class="col-lg-6 mb-3">

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

                            <!-- Logo Upload -->

                            <div class="col-lg-6">

                                <div class="border rounded-3 p-4 text-center h-100">

                                    <h6 class="mb-3">

                                        Diagnostic Logo

                                    </h6>

                                    <img id="logoPreview" src="{{ asset('assets/img/no-image.png') }}"
                                        class="img-thumbnail mb-3" style="width:140px;height:140px;object-fit:contain;">

                                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*">

                                    <small class="text-muted d-block mt-2">

                                        Supported: JPG, PNG, SVG, WEBP

                                    </small>

                                </div>

                            </div>

                            <!-- Banner Upload -->

                            <div class="col-lg-6">

                                <div class="border rounded-3 p-4 text-center h-100">

                                    <h6 class="mb-3">

                                        Diagnostic Banner

                                    </h6>

                                    <img id="bannerPreview" src="{{ asset('assets/img/no-image.png') }}"
                                        class="img-thumbnail mb-3" style="width:250px;height:150px;object-fit:cover;">

                                    <input type="file" class="form-control" id="banner" name="banner" accept="image/*">

                                    <small class="text-muted d-block mt-2">

                                        Recommended Size: 1200 × 600 px

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

                            <a href="{{ route('admin.diagnostics.index') }}" class="btn btn-light">

                                <i class="ti ti-arrow-left me-1"></i>

                                Cancel

                            </a>

                            <button type="submit" id="submitBtn" class="btn btn-primary">

                                <span id="submitText">

                                    <i class="ti ti-device-floppy me-1"></i>

                                    Save Diagnostic

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

            $('#diagnostic_name').on('keyup change', function () {

                let slug = $(this).val()
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');

                $('#slug').val(slug);

            });


            // Logo Preview

            $('#logo').change(function () {

                const file = this.files[0];

                if (file) {

                    const reader = new FileReader();

                    reader.onload = function (e) {

                        $('#logoPreview').attr('src', e.target.result);

                    }

                    reader.readAsDataURL(file);

                }

            });


            // Banner Preview

            $('#banner').change(function () {

                const file = this.files[0];

                if (file) {

                    const reader = new FileReader();

                    reader.onload = function (e) {

                        $('#bannerPreview').attr('src', e.target.result);

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