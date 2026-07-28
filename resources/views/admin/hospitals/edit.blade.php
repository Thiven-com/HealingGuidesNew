@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">

    <div class="content">

        <!-- Page Header -->
        <div class="page-header">

            <div class="page-title">
                <h4>Edit Hospital</h4>
                <h6>Update Hospital Profile</h6>
            </div>

            <div class="page-btn">

                <a href="{{ route('admin.hospitals.index') }}" class="btn btn-secondary">

                    <i class="ti ti-arrow-left"></i>

                    Back

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

        <form action="{{ route('admin.hospitals.update',$hospital) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <!-- Basic Information -->

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

                                Hospital Name
                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="text"
                                name="hospital_name"
                                class="form-control"
                                value="{{ old('hospital_name',$hospital->hospital_name) }}"
                                placeholder="Enter Hospital Name">

                        </div>

                        <!-- Hospital Code -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Hospital Code

                            </label>

                            <input
                                type="text"
                                name="hospital_code"
                                class="form-control"
                                value="{{ old('hospital_code',$hospital->hospital_code) }}"
                                placeholder="HSP001">

                        </div>

                        <!-- Hospital Type -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Hospital Type

                            </label>

                            <select
                                class="form-select"
                                name="hospital_type">

                                <option value="">Select Type</option>

                                <option value="General"
                                    {{ old('hospital_type',$hospital->hospital_type)=='General' ? 'selected' : '' }}>
                                    General
                                </option>

                                <option value="Speciality"
                                    {{ old('hospital_type',$hospital->hospital_type)=='Speciality' ? 'selected' : '' }}>
                                    Speciality
                                </option>

                                <option value="Multi Speciality"
                                    {{ old('hospital_type',$hospital->hospital_type)=='Multi Speciality' ? 'selected' : '' }}>
                                    Multi Speciality
                                </option>

                                <option value="Clinic"
                                    {{ old('hospital_type',$hospital->hospital_type)=='Clinic' ? 'selected' : '' }}>
                                    Clinic
                                </option>

                                <option value="Medical College"
                                    {{ old('hospital_type',$hospital->hospital_type)=='Medical College' ? 'selected' : '' }}>
                                    Medical College
                                </option>

                                <option value="Diagnostic Center"
                                    {{ old('hospital_type',$hospital->hospital_type)=='Diagnostic Center' ? 'selected' : '' }}>
                                    Diagnostic Center
                                </option>

                            </select>

                        </div>

                        <!-- Registration -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Registration Number

                            </label>

                            <input
                                type="text"
                                name="registration_number"
                                class="form-control"
                                value="{{ old('registration_number',$hospital->registration_number) }}">

                        </div>

                        <!-- GST -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                GST Number

                            </label>

                            <input
                                type="text"
                                name="gst_number"
                                class="form-control"
                                value="{{ old('gst_number',$hospital->gst_number) }}">

                        </div>

                        <!-- PAN -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                PAN Number

                            </label>

                            <input
                                type="text"
                                name="pan_number"
                                class="form-control"
                                value="{{ old('pan_number',$hospital->pan_number) }}">

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

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email',$hospital->email) }}">

                        </div>

                        <!-- Mobile -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Mobile

                            </label>

                            <input
                                type="text"
                                name="mobile"
                                class="form-control"
                                value="{{ old('mobile',$hospital->mobile) }}">

                        </div>

                        <!-- Phone -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Phone

                            </label>

                            <input
                                type="text"
                                name="phone"
                                class="form-control"
                                value="{{ old('phone',$hospital->phone) }}">

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
                                Country
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="country"
                                class="form-control"
                                value="{{ old('country',$hospital->country) }}">

                        </div>

                        <!-- State -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                State
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="state"
                                class="form-control"
                                value="{{ old('state',$hospital->state) }}">

                        </div>

                        <!-- City -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                City
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="city"
                                class="form-control"
                                value="{{ old('city',$hospital->city) }}">

                        </div>

                        <!-- Pincode -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Pincode
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="pincode"
                                class="form-control"
                                value="{{ old('pincode',$hospital->pincode) }}">

                        </div>

                        <!-- Latitude -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Latitude
                            </label>

                            <input
                                type="text"
                                name="latitude"
                                class="form-control"
                                value="{{ old('latitude',$hospital->latitude) }}">

                        </div>

                        <!-- Longitude -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Longitude
                            </label>

                            <input
                                type="text"
                                name="longitude"
                                class="form-control"
                                value="{{ old('longitude',$hospital->longitude) }}">

                        </div>

                        <!-- Address -->

                        <div class="col-md-12 mb-3">

                            <label class="form-label">
                                Full Address
                                <span class="text-danger">*</span>
                            </label>

                            <textarea
                                name="address"
                                rows="4"
                                class="form-control">{{ old('address',$hospital->address) }}</textarea>

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

                            <input
                                type="time"
                                name="opening_time"
                                class="form-control"
                                value="{{ old('opening_time',$hospital->opening_time) }}">

                        </div>

                        <!-- Closing Time -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Closing Time

                            </label>

                            <input
                                type="time"
                                name="closing_time"
                                class="form-control"
                                value="{{ old('closing_time',$hospital->closing_time) }}">

                        </div>

                        <!-- Emergency -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Emergency Available

                            </label>

                            <select
                                class="form-select"
                                name="emergency_available">

                                <option value="1"
                                    {{ old('emergency_available',$hospital->emergency_available)==1 ? 'selected':'' }}>
                                    Yes
                                </option>

                                <option value="0"
                                    {{ old('emergency_available',$hospital->emergency_available)==0 ? 'selected':'' }}>
                                    No
                                </option>

                            </select>

                        </div>

                        <!-- Status -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Status

                            </label>

                            <select
                                class="form-select"
                                name="status">

                                <option value="1"
                                    {{ old('status',$hospital->status)==1 ? 'selected':'' }}>
                                    Active
                                </option>

                                <option value="0"
                                    {{ old('status',$hospital->status)==0 ? 'selected':'' }}>
                                    Inactive
                                </option>

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

                        <div class="col-md-6">

                            <label class="form-label">

                                Hospital Logo

                            </label>

                            @if($hospital->logo)

                                <div class="mb-3">

                                    <img
                                        src="{{ asset($hospital->logo) }}"
                                        class="img-thumbnail"
                                        id="logoPreview"
                                        style="height:120px;">

                                </div>

                            @else

                                <img
                                    id="logoPreview"
                                    class="img-thumbnail d-none"
                                    style="height:120px;">

                            @endif

                            <input
                                type="file"
                                class="form-control"
                                name="logo"
                                id="logo"
                                accept="image/*">

                        </div>

                        <!-- Banner -->

                        <div class="col-md-6">

                            <label class="form-label">

                                Hospital Banner

                            </label>

                            @if($hospital->banner)

                                <div class="mb-3">

                                    <img
                                        src="{{ asset($hospital->banner) }}"
                                        class="img-thumbnail w-100"
                                        id="bannerPreview"
                                        style="height:120px;object-fit:cover;">

                                </div>

                            @else

                                <img
                                    id="bannerPreview"
                                    class="img-thumbnail w-100 d-none"
                                    style="height:120px;object-fit:cover;">

                            @endif

                            <input
                                type="file"
                                class="form-control"
                                name="banner"
                                id="banner"
                                accept="image/*">

                        </div>

                    </div>

                </div>

            </div>
                        <!-- Action Buttons -->

            <div class="card border-0 shadow-sm mt-4">

                <div class="card-body">

                    <div class="d-flex justify-content-end gap-2">

                        <a href="{{ route('admin.hospitals.index') }}"
                           class="btn btn-secondary">

                            <i class="ti ti-arrow-left me-1"></i>

                            Cancel

                        </a>

                        <button type="submit"
                                id="submitBtn"
                                class="btn btn-primary">

                            <i class="ti ti-device-floppy me-1"></i>

                            Update Hospital

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

    // ===========================
    // Logo Preview
    // ===========================

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

    // ===========================
    // Banner Preview
    // ===========================

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

    // ===========================
    // Submit Loading
    // ===========================

    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');

    if (form) {

        form.addEventListener('submit', function () {

            submitBtn.disabled = true;

            submitBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2"></span>
                Updating...
            `;

        });

    }

});

</script>

@endpush