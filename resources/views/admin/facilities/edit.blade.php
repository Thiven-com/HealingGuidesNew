@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">

    <div class="content">

        <!-- Page Header -->
        <div class="page-header">

            <div class="page-title">
                <h4>Edit Facility</h4>
                <h6>Update hospital facility</h6>
            </div>

            <div class="page-btn">
                <a href="{{ route('admin.facilities.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i>
                    Back
                </a>
            </div>

        </div>

        <!-- Validation Errors -->
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Edit Facility Form -->
        <div class="card">

            <div class="card-header">
                <h5 class="mb-0">
                    Facility Information
                </h5>
            </div>

            <div class="card-body">

                <form
                    action="{{ route('admin.facilities.update', $facility->id) }}"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    @csrf
                    @method('PUT')

                    <div class="row">

                        <!-- Name -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Facility Name
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                id="facilityName"
                                class="form-control"
                                value="{{ old('name', $facility->name) }}"
                                placeholder="Enter facility name"
                                required
                            >

                        </div>

                        <!-- Slug -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Slug
                            </label>

                            <input
                                type="text"
                                id="slug"
                                class="form-control"
                                value="{{ old('slug', $facility->slug) }}"
                                placeholder="Slug will be generated automatically"
                                readonly
                            >

                            <small class="text-muted">
                                Slug will be generated automatically from the facility name.
                            </small>

                        </div>

                        <!-- Description -->
                        <div class="col-md-12 mb-3">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea
                                name="description"
                                class="form-control"
                                rows="5"
                                placeholder="Enter facility description"
                            >{{ old('description', $facility->description) }}</textarea>

                        </div>

                        <!-- Current Image -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Current Image
                            </label>

                            <div>

                                @if($facility->image)

                                    <img
                                        src="{{ asset($facility->image) }}"
                                        alt="{{ $facility->name }}"
                                        class="img-thumbnail"
                                        style="
                                            width:150px;
                                            height:150px;
                                            object-fit:cover;
                                        "
                                    >

                                @else

                                    <div
                                        class="d-flex align-items-center justify-content-center bg-light rounded"
                                        style="
                                            width:150px;
                                            height:150px;
                                        "
                                    >
                                        <i class="ti ti-photo text-muted fs-40"></i>
                                    </div>

                                @endif

                            </div>

                        </div>

                        <!-- New Image -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Change Image
                            </label>

                            <input
                                type="file"
                                name="image"
                                id="image"
                                class="form-control"
                                accept="image/jpeg,image/jpg,image/png,image/webp"
                            >

                            <small class="text-muted">
                                JPG, JPEG, PNG or WEBP. Maximum 5MB.
                            </small>

                            <!-- New Image Preview -->
                            <div class="mt-3">

                                <img
                                    id="imagePreview"
                                    src=""
                                    alt="Image Preview"
                                    class="img-thumbnail d-none"
                                    style="
                                        width:150px;
                                        height:150px;
                                        object-fit:cover;
                                    "
                                >

                            </div>

                        </div>

                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-end gap-2 mt-3">

                        <a
                            href="{{ route('admin.facilities.index') }}"
                            class="btn btn-secondary"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="ti ti-check me-1"></i>
                            Update Facility
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>


<script>

    // Generate slug preview automatically
    document.getElementById('facilityName').addEventListener('input', function () {

        let name = this.value;

        let slug = name
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');

        document.getElementById('slug').value = slug;

    });


    // Image preview
    document.getElementById('image').addEventListener('change', function (event) {

        const imagePreview = document.getElementById('imagePreview');

        const file = event.target.files[0];

        if (file) {

            const reader = new FileReader();

            reader.onload = function (e) {

                imagePreview.src = e.target.result;

                imagePreview.classList.remove('d-none');

            };

            reader.readAsDataURL(file);

        } else {

            imagePreview.src = '';

            imagePreview.classList.add('d-none');

        }

    });

</script>

@endsection
