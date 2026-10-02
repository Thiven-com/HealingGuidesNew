@extends('layout.mainlayout')

@section('title', 'Create Prescription Quotation')

@section('content')

<div class="page-wrapper">

    <div class="content">

        {{-- ============================================================
             PAGE HEADER
        ============================================================= --}}

        <div class="page-header">
            <div class="row align-items-center">

                <div class="col-sm-8">

                    <h4 class="page-title mb-1">
                        Create Prescription Quotation
                    </h4>

                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.prescription-requests.index') }}">
                                Prescription Requests
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.prescription-requests.show', $prescriptionRequest->id) }}">
                                Request #{{ $prescriptionRequest->id }}
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Create Quotation
                        </li>
                    </ul>

                </div>

                <div class="col-sm-4 text-sm-end">

                    <a href="{{ route('admin.prescription-requests.show', $prescriptionRequest->id) }}"
                       class="btn btn-light">
                        <i class="ti ti-arrow-left me-1"></i>
                        Back
                    </a>

                </div>

            </div>
        </div>


        {{-- ============================================================
             ALERTS
        ============================================================= --}}

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                <i class="ti ti-check me-2"></i>

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show">

                <i class="ti ti-alert-circle me-2"></i>

                {{ session('error') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        @if($errors->any())

            <div class="alert alert-danger">

                <div class="fw-semibold mb-2">
                    Please fix the following errors:
                </div>

                <ul class="mb-0">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- ============================================================
             REQUEST INFORMATION
        ============================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="card-title mb-0">
                    <i class="ti ti-file-description me-2"></i>
                    Prescription Request
                </h5>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-3">

                        <div class="text-muted small">
                            Request ID
                        </div>

                        <div class="fw-semibold">
                            #{{ $prescriptionRequest->id }}
                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Customer
                        </div>

                        <div class="fw-semibold">
                            {{ optional($prescriptionRequest->customer)->name ?? '-' }}
                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Family Member
                        </div>

                        <div class="fw-semibold">
                            {{ optional($prescriptionRequest->familyMember)->name ?? '-' }}
                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Request Type
                        </div>

                        <span class="badge bg-soft-primary text-primary">

                            {{ ucfirst(str_replace('_', ' ', $prescriptionRequest->request_type)) }}

                        </span>

                    </div>


                    @if($prescriptionRequest->address)

                        <div class="col-md-12">

                            <div class="text-muted small">
                                Customer Address
                            </div>

                            <div>
                                {{ $prescriptionRequest->address }}

                                @if($prescriptionRequest->city)
                                    , {{ $prescriptionRequest->city }}
                                @endif

                                @if($prescriptionRequest->state)
                                    , {{ $prescriptionRequest->state }}
                                @endif

                                @if($prescriptionRequest->pincode)
                                    - {{ $prescriptionRequest->pincode }}
                                @endif
                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- ============================================================
             QUOTATION FORM
        ============================================================= --}}

        <form method="POST"
              action="{{ route('admin.prescription-requests.quotation.store', $prescriptionRequest->id) }}"
              id="quotationForm">

            @csrf


            <div class="row">


                {{-- ====================================================
                     LEFT CONTENT
                ===================================================== --}}

                <div class="col-xl-8">


                    {{-- =================================================
                         PROVIDER
                    ================================================== --}}

                    <div class="card mb-4">

                        <div class="card-header">

                            <div class="d-flex align-items-center">

                                <div>

                                    <h5 class="card-title mb-1">
                                        Provider Details
                                    </h5>

                                    <p class="text-muted mb-0 small">
                                        Select the hospital or diagnostic centre
                                        that will provide this quotation.
                                    </p>

                                </div>

                            </div>

                        </div>


                        <div class="card-body">

                            <div class="row g-3">


                                {{-- PROVIDER TYPE --}}

                                <div class="col-md-4">

                                    <label class="form-label">
                                        Provider Type
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="provider_type"
                                            id="provider_type"
                                            class="form-select"
                                            required>

                                        <option value="">
                                            Select Provider
                                        </option>

                                        <option value="hospital">
                                            Hospital
                                        </option>

                                        <option value="diagnostic">
                                            Diagnostic Centre
                                        </option>

                                    </select>

                                </div>


                                {{-- HOSPITAL --}}

                                <div class="col-md-8 provider-hospital d-none">

                                    <label class="form-label">
                                        Hospital
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="hospital_id"
                                            id="hospital_id"
                                            class="form-select">

                                        <option value="">
                                            Select Hospital
                                        </option>

                                        @foreach($hospitals ?? [] as $hospital)

                                            <option value="{{ $hospital->id }}">

                                                {{ $hospital->hospital_name }}

                                            </option>

                                        @endforeach

                                    </select>

                                    <small class="text-muted">
                                        Only medicines belonging to this hospital
                                        will be available for quotation.
                                    </small>

                                </div>


                                {{-- DIAGNOSTIC --}}

                                <div class="col-md-8 provider-diagnostic d-none">

                                    <label class="form-label">
                                        Diagnostic Centre
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="diagnostic_id"
                                            id="diagnostic_id"
                                            class="form-select">

                                        <option value="">
                                            Select Diagnostic Centre
                                        </option>

                                        @foreach($diagnostics ?? [] as $diagnostic)

                                            <option value="{{ $diagnostic->id }}">

                                                {{ $diagnostic->name }}

                                            </option>

                                        @endforeach

                                    </select>

                                    <small class="text-muted">
                                        Only tests available at this diagnostic
                                        centre will be available.
                                    </small>

                                </div>


                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         QUOTATION DETAILS
                    ================================================== --}}

                    <div class="card mb-4">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Quotation Details
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row g-3">

                                <div class="col-md-4">

                                    <label class="form-label">
                                        Quotation Type
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="quotation_type"
                                            class="form-select"
                                            required>

                                        <option value="">
                                            Select Type
                                        </option>

                                        <option value="medicines">
                                            Order Medicines
                                        </option>

                                        <option value="lab_tests">
                                            Lab Tests
                                        </option>

                                        <option value="both">
                                            Medicines & Lab Tests
                                        </option>

                                    </select>

                                </div>


                                <div class="col-md-4">

                                    <label class="form-label">
                                        Valid Until
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="date"
                                           name="valid_until"
                                           class="form-control"
                                           value="{{ old('valid_until', now()->addDays(7)->format('Y-m-d')) }}"
                                           min="{{ now()->format('Y-m-d') }}"
                                           required>

                                </div>


                                <div class="col-md-4">

                                    <label class="form-label">
                                        Quotation Details
                                    </label>

                                    <input type="text"
                                           name="quotation_details"
                                           class="form-control"
                                           value="{{ old('quotation_details') }}"
                                           placeholder="Short quotation description">

                                </div>


                                <div class="col-md-12">

                                    <label class="form-label">
                                        Admin Notes
                                    </label>

                                    <textarea name="admin_notes"
                                              class="form-control"
                                              rows="3"
                                              placeholder="Internal/admin notes">{{ old('admin_notes') }}</textarea>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         MEDICINES
                    ================================================== --}}

                    <div class="card mb-4">

                        <div class="card-header">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <h5 class="card-title mb-1">
                                        <i class="ti ti-pill me-2"></i>
                                        Medicines
                                    </h5>

                                    <p class="text-muted small mb-0">
                                        Medicines are filtered by selected hospital.
                                    </p>

                                </div>


                                <button type="button"
                                        class="btn btn-primary btn-sm"
                                        id="addMedicine">

                                    <i class="ti ti-plus me-1"></i>
                                    Add Medicine

                                </button>

                            </div>

                        </div>


                        <div class="card-body">

                            <div id="medicineContainer">

                                {{-- FIRST MEDICINE ROW --}}

                                <div class="medicine-item border rounded p-3 mb-3">

                                    <div class="row g-3 align-items-end">

                                        <div class="col-lg-5">

                                            <label class="form-label">
                                                Medicine
                                            </label>

                                            <div class="position-relative">

                                                <input type="text"
                                                       class="form-control medicine-search"
                                                       placeholder="Search medicine..."
                                                       autocomplete="off">

                                                <input type="hidden"
                                                       name="medicines[0][medicine_id]"
                                                       class="medicine-id">

                                                <div class="medicine-results list-group position-absolute w-100 shadow-sm"
                                                     style="z-index:1050;">
                                                </div>

                                            </div>

                                            <div class="selected-medicine mt-2"></div>

                                        </div>


                                        <div class="col-lg-2">

                                            <label class="form-label">
                                                Quantity
                                            </label>

                                            <input type="number"
                                                   name="medicines[0][quantity]"
                                                   class="form-control medicine-quantity"
                                                   value="1"
                                                   min="1">

                                        </div>


                                        <div class="col-lg-2">

                                            <label class="form-label">
                                                MRP
                                            </label>

                                            <input type="number"
                                                   name="medicines[0][mrp]"
                                                   class="form-control medicine-mrp"
                                                   step="0.01"
                                                   readonly>

                                        </div>


                                        <div class="col-lg-2">

                                            <label class="form-label">
                                                Price
                                            </label>

                                            <input type="number"
                                                   name="medicines[0][price]"
                                                   class="form-control medicine-price"
                                                   step="0.01"
                                                   readonly>

                                        </div>


                                        <div class="col-lg-1">

                                            <button type="button"
                                                    class="btn btn-light-danger w-100 remove-medicine"
                                                    title="Remove">

                                                <i class="ti ti-trash"></i>

                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            <div id="noMedicineMessage"
                                 class="text-center text-muted py-3 d-none">

                                No medicines added.

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         LAB TESTS
                    ================================================== --}}

                    <div class="card mb-4">

                        <div class="card-header">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <h5 class="card-title mb-1">
                                        <i class="ti ti-test-pipe me-2"></i>
                                        Lab Tests
                                    </h5>

                                    <p class="text-muted small mb-0">
                                        Lab tests are filtered by selected diagnostic centre.
                                    </p>

                                </div>


                                <button type="button"
                                        class="btn btn-primary btn-sm"
                                        id="addLabTest">

                                    <i class="ti ti-plus me-1"></i>
                                    Add Lab Test

                                </button>

                            </div>

                        </div>


                        <div class="card-body">

                            <div id="labTestContainer">

                                {{-- FIRST LAB TEST ROW --}}

                                <div class="lab-test-item border rounded p-3 mb-3">

                                    <div class="row g-3 align-items-end">

                                        <div class="col-lg-6">

                                            <label class="form-label">
                                                Lab Test
                                            </label>

                                            <div class="position-relative">

                                                <input type="text"
                                                       class="form-control lab-test-search"
                                                       placeholder="Search lab test..."
                                                       autocomplete="off">

                                                <input type="hidden"
                                                       name="lab_tests[0][lab_test_id]"
                                                       class="lab-test-id">

                                                <div class="lab-test-results list-group position-absolute w-100 shadow-sm"
                                                     style="z-index:1050;">
                                                </div>

                                            </div>

                                        </div>


                                        <div class="col-lg-2">

                                            <label class="form-label">
                                                MRP
                                            </label>

                                            <input type="number"
                                                   name="lab_tests[0][mrp]"
                                                   class="form-control lab-test-mrp"
                                                   step="0.01"
                                                   readonly>

                                        </div>


                                        <div class="col-lg-2">

                                            <label class="form-label">
                                                Price
                                            </label>

                                            <input type="number"
                                                   name="lab_tests[0][price]"
                                                   class="form-control lab-test-price"
                                                   step="0.01"
                                                   readonly>

                                        </div>


                                        <div class="col-lg-2">

                                            <button type="button"
                                                    class="btn btn-light-danger w-100 remove-lab-test">

                                                <i class="ti ti-trash me-1"></i>
                                                Remove

                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            <div id="noLabTestMessage"
                                 class="text-center text-muted py-3 d-none">

                                No lab tests added.

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         ADDITIONAL NOTES
                    ================================================== --}}

                    <div class="card mb-4">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Additional Information
                            </h5>

                        </div>

                        <div class="card-body">

                            <label class="form-label">
                                Quotation Notes
                            </label>

                            <textarea name="notes"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Enter any additional information for the customer...">{{ old('notes') }}</textarea>

                        </div>

                    </div>


                </div>


                {{-- ====================================================
                     RIGHT SUMMARY
                ===================================================== --}}

                <div class="col-xl-4">

                    <div class="card sticky-top"
                         style="top: 20px;">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Quotation Summary
                            </h5>

                        </div>


                        <div class="card-body">


                            <div class="d-flex justify-content-between mb-3">

                                <span class="text-muted">
                                    Subtotal
                                </span>

                                <strong id="subtotal">
                                    ₹0.00
                                </strong>

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    Delivery Charge
                                </label>

                                <input type="number"
                                       name="delivery_charge"
                                       id="delivery_charge"
                                       class="form-control"
                                       value="{{ old('delivery_charge', 0) }}"
                                       min="0"
                                       step="0.01">

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    Discount
                                </label>

                                <input type="number"
                                       name="discount"
                                       id="discount"
                                       class="form-control"
                                       value="{{ old('discount', 0) }}"
                                       min="0"
                                       step="0.01">

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    Tax
                                </label>

                                <input type="number"
                                       name="tax"
                                       id="tax"
                                       class="form-control"
                                       value="{{ old('tax', 0) }}"
                                       min="0"
                                       step="0.01">

                            </div>


                            <hr>


                            <div class="d-flex justify-content-between align-items-center">

                                <span class="fw-semibold">
                                    Total Amount
                                </span>

                                <span class="fs-20 fw-bold text-primary"
                                      id="total_amount">

                                    ₹0.00

                                </span>

                            </div>


                            <input type="hidden"
                                   name="subtotal"
                                   id="subtotal_input"
                                   value="0">

                            <input type="hidden"
                                   name="total_amount"
                                   id="total_amount_input"
                                   value="0">


                            <div class="d-grid mt-4">

                                <button type="submit"
                                        class="btn btn-primary btn-lg"
                                        id="submitQuotation">

                                    <i class="ti ti-send me-1"></i>
                                    Create Quotation

                                </button>

                            </div>


                            <div class="text-center mt-3">

                                <small class="text-muted">

                                    The quotation will be created as
                                    <strong>Draft</strong>.

                                    You can review and send it to the customer.

                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- ================================================================
     JAVASCRIPT
