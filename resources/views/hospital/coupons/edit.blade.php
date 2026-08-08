@extends('layout.mainlayout')

@section('title', 'Edit Coupon')

@section('content')

<div class="page-wrapper">
    <div class="content">

        <div class="page-header">
            <div class="page-title">
                <h4>Edit Coupon / Offer</h4>
                <h6>Update hospital coupon details</h6>
            </div>

            <div class="page-btn">
                <a href="{{ route('hospital.coupons.index') }}"
                   class="btn btn-light">
                    <i class="ti ti-arrow-left me-1"></i>
                    Back
                </a>
            </div>
        </div>


        {{-- Errors --}}
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
              action="{{ route('hospital.coupons.update', $coupon->id) }}">

            @csrf
            @method('PUT')

            <div class="row">

                {{-- LEFT --}}
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

                                {{-- Code --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Coupon Code
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           name="code"
                                           id="couponCode"
                                           class="form-control text-uppercase"
                                           value="{{ old('code', $coupon->code) }}"
                                           maxlength="50"
                                           required>

                                </div>


                                {{-- Type --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Type
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="coupon_type"
                                            class="form-select"
                                            required>

                                        <option value="coupon"
                                            {{ old('coupon_type', $coupon->coupon_type) == 'coupon' ? 'selected' : '' }}>
                                            Coupon
                                        </option>

                                        <option value="offer"
                                            {{ old('coupon_type', $coupon->coupon_type) == 'offer' ? 'selected' : '' }}>
                                            Offer
                                        </option>

                                    </select>

                                </div>


                                {{-- Title --}}
                                <div class="col-md-12">

                                    <label class="form-label">
                                        Offer Title
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           name="title"
                                           id="couponTitle"
                                           class="form-control"
                                           value="{{ old('title', $coupon->title) }}"
                                           required>

                                </div>


                                {{-- Description --}}
                                <div class="col-md-12">

                                    <label class="form-label">
                                        Description
                                    </label>

                                    <textarea name="description"
                                              class="form-control"
                                              rows="3">{{ old('description', $coupon->description) }}</textarea>

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

                                {{-- Applicable --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Applicable To
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="applicable_to"
                                            id="applicableTo"
                                            class="form-select"
                                            required>

                                        <option value="all"
                                            {{ old('applicable_to', $coupon->applicable_to) == 'all' ? 'selected' : '' }}>
                                            All Services
                                        </option>

                                        <option value="appointment"
                                            {{ old('applicable_to', $coupon->applicable_to) == 'appointment' ? 'selected' : '' }}>
                                            Doctor Appointment
                                        </option>

                                        <option value="medicine"
                                            {{ old('applicable_to', $coupon->applicable_to) == 'medicine' ? 'selected' : '' }}>
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

                                        <option value="percentage"
                                            {{ old('discount_type', $coupon->discount_type) == 'percentage' ? 'selected' : '' }}>
                                            Percentage (%)
                                        </option>

                                        <option value="fixed"
                                            {{ old('discount_type', $coupon->discount_type) == 'fixed' ? 'selected' : '' }}>
                                            Fixed Amount (₹)
                                        </option>

                                        <option value="free"
                                            {{ old('discount_type', $coupon->discount_type) == 'free' ? 'selected' : '' }}>
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
                                               value="{{ old('discount_value', $coupon->discount_value) }}"
                                               min="0"
                                               step="0.01">

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
                                               value="{{ old('max_discount', $coupon->max_discount) }}"
                                               min="0"
                                               step="0.01">

                                    </div>

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
                                               value="{{ old('min_order_amount', $coupon->min_order_amount) }}"
                                               min="0"
                                               step="0.01">

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Eligibility --}}
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
                                                   {{ old('new_customer_only', $coupon->new_customer_only) ? 'checked' : '' }}>

                                            <label class="form-check-label fw-semibold"
                                                   for="newCustomerOnly">

                                                New Customer Only

                                            </label>

                                        </div>

                                        <small class="text-muted">
                                            Only newly registered customers can use this offer.
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
                                                   {{ old('first_appointment_only', $coupon->first_appointment_only) ? 'checked' : '' }}>

                                            <label class="form-check-label fw-semibold"
                                                   for="firstAppointmentOnly">

                                                First Appointment Only

                                            </label>

                                        </div>

                                        <small class="text-muted">
                                            Valid only on the customer's first appointment.
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
                                                   {{ old('free_appointment', $coupon->free_appointment) ? 'checked' : '' }}>

                                            <label class="form-check-label fw-semibold"
                                                   for="freeAppointment">

                                                Free Appointment

                                            </label>

                                        </div>

                                        <small class="text-muted">
                                            Make the doctor appointment completely free.
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

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Total Usage Limit
                                    </label>

                                    <input type="number"
                                           name="usage_limit"
                                           class="form-control"
                                           value="{{ old('usage_limit', $coupon->usage_limit) }}"
                                           min="1">

                                    <small class="text-muted">
                                        Leave empty for unlimited usage.
                                    </small>

                                </div>


                                <div class="col-md-6">

                                    <label class="form-label">
                                        Usage Per Customer
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="number"
                                           name="usage_per_customer"
                                           class="form-control"
                                           value="{{ old('usage_per_customer', $coupon->usage_per_customer ?? 1) }}"
                                           min="1"
                                           required>

                                </div>


                                <div class="col-md-6">

                                    <label class="form-label">
                                        Already Used
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           value="{{ $coupon->used_count }}"
                                           readonly>

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
                                           value="{{ old(
                                               'starts_at',
                                               $coupon->starts_at
                                                   ? \Carbon\Carbon::parse($coupon->starts_at)->format('Y-m-d\TH:i')
                                                   : ''
                                           ) }}">

                                </div>


                                <div class="col-md-6">

                                    <label class="form-label">
                                        Expires At
                                    </label>

                                    <input type="datetime-local"
                                           name="expires_at"
                                           class="form-control"
                                           value="{{ old(
                                               'expires_at',
                                               $coupon->expires_at
                                                   ? \Carbon\Carbon::parse($coupon->expires_at)->format('Y-m-d\TH:i')
                                                   : ''
                                           ) }}">

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- RIGHT --}}
                <div class="col-lg-4">

                    {{-- Preview --}}
                    <div class="card mb-3">

                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Offer Preview
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="border rounded-3 p-4 text-center">

                                <div class="mb-3">

                                    <span class="avatar avatar-xl bg-primary rounded-circle">

                                        <i class="ti ti-ticket fs-28 text-white"></i>

                                    </span>

                                </div>


                                <h5 id="previewTitle">
                                    {{ $coupon->title }}
                                </h5>


                                <div class="my-3">

                                    <span class="badge bg-light-primary text-primary fs-16 px-3 py-2"
                                          id="previewCode">

                                        {{ $coupon->code }}

                                    </span>

                                </div>


                                <h3 class="text-success mb-2"
                                    id="previewDiscount">

                                    @if(
                                        $coupon->free_appointment ||
                                        $coupon->discount_type === 'free'
                                    )

                                        FREE APPOINTMENT

                                    @elseif($coupon->discount_type === 'percentage')

                                        {{ $coupon->discount_value }}% OFF

                                    @else

                                        ₹{{ $coupon->discount_value }} OFF

                                    @endif

                                </h3>


                                <p class="text-muted mb-0"
                                   id="previewApplicable">

                                    @if($coupon->applicable_to === 'appointment')

                                        Doctor Appointment

                                    @elseif($coupon->applicable_to === 'medicine')

                                        Medicine

                                    @else

                                        All Services

                                    @endif

                                </p>

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
                                       {{ old('status', $coupon->status) ? 'checked' : '' }}>

                                <label class="form-check-label fw-semibold"
                                       for="couponStatus">

                                    Active

                                </label>

                            </div>

                            <small class="text-muted">
                                Inactive coupons cannot be used.
                            </small>

                        </div>

                    </div>


                    {{-- Information --}}
                    <div class="card">

                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Coupon Information
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="mb-3">

                                <small class="text-muted d-block">
                                    Created
                                </small>

                                <strong>
                                    {{ $coupon->created_at?->format('d M Y, h:i A') }}
                                </strong>

                            </div>


                            <div>

                                <small class="text-muted d-block">
                                    Last Updated
                                </small>

                                <strong>
                                    {{ $coupon->updated_at?->format('d M Y, h:i A') }}
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Buttons --}}
            <div class="card">

                <div class="card-body">

                    <div class="d-flex justify-content-end gap-2">

                        <a href="{{ route('hospital.coupons.index') }}"
                           class="btn btn-light">
                            Cancel
                        </a>

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="ti ti-device-floppy me-1"></i>
                            Update Coupon

                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>
