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
                        <button type="button"
                            class="btn btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#addCategoryModal">
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

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
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

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
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

                                                <img src="{{ asset($category->image) }}"
                                                    alt="{{ $category->name }}"
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


                                        {{-- CATEGORY FOR --}}
                                        <td>
    @php
        $homeVisitCategory = \App\Models\HomeVisitCategory::find(
            $category->home_visit_category_id
        );
    @endphp

    @if($homeVisitCategory)

        <div class="d-flex align-items-center">

                <strong>
                    {{ $homeVisitCategory->name }}
                </strong>

        </div>

    @else

        <span class="badge bg-light text-dark">
            -
        </span>

    @endif
</td>


                                        {{-- CREATED DATE --}}
                                        <td>
                                            {{ $category->created_at?->format('d M Y') }}
                                        </td>


                                        {{-- ACTION --}}
                                        <td class="text-end">

                                            {{-- EDIT --}}
                                            <button type="button"
                                                class="btn btn-sm btn-primary editCategoryBtn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editCategoryModal"

                                                data-id="{{ $category->id }}"

                                                data-name="{{ $category->name }}"

                                                data-home-visit-category-id="{{ $category->home_visit_category_id }}"

                                                data-slug="{{ $category->slug }}"

                                                data-image="{{ $category->image ? asset($category->image) : '' }}"

                                                data-update-url="{{ route('admin.home-visit-service-categories.update', $category->id) }}">

                                                <i class="ti ti-edit"></i>

                                            </button>


                                            {{-- DELETE --}}
                                            <button type="button"
                                                class="btn btn-sm btn-light-danger"
                                                onclick="deleteCategory({{ $category->id }})"
                                                title="Delete">

                                                <i class="ti ti-trash"></i>

                                            </button>


                                            {{-- DELETE FORM --}}
                                            <form id="delete-form-{{ $category->id }}"
                                                action="{{ route('admin.home-visit-service-categories.destroy', $category->id) }}"
                                                method="POST"
                                                style="display:none;">

                                                @csrf
                                                @method('DELETE')

                                            </form>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="7"
                                            class="text-center py-5">

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


                    {{-- PAGINATION --}}
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
    <div class="modal fade"
        id="addCategoryModal"
        tabindex="-1"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <form action="{{ route('admin.home-visit-service-categories.store') }}"
                    method="POST"
                    enctype="multipart/form-data">

                    @csrf


                    {{-- HEADER --}}
                    <div class="modal-header">

                        <h5 class="modal-title">
                            Add Home Visit Service Category
                        </h5>

                        <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                        </button>

                    </div>


                    {{-- BODY --}}
                    <div class="modal-body">


                        {{-- HOME VISIT CATEGORY --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Home Visit Category
                                <span class="text-danger">*</span>
                            </label>

                            <select name="home_visit_category_id"
                                id="add_home_visit_category_id"
                                class="form-select"
                                required>

                                <option value="">
                                    Select Home Visit Category
                                </option>

                                @foreach($homeVisitCategories as $homeVisitCategory)

                                    <option value="{{ $homeVisitCategory->id }}"
                                        data-slug="{{ $homeVisitCategory->slug }}">

                                        {{ $homeVisitCategory->name }}

                                    </option>

                                @endforeach

                            </select>

                            <small class="text-muted">
                                Category type will be automatically taken from the selected category slug.
                            </small>

                        </div>


                        {{-- NAME --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Name
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                name="name"
                                id="add_name"
                                class="form-control"
                                placeholder="Enter service category name"
                                value="{{ old('name') }}"
                                required>

                        </div>


                        {{-- SLUG --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Slug
                            </label>

                            <input type="text"
                                name="slug"
                                id="add_slug"
                                class="form-control"
                                placeholder="Auto generated slug"
                                readonly>

                            <small class="text-muted">
                                Slug will be generated automatically from the name.
                            </small>

                        </div>


                        {{-- CATEGORY TYPE PREVIEW --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Category Type
                            </label>

                            <input type="text"
                                id="add_category_type_preview"
                                class="form-control"
                                placeholder="Select category"
                                readonly>

                            <small class="text-muted">
                                This value is automatically stored from the selected Home Visit Category slug.
                            </small>

                        </div>


                        {{-- IMAGE --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Image
                            </label>

                            <input type="file"
                                name="image"
                                class="form-control"
                                accept=".jpg,.jpeg,.png,.webp">

                            <small class="text-muted">
                                JPG, JPEG, PNG or WEBP. Maximum 2MB.
                            </small>

                        </div>

                    </div>


                    {{-- FOOTER --}}
                    <div class="modal-footer">

                        <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">

                            Cancel

                        </button>

                        <button type="submit"
                            class="btn btn-primary">

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
    <div class="modal fade"
        id="editCategoryModal"
        tabindex="-1"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <form id="editCategoryForm"
                    method="POST"
                    enctype="multipart/form-data">

                    @csrf
                    @method('PUT')


                    {{-- HEADER --}}
                    <div class="modal-header">

                        <h5 class="modal-title">
                            Edit Home Visit Service Category
                        </h5>

                        <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                        </button>

                    </div>


                    {{-- BODY --}}
                    <div class="modal-body">


                        {{-- HOME VISIT CATEGORY --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Home Visit Category
                                <span class="text-danger">*</span>
                            </label>

                            <select name="home_visit_category_id"
                                id="edit_home_visit_category_id"
                                class="form-select"
                                required>

                                <option value="">
                                    Select Home Visit Category
                                </option>

                                @foreach($homeVisitCategories as $homeVisitCategory)

                                    <option value="{{ $homeVisitCategory->id }}"
                                        data-slug="{{ $homeVisitCategory->slug }}">

                                        {{ $homeVisitCategory->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- NAME --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Name
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                name="name"
                                id="edit_name"
                                class="form-control"
                                placeholder="Enter category name"
                                required>

                        </div>


                        {{-- SLUG --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Slug
                            </label>

                            <input type="text"
                                name="slug"
                                id="edit_slug"
                                class="form-control"
                                placeholder="Auto generated slug"
                                readonly>

                            <small class="text-muted">
                                Slug is generated automatically from the name.
                            </small>

                        </div>


                        {{-- CATEGORY TYPE --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Category Type
                            </label>

                            <input type="text"
                                id="edit_category_type_preview"
                                class="form-control"
                                placeholder="Category type"
                                readonly>

                            <small class="text-muted">
                                Automatically taken from the selected Home Visit Category slug.
                            </small>

                        </div>


                        {{-- CURRENT IMAGE --}}
                        <div class="mb-3"
                            id="editCurrentImageWrapper"
                            style="display:none;">

                            <label class="form-label">
                                Current Image
                            </label>

                            <div>

                                <img id="edit_image_preview"
                                    src=""
                                    alt="Category Image"
                                    class="edit-category-image">

                            </div>

                        </div>


                        {{-- CHANGE IMAGE --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Change Image
                            </label>

                            <input type="file"
                                name="image"
                                class="form-control"
                                accept=".jpg,.jpeg,.png,.webp">

                            <small class="text-muted">
                                Leave empty to keep the current image.
                            </small>

                        </div>

                    </div>


                    {{-- FOOTER --}}
                    <div class="modal-footer">

                        <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">

                            Cancel

                        </button>

                        <button type="submit"
                            class="btn btn-primary">

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

        .parent-category-image {
            width: 38px;
            height: 38px;
            object-fit: cover;
            border-radius: 6px;
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

        .btn-light-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 0;
        }

        .btn-light-danger:hover {
            background: #dc3545;
            color: #fff;
        }

        .table > :not(caption) > * > * {
            padding: 14px 12px;
        }

    </style>



    {{-- ========================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ========================================================= --}}
    <script>
document.addEventListener('DOMContentLoaded', function () {

    /* ==========================================================
     * SLUG GENERATOR
     * ========================================================== */
    function generateSlug(value) {
        return String(value || '')
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    }


    /* ==========================================================
     * GET CATEGORY SLUG FROM SELECT
     * ========================================================== */
    function getCategorySlug(select) {

        if (!select || !select.value) {
            return '';
        }

        const option = select.options[select.selectedIndex];

        if (!option) {
            return '';
        }

        return option.getAttribute('data-slug') || '';
    }


    /* ==========================================================
     * ADD - AUTO SLUG
     * ========================================================== */
    const addNameInput = document.getElementById('add_name');
    const addSlugInput = document.getElementById('add_slug');

    if (addNameInput && addSlugInput) {

        addNameInput.addEventListener('input', function () {
            addSlugInput.value = generateSlug(this.value);
        });

    }


    /* ==========================================================
     * ADD - HOME VISIT CATEGORY
     * ========================================================== */
    const addCategorySelect =
        document.getElementById('add_home_visit_category_id');

    const addCategoryTypePreview =
        document.getElementById('add_category_type_preview');


    function updateAddCategoryType() {

        if (!addCategoryTypePreview) {
            return;
        }

        addCategoryTypePreview.value =
            getCategorySlug(addCategorySelect);

    }


    if (addCategorySelect) {

        addCategorySelect.addEventListener(
            'change',
            updateAddCategoryType
        );

        // Set initial value
        updateAddCategoryType();

    }


    /* ==========================================================
     * EDIT ELEMENTS
     * ========================================================== */
    const editForm =
        document.getElementById('editCategoryForm');

    const editNameInput =
        document.getElementById('edit_name');

    const editSlugInput =
        document.getElementById('edit_slug');

    const editCategorySelect =
        document.getElementById('edit_home_visit_category_id');

    const editCategoryTypePreview =
        document.getElementById('edit_category_type_preview');

    const imagePreview =
        document.getElementById('edit_image_preview');

    const imageWrapper =
        document.getElementById('editCurrentImageWrapper');


    /* ==========================================================
     * EDIT - UPDATE CATEGORY TYPE
     * ========================================================== */
    function updateEditCategoryType() {

        if (!editCategoryTypePreview) {
            return;
        }

        editCategoryTypePreview.value =
            getCategorySlug(editCategorySelect);

    }


    /* ==========================================================
     * EDIT - CATEGORY CHANGE
     * ========================================================== */
    if (editCategorySelect) {

        editCategorySelect.addEventListener(
            'change',
            updateEditCategoryType
        );

    }


    /* ==========================================================
     * EDIT BUTTONS
     * ========================================================== */
    const editButtons =
        document.querySelectorAll('.editCategoryBtn');


    editButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            /* -----------------------------------------------
             * Get Data
             * ----------------------------------------------- */
            const name =
                this.dataset.name || '';

            const homeVisitCategoryId =
                this.dataset.homeVisitCategoryId || '';

            const slug =
                this.dataset.slug || '';

            const image =
                this.dataset.image || '';

            const updateUrl =
                this.dataset.updateUrl || '';


            /* -----------------------------------------------
             * Form Action
             * ----------------------------------------------- */
            if (editForm && updateUrl) {

                editForm.action = updateUrl;

            }


            /* -----------------------------------------------
             * Name
             * ----------------------------------------------- */
            if (editNameInput) {

                editNameInput.value = name;

            }


            /* -----------------------------------------------
             * Slug
             * ----------------------------------------------- */
            if (editSlugInput) {

                editSlugInput.value = slug;

            }


            /* -----------------------------------------------
             * Home Visit Category
             * ----------------------------------------------- */
            if (editCategorySelect) {

                editCategorySelect.value =
                    homeVisitCategoryId;

            }


            /* -----------------------------------------------
             * Category Type
             * ----------------------------------------------- */
            updateEditCategoryType();


            /* -----------------------------------------------
             * Current Image
             * ----------------------------------------------- */
            if (image) {

                if (imagePreview) {

                    imagePreview.src = image;
                    imagePreview.style.display = 'block';

                }

                if (imageWrapper) {

                    imageWrapper.style.display = 'block';

                }

            } else {

                if (imagePreview) {

                    imagePreview.src = '';
                    imagePreview.style.display = 'none';

                }

                if (imageWrapper) {

                    imageWrapper.style.display = 'none';

                }

            }

        });

    });


    /* ==========================================================
     * EDIT - AUTO SLUG
     * ========================================================== */
    if (editNameInput && editSlugInput) {

        editNameInput.addEventListener('input', function () {

            editSlugInput.value =
                generateSlug(this.value);

        });

    }


    /* ==========================================================
     * DELETE CATEGORY
     * ========================================================== */
    window.deleteCategory = function (id) {

        if (
            !confirm(
                'Are you sure you want to delete this category?'
            )
        ) {
            return;
        }

        const form =
            document.getElementById('delete-form-' + id);

        if (form) {
            form.submit();
        }

    };

});
</script>

@endsection