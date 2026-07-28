@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            <!-- Page Header -->
            <div class="page-header">

                <div class="page-title">
                    <h4>Hospital Details</h4>
                    <h6>View Hospital Profile</h6>
                </div>

                <div class="page-btn d-flex gap-2">

                    <a href="{{ route('admin.hospitals.edit', $hospital) }}" class="btn btn-primary">

                        <i class="ti ti-edit me-1"></i>

                        Edit

                    </a>

                    <a href="{{ route('admin.hospitals.index') }}" class="btn btn-secondary">

                        <i class="ti ti-arrow-left me-1"></i>

                        Back

                    </a>

                </div>

            </div>

            <div class="row">

                <!-- Left -->

                <div class="col-lg-4">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body text-center">

                            @if($hospital->logo)

                                <img src="{{ asset($hospital->logo) }}" class="rounded-circle border mb-3" width="150"
                                    height="150" style="object-fit:cover;">

                            @else

                                <div class="avatar avatar-xl bg-primary text-white rounded-circle mx-auto mb-3">

                                    <i class="ti ti-building-hospital fs-40"></i>

                                </div>

                            @endif

                            <h4 class="mb-1">

                                {{ $hospital->hospital_name }}

                            </h4>

                            <p class="text-muted">

                                {{ $hospital->hospital_type }}

                            </p>

                            @if($hospital->status)

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

                                    <strong>Hospital Code</strong>

                                    <br>

                                    {{ $hospital->hospital_code }}

                                </p>

                                <p>

                                    <strong>Registration No</strong>

                                    <br>

                                    {{ $hospital->registration_number ?? '-' }}

                                </p>

                                <p>

                                    <strong>GST Number</strong>

                                    <br>

                                    {{ $hospital->gst_number ?? '-' }}

                                </p>

                                <p>

                                    <strong>PAN Number</strong>

                                    <br>

                                    {{ $hospital->pan_number ?? '-' }}

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Right -->

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

                                <div class="col-md-6 mb-3">

                                    <strong>Email</strong>

                                    <br>

                                    {{ $hospital->email ?? '-' }}

                                </div>

                                <div class="col-md-6 mb-3">

                                    <strong>Mobile</strong>

                                    <br>

                                    {{ $hospital->mobile }}

                                </div>

                                <div class="col-md-6 mb-3">

                                    <strong>Phone</strong>

                                    <br>

                                    {{ $hospital->phone ?? '-' }}

                                </div>

                                <div class="col-md-6 mb-3">

                                    <strong>Website</strong>

                                    <br>

                                    @if($hospital->website)

                                        <a href="{{ $hospital->website }}" target="_blank">

                                            {{ $hospital->website }}

                                        </a>

                                    @else

                                        -

                                    @endif

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

                                    {{ $hospital->address }}

                                </div>

                                <div class="col-md-3">

                                    <strong>Country</strong>

                                    <br>

                                    {{ $hospital->country }}

                                </div>

                                <div class="col-md-3">

                                    <strong>State</strong>

                                    <br>

                                    {{ $hospital->state }}

                                </div>

                                <div class="col-md-3">

                                    <strong>City</strong>

                                    <br>

                                    {{ $hospital->city }}

                                </div>

                                <div class="col-md-3">

                                    <strong>Pincode</strong>

                                    <br>

                                    {{ $hospital->pincode }}

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

                                <div class="col-md-4">

                                    <strong>Opening Time</strong>

                                    <br>

                                    {{ $hospital->opening_time ?? '-' }}

                                </div>

                                <div class="col-md-4">

                                    <strong>Closing Time</strong>

                                    <br>

                                    {{ $hospital->closing_time ?? '-' }}

                                </div>

                                <div class="col-md-4">

                                    <strong>Emergency</strong>

                                    <br>

                                    @if($hospital->emergency_available)

                                        <span class="badge bg-success">

                                            Available

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            Not Available

                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Banner -->

                    @if($hospital->banner)

                        <div class="card border-0 shadow-sm">

                            <div class="card-header">

                                <h5 class="mb-0">

                                    Hospital Banner

                                </h5>

                            </div>

                            <div class="card-body">

                                <img src="{{ asset($hospital->banner) }}" class="img-fluid rounded">

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

@endsection