@extends('layout.mainlayout')

@section('title', 'Create Coupon')

@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="page-title">
                <h4>Create Coupon / Offer</h4>
                <h6>Create a new coupon or customer offer</h6>
            </div>

            <div class="page-btn">
                <a href="{{ route('admin.coupons.index') }}"
                   class="btn btn-light">
                    <i class="ti ti-arrow-left me-1"></i>
                    Back
                </a>
            </div>
        </div>


        {{-- Validation Errors --}}
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


        <form method="POST"
              action="{{ route('admin.coupons.store') }}"
              id="couponForm">

            @csrf

            <div class="row">

                {{-- LEFT SIDE --}}
                <div class="col-lg-8">

                    {{-- Basic Information --}}
                    <div class="card mb-3">

                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Coupon Information
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="row g-3">

                                {{-- Coupon Code --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Coupon Code
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">

                                        <input type="text"
                                               name="code"
                                               id="couponCode"
                                               class="form-control text-uppercase"
                                               placeholder="WELCOME100"
                                               value="{{ old('code') }}"
                                               maxlength="50"
                                               required>

                                        <button type="button"
                                                class="btn btn-light"
                                                id="generateCode">
                                            Generate
                                        </button>

                                    </div>

                                    <small class="text-muted">
                                        Customers will enter this code while booking.
                                    </small>

                                </div>


                                {{-- Coupon Type --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Type
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="coupon_type"
                                            id="couponType"
                                            class="form-select"
                                            required>

                                        <option value="">
                                            Select Type
                                        </option>

                                        <option value="coupon"
                                            {{ old('coupon_type') == 'coupon' ? 'selected' : '' }}>
                                            Coupon
                                        </option>

                                        <option value="offer"
                                            {{ old('coupon_type') == 'offer' ? 'selected' : '' }}>
                                            Offer
                                        </option>

                                    </select>

                                </div>


                                {{-- Title --}}
                                <div class="col-md-12">

                                    <label class="form-label">
                                        Title
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           name="title"
                                           class="form-control"
                                           placeholder="Welcome Appointment Offer"
                                           value="{{ old('title') }}"
                                           required>

                                </div>


                                {{-- Description --}}
                                <div class="col-md-12">

                                    <label class="form-label">
                                        Description
                                    </label>

                                    <textarea name="description"
                                              class="form-control"
                                              rows="3"
                                              placeholder="Enter coupon description">{{ old('description') }}</textarea>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Discount --}}
                    <div class="card mb-3">

                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Discount
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="row g-3">

                                {{-- Applicable To --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Applicable To
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="applicable_to"
                                            id="applicableTo"
                                            class="form-select"
                                            required>

                                        <option value="">
                                            Select
                                        </option>

                                        <option value="all"
                                            {{ old('applicable_to') == 'all' ? 'selected' : '' }}>
                                            All Services
                                        </option>

                                        <option value="appointment"
                                            {{ old('applicable_to') == 'appointment' ? 'selected' : '' }}>
                                            Doctor Appointment
                                        </option>

                                        <option value="medicine"
                                            {{ old('applicable_to') == 'medicine' ? 'selected' : '' }}>
                                            Medicine
                                        </option>

                                    </select>

                                </div>


                                {{-- Discount Type --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Discount Type
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="discount_type"
                                            id="discountType"
                                            class="form-select"
                                            required>

                                        <option value="">
                                            Select Discount Type
                                        </option>

                                        <option value="percentage"
                                            {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>
                                            Percentage (%)
                                        </option>

                                        <option value="fixed"
                                            {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>
                                            Fixed Amount (₹)
                                        </option>

                                        <option value="free"
                                            {{ old('discount_type') == 'free' ? 'selected' : '' }}>
                                            Free
                                        </option>

                                    </select>

                                </div>


                                {{-- Discount Value --}}
                                <div class="col-md-6"
                                     id="discountValueWrapper">

                                    <label class="form-label">
                                        Discount Value
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text"
                                              id="discountPrefix">
                                            %
                                        </span>

                                        <input type="number"
                                               name="discount_value"
                                               id="discountValue"
                                               class="form-control"
                                               value="{{ old('discount_value') }}"
                                               min="0"
                                               step="0.01"
                                               placeholder="20">

                                    </div>

                                </div>


                                {{-- Maximum Discount --}}
                                <div class="col-md-6"
                                     id="maxDiscountWrapper">

                                    <label class="form-label">
                                        Maximum Discount
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            ₹
                                        </span>

                                        <input type="number"
                                               name="max_discount"
                                               class="form-control"
                                               value="{{ old('max_discount') }}"
                                               min="0"
                                               step="0.01"
                                               placeholder="200">

                                    </div>

                                    <small class="text-muted">
                                        Mainly used for percentage discounts.
                                    </small>

                                </div>


                                {{-- Minimum Amount --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Minimum Amount
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            ₹
                                        </span>

                                        <input type="number"
                                               name="min_order_amount"
                                               class="form-control"
                                               value="{{ old('min_order_amount', 0) }}"
                                               min="0"
                                               step="0.01"
                                               placeholder="0">

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Customer Eligibility --}}
                    <div class="card mb-3">

                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Customer Eligibility
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="row g-3">

                                {{-- New Customer --}}
                                <div class="col-md-6">

                                    <div class="border rounded p-3 h-100">

                                        <div class="form-check form-switch">

                                            <input type="checkbox"
                                                   name="new_customer_only"
                                                   value="1"
                                                   class="form-check-input"
                                                   id="newCustomerOnly"
                                                   {{ old('new_customer_only') ? 'checked' : '' }}>

                                            <label class="form-check-label fw-semibold"
                                                   for="newCustomerOnly">

                                                New Customer Only

                                            </label>

                                        </div>

                                        <small class="text-muted">
                                            Only customers who are eligible as new customers can use this offer.
                                        </small>

                                    </div>

                                </div>


                                {{-- First Appointment --}}
                                <div class="col-md-6">

                                    <div class="border rounded p-3 h-100">

                                        <div class="form-check form-switch">

                                            <input type="checkbox"
                                                   name="first_appointment_only"
                                                   value="1"
                                                   class="form-check-input"
                                                   id="firstAppointmentOnly"
                                                   {{ old('first_appointment_only') ? 'checked' : '' }}>

                                            <label class="form-check-label fw-semibold"
                                                   for="firstAppointmentOnly">

                                                First Appointment Only

                                            </label>

                                        </div>

                                        <small class="text-muted">
                                            Offer can only be used for the customer's first appointment.
                                        </small>

                                    </div>

                                </div>


                                {{-- Free Appointment --}}
                                <div class="col-md-6">

                                    <div class="border rounded p-3 h-100">

                                        <div class="form-check form-switch">

                                            <input type="checkbox"
                                                   name="free_appointment"
                                                   value="1"
                                                   class="form-check-input"
                                                   id="freeAppointment"
                                                   {{ old('free_appointment') ? 'checked' : '' }}>

                                            <label class="form-check-label fw-semibold"
                                                   for="freeAppointment">

                                                Free Appointment

                                            </label>

                                        </div>

                                        <small class="text-muted">
                                            Makes the eligible doctor appointment completely free.
                                        </small>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Usage --}}
                    <div class="card mb-3">

                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Usage Limits
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="row g-3">

                                {{-- Total Usage --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Total Usage Limit
                                    </label>

                                    <input type="number"
                                           name="usage_limit"
                                           class="form-control"
                                           value="{{ old('usage_limit') }}"
                                           min="1"
                                           placeholder="Unlimited">

                                    <small class="text-muted">
                                        Leave empty for unlimited usage.
                                    </small>

                                </div>


                                {{-- Per Customer --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Usage Per Customer
                                    </label>

                                    <input type="number"
                                           name="usage_per_customer"
                                           class="form-control"
                                           value="{{ old('usage_per_customer', 1) }}"
                                           min="1"
                                           required>

                                    <small class="text-muted">
                                        Example: 1 means one use per customer.
                                    </small>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Validity --}}
                    <div class="card mb-3">

                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Coupon Validity
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="row g-3">

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Starts At
                                    </label>

                                    <input type="datetime-local"
                                           name="starts_at"
                                           class="form-control"
                                           value="{{ old('starts_at') }}">

                                </div>


                                <div class="col-md-6">

                                    <label class="form-label">
                                        Expires At
                                    </label>

                                    <input type="datetime-local"
                                           name="expires_at"
                                           class="form-control"
                                           value="{{ old('expires_at') }}">

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- RIGHT SIDE --}}
                <div class="col-lg-4">

                    {{-- Preview --}}
                    <div class="card mb-3">

                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Coupon Preview
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="coupon-preview border rounded-3 p-4">

                                <div class="text-center">

                                    <div class="mb-3">

                                        <span class="avatar avatar-xl bg-primary rounded-circle">

                                            <i class="ti ti-ticket fs-28 text-white"></i>

                                        </span>

                                    </div>


                                    <h5 id="previewTitle">
                                        Your Offer
                                    </h5>


                                    <div class="my-3">

                                        <span class="badge bg-light-primary text-primary fs-16 px-3 py-2"
                                              id="previewCode">

                                            COUPON

                                        </span>

                                    </div>


                                    <h3 class="text-success mb-2"
                                        id="previewDiscount">

                                        Discount

                                    </h3>


                                    <p class="text-muted mb-0"
                                       id="previewApplicable">

                                        Applicable to selected services

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="card mb-3">

                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Status
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="form-check form-switch">

                                <input type="checkbox"
                                       name="status"
                                       value="1"
                                       class="form-check-input"
                                       id="couponStatus"
                                       {{ old('status', 1) ? 'checked' : '' }}>

                                <label class="form-check-label fw-semibold"
                                       for="couponStatus">

                                    Active

                                </label>

                            </div>

                            <small class="text-muted">
                                Inactive coupons cannot be used by customers.
                            </small>

                        </div>

                    </div>


                    {{-- Information --}}
                    <div class="card">

                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Offer Rules
                            </h5>
                        </div>

                        <div class="card-body">

                            <ul class="mb-0 ps-3 text-muted">

                                <li class="mb-2">
                                    Coupon code must be unique.
                                </li>

                                <li class="mb-2">
                                    New Customer Only restricts usage to eligible new customers.
                                </li>

                                <li class="mb-2">
                                    First Appointment Only restricts the offer to the first appointment.
                                </li>

                                <li class="mb-2">
                                    Free Appointment makes the appointment amount ₹0 after discount.
                                </li>

                                <li>
                                    Usage limits can restrict total and per-customer usage.
                                </li>

                            </ul>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Submit --}}
            <div class="card">

                <div class="card-body">

                    <div class="d-flex justify-content-end gap-2">

                        <a href="{{ route('admin.coupons.index') }}"
                           class="btn btn-light">

                            Cancel

                        </a>

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="ti ti-device-floppy me-1"></i>
                            Create Coupon

                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>
</div>


{{-- JavaScript --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const codeInput =
        document.getElementById('couponCode');

    const titleInput =
        document.querySelector('input[name="title"]');

    const discountType =
        document.getElementById('discountType');

    const discountValue =
        document.getElementById('discountValue');

    const discountPrefix =
        document.getElementById('discountPrefix');

    const discountValueWrapper =
        document.getElementById('discountValueWrapper');

    const maxDiscountWrapper =
        document.getElementById('maxDiscountWrapper');

    const applicableTo =
        document.getElementById('applicableTo');

    const freeAppointment =
        document.getElementById('freeAppointment');

    const previewCode =
        document.getElementById('previewCode');

    const previewTitle =
        document.getElementById('previewTitle');

    const previewDiscount =
        document.getElementById('previewDiscount');

    const previewApplicable =
        document.getElementById('previewApplicable');


    /*
    |--------------------------------------------------------------------------
    | Generate Coupon Code
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('generateCode')
        .addEventListener('click', function () {

            const random =
                Math.random()
                    .toString(36)
                    .substring(2, 8)
                    .toUpperCase();

            codeInput.value =
                'WELCOME' + random;

            updatePreview();

        });


    /*
    |--------------------------------------------------------------------------
    | Uppercase Code
    |--------------------------------------------------------------------------
    */

    codeInput.addEventListener(
        'input',
        function () {

            this.value =
                this.value
                    .toUpperCase()
                    .replace(/\s/g, '');

            updatePreview();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Title Preview
    |--------------------------------------------------------------------------
    */

    titleInput.addEventListener(
        'input',
        updatePreview
    );


    /*
    |--------------------------------------------------------------------------
    | Discount Type
    |--------------------------------------------------------------------------
    */

    discountType.addEventListener(
        'change',
        function () {

            updateDiscountFields();

            updatePreview();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Discount Value
    |--------------------------------------------------------------------------
    */

    discountValue.addEventListener(
        'input',
        updatePreview
    );


    /*
    |--------------------------------------------------------------------------
    | Applicable To
    |--------------------------------------------------------------------------
    */

    applicableTo.addEventListener(
        'change',
        updatePreview
    );


    /*
    |--------------------------------------------------------------------------
    | Free Appointment
    |--------------------------------------------------------------------------
    */

    freeAppointment.addEventListener(
        'change',
        function () {

            if (this.checked) {

                discountType.value =
                    'free';

                applicableTo.value =
                    'appointment';

                discountValue.value =
                    '100';

                discountType.dispatchEvent(
                    new Event('change')
                );

                applicableTo.dispatchEvent(
                    new Event('change')
                );

            }

            updatePreview();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Update Discount Fields
    |--------------------------------------------------------------------------
    */

    function updateDiscountFields()
    {
        const type =
            discountType.value;


        if (type === 'free') {

            discountValueWrapper.style.display =
                'none';

            maxDiscountWrapper.style.display =
                'none';

            discountValue.value =
                '100';

        } else {

            discountValueWrapper.style.display =
                'block';

            if (type === 'percentage') {

                discountPrefix.innerText =
                    '%';

                maxDiscountWrapper.style.display =
                    'block';

            } else {

                discountPrefix.innerText =
                    '₹';

                maxDiscountWrapper.style.display =
                    'none';

            }

        }
    }


    /*
    |--------------------------------------------------------------------------
    | Update Preview
    |--------------------------------------------------------------------------
    */

    function updatePreview()
    {
        const code =
            codeInput.value || 'COUPON';

        const title =
            titleInput.value || 'Your Offer';

        const type =
            discountType.value;

        const value =
            discountValue.value;


        previewCode.innerText =
            code;

        previewTitle.innerText =
            title;


        if (
            type === 'free' ||
            freeAppointment.checked
        ) {

            previewDiscount.innerText =
                'FREE APPOINTMENT';

        } else if (
            type === 'percentage'
        ) {

            previewDiscount.innerText =
                (value || 0) + '% OFF';

        } else if (
            type === 'fixed'
        ) {

            previewDiscount.innerText =
                '₹' + (value || 0) + ' OFF';

        } else {

            previewDiscount.innerText =
                'Discount';

        }


        let applicableText =
            'Applicable to selected services';


        if (applicableTo.value === 'appointment') {

            applicableText =
                'Doctor Appointment';

        } else if (
            applicableTo.value === 'medicine'
        ) {

            applicableText =
                'Medicine';

        } else if (
            applicableTo.value === 'all'
        ) {

            applicableText =
                'All Services';

        }


        previewApplicable.innerText =
            applicableText;
    }


    /*
    |--------------------------------------------------------------------------
    | Initial State
    |--------------------------------------------------------------------------
    */

    updateDiscountFields();

    updatePreview();

});

</script>

@endsection