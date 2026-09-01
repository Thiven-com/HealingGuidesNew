
@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            {{-- Page Header --}}
            <div class="page-header">

                <div class="row align-items-center">

                    <div class="col">

                        <h4 class="page-title">
                            Medical Report Details
                        </h4>

                        <p class="text-muted mb-0">
                            View patient medical report details
                        </p>

                    </div>


                    <div class="col-auto">

                        {{-- Edit --}}
                        <a href="{{ route(
                            'admin.patient-medical-reports.edit',
                            $report->id
                        ) }}"
                           class="btn btn-warning me-2">

                            <i class="ti ti-edit"></i>
                            Edit

                        </a>


                        {{-- Back --}}
                        <a href="{{ route(
                            'admin.patient-medical-reports.index'
                        ) }}"
                           class="btn btn-light">

                            <i class="ti ti-arrow-left"></i>
                            Back

                        </a>

                    </div>

                </div>

            </div>


            <div class="row g-3">


                {{-- Patient Information --}}
                <div class="col-md-6">

                    <div class="card h-100">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Patient Information
                            </h5>

                        </div>


                        <div class="card-body">


                            {{-- Patient --}}
                            <div class="mb-4">

                                <label class="text-muted d-block mb-1">
                                    Patient Name
                                </label>

                                <h6 class="mb-0">

                                    {{ $report->customer?->name ?? '-' }}

                                </h6>

                            </div>


                            {{-- Family Member --}}
                            <div class="mb-4">

                                <label class="text-muted d-block mb-1">
                                    Family Member
                                </label>

                                <h6 class="mb-0">

                                    {{ $report->familyMember?->name ?? '-' }}

                                </h6>

                            </div>


                            {{-- Doctor --}}
                            <div class="mb-4">

                                <label class="text-muted d-block mb-1">
                                    Doctor
                                </label>

                                <h6 class="mb-0">

                                    {{ $report->doctor?->doctor_name ?? '-' }}

                                </h6>

                            </div>


                            {{-- Appointment --}}
                            <div>

                                <label class="text-muted d-block mb-1">
                                    Appointment No
                                </label>

                                <h6 class="mb-0">

                                    {{ $report->appointment?->appointment_no ?? '-' }}

                                </h6>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Report Information --}}
                <div class="col-md-6">

                    <div class="card h-100">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Report Information
                            </h5>

                        </div>


                        <div class="card-body">


                            {{-- Report Name --}}
                            <div class="mb-4">

                                <label class="text-muted d-block mb-1">
                                    Report Name
                                </label>

                                <h6 class="mb-0">

                                    {{ $report->report_name ?? '-' }}

                                </h6>

                            </div>


                            {{-- Report Type --}}
                            <div class="mb-4">

                                <label class="text-muted d-block mb-1">
                                    Report Type
                                </label>


                                @if($report->report_type)

                                    <span class="badge bg-info">

                                        {{ $report->report_type }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </div>


                            {{-- Report Date --}}
                            <div class="mb-4">

                                <label class="text-muted d-block mb-1">
                                    Report Date
                                </label>

                                <h6 class="mb-0">

                                    @if($report->report_date)

                                        {{ \Carbon\Carbon::parse(
                                            $report->report_date
                                        )->format('d M Y') }}

                                    @else

                                        -

                                    @endif

                                </h6>

                            </div>


                            {{-- Created At --}}
                            <div>

                                <label class="text-muted d-block mb-1">
                                    Created At
                                </label>

                                <h6 class="mb-0">

                                    @if($report->created_at)

                                        {{ \Carbon\Carbon::parse(
                                            $report->created_at
                                        )->format('d M Y h:i A') }}

                                    @else

                                        -

                                    @endif

                                </h6>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Notes --}}
                <div class="col-12">

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Notes
                            </h5>

                        </div>


                        <div class="card-body">

                            @if($report->notes)

                                <p class="mb-0">
                                    {{ $report->notes }}
                                </p>

                            @else

                                <span class="text-muted">
                                    No notes available.
                                </span>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Report File --}}
                <div class="col-12">

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Report File
                            </h5>

                        </div>


                        <div class="card-body">


                            @if($report->report_file)

                                @php

                                    $extension = strtolower(
                                        pathinfo(
                                            $report->report_file,
                                            PATHINFO_EXTENSION
                                        )
                                    );

                                    $fileUrl = asset(
                                        'storage/' . $report->report_file
                                    );

                                @endphp


                                {{-- Image --}}
                                @if(in_array(
                                    $extension,
                                    ['jpg', 'jpeg', 'png', 'webp']
                                ))

                                    <div class="text-center">

                                        <img src="{{ $fileUrl }}"
                                             alt="Medical Report"
                                             class="img-fluid rounded border"
                                             style="max-height: 600px;">

                                        <div class="mt-3">

                                            <a href="{{ $fileUrl }}"
                                               target="_blank"
                                               class="btn btn-primary">

                                                <i class="ti ti-external-link"></i>
                                                Open Full Image

                                            </a>

                                        </div>

                                    </div>


                                {{-- PDF --}}
                                @elseif($extension === 'pdf')

                                    <div class="text-center py-3">

                                        <div class="mb-3">

                                            <i class="ti ti-file-type-pdf"
                                               style="font-size: 50px;">
                                            </i>

                                        </div>


                                        <h6 class="mb-3">
                                            Medical Report PDF
                                        </h6>


                                        <a href="{{ $fileUrl }}"
                                           target="_blank"
                                           class="btn btn-primary">

                                            <i class="ti ti-file"></i>
                                            Open PDF

                                        </a>

                                    </div>


                                {{-- Other Files --}}
                                @else

                                    <div class="text-center py-3">

                                        <div class="mb-3">

                                            <i class="ti ti-file"
                                               style="font-size: 50px;">
                                            </i>

                                        </div>


                                        <h6 class="mb-3">

                                            {{ basename(
                                                $report->report_file
                                            ) }}

                                        </h6>


                                        <a href="{{ $fileUrl }}"
                                           target="_blank"
                                           class="btn btn-primary">

                                            <i class="ti ti-download"></i>
                                            Open File

                                        </a>

                                    </div>

                                @endif


                            @else

                                <div class="text-center py-4">

                                    <div class="text-muted">

                                        <i class="ti ti-file-off"
                                           style="font-size: 40px;">
                                        </i>

                                        <p class="mb-0 mt-2">
                                            No report file uploaded.
                                        </p>

                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Delete --}}
                <div class="col-12">

                    <div class="card">

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <h6 class="mb-1">
                                        Delete Medical Report
                                    </h6>

                                    <small class="text-muted">
                                        This action cannot be undone.
                                    </small>

                                </div>


                                <form action="{{ route(
                                    'admin.patient-medical-reports.destroy',
                                    $report->id
                                ) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this medical report?');">

                                    @csrf

                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-outline-danger">

                                        <i class="ti ti-trash"></i>
                                        Delete Report

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
