@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            {{-- PAGE HEADER --}}
            <div class="page-header">
                <div class="page-title">
                    <h4>Edit Ambulance</h4>
                    <h6>Update ambulance and driver information</h6>
                </div>

                <div class="page-btn">
                    <a href="{{ route('hospital.ambulances.index') }}" class="btn btn-light">
                        <i class="ti ti-arrow-left me-1"></i>
                        Back
                    </a>
                </div>
            </div>


            {{-- ERRORS --}}
            @if($errors->any())

                <div class="alert alert-danger alert-dismissible fade show">

                    <strong>Please fix the following:</strong>

                    <ul class="mb-0 mt-2 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            <form method="POST" action="{{ route('hospital.ambulances.update', $ambulance->id) }}"
                enctype="multipart/form-data">

                @csrf
                @method('PUT')


                {{-- BASIC INFORMATION --}}
                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Ambulance Information
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            {{-- TYPE --}}
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

                                                <option value="{{ $type->id }}" {{ old('ambulance_type_id',$ambulance->ambulance_type_id) == $type->id ? 'selected' : '' }}>
                                                    {{ $type->ambulance_type_name ?? 'Ambulance Type' }}
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


                            {{-- NAME --}}
                            <div class="col-md-6">
                                <div class="mb-3">

                                    <label class="form-label">
                                        Ambulance Name
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text" name="ambulance_name"
                                        class="form-control @error('ambulance_name') is-invalid @enderror" value="{{ old(
        'ambulance_name',
        $ambulance->ambulance_name
    ) }}" required>

                                    @error('ambulance_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>
                            </div>


                            {{-- CODE --}}
                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Ambulance Code
                                    </label>

                                    <input type="text" class="form-control" value="{{ $ambulance->ambulance_code }}"
                                        readonly>

                                    <small class="text-muted">
                                        Ambulance code cannot be changed.
                                    </small>

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
                                        class="form-control @error('vehicle_number') is-invalid @enderror" value="{{ old(
        'vehicle_number',
        $ambulance->vehicle_number
    ) }}" style="text-transform:uppercase;" required>

                                    @error('vehicle_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>


                            {{-- REGISTRATION --}}
                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Registration Number
                                    </label>

                                    <input type="text" name="registration_number"
                                        class="form-control @error('registration_number') is-invalid @enderror" value="{{ old(
        'registration_number',
        $ambulance->registration_number
    ) }}">

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

                                    <input type="text" name="model" class="form-control" value="{{ old(
        'model',
        $ambulance->model
    ) }}">

                                </div>

                            </div>


                            {{-- YEAR --}}
                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Manufacturing Year
                                    </label>

                                    <input type="number" name="manufacturing_year" class="form-control" value="{{ old(
        'manufacturing_year',
        $ambulance->manufacturing_year
    ) }}" min="1900" max="{{ date('Y') }}">

                                </div>

                            </div>


                            {{-- IMAGE --}}
                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Ambulance Image
                                    </label>

                                    <input type="file" name="image" id="ambulanceImage"
                                        class="form-control @error('image') is-invalid @enderror"
                                        accept="image/jpeg,image/png,image/webp">

                                    @error('image')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror


                                    <div class="mt-3">

                                        <img id="imagePreview" src="{{ $ambulance->image
        ? asset($ambulance->image)
        : asset('build/img/no-image.png') }}" alt="Ambulance" style="
                                                width:130px;
                                                height:95px;
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
                                        class="form-control @error('driver_name') is-invalid @enderror" value="{{ old(
        'driver_name',
        $ambulance->driver_name
    ) }}" required>

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

                                        <input type="text" name="driver_mobile" id="driverMobile"
                                            class="form-control @error('driver_mobile') is-invalid @enderror" value="{{ old(
        'driver_mobile',
        $ambulance->driver_mobile
    ) }}" maxlength="10" required>

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

                                    <input type="text" name="driver_license_number" class="form-control" value="{{ old(
        'driver_license_number',
        $ambulance->driver_license_number
    ) }}">

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

                                    @error('driver_photo')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror


                                    <div class="mt-3">

                                        @if($ambulance->driver_photo)

                                            <img id="driverPreview" src="{{ asset($ambulance->driver_photo) }}" alt="Driver"
                                                style="
                                                        width:80px;
                                                        height:80px;
                                                        object-fit:cover;
                                                        border-radius:50%;
                                                        border:1px solid #ddd;
                                                     ">

                                        @else

                                            <img id="driverPreview" src="{{ asset('build/img/profiles/avatar-01.jpg') }}"
                                                alt="Driver" style="
                                                        width:80px;
                                                        height:80px;
                                                        object-fit:cover;
                                                        border-radius:50%;
                                                        border:1px solid #ddd;
                                                     ">

                                        @endif

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
                                            class="form-control @error('base_fare') is-invalid @enderror" value="{{ old(
        'base_fare',
        $ambulance->base_fare
    ) }}" min="0" step="0.01" required>

                                    </div>

                                    @error('base_fare')
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>


                            {{-- PER KM --}}
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
                                            class="form-control @error('price_per_km') is-invalid @enderror" value="{{ old(
        'price_per_km',
        $ambulance->price_per_km
    ) }}" min="0" step="0.01" required>

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


                {{-- AVAILABILITY --}}
                <div class="card">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Availability
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="d-flex align-items-center justify-content-between">

                            <div>

                                <h6 class="mb-1">
                                    Ambulance Availability
                                </h6>

                                <p class="text-muted mb-0">
                                    Allow this ambulance to receive new booking requests.
                                </p>

                            </div>


                            <div class="form-check form-switch">

                                <input type="checkbox" class="form-check-input" name="is_available" value="1"
                                    id="availability" {{ old(
        'is_available',
        $ambulance->is_available
    ) ? 'checked' : '' }}>

                                <label class="form-check-label" for="availability">

                                    Available

                                </label>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ACTIONS --}}
                <div class="card">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <a href="{{ route(
        'hospital.ambulances.show',
        $ambulance->id
    ) }}" class="btn btn-light">

                                <i class="ti ti-eye me-1"></i>
                                View Ambulance

                            </a>


                            <div class="d-flex gap-2">

                                <a href="{{ route(
        'hospital.ambulances.index'
    ) }}" class="btn btn-light">

                                    Cancel

                                </a>

                                <button type="submit" class="btn btn-primary">

                                    <i class="ti ti-device-floppy me-1"></i>

                                    Update Ambulance

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </form>

        </div>
    </div>


    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                /*
                |--------------------------------------------------------------------------
                | Ambulance Image Preview
                |--------------------------------------------------------------------------
                */

                const ambulanceImage =
                    document.getElementById(
                        'ambulanceImage'
                    );

                if (ambulanceImage) {

                    ambulanceImage.addEventListener(
                        'change',
                        function () {

                            const file =
                                this.files[0];

                            if (!file) {
                                return;
                            }

                            const reader =
                                new FileReader();

                            reader.onload =
                                function (e) {

                                    document.getElementById(
                                        'imagePreview'
                                    ).src =
                                        e.target.result;

                                };

                            reader.readAsDataURL(file);

                        }
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | Driver Image Preview
                |--------------------------------------------------------------------------
                */

                const driverPhoto =
                    document.getElementById(
                        'driverPhoto'
                    );

                if (driverPhoto) {

                    driverPhoto.addEventListener(
                        'change',
                        function () {

                            const file =
                                this.files[0];

                            if (!file) {
                                return;
                            }

                            const reader =
                                new FileReader();

                            reader.onload =
                                function (e) {

                                    document.getElementById(
                                        'driverPreview'
                                    ).src =
                                        e.target.result;

                                };

                            reader.readAsDataURL(file);

                        }
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | Mobile Number
                |--------------------------------------------------------------------------
                */

                const driverMobile =
                    document.getElementById(
                        'driverMobile'
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

            }
        );

    </script>

@endsection