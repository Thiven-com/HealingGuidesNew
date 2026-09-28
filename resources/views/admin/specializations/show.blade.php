@extends('layout.mainlayout') @section('content')
    <div class="page-wrapper">
        <div class="content"> <!-- Page Header -->
            <div class="page-header">
                <div class="page-title">
                    <h4>Specialization Details</h4>
                    <h6>View Medical Specialization Information</h6>
                </div>
                <div class="page-btn d-flex gap-2"> <a href="{{ route('admin.specializations.index') }}"
                        class="btn btn-secondary"> <i class="ti ti-arrow-left me-1"></i> Back </a> <a
                        href="{{ route('admin.specializations.edit', $specialization->id) }}" class="btn btn-primary"> <i
                            class="ti ti-edit me-1"></i> Edit </a> </div>
            </div> <!-- /Page Header --> <!-- Success Message --> @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show"> <i class="ti ti-check me-2"></i>
                    {{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"> </button>
                </div>
            @endif <div class="row"> <!-- Left Side -->
                <div class="col-lg-8"> <!-- Basic Information -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"> <i class="ti ti-stethoscope me-2"></i> Specialization Information </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4"> <!-- Category -->
                                <div class="col-md-6">
                                    <div class="detail-item"> <label class="detail-label"> Specialization Category </label>
                                        @if($specialization->specializationCategory)
                                            <div> <span class="badge bg-primary px-3 py-2">
                                                    {{ $specialization->specializationCategory->category_name }} </span> </div>
                                        @else <span class="text-muted"> N/A </span> @endif
                                    </div>
                                </div> <!-- Specialization Name -->
                                <div class="col-md-6">
                                    <div class="detail-item"> <label class="detail-label"> Specialization Name </label>
                                        <div class="detail-value"> {{ $specialization->specialization_name ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div> <!-- Slug -->
                                <div class="col-md-6">
                                    <div class="detail-item"> <label class="detail-label"> Slug </label>
                                        <div class="detail-value"> @if($specialization->slug) <span class="text-muted">
                                        {{ $specialization->slug }} </span> @else N/A @endif </div>
                                    </div>
                                </div> <!-- Status -->
                                <div class="col-md-6">
                                    <div class="detail-item"> <label class="detail-label"> Status </label>
                                        <div> @if($specialization->status) <span class="badge bg-success px-3 py-2"> <i
                                        class="ti ti-check me-1"></i> Active </span> @else <span
                                                        class="badge bg-danger px-3 py-2"> <i class="ti ti-x me-1"></i> Inactive
                                                    </span> @endif </div>
                                    </div>
                                </div> <!-- Description -->
                                <div class="col-12">
                                    <div class="detail-item"> <label class="detail-label"> Description </label>
                                        <div class="description-box"> @if($specialization->description)
                                        {!! nl2br(e($specialization->description)) !!} @else <span class="text-muted">
                                            No description available. </span> @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- Media -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"> <i class="ti ti-photo me-2"></i> Media </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4"> <!-- Icon -->
                                <div class="col-md-5">
                                    <div class="media-box">
                                        <div class="media-title"> <i class="ti ti-stethoscope me-1"></i> Specialization Icon
                                        </div> @if($specialization->icon) <img src="{{ asset($specialization->icon) }}"
                                        alt="Specialization Icon" class="specialization-icon"> @else <div
                                                class="no-media"> <i class="ti ti-photo-off"></i> <span> No Icon Available
                                            </span> </div> @endif
                                    </div>
                                </div> <!-- Banner -->
                                <div class="col-md-7">
                                    <div class="media-box">
                                        <div class="media-title"> <i class="ti ti-photo me-1"></i> Banner Image </div>
                                        @if($specialization->image) <img src="{{ asset($specialization->image) }}"
                                        alt="Specialization Image" class="specialization-banner"> @else <div
                                                class="no-media"> <i class="ti ti-photo-off"></i> <span> No Banner Image
                                            Available </span> </div> @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- Right Side -->
                <div class="col-lg-4"> <!-- Preview Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"> <i class="ti ti-eye me-2"></i> Preview </h5>
                        </div>
                        <div class="card-body text-center"> @if($specialization->image) <img
                            src="{{ asset($specialization->image) }}" alt="{{ $specialization->specialization_name }}"
                        class="preview-image"> @else <div class="preview-placeholder"> <i
                                class="ti ti-stethoscope"></i> </div> @endif <h4 class="mt-3 mb-1">
                                {{ $specialization->specialization_name }}
                            </h4>
                            @if($specialization->specializationCategory)
                                <div class="mb-2"> <span class="badge bg-primary">
                            {{ $specialization->specializationCategory->category_name }} </span> </div> @endif
                            @if($specialization->status) <span class="badge bg-success"> Active </span> @else <span
                            class="badge bg-danger"> Inactive </span> @endif
                        </div>
                    </div> <!-- Record Information -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0"> <i class="ti ti-info-circle me-2"></i> Record Information </h5>
                        </div>
                        <div class="card-body">
                            <div class="record-info">
                                <div class="record-row"> <span> <i class="ti ti-hash me-1"></i> ID </span> <strong>
                                        #{{ $specialization->id }} </strong> </div>
                                <div class="record-row"> <span> <i class="ti ti-calendar-plus me-1"></i> Created </span>
                                    <strong>
                                        {{ $specialization->created_at ? $specialization->created_at->format('d M Y, h:i A') : 'N/A' }}
                                    </strong>
                                </div>
                                <div class="record-row"> <span> <i class="ti ti-calendar-up me-1"></i> Updated </span>
                                    <strong>
                                        {{ $specialization->updated_at ? $specialization->updated_at->format('d M Y, h:i A') : 'N/A' }}
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .detail-item {
            height: 100%;
        }

        .detail-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 7px;
        }

        .detail-value {
            font-size: 16px;
            font-weight: 600;
            color: #212529;
        }

        .description-box {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            min-height: 100px;
            color: #495057;
            line-height: 1.7;
        }

        .media-box {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            height: 100%;
            background: #fff;
        }

        .media-title {
            font-size: 14px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 15px;
        }

        .specialization-icon {
            width: 180px;
            height: 180px;
            object-fit: contain;
            display: block;
            margin: 10px auto;
            border-radius: 8px;
        }

        .specialization-banner {
            width: 100%;
            height: 220px;
            object-fit: cover;
            display: block;
            border-radius: 8px;
        }

        .no-media {
            min-height: 180px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #adb5bd;
            gap: 8px;
        }

        .no-media i {
            font-size: 40px;
        }

        .preview-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
        }

        .preview-placeholder {
            width: 100%;
            height: 180px;
            border-radius: 10px;
            background: #f1f3f5;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .preview-placeholder i {
            font-size: 60px;
            color: #adb5bd;
        }

        .record-info {
            display: flex;
            flex-direction: column;
        }

        .record-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .record-row:last-child {
            border-bottom: 0;
        }

        .record-row span {
            color: #6b7280;
            font-size: 13px;
        }

        .record-row strong {
            color: #212529;
            font-size: 13px;
            text-align: right;
        }

        @media (max-width: 767px) {
            .specialization-banner {
                height: 180px;
            }

            .specialization-icon {
                width: 140px;
                height: 140px;
            }

            .record-row {
                align-items: flex-start;
                flex-direction: column;
                gap: 4px;
            }

            .record-row strong {
                text-align: left;
            }
        }
</style> @endsection