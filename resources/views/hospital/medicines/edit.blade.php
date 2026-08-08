@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">

    <div class="content">

        {{-- =========================================================
        PAGE HEADER
        ========================================================== --}}

        <div class="page-header">

            <div class="page-title">

                <h4>Edit Medicine</h4>

                <h6>Update medicine information</h6>

            </div>

            <div class="page-btn">

                <a href="{{ route('hospital.medicines.show', $medicine->id) }}"
                   class="btn btn-light">

                    <i class="ti ti-arrow-left me-1"></i>

                    Back

                </a>

            </div>

        </div>


        {{-- =========================================================
        ALERTS
        ========================================================== --}}

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                <i class="ti ti-circle-check me-1"></i>

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        @if($errors->any())

            <div class="alert alert-danger alert-dismissible fade show">

                <strong>
                    Please fix the following errors:
                </strong>

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
        FORM
        ========================================================== --}}

        <form method="POST"
              action="{{ route(
                  'hospital.medicines.update',
                  $medicine->id
              ) }}"
              enctype="multipart/form-data">

            @csrf

            @method('PUT')


            {{-- =====================================================
            BASIC INFORMATION
            ====================================================== --}}

            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-0">

                        <i class="ti ti-pill me-2"></i>

                        Basic Information

                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-3">


                        {{-- MEDICINE NAME --}}

                        <div class="col-md-6">

                            <label class="form-label">

                                Medicine Name

                                <span class="text-danger">*</span>

                            </label>

                            <input type="text"
                                   name="medicine_name"
                                   value="{{ old(
                                       'medicine_name',
                                       $medicine->medicine_name
                                   ) }}"
                                   class="form-control @error('medicine_name') is-invalid @enderror"
                                   placeholder="Enter medicine name"
                                   required>

                            @error('medicine_name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- MEDICINE CODE --}}

                        <div class="col-md-6">

                            <label class="form-label">

                                Medicine Code

                            </label>

                            <input type="text"
                                   name="medicine_code"
                                   value="{{ old(
                                       'medicine_code',
                                       $medicine->medicine_code
                                   ) }}"
                                   class="form-control @error('medicine_code') is-invalid @enderror"
                                   placeholder="Medicine code">

                            @error('medicine_code')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- GENERIC NAME --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Generic Name
                            </label>

                            <input type="text"
                                   name="generic_name"
                                   value="{{ old(
                                       'generic_name',
                                       $medicine->generic_name
                                   ) }}"
                                   class="form-control @error('generic_name') is-invalid @enderror"
                                   placeholder="Enter generic name">

                            @error('generic_name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- BRAND NAME --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Brand Name
                            </label>

                            <input type="text"
                                   name="brand_name"
                                   value="{{ old(
                                       'brand_name',
                                       $medicine->brand_name
                                   ) }}"
                                   class="form-control @error('brand_name') is-invalid @enderror"
                                   placeholder="Enter brand name">

                            @error('brand_name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- MANUFACTURER --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Manufacturer
                            </label>

                            <input type="text"
                                   name="manufacturer"
                                   value="{{ old(
                                       'manufacturer',
                                       $medicine->manufacturer
                                   ) }}"
                                   class="form-control @error('manufacturer') is-invalid @enderror"
                                   placeholder="Enter manufacturer">

                            @error('manufacturer')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- MEDICINE TYPE --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Medicine Type
                            </label>

                            <select name="medicine_type"
                                    class="form-select @error('medicine_type') is-invalid @enderror">

                                <option value="">
                                    Select Medicine Type
                                </option>

                                @php
                                    $medicineTypes = [
                                        'Tablet',
                                        'Capsule',
                                        'Syrup',
                                        'Injection',
                                        'Cream',
                                        'Ointment',
                                        'Drops',
                                        'Powder',
                                        'Other'
                                    ];
                                @endphp

                                @foreach($medicineTypes as $type)

                                    <option value="{{ $type }}"
                                        {{ old(
                                            'medicine_type',
                                            $medicine->medicine_type
                                        ) == $type ? 'selected' : '' }}>

                                        {{ $type }}

                                    </option>

                                @endforeach

                            </select>

                            @error('medicine_type')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- STRENGTH --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Strength
                            </label>

                            <input type="text"
                                   name="strength"
                                   value="{{ old(
                                       'strength',
                                       $medicine->strength
                                   ) }}"
                                   class="form-control @error('strength') is-invalid @enderror"
                                   placeholder="Example: 500 mg">

                            @error('strength')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- PACK SIZE --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Pack Size
                            </label>

                            <input type="text"
                                   name="pack_size"
                                   value="{{ old(
                                       'pack_size',
                                       $medicine->pack_size
                                   ) }}"
                                   class="form-control @error('pack_size') is-invalid @enderror"
                                   placeholder="Example: 10 tablets">

                            @error('pack_size')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- IMAGE --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Medicine Image
                            </label>

                            <input type="file"
                                   name="image"
                                   id="medicineImage"
                                   class="form-control @error('image') is-invalid @enderror"
                                   accept="image/*">

                            @error('image')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <small class="text-muted d-block mt-1">

                                JPG, JPEG, PNG or WEBP

                            </small>

                        </div>


                        {{-- CURRENT IMAGE --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Current Image
                            </label>

                            <div id="currentImageWrapper">

                                @if($medicine->image)

                                    <img src="{{ asset($medicine->image) }}"
                                         alt="{{ $medicine->medicine_name }}"
                                         id="currentImage"
                                         style="
                                            width:100px;
                                            height:100px;
                                            object-fit:cover;
                                            border-radius:8px;
                                            border:1px solid #ddd;
                                         ">

                                @else

                                    <div class="
                                        d-flex
                                        align-items-center
                                        justify-content-center
                                        bg-light
                                        rounded
                                    "
                                         style="
                                            width:100px;
                                            height:100px;
                                         ">

                                        <i class="ti ti-pill"
                                           style="font-size:35px;">
                                        </i>

                                    </div>

                                @endif

                            </div>


                            {{-- NEW IMAGE PREVIEW --}}

                            <div id="newImagePreview"
                                 class="d-none mt-2">

                                <small class="text-muted d-block mb-1">
                                    New Image Preview
                                </small>

                                <img id="previewImage"
                                     src="#"
                                     alt="Preview"
                                     style="
                                        width:100px;
                                        height:100px;
                                        object-fit:cover;
                                        border-radius:8px;
                                        border:1px solid #ddd;
                                     ">

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
            PRICING & STOCK
            ====================================================== --}}

            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-0">

                        <i class="ti ti-currency-rupee me-2"></i>

                        Pricing & Stock

                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-3">


                        {{-- MRP --}}

                        <div class="col-md-4">

                            <label class="form-label">
                                MRP
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ₹
                                </span>

                                <input type="number"
                                       name="mrp"
                                       value="{{ old(
                                           'mrp',
                                           $medicine->mrp
                                       ) }}"
                                       class="form-control @error('mrp') is-invalid @enderror"
                                       min="0"
                                       step="0.01"
                                       placeholder="0.00">

                            </div>

                            @error('mrp')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- SELLING PRICE --}}

                        <div class="col-md-4">

                            <label class="form-label">

                                Selling Price

                                <span class="text-danger">*</span>

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ₹
                                </span>

                                <input type="number"
                                       name="selling_price"
                                       value="{{ old(
                                           'selling_price',
                                           $medicine->selling_price
                                       ) }}"
                                       class="form-control @error('selling_price') is-invalid @enderror"
                                       min="0"
                                       step="0.01"
                                       placeholder="0.00"
                                       required>

                            </div>

                            @error('selling_price')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- STOCK --}}

                        <div class="col-md-4">

                            <label class="form-label">

                                Stock Quantity

                                <span class="text-danger">*</span>

                            </label>

                            <input type="number"
                                   name="stock_quantity"
                                   value="{{ old(
                                       'stock_quantity',
                                       $medicine->stock_quantity
                                   ) }}"
                                   class="form-control @error('stock_quantity') is-invalid @enderror"
                                   min="0"
                                   required>

                            @error('stock_quantity')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- PRESCRIPTION --}}

                        <div class="col-md-6">

                            <label class="form-label">

                                Prescription Required

                            </label>

                            <select name="prescription_required"
                                    class="form-select">

                                <option value="0"
                                    {{ old(
                                        'prescription_required',
                                        $medicine->prescription_required
                                    ) == 0 ? 'selected' : '' }}>

                                    No - Prescription Not Required

                                </option>

                                <option value="1"
                                    {{ old(
                                        'prescription_required',
                                        $medicine->prescription_required
                                    ) == 1 ? 'selected' : '' }}>

                                    Yes - Prescription Required

                                </option>

                            </select>

                        </div>


                        {{-- STATUS --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Status
                            </label>

                            <select name="status"
                                    class="form-select">

                                <option value="1"
                                    {{ old(
                                        'status',
                                        $medicine->status
                                    ) == 1 ? 'selected' : '' }}>

                                    Active

                                </option>

                                <option value="0"
                                    {{ old(
                                        'status',
                                        $medicine->status
                                    ) == 0 ? 'selected' : '' }}>

                                    Inactive

                                </option>

                            </select>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
            MEDICINE INFORMATION
            ====================================================== --}}

            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-0">

                        <i class="ti ti-file-description me-2"></i>

                        Medicine Information

                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-3">


                        {{-- DESCRIPTION --}}

                        <div class="col-12">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea name="description"
                                      rows="4"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Enter medicine description">{{ old(
                                          'description',
                                          $medicine->description
                                      ) }}</textarea>

                            @error('description')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- COMPOSITION --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Composition
                            </label>

                            <textarea name="composition"
                                      rows="4"
                                      class="form-control @error('composition') is-invalid @enderror"
                                      placeholder="Enter medicine composition">{{ old(
                                          'composition',
                                          $medicine->composition
                                      ) }}</textarea>

                            @error('composition')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- USAGE --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Usage Instructions
                            </label>

                            <textarea name="usage_instructions"
                                      rows="4"
                                      class="form-control @error('usage_instructions') is-invalid @enderror"
                                      placeholder="Enter usage instructions">{{ old(
                                          'usage_instructions',
                                          $medicine->usage_instructions
                                      ) }}</textarea>

                            @error('usage_instructions')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- SIDE EFFECTS --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Side Effects
                            </label>

                            <textarea name="side_effects"
                                      rows="4"
                                      class="form-control @error('side_effects') is-invalid @enderror"
                                      placeholder="Enter possible side effects">{{ old(
                                          'side_effects',
                                          $medicine->side_effects
                                      ) }}</textarea>

                            @error('side_effects')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- STORAGE --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Storage Instructions
                            </label>

                            <textarea name="storage_instructions"
                                      rows="4"
                                      class="form-control @error('storage_instructions') is-invalid @enderror"
                                      placeholder="Enter storage instructions">{{ old(
                                          'storage_instructions',
                                          $medicine->storage_instructions
                                      ) }}</textarea>

                            @error('storage_instructions')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
            ACTIONS
            ====================================================== --}}

            <div class="card">

                <div class="card-body">

                    <div class="d-flex
                                justify-content-end
                                gap-2">

                        <a href="{{ route(
                            'hospital.medicines.show',
                            $medicine->id
                        ) }}"
                           class="btn btn-light">

                            Cancel

                        </a>


                        <button type="submit"
                                class="btn btn-primary">

                            <i class="ti ti-device-floppy me-1"></i>

                            Update Medicine

                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- =============================================================
IMAGE PREVIEW
============================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const imageInput =
            document.getElementById(
                'medicineImage'
            );


        const preview =
            document.getElementById(
                'newImagePreview'
            );


        const previewImage =
            document.getElementById(
                'previewImage'
            );


        if (!imageInput) {
            return;
        }


        imageInput.addEventListener(
            'change',
            function (event) {

                const file =
                    event.target.files[0];


                if (!file) {

                    preview.classList.add(
                        'd-none'
                    );

                    return;

                }


                const reader =
                    new FileReader();


                reader.onload =
                    function (e) {

                        previewImage.src =
                            e.target.result;

                        preview.classList.remove(
                            'd-none'
                        );

                    };


                reader.readAsDataURL(
                    file
                );

            }
        );

    }
);

</script>

@endsection