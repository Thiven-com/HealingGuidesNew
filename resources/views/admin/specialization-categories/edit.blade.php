@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        <div class="page-header">

            <div class="page-title">
                <h4>Edit Specialization Category</h4>
                <h6>Update specialization category details</h6>
            </div>

            <div class="page-btn">
                <a href="{{ route('admin.specialization-categories.index') }}"
                   class="btn btn-secondary">

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

        <form action="{{ route(
            'admin.specialization-categories.update',
            $category->id
        ) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="card">

                <div class="card-body">

                    <div class="row">

                        {{-- Category Name --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Category Name
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="category_name"
                                   class="form-control"
                                   value="{{ old(
                                       'category_name',
                                       $category->category_name
                                   ) }}"
                                   required>

                        </div>

                        {{-- Slug --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Slug
                            </label>

                            <input type="text"
                                   name="slug"
                                   class="form-control"
                                   value="{{ old(
                                       'slug',
                                       $category->slug
                                   ) }}">

                        </div>

                        {{-- Icon --}}
                        {{-- <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Icon
                            </label>

                            <input type="text"
                                   name="icon"
                                   class="form-control"
                                   value="{{ old(
                                       'icon',
                                       $category->icon
                                   ) }}"
                                   placeholder="ti ti-heart">

                            @if($category->icon)

                                <div class="mt-2">

                                    Current Icon:

                                    <i class="{{ $category->icon }}"
                                       style="font-size:22px;">
                                    </i>

                                </div>

                            @endif

                        </div> --}}

                        {{-- Image --}}
                        {{-- <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Image
                            </label>

                            <input type="file"
                                   name="image"
                                   class="form-control"
                                   accept=".jpg,.jpeg,.png,.webp">

                            @if($category->image)

                                <div class="mt-2">

                                    <img src="{{ asset(
                                        $category->image
                                    ) }}"
                                         alt="{{ $category->category_name }}"
                                         style="
                                            width:100px;
                                            height:100px;
                                            object-fit:cover;
                                            border-radius:8px;
                                            border:1px solid #ddd;
                                         ">

                                </div>

                            @endif

                        </div> --}}

                        {{-- Description --}}
                        {{-- <div class="col-md-12 mb-3">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea name="description"
                                      class="form-control"
                                      rows="5">{{ old(
                                          'description',
                                          $category->description
                                      ) }}</textarea>

                        </div> --}}

                        {{-- Status --}}
                        <div class="col-md-6 mb-3">

                            <div class="form-check form-switch">

                                <input type="checkbox"
                                       name="status"
                                       value="1"
                                       class="form-check-input"
                                       id="status"
                                       {{ $category->status ? 'checked' : '' }}>

                                <label class="form-check-label"
                                       for="status">

                                    Active

                                </label>

                            </div>

                        </div>

                    </div>

                    <div class="text-end mt-3">

                        <a href="{{ route(
                            'admin.specialization-categories.index'
                        ) }}"
                           class="btn btn-light me-2">

                            Cancel

                        </a>

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="ti ti-check me-1"></i>
                            Update Category

                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>
</div>

@endsection