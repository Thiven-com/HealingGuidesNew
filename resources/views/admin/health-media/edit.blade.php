
@extends('layout.mainlayout')

@section('content')
    <div class="page-wrapper">
        <div class="content">

            {{-- Page Header --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Edit Health Media</h4>
                    <p class="text-muted mb-0">
                        Update health awareness images and medical information.
                    </p>
                </div>

                <a href="{{ route('admin.health-media.index') }}"
                   class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>
                    Back to Health Media
                </a>
            </div>

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Please correct the following errors.</strong>

                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-12 col-xl-9">

                    <div class="card border-0 shadow-sm health-media-card">

                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-1 fw-semibold">
                                Health Media Information
                            </h5>

                            <small class="text-muted">
                                Update the details and manage the uploaded image.
                            </small>
                        </div>

                        <div class="card-body p-4">

                            <form action="{{ route('admin.health-media.update', $healthMedia->id) }}"
                                  method="POST"
                                  enctype="multipart/form-data">

                                @csrf
                                @method('PUT')

                                <div class="row">

                                    {{-- Name --}}
                                    <div class="col-md-6 mb-4">
                                        <label for="name" class="form-label fw-semibold">
                                            Media Name <span class="text-danger">*</span>
                                        </label>

                                        <input type="text"
                                               name="name"
                                               id="name"
                                               class="form-control @error('name') is-invalid @enderror"
                                               value="{{ old('name', $healthMedia->name) }}"
                                               placeholder="Enter health media name"
                                               maxlength="255"
                                               required>

                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                        <small class="text-muted">
                                            Example: Diabetes Awareness, Healthy Lifestyle.
                                        </small>
                                    </div>

                                    {{-- Status --}}
                                    <div class="col-md-6 mb-4">
                                        <label for="status" class="form-label fw-semibold">
                                            Status <span class="text-danger">*</span>
                                        </label>

                                        <select name="status"
                                                id="status"
                                                class="form-select @error('status') is-invalid @enderror"
                                                required>

                                            <option value="active"
                                                {{ old('status', $healthMedia->status ?? 'active') == 'active' ? 'selected' : '' }}>
                                                Active
                                            </option>

                                            <option value="inactive"
                                                {{ old('status', $healthMedia->status ?? 'active') == 'inactive' ? 'selected' : '' }}>
                                                Inactive
                                            </option>
                                        </select>

                                        @error('status')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                </div>

                                {{-- Description --}}
                                <div class="mb-4">
                                    <label for="description" class="form-label fw-semibold">
                                        Description
                                    </label>

                                    <textarea name="description"
                                              id="description"
                                              rows="5"
                                              class="form-control @error('description') is-invalid @enderror"
                                              placeholder="Enter health media description...">{{ old('description', $healthMedia->description) }}</textarea>

                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- Current Image --}}
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        Current Health Media Image
                                    </label>

                                    <div class="health-upload-box">

                                        @if ($healthMedia->image)
                                            <img src="{{ asset($healthMedia->image) }}"
                                                 id="currentImagePreview"
                                                 alt="{{ $healthMedia->name }}"
                                                 class="current-image-preview">

                                            <p class="text-muted small mt-3 mb-0">
                                                Currently uploaded image
                                            </p>
                                        @else
                                            <div class="text-muted">
                                                <i class="ti ti-photo fs-1"></i>
                                                <p class="mb-0">No image uploaded.</p>
                                            </div>
                                        @endif

                                    </div>
                                </div>

                                {{-- Replace Image --}}
                                <div class="mb-4">
                                    <label for="image" class="form-label fw-semibold">
                                        Replace Health Media Image
                                    </label>

                                    <div class="health-upload-box" id="uploadBox">

                                        <input type="file"
                                               name="image"
                                               id="image"
                                               class="d-none @error('image') is-invalid @enderror"
                                               accept="image/jpeg,image/png,image/webp">

                                        <div id="uploadPlaceholder">
                                            <div class="upload-icon">
                                                <i class="ti ti-cloud-upload"></i>
                                            </div>

                                            <h6 class="fw-semibold mt-3 mb-2">
                                                Upload a new image
                                            </h6>

                                            <p class="text-muted mb-3">
                                                The current image will remain unchanged
                                                unless you select a replacement.
                                            </p>

                                            <button type="button"
                                                    class="btn btn-outline-primary"
                                                    id="browseImage">
                                                <i class="ti ti-photo me-1"></i>
                                                Choose Image
                                            </button>

                                            <p class="text-muted small mt-3 mb-0">
                                                JPG, PNG or WEBP — Maximum 5 MB
                                            </p>
                                        </div>

                                        <div id="previewContainer" class="d-none">

                                            <img id="imagePreview"
                                                 src=""
                                                 alt="New health media image preview">

                                            <div class="mt-3">
                                                <span id="fileName"
                                                      class="small text-break"></span>
                                            </div>

                                            <div class="mt-3 d-flex justify-content-center gap-2">
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-primary"
                                                        id="changeImage">
                                                    Change Image
                                                </button>

                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger"
                                                        id="removeImage">
                                                    Remove
                                                </button>
                                            </div>

                                        </div>
                                    </div>

                                    @error('image')
                                        <div class="text-danger small mt-2">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- Actions --}}
                                <div class="d-flex flex-wrap justify-content-end gap-2 pt-3 border-top">

                                    <a href="{{ route('admin.health-media.index') }}"
                                       class="btn btn-light border px-4">
                                        Cancel
                                    </a>

                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="ti ti-device-floppy me-1"></i>
                                        Update Health Media
                                    </button>

                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                {{-- Information Card --}}
                <div class="col-12 col-xl-3 mt-4 mt-xl-0">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">

                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="ti ti-info-circle text-primary fs-4"></i>
                                <h6 class="fw-bold mb-0">Guidelines</h6>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <h6 class="small fw-semibold">
                                    <i class="ti ti-check-circle text-success me-1"></i>
                                    Media Name
                                </h6>

                                <p class="text-muted small mb-0">
                                    Use a short, clear, and meaningful title.
                                </p>
                            </div>

                            <div class="mb-3">
                                <h6 class="small fw-semibold">
                                    <i class="ti ti-check-circle text-success me-1"></i>
                                    Description
                                </h6>

                                <p class="text-muted small mb-0">
                                    Provide relevant information about the health topic.
                                </p>
                            </div>

                            <div class="mb-3">
                                <h6 class="small fw-semibold">
                                    <i class="ti ti-check-circle text-success me-1"></i>
                                    Image Quality
                                </h6>

                                <p class="text-muted small mb-0">
                                    Use a clear image suitable for your healthcare website.
                                </p>
                            </div>

                            <div class="mb-0">
                                <h6 class="small fw-semibold">
                                    <i class="ti ti-check-circle text-success me-1"></i>
                                    Status
                                </h6>

                                <p class="text-muted small mb-0">
                                    Active media is available for display according to your
                                    application logic. Inactive media can be hidden.
                                </p>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .health-media-card {
            border-radius: 12px;
            overflow: hidden;
        }

        .health-upload-box {
            border: 2px dashed #d6dce5;
            border-radius: 12px;
            padding: 32px 20px;
            text-align: center;
            background: #fafbfe;
            transition: border-color .2s ease, background .2s ease;
        }

        .health-upload-box:hover {
            border-color: #6571ff;
            background: #f7f8ff;
        }

        .upload-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eef0ff;
            border-radius: 50%;
            color: #6571ff;
            font-size: 30px;
        }

        .current-image-preview,
        #imagePreview {
            display: block;
            max-width: 100%;
            max-height: 300px;
            margin: 0 auto;
            border-radius: 8px;
            object-fit: contain;
        }

        .health-media-card .form-control,
        .health-media-card .form-select {
            min-height: 44px;
            border-radius: 7px;
        }

        .health-media-card textarea.form-control {
            min-height: 120px;
        }

        .health-media-card .form-control:focus,
        .health-media-card .form-select:focus {
            border-color: #6571ff;
            box-shadow: 0 0 0 .15rem rgba(101, 113, 255, .12);
        }

        @media (max-width: 575px) {
            .health-upload-box {
                padding: 24px 12px;
            }

            .health-media-card .card-body {
                padding: 20px !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const imageInput = document.getElementById('image');
            const browseButton = document.getElementById('browseImage');
            const changeButton = document.getElementById('changeImage');
            const removeButton = document.getElementById('removeImage');
            const placeholder = document.getElementById('uploadPlaceholder');
            const previewContainer = document.getElementById('previewContainer');
            const preview = document.getElementById('imagePreview');
            const fileName = document.getElementById('fileName');

            let previewUrl = null;

            function openFilePicker() {
                imageInput.click();
            }

            function clearPreview() {
                if (previewUrl) {
                    URL.revokeObjectURL(previewUrl);
                    previewUrl = null;
                }

                imageInput.value = '';
                preview.removeAttribute('src');
                fileName.textContent = '';

                placeholder.classList.remove('d-none');
                previewContainer.classList.add('d-none');
            }

            browseButton.addEventListener('click', openFilePicker);
            changeButton.addEventListener('click', openFilePicker);
            removeButton.addEventListener('click', clearPreview);

            imageInput.addEventListener('change', function () {
                const file = this.files[0];

                if (!file) {
                    return;
                }

                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ];

                if (!allowedTypes.includes(file.type)) {
                    alert('Please select a JPG, PNG, or WEBP image.');
                    clearPreview();
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    alert('Image size must not exceed 5 MB.');
                    clearPreview();
                    return;
                }

                if (previewUrl) {
                    URL.revokeObjectURL(previewUrl);
                }

                previewUrl = URL.createObjectURL(file);
                preview.src = previewUrl;
                fileName.textContent = file.name;

                placeholder.classList.add('d-none');
                previewContainer.classList.remove('d-none');
            });
        });
    </script>
@endsection