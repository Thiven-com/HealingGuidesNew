@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="row align-items-center">

                <div class="col">
                    <h4 class="page-title">
                        Health Insurance Provider Details
                    </h4>

                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.health-insurance-providers.index') }}">
                                Health Insurance Providers
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            {{ $provider->name }}
                        </li>
                    </ul>
                </div>

                <div class="col-auto">

                    <a
                        href="{{ route('admin.health-insurance-providers.edit', $provider->id) }}"
                        class="btn btn-primary me-2"
                    >
                        <i class="ti ti-edit me-1"></i>
                        Edit
                    </a>

                    <a
                        href="{{ route('admin.health-insurance-providers.index') }}"
                        class="btn btn-light"
                    >
                        <i class="ti ti-arrow-left me-1"></i>
                        Back
                    </a>

                </div>

            </div>
        </div>


        <div class="row">

            {{-- Provider Information --}}
            <div class="col-lg-8">

                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Provider Information
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            {{-- Name --}}
                            <div class="col-md-6 mb-4">

                                <label class="text-muted d-block mb-1">
                                    Provider Name
                                </label>

                                <h6 class="mb-0">
                                    {{ $provider->name ?: '-' }}
                                </h6>

                            </div>


                            {{-- Type --}}
                            <div class="col-md-6 mb-4">

                                <label class="text-muted d-block mb-1">
                                    Type
                                </label>

                                @php

                                    $typeLabels = [
                                        'central_government' => 'Central Government',
                                        'state_government' => 'State Government',
                                        'private' => 'Private',
                                        'public_sector' => 'Public Sector',
                                        'tpa' => 'TPA',
                                        'other' => 'Other',
                                    ];

                                @endphp

                                <span class="badge bg-light text-dark">
                                    {{ $typeLabels[$provider->type] ?? ucfirst(str_replace('_', ' ', $provider->type)) }}
                                </span>

                            </div>


                            {{-- Slug --}}
                            <div class="col-md-6 mb-4">

                                <label class="text-muted d-block mb-1">
                                    Slug
                                </label>

                                <span>
                                    {{ $provider->slug ?: '-' }}
                                </span>

                            </div>


                            {{-- Website --}}
                            <div class="col-md-6 mb-4">

                                <label class="text-muted d-block mb-1">
                                    Website
                                </label>

                                @if($provider->website_url)

                                    <a
                                        href="{{ $provider->website_url }}"
                                        target="_blank"
                                        class="text-primary"
                                    >
                                        {{ $provider->website_url }}

                                        <i class="ti ti-external-link ms-1"></i>
                                    </a>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </div>


                            {{-- Support Email --}}
                            <div class="col-md-6 mb-4">

                                <label class="text-muted d-block mb-1">
                                    Support Email
                                </label>

                                @if($provider->support_email)

                                    <a
                                        href="mailto:{{ $provider->support_email }}"
                                        class="text-primary"
                                    >
                                        {{ $provider->support_email }}
                                    </a>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </div>


                            {{-- Support Phone --}}
                            <div class="col-md-6 mb-4">

                                <label class="text-muted d-block mb-1">
                                    Support Phone
                                </label>

                                @if($provider->support_phone)

                                    <a
                                        href="tel:{{ $provider->support_phone }}"
                                        class="text-primary"
                                    >
                                        {{ $provider->support_phone }}
                                    </a>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </div>


                            {{-- Description --}}
                            <div class="col-md-12 mb-2">

                                <label class="text-muted d-block mb-2">
                                    Description
                                </label>

                                @if($provider->description)

                                    <div class="p-3 bg-light rounded">
                                        {!! nl2br(e($provider->description)) !!}
                                    </div>

                                @else

                                    <span class="text-muted">
                                        No description available.
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Timestamps --}}
                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Record Information
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6">

                                <label class="text-muted d-block mb-1">
                                    Created At
                                </label>

                                <span>
                                    {{ $provider->created_at?->format('d M Y, h:i A') ?? '-' }}
                                </span>

                            </div>


                            <div class="col-md-6">

                                <label class="text-muted d-block mb-1">
                                    Last Updated
                                </label>

                                <span>
                                    {{ $provider->updated_at?->format('d M Y, h:i A') ?? '-' }}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Right Side --}}
            <div class="col-lg-4">

                {{-- Logo --}}
                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Provider Logo
                        </h5>
                    </div>

                    <div class="card-body text-center">

                        @if($provider->logo)

                            <a
                                href="{{ asset($provider->logo) }}"
                                target="_blank"
                            >

                                <img
                                    src="{{ asset($provider->logo) }}"
                                    alt="{{ $provider->name }}"
                                    class="img-fluid rounded border"
                                    style="
                                        width: 220px;
                                        height: 220px;
                                        object-fit: contain;
                                    "
                                >

                            </a>

                        @else

                            <div
                                class="d-flex align-items-center justify-content-center bg-light rounded border"
                                style="
                                    width: 220px;
                                    height: 220px;
                                    margin: auto;
                                "
                            >

                                <div class="text-muted">

                                    <i
                                        class="ti ti-building-hospital"
                                        style="font-size: 50px;"
                                    ></i>

                                    <div class="mt-2">
                                        No Logo
                                    </div>

                                </div>

                            </div>

                        @endif

                    </div>

                </div>


                {{-- Status --}}
                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Status
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="d-flex align-items-center justify-content-between">

                            <span class="text-muted">
                                Current Status
                            </span>

                            @if($provider->status)

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Inactive
                                </span>

                            @endif

                        </div>


                        <hr>


                        <div class="d-flex align-items-center justify-content-between">

                            <span class="text-muted">
                                Display Order
                            </span>

                            <strong>
                                {{ $provider->display_order ?? 0 }}
                            </strong>

                        </div>

                    </div>

                </div>


                {{-- Actions --}}
                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Actions
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="d-grid gap-2">

                            <a
                                href="{{ route('admin.health-insurance-providers.edit', $provider->id) }}"
                                class="btn btn-primary"
                            >
                                <i class="ti ti-edit me-1"></i>
                                Edit Provider
                            </a>


                            <a
                                href="{{ route('admin.health-insurance-providers.toggle-status', $provider->id) }}"
                                class="btn btn-outline-secondary"
                                onclick="return confirm('Are you sure you want to change the provider status?')"
                            >

                                @if($provider->status)
                                    <i class="ti ti-ban me-1"></i>
                                    Deactivate Provider
                                @else
                                    <i class="ti ti-check me-1"></i>
                                    Activate Provider
                                @endif

                            </a>


                            <form
                                action="{{ route('admin.health-insurance-providers.destroy', $provider->id) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this provider? This action cannot be undone.')"
                            >

                                @csrf

                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-outline-danger w-100"
                                >
                                    <i class="ti ti-trash me-1"></i>
                                    Delete Provider
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

@endsection