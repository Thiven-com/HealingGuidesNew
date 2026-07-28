@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            <!-- Page Header -->
            <div class="page-header">

                <div class="page-title">
                    <h4>Add Hospital</h4>
                    <h6>Create New Hospital Profile</h6>
                </div>

                <div class="page-btn">
                    <a href="{{ route('admin.hospitals.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left"></i> Back
                    </a>
                </div>

            </div>

            <!-- Validation Errors -->

            @if ($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <!-- Form -->

            <form action="{{ route('admin.hospitals.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="card border-0 shadow-sm">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-building-hospital me-2"></i>

                            Basic Information

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Hospital Name -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Hospital Name <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="hospital_name" class="form-control"
                                    value="{{ old('hospital_name') }}" placeholder="Enter Hospital Name">

                            </div>
                            <!-- Hospital Type -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Hospital Type

                                </label>

                                <select class="form-select" name="hospital_type">

                                    <option value="">Select Hospital Type</option>

                                    <option value="General Hospital" {{ old('hospital_type') == 'General Hospital'
        ? 'selected' : '' }}>
                                        General Hospital
                                    </option>

                                    <option value="Multi Speciality" {{ old('hospital_type') == 'Multi Speciality'
        ? 'selected' : '' }}>
                                        Multi Speciality
                                    </option>

                                    <option value="Super Speciality" {{ old('hospital_type') == 'Super Speciality'
        ? 'selected' : '' }}>
                                        Super Speciality
                                    </option>

                                    <option value="Government Hospital" {{ old('hospital_type') == 'Government Hospital'
        ? 'selected' : '' }}>
                                        Government Hospital
                                    </option>

                                    <option value="Private Hospital" {{ old('hospital_type') == 'Private Hospital'
        ? 'selected' : '' }}>
                                        Private Hospital
                                    </option>

                                    <option value="Medical College Hospital" {{
        old('hospital_type') == 'Medical College Hospital' ? 'selected' : '' }}>
                                        Medical College Hospital
                                    </option>

                                    <option value="Clinic" {{ old('hospital_type') == 'Clinic' ? 'selected' : '' }}>
                                        Clinic
                                    </option>

                                    <option value="Diagnostic Center" {{ old('hospital_type') == 'Diagnostic Center'
        ? 'selected' : '' }}>
                                        Diagnostic Center
                                    </option>

                                    <option value="Nursing Home" {{ old('hospital_type') == 'Nursing Home' ? 'selected' : ''
                                                }}>
                                        Nursing Home
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Hospital Specializations
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="row">

                                    @foreach($specializations as $specialization)

                                        <div class="col-md-4 col-lg-3 mb-2">

                                            <div class="form-check">

                                                <input class="form-check-input" type="checkbox" name="specializations[]"
                                                    id="specialization{{ $specialization->id }}"
                                                    value="{{ $specialization->id }}" {{ in_array($specialization->id, old('specializations', [])) ? 'checked' : '' }}>

                                                <label class="form-check-label" for="specialization{{ $specialization->id }}">

                                                    {{ $specialization->specialization_name }}

                                                </label>

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                                @error('specializations')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <!-- Registration -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Registration Number

                                </label>

                                <input type="text" name="registration_number" class="form-control"
                                    value="{{ old('registration_number') }}">

                            </div>

                            <!-- GST -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    GST Number

                                </label>

                                <input type="text" name="gst_number" class="form-control" value="{{ old('gst_number') }}">

                            </div>

                            <!-- PAN -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    PAN Number

                                </label>

                                <input type="text" name="pan_number" class="form-control" value="{{ old('pan_number') }}">

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

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Email

                                </label>

                                <input type="email" name="email" class="form-control" value="{{ old('email') }}">

                            </div>

                            <!-- Mobile -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Mobile

                                </label>

                                <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}">

                            </div>

                            <!-- Phone -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Phone

                                </label>

                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">

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

                            <!-- Country -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Country <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="country" class="form-control" value="{{ old('country') }}"
                                    placeholder="India">

                            </div>

                            <!-- State -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    State <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="state" class="form-control" value="{{ old('state') }}"
                                    placeholder="Telangana">

                            </div>

                            <!-- City -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    City <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="city" class="form-control" value="{{ old('city') }}"
                                    placeholder="Hyderabad">

                            </div>

                            <!-- Pincode -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Pincode <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="pincode" class="form-control" value="{{ old('pincode') }}">

                            </div>

                            <!-- Latitude -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Latitude
                                </label>

                                <input type="text" name="latitude" class="form-control" value="{{ old('latitude') }}"
                                    placeholder="17.385044">

                            </div>

                            <!-- Longitude -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Longitude
                                </label>

                                <input type="text" name="longitude" class="form-control" value="{{ old('longitude') }}"
                                    placeholder="78.486671">

                            </div>

                            <!-- Address -->

                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Full Address <span class="text-danger">*</span>
                                </label>

                                <textarea class="form-control" rows="4" name="address">{{ old('address') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Working Hours -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-clock-hour-4 me-2"></i>

                            Working Hours

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Opening Time -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Opening Time
                                </label>

                                <input type="time" class="form-control" name="opening_time"
                                    value="{{ old('opening_time') }}">

                            </div>

                            <!-- Closing Time -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Closing Time
                                </label>

                                <input type="time" class="form-control" name="closing_time"
                                    value="{{ old('closing_time') }}">

                            </div>

                            <!-- Emergency -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Emergency Available

                                </label>

                                <select class="form-select" name="emergency_available">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                            <!-- Status -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Status

                                </label>

                                <select class="form-select" name="status">

                                    <option value="1">Active</option>

                                    <option value="0">Inactive</option>

                                </select>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Hospital Images -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-photo me-2"></i>

                            Hospital Images

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Logo -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Hospital Logo
                                </label>

                                <input type="file" name="logo" id="logo" class="form-control" accept="image/*">

                                <img id="logoPreview" class="img-thumbnail mt-3 d-none" style="max-height:120px;">

                            </div>

                            <!-- Banner -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Hospital Banner
                                </label>

                                <input type="file" name="banner" id="banner" class="form-control" accept="image/*">

                                <img id="bannerPreview" class="img-thumbnail mt-3 d-none"
                                    style="max-height:120px; width:100%; object-fit:cover;">

                            </div>

                        </div>

                    </div>

                </div>
                <!-- Action Buttons -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('admin.hospitals.index') }}" class="btn btn-secondary">

                                <i class="ti ti-arrow-left me-1"></i>

                                Cancel

                            </a>

                            <button type="submit" id="submitBtn" class="btn btn-primary">

                                <i class="ti ti-device-floppy me-1"></i>

                                Save Hospital

                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

@endsection


@push('scripts')

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            // Logo Preview

            const logoInput = document.getElementById('logo');
            const logoPreview = document.getElementById('logoPreview');

            if (logoInput) {

                logoInput.addEventListener('change', function (e) {

                    const file = e.target.files[0];

                    if (file) {

                        logoPreview.src = URL.createObjectURL(file);

                        logoPreview.classList.remove('d-none');

                    }

                });

            }

            // Banner Preview

            const bannerInput = document.getElementById('banner');
            const bannerPreview = document.getElementById('bannerPreview');

            if (bannerInput) {

                bannerInput.addEventListener('change', function (e) {

                    const file = e.target.files[0];

                    if (file) {

                        bannerPreview.src = URL.createObjectURL(file);

                        bannerPreview.classList.remove('d-none');

                    }

                });

            }

            // Loading Button

            const form = document.querySelector('form');
            const submitBtn = document.getElementById('submitBtn');

            form.addEventListener('submit', function () {

                submitBtn.disabled = true;

                submitBtn.innerHTML = `
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Saving...
                                `;

            });

        });

    </script>

@endpush