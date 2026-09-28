<?php $page = 'hospital-patients'; ?>

@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            {{-- PAGE HEADER --}}
            <div class="page-header">
                <div class="add-item d-flex">

                    <div class="page-title">
                        <h4>Patient Details</h4>
                        <h6>View patient information</h6>
                    </div>

                </div>

                <div class="page-btn">

                    <a href="{{ route('hospital.patients.all', [
        'hospital_id' => $appointment->hospital_id
    ]) }}" class="btn btn-secondary">

                        <i class="ti ti-arrow-left me-1"></i>
                        Back to Patients

                    </a>

                </div>
            </div>


            {{-- PATIENT DETAILS --}}
            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-0">
                        Patient Information
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row">

                        {{-- PHOTO --}}
                        <div class="col-md-3 text-center mb-4">

                            @if(!empty($patient->photo))

                                <img src="{{ asset($patient->photo) }}" alt="Patient Photo" class="img-fluid rounded" style="
                                            width:150px;
                                            height:150px;
                                            object-fit:cover;
                                         ">

                            @else

                                <div class="rounded bg-light d-flex align-items-center justify-content-center mx-auto" style="
                                            width:150px;
                                            height:150px;
                                         ">

                                    <i class="ti ti-user fs-60 text-muted"></i>

                                </div>

                            @endif

                            <h5 class="mt-3 mb-1">
                                {{ $patient->name ?? '—' }}
                            </h5>

                            <span class="badge bg-primary">
                                {{ $type === 'family' ? 'Family Member' : 'Customer' }}
                            </span>

                        </div>


                        {{-- BASIC INFORMATION --}}
                        <div class="col-md-9">

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label class="form-label text-muted">
                                        Name
                                    </label>

                                    <h6>
                                        {{ $patient->name ?? '—' }}
                                    </h6>

                                </div>


                                <div class="col-md-6 mb-3">

                                    <label class="form-label text-muted">
                                        Mobile
                                    </label>

                                    <h6>
                                        {{ $patient->mobile ?? '—' }}
                                    </h6>

                                </div>


                                <div class="col-md-6 mb-3">

                                    <label class="form-label text-muted">
                                        Email
                                    </label>

                                    <h6>
                                        {{ $patient->email ?? '—' }}
                                    </h6>

                                </div>


                                <div class="col-md-6 mb-3">

                                    <label class="form-label text-muted">
                                        Gender
                                    </label>

                                    <h6>
                                        {{ $patient->gender ?? '—' }}
                                    </h6>

                                </div>


                                <div class="col-md-6 mb-3">

                                    <label class="form-label text-muted">
                                        Date of Birth
                                    </label>

                                    <h6>
                                        @if($patient->dob)
                                            {{ \Carbon\Carbon::parse($patient->dob)->format('d-m-Y') }}
                                        @else
                                            —
                                        @endif
                                    </h6>

                                </div>


                                <div class="col-md-6 mb-3">

                                    <label class="form-label text-muted">
                                        Age
                                    </label>

                                    <h6>
                                        {{ $patient->age ?? '—' }}
                                    </h6>

                                </div>


                                <div class="col-md-6 mb-3">

                                    <label class="form-label text-muted">
                                        Blood Group
                                    </label>

                                    <h6>
                                        {{ $patient->blood_group ?? '—' }}
                                    </h6>

                                </div>


                                <div class="col-md-6 mb-3">

                                    <label class="form-label text-muted">
                                        Occupation
                                    </label>

                                    <h6>
                                        {{ $patient->occupation ?? '—' }}
                                    </h6>

                                </div>

                            </div>

                        </div>

                    </div>


                    <hr>


                    {{-- PHYSICAL DETAILS --}}
                    <h5 class="mb-3">
                        Physical Details
                    </h5>

                    <div class="row">

                        <div class="col-md-4 mb-3">

                            <label class="form-label text-muted">
                                Height
                            </label>

                            <h6>
                                {{ $patient->height ?? '—' }}
                            </h6>

                        </div>


                        <div class="col-md-4 mb-3">

                            <label class="form-label text-muted">
                                Weight
                            </label>

                            <h6>
                                {{ $patient->weight ?? '—' }}
                            </h6>

                        </div>


                        <div class="col-md-4 mb-3">

                            <label class="form-label text-muted">
                                Blood Group
                            </label>

                            <h6>
                                {{ $patient->blood_group ?? '—' }}
                            </h6>

                        </div>

                    </div>


                    <hr>


                    {{-- ADDRESS --}}
                    <h5 class="mb-3">
                        Address Details
                    </h5>

                    <div class="row">

                        <div class="col-md-12 mb-3">

                            <label class="form-label text-muted">
                                Address
                            </label>

                            <h6>
                                {{ $patient->address ?? '—' }}
                            </h6>

                        </div>


                        <div class="col-md-3 mb-3">

                            <label class="form-label text-muted">
                                City
                            </label>

                            <h6>
                                {{ $patient->city ?? '—' }}
                            </h6>

                        </div>


                        <div class="col-md-3 mb-3">

                            <label class="form-label text-muted">
                                State
                            </label>

                            <h6>
                                {{ $patient->state ?? '—' }}
                            </h6>

                        </div>


                        <div class="col-md-3 mb-3">

                            <label class="form-label text-muted">
                                Country
                            </label>

                            <h6>
                                {{ $patient->country ?? '—' }}
                            </h6>

                        </div>


                        <div class="col-md-3 mb-3">

                            <label class="form-label text-muted">
                                Pincode
                            </label>

                            <h6>
                                {{ $patient->pincode ?? '—' }}
                            </h6>

                        </div>

                    </div>


                    <hr>


                    {{-- EMERGENCY CONTACT --}}
                    <h5 class="mb-3">
                        Emergency Contact
                    </h5>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label text-muted">
                                Contact Name
                            </label>

                            <h6>
                                {{ $patient->emergency_contact_name ?? '—' }}
                            </h6>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="form-label text-muted">
                                Contact Mobile
                            </label>

                            <h6>
                                {{ $patient->emergency_contact_mobile ?? '—' }}
                            </h6>

                        </div>

                    </div>


                    <hr>


                    {{-- APPOINTMENT INFORMATION --}}
                    <h5 class="mb-3">
                        Latest Appointment
                    </h5>

                    <div class="row">

                        <div class="col-md-4 mb-3">

                            <label class="form-label text-muted">
                                Appointment No
                            </label>

                            <h6>
                                {{ $appointment->appointment_no ?? '—' }}
                            </h6>

                        </div>


                        <div class="col-md-4 mb-3">

                            <label class="form-label text-muted">
                                Appointment Date
                            </label>

                            <h6>
                                @if($appointment->appointment_date)
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d-m-Y') }}
                                @else
                                    —
                                @endif
                            </h6>

                        </div>


                        <div class="col-md-4 mb-3">

                            <label class="form-label text-muted">
                                Appointment Time
                            </label>

                            <h6>
                                {{ $appointment->appointment_time ?? '—' }}
                            </h6>

                        </div>


                        <div class="col-md-4 mb-3">

                            <label class="form-label text-muted">
                                Consultation Type
                            </label>

                            <h6>
                                {{ $appointment->consultation_type ?? '—' }}
                            </h6>

                        </div>


                        <div class="col-md-4 mb-3">

                            <label class="form-label text-muted">
                                Consultation Fee
                            </label>

                            <h6>
                                ₹{{ number_format($appointment->consultation_fee ?? 0, 2) }}
                            </h6>

                        </div>


                        <div class="col-md-4 mb-3">

                            <label class="form-label text-muted">
                                Payment Status
                            </label>

                            <h6>
                                {{ ucfirst($appointment->payment_status ?? '—') }}
                            </h6>

                        </div>


                        <div class="col-md-4 mb-3">

                            <label class="form-label text-muted">
                                Appointment Status
                            </label>

                            <h6>
                                {{ ucfirst($appointment->appointment_status ?? '—') }}
                            </h6>

                        </div>

                    </div>
