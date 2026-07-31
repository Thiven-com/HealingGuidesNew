@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            <!-- Page Header -->

            <div class="page-header">

                <div class="page-title">

                    <h4>Ambulance Type Details</h4>

                    <h6>View Ambulance Type Information</h6>

                </div>

                <div class="page-btn">

                    <a href="{{ route('admin.ambulance-types.index') }}" class="btn btn-secondary">

                        <i class="ti ti-arrow-left me-1"></i>

                        Back

                    </a>

                </div>

            </div>

            <div class="row">

                <!-- Left Side -->

                <div class="col-lg-4">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body text-center">

                            <img src="{{ $ambulanceType->image ? asset($ambulanceType->image) : asset('assets/img/no-image.png') }}"
                                class="img-thumbnail mb-3" style="width:220px;height:220px;object-fit:contain;">

                            <h4 class="mb-1">

                                {{ $ambulanceType->ambulance_type_name }}

                            </h4>

                            <p class="text-muted mb-3">

                                {{ $ambulanceType->ambulance_type_code }}

                            </p>

                            @if($ambulanceType->status)

                                <span class="badge bg-success fs-14">

                                    Active

                                </span>

                            @else

                                <span class="badge bg-danger fs-14">

                                    Inactive

                                </span>

                            @endif

                        </div>

                    </div>

                </div>

                <!-- Right Side -->

                <div class="col-lg-8">

                    <!-- Basic Information -->

                    <div class="card border-0 shadow-sm">

                        <div class="card-header">

                            <h5 class="mb-0">

                                <i class="ti ti-info-circle me-2"></i>

                                Basic Information

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-6 mb-4">

                                    <label class="fw-semibold text-muted">

                                        Ambulance Type Name

                                    </label>

                                    <p class="mb-0 mt-2">

                                        {{ $ambulanceType->ambulance_type_name }}

                                    </p>

                                </div>

                                <div class="col-md-6 mb-4">

                                    <label class="fw-semibold text-muted">

                                        Ambulance Type Code

                                    </label>

                                    <p class="mb-0 mt-2">

                                        {{ $ambulanceType->ambulance_type_code }}

                                    </p>

                                </div>

                                <div class="col-md-12 mb-4">

                                    <label class="fw-semibold text-muted">

                                        Slug

                                    </label>

                                    <p class="mb-0 mt-2">

                                        {{ $ambulanceType->slug }}

                                    </p>

                                </div>
                                <div class="col-md-12 mb-4">

                                    <label class="fw-semibold text-muted">

                                        Description

                                    </label>

                                    <p class="mb-0 mt-2">

                                        {{ $ambulanceType->description ?: 'N/A' }}

                                    </p>

                                </div>

                                <div class="col-md-6 mb-4">

                                    <label class="fw-semibold text-muted">

                                        Status

                                    </label>

                                    <p class="mb-0 mt-2">

                                        @if($ambulanceType->status)

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

                                <div class="col-md-6 mb-4">

                                    <label class="fw-semibold text-muted">

                                        Created At

                                    </label>

                                    <p class="mb-0 mt-2">

                                        {{ $ambulanceType->created_at ? $ambulanceType->created_at->format('d M Y, h:i A') : '-' }}

                                    </p>

                                </div>

                                <div class="col-md-6 mb-4">

                                    <label class="fw-semibold text-muted">

                                        Last Updated

                                    </label>

                                    <p class="mb-0 mt-2">

                                        {{ $ambulanceType->updated_at ? $ambulanceType->updated_at->format('d M Y, h:i A') : '-' }}

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>
                    <!-- Action Card -->

                    <div class="card border-0 shadow-sm mt-4">

                        <div class="card-body">

                            <div class="d-flex justify-content-end gap-2">

                                <a href="{{ route('admin.ambulance-types.index') }}" class="btn btn-light">

                                    <i class="ti ti-arrow-left me-1"></i>

                                    Back

                                </a>

                                <a href="{{ route('admin.ambulance-types.edit', $ambulanceType->id) }}"
                                    class="btn btn-primary">

                                    <i class="ti ti-edit me-1"></i>

                                    Edit Ambulance Type

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection