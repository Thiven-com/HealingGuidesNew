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
                            Diagnostic Category Details
                        </h4>

                        <h6 class="text-muted mb-0">
                            View diagnostic category information
                        </h6>
                    </div>
                </div>

                <div class="col-md-5 col-sm-12 mt-3 mt-md-0">
                    <div class="d-flex justify-content-md-end gap-2 flex-wrap">

                        <a href="{{ route('admin.diagnostic-categories.edit', $category->id) }}"
                           class="btn btn-primary">
                            <i class="ti ti-edit me-1"></i>
                            Edit
                        </a>

                        <a href="{{ route('admin.diagnostic-categories.index') }}"
                           class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i>
                            Back
                        </a>

                    </div>
                </div>

            </div>
        </div>


        {{-- =========================================================
            CATEGORY INFORMATION
        ========================================================== --}}
        <div class="card">

            <div class="card-header">
                <h5 class="card-title mb-0">
                    Diagnostic Category Information
                </h5>
            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Category Name --}}
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-4">

                        <label class="form-label text-muted mb-1">
                            Category Name
                        </label>

                        <div class="fw-semibold fs-15">
                            {{ $category->name ?: '-' }}
                        </div>

                    </div>


                    {{-- Slug --}}
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-4">

                        <label class="form-label text-muted mb-1">
                            Slug
                        </label>

                        <div class="fw-semibold">
                            {{ $category->slug ?: '-' }}
                        </div>

                    </div>


                    {{-- Short Description --}}
                    <div class="col-12 mb-4">

                        <label class="form-label text-muted mb-1">
                            Short Description
                        </label>

                        <div>
                            {{ $category->short_description ?: '-' }}
                        </div>

                    </div>


                    {{-- Description --}}
                    <div class="col-12 mb-4">

                        <label class="form-label text-muted mb-1">
                            Description
                        </label>

                        <div>
                            {!! nl2br(e($category->description ?: '-')) !!}
                        </div>

                    </div>


                    {{-- Display Order --}}
                    <div class="col-lg-4 col-md-4 col-sm-12 mb-4">

                        <label class="form-label text-muted mb-1">
                            Display Order
                        </label>

                        <div class="fw-semibold">
                            {{ $category->display_order ?? '-' }}
                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="col-lg-4 col-md-4 col-sm-12 mb-4">

                        <label class="form-label text-muted mb-1">
                            Status
                        </label>

                        <div>

                            @if($category->status)

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


                    {{-- Category ID --}}
                    <div class="col-lg-4 col-md-4 col-sm-12 mb-4">

                        <label class="form-label text-muted mb-1">
                            Category ID
                        </label>

                        <div class="fw-semibold">
                            #{{ $category->id }}
                        </div>

                    </div>


                    {{-- Created At --}}
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-4">

                        <label class="form-label text-muted mb-1">
                            Created At
                        </label>

                        <div>
                            {{ $category->created_at
                                ? $category->created_at->format('d M Y, h:i A')
                                : '-' }}
                        </div>

                    </div>


                    {{-- Updated At --}}
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-4">

                        <label class="form-label text-muted mb-1">
                            Updated At
                        </label>

                        <div>
                            {{ $category->updated_at
                                ? $category->updated_at->format('d M Y, h:i A')
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

                    <a href="{{ route('admin.diagnostic-categories.index') }}"
                       class="btn btn-light">
                        <i class="ti ti-arrow-left me-1"></i>
                        Back
                    </a>

                    <a href="{{ route('admin.diagnostic-categories.edit', $category->id) }}"
                       class="btn btn-primary">
                        <i class="ti ti-edit me-1"></i>
                        Edit Category
                    </a>

                </div>

            </div>

        </div>

    </div>
</div>


{{-- ================================================================
    EXTRA RESPONSIVE FIX
================================================================ --}}
<style>
    .page-header .d-flex {
        width: 100%;
    }

    .page-header .btn {
        white-space: nowrap;
    }

    .card-footer {
        padding: 16px 24px;
    }

    .card-footer .btn {
        white-space: nowrap;
    }

    @media (max-width: 767.98px) {

        .page-header .d-flex {
            justify-content: flex-start !important;
        }

        .page-header .btn {
            flex: 0 0 auto;
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