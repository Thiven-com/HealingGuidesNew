@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            {{-- PAGE HEADER --}}
            <div class="page-header">
                <div class="page-title">
                    <h4>Add Ambulance</h4>
                    <h6>Add a new ambulance to your hospital</h6>
                </div>

                <div class="page-btn">
                    <a href="{{ route('hospital.ambulances.index') }}" class="btn btn-light">
                        <i class="ti ti-arrow-left me-1"></i>
                        Back
                    </a>
                </div>
            </div>


            {{-- VALIDATION ERRORS --}}
            @if($errors->any())

                <div class="alert alert-danger alert-dismissible fade show">

                    <div class="fw-semibold mb-1">
                        Please fix the following errors:
                    </div>

                    <ul class="mb-0 ps-3">

                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            <form method="POST" action="{{ route('hospital.ambulances.store') }}" enctype="multipart/form-data">

                @csrf


                {{-- BASIC INFORMATION --}}
                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Ambulance Information
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            {{-- AMBULANCE TYPE --}}
                            <div class="col-md-6">
                                <div class="mb-3">

                                    <label class="form-label">
                                        Ambulance Type
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="ambulance_type_id"
                                        class="form-select @error('ambulance_type_id') is-invalid @enderror" required>

                                        <option value="">
                                            Select Ambulance Type
                                        </option>

                                        @foreach($ambulanceTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('ambulance_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->ambulance_type_name}}
                                            </option>
                                        @endforeach

                                    </select>

                                    @error('ambulance_type_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>
                            </div>


                            {{-- AMBULANCE NAME --}}
                            <div class="col-md-6">
                                <div class="mb-3">

                                    <label class="form-label">
                                        Ambulance Name
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text" name="ambulance_name"
                                        class="form-control @error('ambulance_name') is-invalid @enderror"
                                        value="{{ old('ambulance_name') }}" placeholder="Example: Emergency Ambulance"
                                        required>

                                    @error('ambulance_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>
                            </div>


                            {{-- VEHICLE NUMBER --}}
                            <div class="col-md-6">
                                <div class="mb-3">

                                    <label class="form-label">
                                        Vehicle Number
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text" name="vehicle_number"
                                        class="form-control @error('vehicle_number') is-invalid @enderror"
                                        value="{{ old('vehicle_number') }}" placeholder="Example: TS09AB1234"
                                        style="text-transform:uppercase;" required>

                                    @error('vehicle_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>
                            </div>


                            {{-- REGISTRATION NUMBER --}}
                            <div class="col-md-6">
                                <div class="mb-3">

                                    <label class="form-label">
                                        Registration Number
                                    </label>

                                    <input type="text" name="registration_number"
                                        class="form-control @error('registration_number') is-invalid @enderror"
                                        value="{{ old('registration_number') }}" placeholder="Registration number">

                                    @error('registration_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>
                            </div>


                            {{-- MODEL --}}
                            <div class="col-md-6">
                                <div class="mb-3">

                                    <label class="form-label">
                                        Vehicle Model
                                    </label>

                                    <input type="text" name="model" class="form-control" value="{{ old('model') }}"
                                        placeholder="Example: Force Traveller">

                                </div>
                            </div>


                            {{-- MANUFACTURING YEAR --}}
                            <div class="col-md-6">
                                <div class="mb-3">

                                    <label class="form-label">
                                        Manufacturing Year
                                    </label>

                                    <input type="number" name="manufacturing_year" class="form-control"
                                        value="{{ old('manufacturing_year') }}" min="1900" max="{{ date('Y') }}"
                                        placeholder="{{ date('Y') }}">

                                </div>
                            </div>


                            {{-- AMBULANCE IMAGE --}}
                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Ambulance Image
                                    </label>

                                    <input type="file" name="image" id="ambulanceImage"
                                        class="form-control @error('image') is-invalid @enderror"
                                        accept="image/jpeg,image/png,image/webp">

                                    <small class="text-muted">
                                        JPG, PNG or WEBP. Maximum 5 MB.
                                    </small>

                                    @error('image')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <div class="mt-3" id="imagePreviewWrapper" style="display:none;">

                                        <img id="imagePreview" src="" alt="Preview" style="
                                                    width:120px;
                                                    height:90px;
                                                    object-fit:cover;
                                                    border-radius:8px;
                                                    border:1px solid #ddd;
                                                 ">

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- DRIVER INFORMATION --}}
                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Driver Information
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            {{-- DRIVER NAME --}}
                            <div class="col-md-6">
                                <div class="mb-3">

                                    <label class="form-label">
                                        Driver Name
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text" name="driver_name"
                                        class="form-control @error('driver_name') is-invalid @enderror"
                                        value="{{ old('driver_name') }}" placeholder="Enter driver name" required>

                                    @error('driver_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>
                            </div>


                            {{-- DRIVER MOBILE --}}
                            <div class="col-md-6">
                                <div class="mb-3">

                                    <label class="form-label">
                                        Driver Mobile
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            +91
                                        </span>

                                        <input type="text" name="driver_mobile"
                                            class="form-control @error('driver_mobile') is-invalid @enderror"
                                            value="{{ old('driver_mobile') }}" maxlength="10" inputmode="numeric"
                                            placeholder="10 digit mobile number" required>

                                    </div>

                                    @error('driver_mobile')
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>
                            </div>


                            {{-- LICENSE --}}
                            <div class="col-md-6">
                                <div class="mb-3">

                                    <label class="form-label">
                                        Driving License Number
                                    </label>

                                    <input type="text" name="driver_license_number" class="form-control"
                                        value="{{ old('driver_license_number') }}" placeholder="Enter license number">

                                </div>
                            </div>


                            {{-- DRIVER PHOTO --}}
                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Driver Photo
                                    </label>

                                    <input type="file" name="driver_photo" id="driverPhoto"
                                        class="form-control @error('driver_photo') is-invalid @enderror"
                                        accept="image/jpeg,image/png,image/webp">

                                    <small class="text-muted">
                                        JPG, PNG or WEBP. Maximum 5 MB.
                                    </small>

                                    @error('driver_photo')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <div class="mt-3" id="driverPreviewWrapper" style="display:none;">

                                        <img id="driverPreview" src="" alt="Driver Preview" style="
                                                    width:80px;
                                                    height:80px;
                                                    object-fit:cover;
                                                    border-radius:50%;
                                                    border:1px solid #ddd;
                                                 ">

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- FARE --}}
                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Fare Configuration
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            {{-- BASE FARE --}}
                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Base Fare
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            ₹
                                        </span>

                                        <input type="number" name="base_fare"
                                            class="form-control @error('base_fare') is-invalid @enderror"
                                            value="{{ old('base_fare', 0) }}" min="0" step="0.01" placeholder="0.00"
                                            required>

                                    </div>

                                    @error('base_fare')
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>


                            {{-- PRICE PER KM --}}
                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Price Per KM
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            ₹
                                        </span>

                                        <input type="number" name="price_per_km"
                                            class="form-control @error('price_per_km') is-invalid @enderror"
                                            value="{{ old('price_per_km', 0) }}" min="0" step="0.01" placeholder="0.00"
                                            required>

                                    </div>

                                    @error('price_per_km')
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- LOCATION --}}
                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Initial Location
                        </h5>

                        <p class="text-muted mb-0 mt-1">
                            You can leave this empty. The ambulance app can update
                            the live location later.
                        </p>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-12">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Current Location
                                    </label>

                                    <input type="text" name="current_location" class="form-control"
                                        value="{{ old('current_location') }}" placeholder="Example: Hyderabad">

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ACTIONS --}}
                <div class="card">

                    <div class="card-body">

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('hospital.ambulances.index') }}" class="btn btn-light">

                                Cancel

                            </a>

                            <button type="submit" class="btn btn-primary">

                                <i class="ti ti-device-floppy me-1"></i>

                                Save Ambulance

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
            | Ambulance Image Preview
            |--------------------------------------------------------------------------
            */

            const ambulanceImage =
                document.getElementById('ambulanceImage');

            if (ambulanceImage) {

                ambulanceImage.addEventListener('change', function () {

                    const file = this.files[0];

                    if (!file) {
                        return;
                    }

                    const reader =
                        new FileReader();

                    reader.onload = function (e) {

                        document.getElementById(
                            'imagePreview'
                        ).src = e.target.result;

                        document.getElementById(
                            'imagePreviewWrapper'
                        ).style.display = 'block';

                    };

                    reader.readAsDataURL(file);

                });

            }


            /*
            |--------------------------------------------------------------------------
            | Driver Photo Preview
            |--------------------------------------------------------------------------
            */

            const driverPhoto =
                document.getElementById('driverPhoto');

            if (driverPhoto) {

                driverPhoto.addEventListener('change', function () {

                    const file = this.files[0];

                    if (!file) {
                        return;
                    }

                    const reader =
                        new FileReader();

                    reader.onload = function (e) {

                        document.getElementById(
                            'driverPreview'
                        ).src = e.target.result;

                        document.getElementById(
                            'driverPreviewWrapper'
                        ).style.display = 'block';

                    };

                    reader.readAsDataURL(file);

                });

            }


            /*
            |--------------------------------------------------------------------------
            | Driver Mobile
            |--------------------------------------------------------------------------
            */

            const driverMobile =
                document.querySelector(
                    'input[name="driver_mobile"]'
                );

            if (driverMobile) {

                driverMobile.addEventListener(
                    'input',
                    function () {

                        this.value =
                            this.value
                                .replace(/\D/g, '')
                                .substring(0, 10);

                    }
                );

            }

        });

    </script>

@endsection