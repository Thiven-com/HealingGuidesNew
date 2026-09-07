<?php $page = 'insurances'; ?>

@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            {{-- Page Header --}}
            <div class="page-header d-flex align-items-center justify-content-between">

                <div class="add-item d-flex">

                    <div class="page-title">

                        <h4>Insurance</h4>

                        <h6>Manage Customer Insurance</h6>

                    </div>

                </div>

            </div>
            <div class="card mb-3">
                <div class="card-body">

                    <form method="GET" action="{{ route('admin.insurances.all') }}">

                        <div class="row g-3">

                            <div class="col-md-3">
                                <label class="form-label">Insurance Provider</label>

                                <input type="text" name="insurance_provider" class="form-control"
                                    placeholder="Enter provider" value="{{ request('insurance_provider') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Policy Number</label>

                                <input type="text" name="policy_number" class="form-control"
                                    placeholder="Enter policy number" value="{{ request('policy_number') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Policy Type</label>

                                <input type="text" name="policy_type" class="form-control" placeholder="Enter policy type"
                                    value="{{ request('policy_type') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Status</label>

                                <select name="status" class="form-select">

                                    <option value="">All Status</option>

                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>
                                        Expired
                                    </option>

                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>

                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Start Date From</label>

                                <input type="date" name="start_date_from" class="form-control"
                                    value="{{ request('start_date_from') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Start Date To</label>

                                <input type="date" name="start_date_to" class="form-control"
                                    value="{{ request('start_date_to') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Expiry Date From</label>

                                <input type="date" name="expiry_date_from" class="form-control"
                                    value="{{ request('expiry_date_from') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Expiry Date To</label>

                                <input type="date" name="expiry_date_to" class="form-control"
                                    value="{{ request('expiry_date_to') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Customer</label>

                                <select name="customer_id" class="form-select">

                                    <option value="">All Customers</option>

                                    @foreach($customers as $customer)

                                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>

                                            {{ $customer->name }}

                                        </option>

                                    @endforeach

                                </select>
                            </div>

                            <div class="col-md-9 d-flex align-items-end gap-2">

                                <button type="submit" class="btn btn-primary">

                                    <i data-feather="filter" class="me-1"></i>
                                    Filter

                                </button>

                                <a href="{{ route('admin.insurances.all') }}" class="btn btn-light">

                                    <i data-feather="rotate-ccw" class="me-1"></i>
                                    Reset

                                </a>

                            </div>

                        </div>

                    </form>

                </div>
            </div>


            {{-- Success Message --}}
            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show" role="alert">

                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>

                </div>

            @endif


            {{-- Error Message --}}
            @if(session('error'))

                <div class="alert alert-danger alert-dismissible fade show" role="alert">

                    {{ session('error') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>

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


            {{-- Insurance Table --}}
            <div class="card">

                <div class="card-header">

                    <div class="d-flex align-items-center justify-content-between">

                        <h5 class="card-title mb-0">
                            Insurance List
                        </h5>

                        <span class="badge bg-light text-dark">
                            {{ $insurances->total() }} Records
                        </span>

                    </div>

                </div>


                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table datanew">

                            <thead>

                                <tr>

                                    <th>
                                        #
                                    </th>

                                    <th>
                                        Customer
                                    </th>

                                    <th>
                                        Insurance Provider
                                    </th>

                                    <th>
                                        Policy Number
                                    </th>

                                    <th>
                                        Policy Type
                                    </th>

                                    <th>
                                        Policy Holder
                                    </th>

                                    <th>
                                        Coverage Amount
                                    </th>

                                    <th>
                                        Start Date
                                    </th>

                                    <th>
                                        Expiry Date
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Document
                                    </th>

                                    <th class="text-end">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($insurances as $key => $insurance)

                                    <tr>

                                        {{-- ID --}}
                                        <td>

                                            {{ $insurances->firstItem() + $key }}

                                        </td>


                                        {{-- Customer --}}
                                        <td>

                                            @if($insurance->customer)

                                                <div class="d-flex align-items-center">

                                                    <div class="avatar avatar-md bg-light-primary rounded-circle me-2">

                                                        <i data-feather="user"></i>

                                                    </div>

                                                    <div>

                                                        <h6 class="mb-0">

                                                            {{ $insurance->customer->name ?? '-' }}

                                                        </h6>

                                                        @if($insurance->customer->mobile ?? false)

                                                            <small class="text-muted">

                                                                {{ $insurance->customer->mobile }}

                                                            </small>

                                                        @endif

                                                    </div>

                                                </div>

                                            @else

                                                <span class="text-muted">
                                                    -
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Provider --}}
                                        <td>

                                            <strong>
                                                {{ $insurance->insurance_provider ?? '-' }}
                                            </strong>

                                        </td>


                                        {{-- Policy Number --}}
                                        <td>

                                            <span class="fw-medium">

                                                {{ $insurance->policy_number ?? '-' }}

                                            </span>

                                        </td>


                                        {{-- Policy Type --}}
                                        <td>

                                            {{ $insurance->policy_type ?? '-' }}

                                        </td>


                                        {{-- Policy Holder --}}
                                        <td>

                                            {{ $insurance->policy_holder_name ?? '-' }}

                                        </td>


                                        {{-- Coverage Amount --}}
                                        <td>

                                            @if($insurance->coverage_amount !== null)

                                                <strong>

                                                    ₹{{ number_format($insurance->coverage_amount, 2) }}

                                                </strong>

                                            @else

                                                -

                                            @endif

                                        </td>


                                        {{-- Start Date --}}
                                        <td>

                                            @if($insurance->start_date)

                                                {{ \Carbon\Carbon::parse($insurance->start_date)->format('d M Y') }}

                                            @else

                                                -

                                            @endif

                                        </td>


                                        {{-- Expiry Date --}}
                                        <td>

                                            @if($insurance->expiry_date)

                                                {{ \Carbon\Carbon::parse($insurance->expiry_date)->format('d M Y') }}

                                            @else

                                                -

                                            @endif

                                        </td>


                                        {{-- Status --}}
                                        <td>

                                            @if($insurance->status === 'active')

                                                <span class="badge bg-success">

                                                    Active

                                                </span>

                                            @elseif($insurance->status === 'expired')

                                                <span class="badge bg-danger">

                                                    Expired

                                                </span>

                                            @else

                                                <span class="badge bg-warning text-dark">

                                                    Inactive

                                                </span>

                                            @endif

                                        </td>


                                        {{-- Document --}}
                                        <td>

                                            @if($insurance->document)

                                                <a href="{{ asset($insurance->document) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary">

                                                    <i data-feather="file-text" class="me-1"></i>

                                                    View

                                                </a>

                                            @else

                                                <span class="text-muted">
                                                    No Document
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Actions --}}
                                        <td class="text-end">

                                            <div class="edit-delete-action">


                                                {{-- Delete --}}
                                                {{-- <form action="{{ route('admin.insurances.destroy', $insurance->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this insurance record?');">

                                                    @csrf

                                                    @method('DELETE')

                                                    <button type="submit" class="btn p-2 text-danger" data-bs-toggle="tooltip"
                                                        title="Delete">

                                                        <i data-feather="trash-2"></i>

                                                    </button>

                                                </form> --}}

                                            </div>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="12" class="text-center py-5">

                                            <div class="text-muted">

                                                <i data-feather="shield" style="width:50px;height:50px;">
                                                </i>

                                                <h5 class="mt-3">
                                                    No Insurance Records Found
                                                </h5>

                                                <p class="mb-3">
                                                    No customer insurance records have been added yet.
                                                </p>

                                                <a href="{{ route('admin.insurances.create') }}" class="btn btn-primary">

                                                    <i data-feather="plus" class="me-2"></i>

                                                    Add Insurance

                                                </a>

                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- Pagination --}}
                    @if($insurances->hasPages())

                        <div class="mt-3">

                            {{ $insurances->links() }}

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    <script>

        document.addEventListener("DOMContentLoaded", function () {

            if (typeof feather !== "undefined") {
                feather.replace();
            }

        });

    </script>

@endsection