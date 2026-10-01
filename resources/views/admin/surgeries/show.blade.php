<?php $page = 'surgeries'; ?>

@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">

    <div class="content">

        {{-- Page Header --}}
        <div class="page-header">

            <div class="page-title">

                <h4>Surgery Details</h4>

                <h6>View surgery information</h6>

            </div>

            <div class="page-btn d-flex gap-2">

                <a href="{{ route('admin.surgeries.edit', $surgery->id) }}"
                   class="btn btn-primary">

                    <i data-feather="edit" class="me-2"></i>

                    Edit

                </a>

                <a href="{{ route('admin.surgeries.index') }}"
                   class="btn btn-secondary">

                    <i data-feather="arrow-left" class="me-2"></i>

                    Back

                </a>

            </div>

        </div>


        {{-- Success --}}
        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"></button>

            </div>

        @endif


        <div class="row">

            {{-- Left Column --}}
            <div class="col-lg-8">

                {{-- Basic Information --}}
                <div class="card">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Surgery Information
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row g-4">

                            {{-- Name --}}
                            <div class="col-md-6">

                                <label class="text-muted d-block mb-1">
                                    Surgery Name
                                </label>

                                <h6 class="mb-0">
                                    {{ $surgery->name }}
                                </h6>

                            </div>


                            {{-- Slug --}}
                            <div class="col-md-6">

                                <label class="text-muted d-block mb-1">
                                    Slug
                                </label>

                                <h6 class="mb-0">
                                    {{ $surgery->slug }}
                                </h6>

                            </div>


                            {{-- Duration --}}
                            <div class="col-md-6">

                                <label class="text-muted d-block mb-1">
                                    Surgery Duration
                                </label>

                                <h6 class="mb-0">
                                    {{ $surgery->duration ?: '-' }}
                                </h6>

                            </div>


                            {{-- Recovery --}}
                            <div class="col-md-6">

                                <label class="text-muted d-block mb-1">
                                    Recovery Time
                                </label>

                                <h6 class="mb-0">
                                    {{ $surgery->recovery_time ?: '-' }}
                                </h6>

                            </div>


                            {{-- Short Description --}}
                            <div class="col-md-12">

                                <label class="text-muted d-block mb-2">
                                    Short Description
                                </label>

                                <div class="border rounded p-3">

                                    {{ $surgery->short_description ?: '-' }}

                                </div>

                            </div>


                            {{-- Description --}}
                            <div class="col-md-12">

                                <label class="text-muted d-block mb-2">
                                    Description
                                </label>

                                <div class="border rounded p-3">

                                    @if($surgery->description)

                                        {!! nl2br(e($surgery->description)) !!}

                                    @else

                                        -

                                    @endif

                                </div>

                            </div>


                            {{-- Preparation --}}
                            <div class="col-md-12">

                                <label class="text-muted d-block mb-2">
                                    Preparation Instructions
                                </label>

                                <div class="border rounded p-3">

                                    @if($surgery->preparation_instructions)

                                        {!! nl2br(e($surgery->preparation_instructions)) !!}

                                    @else

                                        -

                                    @endif

                                </div>

                            </div>


                            {{-- Post Surgery Care --}}
                            <div class="col-md-12">

                                <label class="text-muted d-block mb-2">
                                    Post Surgery Care
                                </label>

                                <div class="border rounded p-3">

                                    @if($surgery->post_surgery_care)

                                        {!! nl2br(e($surgery->post_surgery_care)) !!}

                                    @else

                                        -

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            </div>


            {{-- Right Column --}}
            <div class="col-lg-4">

                {{-- Image --}}
                <div class="card">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Surgery Image
                        </h5>

                    </div>

                    <div class="card-body text-center">

                        @if($surgery->image)

                            <img src="{{ asset($surgery->image) }}"
                                 alt="{{ $surgery->name }}"
                                 class="img-fluid rounded"
                                 style="max-height:300px; object-fit:contain;">

                        @else

                            <img src="{{ asset('assets/img/no-image.png') }}"
                                 alt="No Image"
                                 class="img-fluid rounded"
                                 style="max-height:300px; object-fit:contain;">

                        @endif

                    </div>

                </div>


                {{-- Status --}}
                <div class="card">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Settings
                        </h5>

                    </div>

                    <div class="card-body">

                        {{-- Status --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <span class="text-muted">
                                Status
                            </span>

                            @if($surgery->status)

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Inactive
                                </span>

                            @endif

                        </div>


                        {{-- Display Order --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <span class="text-muted">
                                Display Order
                            </span>

                            <strong>
                                {{ $surgery->display_order }}
                            </strong>

                        </div>


                        {{-- Created --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <span class="text-muted">
                                Created
                            </span>

                            <strong>
                                {{ $surgery->created_at?->format('d M Y, h:i A') }}
                            </strong>

                        </div>


                        {{-- Updated --}}
                        <div class="d-flex justify-content-between align-items-center">

                            <span class="text-muted">
                                Last Updated
                            </span>

                            <strong>
                                {{ $surgery->updated_at?->format('d M Y, h:i A') }}
                            </strong>

                        </div>

                    </div>

                </div>


                {{-- Actions --}}
                <div class="card">

                    <div class="card-body">

                        <div class="d-grid gap-2">

                            <a href="{{ route('admin.surgeries.edit', $surgery->id) }}"
                               class="btn btn-primary">

                                <i data-feather="edit" class="me-2"></i>

                                Edit Surgery

                            </a>


                            <form action="{{ route('admin.surgeries.status', $surgery->id) }}"
                                  method="POST">

                                @csrf

                                <button type="submit"
                                        class="btn btn-outline-secondary w-100">

                                    @if($surgery->status)

                                        <i data-feather="lock" class="me-2"></i>

                                        Deactivate Surgery

                                    @else

                                        <i data-feather="unlock" class="me-2"></i>

                                        Activate Surgery

                                    @endif

                                </button>

                            </form>


                            <form action="{{ route('admin.surgeries.destroy', $surgery->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this surgery?');">

                                @csrf

                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-outline-danger w-100">

                                    <i data-feather="trash-2" class="me-2"></i>

                                    Delete Surgery

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    if (typeof feather !== 'undefined') {

        feather.replace();

    }

});

</script>

@endsection