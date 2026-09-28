@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            <div class="page-header">
                <div class="page-title">
                    <h4>Add Specialization Category</h4>
                    <h6>Create a new specialization category</h6>
                </div>

                <div class="page-btn">
                    <a href="{{ route('admin.specialization-categories.index') }}" class="btn btn-secondary">

                        <i class="ti ti-arrow-left me-1"></i>
                        Back

                    </a>
                </div>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.specialization-categories.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="card">
                    <div class="card-body">

                        <div class="row">

                            {{-- Category Name --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Category Name <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="category_name" id="category_name" class="form-control"
                                    value="{{ old('category_name') }}" placeholder="Enter category name" required>

                            </div>

                            {{-- Slug --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Slug
                                </label>

                                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}"
                                    placeholder="category-slug">

                                <small class="text-muted">
                                    Slug will be generated automatically from category name.
                                </small>

                            </div>

                            {{-- Icon --}}
                            {{-- <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Icon
                                </label>

                                <input type="text" name="icon" class="form-control" value="{{ old('icon') }}"
                                    placeholder="ti ti-heart">

                                <small class="text-muted">
                                    Example: ti ti-heart
                                </small>

                            </div> --}}

                            {{-- Image --}}
                            {{-- <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Image
                                </label>

                                <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">

                                <small class="text-muted">
                                    JPG, JPEG, PNG or WEBP. Max 2MB.
                                </small>

                            </div> --}}

                            {{-- Description --}}
                            {{-- <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Description
                                </label>

                                <textarea name="description" class="form-control" rows="5"
                                    placeholder="Enter description">{{ old('description') }}</textarea>

                            </div> --}}

                            {{-- Status --}}
                            <div class="col-md-6 mb-3">

                                <div class="form-check form-switch">

                                    <input type="checkbox" name="status" value="1" class="form-check-input" id="status"
                                        checked>

                                    <label class="form-check-label" for="status">

                                        Active

                                    </label>

                                </div>

                            </div>

                        </div>

                        <div class="text-end mt-3">

                            <a href="{{ route('admin.specialization-categories.index') }}" class="btn btn-light me-2">

                                Cancel

                            </a>

                            <button type="submit" class="btn btn-primary">

                                <i class="ti ti-check me-1"></i>
                                Save Category

                            </button>

                        </div>

                    </div>
                </div>

            </form>

        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const categoryName = document.getElementById('category_name');
        const slug = document.getElementById('slug');

        if (categoryName && slug) {

            categoryName.addEventListener('input', function () {

                let value = this.value;

                value = value
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');

                slug.value = value;
            });

        }

    });
</script>

@endsection