@extends('layout.mainlayout')

@section('content')


<div class="page-wrapper">

<div class="content">

    {{-- =========================
         PAGE HEADER
    ========================== --}}
    <div class="page-header">

        <div class="page-title">

            <h4>Edit Hospital Profile</h4>

            <h6>
                Update Hospital Profile
            </h6>

        </div>

        <div class="page-btn">

            <a href="{{ url()->previous() }}"
               class="btn btn-secondary">

                <i class="ti ti-arrow-left me-1"></i>

                Back

            </a>

        </div>

    </div>


    {{-- =========================
         VALIDATION ERRORS
    ========================== --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================
         SUCCESS MESSAGE
    ========================== --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- =========================
         UPDATE FORM
    ========================== --}}
    <form action="{{ route('hospital.hospitalprofile.update') }}"
      method="POST"
      enctype="multipart/form-data"
      id="hospitalEditForm">

    @csrf
    @method('PUT')


        {{-- =========================
             BASIC INFORMATION
        ========================== --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="ti ti-building-hospital me-2"></i>

                    Basic Information

                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Hospital Name --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Hospital Name
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="hospital_name"
                               class="form-control"
                               value="{{ old('hospital_name', $hospital->hospital_name) }}"
                               placeholder="Enter Hospital Name">

                        @error('hospital_name')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>


                    {{-- Hospital Type --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Hospital Type
                        </label>

                        <select class="form-select"
                                name="hospital_type">

                            <option value="">
                                Select Hospital Type
                            </option>

                            @php
                                $hospitalTypes = [
                                    'General Hospital',
                                    'Multi Speciality',
                                    'Super Speciality',
                                    'Government Hospital',
                                    'Private Hospital',
                                    'Medical College Hospital',
                                    'Clinic',
                                    'Diagnostic Center',
                                    'Nursing Home'
                                ];
                            @endphp

                            @foreach($hospitalTypes as $type)

                                <option value="{{ $type }}"
                                    {{ old('hospital_type', $hospital->hospital_type) == $type ? 'selected' : '' }}>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- =========================
                         SPECIALIZATIONS
                    ========================== --}}
                    <div class="col-md-12 mb-4">

                        <label class="form-label">

                            Hospital Specializations
                            <span class="text-danger">*</span>

                        </label>

                        @php

                            $selectedSpecializations = old(
                                'specializations',
                                $hospital->hospitalSpecializations
                                    ->pluck('specialization_id')
                                    ->toArray()
                            );

                        @endphp

                        <div class="row">

                            @forelse($specializations as $specialization)

                                <div class="col-md-4 col-lg-3 mb-2">

                                    <div class="form-check">

                                        <input type="checkbox"
                                               class="form-check-input"
                                               id="specialization{{ $specialization->id }}"
                                               name="specializations[]"
                                               value="{{ $specialization->id }}"
                                               {{ in_array($specialization->id, $selectedSpecializations) ? 'checked' : '' }}>

                                        <label class="form-check-label"
                                               for="specialization{{ $specialization->id }}">

                                            {{ $specialization->specialization_name }}

                                        </label>

                                    </div>

                                </div>

                            @empty

                                <div class="col-12">

                                    <span class="text-muted">
                                        No specializations available.
                                    </span>

                                </div>

                            @endforelse

                        </div>

                        @error('specializations')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>


                    {{-- =========================
                         FACILITIES
                    ========================== --}}
                    <div class="col-md-12 mb-4">

                        <label class="form-label">

                            Hospital Facilities
                            <span class="text-danger">*</span>

                        </label>

                        @php

                            $selectedFacilities = old(
                                'facilities',
                                $hospital->facilities
                                    ->pluck('facility_id')
                                    ->toArray()
                            );

                        @endphp

                        <div class="row">

                            @forelse($facilities as $facility)

                                <div class="col-md-4 col-lg-3 mb-2">

                                    <div class="form-check">

                                        <input type="checkbox"
                                               class="form-check-input"
                                               id="facility{{ $facility->id }}"
                                               name="facilities[]"
                                               value="{{ $facility->id }}"
                                               {{ in_array($facility->id, $selectedFacilities) ? 'checked' : '' }}>

                                        <label class="form-check-label"
                                               for="facility{{ $facility->id }}">

                                            {{ $facility->name }}

                                        </label>

                                    </div>

                                </div>

                            @empty

                                <div class="col-12">

                                    <span class="text-muted">
                                        No facilities available.
                                    </span>

                                </div>

                            @endforelse

                        </div>

                        @error('facilities')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>


                    {{-- =========================
                         TIEUPS
                    ========================== --}}
                    <div class="col-md-12 mb-4">

                        <label class="form-label">

                            Hospital Tieups
                            <span class="text-danger">*</span>

                        </label>

                        @php

                            $selectedTieups = old(
                                'tieups',
                                $selectedTieupIds ?? []
                            );

                        @endphp

                        <div class="row">

                            @forelse($tieups as $tieup)

                                <div class="col-md-4 col-lg-3 mb-2">

                                    <div class="form-check">

                                        <input type="checkbox"
                                               class="form-check-input"
                                               id="tieup{{ $tieup->id }}"
                                               name="tieups[]"
                                               value="{{ $tieup->id }}"
                                               {{ in_array($tieup->id, $selectedTieups) ? 'checked' : '' }}>

                                        <label class="form-check-label"
                                               for="tieup{{ $tieup->id }}">

                                            {{ $tieup->name }}

                                        </label>

                                    </div>

                                </div>

                            @empty

                                <div class="col-12">

                                    <span class="text-muted">
                                        No tieups available.
                                    </span>

                                </div>

                            @endforelse

                        </div>

                        @error('tieups')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>


                    {{-- Registration Number --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Registration Number
                        </label>

                        <input type="text"
                               name="registration_number"
                               class="form-control"
                               value="{{ old('registration_number', $hospital->registration_number) }}">

                    </div>


                    {{-- GST --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            GST Number
                        </label>

                        <input type="text"
                               name="gst_number"
                               class="form-control"
                               value="{{ old('gst_number', $hospital->gst_number) }}">

                    </div>


                    {{-- PAN --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            PAN Number
                        </label>

                        <input type="text"
                               name="pan_number"
                               class="form-control"
                               value="{{ old('pan_number', $hospital->pan_number) }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================
             CONTACT INFORMATION
        ========================== --}}
        <div class="card border-0 shadow-sm mt-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="ti ti-phone me-2"></i>

                    Contact Information

                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Email --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Email
                        </label>

                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email', $hospital->email) }}">

                    </div>


                    {{-- Mobile --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Mobile
                        </label>

                        <input type="text"
                               name="mobile"
                               class="form-control"
                               value="{{ old('mobile', $hospital->mobile) }}">

                    </div>


                    {{-- Phone --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Phone
                        </label>

                        <input type="text"
                               name="phone"
                               class="form-control"
                               value="{{ old('phone', $hospital->phone) }}">

                    </div>


                    {{-- Website --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Website
                        </label>

                        <input type="url"
                               name="website"
                               class="form-control"
                               value="{{ old('website', $hospital->website) }}"
                               placeholder="https://example.com">

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================
             ADDRESS INFORMATION
        ========================== --}}
        <div class="card border-0 shadow-sm mt-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="ti ti-map-pin me-2"></i>

                    Address Information

                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Country --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Country
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="country"
                               class="form-control"
                               value="{{ old('country', $hospital->country) }}">

                    </div>


                    {{-- State --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            State
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="state"
                               class="form-control"
                               value="{{ old('state', $hospital->state) }}">

                    </div>


                    {{-- City --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            City
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="city"
                               class="form-control"
                               value="{{ old('city', $hospital->city) }}">

                    </div>


                    {{-- Pincode --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Pincode
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="pincode"
                               class="form-control"
                               value="{{ old('pincode', $hospital->pincode) }}">

                    </div>


                    {{-- Latitude --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Latitude
                        </label>

                        <input type="text"
                               name="latitude"
                               class="form-control"
                               value="{{ old('latitude', $hospital->latitude) }}">

                    </div>


                    {{-- Longitude --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Longitude
                        </label>

                        <input type="text"
                               name="longitude"
                               class="form-control"
                               value="{{ old('longitude', $hospital->longitude) }}">

                    </div>


                    {{-- Address --}}
                    <div class="col-md-12 mb-3">

                        <label class="form-label">

                            Full Address
                            <span class="text-danger">*</span>

                        </label>

                        <textarea name="address"
                                  rows="4"
                                  class="form-control">{{ old('address', $hospital->address) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================
             WORKING HOURS
        ========================== --}}
        <div class="card border-0 shadow-sm mt-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="ti ti-clock-hour-4 me-2"></i>

                    Working Hours

                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Opening --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Opening Time
                        </label>

                        <input type="time"
                               name="opening_time"
                               class="form-control"
                               value="{{ old('opening_time', $hospital->opening_time) }}">

                    </div>


                    {{-- Closing --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Closing Time
                        </label>

                        <input type="time"
                               name="closing_time"
                               class="form-control"
                               value="{{ old('closing_time', $hospital->closing_time) }}">

                    </div>


                    {{-- Emergency --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Emergency Available
                        </label>

                        <select class="form-select"
                                name="emergency_available">

                            <option value="1"
                                {{ old('emergency_available', $hospital->emergency_available) == 1 ? 'selected' : '' }}>

                                Yes

                            </option>

                            <option value="0"
                                {{ old('emergency_available', $hospital->emergency_available) == 0 ? 'selected' : '' }}>

                                No

                            </option>

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select class="form-select"
                                name="status">

                            <option value="1"
                                {{ old('status', $hospital->status) == 1 ? 'selected' : '' }}>

                                Active

                            </option>

                            <option value="0"
                                {{ old('status', $hospital->status) == 0 ? 'selected' : '' }}>

                                Inactive

                            </option>

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================
             HOSPITAL IMAGES
        ========================== --}}
        <div class="card border-0 shadow-sm mt-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="ti ti-photo me-2"></i>

                    Hospital Images

                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- =========================
                         LOGO
                    ========================== --}}
                    <div class="col-md-6 mb-4">

                        <label class="form-label">
                            Hospital Logo
                        </label>

                        <div class="image-preview-box mb-3">

                            @if($hospital->logo)

                                <img src="{{ asset($hospital->logo) }}"
                                     id="logoPreview"
                                     alt="Hospital Logo">

                            @else

                                <div id="logoPlaceholder"
                                     class="image-placeholder">

                                    <i class="ti ti-building-hospital"></i>

                                    <span>
                                        No logo uploaded
                                    </span>

                                </div>

                                <img id="logoPreview"
                                     class="d-none"
                                     alt="Logo Preview">

                            @endif

                        </div>

                        <input type="file"
                               class="form-control"
                               name="logo"
                               id="logo"
                               accept="image/*">

                        <small class="text-muted">
                            Upload JPG, JPEG, PNG or WEBP image.
                        </small>

                    </div>


                    {{-- =========================
                         BANNERS
                    ========================== --}}
                    <div class="col-md-6 mb-4">

                        <label class="form-label">
                            Hospital Banners
                        </label>


                        {{-- Existing Banners --}}
                        @if($hospital->banner)

                            @php

                                $banners = array_filter(
                                    explode(',', $hospital->banner)
                                );

                            @endphp

                            <div class="row mb-3"
                                 id="existingBanners">

                                @foreach($banners as $index => $banner)

                                    @php
                                        $bannerPath = trim($banner);
                                    @endphp

                                    <div class="col-md-6 col-sm-6 mb-3">

                                        <div class="existing-banner">

                                            <img src="{{ asset($bannerPath) }}"
                                                 alt="Hospital Banner {{ $index + 1 }}">

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        @else

                            <div class="no-banner-message mb-3"
                                 id="noBannerMessage">

                                <i class="ti ti-photo-off me-2"></i>

                                No banners added yet.

                            </div>

                        @endif


                        {{-- New Banner Preview --}}
                        <div id="bannerPreviewContainer"
                             class="row mb-3">
                        </div>


                        {{-- Upload --}}
                        <input type="file"
                               class="form-control"
                               name="banner[]"
                               id="banner"
                               accept="image/*"
                               multiple>

                        <small class="text-muted">
                            You can select multiple banner images.
                        </small>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================
             ACTION BUTTONS
        ========================== --}}
        <div class="card border-0 shadow-sm mt-4">

            <div class="card-body">

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ url()->previous() }}"
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

{{-- =========================================================
CSS
========================================================= --}}

<style>

    .image-preview-box {
        width: 100%;
        min-height: 180px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .image-preview-box img {
        max-width: 100%;
        height: 180px;
        object-fit: contain;
        display: block;
    }

    .image-placeholder {
        min-height: 180px;
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #9aa4b2;
    }

    .image-placeholder i {
        font-size: 50px;
        margin-bottom: 8px;
        color: #7651d6;
    }

    .image-placeholder span {
        font-size: 13px;
    }

    .existing-banner {
        position: relative;
        width: 100%;
        height: 100px;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        background: #f8fafc;
    }

    .existing-banner img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .preview-banner {
        position: relative;
        width: 100%;
        height: 100px;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #dce5f2;
        background: #f8fafc;
    }

    .preview-banner img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .no-banner-message {
        min-height: 100px;
        border: 1px dashed #d9e2ef;
        border-radius: 10px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #8a98ad;
        font-size: 14px;
    }

    .form-label {
        font-weight: 600;
        color: #26364f;
    }

    .card {
        border-radius: 14px;
        overflow: hidden;
    }

</style>

@endsection

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    // ==========================================
    // LOGO PREVIEW
    // ==========================================

    const logoInput = document.getElementById('logo');
    const logoPreview = document.getElementById('logoPreview');
    const logoPlaceholder = document.getElementById('logoPlaceholder');

    if (logoInput) {

        logoInput.addEventListener('change', function (event) {

            const file = event.target.files[0];

            if (!file) {
                return;
            }

            if (logoPreview) {

                logoPreview.src = URL.createObjectURL(file);

                logoPreview.classList.remove('d-none');

            }

            if (logoPlaceholder) {

                logoPlaceholder.classList.add('d-none');

            }

        });

    }


    // ==========================================
    // MULTIPLE BANNER PREVIEW
    // ==========================================

    const bannerInput = document.getElementById('banner');
    const bannerPreviewContainer =
        document.getElementById('bannerPreviewContainer');

    if (bannerInput && bannerPreviewContainer) {

        bannerInput.addEventListener('change', function (event) {

            bannerPreviewContainer.innerHTML = '';

            const files = Array.from(event.target.files);

            files.forEach(function (file) {

                if (!file.type.startsWith('image/')) {
                    return;
                }

                const col = document.createElement('div');

                col.className = 'col-md-6 col-sm-6 mb-3';

                const wrapper =
                    document.createElement('div');

                wrapper.className = 'preview-banner';

                const image =
                    document.createElement('img');

                image.src = URL.createObjectURL(file);

                image.alt = 'New Banner Preview';

                wrapper.appendChild(image);

                col.appendChild(wrapper);

                bannerPreviewContainer.appendChild(col);

            });

        });

    }


    // ==========================================
    // FORM SUBMIT LOADING
    // ==========================================

    const form = document.getElementById('hospitalEditForm');

    const submitBtn =
        document.getElementById('submitBtn');

    if (form && submitBtn) {

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
