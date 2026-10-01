<?php $page = 'surgery-quotation-requests'; ?>

@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            {{-- ========================================================= --}}
            {{-- PAGE HEADER --}}
            {{-- ========================================================= --}}

            <div class="page-header">

                <div class="page-title">

                    <h4>Surgery Quotation Request</h4>

                    <h6>
                        Request Details
                    </h6>

                </div>

                <div class="page-btn">

                    <a
                        href="{{ route('admin.surgery-quotation-requests.index') }}"
                        class="btn btn-secondary"
                    >
                        <i data-feather="arrow-left" class="me-2"></i>
                        Back
                    </a>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- ALERTS --}}
            {{-- ========================================================= --}}

            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">

                    {{ session('success') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                    ></button>

                </div>

            @endif


            @if(session('error'))

                <div class="alert alert-danger alert-dismissible fade show">

                    {{ session('error') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                    ></button>

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- REQUEST DETAILS --}}
            {{-- ========================================================= --}}

            <div class="card mb-4">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="card-title mb-0">
                            Request Details
                        </h5>


                        {{-- MAIN REQUEST STATUS --}}

                        @if($quotationRequest->status === 'approved')

                            <span class="badge bg-success fs-6">
                                Approved
                            </span>

                        @elseif($quotationRequest->status === 'rejected')

                            <span class="badge bg-danger fs-6">
                                Rejected
                            </span>

                        @else

                            <span class="badge bg-warning text-dark fs-6">
                                Pending
                            </span>

                        @endif

                    </div>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        {{-- Request No --}}

                        <div class="col-md-4">

                            <label class="text-muted">
                                Request No
                            </label>

                            <h6 class="mb-0">

                                {{ $quotationRequest->request_no ?? '-' }}

                            </h6>

                        </div>


                        {{-- Surgery --}}

                        <div class="col-md-4">

                            <label class="text-muted">
                                Surgery
                            </label>

                            <h6 class="mb-0">

                                {{ optional($quotationRequest->surgery)->name ?? '-' }}

                            </h6>

                        </div>


                        {{-- Created --}}

                        <div class="col-md-4">

                            <label class="text-muted">
                                Requested On
                            </label>

                            <h6 class="mb-0">

                                {{ optional($quotationRequest->created_at)->format('d M Y, h:i A') }}

                            </h6>

                        </div>


                        {{-- Customer --}}

                        <div class="col-md-4">

                            <label class="text-muted">
                                Customer
                            </label>

                            <h6 class="mb-0">

                                {{ optional($quotationRequest->customer)->name ?? '-' }}

                            </h6>

                        </div>


                        {{-- Customer Mobile --}}

                        <div class="col-md-4">

                            <label class="text-muted">
                                Customer Mobile
                            </label>

                            <h6 class="mb-0">

                                {{ optional($quotationRequest->customer)->mobile ?? '-' }}

                            </h6>

                        </div>


                        {{-- Family Member --}}

                        <div class="col-md-4">

                            <label class="text-muted">
                                Patient
                            </label>

                            <h6 class="mb-0">

                                {{ optional($quotationRequest->familyMember)->name ?? 'Self' }}

                            </h6>

                        </div>

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- MAIN REQUEST ACTIONS --}}
                {{-- ===================================================== --}}

                @if($quotationRequest->status === 'pending')

                                <div class="card-footer">

                                    <div class="d-flex gap-2">

                                        {{-- APPROVE REQUEST --}}

                                        <form
                                            action="{{ route(
                        'admin.surgery-quotation-requests.approve',
                        ['id' => $quotationRequest->id]
                    ) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to approve this surgery quotation request?')"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="btn btn-success"
                                            >

                                                <i class="ti ti-check me-1"></i>

                                                Approve Request

                                            </button>

                                        </form>


                                        {{-- REJECT REQUEST --}}

                                        <form
                                            action="{{ route(
                        'admin.surgery-quotation-requests.reject',
                        ['id' => $quotationRequest->id]
                    ) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to reject this surgery quotation request?')"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="btn btn-danger"
                                            >

                                                <i class="ti ti-x me-1"></i>

                                                Reject Request

                                            </button>

                                        </form>

                                    </div>

                                </div>

                @endif

            </div>


            {{-- ========================================================= --}}
            {{-- PATIENT DETAILS --}}
            {{-- ========================================================= --}}

            @if($quotationRequest->familyMember)

                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Patient Details
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row g-4">

                            <div class="col-md-3">

                                <label class="text-muted">
                                    Name
                                </label>

                                <h6>
                                    {{ $quotationRequest->familyMember->name ?? '-' }}
                                </h6>

                            </div>

                            <div class="col-md-3">

                                <label class="text-muted">
                                    Mobile
                                </label>

                                <h6>
                                    {{ $quotationRequest->familyMember->mobile ?? '-' }}
                                </h6>

                            </div>

                            <div class="col-md-3">

                                <label class="text-muted">
                                    Gender
                                </label>

                                <h6>
                                    {{ $quotationRequest->familyMember->gender ?? '-' }}
                                </h6>

                            </div>

                            <div class="col-md-3">

                                <label class="text-muted">
                                    Age
                                </label>

                                <h6>
                                    {{ $quotationRequest->familyMember->age ?? '-' }}
                                </h6>

                            </div>

                        </div>

                    </div>

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- HOSPITAL QUOTATIONS --}}
            {{-- ========================================================= --}}

            <div class="card">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="card-title mb-0">
                            Hospital Quotations
                        </h5>


                        <a
                            href="{{ route(
        'admin.surgery-quotation-requests.quotation.create',
        ['requestId' => $quotationRequest->id]
    ) }}"
                            class="btn btn-primary"
                        >

                            <i class="ti ti-plus me-1"></i>

                            Add Hospital Quotation

                        </a>

                    </div>

                </div>


                <div class="card-body">

                    @if($quotationRequest->quotations->count())

                        <div class="table-responsive">

                            <table class="table table-bordered align-middle">

                                <thead>

                                    <tr>

                                        <th>#</th>

                                        <th>Hospital</th>

                                        <th>Amount</th>

                                        <th>Discount</th>

                                        <th>Tax</th>

                                        <th>Total</th>

                                        <th>Status</th>

                                        <th width="200">
                                            Action
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach($quotationRequest->quotations as $key => $quotation)

                                                                    <tr>

                                                                        <td>
                                                                            {{ $key + 1 }}
                                                                        </td>


                                                                        <td>

                                                                            <strong>

                                                                                {{ optional($quotation->hospital)->hospital_name ?? '-' }}

                                                                            </strong>

                                                                        </td>


                                                                        <td>

                                                                            ₹ {{ number_format($quotation->amount, 2) }}

                                                                        </td>


                                                                        <td>

                                                                            ₹ {{ number_format($quotation->discount, 2) }}

                                                                        </td>


                                                                        <td>

                                                                            ₹ {{ number_format($quotation->tax, 2) }}

                                                                        </td>


                                                                        <td>

                                                                            <strong>

                                                                                ₹ {{ number_format($quotation->total_amount, 2) }}

                                                                            </strong>

                                                                        </td>


                                                                        <td>

                                                                            @if($quotation->status === 'approved')

                                                                                <span class="badge bg-success">
                                                                                    Approved
                                                                                </span>

                                                                            @elseif($quotation->status === 'rejected')

                                                                                <span class="badge bg-danger">
                                                                                    Rejected
                                                                                </span>

                                                                            @else

                                                                                <span class="badge bg-warning text-dark">
                                                                                    Pending
                                                                                </span>

                                                                            @endif

                                                                        </td>


                                                                        <td>

                                                                            <div class="d-flex gap-2">

                                                                                {{-- EDIT QUOTATION --}}

                                                                                <a
                                                                                    href="{{ route(
                                            'admin.surgery-quotation-requests.quotation.edit',
                                            ['id' => $quotation->id]
                                        ) }}"
                                                                                    class="btn btn-sm btn-primary"
                                                                                >

                                                                                    <i class="ti ti-edit"></i>

                                                                                </a>


                                                                                {{-- DELETE QUOTATION --}}

                                                                                <form
                                                                                    action="{{ route(
                                            'admin.surgery-quotation-requests.quotation.destroy',
                                            ['id' => $quotation->id]
                                        ) }}"
                                                                                    method="POST"
                                                                                    onsubmit="return confirm('Are you sure you want to delete this quotation?')"
                                                                                >

                                                                                    @csrf

                                                                                    @method('DELETE')

                                                                                    <button
                                                                                        type="submit"
                                                                                        class="btn btn-sm btn-danger"
                                                                                    >

                                                                                        <i class="ti ti-trash"></i>

                                                                                    </button>

                                                                                </form>

                                                                            </div>

                                                                        </td>

                                                                    </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                                        <div class="text-center py-5">

                                            <i
                                                class="ti ti-file-invoice"
                                                style="font-size:50px;"
                                            ></i>

                                            <h5 class="mt-3">
                                                No Hospital Quotations
                                            </h5>

                                            <p class="text-muted">
                                                No hospital quotation has been added for this request yet.
                                            </p>

                                            <a
                                                href="{{ route(
                            'admin.surgery-quotation-requests.quotation.create',
                            ['requestId' => $quotationRequest->id]
                        ) }}"
                                                class="btn btn-primary"
                                            >

                                                <i class="ti ti-plus me-1"></i>

                                                Add Hospital Quotation

                                            </a>

                                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    <script>

    document.addEventListener(
        "DOMContentLoaded",
        function () {

            if (typeof feather !== "undefined") {
                feather.replace();
            }

        }
    );

    </script>

@endsection