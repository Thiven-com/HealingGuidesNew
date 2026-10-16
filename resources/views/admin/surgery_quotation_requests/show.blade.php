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

                    <a href="{{ route('admin.surgery-quotation-requests.index') }}" class="btn btn-secondary">
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

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>

            @endif


            @if(session('error'))

                <div class="alert alert-danger alert-dismissible fade show">

                    {{ session('error') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

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

                                    <form action="{{ route(
                        'admin.surgery-quotation-requests.approve',
                        ['id' => $quotationRequest->id]
                    ) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to approve this surgery quotation request?')">

                                        @csrf

                                        <button type="submit" class="btn btn-success">

                                            <i class="ti ti-check me-1"></i>

                                            Approve Request

                                        </button>

                                    </form>


                                    {{-- REJECT REQUEST --}}

                                    <form action="{{ route(
                        'admin.surgery-quotation-requests.reject',
                        ['id' => $quotationRequest->id]
                    ) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to reject this surgery quotation request?')">

                                        @csrf

                                        <button type="submit" class="btn btn-danger">

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

            <div class="card mb-4">

                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            Select Hospitals
                        </h5>

                        <span class="badge bg-primary">
                            Multiple Selection
                        </span>
                    </div>
                </div>

  
<div class="card-body">

    <form
        action="{{ route('admin.surgery-quotation-requests.quotation.store', ['requestId' => $quotationRequest->id]) }}"
        method="POST"
        id="sendHospitalsForm"
    >
        @csrf

        @php
            $selectedHospitalIds = old(
                'hospital_ids',
                $selectedHospitalIds ?? []
            );

            $selectedHospitalIds = array_map(
                'intval',
                $selectedHospitalIds
            );
        @endphp

        <div class="row g-3">

            @forelse($hospitals as $hospital)

                <div class="col-12 col-sm-6 col-md-3">

                    <label
                        for="hospital_{{ $hospital->id }}"
                        class="hospital-checkbox-card w-100 h-100"
                    >

                        <input
                            type="checkbox"
                            name="hospital_ids[]"
                            value="{{ $hospital->id }}"
                            class="form-check-input hospital-checkbox"
                            id="hospital_{{ $hospital->id }}"
                            {{ in_array((int) $hospital->id, $selectedHospitalIds, true) ? 'checked' : '' }}
                        >

                        <strong class="hospital-name text-muted">
                            {{ $hospital->hospital_name }}
                        </strong>


                    </label>

                </div>

            @empty

                <div class="col-12">
                    <p class="text-muted text-center py-3">
                        No hospitals available.
                    </p>
                </div>

            @endforelse

        </div>

        @error('hospital_ids')
            <div class="mt-2">{{ $message }}</div>
        @enderror

        @error('hospital_ids.*')
            <div class="mt-2">{{ $message }}</div>
        @enderror

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4">

            <span class="text-muted">
                Selected Hospitals:
                <strong id="selectedHospitalCount">0</strong>
            </span>

            <button type="submit" class="btn bg-primary">
                <i class="ti ti-send me-1"></i>
                Send Request to Hospitals
            </button>

        </div>

    </form>

</div>


            </div>


            {{-- ========================================================= --}}
            {{-- HOSPITAL QUOTATIONS --}}
            {{-- ========================================================= --}}

            <div class="card">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="card-title mb-0">
                            Hospital Quotations
                        </h5>


                        {{-- <a href="{{ route(
        'admin.surgery-quotation-requests.quotation.create',
        ['requestId' => $quotationRequest->id]
    ) }}" class="btn btn-primary">

                            <i class="ti ti-plus me-1"></i>

                            Add Hospital Quotation

                        </a> --}}

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
                                                                            {{$quotation->status}}
                                                                        </span>

                                                                    @endif

                                                                </td>


                                                                <td>

                                                                    <div class="d-flex gap-2">

                                                                        {{-- EDIT QUOTATION --}}

                                                                        <a href="{{ route(
                                            'admin.surgery-quotation-requests.quotation.edit',
                                            ['id' => $quotation->id]
                                        ) }}" class="btn btn-sm btn-primary">

                                                                            <i class="ti ti-edit"></i>

                                                                        </a>


                                                                        {{-- DELETE QUOTATION --}}

                                                                        <form action="{{ route(
                                            'admin.surgery-quotation-requests.quotation.destroy',
                                            ['id' => $quotation->id]
                                        ) }}" method="POST"
                                                                            onsubmit="return confirm('Are you sure you want to delete this quotation?')">

                                                                            @csrf

                                                                            @method('DELETE')

                                                                            <button type="submit" class="btn btn-sm btn-danger">

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

                                        <i class="ti ti-file-invoice" style="font-size:50px;"></i>

                                        <h5 class="mt-3">
                                            No Hospital Quotations
                                        </h5>

                                        <p class="text-muted">
                                            No hospital quotation has been added for this request yet.
                                        </p>

                                        <a href="{{ route(
                            'admin.surgery-quotation-requests.quotation.create',
                            ['requestId' => $quotationRequest->id]
                        ) }}" class="btn btn-primary">

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
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const checkboxes = document.querySelectorAll('.hospital-checkbox');
            const count = document.getElementById('selectedHospitalCount');
            const search = document.getElementById('hospitalSearch');

            function updateCount() {
                count.textContent = document.querySelectorAll(
                    '.hospital-checkbox:checked'
                ).length;
            }

            checkboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', updateCount);
            });

            document.getElementById('selectAllHospitals')
                .addEventListener('click', function () {
                    document.querySelectorAll('.hospital-item').forEach(function (item) {
                        if (item.style.display !== 'none') {
                            item.querySelector('.hospital-checkbox').checked = true;
                        }
                    });
                    updateCount();
                });

            document.getElementById('clearHospitals')
                .addEventListener('click', function () {
                    checkboxes.forEach(function (checkbox) {
                        checkbox.checked = false;
                    });
                    updateCount();
                });

            search.addEventListener('input', function () {
                const keyword = this.value.toLowerCase().trim();

                document.querySelectorAll('.hospital-item').forEach(function (item) {
                    const name = item.querySelector('.hospital-name')
                        .textContent.toLowerCase();

                    item.style.display = name.includes(keyword) ? '' : 'none';
                });
            });

            document.getElementById('sendHospitalsForm')
                .addEventListener('submit', function (event) {
                    if (!document.querySelector('.hospital-checkbox:checked')) {
                        event.preventDefault();
                        alert('Please select at least one hospital.');
                        return;
                    }

                    if (!confirm('Send this surgery quotation request to the selected hospitals?')) {
                        event.preventDefault();
                    }
                });

            updateCount();
        });
    </script>

@endsection