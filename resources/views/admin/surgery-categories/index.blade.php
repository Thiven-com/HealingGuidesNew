
@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h4>Surgery Categories</h4>
                <h6>Manage surgery categories</h6>
            </div>

            <button type="button" class="btn btn-primary"
                    onclick="openCreateModal()">
                <i class="ti ti-plus me-1"></i> Add Category
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close"
                        data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Please correct the following:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Surgery Category List</h5>
                <small class="text-muted">
                    {{ $categories->total() }} categories found
                </small>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width:70px">Image</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Description</th>
                                <th>Created At</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td>
                                        @if($category->image)
                                            <img
                                                src="{{ asset($category->image) }}"
                                                alt="{{ $category->name }}"
                                                width="50"
                                                height="50"
                                                style="object-fit:cover;border-radius:8px;"
                                            >
                                        @else
                                            <div class="bg-light rounded d-flex
                                                        align-items-center justify-content-center"
                                                 style="width:50px;height:50px;">
                                                <i class="ti ti-stethoscope fs-4"></i>
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        <strong>{{ $category->name }}</strong>
                                    </td>

                                    <td>
                                        <span class="badge bg-light-info text-info">
                                            {{ $category->slug }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ \Illuminate\Support\Str::limit($category->description ?? '—', 60) }}
                                    </td>

                                    <td>
                                        {{ $category->created_at->format('d M Y') }}
                                    </td>

                                    <td class="text-end">
                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                onclick="openEditModal(this)"
                                                data-id="{{ $category->id }}"
                                                data-name="{{ $category->name }}"
                                                data-description="{{ $category->description }}"
                                                data-image="{{ $category->image ? asset($category->image) : '' }}">
                                            <i class="ti ti-edit"></i> Edit
                                        </button>

                                        <form action="{{ route('admin.surgery-categories.destroy', $category->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this category?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="ti ti-stethoscope fs-1 text-muted"></i>
                                        <h5 class="mt-2">No Surgery Categories Found</h5>
                                        <p class="text-muted mb-0">
                                            Click Add Category to create one.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($categories->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <small class="text-muted">
                            Showing {{ $categories->firstItem() }}
                            to {{ $categories->lastItem() }}
                            of {{ $categories->total() }} entries
                        </small>

                        {{ $categories->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- CREATE / EDIT MODAL --}}
<div class="modal fade" id="categoryModal" tabindex="-1"
     aria-labelledby="categoryModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form id="categoryForm"
                  action="{{ route('admin.surgery-categories.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">
                        Add Surgery Category
                    </h5>

                    <button type="button" class="btn-close"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label for="categoryName" class="form-label">
                            Category Name <span class="text-danger">*</span>
                        </label>

                        <input type="text" name="name" id="categoryName"
                               class="form-control" required maxlength="255"
                               placeholder="Enter category name">
                    </div>

                    <div class="mb-3">
                        <label for="categorySlug" class="form-label">Slug</label>

                        <input type="text" id="categorySlug"
                               class="form-control" readonly
                               placeholder="Generated automatically">

                        <small class="text-muted">
                            Automatically generated from the category name.
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="categoryDescription" class="form-label">
                            Description
                        </label>

                        <textarea name="description" id="categoryDescription"
                                  class="form-control" rows="3"
                                  placeholder="Enter description"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="categoryImage" class="form-label">Image</label>

                        <input type="file" name="image" id="categoryImage"
                               class="form-control"
                               accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">

                        <small class="text-muted">
                            JPG, JPEG, PNG or WEBP. Maximum 2 MB.
                        </small>

                        <div class="mt-3">
                            <img id="categoryImagePreview" src="" alt="Image preview"
                                 style="display:none;width:100px;height:100px;object-fit:cover;border-radius:8px;">
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">Cancel</button>

                    <button type="submit" class="btn btn-primary"
                            id="saveCategoryBtn">Save Category</button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalElement = document.getElementById('categoryModal');
    const modal = new bootstrap.Modal(modalElement);

    const form = document.getElementById('categoryForm');
    const methodInput = document.getElementById('formMethod');
    const nameInput = document.getElementById('categoryName');
    const slugInput = document.getElementById('categorySlug');
    const descriptionInput = document.getElementById('categoryDescription');
    const imageInput = document.getElementById('categoryImage');
    const imagePreview = document.getElementById('categoryImagePreview');

    const storeUrl = @json(route('admin.surgery-categories.store'));
    const updateUrlTemplate = @json(route('admin.surgery-categories.update', ['surgery_category' => '__ID__']));

    function generateSlug(value) {
        return value
            .toLowerCase()
            .trim()
            .normalize('NFKD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    window.openCreateModal = function () {
        form.reset();
        form.action = storeUrl;
        methodInput.value = 'POST';

        document.getElementById('categoryModalLabel').textContent =
            'Add Surgery Category';

        document.getElementById('saveCategoryBtn').textContent =
            'Save Category';

        slugInput.value = '';
        imagePreview.src = '';
        imagePreview.style.display = 'none';

        modal.show();
    };

    window.openEditModal = function (button) {
        form.reset();

        const id = button.dataset.id;

        form.action = updateUrlTemplate.replace('__ID__', id);
        methodInput.value = 'PUT';

        nameInput.value = button.dataset.name || '';
        slugInput.value = generateSlug(button.dataset.name || '');
        descriptionInput.value = button.dataset.description || '';

        document.getElementById('categoryModalLabel').textContent =
            'Edit Surgery Category';

        document.getElementById('saveCategoryBtn').textContent =
            'Update Category';

        if (button.dataset.image) {
            imagePreview.src = button.dataset.image;
            imagePreview.style.display = 'block';
        } else {
            imagePreview.src = '';
            imagePreview.style.display = 'none';
        }

        modal.show();
    };

    nameInput.addEventListener('input', function () {
        slugInput.value = generateSlug(this.value);
    });

    imageInput.addEventListener('change', function () {
        const file = this.files[0];

        if (file) {
            imagePreview.src = URL.createObjectURL(file);
            imagePreview.style.display = 'block';
        } else {
            imagePreview.src = '';
            imagePreview.style.display = 'none';
        }
    });
});
</script>

@endsection
