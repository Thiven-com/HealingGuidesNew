@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            <div class="page-header">

                <div class="row align-items-center">

                    <div class="col">

                        <h4 class="page-title">
                            Add Diagnostic Category
                        </h4>

                        <ul class="breadcrumb">

                            <li class="breadcrumb-item">
                                <a href="{{ url('/admin/dashboard') }}">
                                    Dashboard
                                </a>
                            </li>

                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.diagnostic-categories.index') }}">
                                    Diagnostic Categories
                                </a>
                            </li>

                            <li class="breadcrumb-item active">
                                Add
                            </li>

                        </ul>

                    </div>

                </div>

            </div>

            <div class="card">

                <div class="card-body">

                    <form method="POST" action="{{ route('admin.diagnostic-categories.store') }}"
                        enctype="multipart/form-data">

                        @csrf

                        <div class="row">

                            {{-- Name --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Category Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="name" id="category_name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    placeholder="Enter category name" required>

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Slug --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Slug
                                </label>

                                <input type="text" name="slug" id="category_slug"
                                    class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}"
                                    placeholder="category-slug">

                                @error('slug')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Image --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Image
                                </label>

                                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                                    accept=".jpg,.jpeg,.png,.webp">

                                @error('image')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <small class="text-muted">
                                    JPG, JPEG, PNG or WEBP. Maximum 2MB.
                                </small>

                            </div>

                            {{-- Display Order --}}
                            <div class="col-md-3 mb-3">

                                <label class="form-label">
                                    Display Order
                                </label>

                                <input type="number" name="display_order" class="form-control"
                                    value="{{ old('display_order', 0) }}" min="0">

                            </div>

                            {{-- Status --}}
                            <div class="col-md-3 mb-3">

                                <label class="form-label">
                                    Status
                                </label>

                                <select name="status" class="form-select">

                                    <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>
                                        Inactive
                                    </option>

                                </select>

                            </div>

                            {{-- Short Description --}}
                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Short Description
                                </label>

                                <textarea name="short_description" class="form-control" rows="3"
                                    placeholder="Enter short description">{{ old('short_description') }}</textarea>

                            </div>

                            {{-- Description --}}
                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Description
                                </label>

                                <textarea name="description" class="form-control" rows="5"
                                    placeholder="Enter description">{{ old('description') }}</textarea>

                            </div>

                        </div>

                        <div class="text-end mt-3">

                            <a href="{{ route('admin.diagnostic-categories.index') }}" class="btn btn-light me-2">

                                Cancel

                            </a>

                            <button type="submit" class="btn btn-primary">

                                <i class="fa fa-save me-1"></i>

                                Save Category

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection

@push('scripts')

    <script>
        document.getElementById('category_name').addEventListener('input', function () {

            const slug = this.value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');

            const slugInput = document.getElementById('category_slug');

            if (!slugInput.value || slugInput.dataset.auto !== 'false') {
                slugInput.value = slug;
                slugInput.dataset.auto = 'true';
            }
        });

        document.getElementById('category_slug').addEventListener('input', function () {
            this.dataset.auto = 'false';
        });
    </script>

@endpush