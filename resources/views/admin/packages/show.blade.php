<?php $page = 'packages'; ?>

@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            {{-- =========================================================
            PAGE HEADER
            ========================================================== --}}

            <div class="page-header">

                <div class="add-item d-flex">

                    <div class="page-title">

                        <h4>Package Details</h4>

                        <h6>View package information and benefits</h6>

                    </div>

                </div>

                <div class="page-btn d-flex gap-2">

                    <a href="{{ route('admin.packages.index') }}" class="btn btn-light">

                        <i class="ti ti-arrow-left me-1"></i>

                        Back

                    </a>

                    <a href="{{ route('admin.packages.edit', $package->id) }}" class="btn btn-primary">

                        <i class="ti ti-edit me-1"></i>

                        Edit Package

                    </a>

                </div>

            </div>


            {{-- =========================================================
            ALERTS
            ========================================================== --}}

            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">

                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            @if(session('error'))

                <div class="alert alert-danger alert-dismissible fade show">

                    {{ session('error') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            <div class="row">

                {{-- =====================================================
                LEFT COLUMN
                ====================================================== --}}

                <div class="col-lg-8">


                    {{-- =================================================
                    PACKAGE OVERVIEW
                    ================================================== --}}

                    <div class="card">

                        <div class="card-header">

                            <div class="d-flex justify-content-between align-items-center">

                                <h5 class="card-title mb-0">

                                    <i class="ti ti-package me-1"></i>

                                    Package Information

                                </h5>


                                @if($package->status)

                                    <span class="badge bg-success">

                                        <i class="ti ti-check me-1"></i>

                                        Active

                                    </span>

                                @else

                                    <span class="badge bg-danger">

                                        <i class="ti ti-x me-1"></i>

                                        Inactive

                                    </span>

                                @endif

                            </div>

                        </div>


                        <div class="card-body">

                            <div class="row">


                                {{-- Package Name --}}

                                <div class="col-md-6 mb-4">

                                    <div class="text-muted mb-1">

                                        Package Name

                                    </div>

                                    <h5 class="mb-0">

                                        {{ $package->name ?? '-' }}

                                    </h5>

                                </div>


                                {{-- Price --}}

                                <div class="col-md-6 mb-4">

                                    <div class="text-muted mb-1">

                                        Package Price

                                    </div>

                                    <h5 class="mb-0 text-primary">

                                        ₹{{ number_format($package->price ?? 0, 2) }}

                                    </h5>

                                </div>


                                {{-- Duration --}}

                                <div class="col-md-6 mb-4">

                                    <div class="text-muted mb-1">

                                        Duration

                                    </div>

                                    <h5 class="mb-0">

                                        {{ $package->duration_days ?? 0 }}

                                        Days

                                    </h5>

                                </div>


                                {{-- Display Order --}}

                                <div class="col-md-6 mb-4">

                                    <div class="text-muted mb-1">

                                        Display Order

                                    </div>

                                    <h5 class="mb-0">

                                        {{ $package->display_order ?? 0 }}

                                    </h5>

                                </div>


                                {{-- Description --}}

                                <div class="col-md-12">

                                    <div class="text-muted mb-1">

                                        Description

                                    </div>

                                    <div class="border rounded p-3 bg-light">

                                        @if(!empty($package->description))

                                            {!! nl2br(e($package->description)) !!}

                                        @else

                                            <span class="text-muted">

                                                No description available.

                                            </span>

                                        @endif

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                    PACKAGE BENEFITS
                    ================================================== --}}

                    <div class="card">

                        <div class="card-header">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <h5 class="card-title mb-1">

                                        <i class="ti ti-gift me-1"></i>

                                        Package Benefits

                                    </h5>

                                    <small class="text-muted">

                                        Services included with this package

                                    </small>

                                </div>


                                <span class="badge bg-primary">

                                    {{ $package->activeBenefits->count() }}

                                    Benefits

                                </span>

                            </div>

                        </div>


                        <div class="card-body p-0">

                            @if($package->activeBenefits->count() > 0)

                                <div class="table-responsive">

                                    <table class="table table-hover mb-0">

                                        <thead>

                                            <tr>

                                                <th width="70">

                                                    #

                                                </th>

                                                <th>

                                                    Benefit

                                                </th>

                                                <th>

                                                    Quantity

                                                </th>

                                                <th>

                                                    Description

                                                </th>

                                                <th>

                                                    Status

                                                </th>

                                            </tr>

                                        </thead>


                                        <tbody>

                                            @foreach($package->activeBenefits as $index => $benefit)

                                                <tr>

                                                    <td>

                                                        {{ $index + 1 }}

                                                    </td>


                                                    <td>

                                                        <div class="d-flex align-items-center">

                                                            <div class="avatar avatar-sm bg-primary-transparent rounded me-2">

                                                                <i class="ti ti-gift"></i>

                                                            </div>

                                                            <div>

                                                                <h6 class="mb-0">

                                                                    @php

                                                                        $benefitLabels = [

                                                                            'free_consultation' =>
                                                                                'Free Consultation',

                                                                            'free_video_consultation' =>
                                                                                'Free Video Consultation',

                                                                            'free_ambulance' =>
                                                                                'Free Ambulance',

                                                                            'free_home_visit' =>
                                                                                'Free Home Visit',

                                                                            'free_surgery_quote' =>
                                                                                'Free Surgery Quote',

                                                                            'free_medicine' =>
                                                                                'Free Medicine',

                                                                        ];

                                                                        $benefitType =
                                                                            $benefit->benefit_type
                                                                            ?? $benefit->type;

                                                                    @endphp

                                                                    {{ $benefitLabels[$benefitType] ?? ucwords(str_replace('_', ' ', $benefitType)) }}

                                                                </h6>

                                                            </div>

                                                        </div>

                                                    </td>


                                                    <td>

                                                        <span class="badge bg-info">

                                                            {{ $benefit->quantity }}

                                                        </span>

                                                    </td>


                                                    <td>

                                                        @if(!empty($benefit->description))

                                                            {{ $benefit->description }}

                                                        @else

                                                            <span class="text-muted">

                                                                -

                                                            </span>

                                                        @endif

                                                    </td>


                                                    <td>

                                                        @if($benefit->status ?? true)

                                                            <span class="badge bg-success">

                                                                Active

                                                            </span>

                                                        @else

                                                            <span class="badge bg-danger">

                                                                Inactive

                                                            </span>

                                                        @endif

                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>

                            @else

                                <div class="text-center py-5">

                                    <div class="avatar avatar-lg bg-light rounded-circle mx-auto mb-3">

                                        <i class="ti ti-gift fs-2 text-muted"></i>

                                    </div>

                                    <h6>

                                        No Benefits Added

                                    </h6>

                                    <p class="text-muted mb-3">

                                        This package does not have any benefits configured yet.

                                    </p>

                                    <a href="{{ route('admin.packages.edit', $package->id) }}" class="btn btn-primary btn-sm">

                                        <i class="ti ti-plus me-1"></i>

                                        Add Benefits

                                    </a>

                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- =================================================
                    PACKAGE TIMELINE
                    ================================================== --}}

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">

                                <i class="ti ti-clock me-1"></i>

                                Package Timeline

                            </h5>

                        </div>


                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-6">

                                    <div class="d-flex align-items-center">

                                        <div class="avatar avatar-sm bg-success-transparent rounded me-2">

                                            <i class="ti ti-calendar-plus"></i>

                                        </div>

                                        <div>

                                            <small class="text-muted d-block">

                                                Created At

                                            </small>

                                            <strong>

                                                {{ $package->created_at
        ? $package->created_at->format('d M Y, h:i A')
        : '-' }}

                                            </strong>

                                        </div>

                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <div class="d-flex align-items-center">

                                        <div class="avatar avatar-sm bg-warning-transparent rounded me-2">

                                            <i class="ti ti-calendar-event"></i>

                                        </div>

                                        <div>

                                            <small class="text-muted d-block">

                                                Last Updated

                                            </small>

                                            <strong>

                                                {{ $package->updated_at
        ? $package->updated_at->format('d M Y, h:i A')
        : '-' }}

                                            </strong>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =====================================================
                RIGHT COLUMN
                ====================================================== --}}

                <div class="col-lg-4">


                    {{-- =================================================
                    PACKAGE IMAGE
                    ================================================== --}}

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">

                                <i class="ti ti-photo me-1"></i>

                                Package Image

                            </h5>

                        </div>


                        <div class="card-body text-center">

                            @if(!empty($package->image))

                                <img src="{{ asset('storage/' . $package->image) }}" class="img-fluid rounded"
                                    style="max-height:280px;" alt="{{ $package->name }}">

                            @else

                                <div class="py-5">

                                    <div class="avatar avatar-lg bg-light rounded-circle mx-auto mb-3">

                                        <i class="ti ti-photo-off fs-2 text-muted"></i>

                                    </div>

                                    <p class="text-muted mb-0">

                                        No image available

                                    </p>

                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- =================================================
                    PACKAGE SUMMARY
                    ================================================== --}}

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">

                                <i class="ti ti-chart-pie me-1"></i>

                                Package Summary

                            </h5>

                        </div>


                        <div class="card-body">


                            <div class="d-flex justify-content-between align-items-center mb-3">

                                <span class="text-muted">

                                    Price

                                </span>

                                <strong class="text-primary">

                                    ₹{{ number_format($package->price ?? 0, 2) }}

                                </strong>

                            </div>


                            <div class="d-flex justify-content-between align-items-center mb-3">

                                <span class="text-muted">

                                    Duration

                                </span>

                                <strong>

                                    {{ $package->duration_days ?? 0 }} Days

                                </strong>

                            </div>


                            <div class="d-flex justify-content-between align-items-center mb-3">

                                <span class="text-muted">

                                    Total Benefits

                                </span>

                                <strong>

                                    {{ $package->activeBenefits->count() }}

                                </strong>

                            </div>


                            <div class="d-flex justify-content-between align-items-center">

                                <span class="text-muted">

                                    Status

                                </span>


                                @if($package->status)

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


                    {{-- =================================================
                    BENEFIT SUMMARY
                    ================================================== --}}

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">

                                <i class="ti ti-list-check me-1"></i>

                                Included Services

                            </h5>

                        </div>


                        <div class="card-body">

                            @forelse($package->activeBenefits as $benefit)

                                @php

                                    $benefitLabels = [

                                        'free_consultation' =>
                                            'Free Consultation',

                                        'free_video_consultation' =>
                                            'Free Video Consultation',

                                        'free_ambulance' =>
                                            'Free Ambulance',

                                        'free_home_visit' =>
                                            'Free Home Visit',

                                        'free_surgery_quote' =>
                                            'Free Surgery Quote',

                                        'free_medicine' =>
                                            'Free Medicine',

                                    ];

                                    $benefitType =
                                        $benefit->benefit_type
                                        ?? $benefit->type;

                                @endphp


                                <div class="d-flex align-items-center mb-3">

                                    <div class="avatar avatar-sm bg-success-transparent rounded me-2">

                                        <i class="ti ti-check"></i>

                                    </div>

                                    <div class="flex-grow-1">

                                        <h6 class="mb-0">

                                            {{ $benefitLabels[$benefitType] ?? ucwords(str_replace('_', ' ', $benefitType)) }}

                                        </h6>

                                        <small class="text-muted">

                                            {{ $benefit->quantity }} available

                                        </small>

                                    </div>

                                </div>

                            @empty

                                <p class="text-muted text-center mb-0">

                                    No benefits configured.

                                </p>

                            @endforelse

                        </div>

                    </div>


                    {{-- =================================================
                    ACTIONS
                    ================================================== --}}

                    <div class="card">

                        <div class="card-body">

                            <a href="{{ route('admin.packages.edit', $package->id) }}" class="btn btn-primary w-100 mb-2">

                                <i class="ti ti-edit me-1"></i>

                                Edit Package

                            </a>


                            <a href="{{ route('admin.packages.index') }}" class="btn btn-light w-100">

                                <i class="ti ti-arrow-left me-1"></i>

                                Back to Packages

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection