<?php $page = 'ambulances'; ?>

@extends('layout.mainlayout')

@section('content')

    <style>
        .pricing-card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .pricing-card .card-header {
            background: #f8f9fa;
            padding: 15px 20px;
        }

        .pricing-card .card-header h5 {
            margin: 0;
        }

        .price-input {
            max-width: 180px;
        }

        .distance-badge {
            font-size: 13px;
            padding: 6px 10px;
        }
    </style>

    <div class="page-wrapper">

        <div class="content">

            {{-- Page Header --}}
            <div class="page-header">

                <div class="add-item d-flex">

                    <div class="page-title">

                        <h4>Ambulance Pricing</h4>

                        <h6>
                            Manage pricing for
                            {{ $ambulance->ambulance_name }}
                        </h6>

                    </div>

                </div>

                <ul class="table-top-head">

                    <li>

                        <a href="{{ route('admin.ambulances.prices.index', $ambulance->id) }}" data-bs-toggle="tooltip"
                            title="Refresh">

                            <i data-feather="rotate-ccw"></i>

                        </a>

                    </li>

                    <li>

                        <a href="{{ route('admin.ambulances.show', $ambulance->id) }}" data-bs-toggle="tooltip"
                            title="View Ambulance">

                            <i data-feather="eye"></i>

                        </a>

                    </li>

                    <li>

                        <a id="collapse-header" data-bs-toggle="tooltip" title="Collapse">

                            <i data-feather="chevron-up"></i>

                        </a>

                    </li>

                </ul>

                <div class="page-btn">

                    <a href="{{ route('admin.ambulances.index') }}" class="btn btn-secondary">

                        <i data-feather="arrow-left" class="me-2"></i>

                        Back to Ambulances

                    </a>

                </div>

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

            @if($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- Ambulance Information --}}
            <div class="card mb-4">

                <div class="card-body">

                    <div class="row align-items-center">

                        <div class="col-md-2 text-center">

                            @if($ambulance->image)

                                <img src="{{ asset($ambulance->image) }}" class="img-thumbnail"
                                    style="width:100px;height:100px;object-fit:cover;">

                            @else

                                <img src="{{ asset('assets/img/no-image.png') }}" class="img-thumbnail"
                                    style="width:100px;height:100px;object-fit:cover;">

                            @endif

                        </div>

                        <div class="col-md-10">

                            <h5 class="mb-2">

                                {{ $ambulance->ambulance_name }}

                            </h5>

                            <div class="row">

                                <div class="col-md-3">

                                    <small class="text-muted">
                                        Ambulance Code
                                    </small>

                                    <div>
                                        <strong>
                                            {{ $ambulance->ambulance_code ?? '-' }}
                                        </strong>
                                    </div>

                                </div>

                                <div class="col-md-3">

                                    <small class="text-muted">
                                        Vehicle Number
                                    </small>

                                    <div>
                                        <strong>
                                            {{ $ambulance->vehicle_number ?? '-' }}
                                        </strong>
                                    </div>

                                </div>

                                <div class="col-md-3">

                                    <small class="text-muted">
                                        Ambulance Type
                                    </small>

                                    <div>
                                        <strong>
                                            {{ optional($ambulance->ambulanceType)->ambulance_type_name ?? '-' }}
                                        </strong>
                                    </div>

                                </div>

                                <div class="col-md-3">

                                    <small class="text-muted">
                                        Hospital
                                    </small>

                                    <div>
                                        <strong>
                                            {{ optional($ambulance->hospital)->hospital_name ?? '-' }}
                                        </strong>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Add Pricing --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h5>Add Ambulance Pricing</h5>

                </div>

                <div class="card-body">

                    <form action="{{ route('admin.ambulances.prices.store', $ambulance->id) }}" method="POST">

                        @csrf

                        <div class="row align-items-end">

                            <div class="col-md-3">

                                <label class="form-label">
                                    Trip Type
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="trip_type" class="form-select" required>

                                    <option value="">
                                        Select Trip Type
                                    </option>

                                    <option value="local">
                                        Local
                                    </option>

                                    <option value="outstation">
                                        Outstation
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Maximum Distance (KM)
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="number" name="max_distance_km" class="form-control" step="0.01" min="0.01"
                                    placeholder="Example: 5" required>

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Amount
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        ₹
                                    </span>

                                    <input type="number" name="amount" class="form-control" step="0.01" min="0"
                                        placeholder="Example: 600" required>

                                </div>

                            </div>

                            <div class="col-md-2">

                                <div class="form-check form-switch mb-2">

                                    <input type="hidden" name="status" value="0">

                                    <input type="checkbox" name="status" value="1" class="form-check-input" id="priceStatus"
                                        checked>

                                    <label class="form-check-label" for="priceStatus">

                                        Active

                                    </label>

                                </div>

                            </div>

                            <div class="col-md-1">

                                <button type="submit" class="btn btn-primary">

                                    <i data-feather="plus"></i>

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>


            {{-- Local Pricing --}}
            <div class="card pricing-card mb-4">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <div>

                        <h5>Local Pricing</h5>

                        <small class="text-muted">
                            Pricing for local trips
                        </small>

                    </div>

                    <span class="badge bg-primary">

                        {{ $localPrices->count() }} Slabs

                    </span>

                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th width="80">#</th>

                                    <th>Distance</th>

                                    <th>Amount</th>

                                    <th>Status</th>

                                    <th width="180">Action</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($localPrices as $key => $price)

                                    <tr>

                                        <td>
                                            {{ $key + 1 }}
                                        </td>

                                        <td>

                                            <span class="badge bg-light text-dark distance-badge">

                                                Up to
                                                {{ number_format($price->max_distance_km, 2) }}
                                                km

                                            </span>

                                        </td>

                                        <td>

                                            <strong>

                                                ₹{{ number_format($price->amount, 2) }}

                                            </strong>

                                        </td>

                                        <td>

                                            @if($price->status)

                                                <span class="badge bg-success">
                                                    Active
                                                </span>

                                            @else

                                                <span class="badge bg-danger">
                                                    Inactive
                                                </span>

                                            @endif

                                        </td>

                                        <td>

                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#editPrice{{ $price->id }}">

                                                <i data-feather="edit" class="me-1"></i>

                                                Edit

                                            </button>

                                            <form
                                                action="{{ route('admin.ambulances.prices.destroy', [$ambulance->id, $price->id]) }}"
                                                method="POST" class="d-inline">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this price?')">

                                                    <i data-feather="trash-2"></i>

                                                </button>

                                            </form>

                                        </td>

                                    </tr>

                                    {{-- Edit Modal --}}
                                    <div class="modal fade" id="editPrice{{ $price->id }}" tabindex="-1">

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <h5 class="modal-title">
                                                        Edit Local Price
                                                    </h5>

                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                                </div>

                                                <form
                                                    action="{{ route('admin.ambulances.prices.update', [$ambulance->id, $price->id]) }}"
                                                    method="POST">

                                                    @csrf
                                                    @method('PUT')

                                                    <div class="modal-body">

                                                        <input type="hidden" name="trip_type" value="local">

                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                Maximum Distance (KM)
                                                            </label>

                                                            <input type="number" name="max_distance_km" class="form-control"
                                                                step="0.01" min="0.01" value="{{ $price->max_distance_km }}"
                                                                required>

                                                        </div>

                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                Amount
                                                            </label>

                                                            <div class="input-group">

                                                                <span class="input-group-text">
                                                                    ₹
                                                                </span>

                                                                <input type="number" name="amount" class="form-control"
                                                                    step="0.01" min="0" value="{{ $price->amount }}" required>

                                                            </div>

                                                        </div>

                                                        <div class="form-check form-switch">

                                                            <input type="hidden" name="status" value="0">

                                                            <input type="checkbox" name="status" value="1"
                                                                class="form-check-input" {{ $price->status ? 'checked' : '' }}>

                                                            <label class="form-check-label">
                                                                Active
                                                            </label>

                                                        </div>

                                                    </div>

                                                    <div class="modal-footer">

                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                                                            Cancel

                                                        </button>

                                                        <button type="submit" class="btn btn-primary">

                                                            Update Price

                                                        </button>

                                                    </div>

                                                </form>

                                            </div>

                                        </div>

                                    </div>

                                @empty

                                    <tr>

                                        <td colspan="5" class="text-center py-4">

                                            No local pricing configured.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- Outstation Pricing --}}
            <div class="card pricing-card mb-4">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <div>

                        <h5>Outstation Pricing</h5>

                        <small class="text-muted">
                            Pricing for outstation trips
                        </small>

                    </div>

                    <span class="badge bg-primary">

                        {{ $outstationPrices->count() }} Slabs

                    </span>

                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th width="80">#</th>

                                    <th>Distance</th>

                                    <th>Amount</th>

                                    <th>Status</th>

                                    <th width="180">Action</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($outstationPrices as $key => $price)

                                    <tr>

                                        <td>
                                            {{ $key + 1 }}
                                        </td>

                                        <td>

                                            <span class="badge bg-light text-dark distance-badge">

                                                Up to
                                                {{ number_format($price->max_distance_km, 2) }}
                                                km

                                            </span>

                                        </td>

                                        <td>

                                            <strong>

                                                ₹{{ number_format($price->amount, 2) }}

                                            </strong>

                                        </td>

                                        <td>

                                            @if($price->status)

                                                <span class="badge bg-success">
                                                    Active
                                                </span>

                                            @else

                                                <span class="badge bg-danger">
                                                    Inactive
                                                </span>

                                            @endif

                                        </td>

                                        <td>

                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#editPrice{{ $price->id }}">

                                                <i data-feather="edit" class="me-1"></i>

                                                Edit

                                            </button>

                                            <form
                                                action="{{ route('admin.ambulances.prices.destroy', [$ambulance->id, $price->id]) }}"
                                                method="POST" class="d-inline">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this price?')">

                                                    <i data-feather="trash-2"></i>

                                                </button>

                                            </form>

                                        </td>

                                    </tr>

                                    {{-- Edit Modal --}}
                                    <div class="modal fade" id="editPrice{{ $price->id }}" tabindex="-1">

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <h5 class="modal-title">
                                                        Edit Outstation Price
                                                    </h5>

                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                                </div>

                                                <form
                                                    action="{{ route('admin.ambulances.prices.update', [$ambulance->id, $price->id]) }}"
                                                    method="POST">

                                                    @csrf
                                                    @method('PUT')

                                                    <div class="modal-body">

                                                        <input type="hidden" name="trip_type" value="outstation">

                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                Maximum Distance (KM)
                                                            </label>

                                                            <input type="number" name="max_distance_km" class="form-control"
                                                                step="0.01" min="0.01" value="{{ $price->max_distance_km }}"
                                                                required>

                                                        </div>

                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                Amount
                                                            </label>

                                                            <div class="input-group">

                                                                <span class="input-group-text">
                                                                    ₹
                                                                </span>

                                                                <input type="number" name="amount" class="form-control"
                                                                    step="0.01" min="0" value="{{ $price->amount }}" required>

                                                            </div>

                                                        </div>

                                                        <div class="form-check form-switch">

                                                            <input type="hidden" name="status" value="0">

                                                            <input type="checkbox" name="status" value="1"
                                                                class="form-check-input" {{ $price->status ? 'checked' : '' }}>

                                                            <label class="form-check-label">
                                                                Active
                                                            </label>

                                                        </div>

                                                    </div>

                                                    <div class="modal-footer">

                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                                                            Cancel

                                                        </button>

                                                        <button type="submit" class="btn btn-primary">

                                                            Update Price

                                                        </button>

                                                    </div>

                                                </form>

                                            </div>

                                        </div>

                                    </div>

                                @empty

                                    <tr>

                                        <td colspan="5" class="text-center py-4">

                                            No outstation pricing configured.

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

            if (typeof feather !== "undefined") {
                feather.replace();
            }

        });

    </script>

@endsection