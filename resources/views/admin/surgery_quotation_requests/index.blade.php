<?php $page = 'surgery-quotation-requests'; ?>

@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            {{-- Page Header --}}
            <div class="page-header">

                <div class="add-item d-flex">

                    <div class="page-title">

                        <h4>Surgery Quotation Requests</h4>

                        <h6>Manage Surgery Quotation Requests</h6>

                    </div>

                </div>

                <ul class="table-top-head">

                    <li>
                        <a href="{{ route('admin.surgery-quotation-requests.index') }}" data-bs-toggle="tooltip"
                            title="Refresh">

                            <i data-feather="rotate-ccw"></i>

                        </a>
                    </li>

                    <li>
                        <a id="collapse-header" data-bs-toggle="tooltip" title="Collapse">

                            <i data-feather="chevron-up"></i>

                        </a>
                    </li>

                </ul>

            </div>

            {{-- Alerts --}}
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

            {{-- Validation Errors --}}
            @if($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <div class="card table-list-card">

                <div class="card-body">

                    {{-- Filters --}}
                    <form method="GET" action="{{ route('admin.surgery-quotation-requests.index') }}">

                        <div class="row g-3 align-items-end mb-4">

                            {{-- Search --}}
                            <div class="col-md-4">

                                <label class="form-label">
                                    Search
                                </label>

                                <input type="text" name="search" class="form-control"
                                    placeholder="Request No, customer or surgery" value="{{ request('search') }}">

                            </div>


                            {{-- Status --}}
                            <div class="col-md-3">

                                <label class="form-label">
                                    Status
                                </label>

                                <select name="status" class="form-select">

                                    <option value="">
                                        All Status
                                    </option>

                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>

                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                        Approved
                                    </option>

                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                        Rejected
                                    </option>

                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                        Completed
                                    </option>

                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled
                                    </option>

                                </select>

                            </div>


                            {{-- Buttons --}}
                            <div class="col-md-5 d-flex gap-2">

                                <button type="submit" class="btn btn-primary">

                                    <i class="ti ti-search me-1"></i>

                                    Filter

                                </button>

                                <a href="{{ route('admin.surgery-quotation-requests.index') }}" class="btn btn-secondary">

                                    <i class="ti ti-refresh me-1"></i>

                                    Reset

                                </a>

                            </div>

                        </div>

                    </form>


                    {{-- Table --}}
                    <div class="table-responsive">

                        <table class="table datanew">

                            <thead>

                                <tr>

                                    <th width="60">
                                        #
                                    </th>

                                    <th>
                                        Request No
                                    </th>

                                    <th>
                                        Customer
                                    </th>

                                    <th>
                                        Patient
                                    </th>

                                    <th>
                                        Surgery
                                    </th>

                                    <th>
                                        Quotations
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Created
                                    </th>

                                    <th width="220" class="text-center">

                                        Action

                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($quotationRequests as $key => $quotationRequest)

                                                            <tr>

                                                                {{-- # --}}
                                                                <td>

                                                                    {{ $key + 1 }}

                                                                </td>


                                                                {{-- Request Number --}}
                                                                <td>

                                                                    <strong>

                                                                        {{ $quotationRequest->request_no
                                        ?? ('REQ-' . $quotationRequest->id) }}

                                                                    </strong>

                                                                </td>


                                                                {{-- Customer --}}
                                                                <td>

                                                                    @if($quotationRequest->customer)

                                                                                                <strong>

                                                                                                    {{ $quotationRequest->customer->name
                                                                        ?? $quotationRequest->customer->full_name
                                                                        ?? '-' }}

                                                                                                </strong>

                                                                                                @if(!empty($quotationRequest->customer->mobile))

                                                                                                    <br>

                                                                                                    <small class="text-muted">

                                                                                                        {{ $quotationRequest->customer->mobile }}

                                                                                                    </small>

                                                                                                @endif

                                                                    @else

                                                                        -

                                                                    @endif

                                                                </td>


                                                                {{-- Patient --}}
                                                                <td>

                                                                    @if($quotationRequest->familyMember)

                                                                        <strong>

                                                                            {{ $quotationRequest->familyMember->name }}

                                                                        </strong>

                                                                        @if(!empty($quotationRequest->familyMember->relationship))

                                                                            <br>

                                                                            <small class="text-muted">

                                                                                {{ $quotationRequest->familyMember->relationship }}

                                                                            </small>

                                                                        @endif

                                                                    @else

                                                                        Self

                                                                    @endif

                                                                </td>


                                                                {{-- Surgery --}}
                                                                <td>

                                                                    @if($quotationRequest->surgery)

                                                                                                <strong>

                                                                                                    {{ $quotationRequest->surgery->name
                                                                        ?? $quotationRequest->surgery->surgery_name
                                                                        ?? '-' }}

                                                                                                </strong>

                                                                    @else

                                                                        -

                                                                    @endif

                                                                </td>


                                                                {{-- Quotations --}}
                                                                <td>

                                                                    <a href="{{ route(
                                        'admin.surgery-quotation-requests.quotations',
                                        $quotationRequest->id
                                    ) }}" class="badge bg-info">

                                                                        {{ $quotationRequest->quotations_count
                                        ?? $quotationRequest->quotations->count() }}

                                                                        Quotations

                                                                    </a>

                                                                </td>


                                                                {{-- Main Request Status --}}
                                                                <td>

                                                                    @php

                                                                        $status = strtolower(
                                                                            $quotationRequest->status ?? 'pending'
                                                                        );

                                                                    @endphp


                                                                    @if($status === 'approved')

                                                                        <span class="badge bg-success">

                                                                            Approved

                                                                        </span>

                                                                    @elseif($status === 'rejected')

                                                                        <span class="badge bg-danger">

                                                                            Rejected

                                                                        </span>

                                                                    @elseif($status === 'completed')

                                                                        <span class="badge bg-primary">

                                                                            Completed

                                                                        </span>

                                                                    @elseif($status === 'cancelled')

                                                                        <span class="badge bg-secondary">

                                                                            Cancelled

                                                                        </span>

                                                                    @else

                                                                        <span class="badge bg-warning text-dark">

                                                                            Pending

                                                                        </span>

                                                                    @endif

                                                                </td>


                                                                {{-- Created --}}
                                                                <td>

                                                                    {{ $quotationRequest->created_at
                                        ? $quotationRequest->created_at->format('d M Y h:i A')
                                        : '-' }}

                                                                </td>


                                                                {{-- Actions --}}
                                                                <td class="text-center">

                                                                    <div class="dropdown">

                                                                        <a href="javascript:void(0)" class="btn btn-sm btn-light"
                                                                            data-bs-toggle="dropdown">

                                                                            <i class="ti ti-dots-vertical"></i>

                                                                        </a>


                                                                        <div class="dropdown-menu dropdown-menu-end">


                                                                            {{-- View --}}
                                                                            <a class="dropdown-item" href="{{ route(
                                        'admin.surgery-quotation-requests.show',
                                        $quotationRequest->id
                                    ) }}">

                                                                                <i class="ti ti-eye me-2"></i>

                                                                                View

                                                                            </a>


                                                                            {{-- Quotations --}}
                                                                            <a class="dropdown-item" href="{{ route(
                                        'admin.surgery-quotation-requests.quotations',
                                        $quotationRequest->id
                                    ) }}">

                                                                                <i class="ti ti-file-invoice me-2"></i>

                                                                                View Quotations

                                                                            </a>


                                                                            @if($status === 'pending')

                                                                                                                    <div class="dropdown-divider"></div>


                                                                                                                    {{-- APPROVE MAIN REQUEST --}}
                                                                                                                    <form method="POST" action="{{ route(
                                                                                    'admin.surgery-quotation-requests.approve',
                                                                                    $quotationRequest->id
                                                                                ) }}">

                                                                                                                        @csrf

                                                                                                                        <button type="submit" class="dropdown-item text-success"
                                                                                                                            onclick="return confirm('Are you sure you want to approve this surgery quotation request?')">

                                                                                                                            <i class="ti ti-check me-2"></i>

                                                                                                                            Approve Request

                                                                                                                        </button>

                                                                                                                    </form>


                                                                                                                    {{-- REJECT MAIN REQUEST --}}
                                                                                                                    <form method="POST" action="{{ route(
                                                                                    'admin.surgery-quotation-requests.reject',
                                                                                    $quotationRequest->id
                                                                                ) }}">

                                                                                                                        @csrf

                                                                                                                        <button type="submit" class="dropdown-item text-danger"
                                                                                                                            onclick="return confirm('Are you sure you want to reject this surgery quotation request?')">

                                                                                                                            <i class="ti ti-x me-2"></i>

                                                                                                                            Reject Request

                                                                                                                        </button>

                                                                                                                    </form>

                                                                            @endif


                                                                            @if($status !== 'approved' && $status !== 'rejected')

                                                                                <div class="dropdown-divider"></div>

                                                                                {{-- Delete --}}
                                                                                <a href="javascript:void(0)" class="dropdown-item text-danger"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#deleteModal{{ $quotationRequest->id }}">

                                                                                    <i class="ti ti-trash me-2"></i>

                                                                                    Delete

                                                                                </a>

                                                                            @endif

                                                                        </div>

                                                                    </div>

                                                                </td>

                                                            </tr>


                                                            {{-- Delete Modal --}}
                                                            <div class="modal fade" id="deleteModal{{ $quotationRequest->id }}" tabindex="-1"
                                                                aria-hidden="true">

                                                                <div class="modal-dialog">

                                                                    <div class="modal-content">

                                                                        <div class="modal-header">

                                                                            <h5 class="modal-title">

                                                                                Delete Surgery Quotation Request

                                                                            </h5>

                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                                                        </div>


                                                                        <div class="modal-body">

                                                                            Are you sure you want to delete this quotation request?

                                                                            <br>

                                                                            <strong>

                                                                                {{ $quotationRequest->request_no
                                        ?? ('REQ-' . $quotationRequest->id) }}

                                                                            </strong>

                                                                        </div>


                                                                        <div class="modal-footer">

                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                                                                                Cancel

                                                                            </button>


                                                                            <form method="POST" action="{{ route(
                                        'admin.surgery-quotation-requests.destroy',
                                        $quotationRequest->id
                                    ) }}">

                                                                                @csrf

                                                                                @method('DELETE')

                                                                                <button type="submit" class="btn btn-danger">

                                                                                    Delete

                                                                                </button>

                                                                            </form>

                                                                        </div>

                                                                    </div>

                                                                </div>

                                                            </div>

                                @empty

                                    <tr>

                                        <td colspan="9" class="text-center py-5">

                                            <i class="ti ti-file-off fs-1 text-muted"></i>

                                            <h6 class="mt-3">

                                                No Surgery Quotation Requests Found

                                            </h6>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <script>

        document.addEventListener("DOMContentLoaded", function () {

            if ($('.datanew').length) {

                $('.datanew').DataTable({

                    responsive: true,

                    autoWidth: false,

                    ordering: true,

                    pageLength: 10,

                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"]
                    ],

                    language: {

                        search: "",

                        searchPlaceholder: "Search Surgery Quotation Requests..."

                    }

                });

            }


            if (typeof feather !== "undefined") {

                feather.replace();

            }

        });

    </script>

@endsection