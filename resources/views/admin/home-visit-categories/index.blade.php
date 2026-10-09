@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="page-title">Home Visit Categories</h4>
                    </div>
                </div>
                <div class="col-auto">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addCategoryModal">
                            <i class="ti ti-plus me-1"></i>
                            Add Category
                        </button>
                    </div>
            </div>
            <!-- /Page Header -->


            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>
                </div>
            @endif


            <!-- Error Message -->
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


            <!-- Categories List -->
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Home Visit Categories List
                    </h5>
                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead>
                                <tr>
                                    <th width="60">#</th>
                                    <th width="100">Image</th>
                                    <th>Name</th>
                                    {{-- <th>Slug</th> --}}
                                    <th>Description</th>
                                    <th width="120">Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($categories as $category)

                                    <tr>

                                        <td>
                                            {{ $categories->firstItem() + $loop->index }}
                                        </td>

                                        <!-- Image -->
                                        <td>
                                            @if($category->image)

                                                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" width="60"
                                                    height="60" style="object-fit: cover; border-radius: 8px;">

                                            @else

                                                <div class="avatar avatar-md bg-light">
                                                    <i class="ti ti-photo"></i>
                                                </div>

                                            @endif
                                        </td>

                                        <!-- Name -->
                                        <td>
                                            <strong>
                                                {{ $category->name }}
                                            </strong>
                                        </td>

                                        <!-- Slug -->
                                        {{-- <td>
                                            <span class="text-muted">
                                                {{ $category->slug }}
                                            </span>
                                        </td> --}}

                                        <!-- Description -->
                                        <td>
                                            @if($category->description)
                                                {{ \Illuminate\Support\Str::limit($category->description, 80) }}
                                            @else
                                                <span class="text-muted">
                                                    -
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Action -->
                                        <td>

                                            <!-- Edit Button -->
                                            <button type="button" class="btn btn-sm btn-primary editCategoryBtn"
                                                data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                                data-slug="{{ $category->slug }}"
                                                data-description="{{ $category->description }}"
                                                data-image="{{ $category->image }}" data-bs-toggle="modal"
                                                data-bs-target="#editCategoryModal">

                                                <i class="ti ti-edit"></i>

                                            </button>


                                            <!-- Delete -->
                                            <form action="{{ route('admin.home-visit-categories.destroy', $category->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this category?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-danger">

                                                    <i class="ti ti-trash"></i>

                                                </button>

                                            </form>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="6" class="text-center py-4">

                                            <span class="text-muted">
                                                No Home Visit Categories Found
                                            </span>

                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $categories->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>


    <!-- ========================================================= -->
    <!-- ADD CATEGORY MODAL -->
    <!-- ========================================================= -->

    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">

                <form action="{{ route('admin.home-visit-categories.store') }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <!-- Modal Header -->
                    <div class="modal-header">

                        <h5 class="modal-title" id="addCategoryModalLabel">

                            Add Home Visit Category

                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>

                    </div>


                    <!-- Modal Body -->
                    <div class="modal-body">

                        <div class="row">

                            <!-- Name -->
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Name <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="name" id="add_category_name" class="form-control"
                                    placeholder="Enter category name" required>

                            </div>


                            <!-- Slug -->
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Slug
                                </label>

                                <input type="text" name="slug" id="add_category_slug" class="form-control"
                                    placeholder="Auto generated" readonly>

                            </div>


                            <!-- Image -->
                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Image
                                </label>

                                <input type="file" name="image" class="form-control"
                                    accept="image/jpeg,image/jpg,image/png,image/webp">

                                <small class="text-muted">
                                    JPG, JPEG, PNG or WEBP. Maximum 2MB.
                                </small>

                            </div>


                            <!-- Description -->
                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Description
                                </label>

                                <textarea name="description" class="form-control" rows="5"
                                    placeholder="Enter category description"></textarea>

                            </div>

                        </div>

                    </div>


                    <!-- Modal Footer -->
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


    <!-- ========================================================= -->
    <!-- EDIT CATEGORY MODAL -->
    <!-- ========================================================= -->

    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">

                <form id="editCategoryForm" method="POST" enctype="multipart/form-data">

                    @csrf
                    @method('PUT')


                    <!-- Modal Header -->
                    <div class="modal-header">

                        <h5 class="modal-title" id="editCategoryModalLabel">

                            Edit Home Visit Category

                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>

                    </div>


                    <!-- Modal Body -->
                    <div class="modal-body">

                        <div class="row">

                            <!-- Name -->
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Name <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="name" id="edit_category_name" class="form-control" required>

                            </div>


                            <!-- Slug -->
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Slug
                                </label>

                                <input type="text" name="slug" id="edit_category_slug" class="form-control" readonly>

                            </div>


                            <!-- Current Image -->
                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Current Image
                                </label>

                                <div>

                                    <img id="edit_category_image_preview" src="" alt="Category Image" width="100"
                                        height="100" style="display:none; object-fit:cover; border-radius:8px;">

                                    <span id="edit_no_image" class="text-muted">
                                        No image available
                                    </span>

                                </div>

                            </div>


                            <!-- New Image -->
                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Change Image
                                </label>

                                <input type="file" name="image" class="form-control"
                                    accept="image/jpeg,image/jpg,image/png,image/webp">

                                <small class="text-muted">
                                    Leave empty to keep the current image.
                                </small>

                            </div>


                            <!-- Description -->
                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Description
                                </label>

                                <textarea name="description" id="edit_category_description" class="form-control" rows="5"
                                    placeholder="Enter category description"></textarea>

                            </div>

                        </div>

                    </div>


                    <!-- Modal Footer -->
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


@endsection


@push('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /*
            |--------------------------------------------------------------------------
            | Generate Slug
            |--------------------------------------------------------------------------
            */

            function generateSlug(text) {
                return text
                    .toString()
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            }


            /*
            |--------------------------------------------------------------------------
            | ADD MODAL - Auto Generate Slug
            |--------------------------------------------------------------------------
            */

            const addNameInput = document.getElementById('add_category_name');
            const addSlugInput = document.getElementById('add_category_slug');

            if (addNameInput && addSlugInput) {

                addNameInput.addEventListener('input', function () {

                    addSlugInput.value = generateSlug(this.value);

                });

            }


            /*
            |--------------------------------------------------------------------------
            | EDIT MODAL
            |--------------------------------------------------------------------------
            */

            const editModal = document.getElementById('editCategoryModal');

            if (editModal) {

                editModal.addEventListener('show.bs.modal', function (event) {

                    const button = event.relatedTarget;

                    if (!button) {
                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Get Data From Edit Button
                    |--------------------------------------------------------------------------
                    */

                    const id = button.getAttribute('data-id');
                    const name = button.getAttribute('data-name');
                    const slug = button.getAttribute('data-slug');
                    const description = button.getAttribute('data-description');
                    const image = button.getAttribute('data-image');


                    /*
                    |--------------------------------------------------------------------------
                    | Edit Form
                    |--------------------------------------------------------------------------
                    */

                    const editForm =
                        document.getElementById('editCategoryForm');

                    editForm.action =
                        "{{ url('admin/home-visit-categories/update') }}/" + id;


                    /*
                    |--------------------------------------------------------------------------
                    | Set Name
                    |--------------------------------------------------------------------------
                    */

                    const editName =
                        document.getElementById('edit_category_name');

                    if (editName) {
                        editName.value = name || '';
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Set Old Slug
                    |--------------------------------------------------------------------------
                    */

                    const editSlug =
                        document.getElementById('edit_category_slug');

                    if (editSlug) {
                        editSlug.value = slug || '';
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Set Description
                    |--------------------------------------------------------------------------
                    */

                    const editDescription =
                        document.getElementById('edit_category_description');

                    if (editDescription) {
                        editDescription.value = description || '';
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Set Current Image
                    |--------------------------------------------------------------------------
                    */

                    const imagePreview =
                        document.getElementById('edit_category_image_preview');

                    const noImage =
                        document.getElementById('edit_no_image');


                    if (image) {

                        imagePreview.src = "{{ url('/') }}/" + image;

                        imagePreview.style.display = 'block';

                        if (noImage) {
                            noImage.style.display = 'none';
                        }

                    } else {

                        imagePreview.src = '';

                        imagePreview.style.display = 'none';

                        if (noImage) {
                            noImage.style.display = 'inline';
                        }

                    }

                });

            }


            /*
            |--------------------------------------------------------------------------
            | EDIT - Auto Generate Slug When Name Changes
            |--------------------------------------------------------------------------
            */

            const editNameInput =
                document.getElementById('edit_category_name');

            const editSlugInput =
                document.getElementById('edit_category_slug');


            if (editNameInput && editSlugInput) {

                editNameInput.addEventListener('input', function () {

                    editSlugInput.value =
                        generateSlug(this.value);

                });

            }

        });
    </script>

@endpush