</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const codeInput =
        document.getElementById('couponCode');

    const titleInput =
        document.getElementById('couponTitle');

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


    function updateDiscountFields()
    {
        const type = discountType.value;

        if (type === 'free') {

            discountValueWrapper.style.display = 'none';
            maxDiscountWrapper.style.display = 'none';

            discountValue.value = 100;

        } else {

            discountValueWrapper.style.display = 'block';

            if (type === 'percentage') {

                discountPrefix.innerText = '%';
                maxDiscountWrapper.style.display = 'block';

            } else {

                discountPrefix.innerText = '₹';
                maxDiscountWrapper.style.display = 'none';

            }
        }
    }


    function updatePreview()
    {
        previewCode.innerText =
            codeInput.value || 'COUPON';

        previewTitle.innerText =
            titleInput.value || 'Your Offer';


        if (
            discountType.value === 'free' ||
            freeAppointment.checked
        ) {

            previewDiscount.innerText =
                'FREE APPOINTMENT';

        } else if (
            discountType.value === 'percentage'
        ) {

            previewDiscount.innerText =
                (discountValue.value || 0) + '% OFF';

        } else if (
            discountType.value === 'fixed'
        ) {

            previewDiscount.innerText =
                '₹' + (discountValue.value || 0) + ' OFF';

        } else {

            previewDiscount.innerText =
                'Discount';

        }


        if (applicableTo.value === 'appointment') {

            previewApplicable.innerText =
                'Doctor Appointment';

        } else if (applicableTo.value === 'medicine') {

            previewApplicable.innerText =
                'Medicine';

        } else {

            previewApplicable.innerText =
                'All Services';

        }
    }


    codeInput.addEventListener('input', function () {

        this.value = this.value
            .toUpperCase()
            .replace(/\s/g, '');

        updatePreview();

    });


    titleInput.addEventListener(
        'input',
        updatePreview
    );


    discountType.addEventListener(
        'change',
        function () {

            updateDiscountFields();
            updatePreview();

        }
    );


    discountValue.addEventListener(
        'input',
        updatePreview
    );


    applicableTo.addEventListener(
        'change',
        updatePreview
    );


    freeAppointment.addEventListener(
        'change',
        function () {

            if (this.checked) {

                discountType.value = 'free';
                applicableTo.value = 'appointment';
                discountValue.value = 100;

                updateDiscountFields();

            }

            updatePreview();

        }
    );


    updateDiscountFields();
    updatePreview();

});

</script>

@endsection