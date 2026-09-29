@extends('layout.mainlayout')

@section('content')
    {{-- @php
    $facilityId = $facility->facility_id ?? $facility->id;

    $hospitalid = $facility->hospital_id;

    $galleries = \App\Models\HospitalGallery::where('facility_id', $facilityId)->where('hospital_id', $hospitalid)
    ->latest()
    ->get();
    @endphp --}}
    @php
        $facility = $facility ?? null;
        $hospitalFacilities = $hospitalFacilities ?? collect();
        $galleries = $galleries ?? collect();

        if (!$facility) {
            abort(404, 'Facility data not found.');
        }

        $facilityId = $facility->facility_id ?? $facility->id;
        $hospitalId = $facility->hospital_id;


        $facilityGalleryData = [];

        foreach ($hospitalFacilities as $hospitalFacility) {

            /*
             * hospital_facilities_lists.id
             * This is the exact facility-list record.
             */
            $hospitalFacilityListId = $hospitalFacility->id;

            /*
             * Main facility ID
             */
            $currentFacilityId =
                $hospitalFacility->facility_id
                ?? $hospitalFacility->id;

            /*
             * Get gallery using the exact hospital facility list.
             */
            $facilityGallery = \App\Models\HospitalGallery::where(
                'hospital_id',
                $hospitalId
            )
                ->where(
                    'hospital_facility_list_id',
                    $hospitalFacilityListId
                )
                ->latest()
                ->get();

            $facilityGalleryData[(string) $hospitalFacilityListId] = [

                'id' => $hospitalFacilityListId,

                'facility_id' => $currentFacilityId,

                'title' => $hospitalFacility->title
                    ?? $hospitalFacility->name
                    ?? 'Facility',

                'image' => !empty($hospitalFacility->image)
                    ? asset($hospitalFacility->image)
                    : null,

                'description' => $hospitalFacility->description ?? '',

                'galleries' => $facilityGallery->map(function ($gallery) {

                    return [
                        'id' => $gallery->id,
                        'file_type' => $gallery->file_type,
                        'file_path' => asset($gallery->file_path),
                        // DELETE URL
                        'delete_url' => route(
                            'admin.hospital-galleries.destroy',
                            $gallery->id
                        ),
                    ];

                })->values()->toArray(),
            ];
        }
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
                        View facility information and hospitals using this facility
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

                    {{-- TOP SECTION --}}
                    <div class="facility-top-section">

                        {{-- SMALL IMAGE --}}
                        <div class="facility-image-box">

                            @if(!empty($facility->facility->image))

                                <img src="{{ asset($facility->facility->image) }}"
                                    alt="{{ $facility->facility->name ?? 'Facility' }}">

                            @else

                                <div class="facility-image-placeholder">
                                    <i class="ti ti-building-hospital"></i>
                                </div>

                            @endif

                        </div>


                        {{-- NAME + DESCRIPTION --}}
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
                                    {{ $facility->facility->hospital_name ?? $facility->facility->name ?? 'Hospital Facility' }}
                                </span>
                            </h1>
                            <div class="facility-description-section">

                                <h5 class="facility-description-title">
                                    <i class="ti ti-info-circle me-1"></i>
                                    Description
                                </h5>

                                @if(!empty($facility->facility->description))

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
                        <style>
                            .facility-description-section {
                                margin-top: 18px;
                            }

                            .facility-description-title {
                                color: #26364f;
                                font-size: 15px;
                                font-weight: 700;
                                margin-bottom: 7px;
                            }

                            .facility-description-title i {
                                color: #4f8df7;
                                font-size: 17px;
                            }

                            .facility-short-description {
                                color: #71809a;
                                font-size: 14px;
                                line-height: 1.7;
                                margin-bottom: 0;
                            }

                            .facility-highlight-name {
                                display: flex;
                                align-items: center;
                                gap: 12px;
                                margin: 12px 0 10px;
                                padding: 12px 16px;
                                background: #f0f6ff;
                                border-left: 4px solid #4f7cff;
                                border-radius: 8px;
                                color: #1f3b64;
                                font-size: 28px;
                                font-weight: 700;
                                line-height: 1.3;
                            }

                            .facility-name-icon {
                                width: 42px;
                                height: 42px;
                                min-width: 42px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                background: #e2ecff;
                                color: #4f7cff;
                                border-radius: 8px;
                                font-size: 21px;
                            }
                        </style>

                    </div>


                    {{-- ================= FACILITY INFORMATION ================= --}}
                    <div class="facility-info-grid">

                        {{-- <div class="facility-info-item">

                            <div class="facility-info-icon">
                                <i class="ti ti-building-hospital"></i>
                            </div>

                            <div>
                                <span>Hospital Name</span>
                                <strong>
                                    {{ $facility->facility->hospital_name ?? $facility->facility->name ?? 'N/A' }}
                                </strong>
                            </div>

                        </div> --}}


                        {{-- <div class="facility-info-item">

                            <div class="facility-info-icon">
                                <i class="ti ti-medical-cross"></i>
                            </div>

                            <div>
                                <span>Facility Name</span>
                                <strong>
                                    {{ $facility->facility->name ?? 'N/A' }}
                                </strong>
                            </div>

                        </div> --}}


                        {{-- <div class="facility-info-item">

                            <div class="facility-info-icon">
                                <i class="ti ti-info-circle"></i>
                            </div>

                            <div>
                                <span>Status</span>

                                @if(isset($facility->facility->status))

                                @if($facility->facility->status == 1 ||
                                $facility->facility->status === 'active')

                                <strong class="status-active">
                                    <i class="ti ti-circle-check"></i>
                                    Active
                                </strong>

                                @else

                                <strong class="status-inactive">
                                    <i class="ti ti-circle-x"></i>
                                    Inactive
                                </strong>

                                @endif

                                @else

                                <strong>
                                    Available
                                </strong>

                                @endif

                            </div>

                        </div> --}}

                    </div>


                    {{-- ================= DESCRIPTION ================= --}}
                    {{-- @if(!empty($facility->facility->description))

                    <div class="facility-description-box">

                        <div class="description-icon">
                            <i class="ti ti-info-circle"></i>
                        </div>

                        <div>
                            <h5>Description</h5>

                            <p>
                                {{ $facility->facility->description }}
                            </p>
                        </div>

                    </div>

                    @endif --}}

                </div>

            </div>



            {{-- ================= HOSPITALS USING FACILITY ================= --}}

            {{-- HOSPITAL FACILITIES LIST --}}
            <div class="hospital-gallery-card">

                {{-- HEADER --}}
                <div class="hospital-gallery-header">

                    {{-- LEFT: TITLE --}}
                    <div class="hospital-section-heading">

                        <div class="hospital-gallery-section-icon">
                            <i class="ti ti-building-hospital"></i>
                        </div>

                        <div>
                            <h4>
                                Hospital Facilities List
                            </h4>

                            <p>
                                Manage hospital facilities available in the system
                            </p>
                        </div>

                    </div>

                    {{-- RIGHT: ADD BUTTON + COUNT --}}
                    <div class="hospital-gallery-header-actions">

                        <button type="button" class="add-gallery-btn" data-bs-toggle="modal"
                            data-bs-target="#addFacilityModal">
                            <span class="add-gallery-icon">
                                <i class="ti ti-plus"></i>
                            </span>
                            <span class="add-gallery-text">
                                Add Hospital Facility List
                            </span>
                        </button>

                        <div class="hospital-gallery-count">

                            {{ $hospitalFacilities->count() }}

                            {{ $hospitalFacilities->count() == 1 ? 'Facility' : 'Facilities' }}

                        </div>

                    </div>

                </div>


                {{-- FACILITIES BODY --}}
                {{-- FACILITIES BODY --}}
                <div class="facility-list-body">

                    @forelse($hospitalFacilities as $hospitalFacility)
                        <input type="hidden" name="facility_id"
                            value="{{ $hospitalFacility->facility_id ?? $hospitalFacility->id }}">

                        {{-- FACILITY ROW --}}
                        <div class="facility-list-row" onclick="showFacilityGallery({{ $hospitalFacility->id }})"
                            style="cursor: pointer;">

                            {{-- IMAGE --}}
                            <div class="facility-list-image">

                                @if(!empty($hospitalFacility->image))

                                    <a href="{{ asset($hospitalFacility->image) }}" target="_blank" rel="noopener noreferrer">

                                        <img src="{{ asset($hospitalFacility->image) }}"
                                            alt="{{ $hospitalFacility->title ?? 'Hospital Facility' }}">

                                    </a>

                                @else

                                    <div class="facility-list-placeholder">
                                        <i class="ti ti-building-hospital"></i>
                                    </div>

                                @endif

                            </div>


                            {{-- CONTENT --}}
                            <div class="facility-list-content">

                                <div class="facility-list-title-row">

                                    <h5>
                                        {{ $hospitalFacility->title ?? 'Hospital Facility' }}
                                    </h5>

                                    <span class="facility-list-badge">
                                        <i class="ti ti-building-hospital"></i>
                                        Facility
                                    </span>

                                </div>

                                @if(!empty($hospitalFacility->description))

                                    <p>
                                        {{ Str::limit($hospitalFacility->description, 130) }}
                                    </p>

                                @else

                                    <p class="facility-list-no-description">
                                        No description available.
                                    </p>

                                @endif

                                <div class="facility-list-meta">

                                    <span>
                                        <i class="ti ti-calendar"></i>
                                        Added {{ $hospitalFacility->created_at?->format('d M Y') ?? 'N/A' }}
                                    </span>

                                    <span>
                                        <i class="ti ti-photo"></i>
                                        {{ !empty($hospitalFacility->image) ? 'Image Available' : 'No Image' }}
                                    </span>

                                </div>

                            </div>


                            {{-- ACTIONS --}}
                            <div class="facility-list-actions">

                                {{-- ADD GALLERY --}}
                                {{-- ADD GALLERY --}}
                                <button type="button" class="add-gallery-btn" data-bs-toggle="modal"
                                    data-bs-target="#addGalleryModal{{ $hospitalFacility->id }}"
                                    onclick="event.stopPropagation();">

                                    <span class="add-gallery-icon">
                                        <i class="ti ti-photo-plus"></i>
                                    </span>

                                    <span class="add-gallery-text">
                                        Add Gallery
                                    </span>

                                </button>


                                {{-- EDIT --}}
                                <button type="button" class="facility-list-action edit" title="Edit Facility"
                                    data-bs-toggle="modal" data-bs-target="#editFacilityModal{{ $hospitalFacility->id }}"
                                    onclick="event.stopPropagation();">

                                    <i class="ti ti-edit"></i>
                                    <span>Edit</span>

                                </button>


                                {{-- DELETE --}}
                                <form action="{{ route('admin.hospital-facilities-list.destroy', $hospitalFacility->id) }}"
                                    method="POST" onclick="event.stopPropagation();"
                                    onsubmit="return confirm('Are you sure you want to delete this hospital facility?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="facility-list-action delete" title="Delete Facility">

                                        <i class="ti ti-trash"></i>
                                        <span>Delete</span>

                                    </button>

                                </form>

                            </div>

                        </div>


                        {{-- =====================================================
                        EDIT FACILITY MODAL
                        ====================================================== --}}
                        <div class="modal fade" id="editFacilityModal{{ $hospitalFacility->id }}" tabindex="-1"
                            aria-labelledby="editFacilityModalLabel{{ $hospitalFacility->id }}" aria-hidden="true">

                            <div class="modal-dialog modal-lg modal-dialog-centered">

                                <div class="modal-content facility-edit-modal">

                                    {{-- MODAL HEADER --}}
                                    <div class="modal-header">

                                        <div>
                                            <h5 class="modal-title" id="editFacilityModalLabel{{ $hospitalFacility->id }}">

                                                Edit Hospital Facility

                                            </h5>

                                            <p class="facility-modal-subtitle">
                                                Update hospital facility information
                                            </p>
                                        </div>

                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        </button>

                                    </div>


                                    {{-- FORM --}}
                                    <form action="{{ route('admin.hospital-facilities-list.update', $hospitalFacility->id) }}"
                                        method="POST" enctype="multipart/form-data">

                                        @csrf
                                        @method('PUT')

                                        <div class="modal-body">

                                            <div class="row g-3">

                                                {{-- TITLE --}}
                                                <div class="col-md-12">

                                                    <label class="facility-form-label">
                                                        Facility Title
                                                        <span class="text-danger">*</span>
                                                    </label>

                                                    <input type="text" name="title" class="form-control facility-form-control"
                                                        value="{{ $hospitalFacility->title }}"
                                                        placeholder="Enter facility title" required>

                                                </div>


                                                {{-- CURRENT IMAGE --}}
                                                <div class="col-md-5">

                                                    <label class="facility-form-label">
                                                        Current Image
                                                    </label>

                                                    <div class="facility-current-image">

                                                        @if(!empty($hospitalFacility->image))

                                                            <img src="{{ asset($hospitalFacility->image) }}"
                                                                alt="{{ $hospitalFacility->title ?? 'Facility' }}">

                                                        @else

                                                            <div class="facility-current-placeholder">

                                                                <i class="ti ti-building-hospital"></i>

                                                                <span>No Image</span>

                                                            </div>

                                                        @endif

                                                    </div>

                                                </div>


                                                {{-- NEW IMAGE --}}
                                                <div class="col-md-7">

                                                    <label class="facility-form-label">
                                                        Change Image
                                                    </label>

                                                    <input type="file" name="image" class="form-control facility-form-control"
                                                        accept=".jpg,.jpeg,.png,.webp">

                                                    <small class="facility-form-help">
                                                        JPG, JPEG, PNG or WEBP. Maximum 5MB.
                                                    </small>

                                                </div>


                                                {{-- DESCRIPTION --}}
                                                <div class="col-md-12">

                                                    <label class="facility-form-label">
                                                        Description
                                                    </label>

                                                    <textarea name="description" rows="5"
                                                        class="form-control facility-form-control"
                                                        placeholder="Enter facility description">{{ $hospitalFacility->description }}</textarea>

                                                </div>

                                            </div>

                                        </div>


                                        {{-- FOOTER --}}
                                        <div class="modal-footer">

                                            <button type="button" class="facility-modal-cancel" data-bs-dismiss="modal">

                                                Cancel

                                            </button>

                                            <button type="submit" class="facility-modal-save">

                                                <i class="ti ti-device-floppy"></i>

                                                Update Facility

                                            </button>

                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>
                        {{-- =========================================================
                        ADD GALLERY MODAL
                        ========================================================= --}}

                        <div class="modal fade" id="addGalleryModal{{ $hospitalFacility->id }}" tabindex="-1"
                            aria-labelledby="addGalleryModalLabel{{ $hospitalFacility->id }}" aria-hidden="true">

                            <div class="modal-dialog modal-dialog-centered modal-lg">

                                <div class="modal-content gallery-modal-content">

                                    {{-- MODAL HEADER --}}
                                    <div class="modal-header gallery-modal-header">

                                        <div class="gallery-modal-title-wrapper">

                                            <div class="gallery-modal-icon">
                                                <i class="ti ti-photo-plus"></i>
                                            </div>

                                            <div>
                                                <h5 class="modal-title" id="addGalleryModalLabel{{ $hospitalFacility->id }}">
                                                    Add Hospital Gallery
                                                </h5>

                                                <p>
                                                    Upload an image or video for this hospital facility.
                                                </p>
                                            </div>

                                        </div>

                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        </button>

                                    </div>



                                    {{-- MODAL BODY --}}
                                    <form action="{{ route('admin.hospital-galleries.store', $hospitalFacility->id) }}"
                                        method="POST" enctype="multipart/form-data">

                                        @csrf

                                        <div class="modal-body gallery-modal-body">

                                            {{-- HOSPITAL ID --}}
                                            <input type="hidden" name="hospital_id" value="{{ $facility->hospital->id ?? '' }}">

                                            {{-- FACILITY ID --}}
                                            <input type="hidden" name="facility_id"
                                                value="{{ $facility->facility_id ?? $facility->id }}">

                                            {{-- HOSPITAL NAME --}}
                                            <input type="hidden" name="hospital"
                                                value="{{ $facility->hospital->hospital_name ?? $facility->hospital->name ?? 'Hospital' }}">
                                            <input type="hidden" name="hospital_facility_list_id"
                                                value="{{$hospitalFacility->id }}">

                                            {{-- FILE TYPE --}}
                                            <div class="gallery-form-group">

                                                <label class="gallery-form-label">
                                                    File Type
                                                    <span>*</span>
                                                </label>

                                                <div class="gallery-type-options">

                                                    {{-- IMAGE --}}
                                                    <label class="gallery-type-option">

                                                        <input type="radio" name="file_type" value="image" checked>

                                                        <span class="gallery-type-card">

                                                            <span class="gallery-type-icon image">
                                                                <i class="ti ti-photo"></i>
                                                            </span>

                                                            <span>
                                                                <strong>Image</strong>
                                                                <small>JPG, PNG, WEBP, GIF</small>
                                                            </span>

                                                        </span>

                                                    </label>


                                                    {{-- VIDEO --}}
                                                    <label class="gallery-type-option">

                                                        <input type="radio" name="file_type" value="video">

                                                        <span class="gallery-type-card">

                                                            <span class="gallery-type-icon video">
                                                                <i class="ti ti-video"></i>
                                                            </span>

                                                            <span>
                                                                <strong>Video</strong>
                                                                <small>MP4, MOV, AVI, WEBM</small>
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

                                                <div class="gallery-upload-box" id="galleryUploadBox">

                                                    <input type="file" name="file_path" id="galleryFile"
                                                        class="gallery-file-input" accept="image/*" required>

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


                                                {{-- FILE PREVIEW --}}
                                                <div id="galleryPreview" class="gallery-preview" style="display:none;">
                                                </div>

                                            </div>

                                        </div>


                                        {{-- MODAL FOOTER --}}
                                        <div class="modal-footer gallery-modal-footer">

                                            <button type="button" class="gallery-cancel-btn" data-bs-dismiss="modal">
                                                Cancel
                                            </button>

                                            <button type="submit" class="gallery-submit-btn">

                                                <i class="ti ti-upload"></i>

                                                Upload Gallery

                                            </button>

                                        </div>

                                    </form>

                                </div>

                            </div>
                        </div>

                    @empty

                        <div class="facility-list-empty">

                            <div class="facility-list-empty-icon">
                                <i class="ti ti-building-hospital"></i>
                            </div>

                            <h5>
                                No Hospital Facilities
                            </h5>

                            <p>
                                No hospital facilities have been added yet.
                            </p>

                            <button type="button" class="add-gallery-btn" data-bs-toggle="modal"
                                data-bs-target="#addFacilityModal">

                                <span class="add-gallery-icon">
                                    <i class="ti ti-plus"></i>
                                </span>

                                <span class="add-gallery-text">
                                    Add Hospital Facility
                                </span>

                            </button>

                        </div>



                    @endforelse

                </div>

            </div>



            {{-- =========================================================
            HOSPITAL GALLERY
            ========================================================= --}}

            <div class="hospital-gallery-card">

                {{-- GALLERY HEADER --}}
                <div class="hospital-gallery-header">

                    {{-- LEFT: TITLE --}}
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




                </div>


                {{-- GALLERY BODY --}}
                {{-- GALLERY BODY --}}
                <div class="hospital-gallery-body" id="hospitalGalleryBody">

                    <div class="hospital-gallery-empty">

                        <div class="hospital-gallery-empty-icon">
                            <i class="ti ti-photo-off"></i>
                        </div>

                        <h5>
                            Select a Facility
                        </h5>

                        <p>
                            Click a facility above to view its gallery.
                        </p>

                    </div>

                </div>

            </div>


        </div>

    </div>


    <!-- Add Hospital Facility Modal -->
    <div class="modal fade" id="addFacilityModal" tabindex="-1" aria-labelledby="addFacilityModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg">

            <div class="modal-content facility-edit-modal">

                {{-- Header --}}
                <div class="modal-header facility-edit-modal-header">

                    <div class="facility-edit-title-wrapper">

                        <div class="facility-edit-icon">
                            <i class="ti ti-building-hospital"></i>
                        </div>

                        <div>
                            <h5 class="modal-title" id="addFacilityModalLabel">
                                Add Hospital Facility
                            </h5>

                            <p>
                                Add a new hospital facility
                            </p>
                        </div>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>

                </div>

                {{-- Form --}}
                <form action="{{ route('admin.hospital-facilities-list.store') }}" method="POST"
                    enctype="multipart/form-data">

                    @csrf

                    <div class="modal-body facility-edit-modal-body">
                        <input type="hidden" name="hospital_id" value="{{ $hospitalId }}">
                        <input type="hidden" name="hospital_facility_id" value="{{ $facility->id }}">
                        {{-- Title --}}
                        <div class="facility-form-group">

                            <label class="facility-form-label">
                                Title
                                <span>*</span>
                            </label>

                            <input type="text" name="title" class="form-control facility-form-control"
                                value="{{ old('title') }}" placeholder="Enter facility title" required>

                            @error('title')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>

                        {{-- Image --}}
                        <div class="facility-form-group">

                            <label class="facility-form-label">
                                Image
                            </label>

                            <input type="file" name="image" class="form-control facility-form-control"
                                accept="image/jpeg,image/png,image/webp">

                            <small class="facility-form-help">
                                JPG, PNG or WEBP. Maximum size 5MB.
                            </small>

                            @error('image')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>

                        {{-- Description --}}
                        <div class="facility-form-group">

                            <label class="facility-form-label">
                                Description
                            </label>

                            <textarea name="description" class="form-control facility-form-control" rows="5"
                                placeholder="Enter facility description">{{ old('description') }}</textarea>

                            @error('description')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="modal-footer facility-edit-modal-footer">

                        <button type="button" class="facility-modal-cancel-btn" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" class="facility-modal-save-btn">

                            <i class="ti ti-device-floppy"></i>

                            Add Facility

                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>
    <style>
        /* =========================================================
                                                   EDIT FACILITY MODAL
                                                ========================================================= */

        .facility-edit-modal {
            border: 0;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 15px 45px rgba(30, 52, 84, 0.15);
        }

        .facility-edit-modal .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid #edf1f6;
            background: #ffffff;
        }

        .facility-edit-modal .modal-title {
            margin: 0;
            color: #1e3454;
            font-size: 18px;
            font-weight: 700;
        }

        .facility-modal-subtitle {
            margin: 4px 0 0;
            color: #98a5b7;
            font-size: 11px;
        }

        .facility-edit-modal .modal-body {
            padding: 24px;
            background: #ffffff;
        }

        .facility-form-label {
            display: block;
            margin-bottom: 7px;
            color: #52647e;
            font-size: 12px;
            font-weight: 600;
        }

        .facility-form-control {
            min-height: 40px;
            border: 1px solid #dfe6ef;
            border-radius: 8px;
            color: #394b63;
            font-size: 12px;
            box-shadow: none;
        }

        .facility-form-control:focus {
            border-color: #8b6bd9;
            box-shadow: 0 0 0 3px rgba(109, 40, 217, 0.08);
        }

        textarea.facility-form-control {
            resize: vertical;
            min-height: 110px;
        }

        .facility-form-help {
            display: block;
            margin-top: 7px;
            color: #9aa7b8;
            font-size: 10px;
        }


        /* CURRENT IMAGE */

        .facility-current-image {
            width: 100%;
            height: 150px;
            overflow: hidden;
            border: 1px solid #e3e9f1;
            border-radius: 10px;
            background: #f5f7fa;
        }

        .facility-current-image img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }

        .facility-current-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
            color: #9aa7b8;
        }

        .facility-current-placeholder i {
            font-size: 35px;
        }

        .facility-current-placeholder span {
            font-size: 11px;
        }


        /* FOOTER */

        .facility-edit-modal .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            padding: 16px 24px;
            border-top: 1px solid #edf1f6;
            background: #fafbfd;
        }

        .facility-modal-cancel,
        .facility-modal-save {
            height: 36px;
            padding: 0 15px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
        }

        .facility-modal-cancel {
            border: 1px solid #dce3ec;
            background: #ffffff;
            color: #65758c;
        }

        .facility-modal-cancel:hover {
            background: #f5f7fa;
        }

        .facility-modal-save {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #6d28d9;
            background: #6d28d9;
            color: #ffffff;
        }

        .facility-modal-save:hover {
            background: #5b21b6;
            border-color: #5b21b6;
        }

        .facility-modal-save i {
            font-size: 14px;
        }


        /* MOBILE */

        @media (max-width: 575px) {

            .facility-edit-modal .modal-header {
                padding: 17px;
            }

            .facility-edit-modal .modal-body {
                padding: 17px;
            }

            .facility-edit-modal .modal-footer {
                padding: 14px 17px;
            }

            .facility-current-image {
                height: 130px;
            }

            .facility-modal-cancel,
            .facility-modal-save {
                flex: 1;
            }

        }
    </style>
    <style>
        /* =========================================================
                                                                   HOSPITAL FACILITIES LIST
                                                                ========================================================= */

        .facility-list-body {
            padding: 18px 25px 25px;
        }


        /* =========================================================
                                                                   FACILITY ROW
                                                                ========================================================= */

        .facility-list-row {
            display: flex;
            align-items: center;
            gap: 18px;

            padding: 16px;

            margin-bottom: 12px;

            border: 1px solid #e5ebf4;
            border-radius: 14px;

            background: #ffffff;

            transition: all 0.2s ease;
        }

        .facility-list-row:last-child {
            margin-bottom: 0;
        }

        .facility-list-row:hover {
            border-color: #d5c6f7;

            box-shadow: 0 6px 20px rgba(46, 42, 90, 0.07);

            transform: translateY(-1px);
        }


        /* =========================================================
                                                                   IMAGE
                                                                ========================================================= */

        .facility-list-image {
            width: 110px;
            height: 90px;

            min-width: 110px;

            overflow: hidden;

            border-radius: 11px;

            background: #f3f5f9;

            border: 1px solid #e5ebf4;
        }

        .facility-list-image a {
            display: block;

            width: 100%;
            height: 100%;
        }

        .facility-list-image img {
            width: 100%;
            height: 100%;

            display: block;

            object-fit: cover;

            transition: transform 0.25s ease;
        }

        .facility-list-row:hover .facility-list-image img {
            transform: scale(1.04);
        }


        /* =========================================================
                                                                   IMAGE PLACEHOLDER
                                                                ========================================================= */

        .facility-list-placeholder {
            width: 100%;
            height: 100%;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #edf5ff;

            color: #6095e6;
        }

        .facility-list-placeholder i {
            font-size: 36px;
        }


        /* =========================================================
                                                                   CONTENT
                                                                ========================================================= */

        .facility-list-content {
            flex: 1;

            min-width: 0;

            padding-right: 15px;
        }


        /* TITLE ROW */

        .facility-list-title-row {
            display: flex;
            align-items: center;

            gap: 10px;

            margin-bottom: 6px;
        }

        .facility-list-title-row h5 {
            margin: 0;

            color: #1e3454;

            font-size: 16px;

            font-weight: 700;

            line-height: 1.4;
        }


        /* BADGE */

        .facility-list-badge {
            display: inline-flex;

            align-items: center;

            gap: 5px;

            padding: 4px 9px;

            border-radius: 15px;

            background: #f0eaff;

            color: #6d28d9;

            font-size: 10px;

            font-weight: 600;

            white-space: nowrap;
        }

        .facility-list-badge i {
            font-size: 12px;
        }


        /* DESCRIPTION */

        .facility-list-content>p {
            margin: 0 0 9px;

            color: #7e8da3;

            font-size: 12px;

            line-height: 1.6;

            max-width: 700px;
        }

        .facility-list-no-description {
            color: #a1adbc !important;

            font-style: italic;
        }


        /* =========================================================
                                                                   META
                                                                ========================================================= */

        .facility-list-meta {
            display: flex;

            align-items: center;

            gap: 18px;

            flex-wrap: wrap;
        }

        .facility-list-meta span {
            display: inline-flex;

            align-items: center;

            gap: 5px;

            color: #9aa7b8;

            font-size: 10px;
        }

        .facility-list-meta i {
            font-size: 13px;

            color: #7c8da8;
        }


        /* =========================================================
                                                                   ACTIONS
                                                                ========================================================= */

        .facility-list-actions {
            display: flex;

            align-items: center;

            gap: 7px;

            min-width: 150px;

            justify-content: flex-end;
        }

        .facility-list-actions form {
            margin: 0;
        }


        /* ACTION BUTTON */

        .facility-list-action {
            height: 34px;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 6px;

            padding: 0 10px;

            border-radius: 8px;

            font-size: 11px;

            font-weight: 600;

            text-decoration: none;

            transition: all 0.2s ease;

            cursor: pointer;
        }


        /* EDIT */

        .facility-list-action.edit {
            border: 1px solid #ddd0f7;

            background: #f7f3ff;

            color: #6d28d9;
        }

        .facility-list-action.edit:hover {
            background: #6d28d9;

            border-color: #6d28d9;

            color: #ffffff;

            box-shadow: 0 4px 10px rgba(109, 40, 217, 0.18);
        }


        /* DELETE */

        .facility-list-action.delete {
            border: 1px solid #f2d2d6;

            background: #fff5f6;

            color: #dc5965;
        }

        .facility-list-action.delete:hover {
            background: #dc5965;

            border-color: #dc5965;

            color: #ffffff;

            box-shadow: 0 4px 10px rgba(220, 89, 101, 0.18);
        }

        .facility-list-action i {
            font-size: 14px;
        }


        /* =========================================================
                                                                   EMPTY
                                                                ========================================================= */

        .facility-list-empty {
            text-align: center;

            padding: 55px 20px;
        }

        .facility-list-empty-icon {
            width: 65px;
            height: 65px;

            margin: 0 auto 13px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 50%;

            background: #f2f5f9;

            color: #a1adbc;
        }

        .facility-list-empty-icon i {
            font-size: 30px;
        }

        .facility-list-empty h5 {
            margin: 0 0 5px;

            color: #52647e;

            font-size: 15px;
        }

        .facility-list-empty p {
            margin: 0 0 16px;

            color: #9aa7b8;

            font-size: 12px;
        }


        /* =========================================================
                                                                   RESPONSIVE
                                                                ========================================================= */

        @media (max-width: 991px) {

            .facility-list-row {
                align-items: flex-start;

                flex-wrap: wrap;
            }

            .facility-list-content {
                flex: 1;
            }

            .facility-list-actions {
                width: 100%;

                min-width: 100%;

                justify-content: flex-end;

                padding-top: 12px;

                border-top: 1px solid #edf1f6;
            }

        }


        @media (max-width: 767px) {

            .facility-list-body {
                padding: 15px;
            }

            .facility-list-row {
                gap: 13px;

                padding: 14px;
            }

            .facility-list-image {
                width: 85px;

                height: 75px;

                min-width: 85px;
            }

            .facility-list-content {
                width: calc(100% - 100px);
            }

            .facility-list-title-row {
                align-items: flex-start;

                flex-direction: column;

                gap: 5px;
            }

            .facility-list-title-row h5 {
                font-size: 14px;
            }

            .facility-list-actions {
                justify-content: stretch;
            }

            .facility-list-action {
                flex: 1;
            }

        }


        @media (max-width: 575px) {

            .facility-list-row {
                flex-direction: column;

                align-items: stretch;
            }

            .facility-list-image {
                width: 100%;

                height: 160px;

                min-width: 100%;
            }

            .facility-list-content {
                width: 100%;

                padding-right: 0;
            }

            .facility-list-actions {
                width: 100%;

                min-width: 100%;

                padding-top: 12px;
            }

            .facility-list-action {
                flex: 1;
            }

        }
    </style>

    <style>
        .facility-edit-modal {
            border: 0;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(31, 56, 88, 0.16);
        }

        .facility-edit-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 23px;
            border-bottom: 1px solid #edf1f6;
            background: #ffffff;
        }

        .facility-edit-title-wrapper {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .facility-edit-icon {
            width: 44px;
            height: 44px;
            min-width: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            background: #f0eaff;
            color: #6d28d9;
        }

        .facility-edit-icon i {
            font-size: 21px;
        }

        .facility-edit-modal-header h5 {
            margin: 0 0 3px;
            color: #172b4d;
            font-size: 17px;
            font-weight: 700;
        }

        .facility-edit-modal-header p {
            margin: 0;
            color: #91a0b5;
            font-size: 11px;
        }

        .facility-edit-modal-body {
            padding: 24px;
            background: #ffffff;
        }

        .facility-form-group {
            margin-bottom: 20px;
        }

        .facility-form-group:last-child {
            margin-bottom: 0;
        }

        .facility-form-label {
            display: block;
            margin-bottom: 8px;
            color: #344563;
            font-size: 12px;
            font-weight: 600;
        }

        .facility-form-label span {
            color: #e05a67;
        }

        .facility-form-control {
            min-height: 43px;
            border: 1px solid #dfe6ef;
            border-radius: 9px;
            color: #52647e;
            font-size: 13px;
            box-shadow: none !important;
        }

        .facility-form-control:focus {
            border-color: #8d68e8;
            box-shadow: 0 0 0 3px rgba(109, 40, 217, 0.08) !important;
        }

        textarea.facility-form-control {
            min-height: 110px;
            resize: vertical;
        }

        .facility-form-help {
            display: block;
            margin-top: 6px;
            color: #9aa7b8;
            font-size: 10px;
        }

        .facility-edit-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 9px;
            padding: 15px 23px;
            border-top: 1px solid #edf1f6;
            background: #fbfcfe;
        }

        .facility-modal-cancel-btn {
            height: 38px;
            padding: 0 16px;
            border: 1px solid #dce5f2;
            border-radius: 8px;
            background: #ffffff;
            color: #52647e;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .facility-modal-cancel-btn:hover {
            background: #f5f7fa;
        }

        .facility-modal-save-btn {
            height: 38px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 0 17px;
            border: 0;
            border-radius: 8px;
            background: #6d28d9;
            color: #ffffff;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .facility-modal-save-btn:hover {
            background: #5b21b6;
            color: #ffffff;
        }

        @media (max-width: 575px) {
            .facility-edit-modal-body {
                padding: 18px;
            }

            .facility-edit-modal-footer {
                padding: 13px 18px;
            }
        }
    </style>
    <style>
        /* =========================================================
                                                                                                                                                                   HOSPITAL GALLERY
                                                                                                                                                                ========================================================= */

        .hospital-gallery-card {
            margin-top: 22px;

            background: #ffffff;

            border: 1px solid #e8edf5;

            border-radius: 18px;

            box-shadow: 0 6px 25px rgba(31, 56, 88, 0.05);

            overflow: hidden;
        }


        /* HEADER */

        .hospital-gallery-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 20px;

            padding: 23px 25px;

            border-bottom: 1px solid #edf1f6;
        }


        /* LEFT SECTION */

        .hospital-section-heading {
            display: flex;
            align-items: center;
            gap: 13px;

            min-width: 0;
        }


        /* RIGHT SECTION */

        .hospital-gallery-header-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;

            gap: 10px;

            margin-left: auto;
        }

        .hospital-gallery-section-icon {
            width: 45px;
            height: 45px;

            min-width: 45px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 12px;

            background: #f0eaff;
            color: #6d28d9;
        }

        .hospital-gallery-section-icon i {
            font-size: 22px;
        }

        .hospital-gallery-count {
            padding: 7px 13px;

            border-radius: 20px;

            background: #f0eaff;
            color: #6d28d9;

            font-size: 12px;
            font-weight: 600;

            white-space: nowrap;
        }


        /* BODY */

        .hospital-gallery-body {
            display: grid;

            grid-template-columns: repeat(4, 1fr);

            gap: 16px;

            padding: 20px 25px 25px;
        }


        /* GALLERY ITEM */

        .hospital-gallery-item {
            min-width: 0;

            border: 1px solid #e5ebf4;

            border-radius: 13px;

            background: #ffffff;

            overflow: hidden;

            transition: all 0.2s ease;
        }

        .hospital-gallery-item:hover {
            border-color: #d5c6f7;

            box-shadow: 0 7px 22px rgba(46, 42, 90, 0.08);

            transform: translateY(-2px);
        }


        /* MEDIA */

        .hospital-gallery-media {
            position: relative;

            width: 100%;
            height: 190px;

            background: #f3f5f9;

            overflow: hidden;
        }

        .gallery-media-image {
            width: 100%;
            height: 100%;

            display: block;

            object-fit: cover;

            cursor: pointer;

            transition: transform 0.25s ease;
        }

        .hospital-gallery-item:hover .gallery-media-image {
            transform: scale(1.03);
        }


        .gallery-media-video {
            width: 100%;
            height: 100%;

            display: block;

            object-fit: cover;

            background: #101318;
        }


        /* TYPE BADGE */

        .hospital-gallery-type {
            position: absolute;

            top: 10px;
            left: 10px;

            display: inline-flex;
            align-items: center;

            gap: 5px;

            padding: 5px 9px;

            border-radius: 15px;

            background: rgba(109, 40, 217, 0.92);

            color: #ffffff;

            font-size: 10px;
            font-weight: 600;
        }

        .hospital-gallery-type i {
            font-size: 13px;
        }

        .hospital-gallery-type.video-type {
            background: rgba(219, 89, 101, 0.92);
        }


        /* DETAILS */

        .hospital-gallery-details {
            padding: 11px 12px;
        }

        .hospital-gallery-hospital {
            display: flex;
            align-items: center;

            gap: 6px;

            margin-bottom: 6px;
        }

        .hospital-gallery-hospital i {
            color: #6d28d9;

            font-size: 14px;
        }

        .hospital-gallery-hospital span {
            overflow: hidden;

            color: #344563;

            font-size: 11px;
            font-weight: 600;

            text-overflow: ellipsis;
            white-space: nowrap;
        }


        .hospital-gallery-date {
            display: flex;
            align-items: center;

            gap: 6px;

            color: #9aa7b8;

            font-size: 10px;
        }

        .hospital-gallery-date i {
            font-size: 13px;
        }


        /* EMPTY */

        .hospital-gallery-empty {
            grid-column: 1 / -1;

            text-align: center;

            padding: 55px 20px;
        }

        .hospital-gallery-empty-icon {
            width: 65px;
            height: 65px;

            margin: 0 auto 13px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #f2f5f9;
            color: #a1adbc;
        }

        .hospital-gallery-empty-icon i {
            font-size: 30px;
        }

        .hospital-gallery-empty h5 {
            margin: 0 0 5px;

            color: #52647e;

            font-size: 15px;
        }

        .hospital-gallery-empty p {
            margin: 0;

            color: #9aa7b8;

            font-size: 12px;
        }


        /* =========================================================
                                                                                                                                                                   GALLERY RESPONSIVE
                                                                                                                                                                ========================================================= */

        @media (max-width: 1199px) {

            .hospital-gallery-body {
                grid-template-columns: repeat(3, 1fr);
            }

        }


        @media (max-width: 991px) {

            .hospital-gallery-body {
                grid-template-columns: repeat(2, 1fr);
            }

        }


        @media (max-width: 575px) {

            .hospital-gallery-header {
                align-items: flex-start;

                flex-direction: column;

                padding: 18px;
            }

            .hospital-gallery-body {
                grid-template-columns: 1fr;

                padding: 15px;
            }

            .hospital-gallery-media {
                height: 220px;
            }

        }
    </style>
    <style>
        /* =========================================================
                                                                                                                                                                               ADD GALLERY MODAL
                                                                                                                                                                            ========================================================= */

        .gallery-modal-content {
            border: 0;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(31, 56, 88, 0.16);
        }


        /* HEADER */

        .gallery-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 20px 23px;

            border-bottom: 1px solid #edf1f6;
            background: #ffffff;
        }

        .gallery-modal-title-wrapper {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .gallery-modal-icon {
            width: 44px;
            height: 44px;

            min-width: 44px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 11px;

            background: #f0eaff;
            color: #6d28d9;
        }

        .gallery-modal-icon i {
            font-size: 21px;
        }

        .gallery-modal-header h5 {
            margin: 0 0 3px;

            color: #172b4d;

            font-size: 17px;
            font-weight: 700;
        }

        .gallery-modal-header p {
            margin: 0;

            color: #91a0b5;

            font-size: 11px;
        }


        /* BODY */

        .gallery-modal-body {
            padding: 24px;
            background: #ffffff;
        }

        .gallery-form-group {
            margin-bottom: 20px;
        }

        .gallery-form-group:last-child {
            margin-bottom: 0;
        }

        .gallery-form-label {
            display: block;

            margin-bottom: 8px;

            color: #344563;

            font-size: 12px;
            font-weight: 600;
        }

        .gallery-form-label span {
            color: #e05a67;
        }

        .gallery-form-control {
            min-height: 43px;

            border: 1px solid #dfe6ef;
            border-radius: 9px;

            color: #52647e;

            font-size: 13px;

            box-shadow: none !important;
        }

        .gallery-form-control:focus {
            border-color: #8d68e8;

            box-shadow: 0 0 0 3px rgba(109, 40, 217, 0.08) !important;
        }


        /* FILE TYPE */

        .gallery-type-options {
            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 12px;
        }

        .gallery-type-option {
            position: relative;

            margin: 0;

            cursor: pointer;
        }

        .gallery-type-option input {
            position: absolute;

            opacity: 0;
        }

        .gallery-type-card {
            display: flex;
            align-items: center;

            gap: 11px;

            padding: 12px 14px;

            border: 1px solid #e2e7ef;
            border-radius: 10px;

            background: #ffffff;

            transition: all 0.2s ease;
        }

        .gallery-type-option input:checked+.gallery-type-card {
            border-color: #8d68e8;

            background: #f8f5ff;

            box-shadow: 0 0 0 2px rgba(109, 40, 217, 0.06);
        }

        .gallery-type-icon {
            width: 38px;
            height: 38px;

            min-width: 38px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 9px;
        }

        .gallery-type-icon.image {
            background: #edf5ff;
            color: #397fe5;
        }

        .gallery-type-icon.video {
            background: #fff0f4;
            color: #db5965;
        }

        .gallery-type-icon i {
            font-size: 18px;
        }

        .gallery-type-card strong {
            display: block;

            color: #344563;

            font-size: 12px;
            font-weight: 700;
        }

        .gallery-type-card small {
            display: block;

            margin-top: 2px;

            color: #9aa7b8;

            font-size: 10px;
        }


        /* UPLOAD */

        .gallery-upload-box {
            position: relative;

            min-height: 150px;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 20px;

            border: 1.5px dashed #cfd8e6;
            border-radius: 12px;

            background: #fafbfe;

            cursor: pointer;

            transition: all 0.2s ease;
        }

        .gallery-upload-box:hover {
            border-color: #8d68e8;
            background: #faf8ff;
        }

        .gallery-file-input {
            position: absolute;

            inset: 0;

            width: 100%;
            height: 100%;

            opacity: 0;

            cursor: pointer;
        }

        .gallery-upload-content {
            text-align: center;

            pointer-events: none;
        }

        .gallery-upload-icon {
            width: 45px;
            height: 45px;

            margin: 0 auto 8px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #eee7ff;
            color: #6d28d9;
        }

        .gallery-upload-icon i {
            font-size: 21px;
        }

        .gallery-upload-content h6 {
            margin: 0 0 4px;

            color: #344563;

            font-size: 13px;
            font-weight: 700;
        }

        .gallery-upload-content p {
            margin: 0 0 4px;

            color: #7e8da3;

            font-size: 11px;
        }

        .gallery-upload-content>span {
            color: #a1adbc;

            font-size: 10px;
        }


        /* PREVIEW */

        .gallery-preview {
            margin-top: 12px;

            padding: 10px;

            border: 1px solid #e5ebf4;
            border-radius: 10px;

            background: #fafbfe;
        }

        .gallery-preview img,
        .gallery-preview video {
            display: block;

            width: 100%;
            max-height: 220px;

            object-fit: contain;

            border-radius: 8px;
        }


        /* FOOTER */

        .gallery-modal-footer {
            display: flex;
            justify-content: flex-end;

            gap: 9px;

            padding: 15px 23px;

            border-top: 1px solid #edf1f6;

            background: #fbfcfe;
        }

        .gallery-cancel-btn {
            height: 38px;

            padding: 0 16px;

            border: 1px solid #dce5f2;
            border-radius: 8px;

            background: #ffffff;
            color: #52647e;

            font-size: 12px;
            font-weight: 600;
        }

        .gallery-submit-btn {
            height: 38px;

            display: inline-flex;
            align-items: center;

            gap: 7px;

            padding: 0 17px;

            border: 0;
            border-radius: 8px;

            background: #6d28d9;
            color: #ffffff;

            font-size: 12px;
            font-weight: 600;

            transition: all 0.2s ease;
        }

        .gallery-submit-btn:hover {
            background: #5b21b6;
            color: #ffffff;
        }


        /* RESPONSIVE */

        @media (max-width: 575px) {

            .gallery-modal-body {
                padding: 18px;
            }

            .gallery-type-options {
                grid-template-columns: 1fr;
            }

            .gallery-modal-footer {
                padding: 13px 18px;
            }

        }
    </style>

    <style>
        .hospital-action-buttons {
            display: flex;
            align-items: center;
            margin: 12px;
            justify-content: end;
        }

        .add-gallery-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;

            height: 34px;
            padding: 0 13px;

            border: 1px solid #ddd0f7;
            border-radius: 8px;

            background: #f7f3ff;
            color: #6d28d9 !important;

            font-size: 12px;
            font-weight: 600;

            text-decoration: none !important;

            transition: all 0.2s ease;
        }

        .add-gallery-icon {
            width: 22px;
            height: 22px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border-radius: 6px;

            background: #eee6ff;
            color: #6d28d9;
        }

        .add-gallery-icon i {
            font-size: 14px;
        }

        .add-gallery-text {
            line-height: 1;
        }

        .add-gallery-btn:hover {
            background: #6d28d9;
            border-color: #6d28d9;

            color: #ffffff !important;

            box-shadow: 0 4px 10px rgba(109, 40, 217, 0.20);

            transform: translateY(-1px);
        }

        .add-gallery-btn:hover .add-gallery-icon {
            background: rgba(255, 255, 255, 0.18);
            color: #ffffff;
        }
    </style>


    <style>
        /* =========================================================
                                                                                                                                                                                                       PAGE HEADER
                                                                                                                                                                                                    ========================================================= */

        .facility-page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;

            margin-bottom: 22px;
        }

        .facility-breadcrumb {
            display: flex;
            align-items: center;
            gap: 7px;

            color: #71809a;
            font-size: 12px;
            font-weight: 500;

            margin-bottom: 7px;
        }

        .facility-breadcrumb i:first-child {
            color: #4f8df7;
        }

        .facility-breadcrumb i {
            font-size: 14px;
        }

        .facility-page-title {
            margin: 0;

            color: #172b4d;

            font-size: 26px;
            font-weight: 700;
        }

        .facility-page-subtitle {
            margin: 4px 0 0;

            color: #8a98ad;
            font-size: 13px;
        }

        .facility-back-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;

            padding: 10px 18px;

            border: 1px solid #dce5f2;
            border-radius: 10px;

            background: #ffffff;
            color: #52647e;

            font-size: 13px;
            font-weight: 600;

            text-decoration: none;

            transition: all 0.2s ease;
        }

        .facility-back-btn:hover {
            background: #f4f8ff;
            color: #3978e8;
            border-color: #c8daf8;
        }


        /* =========================================================
                                                                                                                                                                                                       MAIN FACILITY CARD
                                                                                                                                                                                                    ========================================================= */

        .facility-details-card {
            background: #ffffff;

            border: 1px solid #e8edf5;

            border-radius: 18px;

            box-shadow: 0 6px 25px rgba(31, 56, 88, 0.05);

            margin-bottom: 22px;
        }

        .facility-details-body {
            padding: 28px;
        }


        /* =========================================================
                                                                                                                                                                                                       FACILITY TOP
                                                                                                                                                                                                    ========================================================= */

        .facility-top-section {
            display: flex;
            align-items: center;
            gap: 25px;
        }


        /* SMALL IMAGE */

        .facility-image-box {
            width: 155px;
            height: 155px;

            min-width: 155px;

            overflow: hidden;

            border-radius: 16px;

            background: #f1f5fb;

            border: 1px solid #e4ebf5;
        }

        .facility-image-box img {
            width: 100%;
            height: 100%;

            display: block;

            object-fit: cover;
        }

        .facility-image-placeholder {
            width: 100%;
            height: 100%;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #edf5ff;
        }

        .facility-image-placeholder i {
            font-size: 55px;
            color: #5891ed;
        }


        /* FACILITY CONTENT */

        .facility-main-content {
            flex: 1;
            min-width: 0;
        }

        .facility-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;

            padding: 6px 11px;

            border-radius: 20px;

            background: #edf5ff;
            color: #397fe5;

            font-size: 12px;
            font-weight: 600;

            margin-bottom: 9px;
        }

        .facility-main-content h1 {
            margin: 0 0 9px;

            color: #172b4d;

            font-size: 27px;
            line-height: 1.3;

            font-weight: 700;
        }

        .facility-short-description {
            max-width: 850px;

            margin: 0;

            color: #71809a;

            font-size: 14px;
            line-height: 1.7;
        }


        /* =========================================================
                                                                                                                                                                                                       FACILITY INFORMATION
                                                                                                                                                                                                    ========================================================= */

        .facility-info-grid {
            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 15px;

            margin-top: 25px;
        }

        .facility-info-item {
            display: flex;
            align-items: center;
            gap: 13px;

            min-height: 76px;

            padding: 13px 15px;

            border: 1px solid #e9eef5;

            border-radius: 13px;

            background: #fbfcfe;
        }

        .facility-info-icon {
            width: 42px;
            height: 42px;

            min-width: 42px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 11px;

            background: #edf5ff;
            color: #4b89ea;
        }

        .facility-info-icon i {
            font-size: 20px;
        }

        .facility-info-item span {
            display: block;

            color: #91a0b5;

            font-size: 11px;
            font-weight: 500;

            margin-bottom: 4px;
        }

        .facility-info-item strong {
            display: block;

            color: #263a59;

            font-size: 13px;
            font-weight: 600;
        }

        .status-active {
            color: #18a77a !important;
        }

        .status-inactive {
            color: #e05a67 !important;
        }


        /* =========================================================
                                                                                                                                                                                                       DESCRIPTION
                                                                                                                                                                                                    ========================================================= */

        .facility-description-box {
            display: flex;
            align-items: flex-start;
            gap: 13px;

            margin-top: 15px;

            padding: 17px 18px;

            border-radius: 13px;

            background: #f5f9ff;

            border: 1px solid #e1edff;
        }

        .description-icon {
            width: 36px;
            height: 36px;

            min-width: 36px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 9px;

            background: #e5f0ff;
            color: #4b89ea;
        }

        .description-icon i {
            font-size: 18px;
        }

        .facility-description-box h5 {
            margin: 0 0 4px;

            color: #273b5a;

            font-size: 14px;
            font-weight: 700;
        }

        .facility-description-box p {
            margin: 0;

            color: #71809a;

            font-size: 13px;
            line-height: 1.6;
        }


        /* =========================================================
                                                                                                                                                                                                       HOSPITAL LIST CARD
                                                                                                                                                                                                    ========================================================= */

        .hospital-list-card {
            background: #ffffff;

            border: 1px solid #e8edf5;

            border-radius: 18px;

            box-shadow: 0 6px 25px rgba(31, 56, 88, 0.05);

            overflow: hidden;
        }


        /* HEADER */

        .hospital-list-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 20px;

            padding: 23px 25px;

            border-bottom: 1px solid #edf1f6;
        }

        .hospital-section-heading {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .hospital-section-icon {
            width: 45px;
            height: 45px;

            min-width: 45px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 12px;

            background: #eafaf5;
            color: #18a77a;
        }

        .hospital-section-icon i {
            font-size: 22px;
        }

        .hospital-section-heading h4 {
            margin: 0 0 3px;

            color: #172b4d;

            font-size: 19px;
            font-weight: 700;
        }

        .hospital-section-heading p {
            margin: 0;

            color: #91a0b5;

            font-size: 12px;
        }

        .hospital-count {
            padding: 7px 13px;

            border-radius: 20px;

            background: #eafaf5;
            color: #15976f;

            font-size: 12px;
            font-weight: 600;

            white-space: nowrap;
        }


        /* =========================================================
                                                                                                                                                                                                       HOSPITAL LIST BODY
                                                                                                                                                                                                    ========================================================= */

        .hospital-list-body {
            padding: 18px 25px 25px;
        }


        /* =========================================================
                                                                                                                                                                                                       HOSPITAL ITEM
                                                                                                                                                                                                    ========================================================= */

        .hospital-item {
            display: flex;
            align-items: center;

            gap: 18px;

            padding: 17px;

            border: 1px solid #e5ebf4;

            border-radius: 14px;

            background: #ffffff;

            margin-bottom: 13px;

            transition: all 0.2s ease;
        }

        .hospital-item:last-child {
            margin-bottom: 0;
        }

        .hospital-item:hover {
            border-color: #cfe0f8;

            box-shadow: 0 6px 20px rgba(46, 85, 130, 0.06);

            transform: translateY(-1px);
        }


        /* HOSPITAL IMAGE */

        .hospital-item-image {
            width: 125px;
            height: 105px;

            min-width: 125px;

            overflow: hidden;

            border-radius: 11px;

            background: #f2f6fb;
        }

        .hospital-item-image img {
            width: 100%;
            height: 100%;

            display: block;

            object-fit: cover;
        }

        .hospital-placeholder {
            width: 100%;
            height: 100%;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #edf5ff;
        }

        .hospital-placeholder i {
            font-size: 40px;
            color: #6095e6;
        }


        /* HOSPITAL CONTENT */

        .hospital-item-content {
            flex: 1;
            min-width: 0;

            padding-right: 15px;
        }

        .hospital-item-content h5 {
            margin: 0 0 7px;

            color: #1e3454;

            font-size: 16px;
            font-weight: 700;
        }

        .hospital-type-badge {
            display: inline-block;

            padding: 4px 9px;

            border-radius: 15px;

            background: #f0edff;
            color: #7258d8;

            font-size: 10px;
            font-weight: 600;

            margin-bottom: 7px;
        }

        .hospital-short-description {
            margin: 0;

            color: #7e8da3;

            font-size: 12px;
            line-height: 1.6;

            max-width: 650px;
        }


        /* =========================================================
                                                                                                                                                                                                       HOSPITAL META
                                                                                                                                                                                                    ========================================================= */

        .hospital-item-meta {
            width: 225px;
            min-width: 225px;

            padding-left: 20px;

            border-left: 1px solid #e9eef5;
        }

        .hospital-meta-row {
            display: flex;
            align-items: center;

            gap: 8px;

            margin-bottom: 9px;
        }

        .hospital-meta-row:last-of-type {
            margin-bottom: 10px;
        }

        .hospital-meta-row i {
            color: #6686ad;

            font-size: 15px;
        }

        .hospital-meta-row span {
            color: #71809a;

            font-size: 11px;
        }


        /* STATUS */

        .hospital-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;

            padding: 5px 10px;

            border-radius: 15px;

            font-size: 10px;
            font-weight: 600;
        }

        .hospital-status.active {
            background: #e9faf4;
            color: #159a72;
        }

        .hospital-status.inactive {
            background: #fff0f1;
            color: #db5965;
        }


        /* =========================================================
                                                                                                                                                                                                       EMPTY STATE
                                                                                                                                                                                                    ========================================================= */

        .facility-empty-state {
            text-align: center;

            padding: 55px 20px;
        }

        .facility-empty-icon {
            width: 65px;
            height: 65px;

            margin: 0 auto 13px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #f2f5f9;
            color: #a1adbc;
        }

        .facility-empty-icon i {
            font-size: 30px;
        }

        .facility-empty-state h5 {
            margin: 0 0 5px;

            color: #52647e;

            font-size: 15px;
        }

        .facility-empty-state p {
            margin: 0;

            color: #9aa7b8;

            font-size: 12px;
        }


        /* =========================================================
                                                                                                                                                                                                       RESPONSIVE
                                                                                                                                                                                                    ========================================================= */

        @media (max-width: 991px) {

            .facility-info-grid {
                grid-template-columns: 1fr;
            }

            .hospital-item-meta {
                width: 190px;
                min-width: 190px;
            }

        }


        @media (max-width: 767px) {

            .facility-page-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .facility-back-btn {
                width: 100%;
                justify-content: center;
            }

            .facility-details-body {
                padding: 20px;
            }

            .facility-top-section {
                align-items: flex-start;
                flex-direction: column;
            }

            .facility-image-box {
                width: 115px;
                height: 115px;
                min-width: 115px;
            }

            .facility-main-content h1 {
                font-size: 22px;
            }

            .hospital-list-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .hospital-list-body {
                padding: 15px;
            }

            .hospital-item {
                align-items: flex-start;
                flex-direction: column;
            }

            .hospital-item-image {
                width: 100%;
                height: 170px;
            }

            .hospital-item-content {
                width: 100%;
                padding-right: 0;
            }

            .hospital-item-meta {
                width: 100%;
                min-width: 100%;

                padding-left: 0;
                padding-top: 15px;

                border-left: 0;
                border-top: 1px solid #e9eef5;
            }

        }


        @media (max-width: 575px) {

            .facility-page-title {
                font-size: 22px;
            }

            .facility-page-subtitle {
                font-size: 12px;
            }

            .facility-details-body {
                padding: 15px;
            }

            .facility-image-box {
                width: 100px;
                height: 100px;
                min-width: 100px;
            }

            .facility-main-content h1 {
                font-size: 20px;
            }

            .facility-info-item {
                min-height: 68px;
            }

            .hospital-section-heading h4 {
                font-size: 16px;
            }

        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const fileInput = document.getElementById('galleryFile');
            const uploadText = document.getElementById('galleryUploadText');
            const previewBox = document.getElementById('galleryPreview');

            const typeInputs = document.querySelectorAll(
                'input[name="file_type"]'
            );


            /* =========================
               FILE TYPE
            ========================= */

            typeInputs.forEach(function (input) {

                input.addEventListener('change', function () {

                    // Clear previous selected file
                    fileInput.value = '';

                    // Clear previous preview
                    previewBox.innerHTML = '';
                    previewBox.style.display = 'none';


                    if (this.value === 'image') {

                        fileInput.accept =
                            'image/jpeg,image/png,image/webp,image/gif';

                        uploadText.textContent =
                            'Select an image from your computer';

                    } else {

                        // Allow all browser-supported video formats
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


                // Show selected file name
                uploadText.textContent = file.name;


                // Clear previous preview
                previewBox.innerHTML = '';


                // Get selected file type
                const selectedType = document.querySelector(
                    'input[name="file_type"]:checked'
                ).value;


                /* =========================
                   IMAGE PREVIEW
                ========================= */

                if (selectedType === 'image') {

                    const image = document.createElement('img');

                    image.src = URL.createObjectURL(file);

                    image.alt = 'Gallery Preview';

                    image.style.maxWidth = '100%';
                    image.style.maxHeight = '250px';
                    image.style.objectFit = 'contain';
                    image.style.display = 'block';
                    image.style.margin = '0 auto';
                    image.style.borderRadius = '8px';

                    previewBox.appendChild(image);

                }


                /* =========================
                   VIDEO PREVIEW
                ========================= */

                else {

                    const video = document.createElement('video');

                    video.src = URL.createObjectURL(file);

                    video.controls = true;

                    video.preload = 'metadata';

                    video.style.width = '100%';
                    video.style.maxWidth = '100%';
                    video.style.maxHeight = '300px';
                    video.style.display = 'block';
                    video.style.borderRadius = '8px';

                    previewBox.appendChild(video);
                }


                // Show preview
                previewBox.style.display = 'block';

            });

        });
    </script>
   <script>
    const facilityGalleryData = @json($facilityGalleryData ?? []);

    function showFacilityGallery(facilityId) {

        const section = document.getElementById('facilityGallerySection');
        const title = document.getElementById('facilityGalleryTitle');
        const count = document.getElementById('facilityGalleryCount');
        const body = document.getElementById('hospitalGalleryBody');

        if (!body) {
            console.error('hospitalGalleryBody not found');
            return;
        }

        const selectedFacilityId = String(facilityId);
        const facility = facilityGalleryData[selectedFacilityId];

        if (!facility) {

            if (title) {
                title.textContent = 'Gallery';
            }

            if (count) {
                count.textContent = '0 Files';
            }

            body.innerHTML = `
                <div class="hospital-gallery-empty">
                    <div class="hospital-gallery-empty-icon">
                        <i class="ti ti-photo-off"></i>
                    </div>

                    <h5>No Gallery Files</h5>

                    <p>
                        No images or videos have been uploaded for this facility yet.
                    </p>
                </div>
            `;

            return;
        }

        if (title) {
            title.textContent = 'Gallery - ' + facility.title;
        }

        const galleries = Array.isArray(facility.galleries)
            ? facility.galleries
            : [];

        if (count) {
            count.textContent =
                galleries.length +
                (galleries.length === 1 ? ' File' : ' Files');
        }

        body.innerHTML = '';

        if (galleries.length === 0) {

            body.innerHTML = `
                <div class="hospital-gallery-empty">
                    <div class="hospital-gallery-empty-icon">
                        <i class="ti ti-photo-off"></i>
                    </div>

                    <h5>No Gallery Files</h5>

                    <p>
                        No images or videos have been uploaded for this facility yet.
                    </p>
                </div>
            `;

            return;
        }

        galleries.forEach(function (gallery) {

            const item = document.createElement('div');

            // KEEP EXISTING CLASS
            item.className = 'hospital-gallery-item';

            let mediaHtml = '';

            // IMAGE
            if (gallery.file_type === 'image') {

                mediaHtml = `
                    <div class="hospital-gallery-media">

                        <a
                            href="${gallery.file_path}"
                            target="_blank"
                            rel="noopener noreferrer"
                            style="display: block; width: 100%; height: 100%;"
                        >

                            <img
                                src="${gallery.file_path}"
                                alt="${facility.title}"
                                class="gallery-media-image"
                                onclick="openGalleryPreview('${gallery.file_path}', 'image')"
                            >

                        </a>

                        <div class="hospital-gallery-type">
                            <i class="ti ti-photo"></i>
                            Image
                        </div>

                    </div>
                `;
            }

            // VIDEO
            else if (gallery.file_type === 'video') {

                mediaHtml = `
                    <div class="hospital-gallery-media">

                        <a
                            href="${gallery.file_path}"
                            target="_blank"
                            rel="noopener noreferrer"
                            style="display: block; width: 100%; height: 100%;"
                        >

                            <video
                                class="gallery-media-video"
                                autoplay
                                muted
                                loop
                                playsinline
                                preload="metadata"
                                style="
                                    width: 100%;
                                    height: 100%;
                                    object-fit: cover;
                                    cursor: pointer;
                                "
                            >
                                <source
                                    src="${gallery.file_path}"
                                    type="video/mp4"
                                >
                            </video>

                        </a>

                        <div class="hospital-gallery-type video-type">
                            <i class="ti ti-video"></i>
                            Video
                        </div>

                    </div>
                `;
            }

            // DELETE FORM
            const deleteHtml = `
                <form
                    action="${gallery.delete_url}"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to remove this gallery file?');"
                    style="
                        position: absolute;
                        top: 10px;
                        right: 10px;
                        z-index: 10;
                    "
                >

                    <input
                        type="hidden"
                        name="_token"
                        value="{{ csrf_token() }}"
                    >

                    <input
                        type="hidden"
                        name="_method"
                        value="DELETE"
                    >

                    <button
                        type="submit"
                        title="Remove"
                        style="
                            width: 32px;
                            height: 32px;
                            padding: 0;
                            border: 0;
                            border-radius: 6px;
                            background: #dc3545;
                            color: #fff;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            cursor: pointer;
                        "
                    >
                        <i
                            class="ti ti-trash"
                            style="font-size: 16px;"
                        ></i>
                    </button>

                </form>
            `;

            item.innerHTML = deleteHtml + mediaHtml;

            body.appendChild(item);
        });

        if (section) {
            section.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }
</script>

@endsection