================================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    'use strict';


    /* ================================================================
       CONFIGURATION
    ================================================================= */

    let medicineIndex =
        document.querySelectorAll('.medicine-item').length;

    let labTestIndex =
        document.querySelectorAll('.lab-test-item').length;

    let medicineSearchTimer = null;

    let labTestSearchTimer = null;


    const providerType =
        document.getElementById('provider_type');

    const hospitalSelect =
        document.getElementById('hospital_id');

    const diagnosticSelect =
        document.getElementById('diagnostic_id');


    /* ================================================================
       PROVIDER TYPE
    ================================================================= */

    providerType?.addEventListener('change', function () {

        const hospitalBox =
            document.querySelector('.provider-hospital');

        const diagnosticBox =
            document.querySelector('.provider-diagnostic');


        hospitalBox?.classList.add('d-none');
        diagnosticBox?.classList.add('d-none');


        if (this.value === 'hospital') {

            hospitalBox?.classList.remove('d-none');

            if (diagnosticSelect) {
                diagnosticSelect.value = '';
            }

        }


        if (this.value === 'diagnostic') {

            diagnosticBox?.classList.remove('d-none');

            if (hospitalSelect) {
                hospitalSelect.value = '';
            }

        }

    });


    /* ================================================================
       GLOBAL TOTAL FUNCTION
    ================================================================= */

    window.calculateTotal = function () {

        let subtotal = 0;


        /* ---------------- Medicines ---------------- */

        document
            .querySelectorAll('.medicine-item')
            .forEach(function (item) {

                const price =
                    parseFloat(
                        item.querySelector('.medicine-price')?.value
                    ) || 0;

                const quantity =
                    parseInt(
                        item.querySelector('.medicine-quantity')?.value
                    ) || 0;

                subtotal +=
                    price * quantity;

            });


        /* ---------------- Lab Tests ---------------- */

        document
            .querySelectorAll('.lab-test-item')
            .forEach(function (item) {

                const price =
                    parseFloat(
                        item.querySelector('.lab-test-price')?.value
                    ) || 0;

                subtotal += price;

            });


        /* ---------------- Charges ---------------- */

        const delivery =
            parseFloat(
                document.getElementById('delivery_charge')?.value
            ) || 0;

        const discount =
            parseFloat(
                document.getElementById('discount')?.value
            ) || 0;

        const tax =
            parseFloat(
                document.getElementById('tax')?.value
            ) || 0;


        let total =
            subtotal +
            delivery +
            tax -
            discount;


        if (total < 0) {
            total = 0;
        }


        /* ---------------- Update UI ---------------- */

        const subtotalInput =
            document.getElementById('subtotal_input');

        const totalInput =
            document.getElementById('total_amount_input');


        if (subtotalInput) {
            subtotalInput.value =
                subtotal.toFixed(2);
        }


        if (totalInput) {
            totalInput.value =
                total.toFixed(2);
        }


        document.getElementById('subtotal')
            ?.replaceChildren(
                document.createTextNode(
                    '₹' + subtotal.toFixed(2)
                )
            );


        document.getElementById('total_amount')
            ?.replaceChildren(
                document.createTextNode(
                    '₹' + total.toFixed(2)
                )
            );

    };


    /* ================================================================
       HOSPITAL CHANGE
    ================================================================= */

    hospitalSelect?.addEventListener('change', function () {

        clearMedicineRows();

        calculateTotal();

    });


    /* ================================================================
       DIAGNOSTIC CHANGE
    ================================================================= */

    diagnosticSelect?.addEventListener('change', function () {

        clearLabTestRows();

        calculateTotal();

    });


    /* ================================================================
       CLEAR MEDICINES
    ================================================================= */

    function clearMedicineRows() {

        document
            .querySelectorAll('.medicine-item')
            .forEach(function (item, index) {

                if (index === 0) {

                    resetMedicineRow(item);

                } else {

                    item.remove();

                }

            });

        medicineIndex = 1;

    }


    /* ================================================================
       CLEAR LAB TESTS
    ================================================================= */

    function clearLabTestRows() {

        document
            .querySelectorAll('.lab-test-item')
            .forEach(function (item, index) {

                if (index === 0) {

                    resetLabTestRow(item);

                } else {

                    item.remove();

                }

            });

        labTestIndex = 1;

    }


    /* ================================================================
       RESET MEDICINE ROW
    ================================================================= */

    function resetMedicineRow(item) {

        item.querySelector('.medicine-search').value = '';

        item.querySelector('.medicine-id').value = '';

        item.querySelector('.medicine-mrp').value = '';

        item.querySelector('.medicine-price').value = '';

        item.querySelector('.medicine-quantity').value = 1;

        item.querySelector('.medicine-results').innerHTML = '';

        item.querySelector('.selected-medicine').innerHTML = '';

    }


    /* ================================================================
       RESET LAB TEST ROW
    ================================================================= */

    function resetLabTestRow(item) {

        item.querySelector('.lab-test-search').value = '';

        item.querySelector('.lab-test-id').value = '';

        item.querySelector('.lab-test-mrp').value = '';

        item.querySelector('.lab-test-price').value = '';

        item.querySelector('.lab-test-results').innerHTML = '';

    }


    /* ================================================================
       MEDICINE SEARCH
    ================================================================= */

    document.addEventListener('input', function (event) {

        if (
            !event.target.classList.contains(
                'medicine-search'
            )
        ) {
            return;
        }


        const input =
            event.target;

        const row =
            input.closest('.medicine-item');

        const results =
            row?.querySelector('.medicine-results');


        const hospitalId =
            hospitalSelect?.value;


        const search =
            input.value.trim();


        if (!hospitalId) {

            results.innerHTML = `
                <div class="list-group-item text-danger">
                    <i class="ti ti-alert-circle me-1"></i>
                    Please select a hospital first.
                </div>
            `;

            return;

        }


        if (search.length < 2) {

            results.innerHTML = '';

            return;

        }


        clearTimeout(medicineSearchTimer);


        medicineSearchTimer =
            setTimeout(function () {

                fetchMedicines(
                    row,
                    search,
                    hospitalId
                );

            }, 300);

    });


    /* ================================================================
       FETCH MEDICINES
    ================================================================= */

    function fetchMedicines(
        row,
        search,
        hospitalId
    ) {

        const results =
            row.querySelector(
                '.medicine-results'
            );


        results.innerHTML = `
            <div class="list-group-item">
                <span class="spinner-border spinner-border-sm me-2"></span>
                Searching...
            </div>
        `;


        const url =
            "{{ route('admin.prescription-quotations.search.medicines') }}"
            + '?hospital_id='
            + encodeURIComponent(hospitalId)
            + '&search='
            + encodeURIComponent(search);


        fetch(url, {

            method: 'GET',

            headers: {

                'Accept':
                    'application/json',

                'X-Requested-With':
                    'XMLHttpRequest'

            }

        })
        .then(function (response) {

            if (!response.ok) {
                throw new Error(
                    'Unable to fetch medicines.'
                );
            }

            return response.json();

        })
        .then(function (response) {

            results.innerHTML = '';


            if (
                !response.success ||
                !response.data ||
                response.data.length === 0
            ) {

                results.innerHTML = `
                    <div class="list-group-item text-muted">
                        No medicines found for this hospital.
                    </div>
                `;

                return;

            }


            response.data.forEach(function (medicine) {

                const button =
                    document.createElement('button');


                button.type =
                    'button';

                button.className =
                    'list-group-item list-group-item-action';


                button.innerHTML = `

                    <div class="d-flex justify-content-between gap-3">

                        <div>

                            <div class="fw-semibold">

                                ${escapeHtml(
                                    medicine.medicine_name
                                )}

                            </div>

                            <small class="text-muted">

                                ${escapeHtml(
                                    medicine.generic_name || ''
                                )}

                                ${
                                    medicine.strength
                                        ? ' · ' +
                                          escapeHtml(
                                              medicine.strength
                                          )
                                        : ''
                                }

                                ${
                                    medicine.pack_size
                                        ? ' · ' +
                                          escapeHtml(
                                              medicine.pack_size
                                          )
                                        : ''
                                }

                            </small>

                            <div>

                                <small class="text-muted">

                                    Code:
                                    ${escapeHtml(
                                        medicine.medicine_code || '-'
                                    )}

                                </small>

                            </div>

                        </div>


                        <div class="text-end text-nowrap">

                            <div class="small text-muted">
                                MRP ₹${formatMoney(medicine.mrp)}
                            </div>

                            <div class="fw-semibold text-success">
                                ₹${formatMoney(medicine.selling_price)}
                            </div>

                        </div>

                    </div>

                `;


                button.addEventListener(
                    'click',
                    function () {

                        selectMedicine(
                            row,
                            medicine
                        );

                    }
                );


                results.appendChild(
                    button
                );

            });

        })
        .catch(function (error) {

            console.error(error);

            results.innerHTML = `
                <div class="list-group-item text-danger">
                    Unable to load medicines.
                </div>
            `;

        });

    }


    /* ================================================================
       SELECT MEDICINE
    ================================================================= */

    function selectMedicine(
        row,
        medicine
    ) {

        row.querySelector('.medicine-search').value =
            medicine.medicine_name || '';

        row.querySelector('.medicine-id').value =
            medicine.id;

        row.querySelector('.medicine-mrp').value =
            medicine.mrp || 0;

        row.querySelector('.medicine-price').value =
            medicine.selling_price || 0;

        row.querySelector('.medicine-results').innerHTML = '';


        row.querySelector('.selected-medicine').innerHTML = `

            <div class="alert alert-light border mb-0 py-2">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <strong>
                            ${escapeHtml(
                                medicine.medicine_name
                            )}
                        </strong>

                        <div class="small text-muted">

                            ${escapeHtml(
                                medicine.generic_name || ''
                            )}

                            ${
                                medicine.strength
                                    ? ' · ' +
                                      escapeHtml(
                                          medicine.strength
                                      )
                                    : ''
                            }

                        </div>

                    </div>

                    <span class="badge bg-soft-success text-success">

                        ₹${formatMoney(
                            medicine.selling_price
                        )}

                    </span>

                </div>

            </div>

        `;


        calculateTotal();

    }


    /* ================================================================
       ADD MEDICINE
    ================================================================= */

    document.getElementById('addMedicine')
        ?.addEventListener('click', function () {

            const container =
                document.getElementById(
                    'medicineContainer'
                );


            const index =
                medicineIndex++;


            container.insertAdjacentHTML(
                'beforeend',
                medicineTemplate(index)
            );

        });


    /* ================================================================
       MEDICINE TEMPLATE
    ================================================================= */

    function medicineTemplate(index) {

        return `

            <div class="medicine-item border rounded p-3 mb-3">

                <div class="row g-3 align-items-end">

                    <div class="col-lg-5">

                        <label class="form-label">
                            Medicine
                        </label>

                        <div class="position-relative">

                            <input type="text"
                                   class="form-control medicine-search"
                                   placeholder="Search medicine..."
                                   autocomplete="off">

                            <input type="hidden"
                                   name="medicines[${index}][medicine_id]"
                                   class="medicine-id">

                            <div class="medicine-results list-group position-absolute w-100 shadow-sm"
                                 style="z-index:1050;">
                            </div>

                        </div>

                        <div class="selected-medicine mt-2"></div>

                    </div>


                    <div class="col-lg-2">

                        <label class="form-label">
                            Quantity
                        </label>

                        <input type="number"
                               name="medicines[${index}][quantity]"
                               class="form-control medicine-quantity"
                               value="1"
                               min="1">

                    </div>


                    <div class="col-lg-2">

                        <label class="form-label">
                            MRP
                        </label>

                        <input type="number"
                               name="medicines[${index}][mrp]"
                               class="form-control medicine-mrp"
                               step="0.01"
                               readonly>

                    </div>


                    <div class="col-lg-2">

                        <label class="form-label">
                            Price
                        </label>

                        <input type="number"
                               name="medicines[${index}][price]"
                               class="form-control medicine-price"
                               step="0.01"
                               readonly>

                    </div>


                    <div class="col-lg-1">

                        <button type="button"
                                class="btn btn-light-danger w-100 remove-medicine">

                            <i class="ti ti-trash"></i>

                        </button>

                    </div>

                </div>

            </div>

        `;

    }


    /* ================================================================
       REMOVE MEDICINE
    ================================================================= */

    document.addEventListener('click', function (event) {

        const button =
            event.target.closest(
                '.remove-medicine'
            );


        if (!button) {
            return;
        }


        const row =
            button.closest(
                '.medicine-item'
            );


        row?.remove();


        calculateTotal();

    });


    /* ================================================================
       LAB TEST SEARCH
    ================================================================= */

    document.addEventListener('input', function (event) {

        if (
            !event.target.classList.contains(
                'lab-test-search'
            )
        ) {
            return;
        }


        const input =
            event.target;

        const row =
            input.closest('.lab-test-item');

        const results =
            row?.querySelector(
                '.lab-test-results'
            );


        const diagnosticId =
            diagnosticSelect?.value;


        const search =
            input.value.trim();


        if (!diagnosticId) {

            results.innerHTML = `
                <div class="list-group-item text-danger">
                    <i class="ti ti-alert-circle me-1"></i>
                    Please select a diagnostic centre first.
                </div>
            `;

            return;

        }


        if (search.length < 2) {

            results.innerHTML = '';

            return;

        }


        clearTimeout(
            labTestSearchTimer
        );


        labTestSearchTimer =
            setTimeout(function () {

                fetchLabTests(
                    row,
                    search,
                    diagnosticId
                );

            }, 300);

    });


    /* ================================================================
       FETCH LAB TESTS
    ================================================================= */

    function fetchLabTests(
        row,
        search,
        diagnosticId
    ) {

        const results =
            row.querySelector(
                '.lab-test-results'
            );


        results.innerHTML = `
            <div class="list-group-item">
                <span class="spinner-border spinner-border-sm me-2"></span>
                Searching...
            </div>
        `;


        const url =
            "{{ route('admin.prescription-quotations.search.lab-tests') }}"
            + '?diagnostic_id='
            + encodeURIComponent(diagnosticId)
            + '&search='
            + encodeURIComponent(search);


        fetch(url, {

            headers: {

                'Accept':
                    'application/json',

                'X-Requested-With':
                    'XMLHttpRequest'

            }

        })
        .then(function (response) {

            if (!response.ok) {
                throw new Error(
                    'Unable to fetch lab tests.'
                );
            }

            return response.json();

        })
        .then(function (response) {

            results.innerHTML = '';


            if (
                !response.success ||
                !response.data ||
                response.data.length === 0
            ) {

                results.innerHTML = `
                    <div class="list-group-item text-muted">
                        No lab tests found for this diagnostic centre.
                    </div>
                `;

                return;

            }


            response.data.forEach(function (test) {

                const button =
                    document.createElement('button');


                button.type =
                    'button';

                button.className =
                    'list-group-item list-group-item-action';


                const name =
                    test.test_name ||
                    test.name ||
                    '';


                const price =
                    test.offer_price ??
                    test.price ??
                    0;


                button.innerHTML = `

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="fw-semibold">
                                ${escapeHtml(name)}
                            </div>

                            <small class="text-muted">

                                Code:
                                ${escapeHtml(
                                    test.test_code || '-'
                                )}

                            </small>

                        </div>

                        <div class="fw-semibold text-success">

                            ₹${formatMoney(price)}

                        </div>

                    </div>

                `;


                button.addEventListener(
                    'click',
                    function () {

                        selectLabTest(
                            row,
                            test
                        );

                    }
                );


                results.appendChild(
                    button
                );

            });

        })
        .catch(function (error) {

            console.error(error);

            results.innerHTML = `
                <div class="list-group-item text-danger">
                    Unable to load lab tests.
                </div>
            `;

        });

    }


    /* ================================================================
       SELECT LAB TEST
    ================================================================= */

    function selectLabTest(
        row,
        test
    ) {

        const name =
            test.test_name ||
            test.name ||
            '';


        const price =
            test.offer_price ??
            test.price ??
            0;


        const mrp =
            test.price ??
            0;


        row.querySelector('.lab-test-search').value =
            name;


        row.querySelector('.lab-test-id').value =
            test.lab_test_id ??
            test.id;


        row.querySelector('.lab-test-mrp').value =
            mrp;


        row.querySelector('.lab-test-price').value =
            price;


        row.querySelector('.lab-test-results').innerHTML =
            '';


        calculateTotal();

    }


    /* ================================================================
       ADD LAB TEST
    ================================================================= */

    document.getElementById('addLabTest')
        ?.addEventListener('click', function () {

            const container =
                document.getElementById(
                    'labTestContainer'
                );


            const index =
                labTestIndex++;


            container.insertAdjacentHTML(
                'beforeend',
                labTestTemplate(index)
            );

        });


    /* ================================================================
       LAB TEST TEMPLATE
    ================================================================= */

    function labTestTemplate(index) {

        return `

            <div class="lab-test-item border rounded p-3 mb-3">

                <div class="row g-3 align-items-end">

                    <div class="col-lg-6">

                        <label class="form-label">
                            Lab Test
                        </label>

                        <div class="position-relative">

                            <input type="text"
                                   class="form-control lab-test-search"
                                   placeholder="Search lab test..."
                                   autocomplete="off">

                            <input type="hidden"
                                   name="lab_tests[${index}][lab_test_id]"
                                   class="lab-test-id">

                            <div class="lab-test-results list-group position-absolute w-100 shadow-sm"
                                 style="z-index:1050;">
                            </div>

                        </div>

                    </div>


                    <div class="col-lg-2">

                        <label class="form-label">
                            MRP
                        </label>

                        <input type="number"
                               name="lab_tests[${index}][mrp]"
                               class="form-control lab-test-mrp"
                               step="0.01"
                               readonly>

                    </div>


                    <div class="col-lg-2">

                        <label class="form-label">
                            Price
                        </label>

                        <input type="number"
                               name="lab_tests[${index}][price]"
                               class="form-control lab-test-price"
                               step="0.01"
                               readonly>

                    </div>


                    <div class="col-lg-2">

                        <button type="button"
                                class="btn btn-light-danger w-100 remove-lab-test">

                            <i class="ti ti-trash me-1"></i>
                            Remove

                        </button>

                    </div>

                </div>

            </div>

        `;

    }


    /* ================================================================
       REMOVE LAB TEST
    ================================================================= */

    document.addEventListener('click', function (event) {

        const button =
            event.target.closest(
                '.remove-lab-test'
            );


        if (!button) {
            return;
        }


        const row =
            button.closest(
                '.lab-test-item'
            );


        row?.remove();


        calculateTotal();

    });


    /* ================================================================
       TOTAL INPUT EVENTS
    ================================================================= */

    document.addEventListener('input', function (event) {

        if (

            event.target.classList.contains(
                'medicine-quantity'
            )

            ||

            event.target.classList.contains(
                'medicine-price'
            )

            ||

            event.target.classList.contains(
                'lab-test-price'
            )

            ||

            event.target.id ===
            'delivery_charge'

            ||

            event.target.id ===
            'discount'

            ||

            event.target.id ===
            'tax'

        ) {

            calculateTotal();

        }

    });


    /* ================================================================
       FORM VALIDATION
    ================================================================= */

    document.getElementById('quotationForm')
        ?.addEventListener('submit', function (event) {

            const type =
                document.querySelector(
                    '[name="quotation_type"]'
                )?.value;


            const hospitalId =
                hospitalSelect?.value;


            const diagnosticId =
                diagnosticSelect?.value;


            const medicineRows =
                document.querySelectorAll(
                    '.medicine-item'
                );


            const labTestRows =
                document.querySelectorAll(
                    '.lab-test-item'
                );


            let hasMedicine = false;

            let hasLabTest = false;


            medicineRows.forEach(function (row) {

                if (
                    row.querySelector(
                        '.medicine-id'
                    )?.value
                ) {

                    hasMedicine = true;

                }

            });


            labTestRows.forEach(function (row) {

                if (
                    row.querySelector(
                        '.lab-test-id'
                    )?.value
                ) {

                    hasLabTest = true;

                }

            });


            if (
                type === 'medicines' &&
                !hospitalId
            ) {

                event.preventDefault();

                alert(
                    'Please select a hospital for the medicine quotation.'
                );

                return;

            }


            if (
                type === 'lab_tests' &&
                !diagnosticId
            ) {

                event.preventDefault();

                alert(
                    'Please select a diagnostic centre for the lab test quotation.'
                );

                return;

            }


            if (
                type === 'both' &&
                (!hospitalId || !diagnosticId)
            ) {

                event.preventDefault();

                alert(
                    'Please select both hospital and diagnostic centre.'
                );

                return;

            }


            if (
                type === 'medicines' &&
                !hasMedicine
            ) {

                event.preventDefault();

                alert(
                    'Please add at least one medicine.'
                );

                return;

            }


            if (
                type === 'lab_tests' &&
                !hasLabTest
            ) {

                event.preventDefault();

                alert(
                    'Please add at least one lab test.'
                );

                return;

            }


            if (
                type === 'both' &&
                !hasMedicine &&
                !hasLabTest
            ) {

                event.preventDefault();

                alert(
                    'Please add at least one medicine or lab test.'
                );

                return;

            }


            calculateTotal();

        });


    /* ================================================================
       HELPERS
    ================================================================= */

    function formatMoney(value) {

        const number =
            parseFloat(value) || 0;

        return number.toFixed(2);

    }


    function escapeHtml(value) {

        if (
            value === null ||
            value === undefined
        ) {
            return '';
        }


        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }


    /* ================================================================
       INITIAL
    ================================================================= */

    calculateTotal();

});

</script>

@endsection