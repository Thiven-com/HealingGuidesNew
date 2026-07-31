@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            <!-- Page Header -->

            <div class="page-header">

                <div class="page-title">

                    <h4>Lab Test Details</h4>

                    <h6>View Lab Test Information</h6>

                </div>

                <div class="page-btn">

                    <a href="{{ route('admin.lab-tests.index') }}" class="btn btn-secondary">

                        <i class="ti ti-arrow-left me-1"></i>

                        Back

                    </a>

                </div>

            </div>


            <!-- Basic Information -->

            <div class="card border-0 shadow-sm">

                <div class="card-header">

                    <h5 class="mb-0">

                        <i class="ti ti-flask me-2"></i>

                        Lab Test Information

                    </h5>

                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-lg-3 text-center mb-4">

                            @if($labTest->image)

                                <img src="{{ asset($labTest->image) }}" class="img-thumbnail"
                                    style="width:180px;height:180px;object-fit:contain;">

                            @else

                                <img src="{{ asset('assets/img/no-image.png') }}" class="img-thumbnail"
                                    style="width:180px;height:180px;object-fit:contain;">

                            @endif

                        </div>

                        <div class="col-lg-9">

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <strong>Test Name</strong>

                                    <p>{{ $labTest->test_name }}</p>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <strong>Test Code</strong>

                                    <p>

                                        <span class="badge bg-primary">

                                            {{ $labTest->test_code }}

                                        </span>

                                    </p>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <strong>Slug</strong>

                                    <p>{{ $labTest->slug }}</p>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <strong>Sample Type</strong>

                                    <p>{{ $labTest->sample_type }}</p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
            <!-- Test Details -->

            <div class="card border-0 shadow-sm mt-4">

                <div class="card-header">

                    <h5 class="mb-0">

                        <i class="ti ti-notes me-2"></i>

                        Test Details

                    </h5>

                </div>

                <div class="card-body">

                    <div class="row">

                        <!-- Description -->

                        <div class="col-lg-12 mb-4">

                            <strong>Description</strong>

                            <div class="border rounded p-3 bg-light mt-2">

                                {!! $labTest->description ?: '<span class="text-muted">No description available.</span>' !!}

                            </div>

                        </div>

                        <!-- Preparation -->

                        <div class="col-lg-12 mb-4">

                            <strong>Preparation</strong>

                            <div class="border rounded p-3 bg-light mt-2">

                                {!! $labTest->preparation ?: '<span class="text-muted">No preparation required.</span>' !!}

                            </div>

                        </div>

                        <!-- Sample Type -->

                        <div class="col-md-4 mb-3">

                            <strong>Sample Type</strong>

                            <p class="mt-2">

                                <span class="badge bg-info">

                                    {{ $labTest->sample_type }}

                                </span>

                            </p>

                        </div>

                        <!-- Report Time -->

                        <div class="col-md-4 mb-3">

                            <strong>Report Time</strong>

                            <p class="mt-2">

                                {{ $labTest->report_time }}

                                {{ $labTest->report_time_type }}

                            </p>

                        </div>

                        <!-- Fasting Required -->

                        <div class="col-md-4 mb-3">

                            <strong>Fasting Required</strong>

                            <p class="mt-2">

                                @if($labTest->fasting_required)

                                    <span class="badge bg-success">

                                        Yes

                                    </span>

                                @else

                                    <span class="badge bg-danger">

                                        No

                                    </span>

                                @endif

                            </p>

                        </div>

                        <!-- Home Collection -->

                        <div class="col-md-6 mb-3">

                            <strong>Home Collection</strong>

                            <p class="mt-2">

                                @if($labTest->home_collection)

                                    <span class="badge bg-success">

                                        Available

                                    </span>

                                @else

                                    <span class="badge bg-danger">

                                        Not Available

                                    </span>

                                @endif

                            </p>

                        </div>

                        <!-- Status -->

                        <div class="col-md-6 mb-3">

                            <strong>Status</strong>

                            <p class="mt-2">

                                @if($labTest->status)

                                    <span class="badge bg-success">

                                        Active

                                    </span>

                                @else

                                    <span class="badge bg-danger">

                                        Inactive

                                    </span>

                                @endif

                            </p>

                        </div>

                    </div>

                </div>

            </div>
            <!-- Audit Information -->

            <div class="card border-0 shadow-sm mt-4">

                <div class="card-header">

                    <h5 class="mb-0">

                        <i class="ti ti-info-circle me-2"></i>

                        Audit Information

                    </h5>

                </div>

                <div class="card-body">

                    <div class="row">

                        <!-- Created At -->

                        <div class="col-md-6 mb-3">

                            <strong>Created At</strong>

                            <p class="mt-2">

                                {{ $labTest->created_at ? $labTest->created_at->format('d M Y, h:i A') : '-' }}

                            </p>

                        </div>

                        <!-- Updated At -->

                        <div class="col-md-6 mb-3">

                            <strong>Last Updated</strong>

                            <p class="mt-2">

                                {{ $labTest->updated_at ? $labTest->updated_at->format('d M Y, h:i A') : '-' }}

                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Action Buttons -->

            <div class="card border-0 shadow-sm mt-4">

                <div class="card-body">

                    <div class="d-flex justify-content-end gap-2">

                        <a href="{{ route('admin.lab-tests.index') }}" class="btn btn-light">

                            <i class="ti ti-arrow-left me-1"></i>

                            Back

                        </a>

                        <a href="{{ route('admin.lab-tests.edit', $labTest->id) }}" class="btn btn-primary">

                            <i class="ti ti-edit me-1"></i>

                            Edit Lab Test

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection