<?php $page = 'surgery-quotation-requests'; ?>

@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            {{-- Page Header --}}
            <div class="page-header">

                <div class="add-item d-flex">

                    <div class="page-title">

                        <h4>Edit Hospital Quotation</h4>

                        <h6>
                            Update hospital quotation
                        </h6>

                    </div>

                </div>

                <ul class="table-top-head">

                    <li>
                        <a href="{{ route(
        'admin.surgery-quotation-requests.show',
        $quotation->surgery_quotation_request_id
    ) }}" data-bs-toggle="tooltip" title="Back">

                            <i data-feather="arrow-left"></i>

                        </a>
                    </li>

                    <li>
                        <a id="collapse-header" data-bs-toggle="tooltip" title="Collapse">

                            <i data-feather="chevron-up"></i>

                        </a>
                    </li>

                </ul>

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

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>

            @endif


            {{-- Success --}}
            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">

                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>

            @endif


            <form method="POST" action="{{ route(
        'admin.surgery-quotation-requests.quotation.update',
        $quotation->id
    ) }}">

                @csrf

                @method('PUT')


                {{-- ========================================================= --}}
                {{-- Request Information --}}
                {{-- ========================================================= --}}

                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Surgery Request
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-3 mb-3">

                                <label class="form-label">
                                    Request ID
                                </label>

                                <input type="text" class="form-control"
                                    value="#{{ $quotation->surgery_quotation_request_id }}" readonly>

                            </div>


                            <div class="col-md-3 mb-3">

                                <label class="form-label">
                                    Customer
                                </label>

                                <input type="text" class="form-control" value="{{ optional($quotation->quotationRequest->customer)->name
        ?? optional($quotation->quotationRequest->customer)->full_name
        ?? '-' }}" readonly>

                            </div>


                            <div class="col-md-3 mb-3">

                                <label class="form-label">
                                    Patient
                                </label>

                                <input type="text" class="form-control" value="{{ optional($quotation->quotationRequest->familyMember)->name
        ?? 'Self' }}" readonly>

                            </div>


                            <div class="col-md-3 mb-3">

                                <label class="form-label">
                                    Surgery
                                </label>

                                <input type="text" class="form-control" value="{{ optional($quotation->quotationRequest->surgery)->name
        ?? optional($quotation->quotationRequest->surgery)->surgery_name
        ?? '-' }}" readonly>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ========================================================= --}}
                {{-- Quotation --}}
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
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Hospital <span class="text-danger">*</span>
                                </label>

                                <select name="hospital_id" id="hospital_id" class="form-select" required>

                                    <option value="">
                                        Select Hospital
                                    </option>

                                    @foreach($hospitals as $hospital)

                                                                    <option value="{{ $hospital->id }}" {{ old(
                                            'hospital_id',
                                            $quotation->hospital_id
                                        ) == $hospital->id ? 'selected' : '' }}>

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


                            {{-- Hospital Name --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Hospital Name
                                </label>

                                <input type="text" name="hospital_name" id="hospital_name" class="form-control" value="{{ old(
        'hospital_name',
        $quotation->hospital_name
    ) }}" placeholder="Hospital name">

                                @error('hospital_name')

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

                                <input type="number" name="amount" id="amount" class="form-control" min="0" step="0.01"
                                    value="{{ old(
        'amount',
        $quotation->amount
    ) }}" required>

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

                                <input type="number" name="discount" id="discount" class="form-control" min="0" step="0.01"
                                    value="{{ old(
        'discount',
        $quotation->discount
    ) }}">

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

                                <input type="number" name="tax" id="tax" class="form-control" min="0" step="0.01" value="{{ old(
        'tax',
        $quotation->tax
    ) }}">

                                @error('tax')

                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>

                                @enderror

                            </div>


                            {{-- Total --}}
                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Total Amount
                                </label>

                                <input type="number" name="total_amount" id="total_amount" class="form-control" min="0"
                                    step="0.01" value="{{ old(
        'total_amount',
        $quotation->total_amount
    ) }}" readonly>

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

                                <input type="date" name="valid_until" class="form-control"
                                    value="{{ old('valid_until', $quotation->valid_until) }}">

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

                                <select name="status" class="form-select">

                                    <option value="sent" {{ old(
        'status',
        $quotation->status
    ) == 'sent' ? 'selected' : '' }}>
                                        Sent
                                    </option>

                                    <option value="approved" {{ old(
        'status',
        $quotation->status
    ) == 'approved' ? 'selected' : '' }}>
                                        Approved
                                    </option>

                                    <option value="rejected" {{ old(
        'status',
        $quotation->status
    ) == 'rejected' ? 'selected' : '' }}>
                                        Rejected
                                    </option>

                                </select>

                            </div>


                            {{-- Quotation Details --}}
                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Quotation Details
                                </label>

                                <textarea name="quotation_details" class="form-control" rows="4"
                                    placeholder="Enter quotation details">{{ old(
        'quotation_details',
        $quotation->quotation_details
    ) }}</textarea>

                            </div>


                            {{-- Included Services --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Included Services
                                </label>

                                <textarea name="included_services" class="form-control" rows="5"
                                    placeholder="Enter included services">{{ old(
        'included_services',
        $quotation->included_services
    ) }}</textarea>

                            </div>


                            {{-- Excluded Services --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Excluded Services
                                </label>

                                <textarea name="excluded_services" class="form-control" rows="5"
                                    placeholder="Enter excluded services">{{ old(
        'excluded_services',
        $quotation->excluded_services
    ) }}</textarea>

                            </div>


                            {{-- Admin Notes --}}
                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Admin Notes
                                </label>

                                <textarea name="admin_notes" class="form-control" rows="4" placeholder="Enter admin notes">{{ old(
        'admin_notes',
        $quotation->admin_notes
    ) }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ========================================================= --}}
                {{-- Buttons --}}
                {{-- ========================================================= --}}

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route(
        'admin.surgery-quotation-requests.show',
        $quotation->surgery_quotation_request_id
    ) }}" class="btn btn-secondary">

                                <i data-feather="arrow-left" class="me-1"></i>

                                Cancel

                            </a>


                            <button type="submit" class="btn btn-primary">

                                <i data-feather="save" class="me-1"></i>

                                Update Quotation

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