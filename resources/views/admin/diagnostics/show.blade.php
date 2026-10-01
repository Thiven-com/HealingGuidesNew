@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            <!-- Page Header -->
            <div class="page-header">

                <div class="page-title">

                    <h4>Diagnostic Details</h4>

                    <h6>View Diagnostic Center Profile</h6>

                </div>

                <div class="page-btn d-flex gap-2">

                    <a href="{{ route('admin.diagnostics.edit', $diagnostic) }}" class="btn btn-primary">

                        <i class="ti ti-edit me-1"></i>

                        Edit

                    </a>

                    <a href="{{ route('admin.diagnostics.index') }}" class="btn btn-secondary">

                        <i class="ti ti-arrow-left me-1"></i>

                        Back

                    </a>

                </div>

            </div>

            <div class="row">

                <!-- Left Section -->
                <div class="col-lg-4">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body text-center">

                            @if($diagnostic->logo)

                                <img src="{{ asset($diagnostic->logo) }}" class="rounded-circle border mb-3" width="150"
                                    height="150" style="object-fit:cover;">

                            @else

                                <div class="avatar avatar-xl bg-primary text-white rounded-circle mx-auto mb-3">

                                    <i class="ti ti-microscope fs-40"></i>

                                </div>

                            @endif

                            <h4 class="mb-1">

                                {{ $diagnostic->diagnostic_name }}

                            </h4>

                            @if($diagnostic->status)

                                <span class="badge bg-success">

                                    Active

                                </span>

                            @else

                                <span class="badge bg-danger">

                                    Inactive

                                </span>

                            @endif

                            <hr>

                            <div class="text-start">

                                <p>

                                    <strong>Diagnostic Code</strong>

                                    <br>

                                    {{ $diagnostic->diagnostic_code }}

                                </p>

                                <p>

                                    <strong>Registration Number</strong>

                                    <br>

                                    {{ $diagnostic->registration_number ?? '-' }}

                                </p>

                            </div>

                        </div>

                    </div>


                    <!-- Banner -->

                    @if($diagnostic->banner)

                        <div class="card border-0 shadow-sm">

                            <div class="card-header">

                                <h5 class="mb-0">

                                    Diagnostic Banner

                                </h5>

                            </div>

                            <div class="card-body">

                                <img src="{{ asset($diagnostic->banner) }}" class="img-fluid rounded">

                            </div>

                        </div>

                    @endif

                </div>

                <!-- Right Section -->
                <div class="col-lg-8">

                    <!-- Contact -->

                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header">

                            <h5 class="mb-0">

                                Contact Information

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-4 mb-3">

                                    <strong>Email</strong>

                                    <br>

                                    {{ $diagnostic->email ?? '-' }}

                                </div>

                                <div class="col-md-4 mb-3">

                                    <strong>Mobile</strong>

                                    <br>

                                    {{ $diagnostic->mobile }}

                                </div>

                                <div class="col-md-4 mb-3">

                                    <strong>Phone</strong>

                                    <br>

                                    {{ $diagnostic->phone ?? '-' }}

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Address -->

                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header">

                            <h5 class="mb-0">

                                Address Information

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-12 mb-3">

                                    <strong>Address</strong>

                                    <br>

                                    {{ $diagnostic->address ?? '-' }}

                                </div>

                                <div class="col-md-3">

                                    <strong>Country</strong>

                                    <br>

                                    {{ $diagnostic->country ?? '-' }}

                                </div>

                                <div class="col-md-3">

                                    <strong>State</strong>

                                    <br>

                                    {{ $diagnostic->state ?? '-' }}

                                </div>

                                <div class="col-md-3">

                                    <strong>City</strong>

                                    <br>

                                    {{ $diagnostic->city ?? '-' }}

                                </div>

                                <div class="col-md-3">

                                    <strong>Pincode</strong>

                                    <br>

                                    {{ $diagnostic->pincode ?? '-' }}

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Working Hours -->

                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header">

                            <h5 class="mb-0">

                                Working Hours

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-3">

                                    <strong>Opening Time</strong>

                                    <br>

                                    {{ $diagnostic->opening_time ?? '-' }}

                                </div>

                                <div class="col-md-3">

                                    <strong>Closing Time</strong>

                                    <br>

                                    {{ $diagnostic->closing_time ?? '-' }}

                                </div>

                                <div class="col-md-3">

                                    <strong>Home Collection</strong>

                                    <br>

                                    @if($diagnostic->home_collection)

                                        <span class="badge bg-success">

                                            Available

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            Not Available

                                        </span>

                                    @endif

                                </div>

                                <div class="col-md-3">

                                    <strong>Status</strong>

                                    <br>

                                    @if($diagnostic->status)

                                        <span class="badge bg-success">

                                            Active

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            Inactive

                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Location -->

                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header">

                            <h5 class="mb-0">

                                Location

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-6">

                                    <strong>Latitude</strong>

                                    <br>

                                    {{ $diagnostic->latitude ?? '-' }}

                                </div>

                                <div class="col-md-6">

                                    <strong>Longitude</strong>

                                    <br>

                                    {{ $diagnostic->longitude ?? '-' }}

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- ============================================================ --}}
                    {{-- EDIT LAB TEST MODALS --}}
                    {{-- ============================================================ --}}

                    @foreach($diagnosticLabTests as $diagnosticLabTest)

                                    <div class="modal fade" id="editLabTestModal{{ $diagnosticLabTest->id }}" tabindex="-1"
                                        aria-labelledby="editLabTestModalLabel{{ $diagnosticLabTest->id }}" aria-hidden="true">

                                        <div class="modal-dialog modal-lg modal-dialog-centered">

                                            <div class="modal-content">

                                                <form action="{{ route(
                            'admin.diagnostic-lab-tests.update',
                            $diagnosticLabTest->id
                        ) }}" method="POST">

                                                    @csrf
                                                    @method('PUT')

                                                    {{-- HEADER --}}
                                                    <div class="modal-header">

                                                        <h5 class="modal-title" id="editLabTestModalLabel{{ $diagnosticLabTest->id }}">
                                                            <i class="ti ti-flask me-2"></i>
                                                            Edit Lab Test
                                                        </h5>

                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                                    </div>

                                                    {{-- BODY --}}
                                                    <div class="modal-body">

                                                        {{-- Lab Test --}}
                                                        <div class="mb-4">

                                                            <label class="form-label fw-semibold">
                                                                Lab Test
                                                            </label>

                                                            <input type="text" class="form-control"
                                                                value="{{ $diagnosticLabTest->labTest->test_name ?? '-' }}" readonly>

                                                            <input type="hidden" name="lab_test_id"
                                                                value="{{ $diagnosticLabTest->lab_test_id }}">

                                                        </div>

                                                        <div class="row">

                                                            {{-- Price --}}
                                                            <div class="col-md-6 mb-3">

                                                                <label class="form-label fw-semibold">
                                                                    Price
                                                                </label>

                                                                <div class="input-group">

                                                                    <span class="input-group-text">
                                                                        ₹
                                                                    </span>

                                                                    <input type="number" name="price" step="0.01" min="0"
                                                                        class="form-control" value="{{ $diagnosticLabTest->price }}"
                                                                        placeholder="Enter price">

                                                                </div>

                                                            </div>

                                                            {{-- Offer Price --}}
                                                            <div class="col-md-6 mb-3">

                                                                <label class="form-label fw-semibold">
                                                                    Offer Price
                                                                </label>

                                                                <div class="input-group">

                                                                    <span class="input-group-text">
                                                                        ₹
                                                                    </span>

                                                                    <input type="number" name="offer_price" step="0.01" min="0"
                                                                        class="form-control" value="{{ $diagnosticLabTest->offer_price }}"
                                                                        placeholder="Enter offer price">

                                                                </div>

                                                            </div>

                                                            {{-- Report Time --}}
                                                            <div class="col-md-6 mb-3">

                                                                <label class="form-label fw-semibold">
                                                                    Report Time
                                                                </label>

                                                                <input type="text" name="report_time" class="form-control"
                                                                    value="{{ $diagnosticLabTest->report_time }}" placeholder="Example: 2">

                                                            </div>

                                                            {{-- Report Time Type --}}
                                                            <div class="col-md-6 mb-3">

                                                                <label class="form-label fw-semibold">
                                                                    Report Time Type
                                                                </label>

                                                                <select name="report_time_type" class="form-select">

                                                                    <option value="">
                                                                        Select Type
                                                                    </option>

                                                                    <option value="Minutes"
                                                                        @selected($diagnosticLabTest->report_time_type === 'Minutes')>
                                                                        Minutes
                                                                    </option>

                                                                    <option value="Hours"
                                                                        @selected($diagnosticLabTest->report_time_type === 'Hours')>
                                                                        Hours
                                                                    </option>

                                                                    <option value="Days"
                                                                        @selected($diagnosticLabTest->report_time_type === 'Days')>
                                                                        Days
                                                                    </option>

                                                                </select>

                                                            </div>

                                                            {{-- Home Collection --}}
                                                            <div class="col-md-6 mb-3">

                                                                <label class="form-label fw-semibold">
                                                                    Home Collection
                                                                </label>

                                                                <div class="form-check form-switch">

                                                                    <input type="hidden" name="home_collection" value="0">

                                                                    <input type="checkbox" class="form-check-input" role="switch"
                                                                        name="home_collection" value="1"
                                                                        id="homeCollection{{ $diagnosticLabTest->id }}" @checked((int) $diagnosticLabTest->home_collection === 1)>

                                                                    <label class="form-check-label"
                                                                        for="homeCollection{{ $diagnosticLabTest->id }}">
                                                                        Available
                                                                    </label>

                                                                </div>

                                                            </div>
                                                            {{-- Free Ambulances --}}
