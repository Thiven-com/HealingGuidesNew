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

            </div>

        </div>

    </div>

@endsection