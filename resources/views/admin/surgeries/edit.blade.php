<?php $page = 'surgeries'; ?>

@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">

    <div class="content">

        {{-- Page Header --}}
        <div class="page-header">

            <div class="page-title">

                <h4>Edit Surgery</h4>

                <h6>Update surgery details</h6>

            </div>

            <div class="page-btn">

                <a href="{{ route('admin.surgeries.index') }}"
                   class="btn btn-secondary">

                    <i data-feather="arrow-left" class="me-2"></i>

                    Back

                </a>

            </div>

        </div>


        {{-- Validation Errors --}}
        @if($errors->any())

            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form action="{{ route('admin.surgeries.update', $surgery->id) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            @method('PUT')


            <div class="row">

                {{-- Left --}}
                <div class="col-lg-8">

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Surgery Information
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row g-3">

                                {{-- Name --}}
                                <div class="col-md-12">

                                    <label class="form-label">
                                        Surgery Name
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           name="name"
                                           class="form-control"
                                           value="{{ old('name', $surgery->name) }}"
                                           placeholder="Enter surgery name"
                                           required>

                                </div>


                                {{-- Slug --}}
                                <div class="col-md-12">

                                    <label class="form-label">
                                        Slug
                                    </label>

                                    <input type="text"
                                           name="slug"
                                           class="form-control"
                                           value="{{ old('slug', $surgery->slug) }}"
                                           placeholder="surgery-slug">

                                </div>


                                {{-- Short Description --}}
                                <div class="col-md-12">

                                    <label class="form-label">
                                        Short Description
                                    </label>

                                    <textarea name="short_description"
                                              class="form-control"
                                              rows="3"
                                              placeholder="Enter short description">{{ old('short_description', $surgery->short_description) }}</textarea>

                                </div>


                                {{-- Description --}}
                                <div class="col-md-12">

                                    <label class="form-label">
                                        Description
                                    </label>

                                    <textarea name="description"
                                              class="form-control"
                                              rows="6"
                                              placeholder="Enter surgery description">{{ old('description', $surgery->description) }}</textarea>

                                </div>


                                {{-- Duration --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Surgery Duration
                                    </label>

                                    <input type="text"
                                           name="duration"
                                           class="form-control"
                                           value="{{ old('duration', $surgery->duration) }}"
                                           placeholder="Example: 2 - 3 Hours">

                                </div>


                                {{-- Recovery Time --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Recovery Time
                                    </label>

                                    <input type="text"
                                           name="recovery_time"
                                           class="form-control"
                                           value="{{ old('recovery_time', $surgery->recovery_time) }}"
                                           placeholder="Example: 2 - 4 Weeks">

                                </div>


                                {{-- Preparation --}}
                                <div class="col-md-12">

                                    <label class="form-label">
                                        Preparation Instructions
                                    </label>

                                    <textarea name="preparation_instructions"
                                              class="form-control"
                                              rows="5"
                                              placeholder="Enter preparation instructions">{{ old('preparation_instructions', $surgery->preparation_instructions) }}</textarea>

                                </div>


                                {{-- Post Surgery Care --}}
                                <div class="col-md-12">

                                    <label class="form-label">
                                        Post Surgery Care
                                    </label>

                                    <textarea name="post_surgery_care"
                                              class="form-control"
                                              rows="5"
                                              placeholder="Enter post surgery care">{{ old('post_surgery_care', $surgery->post_surgery_care) }}</textarea>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Right --}}
                <div class="col-lg-4">

                    {{-- Image --}}
                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Surgery Image
                            </h5>

                        </div>

                        <div class="card-body">

                            @if($surgery->image)

                                <div class="mb-3 text-center">

                                    <img src="{{ asset($surgery->image) }}"
                                         id="currentImage"
                                         class="img-fluid rounded"
                                         style="max-height:220px;">

                                </div>

                            @endif


                            <div class="mb-3">

                                <label class="form-label">
                                    Change Image
                                </label>

                                <input type="file"
                                       name="image"
                                       class="form-control"
                                       accept="image/png,image/jpeg,image/jpg,image/webp">

                            </div>


                            <div id="imagePreview"
                                 class="text-center d-none">

                                <img src=""
                                     id="previewImage"
                                     class="img-fluid rounded"
                                     style="max-height:220px;">

                            </div>

                        </div>

                    </div>

                    {{-- Settings --}}
                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Settings
                            </h5>

                        </div>

                        <div class="card-body">

                            {{-- Display Order --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Display Order
                                </label>

                                <input type="number"
                                       name="display_order"
                                       class="form-control"
                                       value="{{ old('display_order', $surgery->display_order) }}"
                                       min="0">

                            </div>


                            {{-- Status --}}
                            <div class="form-check form-switch">

                                <input type="checkbox"
                                       name="status"
                                       value="1"
                                       class="form-check-input"
                                       id="status"
                                       {{ old('status', $surgery->status) ? 'checked' : '' }}>

                                <label class="form-check-label"
                                       for="status">

                                    Active

                                </label>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Submit --}}
            <div class="card">

                <div class="card-body">

                    <div class="d-flex justify-content-end gap-2">

                        <a href="{{ route('admin.surgeries.index') }}"
                           class="btn btn-secondary">

                            Cancel

                        </a>

                        <button type="submit"
                                class="btn btn-primary">

                            <i data-feather="save" class="me-2"></i>

                            Update Surgery

                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    if (typeof feather !== 'undefined') {
        feather.replace();
    }


    /*
    |--------------------------------------------------------------------------
    | Image Preview
    |--------------------------------------------------------------------------
    */

    const imageInput =
        document.querySelector('input[name="image"]');

    const preview =
        document.getElementById('imagePreview');

    const previewImage =
        document.getElementById('previewImage');


    if (imageInput) {

        imageInput.addEventListener('change', function (event) {

            const file =
                event.target.files[0];

            if (!file) {

                preview.classList.add('d-none');

                return;
            }

            const reader =
                new FileReader();

            reader.onload = function (e) {

                previewImage.src =
                    e.target.result;

                preview.classList.remove('d-none');

            };

            reader.readAsDataURL(file);

        });

    }

});

</script>

@endsection