<div class="col-md-6 mb-3">

    <label class="form-label fw-semibold">
        Free Ambulance
    </label>

    <div class="form-check form-switch">

        {{-- Default disabled --}}
        <input type="hidden" name="free_ambulances" value="0">

        {{-- Enable = 1 --}}
        <input type="checkbox"
               class="form-check-input"
               role="switch"
               name="free_ambulances"
               value="1"
               id="freeAmbulances{{ $diagnosticLabTest->id }}"
               @checked((int) $diagnosticLabTest->free_ambulances === 1)>

        <label class="form-check-label"
               for="freeAmbulances{{ $diagnosticLabTest->id }}">
            Available
        </label>

    </div>

</div>

                                                            {{-- Status --}}
                                                            <div class="col-md-6 mb-3">

                                                                <label class="form-label fw-semibold">
                                                                    Status
                                                                </label>

                                                                <div class="form-check form-switch">

                                                                    <input type="hidden" name="status" value="0">

                                                                    <input type="checkbox" class="form-check-input" role="switch"
                                                                        name="status" value="1" id="labStatus{{ $diagnosticLabTest->id }}"
                                                                        @checked((int) $diagnosticLabTest->status === 1)>

                                                                    <label class="form-check-label"
                                                                        for="labStatus{{ $diagnosticLabTest->id }}">
                                                                        Active
                                                                    </label>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    {{-- FOOTER --}}
                                                    <div class="modal-footer">

                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                            Cancel
                                                        </button>

                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="ti ti-device-floppy me-1"></i>
                                                            Update Lab Test
                                                        </button>

                                                    </div>

                                                </form>

                                            </div>

                                        </div>

                                    </div>

                    @endforeach

                </div>

            </div>
             <!-- Lab Tests -->
                    <!-- Lab Tests -->
                    <!-- Lab Tests -->
                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header d-flex align-items-center justify-content-between">

                            <h5 class="mb-0">
                                <i class="ti ti-flask me-2"></i>
                                Diagnostic Lab Tests
                            </h5>

                            <span class="badge bg-primary">
                                {{ $diagnosticLabTests->count() }} Tests
                            </span>

                        </div>

                        <div class="card-body">

                            @if($diagnosticLabTests->count() > 0)

                                <div class="table-responsive">

                                    <table class="table table-bordered table-hover align-middle mb-0">

                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Lab Test</th>
                                                <th>Price</th>
                                                <th>Offer Price</th>
                                                <th>Report Time</th>
                                                <th>Report Type</th>
                                                <th>Home Collection</th>
                                                <th>Free Ambulance
                                                <th>Status</th>
                                                <th width="130">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @foreach($diagnosticLabTests as $key => $diagnosticLabTest)

                                                                            <tr>

                                                                                {{-- # --}}
                                                                                <td>
                                                                                    {{ $key + 1 }}
                                                                                </td>

                                                                                {{-- Lab Test --}}
                                                                                <td>
                                                                                    <strong>
                                                                                        {{ $diagnosticLabTest->labTest->test_name ?? '-' }}
                                                                                    </strong>

                                                                                    <br>

                                                                                    <small class="text-muted">
                                                                                        Lab Test ID:
                                                                                        {{ $diagnosticLabTest->lab_test_id }}
                                                                                    </small>
                                                                                </td>

                                                                                {{-- Price --}}
                                                                                <td>
                                                                                    @if($diagnosticLabTest->price !== null)
                                                                                        ₹{{ number_format((float) $diagnosticLabTest->price, 2) }}
                                                                                    @else
                                                                                        <span class="text-muted">-</span>
                                                                                    @endif
                                                                                </td>

                                                                                {{-- Offer Price --}}
                                                                                <td>
                                                                                    @if($diagnosticLabTest->offer_price !== null)
                                                                                        <strong class="text-success">
                                                                                            ₹{{ number_format((float) $diagnosticLabTest->offer_price, 2) }}
                                                                                        </strong>
                                                                                    @else
                                                                                        <span class="text-muted">-</span>
                                                                                    @endif
                                                                                </td>

                                                                                {{-- Report Time --}}
                                                                                <td>
                                                                                    {{ $diagnosticLabTest->report_time ?? '-' }}
                                                                                </td>

                                                                                {{-- Report Type --}}
                                                                                <td>
                                                                                    {{ $diagnosticLabTest->report_time_type ?? '-' }}
                                                                                </td>

                                                                                {{-- Home Collection --}}
                                                                                <td>
                                                                                    @if((int) $diagnosticLabTest->home_collection === 1)
                                                                                        <span class="badge bg-success">
                                                                                            Available
                                                                                        </span>
                                                                                    @else
                                                                                        <span class="badge bg-danger">
                                                                                            Not Available
                                                                                        </span>
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    {{-- {{ $diagnosticLabTest->free_ambulances ? 'available' : 'not available' }} --}}
                                                                                    @if((int) $diagnosticLabTest->free_ambulances === 1)
                                                                                        <span class="badge bg-success">
                                                                                            Available
                                                                                        </span>
                                                                                    @else
                                                                                        <span class="badge bg-danger">
                                                                                            Not Available
                                                                                        </span>
                                                                                    @endif
                                                                                </td>

                                                                                {{-- Status --}}
                                                                                <td>
                                                                                    @if((int) $diagnosticLabTest->status === 1)
                                                                                        <span class="badge bg-success">
                                                                                            Active
                                                                                        </span>
                                                                                    @else
                                                                                        <span class="badge bg-danger">
                                                                                            Inactive
                                                                                        </span>
                                                                                    @endif
                                                                                </td>

                                                                                {{-- Actions --}}
                                                                                <td>
                                                                                    <div class="d-flex gap-2">

                                                                                        {{-- EDIT --}}
                                                                                        <button type="button" class="btn btn-sm btn-primary" title="Edit"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#editLabTestModal{{ $diagnosticLabTest->id }}">
                                                                                            <i class="ti ti-edit"></i>
                                                                                        </button>

                                                                                        {{-- DELETE --}}
                                                                                        <form action="{{ route(
                                                    'admin.diagnostic-lab-tests.destroy',
                                                    $diagnosticLabTest->id
                                                ) }}" method="POST" class="d-inline"
                                                                                            onsubmit="return confirm('Are you sure you want to delete this lab test?');">
                                                                                            @csrf
                                                                                            @method('DELETE')

                                                                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
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

                                <div class="text-center py-4">

                                    <i class="ti ti-flask-off fs-40 text-muted"></i>

                                    <p class="text-muted mt-2 mb-0">
                                        No lab tests found for this diagnostic.
                                    </p>

                                </div>

                            @endif

                        </div>

                    </div>

        </div>

    </div>

    

@endsection