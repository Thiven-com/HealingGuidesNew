
@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            {{-- Page Header --}}
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">

                        <h4 class="page-title">
                            Patient Medical Reports
                        </h4>

                        <p class="text-muted mb-0">
                            Manage all patient medical reports
                        </p>

                    </div>
                </div>
            </div>


            {{-- Success Message --}}
            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert">
                    </button>
                </div>

            @endif


            {{-- Filters --}}
            <div class="card">

                <div class="card-body">

                    <form method="GET"
                          action="{{ route('admin.patient-medical-reports.index') }}">

                        {{-- Main Filters --}}
                        <div class="row g-3">

                            {{-- Search --}}
                            <div class="col-md-4">

                                <label class="form-label">
                                    Search
                                </label>

                                <input type="text"
                                       name="search"
                                       class="form-control"
                                       placeholder="Report, Patient, Doctor..."
                                       value="{{ request('search') }}">

                            </div>


                            {{-- Report Type --}}
                            <div class="col-md-3">

                                <label class="form-label">
                                    Report Type
                                </label>

                                <input type="text"
                                       name="report_type"
                                       class="form-control"
                                       placeholder="Report Type"
                                       value="{{ request('report_type') }}">

                            </div>


                            {{-- Report Date --}}
                            <div class="col-md-3">

                                <label class="form-label">
                                    Report Date
                                </label>

                                <input type="date"
                                       name="report_date"
                                       class="form-control"
                                       value="{{ request('report_date') }}">

                            </div>


                            {{-- Filter --}}
                            <div class="col-md-2 d-flex align-items-end">

                                <button type="submit"
                                        class="btn btn-primary me-2">

                                    <i class="ti ti-filter"></i>
                                    Filter

                                </button>


                                <a href="{{ route(
                                    'admin.patient-medical-reports.index'
                                ) }}"
                                   class="btn btn-light">

                                    Reset

                                </a>

                            </div>

                        </div>

                    </form>

                </div>

            </div>


            {{-- Medical Reports Table --}}
            <div class="card mt-4">

                {{-- Card Header --}}
                <div class="card-header">

                    <div class="row align-items-center">

                        <div class="col">

                            <h5 class="card-title mb-0">
                                Medical Report List
                            </h5>

                        </div>


                        <div class="col-auto">

                            <span class="text-muted">

                                Total:
                                {{ $reports->total() }}

                            </span>

                        </div>

                    </div>

                </div>


                {{-- Table --}}
                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th>
                                        #
                                    </th>

                                    <th>
                                        Patient
                                    </th>

                                    <th>
                                        Family Member
                                    </th>

                                    <th>
                                        Doctor
                                    </th>

                                    <th>
                                        Report Name
                                    </th>

                                    <th>
                                        Report Type
                                    </th>

                                    <th>
                                        Report Date
                                    </th>

                                    <th>
                                        File
                                    </th>

                                    <th>
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($reports as $report)

                                    <tr>

                                        {{-- ID --}}
                                        <td>
                                            {{ $report->id }}
                                        </td>


                                        {{-- Patient --}}
                                        <td>

                                            @if($report->customer)

                                                {{ $report->customer->name }}

                                            @else

                                                <span class="text-muted">
                                                    -
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Family Member --}}
                                        <td>

                                            {{ $report->familyMember?->name ?? '-' }}

                                        </td>


                                        {{-- Doctor --}}
                                        <td>

                                            {{ $report->doctor?->doctor_name ?? '-' }}

                                        </td>


                                        {{-- Report Name --}}
                                        <td>

                                            <strong>
                                                {{ $report->report_name ?? '-' }}
                                            </strong>

                                        </td>


                                        {{-- Report Type --}}
                                        <td>

                                            @if($report->report_type)

                                                <span class="badge bg-info">

                                                    {{ $report->report_type }}

                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    -
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Report Date --}}
                                        <td>

                                            @if($report->report_date)

                                                {{ \Carbon\Carbon::parse(
                                                    $report->report_date
                                                )->format('d M Y') }}

                                            @else

                                                <span class="text-muted">
                                                    -
                                                </span>

                                            @endif

                                        </td>


                                        {{-- File --}}
                                        <td>

                                            @if($report->report_file)

                                                <a href="{{ asset(
                                                    'storage/' . $report->report_file
                                                ) }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-outline-primary">

                                                    <i class="ti ti-file"></i>
                                                    View

                                                </a>

                                            @else

                                                <span class="text-muted">
                                                    No File
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Action --}}
                                        <td>

                                            <a href="{{ route(
                                                'admin.patient-medical-reports.show',
                                                $report->id
                                            ) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="View">

                                                <i class="ti ti-eye"></i>

                                            </a>


                                            <a href="{{ route(
                                                'admin.patient-medical-reports.edit',
                                                $report->id
                                            ) }}"
                                               class="btn btn-sm btn-outline-warning"
                                               title="Edit">

                                                <i class="ti ti-edit"></i>

                                            </a>


                                            <form action="{{ route(
                                                'admin.patient-medical-reports.destroy',
                                                $report->id
                                            ) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this medical report?');">

                                                @csrf

                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Delete">

                                                    <i class="ti ti-trash"></i>

                                                </button>

                                            </form>

                                        </td>

                                    </tr>


                                @empty

                                    <tr>

                                        <td colspan="9"
                                            class="text-center py-5">

                                            <div class="text-muted">

                                                No medical reports found.

                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- Pagination --}}
                @if($reports->hasPages())

                    <div class="card-footer">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                Showing
                                {{ $reports->firstItem() ?? 0 }}
                                to
                                {{ $reports->lastItem() ?? 0 }}
                                of
                                {{ $reports->total() }}
                                reports

                            </div>


                            <div>

                                {{ $reports->links() }}

                            </div>

                        </div>

                    </div>

                @endif

            </div>

        </div>
    </div>

@endsection
