@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            <!-- Page Header -->

            <div class="page-header">

                <div class="page-title">

                    <h4>Add Ambulance</h4>

                    <h6>Create New Ambulance</h6>

                </div>

                <div class="page-btn">

                    <a href="{{ route('admin.ambulances.index') }}" class="btn btn-secondary">

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

            <form action="{{ route('admin.ambulances.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <!-- Ambulance Information -->

                <div class="card border-0 shadow-sm">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-ambulance me-2"></i>

                            Ambulance Information

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Hospital -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Hospital

                                    <span class="text-danger">*</span>

                                </label>

                                <select name="hospital_id" class="form-select">

                                    <option value="">

                                        Select Hospital

                                    </option>

                                    @foreach($hospitals as $hospital)

                                        <option value="{{ $hospital->id }}" {{ old('hospital_id') == $hospital->id ? 'selected' : '' }}>{{ $hospital->hospital_name }}</option>

                                    @endforeach

                                </select>

                            </div>

                            <!-- Ambulance Type -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Ambulance Type

                                    <span class="text-danger">*</span>

                                </label>

                                <select name="ambulance_type_id" class="form-select">

                                    <option value="">

                                        Select Ambulance Type

                                    </option>

                                    @foreach($ambulanceTypes as $ambulanceType)

                                        <option value="{{ $ambulanceType->id }}" {{ old('ambulance_type_id') == $ambulanceType->id ? 'selected' : '' }}>

                                            {{ $ambulanceType->ambulance_type_name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <!-- Ambulance Name -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Ambulance Name

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="text" name="ambulance_name" class="form-control"
                                    value="{{ old('ambulance_name') }}" placeholder="Enter Ambulance Name">

                            </div>

                            <!-- Ambulance Code -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Ambulance Code

                                </label>

                                <input type="text" class="form-control" value="Auto Generated" readonly>

                            </div>

                        </div>

                    </div>

                </div>
                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-photo me-2"></i>
                            Ambulance Image
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="text-center">

                            <img id="ambulancePreview" src="{{ asset('assets/img/no-image.png') }}"
                                class="img-thumbnail mb-3" style="width:180px;height:180px;object-fit:contain;">

                            <input type="file" name="image" id="image" class="form-control" accept="image/*">

                        </div>

                    </div>

                </div>
                <!-- Vehicle Information -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-car me-2"></i>

                            Vehicle Information

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Vehicle Number -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Vehicle Number

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="text" name="vehicle_number" class="form-control"
                                    value="{{ old('vehicle_number') }}" placeholder="Enter Vehicle Number">

                            </div>

                            <!-- Registration Number -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Registration Number

                                </label>

                                <input type="text" name="registration_number" class="form-control"
                                    value="{{ old('registration_number') }}" placeholder="Enter Registration Number">

                            </div>

                            <!-- Model -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Vehicle Model

                                </label>

                                <input type="text" name="model" class="form-control" value="{{ old('model') }}"
                                    placeholder="Enter Vehicle Model">

                            </div>

                            <!-- Manufacturing Year -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Manufacturing Year

                                </label>

                                <input type="number" min="1990" max="{{ date('Y') }}" name="manufacturing_year"
                                    class="form-control" value="{{ old('manufacturing_year') }}" placeholder="2024">

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Driver Information -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-user me-2"></i>

                            Driver Information

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Driver Name -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Driver Name

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="text" name="driver_name" class="form-control" value="{{ old('driver_name') }}"
                                    placeholder="Enter Driver Name">

                            </div>

                            <!-- Driver Mobile -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Driver Mobile

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="text" name="driver_mobile" class="form-control"
                                    value="{{ old('driver_mobile') }}" placeholder="Enter Mobile Number">

                            </div>

                            <!-- Driver License -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Driver License Number

                                </label>

                                <input type="text" name="driver_license_number" class="form-control"
                                    value="{{ old('driver_license_number') }}" placeholder="Enter License Number">

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Driver Photo -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-photo me-2"></i>

                            Driver Photo

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row justify-content-center">

                            <div class="col-lg-6">

                                <div class="border rounded-3 p-4 text-center">

                                    <h6 class="mb-3">

                                        Upload Driver Photo

                                    </h6>

                                    <img id="imagePreview" src="{{ asset('assets/img/no-image.png') }}"
                                        class="img-thumbnail mb-3" style="width:180px;height:180px;object-fit:contain;">

                                    <input type="file" id="driver_photo" name="driver_photo" class="form-control"
                                        accept="image/*">

                                    <small class="text-muted d-block mt-2">

                                        Supported formats: JPG, JPEG, PNG, WEBP

                                    </small>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
                <!-- Location Information -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-map-pin me-2"></i>

                            Location Information

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Current Location -->

                            <div class="col-lg-12 mb-3">

                                <label class="form-label">

                                    Current Location

                                </label>

                                <input type="text" name="current_location" class="form-control"
                                    value="{{ old('current_location') }}" placeholder="Enter Current Location">

                            </div>

                            <!-- Latitude -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Latitude

                                </label>

                                <input type="text" name="latitude" class="form-control" value="{{ old('latitude') }}"
                                    placeholder="17.385044">

                            </div>

                            <!-- Longitude -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Longitude

                                </label>

                                <input type="text" name="longitude" class="form-control" value="{{ old('longitude') }}"
                                    placeholder="78.486671">

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Fare Information -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-currency-rupee me-2"></i>

                            Fare Information

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Base Fare -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Base Fare

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="number" step="0.01" min="0" name="base_fare" class="form-control"
                                    value="{{ old('base_fare', 0) }}" placeholder="0.00">

                            </div>

                            <!-- Price Per KM -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Price Per KM

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="number" step="0.01" min="0" name="price_per_km" class="form-control"
                                    value="{{ old('price_per_km', 0) }}" placeholder="0.00">

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Ambulance Options -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-settings me-2"></i>

                            Ambulance Options

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Availability -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Availability

                                </label>

                                <select name="is_available" class="form-select">

                                    <option value="1" {{ old('is_available', 1) == 1 ? 'selected' : '' }}>

                                        Available

                                    </option>

                                    <option value="0" {{ old('is_available') == 0 ? 'selected' : '' }}>

                                        Not Available

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

                                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>

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

                            <a href="{{ route('admin.ambulances.index') }}" class="btn btn-light">

                                <i class="ti ti-arrow-left me-1"></i>

                                Cancel

                            </a>

                            <button type="submit" id="submitBtn" class="btn btn-primary">

                                <span id="submitText">

                                    <i class="ti ti-device-floppy me-1"></i>

                                    Save Ambulance

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

            // Driver Photo Preview

            const imageInput = document.getElementById('driver_photo');
            const preview = document.getElementById('imagePreview');

            if (imageInput) {

                imageInput.addEventListener('change', function (e) {

                    const file = e.target.files[0];

                    if (file) {

                        const reader = new FileReader();

                        reader.onload = function (event) {

                            preview.src = event.target.result;

                        };

                        reader.readAsDataURL(file);

                    }

                });

            }

            // Submit Loading

            const form = document.querySelector('form');
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const loadingText = document.getElementById('loadingText');

            if (form) {

                form.addEventListener('submit', function () {

                    submitBtn.disabled = true;

                    submitText.classList.add('d-none');

                    loadingText.classList.remove('d-none');

                });

            }

            // Select2 Initialization

            if ($.fn.select2) {

                $('select').select2({
                    width: '100%'
                });

            }

            // Feather Icons

            if (typeof feather !== 'undefined') {

                feather.replace();

            }

        });

    </script>

@endsection