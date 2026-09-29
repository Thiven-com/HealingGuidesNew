@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">

    <div class="content">

        {{-- =========================================================
            PAGE HEADER
        ========================================================== --}}

        <div class="page-header">

            <div class="add-item d-flex">

                <div class="page-title">

                    <h4>Edit Procedure</h4>

                    <h6>Update procedure and benefits</h6>

                </div>

            </div>

            <div class="page-btn">

                <a href="{{ route('admin.procedures.index') }}"
                   class="btn btn-light me-2">

                    <i class="ti ti-arrow-left me-1"></i>

                    Back

                </a>

                <a href="{{ route('admin.procedures.show', $procedure->id) }}"
                   class="btn btn-info">

                    <i class="ti ti-eye me-1"></i>

                    View

                </a>

            </div>

        </div>


        {{-- =========================================================
            VALIDATION ERRORS
        ========================================================== --}}

        @if($errors->any())

            <div class="alert alert-danger alert-dismissible fade show">

                <strong>Please fix the following errors:</strong>

                <ul class="mb-0 mt-2">

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


        {{-- =========================================================
            SUCCESS
        ========================================================== --}}

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- =========================================================
            FORM
        ========================================================== --}}

        <form action="{{ route('admin.procedures.update', $procedure->id) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            @method('PUT')


            {{-- =====================================================
                PROCEDURE INFORMATION
            ====================================================== --}}

            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-0">

                        <i class="ti ti-stethoscope me-1"></i>

                        Procedure Information

                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-3">


                        {{-- SPECIALIZATION --}}

                        <div class="col-md-6">

                            <label class="form-label">

                                Specialization

                            </label>

                            <select name="specialization_id"
                                    class="form-select @error('specialization_id') is-invalid @enderror">

                                <option value="">

                                    Select Specialization

                                </option>

                                @foreach($specializations as $specialization)

                                    <option value="{{ $specialization->id }}"
                                        {{ old(
                                            'specialization_id',
                                            $procedure->specialization_id
                                        ) == $specialization->id
                                            ? 'selected'
                                            : '' }}>

                                        {{ $specialization->specialization_name }}

                                    </option>

                                @endforeach

                            </select>

                            @error('specialization_id')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>


                        {{-- NAME --}}

                        <div class="col-md-6">

                            <label class="form-label">

                                Procedure Name

                                <span class="text-danger">*</span>

                            </label>

                            <input type="text"
                                   name="name"
                                   id="procedureName"
                                   value="{{ old('name', $procedure->name) }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Enter procedure name"
                                   required>

                            @error('name')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>


                        {{-- SLUG --}}

                        <div class="col-md-6">

                            <label class="form-label">

                                Slug

                            </label>

                            <input type="text"
                                   name="slug"
                                   id="procedureSlug"
                                   value="{{ old('slug', $procedure->slug) }}"
                                   class="form-control"
                                   placeholder="procedure-slug">

                        </div>


                        {{-- PRICE --}}

                        <div class="col-md-3">

                            <label class="form-label">

                                Price

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">

                                    ₹

                                </span>

                                <input type="number"
                                       name="price"
                                       value="{{ old('price', $procedure->price) }}"
                                       min="0"
                                       step="0.01"
                                       class="form-control">

                            </div>

                        </div>


                        {{-- DISPLAY ORDER --}}

                        <div class="col-md-3">

                            <label class="form-label">

                                Display Order

                            </label>

                            <input type="number"
                                   name="display_order"
                                   value="{{ old(
                                       'display_order',
                                       $procedure->display_order
                                   ) }}"
                                   min="0"
                                   class="form-control">

                        </div>


                        {{-- DURATION --}}

                        <div class="col-md-4">

                            <label class="form-label">

                                Duration

                            </label>

                            <input type="text"
                                   name="duration"
                                   value="{{ old(
                                       'duration',
                                       $procedure->duration
                                   ) }}"
                                   class="form-control"
                                   placeholder="Example: 2-3 Hours">

                        </div>


                        {{-- HOSPITAL STAY --}}

                        <div class="col-md-4">

                            <label class="form-label">

                                Hospital Stay

                            </label>

                            <input type="text"
                                   name="hospital_stay"
                                   value="{{ old(
                                       'hospital_stay',
                                       $procedure->hospital_stay
                                   ) }}"
                                   class="form-control"
                                   placeholder="Example: 2 Days">

                        </div>


                        {{-- RECOVERY --}}

                        <div class="col-md-4">

                            <label class="form-label">

                                Recovery

                            </label>

                            <input type="text"
                                   name="recovery"
                                   value="{{ old(
                                       'recovery',
                                       $procedure->recovery
                                   ) }}"
                                   class="form-control"
                                   placeholder="Example: 4-6 Weeks">

                        </div>


                        {{-- SHORT DESCRIPTION --}}

                        <div class="col-md-12">

                            <label class="form-label">

                                Short Description

                            </label>

                            <textarea name="short_description"
                                      rows="2"
                                      maxlength="500"
                                      class="form-control"
                                      placeholder="Enter short description">{{ old(
                                          'short_description',
                                          $procedure->short_description
                                      ) }}</textarea>

                        </div>


                        {{-- DESCRIPTION --}}

                        <div class="col-md-12">

                            <label class="form-label">

                                Description

                            </label>

                            <textarea name="description"
                                      rows="4"
                                      class="form-control"
                                      placeholder="Enter procedure description">{{ old(
                                          'description',
                                          $procedure->description
                                      ) }}</textarea>

                        </div>


                        {{-- ABOUT --}}

                        <div class="col-md-12">

                            <label class="form-label">

                                About Procedure

                            </label>

                            <textarea name="about"
                                      rows="4"
                                      class="form-control"
                                      placeholder="Enter detailed information">{{ old(
                                          'about',
                                          $procedure->about
                                      ) }}</textarea>

                        </div>


                        {{-- CURRENT IMAGE --}}

                        <div class="col-md-6">

                            <label class="form-label">

                                Procedure Image

                            </label>

                            @if($procedure->image)

                                <div class="mb-2">

                                    <img src="{{ asset($procedure->image
                                    ) }}"
                                         alt="{{ $procedure->name }}"
                                         style="
                                            width:120px;
                                            height:120px;
                                            object-fit:cover;
                                         "
                                         class="rounded border">

                                </div>

                            @endif


                            <input type="file"
                                   name="image"
                                   id="procedureImage"
                                   accept="image/jpeg,image/jpg,image/png,image/webp"
                                   class="form-control">

                            <small class="text-muted">

                                Leave empty to keep the existing image.

                            </small>


                            <div id="imagePreviewWrapper"
                                 class="mt-3 d-none">

                                <img id="imagePreview"
                                     src=""
                                     alt="Preview"
                                     style="
                                        width:120px;
                                        height:120px;
                                        object-fit:cover;
                                     "
                                     class="rounded border">

                            </div>

                        </div>

                        {{-- STATUS --}}

                        <div class="col-md-3">

                            <label class="form-label">

                                Status

                            </label>

                            <select name="status"
                                    class="form-select">

                                <option value="1"
                                    {{ old(
                                        'status',
                                        $procedure->status
                                    ) == 1
                                        ? 'selected'
                                        : '' }}>

                                    Active

                                </option>

                                <option value="0"
                                    {{ old(
                                        'status',
                                        $procedure->status
                                    ) == 0
                                        ? 'selected'
                                        : '' }}>

                                    Inactive

                                </option>

                            </select>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                PROCEDURE BENEFITS
            ====================================================== --}}

            <div class="card mt-3">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="card-title mb-0">

                                <i class="ti ti-list-check me-1"></i>

                                Procedure Benefits

                            </h5>

                            <small class="text-muted">

                                Manage benefits for this procedure.

                            </small>

                        </div>


                        <button type="button"
                                id="addBenefit"
                                class="btn btn-primary btn-sm">

                            <i class="ti ti-plus me-1"></i>

                            Add Benefit

                        </button>

                    </div>

                </div>


                <div class="card-body">

                    <div id="benefitsWrapper">


                        {{-- =================================================
                            OLD FORM VALUES
                        ================================================== --}}

                        @if(old('benefits'))

                            @foreach(old('benefits') as $index => $benefit)

                                <div class="benefit-item border rounded p-3 mb-3">

                                    <div class="row g-3">


                                        <div class="col-md-4">

                                            <label class="form-label">

                                                Benefit Title

                                                <span class="text-danger">*</span>

                                            </label>

                                            <input type="text"
                                                   name="benefits[{{ $index }}][title]"
                                                   value="{{ $benefit['title'] ?? '' }}"
                                                   class="form-control"
                                                   required>

                                        </div>

                                        <div class="col-md-2">

                                            <label class="form-label">

                                                Display Order

                                            </label>

                                            <input type="number"
                                                   name="benefits[{{ $index }}][display_order]"
                                                   value="{{ $benefit['display_order'] ?? $index }}"
                                                   min="0"
                                                   class="form-control">

                                        </div>


                                        <div class="col-md-2">

                                            <label class="form-label">

                                                Status

                                            </label>

                                            <select name="benefits[{{ $index }}][status]"
                                                    class="form-select">

                                                <option value="1"
                                                    {{ ($benefit['status'] ?? 1) == 1
                                                        ? 'selected'
                                                        : '' }}>

                                                    Active

                                                </option>

                                                <option value="0"
                                                    {{ ($benefit['status'] ?? 1) == 0
                                                        ? 'selected'
                                                        : '' }}>

                                                    Inactive

                                                </option>

                                            </select>

                                        </div>


                                        <div class="col-md-2 d-flex align-items-end">

                                            <button type="button"
                                                    class="btn btn-danger removeBenefit w-100">

                                                <i class="ti ti-trash me-1"></i>

                                                Remove

                                            </button>

                                        </div>


                                        <div class="col-md-12">

                                            <label class="form-label">

                                                Description

                                            </label>

                                            <textarea name="benefits[{{ $index }}][description]"
                                                      rows="2"
                                                      class="form-control">{{ $benefit['description'] ?? '' }}</textarea>

                                        </div>

                                    </div>

                                </div>

                            @endforeach


                        @else


                            {{-- =================================================
                                EXISTING DATABASE BENEFITS
                            ================================================== --}}

                            @foreach($procedure->benefits as $index => $benefit)

                                <div class="benefit-item border rounded p-3 mb-3">

                                    <input type="hidden"
                                           name="benefits[{{ $index }}][id]"
                                           value="{{ $benefit->id }}">


                                    <div class="row g-3">


                                        <div class="col-md-4">

                                            <label class="form-label">

                                                Benefit Title

                                                <span class="text-danger">*</span>

                                            </label>

                                            <input type="text"
                                                   name="benefits[{{ $index }}][title]"
                                                   value="{{ $benefit->title }}"
                                                   class="form-control"
                                                   required>

                                        </div>

                                        <div class="col-md-2">

                                            <label class="form-label">

                                                Display Order

                                            </label>

                                            <input type="number"
                                                   name="benefits[{{ $index }}][display_order]"
                                                   value="{{ $benefit->display_order }}"
                                                   min="0"
                                                   class="form-control">

                                        </div>


                                        <div class="col-md-2">

                                            <label class="form-label">

                                                Status

                                            </label>

                                            <select name="benefits[{{ $index }}][status]"
                                                    class="form-select">

                                                <option value="1"
                                                    {{ $benefit->status == 1
                                                        ? 'selected'
                                                        : '' }}>

                                                    Active

                                                </option>

                                                <option value="0"
                                                    {{ $benefit->status == 0
                                                        ? 'selected'
                                                        : '' }}>

                                                    Inactive

                                                </option>

                                            </select>

                                        </div>


                                        <div class="col-md-2 d-flex align-items-end">

                                            <button type="button"
                                                    class="btn btn-danger removeBenefit w-100">

                                                <i class="ti ti-trash me-1"></i>

                                                Remove

                                            </button>

                                        </div>


                                        <div class="col-md-12">

                                            <label class="form-label">

                                                Description

                                            </label>

                                            <textarea name="benefits[{{ $index }}][description]"
                                                      rows="2"
                                                      class="form-control">{{ $benefit->description }}</textarea>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        @endif

                    </div>


                    {{-- NO BENEFITS --}}

                    <div id="noBenefits"
                         class="{{ $procedure->benefits->count() > 0 ? 'd-none' : '' }} text-center py-4">

                        <i class="ti ti-list-check"
                           style="font-size:45px;color:#adb5bd;">
                        </i>

                        <h6 class="mt-2">

                            No benefits added

                        </h6>

                        <p class="text-muted">

                            Click "Add Benefit" to add a benefit.

                        </p>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                ACTIONS
            ====================================================== --}}

            <div class="card mt-3">

                <div class="card-body">

                    <div class="d-flex justify-content-end gap-2">

                        <a href="{{ route('admin.procedures.index') }}"
                           class="btn btn-light">

                            <i class="ti ti-x me-1"></i>

                            Cancel

                        </a>


                        <button type="submit"
                                class="btn btn-primary">

                            <i class="ti ti-device-floppy me-1"></i>

                            Update Procedure

                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- =============================================================
    JAVASCRIPT
