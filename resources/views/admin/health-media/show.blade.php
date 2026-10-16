
@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">

        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Health Media Details</h4>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.health-media.edit', $healthMedia->id) }}"
                   class="btn btn-primary">
                    <i class="fa fa-edit me-1"></i> Edit
                </a>

                <a href="{{ route('admin.health-media.index') }}"
                   class="btn btn-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        {{-- Health Media Details --}}
        <div class="card health-media-card">
            <div class="card-header">
                <h5 class="card-title mb-0">Media Information</h5>
            </div>

            <div class="card-body">

                <div class="row g-4">

                    {{-- Image Preview --}}
                    <div class="col-12 col-lg-5">
                        <div class="health-media-preview">
                            @if ($healthMedia->image)
                                <img
                                    src="{{ asset($healthMedia->image) }}"
                                    alt="{{ $healthMedia->name }}"
                                    class="img-fluid rounded"
                                    style="max-width:100%; max-height:400px; object-fit:contain;"
                                >
                            @else
                                <div class="text-muted text-center py-5">
                                    <i class="fa fa-image fa-3x mb-2"></i>
                                    <p class="mb-0">No image available</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Information --}}
                    <div class="col-12 col-lg-7">

                        <div class="mb-4">
                            <label class="text-muted d-block mb-1">
                                Media Name
                            </label>

                            <h5 class="mb-0">
                                {{ $healthMedia->name }}
                            </h5>
                        </div>

                        <div class="mb-4">
                            <label class="text-muted d-block mb-2">
                                Status
                            </label>

                            @if ($healthMedia->status === 'active')
                                <span class="badge bg-success">
                                    Active
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    Inactive
                                </span>
                            @endif
                        </div>

                        <div class="mb-4">
                            <label class="text-muted d-block mb-2">
                                Description
                            </label>

                            <p class="mb-0 text-break">
                                {{ $healthMedia->description ?: 'No description available.' }}
                            </p>
                        </div>

                        <div class="mb-4">
                            <label class="text-muted d-block mb-2">
                                Image File
                            </label>

                            @if ($healthMedia->image)
                                <a
                                    href="{{ asset($healthMedia->image) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-outline-primary"
                                >
                                    <i class="fa fa-external-link me-1"></i>
                                    Open Image
                                </a>
                            @else
                                <span class="text-muted">
                                    No image file available.
                                </span>
                            @endif
                        </div>

                    </div>
                </div>

                <hr class="my-4">

                {{-- Timestamps --}}
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="text-muted d-block mb-1">
                            Created At
                        </label>

                        <p class="mb-0">
                            {{ $healthMedia->created_at
                                ? $healthMedia->created_at->format('d-m-Y h:i A')
                                : 'N/A' }}
                        </p>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="text-muted d-block mb-1">
                            Last Updated
                        </label>

                        <p class="mb-0">
                            {{ $healthMedia->updated_at
                                ? $healthMedia->updated_at->format('d-m-Y h:i A')
                                : 'N/A' }}
                        </p>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<style>
    .health-media-card {
        border: 1px solid #e8e8e8;
        border-radius: 12px;
        overflow: hidden;
    }

    .health-media-card .card-header {
        background: #fff;
        padding: 18px 22px;
        border-bottom: 1px solid #eee;
    }

    .health-media-card .card-body {
        padding: 24px;
    }

    .health-media-preview {
        min-height: 260px;
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border: 1px solid #eee;
        border-radius: 10px;
    }

    .health-media-card label {
        font-size: 13px;
        font-weight: 500;
    }

    @media (max-width: 576px) {
        .health-media-card .card-body {
            padding: 16px;
        }

        .page-wrapper .content > .d-flex {
            align-items: flex-start !important;
            gap: 12px;
        }
    }
</style>
@endsection