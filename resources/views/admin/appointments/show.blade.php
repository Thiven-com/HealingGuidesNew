<?php $page = 'admin-appointments'; ?>

@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            {{-- PAGE HEADER --}}
            <div class="page-header">

                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Appointment Details</h4>
                        <h6>
                            {{ $appointment->appointment_no }}
                        </h6>
                    </div>
                </div>

                <div class="page-btn">
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">

                        <i class="ti ti-arrow-left me-1"></i>
                        Back to Appointments

                    </a>
                </div>

            </div>


            @php

                $status = strtolower(
                    $appointment->appointment_status ?? 'pending'
                );

                $paymentStatus = strtolower(
                    $appointment->payment_status ?? 'pending'
                );

                $consultationType = strtolower(
                    $appointment->consultation_type ?? ''
                );

                $patient = $appointment->family_member_id
                    ? $appointment->familyMember
                    : $appointment->customer;

            @endphp


            <div class="row">

                {{-- =====================================================
                LEFT SIDE
                ====================================================== --}}

                <div class="col-xl-4 col-lg-5">

                    {{-- APPOINTMENT SUMMARY --}}
                    <div class="card">

                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Appointment Summary
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="text-center mb-4">

                                <span class="avatar avatar-xl bg-primary-transparent mb-3">
                                    <i class="ti ti-calendar-event fs-30 text-primary"></i>
                                </span>

                                <h5 class="mb-1">
                                    {{ $appointment->appointment_no }}
                                </h5>

                                @if($appointment->token_no)

                                    <span class="badge bg-light text-dark border">
                                        Token #{{ $appointment->token_no }}
                                    </span>

                                @endif

                            </div>


                            <div class="border-top pt-3">

                                <div class="d-flex justify-content-between mb-3">

                                    <span class="text-muted">
                                        Appointment Date
                                    </span>

                                    <strong>
                                        {{ $appointment->appointment_date
        ? \Carbon\Carbon::parse(
            $appointment->appointment_date
        )->format('d M Y')
        : '-' }}
                                    </strong>

                                </div>


                                <div class="d-flex justify-content-between mb-3">

                                    <span class="text-muted">
                                        Appointment Time
                                    </span>

                                    <strong>
                                        {{ $appointment->appointment_time
        ? \Carbon\Carbon::parse(
            $appointment->appointment_time
        )->format('h:i A')
        : '-' }}
                                    </strong>

                                </div>


                                <div class="d-flex justify-content-between mb-3">

                                    <span class="text-muted">
                                        Consultation
                                    </span>

                                    <strong>
                                        {{ ucwords(
        str_replace(
            '_',
            ' ',
            $appointment->consultation_type
            ?? '-'
        )
    ) }}
                                    </strong>

                                </div>


                                <div class="d-flex justify-content-between mb-3">

                                    <span class="text-muted">
                                        Payment
                                    </span>

                                    <div>

                                        @if($paymentStatus === 'paid')

                                            <span class="badge bg-success">
                                                Paid
                                            </span>

                                        @elseif($paymentStatus === 'failed')

                                            <span class="badge bg-danger">
                                                Failed
                                            </span>

                                        @elseif($paymentStatus === 'refunded')

                                            <span class="badge bg-info">
                                                Refunded
                                            </span>

                                        @else

                                            <span class="badge bg-warning">
                                                Pending
                                            </span>

                                        @endif

                                    </div>

                                </div>


                                <div class="d-flex justify-content-between">

                                    <span class="text-muted">
                                        Status
                                    </span>

                                    <div>

                                        @if($status === 'pending')

                                            <span class="badge bg-warning">
                                                Pending
                                            </span>

                                        @elseif($status === 'confirmed')

                                            <span class="badge bg-primary">
                                                Confirmed
                                            </span>

                                        @elseif($status === 'ongoing')

                                            <span class="badge bg-info">
                                                Ongoing
                                            </span>

                                        @elseif($status === 'completed')

                                            <span class="badge bg-success">
                                                Completed
                                            </span>

                                        @elseif($status === 'cancelled')

                                            <span class="badge bg-danger">
                                                Cancelled
                                            </span>

                                        @else

                                                                            <span class="badge bg-secondary">

                                                                                {{ ucfirst(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $status
                                                )
                                            ) }}

                                                                            </span>

                                        @endif

                                    </div>

                                </div>

                                @if(
                                                                !in_array($appointment->appointment_status, [
                                                                    'completed',
                                                                    'cancelled',
                                                                    'rejected'
                                                                ])
                                                            )

                                                            <a href="{{ route(
                                        'admin.appointments.reschedule',
                                        $appointment->id
                                    ) }}" class="btn btn-warning">

                                                                <i class="ti ti-calendar-event me-1"></i>

                                                                Reschedule

                                                            </a>

                                @endif

                            </div>

                        </div>

                    </div>


                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">

                                <i class="ti ti-user me-2"></i>

                                Patient Details

                            </h5>

                        </div>


                        <div class="card-body">

                            <div class="d-flex align-items-center mb-4">

                                <span class="avatar avatar-lg bg-success-transparent me-3">

                                    <i class="ti ti-user fs-24 text-success"></i>

                                </span>

                                <div>

                                    <h5 class="mb-1">

                                        {{ $patient->name ?? '-' }}

                                    </h5>

                                    <small class="text-muted">

                                        @if($appointment->family_member_id)

                                            Family Member

                                        @else

                                            Customer

                                        @endif

                                    </small>

                                </div>

                            </div>


                            <div class="mb-3">

                                <small class="text-muted d-block">
                                    Mobile
                                </small>

                                <strong>

                                    {{ $patient->mobile
        ?? $appointment->customer?->mobile
        ?? '-' }}

                                </strong>

                            </div>


                            @if($patient?->gender)

                                <div class="mb-3">

                                    <small class="text-muted d-block">
                                        Gender
                                    </small>

                                    <strong>

                                        {{ ucfirst($patient->gender) }}

                                    </strong>

                                </div>

                            @endif


                            @if(
                                    $appointment->family_member_id &&
                                    isset($patient->relation)
                                )

                                <div>

                                    <small class="text-muted d-block">
                                        Relation
                                    </small>

                                    <strong>

                                        {{ ucfirst($patient->relation) }}

                                    </strong>

                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- DOCTOR DETAILS --}}
                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">

                                <i class="ti ti-stethoscope me-2"></i>

                                Doctor

                            </h5>

                        </div>


                        <div class="card-body">

                            <div class="d-flex align-items-center">

                                @if($appointment->doctor?->photo)

                                                            <img src="{{ asset(
                                        $appointment->doctor->photo
                                    ) }}" width="65" height="65" class="rounded-circle me-3" style="object-fit:cover;"
                                                                alt="">

                                @else

                                    <span class="avatar avatar-lg bg-primary-transparent me-3">

                                        <i class="ti ti-stethoscope fs-24 text-primary"></i>

                                    </span>

                                @endif


                                <div>

                                    <h5 class="mb-1">

                                        {{ $appointment->doctor?->doctor_name
        ?? '-' }}

                                    </h5>


                                    <p class="text-muted mb-1">

                                        {{ $appointment->doctor
        ?->hospitalSpecialization
        ?->specialization
            ?->specialization_name
        ?? $appointment->doctor
            ?->hospitalSpecialization
            ?->specialization
                ?->name
        ?? '-' }}

                                    </p>


                                    <small class="text-muted">

                                        {{ $appointment->doctor?->qualification
        ?? '-' }}

                                    </small>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =====================================================
                RIGHT SIDE
                ====================================================== --}}

                <div class="col-xl-8 col-lg-7">


                    {{-- STATUS ACTIONS --}}
                    @if(
                            !in_array(
                                $status,
                                ['completed', 'cancelled']
                            )
                        )

                        <div class="card">

                            <div class="card-header">

                                <h5 class="card-title mb-0">
                                    Appointment Actions
                                </h5>

                            </div>


                            <div class="card-body">

                                <div class="d-flex flex-wrap gap-2">


                                    @if($status === 'pending')

                                        <button type="button" class="btn btn-primary status-btn" data-status="confirmed">

                                            <i class="ti ti-circle-check me-1"></i>

                                            Confirm Appointment

                                        </button>

                                    @endif


                                    @if($status === 'confirmed')

                                        <button type="button" class="btn btn-info status-btn" data-status="ongoing">

                                            <i class="ti ti-player-play me-1"></i>

                                            Start Appointment

                                        </button>

                                    @endif


                                    @if($status === 'ongoing')

                                        <button type="button" class="btn btn-success status-btn" data-status="completed">

                                            <i class="ti ti-check me-1"></i>

                                            Complete Appointment

                                        </button>

                                    @endif


                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#cancelAppointmentModal">

                                        <i class="ti ti-circle-x me-1"></i>

                                        Cancel

                                    </button>

                                </div>

                            </div>

                        </div>

                    @endif



                    {{-- APPOINTMENT INFORMATION --}}
                    <div class="card">

                        <div class="card-header">

                            <div class="d-flex align-items-center">

                                <span class="avatar avatar-sm bg-primary-transparent me-2">

                                    <i class="ti ti-calendar text-primary"></i>

                                </span>

                                <div>

                                    <h5 class="card-title mb-0">
                                        Appointment Information
                                    </h5>

                                    <small class="text-muted">
                                        Booking and consultation details
                                    </small>

                                </div>

                            </div>

                        </div>


                        <div class="card-body">

                            <div class="row">


                                <div class="col-md-6">

                                    <div class="mb-4">

                                        <small class="text-muted d-block mb-1">
                                            Appointment Number
                                        </small>

                                        <strong>

                                            {{ $appointment->appointment_no }}

                                        </strong>

                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <div class="mb-4">

                                        <small class="text-muted d-block mb-1">
                                            Token Number
                                        </small>

                                        <strong>

                                            {{ $appointment->token_no
        ? '#' . $appointment->token_no
        : '-' }}

                                        </strong>

                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <div class="mb-4">

                                        <small class="text-muted d-block mb-1">
                                            Date
                                        </small>

                                        <strong>

                                            {{ $appointment->appointment_date
        ? \Carbon\Carbon::parse(
            $appointment->appointment_date
        )->format('d M Y')
        : '-' }}

                                        </strong>

                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <div class="mb-4">

                                        <small class="text-muted d-block mb-1">
                                            Time
                                        </small>

                                        <strong>

                                            {{ $appointment->appointment_time
        ? \Carbon\Carbon::parse(
            $appointment->appointment_time
        )->format('h:i A')
        : '-' }}

                                        </strong>

                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <div class="mb-4">

                                        <small class="text-muted d-block mb-1">
                                            Consultation Type
                                        </small>

                                        <strong>

                                            {{ ucwords(
        str_replace(
            '_',
            ' ',
            $appointment->consultation_type
            ?? '-'
        )
    ) }}

                                        </strong>

                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <div class="mb-4">

                                        <small class="text-muted d-block mb-1">
                                            Doctor Schedule
                                        </small>

                                        <strong>

                                            #{{ $appointment->doctor_schedule_id
        ?? '-' }}

                                        </strong>

                                    </div>

                                </div>

                            </div>


                            @if($appointment->remarks)

                                <div class="border-top pt-3">

                                    <small class="text-muted d-block mb-1">
                                        Remarks
                                    </small>

                                    <p class="mb-0">

                                        {{ $appointment->remarks }}

                                    </p>

                                </div>

                            @endif

                        </div>

                    </div>

                    {{-- =====================================================
                    PATIENT VITALS
                    ====================================================== --}}
                    @if($appointment->patientVitals)

                        <div class="card">

                            <div class="card-header">

                                <div class="d-flex align-items-center">

                                    <span class="avatar avatar-sm bg-danger-transparent me-2">
                                        <i class="ti ti-heartbeat text-danger"></i>
                                    </span>

                                    <div>

                                        <h5 class="card-title mb-0">
                                            Patient Vitals
                                        </h5>

                                        <small class="text-muted">
                                            Latest recorded health measurements
                                        </small>

                                    </div>

                                </div>

                            </div>


                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-3 col-sm-6 mb-4">

                                        <small class="text-muted d-block">
                                            Height
                                        </small>

                                        <strong>
                                            {{ $appointment->patientVitals->height ?? '-' }}
                                            @if($appointment->patientVitals->height) cm @endif
                                        </strong>

                                    </div>


                                    <div class="col-md-3 col-sm-6 mb-4">

                                        <small class="text-muted d-block">
                                            Weight
                                        </small>

                                        <strong>
                                            {{ $appointment->patientVitals->weight ?? '-' }}
                                            @if($appointment->patientVitals->weight) kg @endif
                                        </strong>

                                    </div>


                                    <div class="col-md-3 col-sm-6 mb-4">

                                        <small class="text-muted d-block">
                                            Blood Pressure
                                        </small>

                                        <strong>
                                            {{ $appointment->patientVitals->blood_pressure ?? '-' }}
                                        </strong>

                                    </div>


                                    <div class="col-md-3 col-sm-6 mb-4">

                                        <small class="text-muted d-block">
                                            Heart Rate
                                        </small>

                                        <strong>
                                            {{ $appointment->patientVitals->heart_rate ?? '-' }}
                                            @if($appointment->patientVitals->heart_rate) bpm @endif
                                        </strong>

                                    </div>


                                    <div class="col-md-3 col-sm-6 mb-4">

                                        <small class="text-muted d-block">
                                            Temperature
                                        </small>

                                        <strong>
                                            {{ $appointment->patientVitals->temperature ?? '-' }}
                                            @if($appointment->patientVitals->temperature) °C @endif
                                        </strong>

                                    </div>


                                    <div class="col-md-3 col-sm-6 mb-4">

                                        <small class="text-muted d-block">
                                            SpO2
                                        </small>

                                        <strong>
                                            {{ $appointment->patientVitals->spo2 ?? '-' }}
                                            @if($appointment->patientVitals->spo2) % @endif
                                        </strong>

                                    </div>


                                    <div class="col-md-3 col-sm-6 mb-4">

                                        <small class="text-muted d-block">
                                            Blood Sugar
                                        </small>

                                        <strong>
                                            {{ $appointment->patientVitals->blood_sugar ?? '-' }}
                                        </strong>

                                    </div>


                                    <div class="col-md-3 col-sm-6 mb-4">

                                        <small class="text-muted d-block">
                                            BMI
                                        </small>

                                        <strong>
                                            {{ $appointment->patientVitals->bmi ?? '-' }}
                                        </strong>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endif

                    {{-- =====================================================
                    MEDICAL INFORMATION
                    ====================================================== --}}
                    @if(
                            $patient?->blood_group ||
                            $patient?->allergies ||
                            $patient?->medical_conditions
                        )

                        <div class="card">

                            <div class="card-header">

                                <div class="d-flex align-items-center">

                                    <span class="avatar avatar-sm bg-warning-transparent me-2">
                                        <i class="ti ti-notes-medical text-warning"></i>
                                    </span>

                                    <div>

                                        <h5 class="card-title mb-0">
                                            Medical Information
                                        </h5>

                                        <small class="text-muted">
                                            Patient medical background
                                        </small>

                                    </div>

                                </div>

                            </div>


                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-4 mb-3">

                                        <small class="text-muted d-block">
                                            Blood Group
                                        </small>

                                        <strong>
                                            {{ $patient?->blood_group ?? '-' }}
                                        </strong>

                                    </div>


                                    <div class="col-md-4 mb-3">

                                        <small class="text-muted d-block">
                                            Allergies
                                        </small>

                                        <strong>
                                            {{ $patient?->allergies ?? '-' }}
                                        </strong>

                                    </div>


                                    <div class="col-md-4 mb-3">

                                        <small class="text-muted d-block">
                                            Medical Conditions
                                        </small>

                                        <strong>
                                            {{ $patient?->medical_conditions ?? '-' }}
                                        </strong>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- =====================================================
                    PRESCRIPTION
                    ====================================================== --}}
                    @if($appointment->prescription)

                        <div class="card">

                            <div class="card-header">

                                <div class="d-flex align-items-center">

                                    <span class="avatar avatar-sm bg-success-transparent me-2">
                                        <i class="ti ti-prescription text-success"></i>
                                    </span>

                                    <div>

                                        <h5 class="card-title mb-0">
                                            Prescription
                                        </h5>

                                        <small class="text-muted">
                                            Prescribed medicines and instructions
                                        </small>

                                    </div>

                                </div>

                            </div>


                            <div class="card-body">

                                @if($appointment->prescription->notes)

                                    <div class="mb-4">

                                        <small class="text-muted d-block mb-1">
                                            Doctor Instructions
                                        </small>

                                        <p class="mb-0">
                                            {{ $appointment->prescription->notes }}
                                        </p>

                                    </div>

                                @endif


                                @if(
                                        $appointment->prescription->medicines &&
                                        $appointment->prescription->medicines->count()
                                    )

                                    <div class="table-responsive">

                                        <table class="table table-bordered">

                                            <thead>

                                                <tr>

                                                    <th>#</th>
                                                    <th>Medicine</th>
                                                    <th>Dosage</th>
                                                    <th>Frequency</th>
                                                    <th>Duration</th>
                                                    <th>Instructions</th>

                                                </tr>

                                            </thead>


                                            <tbody>

                                                @foreach(
                                                        $appointment->prescription->medicines
                                                        as $key => $medicine
                                                    )

                                                    <tr>

                                                        <td>
                                                            {{ $key + 1 }}
                                                        </td>

                                                        <td>
                                                            <strong>
                                                                {{ $medicine->medicine_name ?? '-' }}
                                                            </strong>
                                                        </td>

                                                        <td>
                                                            {{ $medicine->dosage ?? '-' }}
                                                        </td>

                                                        <td>
                                                            {{ $medicine->frequency ?? '-' }}
                                                        </td>

                                                        <td>
                                                            {{ $medicine->duration ?? '-' }}
                                                        </td>

                                                        <td>
                                                            {{ $medicine->instructions ?? '-' }}
                                                        </td>

                                                    </tr>

                                                @endforeach

                                            </tbody>

                                        </table>

                                    </div>

                                @endif

                            </div>

                        </div>

                    @endif

                    {{-- =====================================================
                    MEDICAL REPORTS
                    ====================================================== --}}
                    @if(
                            $appointment->medicalReports &&
                            $appointment->medicalReports->count()
                        )

                        <div class="card">

                            <div class="card-header">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div class="d-flex align-items-center">

                                        <span class="avatar avatar-sm bg-info-transparent me-2">
                                            <i class="ti ti-file-medical text-info"></i>
                                        </span>

                                        <div>

                                            <h5 class="card-title mb-0">
                                                Medical Reports
                                            </h5>

                                            <small class="text-muted">
                                                Uploaded patient reports
                                            </small>

                                        </div>

                                    </div>


                                    <span class="badge bg-primary">

                                        {{ $appointment->medicalReports->count() }}

                                    </span>

                                </div>

                            </div>


                            <div class="card-body">

                                <div class="table-responsive">

                                    <table class="table table-hover">

                                        <thead>

                                            <tr>

                                                <th>#</th>
                                                <th>Report Name</th>
                                                <th>Report Type</th>
                                                <th>Uploaded Date</th>
                                                <th class="text-end">Action</th>

                                            </tr>

                                        </thead>


                                        <tbody>

                                            @foreach(
                                                                                $appointment->medicalReports
                                                                                as $key => $report
                                                                            )

                                                                            <tr>

                                                                                <td>
                                                                                    {{ $key + 1 }}
                                                                                </td>

                                                                                <td>

                                                                                    <strong>
                                                                                        {{ $report->report_name
                                                    ?? $report->title
                                                    ?? 'Medical Report' }}
                                                                                    </strong>

                                                                                </td>

                                                                                <td>

                                                                                    {{ ucfirst(
                                                    $report->report_type
                                                    ?? 'Report'
                                                ) }}

                                                                                </td>

                                                                                <td>

                                                                                    {{ $report->created_at
                                                    ? $report->created_at->format('d M Y')
                                                    : '-' }}

                                                                                </td>

                                                                                <td class="text-end">

                                                                                    @php

                                                                                        $reportFile =
                                                                                            $report->file
                                                                                            ?? $report->report_file
                                                                                            ?? $report->document
                                                                                            ?? null;

                                                                                    @endphp


                                                                                    @if($reportFile)

                                                                                                                        <a href="{{ asset(
                                                                                            $reportFile
                                                                                        ) }}" target="_blank"
                                                                                                                            class="btn btn-sm btn-outline-primary">

                                                                                                                            <i class="ti ti-eye me-1"></i>
                                                                                                                            View

                                                                                                                        </a>

                                                                                    @else

                                                                                        <span class="text-muted">
                                                                                            No File
                                                                                        </span>

                                                                                    @endif

                                                                                </td>

                                                                            </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    @endif

                    {{-- =====================================================
                    LAB TESTS
                    ====================================================== --}}

                    @if($appointment->labTests && $appointment->labTests->count())

                        <div class="card">

                            <div class="card-header">

                                <div class="d-flex align-items-center">

                                    <span class="avatar avatar-sm bg-warning-transparent me-2">
                                        <i class="ti ti-microscope text-warning"></i>
                                    </span>

                                    <div>

                                        <h5 class="card-title mb-0">
                                            Lab Tests
                                        </h5>

                                        <small class="text-muted">
                                            Laboratory test details
                                        </small>

                                    </div>

                                </div>

                            </div>

                            <div class="card-body">

                                <div class="table-responsive">

                                    <table class="table table-bordered">

                                        <thead>

                                            <tr>
                                                <th>Test Name</th>
                                                <th>Test Date</th>
                                                <th>Status</th>
                                                <th>Result</th>
                                            </tr>

                                        </thead>

                                        <tbody>

                                            @foreach($appointment->labTests as $test)

                                                                        <tr>

                                                                            <td>
                                                                                <strong>
                                                                                    {{ $test->test_name
                                                ?? $test->name
                                                ?? '-' }}
                                                                                </strong>
                                                                            </td>

                                                                            <td>
                                                                                {{ $test->test_date
                                                ? \Carbon\Carbon::parse($test->test_date)->format('d M Y')
                                                : '-' }}
                                                                            </td>

                                                                            <td>

                                                                                @php
                                                                                    $testStatus = strtolower($test->status ?? 'pending');
                                                                                @endphp

                                                                                @if($testStatus === 'completed')
                                                                                    <span class="badge bg-success">
                                                                                        Completed
                                                                                    </span>
                                                                                @elseif($testStatus === 'cancelled')
                                                                                    <span class="badge bg-danger">
                                                                                        Cancelled
                                                                                    </span>
                                                                                @else
                                                                                    <span class="badge bg-warning">
                                                                                        Pending
                                                                                    </span>
                                                                                @endif

                                                                            </td>

                                                                            <td>
                                                                                {{ $test->result ?? '-' }}
                                                                            </td>

                                                                        </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    @endif

                    {{-- =====================================================
                    MEETINGS
                    ====================================================== --}}

                    @if($appointment->meetings && $appointment->meetings->count())

                        <div class="card">

                            <div class="card-header">

                                <div class="d-flex align-items-center">

                                    <span class="avatar avatar-sm bg-primary-transparent me-2">
                                        <i class="ti ti-video text-primary"></i>
                                    </span>

                                    <div>

                                        <h5 class="card-title mb-0">
                                            Meetings
                                        </h5>

                                        <small class="text-muted">
                                            Online consultation meetings
                                        </small>

                                    </div>

                                </div>

                            </div>

                            <div class="card-body">

                                @foreach($appointment->meetings as $meeting)

                                                <div class="border rounded p-3 mb-3">

                                                    <div class="row">

                                                        <div class="col-md-6 mb-3">

                                                            <small class="text-muted d-block">
                                                                Meeting Provider
                                                            </small>

                                                            <strong>
                                                                {{ ucfirst($meeting->provider ?? '-') }}
                                                            </strong>

                                                        </div>

                                                        <div class="col-md-6 mb-3">

                                                            <small class="text-muted d-block">
                                                                Meeting ID
                                                            </small>

                                                            <strong>
                                                                {{ $meeting->meeting_id ?? '-' }}
                                                            </strong>

                                                        </div>

                                                        <div class="col-md-6 mb-3">

                                                            <small class="text-muted d-block">
                                                                Meeting Status
                                                            </small>

                                                            <strong>

                                                                {{ $meeting->status
                                    ? ucfirst($meeting->status)
                                    : '-' }}

                                                            </strong>

                                                        </div>

                                                        <div class="col-md-6 mb-3">

                                                            <small class="text-muted d-block">
                                                                Scheduled At
                                                            </small>

                                                            <strong>

                                                                {{ $meeting->scheduled_at
                                    ? \Carbon\Carbon::parse($meeting->scheduled_at)->format('d M Y, h:i A')
                                    : '-' }}

                                                            </strong>

                                                        </div>

                                                        @if($meeting->meeting_link)

                                                            <div class="col-12">

                                                                <a href="{{ $meeting->meeting_link }}" target="_blank" class="btn btn-primary">

                                                                    <i class="ti ti-video me-1"></i>
                                                                    Join Meeting

                                                                </a>

                                                            </div>

                                                        @endif

                                                    </div>

                                                </div>

                                @endforeach

                            </div>

                        </div>

                    @endif



                    {{-- =====================================================
                    VIDEO CONSULTATION
                    ====================================================== --}}

                    @if($consultationType === 'video')

                                <div class="card">

                                    <div class="card-header">

                                        <div class="d-flex align-items-center">

                                            <span class="avatar avatar-sm bg-info-transparent me-2">

                                                <i class="ti ti-video text-info"></i>

                                            </span>

                                            <div>

                                                <h5 class="card-title mb-0">
                                                    Video Consultation
                                                </h5>

                                                <small class="text-muted">
                                                    Online meeting information
                                                </small>

                                            </div>

                                        </div>

                                    </div>


                                    <div class="card-body">

                                        <div class="row">


                                            <div class="col-md-6">

                                                <div class="mb-4">

                                                    <small class="text-muted d-block">
                                                        Meeting Provider
                                                    </small>

                                                    <strong>

                                                        {{ $appointment->meeting_provider
                        ? ucfirst(
                            $appointment->meeting_provider
                        )
                        : '-' }}

                                                    </strong>

                                                </div>

                                            </div>


                                            <div class="col-md-6">

                                                <div class="mb-4">

                                                    <small class="text-muted d-block">
                                                        Meeting ID
                                                    </small>

                                                    <strong>

                                                        {{ $appointment->meeting_id
                        ?? '-' }}

                                                    </strong>

                                                </div>

                                            </div>


                                            <div class="col-md-6">

                                                <div class="mb-4">

                                                    <small class="text-muted d-block">
                                                        Meeting Password
                                                    </small>

                                                    <strong>

                                                        {{ $appointment->meeting_password
                        ?? '-' }}

                                                    </strong>

                                                </div>

                                            </div>


                                            <div class="col-md-6">

                                                <div class="mb-4">

                                                    <small class="text-muted d-block">
                                                        Meeting Status
                                                    </small>

                                                    <strong>

                                                        {{ $appointment->meeting_status
                        ? ucfirst(
                            $appointment->meeting_status
                        )
                        : '-' }}

                                                    </strong>

                                                </div>

                                            </div>


                                            @if($appointment->meeting_link)

                                                <div class="col-12">

                                                    <a href="{{ $appointment->meeting_link }}" target="_blank" class="btn btn-info">

                                                        <i class="ti ti-video me-1"></i>

                                                        Open Meeting

                                                    </a>

                                                </div>

                                            @endif


                                            @if(
                                                                    $appointment->meeting_started_at ||
                                                                    $appointment->meeting_ended_at
                                                                )

                                                                <div class="col-12">

                                                                    <hr>

                                                                </div>


                                                                <div class="col-md-6">

                                                                    <small class="text-muted d-block">
                                                                        Started At
                                                                    </small>

                                                                    <strong>

                                                                        {{ $appointment->meeting_started_at
                                                ? \Carbon\Carbon::parse(
                                                    $appointment->meeting_started_at
                                                )->format('d M Y, h:i A')
                                                : '-' }}

                                                                    </strong>

                                                                </div>


                                                                <div class="col-md-6">

                                                                    <small class="text-muted d-block">
                                                                        Ended At
                                                                    </small>

                                                                    <strong>

                                                                        {{ $appointment->meeting_ended_at
                                                ? \Carbon\Carbon::parse(
                                                    $appointment->meeting_ended_at
                                                )->format('d M Y, h:i A')
                                                : '-' }}

                                                                    </strong>

                                                                </div>

                                            @endif

                                        </div>

                                    </div>

                                </div>

                    @endif



                    {{-- =====================================================
                    CHAT CONSULTATION
                    ====================================================== --}}

                    @if($consultationType === 'chat')

                                <div class="card">

                                    <div class="card-header">

                                        <div class="d-flex align-items-center">

                                            <span class="avatar avatar-sm bg-success-transparent me-2">

                                                <i class="ti ti-message text-success"></i>

                                            </span>

                                            <div>

                                                <h5 class="card-title mb-0">
                                                    Chat Consultation
                                                </h5>

                                                <small class="text-muted">
                                                    Chat session information
                                                </small>

                                            </div>

                                        </div>

                                    </div>


                                    <div class="card-body">

                                        <div class="row">

                                            <div class="col-md-6">

                                                <small class="text-muted d-block mb-1">
                                                    Chat Started
                                                </small>

                                                <strong>

                                                    {{ $appointment->chat_started_at
                        ? \Carbon\Carbon::parse(
                            $appointment->chat_started_at
                        )->format('d M Y, h:i A')
                        : 'Not Started' }}

                                                </strong>

                                            </div>


                                            <div class="col-md-6">

                                                <small class="text-muted d-block mb-1">
                                                    Chat Ended
                                                </small>

                                                <strong>

                                                    {{ $appointment->chat_ended_at
                        ? \Carbon\Carbon::parse(
                            $appointment->chat_ended_at
                        )->format('d M Y, h:i A')
                        : 'Not Ended' }}

                                                </strong>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                    @endif



                    {{-- =====================================================
                    HOME VISIT
                    ====================================================== --}}

                    @if(
                                    $consultationType === 'home_visit' ||
                                    $consultationType === 'home visit'
                                )

                                <div class="card">

                                    <div class="card-header">

                                        <div class="d-flex align-items-center">

                                            <span class="avatar avatar-sm bg-warning-transparent me-2">

                                                <i class="ti ti-home-heart text-warning"></i>

                                            </span>

                                            <div>

                                                <h5 class="card-title mb-0">
                                                    Home Visit Details
                                                </h5>

                                                <small class="text-muted">
                                                    Patient visit location
                                                </small>

                                            </div>

                                        </div>

                                    </div>


                                    <div class="card-body">

                                        <div class="row">


                                            <div class="col-12">

                                                <div class="mb-4">

                                                    <small class="text-muted d-block mb-1">
                                                        Visit Address
                                                    </small>

                                                    <strong>

                                                        {{ $appointment->visit_address
                        ?? '-' }}

                                                    </strong>

                                                </div>

                                            </div>


                                            <div class="col-md-4">

                                                <div class="mb-4">

                                                    <small class="text-muted d-block">
                                                        City
                                                    </small>

                                                    <strong>

                                                        {{ $appointment->visit_city
                        ?? '-' }}

                                                    </strong>

                                                </div>

                                            </div>


                                            <div class="col-md-4">

                                                <div class="mb-4">

                                                    <small class="text-muted d-block">
                                                        State
                                                    </small>

                                                    <strong>

                                                        {{ $appointment->visit_state
                        ?? '-' }}

                                                    </strong>

                                                </div>

                                            </div>


                                            <div class="col-md-4">

                                                <div class="mb-4">

                                                    <small class="text-muted d-block">
                                                        Pincode
                                                    </small>

                                                    <strong>

                                                        {{ $appointment->visit_pincode
                        ?? '-' }}

                                                    </strong>

                                                </div>

                                            </div>


                                            <div class="col-md-6">

                                                <small class="text-muted d-block">
                                                    Visit Status
                                                </small>

                                                <strong>

                                                    {{ $appointment->visit_status
                        ? ucwords(
                            str_replace(
                                '_',
                                ' ',
                                $appointment->visit_status
                            )
                        )
                        : '-' }}

                                                </strong>

                                            </div>


                                            @if(
                                                    $appointment->visit_latitude &&
                                                    $appointment->visit_longitude
                                                )

                                                <div class="col-md-6 text-md-end">

                                                    <a href="https://www.google.com/maps?q={{ $appointment->visit_latitude }},{{ $appointment->visit_longitude }}"
                                                        target="_blank" class="btn btn-outline-primary btn-sm">

                                                        <i class="ti ti-map-pin me-1"></i>

                                                        View Location

                                                    </a>

                                                </div>

                                            @endif

                                        </div>

                                    </div>

                                </div>

                    @endif



                    {{-- =====================================================
                    PAYMENT SUMMARY
                    ====================================================== --}}

                    <div class="card">

                        <div class="card-header">

                            <div class="d-flex align-items-center">

                                <span class="avatar avatar-sm bg-success-transparent me-2">

                                    <i class="ti ti-currency-rupee text-success"></i>

                                </span>

                                <div>

                                    <h5 class="card-title mb-0">
                                        Payment Summary
                                    </h5>

                                    <small class="text-muted">
                                        Appointment charges
                                    </small>

                                </div>

                            </div>

                        </div>


                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-borderless mb-0">

                                    <tbody>

                                        <tr>

                                            <td>
                                                Consultation Fee
                                            </td>

                                            <td class="text-end">

                                                ₹{{ number_format(
        (float) (
            $appointment->consultation_fee
            ?? 0
        ),
        2
    ) }}

                                            </td>

                                        </tr>


                                        <tr>

                                            <td>
                                                Discount
                                            </td>

                                            <td class="text-end text-success">

                                                - ₹{{ number_format(
        (float) (
            $appointment->discount
            ?? 0
        ),
        2
    ) }}

                                            </td>

                                        </tr>


                                        <tr>

                                            <td>
                                                Tax
                                            </td>

                                            <td class="text-end">

                                                ₹{{ number_format(
        (float) (
            $appointment->tax
            ?? 0
        ),
        2
    ) }}

                                            </td>

                                        </tr>


                                        <tr class="border-top">

                                            <td>

                                                <strong>
                                                    Total Amount
                                                </strong>

                                            </td>

                                            <td class="text-end">

                                                <h5 class="mb-0 text-primary">

                                                    ₹{{ number_format(
        (float) (
            $appointment->total_amount
            ?? 0
        ),
        2
    ) }}

                                                </h5>

                                            </td>

                                        </tr>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>




                    {{-- =====================================================
                    CANCELLATION DETAILS
                    ====================================================== --}}

                    @if($status === 'cancelled')

                                <div class="card border-danger">

                                    <div class="card-header">

                                        <h5 class="card-title text-danger mb-0">

                                            <i class="ti ti-circle-x me-2"></i>

                                            Cancellation Details

                                        </h5>

                                    </div>


                                    <div class="card-body">

                                        <div class="mb-3">

                                            <small class="text-muted d-block">
                                                Reason
                                            </small>

                                            <strong>

                                                {{ $appointment->cancel_reason
                        ?? '-' }}

                                            </strong>

                                        </div>


                                        <div>

                                            <small class="text-muted d-block">
                                                Cancelled At
                                            </small>

                                            <strong>

                                                {{ $appointment->cancelled_at
                        ? \Carbon\Carbon::parse(
                            $appointment->cancelled_at
                        )->format('d M Y, h:i A')
                        : '-' }}

                                            </strong>

                                        </div>

                                    </div>

                                </div>

                    @endif


                    {{-- CREATED INFORMATION --}}
                    <div class="card">

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-6">

                                    <small class="text-muted d-block">
                                        Booked On
                                    </small>

                                    <strong>

                                        {{ $appointment->created_at
        ? $appointment->created_at->format(
            'd M Y, h:i A'
        )
        : '-' }}

                                    </strong>

                                </div>


                                <div class="col-md-6">

                                    <small class="text-muted d-block">
                                        Last Updated
                                    </small>

                                    <strong>

                                        {{ $appointment->updated_at
        ? $appointment->updated_at->format(
            'd M Y, h:i A'
        )
        : '-' }}

                                    </strong>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>



    {{-- =====================================================
    CANCEL MODAL
    ====================================================== --}}

    <div class="modal fade" id="cancelAppointmentModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">

                        <i class="ti ti-alert-circle text-danger me-2"></i>

                        Cancel Appointment

                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">

                            Cancellation Reason

                            <span class="text-danger">*</span>

                        </label>

                        <textarea id="cancelReason" rows="4" class="form-control"
                            placeholder="Enter cancellation reason..."></textarea>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">

                        Close

                    </button>

                    <button type="button" class="btn btn-danger" id="confirmCancel">

                        Cancel Appointment

                    </button>

                </div>

            </div>

        </div>

    </div>



    <script>

        document.addEventListener('DOMContentLoaded', function () {

            /*
            |--------------------------------------------------------------------------
            | Normal Status Update
            |--------------------------------------------------------------------------
            */

            document
                .querySelectorAll('.status-btn')
                .forEach(function (button) {

                    button.addEventListener('click', function () {

                        const status =
                            this.dataset.status;

                        let message =
                            'Update appointment status?';

                        if (status === 'confirmed') {

                            message =
                                'Confirm this appointment?';

                        } else if (status === 'ongoing') {

                            message =
                                'Start this appointment?';

                        } else if (status === 'completed') {

                            message =
                                'Mark this appointment as completed?';

                        }


                        if (!confirm(message)) {
                            return;
                        }


                        updateStatus(
                            status
                        );

                    });

                });



            /*
            |--------------------------------------------------------------------------
            | Cancel Appointment
            |--------------------------------------------------------------------------
            */

            const confirmCancel =
                document.getElementById(
                    'confirmCancel'
                );


            if (confirmCancel) {

                confirmCancel.addEventListener(
                    'click',
                    function () {

                        const reason =
                            document
                                .getElementById(
                                    'cancelReason'
                                )
                                .value
                                .trim();


                        if (!reason) {

                            alert(
                                'Please enter cancellation reason.'
                            );

                            return;
                        }


                        updateStatus(
                            'cancelled',
                            reason
                        );

                    }
                );

            }



            /*
            |--------------------------------------------------------------------------
            | Status Request
            |--------------------------------------------------------------------------
            */

            function updateStatus(
                status,
                cancelReason = null
            ) {

                const payload = {

                    id:
                        "{{ $appointment->id }}",

                    appointment_status:
                        status

                };


                if (cancelReason) {

                    payload.cancel_reason =
                        cancelReason;

                }


                fetch(
                    "{{ route('admin.appointments.status') }}",
                    {

                        method: 'POST',

                        headers: {

                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json',

                            'X-CSRF-TOKEN':
                                "{{ csrf_token() }}"

                        },

                        body:
                            JSON.stringify(payload)

                    }
                )

                    .then(response => response.json())

                    .then(data => {

                        if (data.success == 1) {

                            window.location.reload();

                            return;

                        }


                        alert(
                            data.message
                            ?? 'Unable to update appointment.'
                        );

                    })

                    .catch(error => {

                        console.error(error);

                        alert(
                            'Something went wrong.'
                        );

                    });

            }

        });

    </script>

@endsection