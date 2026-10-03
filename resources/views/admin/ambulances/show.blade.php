@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- =========================================================
            PAGE HEADER
        ========================================================== --}}
        <div class="page-header">
            <div class="row align-items-center w-100">

                <div class="col-md-7 col-sm-12">
                    <div class="page-title-box">
                        <h4 class="page-title mb-1">
                            Ambulance Details
                        </h4>

                        <h6 class="text-muted mb-0">
                            View ambulance information
                        </h6>
                    </div>
                </div>

                <div class="col-md-5 col-sm-12 mt-3 mt-md-0">
                    <div class="d-flex justify-content-md-end gap-2 flex-wrap">

                        <a href="{{ route('admin.ambulances.edit', $ambulance->id) }}"
                           class="btn btn-primary">
                            <i class="ti ti-edit me-1"></i>
                            Edit
                        </a>

                        <a href="{{ route('admin.ambulances.index') }}"
                           class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i>
                            Back
                        </a>

                    </div>
                </div>

            </div>
        </div>


        {{-- =========================================================
            AMBULANCE INFORMATION
        ========================================================== --}}
        <div class="card">

            <div class="card-header">
                <h5 class="card-title mb-0">
                    Ambulance Information
                </h5>
            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Ambulance Number --}}
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">

                        <label class="form-label text-muted mb-1">
                            Ambulance Number
                        </label>

                        <div class="fw-semibold fs-15">
                            {{ $ambulance->ambulance_no ?? $ambulance->registration_no ?? '-' }}
                        </div>

                    </div>


                    {{-- Vehicle Number --}}
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">

                        <label class="form-label text-muted mb-1">
                            Vehicle Number
                        </label>

                        <div class="fw-semibold">
                            {{ $ambulance->vehicle_number ?? $ambulance->registration_number ?? '-' }}
                        </div>

                    </div>


                    {{-- Ambulance Type --}}
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">

                        <label class="form-label text-muted mb-1">
                            Ambulance Type
                        </label>

                        <div>
                            {{ $ambulance->ambulance_type ?? $ambulance->type ?? '-' }}
                        </div>

                    </div>


                    {{-- Hospital --}}
                    @if(isset($ambulance->hospital))

                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">

                            <label class="form-label text-muted mb-1">
                                Hospital
                            </label>

                            <div class="fw-semibold">
                                {{ $ambulance->hospital->name ?? '-' }}
                            </div>

                        </div>

                    @endif


                    {{-- Driver Name --}}
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">

                        <label class="form-label text-muted mb-1">
                            Driver Name
                        </label>

                        <div>
                            {{ $ambulance->driver_name ?? '-' }}
                        </div>

                    </div>


                    {{-- Driver Mobile --}}
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">

                        <label class="form-label text-muted mb-1">
                            Driver Mobile
                        </label>

                        <div>
                            {{ $ambulance->driver_mobile ?? $ambulance->driver_phone ?? '-' }}
                        </div>

                    </div>


                    {{-- Emergency Contact --}}
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">

                        <label class="form-label text-muted mb-1">
                            Emergency Contact
                        </label>

                        <div>
                            {{ $ambulance->emergency_contact ?? '-' }}
                        </div>

                    </div>


                    {{-- Base Location --}}
                    <div class="col-lg-8 col-md-6 col-sm-12 mb-4">

                        <label class="form-label text-muted mb-1">
                            Location
                        </label>

                        <div>
                            {{ $ambulance->location ?? $ambulance->address ?? '-' }}
                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">

                        <label class="form-label text-muted mb-1">
                            Status
                        </label>

                        <div>

                            @if($ambulance->status == 1 || $ambulance->status === 'active')

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @elseif($ambulance->status === 'inactive' || $ambulance->status == 0)

                                <span class="badge bg-danger">
                                    Inactive
                                </span>

                            @else

                                <span class="badge bg-warning">
                                    {{ ucfirst($ambulance->status ?? 'Unknown') }}
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Description --}}
                    @if(isset($ambulance->description))

                        <div class="col-12 mb-4">

                            <label class="form-label text-muted mb-1">
                                Description
                            </label>

                            <div>
                                {!! nl2br(e($ambulance->description ?: '-')) !!}
                            </div>

                        </div>

                    @endif


                    {{-- Created At --}}
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-4">

                        <label class="form-label text-muted mb-1">
                            Created At
                        </label>

                        <div>
                            {{ $ambulance->created_at
                                ? $ambulance->created_at->format('d M Y, h:i A')
                                : '-' }}
                        </div>

                    </div>


                    {{-- Updated At --}}
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-4">

                        <label class="form-label text-muted mb-1">
                            Updated At
                        </label>

                        <div>
                            {{ $ambulance->updated_at
                                ? $ambulance->updated_at->format('d M Y, h:i A')
                                : '-' }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                FOOTER ACTIONS
            ====================================================== --}}
            <div class="card-footer">

                <div class="d-flex justify-content-end align-items-center gap-2 flex-wrap">

                    <a href="{{ route('admin.ambulances.index') }}"
                       class="btn btn-light">
                        <i class="ti ti-arrow-left me-1"></i>
                        Back
                    </a>

                    <a href="{{ route('admin.ambulances.edit', $ambulance->id) }}"
                       class="btn btn-primary">
                        <i class="ti ti-edit me-1"></i>
                        <span>Edit Ambulance</span>
                    </a>

                </div>

            </div>

        </div>

    </div>
</div>


<style>
    .page-header .d-flex {
        width: 100%;
    }

    .page-header .btn,
    .card-footer .btn {
        white-space: nowrap;
    }

    .card-footer {
        padding: 16px 24px;
    }

    @media (max-width: 767.98px) {

        .page-header .d-flex {
            justify-content: flex-start !important;
        }

        .card-footer .d-flex {
            justify-content: flex-start !important;
        }

        .card-footer .btn {
            flex: 1 1 auto;
        }
    }
</style>

@endsection