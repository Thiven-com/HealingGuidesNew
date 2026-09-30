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
                    Family Member Health Checkups
                </h4>

                <p class="text-muted mb-0">
                    Manage health checkup reports, values and percentages.
                </p>
            </div>

            <a href="{{ route('admin.family-member-health-checkups.create') }}"
               class="btn btn-primary">

                <i class="ti ti-plus me-1"></i>
                Add Health Report

            </a>

        </div>


        {{-- ========================================================= --}}
        {{-- Success / Error Messages --}}
        {{-- ========================================================= --}}

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                <i class="ti ti-check me-1"></i>

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show">

                <i class="ti ti-alert-circle me-1"></i>

                {{ session('error') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- Statistics --}}
        {{-- ========================================================= --}}

        <div class="row mb-4">

            {{-- Total Reports --}}
            <div class="col-md-3">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div class="avatar avatar-lg bg-primary-subtle rounded">

                                <i class="ti ti-report-medical text-primary fs-3"></i>

                            </div>

                            <div class="ms-3">

                                <span class="text-muted">
                                    Total Reports
                                </span>

                                <h4 class="mb-0">
                                    {{ $reports->total() }}
                                </h4>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Completed --}}
            <div class="col-md-3">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div class="avatar avatar-lg bg-success-subtle rounded">

                                <i class="ti ti-circle-check text-success fs-3"></i>

                            </div>

                            <div class="ms-3">

                                <span class="text-muted">
                                    Completed
                                </span>

                                <h4 class="mb-0">

                                    {{ \App\Models\FamilyMemberHealthCheckup::where('status', 'completed')->count() }}

                                </h4>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Pending --}}
            <div class="col-md-3">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div class="avatar avatar-lg bg-warning-subtle rounded">

                                <i class="ti ti-clock text-warning fs-3"></i>

                            </div>

                            <div class="ms-3">

                                <span class="text-muted">
                                    Pending
                                </span>

                                <h4 class="mb-0">

                                    {{ \App\Models\FamilyMemberHealthCheckup::where('status', 'pending')->count() }}

                                </h4>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Health Checkups --}}
            <div class="col-md-3">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div class="avatar avatar-lg bg-info-subtle rounded">

                                <i class="ti ti-test-pipe text-info fs-3"></i>

                            </div>

                            <div class="ms-3">

                                <span class="text-muted">
                                    Health Checkups
                                </span>

                                <h4 class="mb-0">
                                    {{ \App\Models\HealthCheckup::count() }}
                                </h4>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Reports Table --}}
        {{-- ========================================================= --}}

        <div class="card">

            <div class="card-header">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <h5 class="card-title mb-1">
                            Health Checkup Reports
                        </h5>

                        <p class="text-muted mb-0">
                            Family member health assessment reports.
                        </p>

                    </div>

                </div>

            </div>


            <div class="card-body">

                {{-- Search / Filter --}}
                <form method="GET"
                      action="{{ route('admin.family-member-health-checkups.index') }}"
                      class="mb-4">

                    <div class="row g-2">

                        <div class="col-md-5">

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="ti ti-search"></i>
                                </span>

                                <input type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       class="form-control"
                                       placeholder="Search family member...">

                            </div>

                        </div>


                        <div class="col-md-3">

                            <select name="health_checkup_id"
                                    class="form-select">

                                <option value="">
                                    All Health Checkups
                                </option>

                                @foreach($healthCheckups as $healthCheckup)

                                    <option value="{{ $healthCheckup->id }}"
                                        {{ request('health_checkup_id') == $healthCheckup->id ? 'selected' : '' }}>

                                        {{ $healthCheckup->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-2">

                            <select name="status"
                                    class="form-select">

                                <option value="">
                                    All Status
                                </option>

                                <option value="completed"
                                    {{ request('status') === 'completed' ? 'selected' : '' }}>

                                    Completed

                                </option>

                                <option value="pending"
                                    {{ request('status') === 'pending' ? 'selected' : '' }}>

                                    Pending

                                </option>

                            </select>

                        </div>


                        <div class="col-md-2">

                            <button type="submit"
                                    class="btn btn-primary w-100">

                                <i class="ti ti-search me-1"></i>
                                Search

                            </button>

                        </div>

                    </div>

                </form>


                {{-- Table --}}
                @if($reports->count())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th width="60">
                                        #
                                    </th>

                                    <th>
                                        Family Member
                                    </th>

                                    <th>
                                        Health Checkup
                                    </th>

                                    <th>
                                        Report Value
                                    </th>

                                    <th>
                                        Percentage
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Checked Date
                                    </th>

                                    <th width="150">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($reports as $report)

                                    <tr>

                                        {{-- Number --}}
                                        <td>
                                            {{ $reports->firstItem() + $loop->index }}
                                        </td>


                                        {{-- Family Member --}}
                                        <td>

                                            <div class="d-flex align-items-center">

                                                <div class="avatar avatar-md bg-primary-subtle rounded">

                                                    <i class="ti ti-user text-primary"></i>

                                                </div>

                                                <div class="ms-2">

                                                    <h6 class="mb-0">

                                                        {{ $report->familyMember->name ?? '—' }}

                                                    </h6>

                                                    @if($report->familyMember?->customer)

                                                        <small class="text-muted">

                                                            {{ $report->familyMember->customer->name ?? '' }}

                                                        </small>

                                                    @endif

                                                </div>

                                            </div>

                                        </td>


                                        {{-- Health Checkup --}}
                                        <td>

                                            @if($report->healthCheckup)

                                                <span class="badge bg-info-subtle text-info">

                                                    <i class="ti ti-stethoscope me-1"></i>

                                                    {{ $report->healthCheckup->name }}

                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Report Value --}}
                                        <td>

                                            @if($report->report_value)

                                                <strong>
                                                    {{ $report->report_value }}
                                                </strong>

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Percentage --}}
                                        <td>

                                            @if(!is_null($report->percentage))

                                                <div class="d-flex align-items-center gap-2">

                                                    <div class="progress"
                                                         style="width:80px;height:7px;">

                                                        <div class="progress-bar"
                                                             style="width: {{ $report->percentage }}%;">
                                                        </div>

                                                    </div>

                                                    <strong>
                                                        {{ $report->percentage }}%
                                                    </strong>

                                                </div>

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Status --}}
                                        <td>

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

                                        </td>


                                        {{-- Checked Date --}}
                                        <td>

                                            {{ $report->checked_at
                                                ? $report->checked_at->format('d M Y')
                                                : '—' }}

                                        </td>


                                        {{-- Actions --}}
                                        <td>

                                            <div class="d-flex gap-1">

                                                <a href="{{ route(
                                                    'admin.family-member-health-checkups.show',
                                                    $report->id
                                                ) }}"
                                                   class="btn btn-sm btn-icon btn-light"
                                                   title="View">

                                                    <i class="ti ti-eye"></i>

                                                </a>


                                                <a href="{{ route(
                                                    'admin.family-member-health-checkups.edit',
                                                    $report->id
                                                ) }}"
                                                   class="btn btn-sm btn-icon btn-light"
                                                   title="Edit">

                                                    <i class="ti ti-edit"></i>

                                                </a>


                                                <form action="{{ route(
                                                    'admin.family-member-health-checkups.destroy',
                                                    $report->id
                                                ) }}"
                                                      method="POST"
                                                      class="delete-report-form">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-sm btn-icon btn-light text-danger"
                                                            title="Delete">

                                                        <i class="ti ti-trash"></i>

                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- Pagination --}}
                    <div class="mt-4">

                        {{ $reports->withQueryString()->links() }}

                    </div>

                @else

                    <div class="text-center py-5">

                        <div class="mb-3">

                            <i class="ti ti-report-medical"
                               style="font-size:60px;">
                            </i>

                        </div>

                        <h5>
                            No Health Checkup Reports Found
                        </h5>

                        <p class="text-muted mb-3">
                            Add a health checkup report for a family member.
                        </p>

                        <a href="{{ route(
                            'admin.family-member-health-checkups.create'
                        ) }}"
                           class="btn btn-primary">

                            <i class="ti ti-plus me-1"></i>

                            Add Health Report

                        </a>

                    </div>

                @endif

            </div>

        </div>

    </div>
</div>

@endsection


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    document
        .querySelectorAll('.delete-report-form')
        .forEach(function (form) {

            form.addEventListener('submit', function (event) {

                if (!confirm(
                    'Are you sure you want to delete this health checkup report?'
                )) {

                    event.preventDefault();

                }

            });

        });

});

</script>

@endpush