<hr>

{{-- =========================================================
     MEDICAL REPORTS
========================================================= --}}

<div class="d-flex align-items-center justify-content-between mb-3">

    <div>
        <h5 class="mb-1">
            Medical Reports
        </h5>

        <p class="text-muted mb-0">
            Patient medical reports and documents
        </p>
    </div>

    <span class="badge bg-primary">
        {{ $reports->count() }} Reports
    </span>

</div>


@if($reports->count())

    <div class="row">

        @foreach($reports as $report)

            <div class="col-md-6 col-lg-4 mb-4">

                <div class="card border shadow-sm h-100">

                    <div class="card-body">

                        {{-- Report Icon --}}
                        <div class="d-flex align-items-center mb-3">

                            <div class="rounded-circle bg-light-primary d-flex align-items-center justify-content-center"
                                 style="
                                    width:48px;
                                    height:48px;
                                 ">

                                <i class="ti ti-file-medical fs-24 text-primary"></i>

                            </div>

                            <div class="ms-3">

                                <h6 class="mb-1">

                                    {{ $report->report_name ?? 'Medical Report' }}

                                </h6>

                                <small class="text-muted">

                                    {{ $report->report_type ?? 'Medical Report' }}

                                </small>

                            </div>

                        </div>


                        {{-- Report Date --}}
                        <div class="mb-2">

                            <small class="text-muted d-block">
                                Report Date
                            </small>

                            <strong>

                                @if($report->report_date)

                                    {{ \Carbon\Carbon::parse($report->report_date)->format('d-m-Y') }}

                                @else

                                    —

                                @endif

                            </strong>

                        </div>


                        {{-- Doctor --}}
                        @if($report->doctor)

                            <div class="mb-2">

                                <small class="text-muted d-block">
                                    Doctor
                                </small>

                                <strong>
                                    {{ $report->doctor->name ?? '—' }}
                                </strong>

                            </div>

                        @endif


                        {{-- Appointment --}}
                        @if($report->appointment)

                            <div class="mb-2">

                                <small class="text-muted d-block">
                                    Appointment
                                </small>

                                <strong>
                                    {{ $report->appointment->appointment_no ?? '—' }}
                                </strong>

                            </div>

                        @endif


                        {{-- Notes --}}
                        @if($report->notes)

                            <div class="mb-3">

                                <small class="text-muted d-block">
                                    Notes
                                </small>

                                <p class="mb-0">
                                    {{ $report->notes }}
                                </p>

                            </div>

                        @endif


                        {{-- File --}}
                        @if($report->report_file)

                            <div class="mt-3">

                                <a href="{{ asset($report->report_file) }}"
                                   target="_blank"
                                   class="btn btn-primary btn-sm">

                                    <i class="ti ti-eye me-1"></i>

                                    View Report

                                </a>

                                <a href="{{ asset($report->report_file) }}"
                                   download
                                   class="btn btn-outline-secondary btn-sm">

                                    <i class="ti ti-download me-1"></i>

                                    Download

                                </a>

                            </div>

                        @else

                            <span class="text-muted">
                                No report file available
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        @endforeach

    </div>

@else

    <div class="card border">

        <div class="card-body text-center py-5">

            <div class="mb-3">

                <i class="ti ti-file-off"
                   style="
                       font-size:50px;
                       color:#adb5bd;
                   ">
                </i>

            </div>

            <h6>
                No Medical Reports
            </h6>

            <p class="text-muted mb-0">
                No medical reports are available for this patient.
            </p>

        </div>

    </div>

@endif

                </div>

            </div>

        </div>

    </div>

@endsection