@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- ========================================================= --}}
        {{-- Page Header --}}
        {{-- ========================================================= --}}

        <div class="d-flex align-items-center justify-content-between mb-4">

            <div>
                <h4 class="mb-1">
                    Health Checkup Report
                </h4>

                <p class="text-muted mb-0">
                    View family member health checkup report details.
                </p>
            </div>

            <div class="d-flex gap-2">

                <a href="{{ route(
                    'admin.family-member-health-checkups.edit',
                    $report->id
                ) }}"
                   class="btn btn-primary">

                    <i class="ti ti-edit me-1"></i>
                    Edit

                </a>

                <a href="{{ route(
                    'admin.family-member-health-checkups.index'
                ) }}"
                   class="btn btn-light">

                    <i class="ti ti-arrow-left me-1"></i>
                    Back

                </a>

            </div>

        </div>


        <div class="row">

            {{-- ===================================================== --}}
            {{-- Family Member --}}
            {{-- ===================================================== --}}

            <div class="col-lg-4">

                <div class="card">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Family Member
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="text-center mb-4">

                            <div class="avatar avatar-xl bg-primary-subtle rounded-circle mx-auto mb-3">

                                <i class="ti ti-user text-primary"
                                   style="font-size: 40px;">
                                </i>

                            </div>

                            <h5 class="mb-1">

                                {{ $report->familyMember->name ?? '—' }}

                            </h5>

                            @if($report->familyMember?->customer)

                                <p class="text-muted mb-0">

                                    {{ $report->familyMember->customer->name ?? '—' }}

                                </p>

                            @endif

                        </div>


                        <div class="border-top pt-3">

                            <div class="d-flex justify-content-between mb-3">

                                <span class="text-muted">
                                    Family Member ID
                                </span>

                                <strong>
                                    #{{ $report->family_member_id }}
                                </strong>

                            </div>


                            @if($report->familyMember?->relationship)

                                <div class="d-flex justify-content-between mb-3">

                                    <span class="text-muted">
                                        Relationship
                                    </span>

                                    <strong>
                                        {{ $report->familyMember->relationship }}
                                    </strong>

                                </div>

                            @endif


                            @if($report->familyMember?->gender)

                                <div class="d-flex justify-content-between mb-3">

                                    <span class="text-muted">
                                        Gender
                                    </span>

                                    <strong>
                                        {{ ucfirst($report->familyMember->gender) }}
                                    </strong>

                                </div>

                            @endif


                            @if($report->familyMember?->dob)

                                <div class="d-flex justify-content-between">

                                    <span class="text-muted">
                                        Date of Birth
                                    </span>

                                    <strong>
                                        {{ \Carbon\Carbon::parse(
                                            $report->familyMember->dob
                                        )->format('d M Y') }}
                                    </strong>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- Health Checkup --}}
            {{-- ===================================================== --}}

            <div class="col-lg-8">

                <div class="card">

                    <div class="card-header">

                        <div class="d-flex align-items-center justify-content-between">

                            <div>

                                <h5 class="card-title mb-1">
                                    Health Checkup Details
                                </h5>

                                <p class="text-muted mb-0">
                                    Report information and health score.
                                </p>

                            </div>


                            {{-- Status --}}
                            @if($report->status === 'completed')

                                <span class="badge bg-success-subtle text-success">

                                    <i class="ti ti-circle-check me-1"></i>

                                    Completed

                                </span>

                            @else

                                <span class="badge bg-warning-subtle text-warning">

                                    <i class="ti ti-clock me-1"></i>

                                    Pending

                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="card-body">

                        {{-- ================================================= --}}
                        {{-- Health Checkup --}}
                        {{-- ================================================= --}}

                        <div class="d-flex align-items-center border rounded p-3 mb-4">

                            <div class="avatar avatar-lg bg-info-subtle rounded">

                                <i class="ti ti-report-medical text-info fs-3"></i>

                            </div>

                            <div class="ms-3">

                                <span class="text-muted d-block small">
                                    Health Checkup
                                </span>

                                <h5 class="mb-0">

                                    {{ $report->healthCheckup->name ?? '—' }}

                                </h5>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- Report Value & Percentage --}}
                        {{-- ================================================= --}}

                        <div class="row g-3 mb-4">

                            {{-- Report Value --}}
                            <div class="col-md-6">

                                <div class="border rounded p-4 h-100">

                                    <div class="d-flex align-items-center mb-3">

                                        <div class="avatar avatar-md bg-primary-subtle rounded">

                                            <i class="ti ti-test-pipe text-primary"></i>

                                        </div>

                                        <div class="ms-2">

                                            <span class="text-muted d-block">
                                                Report Value
                                            </span>

                                            <h5 class="mb-0">

                                                {{ $report->report_value ?: '—' }}

                                            </h5>

                                        </div>

                                    </div>

                                    <p class="text-muted small mb-0">
                                        Actual health checkup report value.
                                    </p>

                                </div>

                            </div>


                            {{-- Percentage --}}
                            <div class="col-md-6">

                                <div class="border rounded p-4 h-100">

                                    <div class="d-flex align-items-center mb-3">

                                        <div class="avatar avatar-md bg-success-subtle rounded">

                                            <i class="ti ti-percentage text-success"></i>

                                        </div>

                                        <div class="ms-2">

                                            <span class="text-muted d-block">
                                                Health Percentage
                                            </span>

                                            <h5 class="mb-0">

                                                {{ !is_null($report->percentage)
                                                    ? $report->percentage . '%'
                                                    : '—' }}

                                            </h5>

                                        </div>

                                    </div>


                                    @if(!is_null($report->percentage))

                                        <div class="progress mb-2"
                                             style="height: 8px;">

                                            <div class="progress-bar"
                                                 style="width: {{ min(100, max(0, $report->percentage)) }}%;">
                                            </div>

                                        </div>

                                    @endif

                                    <p class="text-muted small mb-0">
                                        Health score percentage.
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- Report Information --}}
                        {{-- ================================================= --}}

                        <div class="row g-3">

                            {{-- Checked Date --}}
                            <div class="col-md-6">

                                <div class="border rounded p-3">

                                    <span class="text-muted d-block small mb-1">
                                        Checked Date
                                    </span>

                                    <strong>

                                        {{ $report->checked_at
                                            ? $report->checked_at->format('d M Y')
                                            : '—' }}

                                    </strong>

                                </div>

                            </div>


                            {{-- Created Date --}}
                            <div class="col-md-6">

                                <div class="border rounded p-3">

                                    <span class="text-muted d-block small mb-1">
                                        Added On
                                    </span>

                                    <strong>

                                        {{ $report->created_at
                                            ? $report->created_at->format('d M Y, h:i A')
                                            : '—' }}

                                    </strong>

                                </div>

                            </div>


                            {{-- Updated Date --}}
                            <div class="col-md-6">

                                <div class="border rounded p-3">

                                    <span class="text-muted d-block small mb-1">
                                        Last Updated
                                    </span>

                                    <strong>

                                        {{ $report->updated_at
                                            ? $report->updated_at->format('d M Y, h:i A')
                                            : '—' }}

                                    </strong>

                                </div>

                            </div>


                            {{-- Report ID --}}
                            <div class="col-md-6">

                                <div class="border rounded p-3">

                                    <span class="text-muted d-block small mb-1">
                                        Report ID
                                    </span>

                                    <strong>
                                        #{{ $report->id }}
                                    </strong>

                                </div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- Remarks --}}
                        {{-- ================================================= --}}

                        @if($report->remarks)

                            <div class="mt-4">

                                <label class="form-label fw-semibold">
                                    Remarks
                                </label>

                                <div class="border rounded p-3 bg-light">

                                    {!! nl2br(e($report->remarks)) !!}

                                </div>

                            </div>

                        @endif

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- Actions --}}
                {{-- ===================================================== --}}

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <h6 class="mb-1">
                                    Manage Report
                                </h6>

                                <p class="text-muted mb-0 small">
                                    Update or remove this health checkup report.
                                </p>

                            </div>


                            <div class="d-flex gap-2">

                                <a href="{{ route(
                                    'admin.family-member-health-checkups.edit',
                                    $report->id
                                ) }}"
                                   class="btn btn-primary">

                                    <i class="ti ti-edit me-1"></i>
                                    Edit Report

                                </a>


                                <form method="POST"
                                      action="{{ route(
                                          'admin.family-member-health-checkups.destroy',
                                          $report->id
                                      ) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this health checkup report?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-outline-danger">

                                        <i class="ti ti-trash me-1"></i>
                                        Delete

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

@endsection