@extends('layout.mainlayout')

@section('content')

    @php
        $facilityId = $facility->facility_id ?? $facility->id;
        $hospitalId = $facility->hospital_id;
    @endphp

    <div class="page-wrapper">
        <div class="content">

            {{-- ================= PAGE HEADER ================= --}}
            <div class="facility-page-header">

                <div>

                    <div class="facility-breadcrumb">
                        <i class="ti ti-building-hospital"></i>
                        Hospital Management
                        <i class="ti ti-chevron-right"></i>
                        Facility Details
                    </div>

                    <h4 class="facility-page-title">
                        Hospital Facility Details
                    </h4>

                    <p class="facility-page-subtitle">
                        View facility information and hospital gallery
                    </p>

                </div>

                <div>
                    <a href="{{ url()->previous() }}" class="facility-back-btn">
                        <i class="ti ti-arrow-left"></i>
                        Back
                    </a>
                </div>

            </div>


            {{-- ================= MAIN FACILITY CARD ================= --}}
            <div class="facility-details-card">

                <div class="facility-details-body">

                    <div class="facility-top-section">

                        {{-- FACILITY IMAGE --}}
                        <div class="facility-image-box">

                            @if(!empty($facility->facility?->image))

                                <img src="{{ asset($facility->facility->image) }}"
                                    alt="{{ $facility->facility->name ?? 'Facility' }}">

                            @else

                                <div class="facility-image-placeholder">
                                    <i class="ti ti-building-hospital"></i>
                                </div>

                            @endif

                        </div>


                        {{-- FACILITY NAME + DESCRIPTION --}}
                        <div class="facility-main-content">

                            <span class="facility-badge">
                                <i class="ti ti-building-hospital"></i>
                                Facility
                            </span>

                            <h1 class="facility-highlight-name">

                                <span class="facility-name-icon">
                                    <i class="ti ti-building-hospital"></i>
                                </span>

                                <span>
                                    {{ $facility->facility->hospital_name
                                        ?? $facility->facility->name
                                        ?? 'Hospital Facility' }}
                                </span>

                            </h1>


                            <div class="facility-description-section">

                                <h5 class="facility-description-title">
                                    <i class="ti ti-info-circle me-1"></i>
                                    Description
                                </h5>

                                @if(!empty($facility->facility?->description))

                                    <p class="facility-short-description">
                                        {{ $facility->facility->description }}
                                    </p>

                                @else

                                    <p class="facility-short-description text-muted">
                                        No description available for this facility.
                                    </p>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =========================================================
                 HOSPITAL GALLERY
            ========================================================= --}}

            <div class="hospital-gallery-card">

                {{-- HEADER --}}
                <div class="hospital-gallery-header">

                    <div class="hospital-section-heading">

                        <div class="hospital-gallery-section-icon">
                            <i class="ti ti-photo"></i>
                        </div>

                        <div>

                            <h4>
                                Hospital Gallery
                            </h4>

                            <p>
                                Images and videos uploaded for this facility
                            </p>

                        </div>

                    </div>


                    {{-- HEADER ACTIONS --}}
                    <div class="hospital-gallery-header-actions">

                        <button type="button"
                            class="add-gallery-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#addGalleryModal">

                            <span class="add-gallery-icon">
                                <i class="ti ti-photo-plus"></i>
                            </span>

                            <span class="add-gallery-text">
                                Add Gallery
                            </span>

                        </button>


                        <div class="hospital-gallery-count">

                            {{ $galleries->count() }}

                            {{ $galleries->count() == 1 ? 'File' : 'Files' }}

                        </div>

                    </div>

                </div>


                {{-- GALLERY BODY --}}
                <div class="hospital-gallery-body">

                    @forelse($galleries as $gallery)

                        <div class="hospital-gallery-item">

                            {{-- DELETE --}}
                            <form action="{{ route('hospital.hospitalprofile.gallery.destroy', $gallery->id) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to remove this gallery file?');"
                                style="position:absolute;top:10px;right:10px;z-index:10;">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    title="Remove"
                                    style="
                                        width:32px;
                                        height:32px;
                                        padding:0;
                                        border:0;
                                        border-radius:6px;
                                        background:#dc3545;
                                        color:#fff;
                                        display:flex;
                                        align-items:center;
                                        justify-content:center;
                                        cursor:pointer;
                                    ">

                                    <i class="ti ti-trash" style="font-size:16px;"></i>

                                </button>

                            </form>


                            {{-- IMAGE --}}
                            @if($gallery->file_type === 'image')

                                <div class="hospital-gallery-media">

                                    <a href="{{ asset($gallery->file_path) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        style="display:block;width:100%;height:100%;">

                                        <img src="{{ asset($gallery->file_path) }}"
                                            alt="{{ $gallery->hospital ?? 'Hospital Gallery' }}"
                                            class="gallery-media-image">

                                    </a>

                                    <div class="hospital-gallery-type">

                                        <i class="ti ti-photo"></i>
                                        Image

                                    </div>

                                </div>


                            {{-- VIDEO --}}
                            @elseif($gallery->file_type === 'video')

                                <div class="hospital-gallery-media">

                                    <a href="{{ asset($gallery->file_path) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        style="display:block;width:100%;height:100%;">

                                        <video class="gallery-media-video"
                                            autoplay
                                            muted
                                            loop
                                            playsinline
                                            preload="auto">

                                            <source src="{{ asset($gallery->file_path) }}"
                                                type="video/mp4">

                                            Your browser does not support video playback.

                                        </video>

                                    </a>

                                    <div class="hospital-gallery-type video-type">

                                        <i class="ti ti-video"></i>
                                        Video

                                    </div>

                                </div>

                            @endif

                        </div>

                    @empty

                        <div class="hospital-gallery-empty">

                            <div class="hospital-gallery-empty-icon">
                                <i class="ti ti-photo-off"></i>
                            </div>

                            <h5>
                                No Gallery Files
                            </h5>

                            <p>
                                No images or videos have been uploaded for this facility yet.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>
    </div>


    {{-- =========================================================
         ADD GALLERY MODAL
    ========================================================= --}}

    <div class="modal fade"
        id="addGalleryModal"
        tabindex="-1"
        aria-labelledby="addGalleryModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg">

            <div class="modal-content gallery-modal-content">


                {{-- MODAL HEADER --}}
                <div class="modal-header gallery-modal-header">

                    <div class="gallery-modal-title-wrapper">

                        <div class="gallery-modal-icon">
                            <i class="ti ti-photo-plus"></i>
                        </div>

                        <div>

                            <h5 class="modal-title"
                                id="addGalleryModalLabel">

                                Add Hospital Gallery

                            </h5>

                            <p>
                                Upload an image or video for this hospital facility.
                            </p>

                        </div>

                    </div>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>

                </div>


                {{-- FORM --}}
                <form action="{{ route('hospital.hospitalprofile.gallery.store') }}"
                    method="POST"
                    enctype="multipart/form-data">

                    @csrf


                    <div class="modal-body gallery-modal-body">

                        {{-- HOSPITAL ID --}}
                        <input type="hidden"
                            name="hospital_id"
                            value="{{ $facility->hospital_id ?? '' }}">


                        {{-- FACILITY ID --}}
                        <input type="hidden"
                            name="facility_id"
                            value="{{ $facility->facility_id ?? $facility->id }}">


                        {{-- HOSPITAL NAME --}}
                        <input type="hidden"
                            name="hospital"
                            value="{{ $facility->hospital->hospital_name
                                ?? $facility->hospital->name
                                ?? 'Hospital' }}">


                        {{-- HOSPITAL --}}
                        <div class="gallery-form-group">

                            <label class="gallery-form-label">
                                Hospital
                                <span>*</span>
                            </label>

                            <div class="form-control bg-light">

                                {{ $facility->hospital->hospital_name
                                    ?? $facility->hospital->name
                                    ?? 'Hospital' }}

                            </div>

                        </div>


                        {{-- FACILITY --}}
                        <div class="gallery-form-group">

                            <label class="gallery-form-label">
                                Facility
                                <span>*</span>
                            </label>

                            <div class="form-control bg-light">

                                {{ $facility->facility->hospital_name
                                    ?? $facility->facility->name
                                    ?? 'Hospital Facility' }}

                            </div>

                        </div>


                        {{-- FILE TYPE --}}
                        <div class="gallery-form-group">

                            <label class="gallery-form-label">
                                File Type
                                <span>*</span>
                            </label>


                            <div class="gallery-type-options">


                                {{-- IMAGE --}}
                                <label class="gallery-type-option">

                                    <input type="radio"
                                        name="file_type"
                                        value="image"
                                        checked>

                                    <span class="gallery-type-card">

                                        <span class="gallery-type-icon image">
                                            <i class="ti ti-photo"></i>
                                        </span>

                                        <span>

                                            <strong>
                                                Image
                                            </strong>

                                            <small>
                                                JPG, PNG, WEBP, GIF
                                            </small>

                                        </span>

                                    </span>

                                </label>


                                {{-- VIDEO --}}
                                <label class="gallery-type-option">

                                    <input type="radio"
                                        name="file_type"
                                        value="video">

                                    <span class="gallery-type-card">

                                        <span class="gallery-type-icon video">
                                            <i class="ti ti-video"></i>
                                        </span>

                                        <span>

                                            <strong>
                                                Video
                                            </strong>

                                            <small>
                                                MP4, MOV, AVI, WEBM
                                            </small>

                                        </span>

                                    </span>

                                </label>

                            </div>

                        </div>


                        {{-- FILE UPLOAD --}}
                        <div class="gallery-form-group">

                            <label class="gallery-form-label">
                                Upload File
                                <span>*</span>
                            </label>


                            <div class="gallery-upload-box"
                                id="galleryUploadBox">

                                <input type="file"
                                    name="file_path"
                                    id="galleryFile"
                                    class="gallery-file-input"
                                    accept="image/*"
                                    required>


                                <div class="gallery-upload-content">

                                    <div class="gallery-upload-icon">
                                        <i class="ti ti-cloud-upload"></i>
                                    </div>

                                    <h6>
                                        Click to upload
                                    </h6>

                                    <p id="galleryUploadText">
                                        Select an image from your computer
                                    </p>

                                    <span>
                                        Maximum file size: 50 MB
                                    </span>

                                </div>

                            </div>


                            {{-- PREVIEW --}}
                            <div id="galleryPreview"
                                class="gallery-preview"
                                style="display:none;">
                            </div>

                        </div>

                    </div>


                    {{-- FOOTER --}}
                    <div class="modal-footer gallery-modal-footer">

                        <button type="button"
                            class="gallery-cancel-btn"
                            data-bs-dismiss="modal">

                            Cancel

                        </button>


                        <button type="submit"
                            class="gallery-submit-btn">

                            <i class="ti ti-upload"></i>

                            Upload Gallery

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- =========================================================
         CSS
    ========================================================= --}}

    <style>

        /* ================= PAGE HEADER ================= */

        .facility-page-header {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:20px;
            margin-bottom:22px;
        }

        .facility-breadcrumb {
            display:flex;
            align-items:center;
            gap:7px;
            color:#71809a;
            font-size:12px;
            font-weight:500;
            margin-bottom:7px;
        }

        .facility-breadcrumb i:first-child {
            color:#4f8df7;
        }

        .facility-breadcrumb i {
            font-size:14px;
        }

        .facility-page-title {
            margin:0;
            color:#172b4d;
            font-size:26px;
            font-weight:700;
        }

        .facility-page-subtitle {
            margin:4px 0 0;
            color:#8a98ad;
            font-size:13px;
        }

        .facility-back-btn {
            display:inline-flex;
            align-items:center;
            gap:7px;
            padding:10px 18px;
            border:1px solid #dce5f2;
            border-radius:10px;
            background:#fff;
            color:#52647e;
            font-size:13px;
            font-weight:600;
            text-decoration:none;
            transition:all .2s ease;
        }

        .facility-back-btn:hover {
            background:#f4f8ff;
            color:#3978e8;
            border-color:#c8daf8;
        }


        /* ================= FACILITY CARD ================= */

        .facility-details-card {
            background:#fff;
            border:1px solid #e8edf5;
            border-radius:18px;
            box-shadow:0 6px 25px rgba(31,56,88,.05);
            margin-bottom:22px;
        }

        .facility-details-body {
            padding:28px;
        }

        .facility-top-section {
            display:flex;
            align-items:center;
            gap:25px;
        }

        .facility-image-box {
            width:155px;
            height:155px;
            min-width:155px;
            overflow:hidden;
            border-radius:16px;
            background:#f1f5fb;
            border:1px solid #e4ebf5;
        }

        .facility-image-box img {
            width:100%;
            height:100%;
            display:block;
            object-fit:cover;
        }

        .facility-image-placeholder {
            width:100%;
            height:100%;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#edf5ff;
        }

        .facility-image-placeholder i {
            font-size:55px;
            color:#5891ed;
        }

        .facility-main-content {
            flex:1;
            min-width:0;
        }

        .facility-badge {
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:6px 11px;
            border-radius:20px;
            background:#edf5ff;
            color:#397fe5;
            font-size:12px;
            font-weight:600;
            margin-bottom:9px;
        }

        .facility-highlight-name {
            display:flex;
            align-items:center;
            gap:12px;
            margin:12px 0 10px;
            padding:12px 16px;
            background:#f0f6ff;
            border-left:4px solid #4f7cff;
            border-radius:8px;
            color:#1f3b64;
            font-size:28px;
            font-weight:700;
            line-height:1.3;
        }

        .facility-name-icon {
            width:42px;
            height:42px;
            min-width:42px;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#e2ecff;
            color:#4f7cff;
            border-radius:8px;
            font-size:21px;
        }

        .facility-description-section {
            margin-top:18px;
        }

        .facility-description-title {
            color:#26364f;
            font-size:15px;
            font-weight:700;
            margin-bottom:7px;
        }

        .facility-description-title i {
            color:#4f8df7;
            font-size:17px;
        }

        .facility-short-description {
            color:#71809a;
            font-size:14px;
            line-height:1.7;
            margin-bottom:0;
        }


        /* ================= GALLERY CARD ================= */

        .hospital-gallery-card {
            margin-top:22px;
            background:#fff;
            border:1px solid #e8edf5;
            border-radius:18px;
            box-shadow:0 6px 25px rgba(31,56,88,.05);
            overflow:hidden;
        }

        .hospital-gallery-header {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:20px;
            padding:23px 25px;
            border-bottom:1px solid #edf1f6;
        }

        .hospital-section-heading {
            display:flex;
            align-items:center;
            gap:13px;
            min-width:0;
        }

        .hospital-gallery-section-icon {
            width:45px;
            height:45px;
            min-width:45px;
            display:flex;
            align-items:center;
            justify-content:center;
            border-radius:12px;
            background:#f0eaff;
            color:#6d28d9;
        }

        .hospital-gallery-section-icon i {
            font-size:22px;
        }

        .hospital-section-heading h4 {
            margin:0 0 3px;
            color:#172b4d;
            font-size:19px;
            font-weight:700;
        }

        .hospital-section-heading p {
            margin:0;
            color:#91a0b5;
            font-size:12px;
        }

        .hospital-gallery-header-actions {
            display:flex;
            align-items:center;
            justify-content:flex-end;
            gap:10px;
            margin-left:auto;
        }

        .hospital-gallery-count {
            padding:7px 13px;
            border-radius:20px;
            background:#f0eaff;
            color:#6d28d9;
            font-size:12px;
            font-weight:600;
            white-space:nowrap;
        }

        .add-gallery-btn {
            display:inline-flex;
            align-items:center;
            gap:8px;
            height:34px;
            padding:0 13px;
            border:1px solid #ddd0f7;
            border-radius:8px;
            background:#f7f3ff;
            color:#6d28d9 !important;
            font-size:12px;
            font-weight:600;
            text-decoration:none !important;
            transition:all .2s ease;
        }

        .add-gallery-icon {
            width:22px;
            height:22px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            border-radius:6px;
            background:#eee6ff;
            color:#6d28d9;
        }

        .add-gallery-icon i {
            font-size:14px;
        }

        .add-gallery-btn:hover {
            background:#6d28d9;
            border-color:#6d28d9;
            color:#fff !important;
            box-shadow:0 4px 10px rgba(109,40,217,.20);
            transform:translateY(-1px);
        }

        .add-gallery-btn:hover .add-gallery-icon {
            background:rgba(255,255,255,.18);
            color:#fff;
        }


        /* ================= GALLERY BODY ================= */

        .hospital-gallery-body {
            display:grid;
            grid-template-columns:repeat(4,1fr);
            gap:16px;
            padding:20px 25px 25px;
        }

        .hospital-gallery-item {
            position:relative;
            min-width:0;
            border:1px solid #e5ebf4;
            border-radius:13px;
            background:#fff;
            overflow:hidden;
            transition:all .2s ease;
        }

        .hospital-gallery-item:hover {
            border-color:#d5c6f7;
            box-shadow:0 7px 22px rgba(46,42,90,.08);
            transform:translateY(-2px);
        }

        .hospital-gallery-media {
            position:relative;
            width:100%;
            height:190px;
            background:#f3f5f9;
            overflow:hidden;
        }

        .gallery-media-image {
            width:100%;
            height:100%;
            display:block;
            object-fit:cover;
            cursor:pointer;
            transition:transform .25s ease;
        }

        .hospital-gallery-item:hover .gallery-media-image {
            transform:scale(1.03);
        }

        .gallery-media-video {
            width:100%;
            height:100%;
            display:block;
            object-fit:cover;
            background:#101318;
        }

        .hospital-gallery-type {
            position:absolute;
            top:10px;
            left:10px;
            display:inline-flex;
            align-items:center;
            gap:5px;
            padding:5px 9px;
            border-radius:15px;
            background:rgba(109,40,217,.92);
            color:#fff;
            font-size:10px;
            font-weight:600;
        }

        .hospital-gallery-type i {
            font-size:13px;
        }

        .hospital-gallery-type.video-type {
            background:rgba(219,89,101,.92);
        }


        /* ================= EMPTY ================= */

        .hospital-gallery-empty {
            grid-column:1 / -1;
            text-align:center;
            padding:55px 20px;
        }

        .hospital-gallery-empty-icon {
            width:65px;
            height:65px;
            margin:0 auto 13px;
            display:flex;
            align-items:center;
            justify-content:center;
            border-radius:50%;
            background:#f2f5f9;
            color:#a1adbc;
        }

        .hospital-gallery-empty-icon i {
            font-size:30px;
        }

        .hospital-gallery-empty h5 {
            margin:0 0 5px;
            color:#52647e;
            font-size:15px;
        }

        .hospital-gallery-empty p {
            margin:0;
            color:#9aa7b8;
            font-size:12px;
        }


        /* ================= MODAL ================= */

        .gallery-modal-content {
            border:0;
            border-radius:18px;
            overflow:hidden;
            box-shadow:0 20px 60px rgba(31,56,88,.16);
        }

        .gallery-modal-header {
            display:flex;
            align-items:center;
            justify-content:space-between;
            padding:20px 23px;
            border-bottom:1px solid #edf1f6;
            background:#fff;
        }

        .gallery-modal-title-wrapper {
            display:flex;
            align-items:center;
            gap:13px;
        }

        .gallery-modal-icon {
            width:44px;
            height:44px;
            min-width:44px;
            display:flex;
            align-items:center;
            justify-content:center;
            border-radius:11px;
            background:#f0eaff;
            color:#6d28d9;
        }

        .gallery-modal-icon i {
            font-size:21px;
        }

        .gallery-modal-header h5 {
            margin:0 0 3px;
            color:#172b4d;
            font-size:17px;
            font-weight:700;
        }

        .gallery-modal-header p {
            margin:0;
            color:#91a0b5;
            font-size:11px;
        }

        .gallery-modal-body {
            padding:24px;
            background:#fff;
        }

        .gallery-form-group {
            margin-bottom:20px;
        }

        .gallery-form-group:last-child {
            margin-bottom:0;
        }

        .gallery-form-label {
            display:block;
            margin-bottom:8px;
            color:#344563;
            font-size:12px;
            font-weight:600;
        }

        .gallery-form-label span {
            color:#e05a67;
        }

        .gallery-type-options {
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:12px;
        }

        .gallery-type-option {
            position:relative;
            margin:0;
            cursor:pointer;
        }

        .gallery-type-option input {
            position:absolute;
            opacity:0;
        }

        .gallery-type-card {
            display:flex;
            align-items:center;
            gap:11px;
            padding:12px 14px;
            border:1px solid #e2e7ef;
            border-radius:10px;
            background:#fff;
            transition:all .2s ease;
        }

        .gallery-type-option input:checked + .gallery-type-card {
            border-color:#8d68e8;
            background:#f8f5ff;
            box-shadow:0 0 0 2px rgba(109,40,217,.06);
        }

        .gallery-type-icon {
            width:38px;
            height:38px;
            min-width:38px;
            display:flex;
            align-items:center;
            justify-content:center;
            border-radius:9px;
        }

        .gallery-type-icon.image {
            background:#edf5ff;
            color:#397fe5;
        }

        .gallery-type-icon.video {
            background:#fff0f4;
            color:#db5965;
        }

        .gallery-type-icon i {
            font-size:18px;
        }

        .gallery-type-card strong {
            display:block;
            color:#344563;
            font-size:12px;
            font-weight:700;
        }

        .gallery-type-card small {
            display:block;
            margin-top:2px;
            color:#9aa7b8;
            font-size:10px;
        }


        /* ================= UPLOAD ================= */

        .gallery-upload-box {
            position:relative;
            min-height:150px;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:20px;
            border:1.5px dashed #cfd8e6;
            border-radius:12px;
            background:#fafbfe;
            cursor:pointer;
            transition:all .2s ease;
        }

        .gallery-upload-box:hover {
            border-color:#8d68e8;
            background:#faf8ff;
        }

        .gallery-file-input {
            position:absolute;
            inset:0;
            width:100%;
            height:100%;
            opacity:0;
            cursor:pointer;
        }

        .gallery-upload-content {
            text-align:center;
            pointer-events:none;
        }

        .gallery-upload-icon {
            width:45px;
            height:45px;
            margin:0 auto 8px;
            display:flex;
            align-items:center;
            justify-content:center;
            border-radius:50%;
            background:#eee7ff;
            color:#6d28d9;
        }

        .gallery-upload-icon i {
            font-size:21px;
        }

        .gallery-upload-content h6 {
            margin:0 0 4px;
            color:#344563;
            font-size:13px;
            font-weight:700;
        }

        .gallery-upload-content p {
            margin:0 0 4px;
            color:#7e8da3;
            font-size:11px;
        }

        .gallery-upload-content > span {
            color:#a1adbc;
            font-size:10px;
        }


        /* ================= PREVIEW ================= */

        .gallery-preview {
            margin-top:12px;
            padding:10px;
            border:1px solid #e5ebf4;
            border-radius:10px;
            background:#fafbfe;
        }

        .gallery-preview img,
        .gallery-preview video {
            display:block;
            width:100%;
            max-height:220px;
            object-fit:contain;
            border-radius:8px;
        }


        /* ================= FOOTER ================= */

        .gallery-modal-footer {
            display:flex;
            justify-content:flex-end;
            gap:9px;
            padding:15px 23px;
            border-top:1px solid #edf1f6;
            background:#fbfcfe;
        }

        .gallery-cancel-btn {
            height:38px;
            padding:0 16px;
            border:1px solid #dce5f2;
            border-radius:8px;
            background:#fff;
            color:#52647e;
            font-size:12px;
            font-weight:600;
        }

        .gallery-submit-btn {
            height:38px;
            display:inline-flex;
            align-items:center;
            gap:7px;
            padding:0 17px;
            border:0;
            border-radius:8px;
            background:#6d28d9;
            color:#fff;
            font-size:12px;
            font-weight:600;
            transition:all .2s ease;
        }

        .gallery-submit-btn:hover {
            background:#5b21b6;
            color:#fff;
        }


        /* ================= RESPONSIVE ================= */

        @media(max-width:1199px) {

            .hospital-gallery-body {
                grid-template-columns:repeat(3,1fr);
            }

        }

        @media(max-width:991px) {

            .hospital-gallery-body {
                grid-template-columns:repeat(2,1fr);
            }

        }

        @media(max-width:767px) {

            .facility-page-header {
                align-items:flex-start;
                flex-direction:column;
            }

            .facility-back-btn {
                width:100%;
                justify-content:center;
            }

            .facility-details-body {
                padding:20px;
            }

            .facility-top-section {
                align-items:flex-start;
                flex-direction:column;
            }

            .facility-image-box {
                width:115px;
                height:115px;
                min-width:115px;
            }

            .facility-highlight-name {
                font-size:22px;
            }

            .hospital-gallery-header {
                align-items:flex-start;
                flex-direction:column;
            }

            .hospital-gallery-header-actions {
                width:100%;
                justify-content:flex-start;
            }

        }

        @media(max-width:575px) {

            .facility-page-title {
                font-size:22px;
            }

            .facility-page-subtitle {
                font-size:12px;
            }

            .facility-details-body {
                padding:15px;
            }

            .facility-image-box {
                width:100px;
                height:100px;
                min-width:100px;
            }

            .facility-highlight-name {
                font-size:20px;
                padding:10px 12px;
            }

            .hospital-gallery-body {
                grid-template-columns:1fr;
                padding:15px;
            }

            .hospital-gallery-media {
                height:220px;
            }

            .gallery-type-options {
                grid-template-columns:1fr;
            }

            .gallery-modal-body {
                padding:18px;
            }

            .gallery-modal-footer {
                padding:13px 18px;
            }

        }

    </style>


    {{-- =========================================================
         JAVASCRIPT
    ========================================================= --}}

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const fileInput = document.getElementById('galleryFile');

            const uploadText =
                document.getElementById('galleryUploadText');

            const previewBox =
                document.getElementById('galleryPreview');

            const typeInputs =
                document.querySelectorAll('input[name="file_type"]');


            /* =========================
               FILE TYPE CHANGE
            ========================= */

            typeInputs.forEach(function (input) {

                input.addEventListener('change', function () {

                    fileInput.value = '';

                    previewBox.innerHTML = '';

                    previewBox.style.display = 'none';


                    if (this.value === 'image') {

                        fileInput.accept =
                            'image/jpeg,image/png,image/webp,image/gif';

                        uploadText.textContent =
                            'Select an image from your computer';

                    } else {

                        fileInput.accept = 'video/*';

                        uploadText.textContent =
                            'Select a video from your computer';

                    }

                });

            });


            /* =========================
               FILE PREVIEW
            ========================= */

            fileInput.addEventListener('change', function () {

                const file = this.files[0];

                if (!file) {
                    return;
                }


                uploadText.textContent = file.name;

                previewBox.innerHTML = '';


                const selectedType =
                    document.querySelector(
                        'input[name="file_type"]:checked'
                    ).value;


                /* IMAGE */

                if (selectedType === 'image') {

                    const image =
                        document.createElement('img');

                    image.src =
                        URL.createObjectURL(file);

                    image.alt =
                        'Gallery Preview';

                    image.style.maxWidth = '100%';
                    image.style.maxHeight = '250px';
                    image.style.objectFit = 'contain';
                    image.style.display = 'block';
                    image.style.margin = '0 auto';
                    image.style.borderRadius = '8px';

                    previewBox.appendChild(image);

                }


                /* VIDEO */

                else {

                    const video =
                        document.createElement('video');

                    video.src =
                        URL.createObjectURL(file);

                    video.controls = true;

                    video.preload = 'metadata';

                    video.style.width = '100%';
                    video.style.maxWidth = '100%';
                    video.style.maxHeight = '300px';
                    video.style.display = 'block';
                    video.style.borderRadius = '8px';

                    previewBox.appendChild(video);

                }


                previewBox.style.display = 'block';

            });

        });

    </script>

@endsection