@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            {{-- ========================================================= --}}
            {{-- PAGE HEADER --}}
            {{-- ========================================================= --}}
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="page-title">Home Visit Service Categories</h4>
                    </div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="ti ti-plus me-1"></i>
                        Add Category
                    </button>
                </div>
            </div>


            {{-- ========================================================= --}}
            {{-- SUCCESS MESSAGE --}}
            {{-- ========================================================= --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>
                </div>
            @endif


            {{-- ========================================================= --}}
            {{-- VALIDATION ERRORS --}}
            {{-- ========================================================= --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">

                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>
            @endif


            {{-- ========================================================= --}}
            {{-- CATEGORY LIST --}}
            {{-- ========================================================= --}}
            <div class="card">

                <div class="card-header">
                    <div class="row align-items-center">

                        <div class="col">
                            <h5 class="card-title mb-0">
                                Category List
                            </h5>
                        </div>

                        <div class="col-auto">
                            <span class="text-muted">
                                Total: {{ $categories->total() }}
                            </span>
                        </div>

                    </div>
                </div>


                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Category For</th>
                                    <th>Created Date</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>


                            <tbody>

                                @forelse($categories as $category)

                                    <tr>

                                        {{-- ID --}}
                                        <td>
                                            {{ $categories->firstItem() + $loop->index }}
                                        </td>


                                        {{-- IMAGE --}}
                                        <td>
                                            @if($category->image)

                                                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}"
                                                    class="category-image">

                                            @else

                                                <div class="no-image">
                                                    <i class="ti ti-photo"></i>
                                                </div>

                                            @endif
                                        </td>


                                        {{-- NAME --}}
                                        <td>
                                            <strong>
                                                {{ $category->name }}
                                            </strong>
                                        </td>


                                        {{-- SLUG --}}
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ $category->slug }}
                                            </span>
                                        </td>

                                        <td>
                                            @php
                                                $typeClass = match ($category->category_type) {
                                                    'care_service' => 'bg-primary-transparent text-primary',
                                                    'physio' => 'bg-success-transparent text-success',
                                                    'sleep_test' => 'bg-info-transparent text-info',
                                                    default => 'bg-light text-dark',
                                                };

                                                $typeName = match ($category->category_type) {
                                                    'care_service' => 'Care Service',
                                                    'physio' => 'Physio',
                                                    'sleep_test' => 'Sleep Test',
                                                    default => '-',
                                                };
                                            @endphp

                                            <span class="badge {{ $typeClass }}">
                                                {{ $typeName }}
                                            </span>
                                        </td>


                                        {{-- CREATED DATE --}}
                                        <td>
                                            {{ $category->created_at?->format('d M Y') }}
                                        </td>


                                        {{-- ACTION --}}
                                        <td class="text-end">

                                            {{-- EDIT --}}
                                            <button type="button" class="btn btn-sm btn-primary editCategoryBtn"
                                                data-bs-toggle="modal" data-bs-target="#editCategoryModal"
                                                data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                                data-category-type="{{ $category->category_type }}"
                                                data-slug="{{ $category->slug }}"
                                                data-image="{{ $category->image ? asset($category->image) : '' }}"
                                                data-update-url="{{ route('admin.home-visit-service-categories.update', $category->id) }}">
                                                <i class="ti ti-edit"></i>
                                            </button>


                                            {{-- DELETE --}}
                                            <button type="button" class="btn btn-sm btn-light-danger"
                                                onclick="deleteCategory({{ $category->id }})" title="Delete">

                                                <i class="ti ti-trash"></i>

                                            </button>


                                            {{-- DELETE FORM --}}
                                            <form id="delete-form-{{ $category->id }}"
                                                action="{{ route('admin.home-visit-service-categories.destroy', $category->id) }}"
                                                method="POST" style="display:none;">

                                                @csrf
                                                @method('DELETE')

                                            </form>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="6" class="text-center py-5">

                                            <i class="ti ti-folder-off fs-2 text-muted"></i>

                                            <div class="text-muted mt-2">
                                                No Home Visit Service Categories Found
                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- ========================================================= --}}
                    {{-- PAGINATION --}}
                    {{-- ========================================================= --}}
                    @if($categories->hasPages())

                        <div class="d-flex justify-content-end mt-3">

                            {{ $categories->links() }}

                        </div>

                    @endif

                </div>

            </div>

        </div>
    </div>



    {{-- ========================================================= --}}
    {{-- ADD CATEGORY MODAL --}}
    {{-- ========================================================= --}}
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <form action="{{ route('admin.home-visit-service-categories.store') }}" method="POST"
                    enctype="multipart/form-data">

                    @csrf


                    {{-- HEADER --}}
                    <div class="modal-header">

                        <h5 class="modal-title">
                            Add Home Visit Service Category
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>


                    {{-- BODY --}}
                    <div class="modal-body">

                        {{-- NAME --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Name <span class="text-danger">*</span>
                            </label>

                            <input type="text" name="name" id="add_name" class="form-control"
                                placeholder="Enter category name" value="{{ old('name') }}" required>
                        </div>


                        {{-- SLUG --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Slug
                            </label>

                            <input type="text" name="slug" id="add_slug" class="form-control"
                                placeholder="Auto generated slug" readonly>

                            <small class="text-muted">
                                Slug will be generated automatically from the category name.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Category Type <span class="text-danger">*</span>
                            </label>

                            <select name="category_type" class="form-select" required>
                                <option value="">Select Category Type</option>

                                <option value="care_service">
                                    Care Service
                                </option>

                                <option value="physio">
                                    Physio
                                </option>

                                <option value="sleep_test">
                                    Sleep Test
                                </option>
                            </select>
                        </div>


                        {{-- IMAGE --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Image
                            </label>

                            <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">

                            <small class="text-muted">
                                JPG, JPEG, PNG or WEBP. Maximum 2MB.
                            </small>

                        </div>

                    </div>


                    {{-- FOOTER --}}
                    <div class="modal-footer">

                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" class="btn btn-primary">

                            <i class="ti ti-check me-1"></i>
                            Save Category

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- EDIT CATEGORY MODAL --}}
    {{-- ========================================================= --}}
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <form id="editCategoryForm" method="POST" enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    <div class="modal-header">

                        <h5 class="modal-title">
                            Edit Home Visit Service Category
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">
                                Name <span class="text-danger">*</span>
                            </label>

                            <input type="text" name="name" id="edit_name" class="form-control"
                                placeholder="Enter category name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Slug
                            </label>

                            <input type="text" name="slug" id="edit_slug" class="form-control"
                                placeholder="Enter category slug" required>

                            <small class="text-muted">
                                Slug is generated automatically from the name.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Category Type <span class="text-danger">*</span>
                            </label>

                            <select name="category_type" id="edit_category_type" class="form-select" required>

                                <option value="">Select Category Type</option>

                                <option value="care_service" >
                                    Care Service
                                </option>

                                <option value="physio">
                                    Physio
                                </option>

                                <option value="sleep_test">
                                    Sleep Test
                                </option>

                            </select>
                        </div>


                        <div class="mb-3" id="editCurrentImageWrapper" style="display:none;">

                            <label class="form-label">
                                Current Image
                            </label>

                            <div>
                                <img id="edit_image_preview" src="" alt="Category Image" class="edit-category-image">
                            </div>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Change Image
                            </label>

                            <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">

                            <small class="text-muted">
                                Leave empty to keep the current image.
                            </small>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" class="btn btn-primary">

                            <i class="ti ti-check me-1"></i>
                            Update Category

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>





    {{-- ========================================================= --}}
    {{-- CSS --}}
    {{-- ========================================================= --}}
    <style>
        .category-image {
            width: 55px;
            height: 55px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }


        .edit-category-image {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }


        .no-image {
            width: 55px;
            height: 55px;
            border-radius: 8px;
            background: #f5f6f8;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 22px;
        }


        .btn-light-primary {
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            border: 0;
        }


        .btn-light-primary:hover {
            background: #0d6efd;
            color: #fff;
        }


        .btn-light-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 0;
        }


        .btn-light-danger:hover {
            background: #dc3545;
            color: #fff;
        }


        .table> :not(caption)>*>* {
            padding: 14px 12px;
        }
    </style>


    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /*
             * SLUG GENERATOR
             */
            function generateSlug(value) {
                return value
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            }


            /*
             * ================================
             * ADD CATEGORY - AUTO SLUG
             * ================================
             */
            const addNameInput = document.getElementById('add_name');
            const addSlugInput = document.getElementById('add_slug');

            if (addNameInput && addSlugInput) {

                addNameInput.addEventListener('input', function () {

                    addSlugInput.value = generateSlug(this.value);

                });

            }


            /*
             * ================================
             * EDIT CATEGORY
             * ================================
             */
            const editButtons = document.querySelectorAll('.editCategoryBtn');

            editButtons.forEach(function (button) {

                button.addEventListener('click', function () {

                    const id = this.getAttribute('data-id') || '';
                    const name = this.getAttribute('data-name') || '';
                    const categoryType = this.getAttribute('data-category-type') || '';
                    const slug = this.getAttribute('data-slug') || '';
                    const image = this.getAttribute('data-image') || '';
                    const updateUrl = this.getAttribute('data-update-url');


                    /*
                     * Set Form Action
                     */
                    const form = document.getElementById('editCategoryForm');

                    if (form && updateUrl) {
                        form.setAttribute('action', updateUrl);
                    }


                    /*
                     * Set Name
                     */
                    const editNameInput = document.getElementById('edit_name');

                    if (editNameInput) {
                        editNameInput.value = name;
                    }

                    /*
    * Set Category Type
    */
                    const editCategoryType =
                        document.getElementById('edit_category_type');

                    if (editCategoryType) {
                        editCategoryType.value = categoryType;
                    }


                    /*
                     * Set Existing Slug
                     */
                    const editSlugInput = document.getElementById('edit_slug');

                    if (editSlugInput) {
                        editSlugInput.value = slug;
                    }


                    /*
                     * Current Image Preview
                     */
                    const preview = document.getElementById('edit_image_preview');
                    const imageWrapper = document.getElementById('editCurrentImageWrapper');

                    if (image) {

                        if (preview) {
                            preview.src = image;
                        }

                        if (imageWrapper) {
                            imageWrapper.style.display = 'block';
                        }

                    } else {

                        if (preview) {
                            preview.src = '';
                        }

                        if (imageWrapper) {
                            imageWrapper.style.display = 'none';
                        }

                    }

                });

            });


            /*
             * ================================
             * EDIT CATEGORY - AUTO SLUG
             * ================================
             */
            const editNameInput = document.getElementById('edit_name');
            const editSlugInput = document.getElementById('edit_slug');

            if (editNameInput && editSlugInput) {

                editNameInput.addEventListener('input', function () {

                    editSlugInput.value = generateSlug(this.value);

                });

            }


            /*
             * ================================
             * DELETE CATEGORY
             * ================================
             */
            window.deleteCategory = function (id) {

                if (confirm('Are you sure you want to delete this category?')) {

                    const form = document.getElementById('delete-form-' + id);

                    if (form) {
                        form.submit();
                    }

                }

            };

        });
    </script>
@endsection