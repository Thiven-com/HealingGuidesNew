<?php $page = 'surgery-quotation-requests'; ?>

@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">

    <div class="content">

        {{-- Page Header --}}
        <div class="page-header">

            <div class="add-item d-flex">

                <div class="page-title">

                    <h4>Add Hospital Quotation</h4>

                    <h6>
                        Submit hospital quotation for surgery request
                    </h6>

                </div>

            </div>

            <ul class="table-top-head">

                <li>
                    <a href="{{ route(
                        'admin.surgery-quotation-requests.show',
                        $quotationRequest->id
                    ) }}"
                       data-bs-toggle="tooltip"
                       title="Back">

                        <i data-feather="arrow-left"></i>

                    </a>
                </li>

                <li>
                    <a id="collapse-header"
                       data-bs-toggle="tooltip"
                       title="Collapse">

                        <i data-feather="chevron-up"></i>

                    </a>
                </li>

            </ul>

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
                        data-bs-dismiss="alert"></button>

            </div>

        @endif


        {{-- Success --}}
        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"></button>

            </div>

        @endif


        <form method="POST"
              action="{{ route(
                  'admin.surgery-quotation-requests.quotation.store',
                  $quotationRequest->id
              ) }}">

            @csrf


            {{-- ========================================================= --}}
            {{-- Surgery Request Details --}}
            {{-- ========================================================= --}}

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="card-title mb-0">
                        Surgery Request Details
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row">

                        {{-- Request ID --}}
                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                Request ID
                            </label>

                            <input type="text"
                                   class="form-control"
                                   value="#{{ $quotationRequest->id }}"
                                   readonly>

                        </div>


                        {{-- Customer --}}
                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                Customer
                            </label>

                            <input type="text"
                                   class="form-control"
                                   value="{{ optional($quotationRequest->customer)->name
                                        ?? optional($quotationRequest->customer)->full_name
                                        ?? '-' }}"
                                   readonly>

                        </div>


                        {{-- Patient --}}
                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                Patient
                            </label>

                            <input type="text"
                                   class="form-control"
                                   value="{{ optional($quotationRequest->familyMember)->name
                                        ?? 'Self' }}"
                                   readonly>

                        </div>


                        {{-- Surgery --}}
                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                Surgery
                            </label>

                            <input type="text"
                                   class="form-control"
                                   value="{{ optional($quotationRequest->surgery)->name
                                        ?? optional($quotationRequest->surgery)->surgery_name
                                        ?? '-' }}"
                                   readonly>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- Hospital Quotation --}}
            {{-- ========================================================= --}}

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="card-title mb-0">
                        Hospital Quotation
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row">

                        {{-- Hospital --}}
                        <div class="col-md-12 mb-3">

                            <label class="form-label">
                                Hospital <span class="text-danger">*</span>
                            </label>

                            <select name="hospital_id"
                                    class="form-select"
                                    required>

                                <option value="">
                                    Select Hospital
                                </option>

                                @foreach($hospitals as $hospital)

                                    <option value="{{ $hospital->id }}"
                                        {{ old('hospital_id') == $hospital->id ? 'selected' : '' }}>

                                        {{ $hospital->hospital_name }}

                                    </option>

                                @endforeach

                            </select>

                            @error('hospital_id')

                                <small class="text-danger">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>

                        {{-- Amount --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Amount <span class="text-danger">*</span>
                            </label>

                            <input type="number"
                                   name="amount"
                                   id="amount"
                                   class="form-control"
                                   min="0"
                                   step="0.01"
                                   value="{{ old('amount', 0) }}"
                                   placeholder="0.00"
                                   required>

                            @error('amount')

                                <small class="text-danger">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- Discount --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Discount
                            </label>

                            <input type="number"
                                   name="discount"
                                   id="discount"
                                   class="form-control"
                                   min="0"
                                   step="0.01"
                                   value="{{ old('discount', 0) }}"
                                   placeholder="0.00">

                            @error('discount')

                                <small class="text-danger">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- Tax --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Tax
                            </label>

                            <input type="number"
                                   name="tax"
                                   id="tax"
                                   class="form-control"
                                   min="0"
                                   step="0.01"
                                   value="{{ old('tax', 0) }}"
                                   placeholder="0.00">

                            @error('tax')

                                <small class="text-danger">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- Total Amount --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Total Amount
                            </label>

                            <input type="number"
                                   name="total_amount"
                                   id="total_amount"
                                   class="form-control"
                                   min="0"
                                   step="0.01"
                                   value="{{ old('total_amount', 0) }}"
                                   placeholder="0.00"
                                   readonly>

                            @error('total_amount')

                                <small class="text-danger">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- Valid Until --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Valid Until
                            </label>

                            <input type="date"
                                   name="valid_until"
                                   class="form-control"
                                   value="{{ old('valid_until') }}">

                            @error('valid_until')

                                <small class="text-danger">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- Status --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Status
                            </label>

                            <select name="status"
                                    class="form-select">

                                <option value="sent"
                                    {{ old('status', 'sent') == 'sent' ? 'selected' : '' }}>
                                    Sent
                                </option>

                                <option value="approved"
                                    {{ old('status') == 'approved' ? 'selected' : '' }}>
                                    Approved
                                </option>

                                <option value="rejected"
                                    {{ old('status') == 'rejected' ? 'selected' : '' }}>
                                    Rejected
                                </option>

                            </select>

                        </div>


                        {{-- Quotation Details --}}
                        <div class="col-md-12 mb-3">

                            <label class="form-label">
                                Quotation Details
                            </label>

                            <textarea name="quotation_details"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Enter quotation details">{{ old('quotation_details') }}</textarea>

                            @error('quotation_details')

                                <small class="text-danger">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- Included Services --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Included Services
                            </label>

                            <textarea name="included_services"
                                      class="form-control"
                                      rows="5"
                                      placeholder="Enter included services">{{ old('included_services') }}</textarea>

                            @error('included_services')

                                <small class="text-danger">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- Excluded Services --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Excluded Services
                            </label>

                            <textarea name="excluded_services"
                                      class="form-control"
                                      rows="5"
                                      placeholder="Enter excluded services">{{ old('excluded_services') }}</textarea>

                            @error('excluded_services')

                                <small class="text-danger">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- Admin Notes --}}
                        <div class="col-md-12 mb-3">

                            <label class="form-label">
                                Admin Notes
                            </label>

                            <textarea name="admin_notes"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Enter internal admin notes">{{ old('admin_notes') }}</textarea>

                            @error('admin_notes')

                                <small class="text-danger">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- Actions --}}
            {{-- ========================================================= --}}

            <div class="card">

                <div class="card-body">

                    <div class="d-flex justify-content-end gap-2">

                        <a href="{{ route(
                            'admin.surgery-quotation-requests.show',
                            $quotationRequest->id
                        ) }}"
                           class="btn btn-secondary">

                            <i data-feather="arrow-left"
                               class="me-1"></i>

                            Cancel

                        </a>


                        <button type="submit"
                                class="btn btn-primary">

                            <i data-feather="send"
                               class="me-1"></i>

                            Submit Quotation

                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const amount = document.getElementById('amount');
    const discount = document.getElementById('discount');
    const tax = document.getElementById('tax');
    const totalAmount = document.getElementById('total_amount');

    function calculateTotal() {

        const amountValue =
            parseFloat(amount.value) || 0;

        const discountValue =
            parseFloat(discount.value) || 0;

        const taxValue =
            parseFloat(tax.value) || 0;

        let total =
            amountValue
            - discountValue
            + taxValue;

        if (total < 0) {
            total = 0;
        }

        totalAmount.value = total.toFixed(2);
    }

    amount.addEventListener('input', calculateTotal);
    discount.addEventListener('input', calculateTotal);
    tax.addEventListener('input', calculateTotal);

    calculateTotal();

    if (typeof feather !== 'undefined') {
        feather.replace();
    }

});

</script>

@endsection