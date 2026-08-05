<?php $page = 'medicines'; ?>

@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            {{-- Page Header --}}
            <div class="page-header">

                <div class="page-title">
                    <h4>Edit Medicine</h4>
                    <h6>Update Hospital Medicine</h6>
                </div>

                <div class="page-btn">
                    <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary">

                        <i class="ti ti-arrow-left me-1"></i>
                        Back

                    </a>
                </div>

            </div>


            {{-- Validation Errors --}}
            @if ($errors->any())

                <div class="alert alert-danger alert-dismissible fade show">

                    <strong>Please fix the following errors:</strong>

                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            @if(session('error'))

                <div class="alert alert-danger alert-dismissible fade show">

                    {{ session('error') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            <form action="{{ route('admin.medicines.update', $medicine->id) }}" method="POST" enctype="multipart/form-data"
                id="medicineForm">

                @csrf
                @method('PUT')


                {{-- ============================================================
                MEDICINE INFORMATION
                ============================================================ --}}

                <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-pill me-2"></i>

                            Medicine Information

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            {{-- Hospital --}}

                            <div class="col-lg-6 col-md-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Hospital

                                        <span class="text-danger">*</span>

                                    </label>


                                    <select name="hospital_id" class="form-select" required>

                                        <option value="">
                                            Select Hospital
                                        </option>


                                        @foreach($hospitals as $hospital)

                                                                        <option value="{{ $hospital->id }}" {{
                                            old(
                                                'hospital_id',
                                                $medicine->hospital_id
                                            ) == $hospital->id
                                            ? 'selected'
                                            : ''
                                                                                }}>

                                                                            {{ $hospital->hospital_name }}

                                                                            @if($hospital->hospital_code)

                                                                                - {{ $hospital->hospital_code }}

                                                                            @endif

                                                                        </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>


                            {{-- Medicine Category --}}

                            <div class="col-lg-6 col-md-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Medicine Category

                                        <span class="text-danger">*</span>

                                    </label>


                                    <select name="medicine_category_id" class="form-select" required>

                                        <option value="">
                                            Select Medicine Category
                                        </option>


                                        @foreach($categories as $category)

                                                                        <option value="{{ $category->id }}" {{
                                            old(
                                                'medicine_category_id',
                                                $medicine->medicine_category_id
                                            ) == $category->id
                                            ? 'selected'
                                            : ''
                                                                                }}>

                                                                            {{ $category->category_name }}

                                                                        </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>


                            {{-- Medicine Name --}}

                            <div class="col-lg-6 col-md-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Medicine Name

                                        <span class="text-danger">*</span>

                                    </label>


                                    <input type="text" name="medicine_name" class="form-control" value="{{ old(
        'medicine_name',
        $medicine->medicine_name
    ) }}" placeholder="Enter Medicine Name" required>

                                </div>

                            </div>


                            {{-- Medicine Code --}}

                            <div class="col-lg-6 col-md-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Medicine Code
                                    </label>


                                    <input type="text" class="form-control" value="{{ $medicine->medicine_code }}" readonly>


                                    <small class="text-muted">
                                        Medicine code cannot be changed.
                                    </small>

                                </div>

                            </div>


                            {{-- Generic Name --}}

                            <div class="col-lg-6 col-md-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Generic Name
                                    </label>


                                    <input type="text" name="generic_name" class="form-control" value="{{ old(
        'generic_name',
        $medicine->generic_name
    ) }}" placeholder="Ex: Paracetamol">

                                </div>

                            </div>


                            {{-- Brand Name --}}

                            <div class="col-lg-6 col-md-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Brand Name
                                    </label>


                                    <input type="text" name="brand_name" class="form-control" value="{{ old(
        'brand_name',
        $medicine->brand_name
    ) }}" placeholder="Enter Brand Name">

                                </div>

                            </div>


                            {{-- Manufacturer --}}

                            <div class="col-lg-6 col-md-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Manufacturer
                                    </label>


                                    <input type="text" name="manufacturer" class="form-control" value="{{ old(
        'manufacturer',
        $medicine->manufacturer
    ) }}" placeholder="Enter Manufacturer">

                                </div>

                            </div>


                            {{-- Medicine Type --}}

                            <div class="col-lg-6 col-md-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Medicine Type
                                    </label>


                                    @php

                                        $medicineTypes = [

                                            'tablet' => 'Tablet',

                                            'capsule' => 'Capsule',

                                            'syrup' => 'Syrup',

                                            'injection' => 'Injection',

                                            'cream' => 'Cream',

                                            'ointment' => 'Ointment',

                                            'drops' => 'Drops',

                                            'inhaler' => 'Inhaler',

                                            'powder' => 'Powder',

                                            'gel' => 'Gel',

                                            'spray' => 'Spray',

                                            'other' => 'Other',

                                        ];

                                    @endphp


                                    <select name="medicine_type" class="form-select">

                                        <option value="">
                                            Select Medicine Type
                                        </option>


                                        @foreach($medicineTypes as $value => $label)

                                                                        <option value="{{ $value }}" {{
                                            old(
                                                'medicine_type',
                                                $medicine->medicine_type
                                            ) == $value
                                            ? 'selected'
                                            : ''
                                                                                }}>

                                                                            {{ $label }}

                                                                        </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>


                            {{-- Strength --}}

                            <div class="col-lg-6 col-md-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Strength
                                    </label>


                                    <input type="text" name="strength" class="form-control" value="{{ old(
        'strength',
        $medicine->strength
    ) }}" placeholder="Ex: 500mg">

                                </div>

                            </div>


                            {{-- Pack Size --}}

                            <div class="col-lg-6 col-md-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Pack Size
                                    </label>


                                    <input type="text" name="pack_size" class="form-control" value="{{ old(
        'pack_size',
        $medicine->pack_size
    ) }}" placeholder="Ex: 10 Tablets">

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ============================================================
                PRICING & STOCK
                ============================================================ --}}

                <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-currency-rupee me-2"></i>

                            Pricing & Stock

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            {{-- MRP --}}

                            <div class="col-lg-4 col-md-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">

                                        MRP

                                        <span class="text-danger">*</span>

                                    </label>


                                    <div class="input-group">

                                        <span class="input-group-text">
                                            ₹
                                        </span>


                                        <input type="number" name="mrp" id="mrp" class="form-control" value="{{ old(
        'mrp',
        $medicine->mrp
    ) }}" step="0.01" min="0" required>

                                    </div>

                                </div>

                            </div>


                            {{-- Selling Price --}}

                            <div class="col-lg-4 col-md-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Selling Price

                                        <span class="text-danger">*</span>

                                    </label>


                                    <div class="input-group">

                                        <span class="input-group-text">
                                            ₹
                                        </span>


                                        <input type="number" name="selling_price" id="selling_price" class="form-control"
                                            value="{{ old(
        'selling_price',
        $medicine->selling_price
    ) }}" step="0.01" min="0" required>

                                    </div>


                                    <small id="priceError" class="text-danger d-none">

                                        Selling price cannot be greater than MRP.

                                    </small>

                                </div>

                            </div>


                            {{-- Stock --}}

                            <div class="col-lg-4 col-md-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Stock Quantity

                                        <span class="text-danger">*</span>

                                    </label>


                                    <input type="number" name="stock_quantity" class="form-control" value="{{ old(
        'stock_quantity',
        $medicine->stock_quantity
    ) }}" min="0" required>

                                </div>

                            </div>


                            {{-- Prescription --}}

                            <div class="col-lg-6 col-md-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Prescription Required

                                        <span class="text-danger">*</span>

                                    </label>


                                    <select name="prescription_required" class="form-select" required>

                                        <option value="0" {{
        old(
            'prescription_required',
            $medicine->prescription_required
        ) == 0
        ? 'selected'
        : ''
                                            }}>

                                            No

                                        </option>


                                        <option value="1" {{
        old(
            'prescription_required',
            $medicine->prescription_required
        ) == 1
        ? 'selected'
        : ''
                                            }}>

                                            Yes

                                        </option>

                                    </select>

                                </div>

                            </div>


                            {{-- Status --}}

                            <div class="col-lg-6 col-md-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Status

                                        <span class="text-danger">*</span>

                                    </label>


                                    <select name="status" class="form-select" required>

                                        <option value="1" {{
        old(
            'status',
            $medicine->status
        ) == 1
        ? 'selected'
        : ''
                                            }}>

                                            Active

                                        </option>


                                        <option value="0" {{
        old(
            'status',
            $medicine->status
        ) == 0
        ? 'selected'
        : ''
                                            }}>

                                            Inactive

                                        </option>

                                    </select>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ============================================================
                MEDICINE DETAILS
                ============================================================ --}}

                <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-notes me-2"></i>

                            Medicine Details

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            {{-- Composition --}}

                            <div class="col-lg-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Composition
                                    </label>


                                    <textarea name="composition" class="form-control" rows="4"
                                        placeholder="Enter Medicine Composition">{{ old(
        'composition',
        $medicine->composition
    ) }}</textarea>

                                </div>

                            </div>


                            {{-- Description --}}

                            <div class="col-lg-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Description
                                    </label>


                                    <textarea name="description" class="form-control" rows="4"
                                        placeholder="Enter Medicine Description">{{ old(
        'description',
        $medicine->description
    ) }}</textarea>

                                </div>

                            </div>


                            {{-- Usage Instructions --}}

                            <div class="col-lg-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Usage Instructions
                                    </label>


                                    <textarea name="usage_instructions" class="form-control" rows="4"
                                        placeholder="Enter Usage Instructions">{{ old(
        'usage_instructions',
        $medicine->usage_instructions
    ) }}</textarea>

                                </div>

                            </div>


                            {{-- Side Effects --}}

                            <div class="col-lg-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Side Effects
                                    </label>


                                    <textarea name="side_effects" class="form-control" rows="4"
                                        placeholder="Enter Possible Side Effects">{{ old(
        'side_effects',
        $medicine->side_effects
    ) }}</textarea>

                                </div>

                            </div>


                            {{-- Storage Instructions --}}

                            <div class="col-lg-12">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Storage Instructions
                                    </label>


                                    <textarea name="storage_instructions" class="form-control" rows="3"
                                        placeholder="Enter Storage Instructions">{{ old(
        'storage_instructions',
        $medicine->storage_instructions
    ) }}</textarea>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ============================================================
                MEDICINE IMAGE
                ============================================================ --}}

                <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-photo me-2"></i>

                            Medicine Image

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row align-items-center">


                            {{-- Upload --}}

                            <div class="col-lg-6 col-md-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Change Medicine Image

                                    </label>


                                    <input type="file" name="image" id="image" class="form-control"
                                        accept=".jpg,.jpeg,.png,.webp">


                                    <small class="text-muted">

                                        Leave empty to keep the current image.
                                        JPG, JPEG, PNG or WEBP. Maximum 5 MB.

                                    </small>

                                </div>

                            </div>


                            {{-- Preview --}}

                            <div class="col-lg-6 col-md-6 col-12">

                                <div class="mb-3">

                                    <label class="form-label d-block">

                                        Current Image

                                    </label>


                                    <div class="border rounded p-2 d-inline-block">

                                        @if($medicine->image)

                                            <img src="{{ asset($medicine->image) }}" id="imagePreview"
                                                alt="{{ $medicine->medicine_name }}" style="
                                                        width: 170px;
                                                        height: 170px;
                                                        object-fit: cover;
                                                        border-radius: 6px;
                                                    ">

                                            <div id="imagePlaceholder"
                                                class="d-none bg-light align-items-center justify-content-center" style="
                                                        width: 170px;
                                                        height: 170px;
                                                    ">

                                                <i class="ti ti-photo fs-1 text-muted"></i>

                                            </div>

                                        @else

                                            <img src="" id="imagePreview" class="d-none" alt="Medicine Preview" style="
                                                        width: 170px;
                                                        height: 170px;
                                                        object-fit: cover;
                                                        border-radius: 6px;
                                                    ">


                                            <div id="imagePlaceholder"
                                                class="bg-light d-flex align-items-center justify-content-center" style="
                                                        width: 170px;
                                                        height: 170px;
                                                    ">

                                                <i class="ti ti-photo fs-1 text-muted"></i>

                                            </div>

                                        @endif

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ============================================================
                ACTION BUTTONS
                ============================================================ --}}

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary">

                                Cancel

                            </a>


                            <button type="submit" class="btn btn-primary" id="submitBtn">

                                <span id="submitText">

                                    <i class="ti ti-device-floppy me-1"></i>

                                    Update Medicine

                                </span>


                                <span id="loadingText" class="d-none">

                                    <span class="spinner-border spinner-border-sm me-1"></span>

                                    Updating...

                                </span>

                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>
    </div>


    <script>

        document.addEventListener('DOMContentLoaded', function () {

            /*
            |--------------------------------------------------------------------------
            | Image Preview
            |--------------------------------------------------------------------------
            */

            const imageInput =
                document.getElementById('image');

            const imagePreview =
                document.getElementById('imagePreview');

            const imagePlaceholder =
                document.getElementById('imagePlaceholder');


            if (imageInput) {

                imageInput.addEventListener(
                    'change',
                    function () {

                        if (
                            !this.files ||
                            !this.files[0]
                        ) {
                            return;
                        }


                        const reader =
                            new FileReader();


                        reader.onload =
                            function (event) {

                                imagePreview.src =
                                    event.target.result;

                                imagePreview.classList.remove(
                                    'd-none'
                                );


                                if (imagePlaceholder) {

                                    imagePlaceholder.classList.add(
                                        'd-none'
                                    );

                                    imagePlaceholder.classList.remove(
                                        'd-flex'
                                    );

                                }

                            };


                        reader.readAsDataURL(
                            this.files[0]
                        );

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Price Validation
            |--------------------------------------------------------------------------
            */

            const mrp =
                document.getElementById('mrp');

            const sellingPrice =
                document.getElementById(
                    'selling_price'
                );

            const priceError =
                document.getElementById(
                    'priceError'
                );


            function validatePrice() {

                if (
                    mrp.value !== '' &&
                    sellingPrice.value !== '' &&
                    parseFloat(sellingPrice.value) >
                    parseFloat(mrp.value)
                ) {

                    sellingPrice.classList.add(
                        'is-invalid'
                    );

                    priceError.classList.remove(
                        'd-none'
                    );

                    return false;

                }


                sellingPrice.classList.remove(
                    'is-invalid'
                );

                priceError.classList.add(
                    'd-none'
                );

                return true;

            }


            if (mrp && sellingPrice) {

                mrp.addEventListener(
                    'input',
                    validatePrice
                );

                sellingPrice.addEventListener(
                    'input',
                    validatePrice
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Form Submit
            |--------------------------------------------------------------------------
            */

            const form =
                document.getElementById(
                    'medicineForm'
                );


            if (form) {

                form.addEventListener(
                    'submit',
                    function (event) {

                        if (!validatePrice()) {

                            event.preventDefault();

                            sellingPrice.focus();

                            return;

                        }


                        const submitBtn =
                            document.getElementById(
                                'submitBtn'
                            );

                        const submitText =
                            document.getElementById(
                                'submitText'
                            );

                        const loadingText =
                            document.getElementById(
                                'loadingText'
                            );


                        submitBtn.disabled = true;

                        submitText.classList.add(
                            'd-none'
                        );

                        loadingText.classList.remove(
                            'd-none'
                        );

                    }
                );

            }

        });

    </script>

@endsection