============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const addBenefitButton =
        document.getElementById('addBenefit');

    const benefitsWrapper =
        document.getElementById('benefitsWrapper');

    const noBenefits =
        document.getElementById('noBenefits');

    let benefitIndex =
        {{ old('benefits')
            ? count(old('benefits'))
            : $procedure->benefits->count()
        }};


    /*
    |--------------------------------------------------------------------------
    | Update Empty State
    |--------------------------------------------------------------------------
    */

    function updateEmptyState()
    {

        const count =
            benefitsWrapper.querySelectorAll(
                '.benefit-item'
            ).length;


        if (count === 0) {

            noBenefits.classList.remove('d-none');

        } else {

            noBenefits.classList.add('d-none');

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Add Benefit
    |--------------------------------------------------------------------------
    */

    if (addBenefitButton) {

        addBenefitButton.addEventListener(
            'click',
            function (e) {

                e.preventDefault();


                const html = `

                    <div class="benefit-item border rounded p-3 mb-3">

                        <div class="row g-3">

                            <div class="col-md-4">

                                <label class="form-label">

                                    Benefit Title

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="text"
                                       name="benefits[${benefitIndex}][title]"
                                       class="form-control"
                                       placeholder="Enter benefit title"
                                       required>

                            </div>

                            <div class="col-md-2">

                                <label class="form-label">

                                    Display Order

                                </label>

                                <input type="number"
                                       name="benefits[${benefitIndex}][display_order]"
                                       value="${benefitIndex}"
                                       min="0"
                                       class="form-control">

                            </div>


                            <div class="col-md-2">

                                <label class="form-label">

                                    Status

                                </label>

                                <select name="benefits[${benefitIndex}][status]"
                                        class="form-select">

                                    <option value="1" selected>
                                        Active
                                    </option>

                                    <option value="0">
                                        Inactive
                                    </option>

                                </select>

                            </div>


                            <div class="col-md-2 d-flex align-items-end">

                                <button type="button"
                                        class="btn btn-danger removeBenefit w-100">

                                    <i class="ti ti-trash me-1"></i>

                                    Remove

                                </button>

                            </div>


                            <div class="col-md-12">

                                <label class="form-label">

                                    Description

                                </label>

                                <textarea name="benefits[${benefitIndex}][description]"
                                          rows="2"
                                          class="form-control"
                                          placeholder="Enter benefit description"></textarea>

                            </div>

                        </div>

                    </div>

                `;


                benefitsWrapper.insertAdjacentHTML(
                    'beforeend',
                    html
                );


                benefitIndex++;


                updateEmptyState();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Remove Benefit
    |--------------------------------------------------------------------------
    */

    benefitsWrapper.addEventListener(
        'click',
        function (e) {

            const button =
                e.target.closest(
                    '.removeBenefit'
                );


            if (!button) {
                return;
            }


            e.preventDefault();


            const item =
                button.closest(
                    '.benefit-item'
                );


            if (item) {

                item.remove();

            }


            updateEmptyState();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Slug
    |--------------------------------------------------------------------------
    */

    const procedureName =
        document.getElementById('procedureName');

    const procedureSlug =
        document.getElementById('procedureSlug');


    if (procedureName && procedureSlug) {

        procedureName.addEventListener(
            'input',
            function () {

                if (
                    procedureSlug.dataset.manual === 'true'
                ) {
                    return;
                }


                procedureSlug.value =
                    this.value
                        .toLowerCase()
                        .trim()
                        .replace(
                            /[^a-z0-9]+/g,
                            '-'
                        )
                        .replace(
                            /^-+|-+$/g,
                            ''
                        );

            }
        );


        procedureSlug.addEventListener(
            'input',
            function () {

                this.dataset.manual = 'true';

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Image Preview
    |--------------------------------------------------------------------------
    */

    const imageInput =
        document.getElementById('procedureImage');

    const imagePreviewWrapper =
        document.getElementById(
            'imagePreviewWrapper'
        );

    const imagePreview =
        document.getElementById(
            'imagePreview'
        );


    if (imageInput) {

        imageInput.addEventListener(
            'change',
            function (event) {

                const file =
                    event.target.files[0];


                if (!file) {
                    return;
                }


                const reader =
                    new FileReader();


                reader.onload =
                    function (e) {

                        imagePreview.src =
                            e.target.result;

                        imagePreviewWrapper.classList.remove(
                            'd-none'
                        );

                    };


                reader.readAsDataURL(file);

            }
        );

    }


    updateEmptyState();

});

</script>

@endsection