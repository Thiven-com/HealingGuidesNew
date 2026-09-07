<?php $page = 'hospital-doctors'; ?>

@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            {{-- =====================================================
            PAGE HEADER
            ====================================================== --}}

            <div class="page-header">

                <div class="add-item d-flex">

                    <div class="page-title">

                        <h4>Doctor Details</h4>

                        <h6>
                            View doctor profile and professional information
                        </h6>

                    </div>

                </div>


                <div class="page-btn d-flex gap-2">

                    <a href="{{ route('hospital.doctors.index') }}" class="btn btn-secondary">

                        <i class="ti ti-arrow-left me-1"></i>

                        Back

                    </a>


                    <a href="{{ route('hospital.doctors.edit', $doctor->id) }}" class="btn btn-primary">

                        <i class="ti ti-edit me-1"></i>

                        Edit Doctor

                    </a>

                </div>

            </div>



            {{-- =====================================================
            SUCCESS MESSAGE
            ====================================================== --}}

            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">

                    <i class="ti ti-circle-check me-2"></i>

                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>

            @endif



            <div class="row">


                {{-- =====================================================
                LEFT SIDE - PROFILE
                ====================================================== --}}

                <div class="col-xl-4 col-lg-5">

                    <div class="card">

                        <div class="card-body text-center">


                            {{-- Photo --}}

                            <div class="mb-3">

                                @if($doctor->photo)

                                    <img src="{{ asset($doctor->photo) }}" alt="{{ $doctor->doctor_name }}"
                                        class="rounded-circle border" width="120" height="120" style="object-fit: cover;">

                                @else

                                                            <div class="avatar avatar-xxl bg-primary-transparent rounded-circle mx-auto"
                                                                style="
                                                                                                                                                                                                                                                                                                        width:120px;
                                                                                                                                                                                                                                                                                                        height:120px;
                                                                                                                                                                                                                                                                                                        display:flex;
                                                                                                                                                                                                                                                                                                        align-items:center;
                                                                                                                                                                                                                                                                                                        justify-content:center;
                                                                                                                                                                                                                                                                                                    ">

                                                                <span class="fs-32 fw-bold text-primary">

                                                                    {{ strtoupper(
                                        substr(
                                            $doctor->doctor_name,
                                            0,
                                            1
                                        )
                                    ) }}

                                                                </span>

                                                            </div>

                                @endif

                            </div>



                            {{-- Name --}}

                            <h4 class="mb-1">

                                {{ $doctor->doctor_name }}

                            </h4>


                            <p class="text-muted mb-2">

                                {{ $doctor->designation ?? 'Doctor' }}

                            </p>



                            {{-- Doctor Code --}}

                            <span class="badge bg-light text-dark border mb-3">

                                <i class="ti ti-id me-1"></i>

                                {{ $doctor->doctor_code }}

                            </span>



                            {{-- Status --}}

                            <div class="mb-3">

                                @if($doctor->status)

                                    <span class="badge bg-success">

                                        <i class="ti ti-circle-check me-1"></i>

                                        Active

                                    </span>

                                @else

                                    <span class="badge bg-danger">

                                        <i class="ti ti-circle-x me-1"></i>

                                        Inactive

                                    </span>

                                @endif

                            </div>



                            <hr>



                            {{-- Quick Details --}}

                            <div class="text-start">


                                {{-- Specialization --}}

                                <div class="d-flex align-items-center mb-3">

                                    <span class="avatar avatar-sm bg-primary-transparent me-2">

                                        <i class="ti ti-stethoscope text-primary"></i>

                                    </span>

                                    <div>

                                        <small class="text-muted d-block">
                                            Specialization
                                        </small>

                                        <strong>
                                            {{ $doctor->hospitalSpecialization?->specialization?->specialization_name ?? '-' }}
                                        </strong>

                                    </div>

                                </div>



                                {{-- Qualification --}}

                                <div class="d-flex align-items-center mb-3">

                                    <span class="avatar avatar-sm bg-info-transparent me-2">

                                        <i class="ti ti-school text-info"></i>

                                    </span>

                                    <div>

                                        <small class="text-muted d-block">
                                            Qualification
                                        </small>

                                        <strong>

                                            {{ $doctor->qualification ?? '-' }}

                                        </strong>

                                    </div>

                                </div>



                                {{-- Experience --}}

                                <div class="d-flex align-items-center mb-3">

                                    <span class="avatar avatar-sm bg-warning-transparent me-2">

                                        <i class="ti ti-briefcase text-warning"></i>

                                    </span>

                                    <div>

                                        <small class="text-muted d-block">
                                            Experience
                                        </small>

                                        <strong>

                                            {{ $doctor->experience ?? 0 }}
                                            Years

                                        </strong>

                                    </div>

                                </div>



                                {{-- Mobile --}}

                                <div class="d-flex align-items-center mb-3">

                                    <span class="avatar avatar-sm bg-success-transparent me-2">

                                        <i class="ti ti-phone text-success"></i>

                                    </span>

                                    <div>

                                        <small class="text-muted d-block">
                                            Mobile
                                        </small>

                                        <strong>

                                            +91 {{ $doctor->mobile }}

                                        </strong>

                                    </div>

                                </div>



                                {{-- Email --}}

                                <div class="d-flex align-items-center">

                                    <span class="avatar avatar-sm bg-danger-transparent me-2">

                                        <i class="ti ti-mail text-danger"></i>

                                    </span>

                                    <div class="overflow-hidden">

                                        <small class="text-muted d-block">
                                            Email
                                        </small>

                                        <strong class="text-break">

                                            {{ $doctor->email ?? '-' }}

                                        </strong>

                                    </div>

                                </div>


                            </div>

                        </div>

                    </div>



                    {{-- =====================================================
                    CERTIFICATE
                    ====================================================== --}}

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">

                                <i class="ti ti-certificate me-2"></i>

                                Doctor Certificate

                            </h5>

                        </div>


                        <div class="card-body">


                            @if($doctor->certificate)

                                @php

                                    $extension =
                                        strtolower(
                                            pathinfo(
                                                $doctor->certificate,
                                                PATHINFO_EXTENSION
                                            )
                                        );

                                @endphp


                                @if(
                                        in_array(
                                            $extension,
                                            ['jpg', 'jpeg', 'png', 'webp']
                                        )
                                    )

                                    <div class="text-center">

                                        <a href="{{ asset($doctor->certificate) }}" target="_blank">

                                            <img src="{{ asset($doctor->certificate) }}" alt="Doctor Certificate"
                                                class="img-fluid rounded border mb-3"
                                                style="
                                                                                                                                                max-height:250px;
                                                                                                                                                object-fit:contain;
                                                                                                                                            ">

                                        </a>


                                        <div>

                                            <a href="{{ asset($doctor->certificate) }}" target="_blank"
                                                class="btn btn-outline-primary btn-sm">

                                                <i class="ti ti-eye me-1"></i>

                                                View Certificate

                                            </a>

                                        </div>

                                    </div>


                                @elseif($extension == 'pdf')


                                    <div class="text-center py-3">

                                        <span class="avatar avatar-xl bg-danger-transparent mb-3">

                                            <i class="ti ti-file-type-pdf fs-30 text-danger"></i>

                                        </span>


                                        <h6>
                                            Doctor Certificate
                                        </h6>


                                        <p class="text-muted">
                                            PDF Document
                                        </p>


                                        <a href="{{ asset($doctor->certificate) }}" target="_blank"
                                            class="btn btn-outline-primary btn-sm">

                                            <i class="ti ti-eye me-1"></i>

                                            View Certificate

                                        </a>

                                    </div>

                                @else

                                    <div class="text-center">

                                        <a href="{{ asset($doctor->certificate) }}" target="_blank" class="btn btn-outline-primary">

                                            <i class="ti ti-file me-1"></i>

                                            View Certificate

                                        </a>

                                    </div>

                                @endif


                            @else

                                <div class="text-center py-4">

                                    <span class="avatar avatar-lg bg-light mx-auto mb-2">

                                        <i class="ti ti-certificate fs-24 text-muted"></i>

                                    </span>

                                    <p class="text-muted mb-0">

                                        No certificate uploaded

                                    </p>

                                </div>

                            @endif


                        </div>

                    </div>

                </div>



                {{-- =====================================================
                RIGHT SIDE
                ====================================================== --}}

                <div class="col-xl-8 col-lg-7">


                    {{-- =====================================================
                    PROFESSIONAL INFORMATION
                    ====================================================== --}}

                    <div class="card">

                        <div class="card-header">

                            <div class="d-flex align-items-center">

                                <span class="avatar avatar-sm bg-primary-transparent me-2">

                                    <i class="ti ti-stethoscope text-primary"></i>

                                </span>

                                <div>

                                    <h5 class="card-title mb-0">
                                        Professional Information
                                    </h5>

                                    <small class="text-muted">
                                        Doctor's professional details
                                    </small>

                                </div>

                            </div>

                        </div>


                        <div class="card-body">

                            <div class="row">


                                <div class="col-md-6">

                                    <div class="mb-4">

                                        <small class="text-muted d-block mb-1">
                                            Doctor Name
                                        </small>

                                        <h6 class="mb-0">

                                            {{ $doctor->doctor_name }}

                                        </h6>

                                    </div>

                                </div>



                                <div class="col-md-6">

                                    <div class="mb-4">

                                        <small class="text-muted d-block mb-1">
                                            Doctor Code
                                        </small>

                                        <h6 class="mb-0">

                                            {{ $doctor->doctor_code }}

                                        </h6>

                                    </div>

                                </div>



                                <div class="col-md-6">

                                    <div class="mb-4">

                                        <small class="text-muted d-block mb-1">
                                            Specialization
                                        </small>

                                        <h6 class="mb-0">

                                            {{ $doctor->hospitalSpecialization->specialization_name
        ?? '-' }}

                                        </h6>

                                    </div>

                                </div>



                                <div class="col-md-6">

                                    <div class="mb-4">

                                        <small class="text-muted d-block mb-1">
                                            Qualification
                                        </small>

                                        <h6 class="mb-0">

                                            {{ $doctor->qualification ?? '-' }}

                                        </h6>

                                    </div>

                                </div>



                                <div class="col-md-6">

                                    <div class="mb-4">

                                        <small class="text-muted d-block mb-1">
                                            Designation
                                        </small>

                                        <h6 class="mb-0">

                                            {{ $doctor->designation ?? '-' }}

                                        </h6>

                                    </div>

                                </div>



                                <div class="col-md-6">

                                    <div class="mb-4">

                                        <small class="text-muted d-block mb-1">
                                            Experience
                                        </small>

                                        <h6 class="mb-0">

                                            {{ $doctor->experience ?? 0 }}
                                            Years

                                        </h6>

                                    </div>

                                </div>


                            </div>

                        </div>

                    </div>



                    {{-- =====================================================
                    PERSONAL & CONTACT INFORMATION
                    ====================================================== --}}

                    <div class="card">

                        <div class="card-header">

                            <div class="d-flex align-items-center">

                                <span class="avatar avatar-sm bg-success-transparent me-2">

                                    <i class="ti ti-address-book text-success"></i>

                                </span>

                                <div>

                                    <h5 class="card-title mb-0">
                                        Personal & Contact Information
                                    </h5>

                                    <small class="text-muted">
                                        Personal and communication details
                                    </small>

                                </div>

                            </div>

                        </div>


                        <div class="card-body">

                            <div class="row">


                                <div class="col-md-6">

                                    <div class="mb-4">

                                        <small class="text-muted d-block mb-1">
                                            Mobile Number
                                        </small>

                                        <h6 class="mb-0">

                                            +91 {{ $doctor->mobile }}

                                        </h6>

                                    </div>

                                </div>



                                <div class="col-md-6">

                                    <div class="mb-4">

                                        <small class="text-muted d-block mb-1">
                                            Email Address
                                        </small>

                                        <h6 class="mb-0 text-break">

                                            {{ $doctor->email ?? '-' }}

                                        </h6>

                                    </div>

                                </div>



                                <div class="col-md-4">

                                    <div class="mb-4">

                                        <small class="text-muted d-block mb-1">
                                            Gender
                                        </small>

                                        <h6 class="mb-0">

                                            {{ $doctor->gender
        ? ucfirst($doctor->gender)
        : '-' }}

                                        </h6>

                                    </div>

                                </div>



                                <div class="col-md-4">

                                    <div class="mb-4">

                                        <small class="text-muted d-block mb-1">
                                            Date of Birth
                                        </small>

                                        <h6 class="mb-0">

                                            {{ $doctor->dob
        ? \Carbon\Carbon::parse(
            $doctor->dob
        )->format('d M Y')
        : '-' }}

                                        </h6>

                                    </div>

                                </div>



                                <div class="col-md-4">

                                    <div class="mb-4">

                                        <small class="text-muted d-block mb-1">
                                            Blood Group
                                        </small>

                                        <h6 class="mb-0">

                                            {{ $doctor->blood_group ?? '-' }}

                                        </h6>

                                    </div>

                                </div>



                                <div class="col-12">

                                    <div>

                                        <small class="text-muted d-block mb-1">
                                            Address
                                        </small>

                                        <h6 class="mb-0">

                                            {{ $doctor->address ?? '-' }}

                                        </h6>

                                    </div>

                                </div>


                            </div>

                        </div>

                    </div>



                    {{-- =====================================================
                    CONSULTATION FEES
                    ====================================================== --}}

                    <div class="card">

                        <div class="card-header">

                            <div class="d-flex align-items-center">

                                <span class="avatar avatar-sm bg-warning-transparent me-2">

                                    <i class="ti ti-currency-rupee text-warning"></i>

                                </span>

                                <div>

                                    <h5 class="card-title mb-0">
                                        Consultation Fees
                                    </h5>

                                    <small class="text-muted">
                                        Doctor's consultation charges
                                    </small>

                                </div>

                            </div>

                        </div>


                        <div class="card-body">

                            <div class="row g-3">


                                {{-- Clinic Consultation --}}

                                <div class="col-xl-3 col-md-6">

                                    <div class="border rounded p-3 h-100">

                                        <div class="d-flex align-items-center mb-2">

                                            <span class="avatar avatar-sm bg-primary-transparent me-2">

                                                <i class="ti ti-building-hospital text-primary"></i>

                                            </span>

                                            <small class="text-muted">
                                                Consultation
                                            </small>

                                        </div>


                                        <h4 class="mb-0">

                                            ₹{{ number_format(
        (float) (
            $doctor->consultation_fee
            ?? 0
        ),
        2
    ) }}

                                        </h4>

                                    </div>

                                </div>



                                {{-- Video --}}

                                <div class="col-xl-3 col-md-6">

                                    <div class="border rounded p-3 h-100">

                                        <div class="d-flex align-items-center mb-2">

                                            <span class="avatar avatar-sm bg-info-transparent me-2">

                                                <i class="ti ti-video text-info"></i>

                                            </span>

                                            <small class="text-muted">
                                                Video
                                            </small>

                                        </div>


                                        <h4 class="mb-0">

                                            ₹{{ number_format(
        (float) (
            $doctor->video_consultation_fee
            ?? 0
        ),
        2
    ) }}

                                        </h4>

                                    </div>

                                </div>



                                {{-- Chat --}}

                                <div class="col-xl-3 col-md-6">

                                    <div class="border rounded p-3 h-100">

                                        <div class="d-flex align-items-center mb-2">

                                            <span class="avatar avatar-sm bg-success-transparent me-2">

                                                <i class="ti ti-message text-success"></i>

                                            </span>

                                            <small class="text-muted">
                                                Chat
                                            </small>

                                        </div>


                                        <h4 class="mb-0">

                                            ₹{{ number_format(
        (float) (
            $doctor->chat_consultation_fee
            ?? 0
        ),
        2
    ) }}

                                        </h4>

                                    </div>

                                </div>



                                {{-- Home Visit --}}

                                <div class="col-xl-3 col-md-6">

                                    <div class="border rounded p-3 h-100">

                                        <div class="d-flex align-items-center mb-2">

                                            <span class="avatar avatar-sm bg-danger-transparent me-2">

                                                <i class="ti ti-home-heart text-danger"></i>

                                            </span>

                                            <small class="text-muted">
                                                Home Visit
                                            </small>

                                        </div>


                                        <h4 class="mb-0">

                                            ₹{{ number_format(
        (float) (
            $doctor->home_visit_fee
            ?? 0
        ),
        2
    ) }}

                                        </h4>

                                    </div>

                                </div>


                            </div>

                        </div>

                    </div>





                    {{-- =====================================================
                    AVAILABILITY
                    ====================================================== --}}

                    <div class="card">

                        <div class="card-header">

                            <div class="d-flex align-items-center">

                                <span class="avatar avatar-sm bg-info-transparent me-2">

                                    <i class="ti ti-clock text-info"></i>

                                </span>

                                <div>

                                    <h5 class="card-title mb-0">
                                        Availability
                                    </h5>

                                    <small class="text-muted">
                                        Doctor's general available time
                                    </small>

                                </div>

                            </div>

                        </div>


                        <div class="card-body">

                            <div class="row">


                                <div class="col-md-6">

                                    <div class="border rounded p-3">

                                        <small class="text-muted d-block mb-1">
                                            Available From
                                        </small>

                                        <h5 class="mb-0">

                                            @if($doctor->available_from)

                                                                                    {{ \Carbon\Carbon::parse(
                                                    $doctor->available_from
                                                )->format('h:i A') }}

                                            @else

                                                Not Set

                                            @endif

                                        </h5>

                                    </div>

                                </div>



                                <div class="col-md-6">

                                    <div class="border rounded p-3">

                                        <small class="text-muted d-block mb-1">
                                            Available To
                                        </small>

                                        <h5 class="mb-0">

                                            @if($doctor->available_to)

                                                                                    {{ \Carbon\Carbon::parse(
                                                    $doctor->available_to
                                                )->format('h:i A') }}

                                            @else

                                                Not Set

                                            @endif

                                        </h5>

                                    </div>

                                </div>


                            </div>

                        </div>

                    </div>



                    {{-- =====================================================
                    ABOUT DOCTOR
                    ====================================================== --}}

                    <div class="card">

                        <div class="card-header">

                            <div class="d-flex align-items-center">

                                <span class="avatar avatar-sm bg-primary-transparent me-2">

                                    <i class="ti ti-notes text-primary"></i>

                                </span>

                                <div>

                                    <h5 class="card-title mb-0">
                                        About Doctor
                                    </h5>

                                    <small class="text-muted">
                                        Professional profile
                                    </small>

                                </div>

                            </div>

                        </div>


                        <div class="card-body">

                            @if($doctor->about)

                                <p class="mb-0" style="white-space: pre-line;">

                                    {{ $doctor->about }}

                                </p>

                            @else

                                <p class="text-muted mb-0">

                                    No information added about this doctor.

                                </p>

                            @endif

                        </div>

                    </div>

                    <!-- Doctor Schedule -->
                    <!-- Doctor Schedule -->
                    <div class="card">

                        {{-- Header --}}
                        <div class="card-header d-flex align-items-center justify-content-between">

                            <h5 class="card-title mb-0">
                                Doctor Schedule
                            </h5>

                            {{-- Add Schedule --}}
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#addDoctorScheduleModal">

                                <i data-feather="plus" class="me-1"></i>
                                Add Schedule

                            </button>

                        </div>

                        <div class="card-body">

                            @if($doctor->schedules->count() > 0)

                                <div class="row g-3">

                                    @foreach($doctor->schedules as $schedule)

                                                        <div class="col-md-6">

                                                            <div class="border rounded p-3 h-100">

                                                                {{-- Card Header --}}
                                                                <div class="d-flex align-items-center justify-content-between mb-3">

                                                                    <h6 class="mb-0 fw-semibold">
                                                                        {{ ucfirst($schedule->day_of_week) }}
                                                                    </h6>

                                                                    <div class="d-flex align-items-center gap-2">

                                                                        {{-- Status --}}
                                                                        @if($schedule->status)

                                                                            <span class="badge bg-success">
                                                                                Active
                                                                            </span>

                                                                        @else

                                                                            <span class="badge bg-danger">
                                                                                Inactive
                                                                            </span>

                                                                        @endif

                                                                        {{-- Edit --}}
                                                                        <button type="button" class="btn btn-sm btn-light" title="Edit Schedule"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#editScheduleModal{{ $schedule->id }}">

                                                                            <i class="ti ti-edit"></i>

                                                                        </button>

                                                                    </div>

                                                                </div>


                                                                <div class="row g-3">

                                                                    {{-- Time --}}
                                                                    <div class="col-12">

                                                                        <div class="d-flex align-items-center">

                                                                            <div class="avatar avatar-sm bg-light-primary me-2">
                                                                                <i class="ti ti-clock fs-18"></i>
                                                                            </div>

                                                                            <div>

                                                                                <span class="text-muted d-block fs-12">
                                                                                    Available Time
                                                                                </span>

                                                                                <span class="fw-medium">

                                                                                    {{ $schedule->available_from
                                        ? \Carbon\Carbon::parse(
                                            $schedule->available_from
                                        )->format('h:i A')
                                        : '-' }}

                                                                                    -

                                                                                    {{ $schedule->available_to
                                        ? \Carbon\Carbon::parse(
                                            $schedule->available_to
                                        )->format('h:i A')
                                        : '-' }}

                                                                                </span>

                                                                            </div>

                                                                        </div>

                                                                    </div>


                                                                    {{-- Consultation Type --}}
                                                                    <div class="col-12">

                                                                        <div class="d-flex align-items-center">

                                                                            <div class="avatar avatar-sm bg-light-info me-2">
                                                                                <i class="ti ti-stethoscope fs-18"></i>
                                                                            </div>

                                                                            <div>

                                                                                <span class="text-muted d-block fs-12">
                                                                                    Consultation Type
                                                                                </span>

                                                                                <span class="fw-medium">

                                                                                    @if($schedule->consultation_type)

                                                                                                                                    {{ ucwords(
                                                                                            str_replace(
                                                                                                '_',
                                                                                                ' ',
                                                                                                $schedule->consultation_type
                                                                                            )
                                                                                        ) }}

                                                                                    @else

                                                                                        -

                                                                                    @endif

                                                                                </span>

                                                                            </div>

                                                                        </div>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>

                                                        <!-- Edit Schedule Modal -->
                                                        <div class="modal fade" id="editScheduleModal{{ $schedule->id }}" tabindex="-1"
                                                            aria-hidden="true">

                                                            <div class="modal-dialog modal-lg modal-dialog-centered">

                                                                <div class="modal-content">

                                                                    <div class="modal-header">

                                                                        <div>
                                                                            <h5 class="modal-title">
                                                                                Edit Doctor Schedule
                                                                            </h5>

                                                                            <small class="text-muted">
                                                                                {{ $doctor->doctor_name }}
                                                                            </small>
                                                                        </div>

                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                                        </button>

                                                                    </div>

                                                                    <form action="{{ route('hospital.doctor-schedules.update', $schedule->id) }}"
                                                                        method="POST">

                                                                        @csrf
                                                                        @method('PUT')

                                                                        <div class="modal-body">

                                                                            <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                                                                            <div class="row">

                                                                                {{-- Day --}}
                                                                                <div class="col-md-6 mb-3">

                                                                                    <label class="form-label">
                                                                                        Day <span class="text-danger">*</span>
                                                                                    </label>

                                                                                    <select name="day_of_week" class="form-select" required>

                                                                                        @foreach([
                                                                                                'monday',
                                                                                                'tuesday',
                                                                                                'wednesday',
                                                                                                'thursday',
                                                                                                'friday',
                                                                                                'saturday',
                                                                                                'sunday'
                                                                                            ] as $day)

                                                                                            <option value="{{ $day }}" {{ strtolower($schedule->day_of_week) == $day ? 'selected' : '' }}>

                                                                                                {{ ucfirst($day) }}

                                                                                            </option>

                                                                                        @endforeach

                                                                                    </select>

                                                                                </div>

                                                                                {{-- Slot Duration --}}
                                                                                <div class="col-md-6 mb-3">

                                                                                    <label class="form-label">
                                                                                        Slot Duration <span class="text-danger">*</span>
                                                                                    </label>

                                                                                    <select name="slot_duration" class="form-select" required>

                                                                                        @foreach([15, 20, 30, 45, 60] as $duration)

                                                                                            <option value="{{ $duration }}" {{ $schedule->slot_duration == $duration ? 'selected' : '' }}>

                                                                                                {{ $duration }} Minutes

                                                                                            </option>

                                                                                        @endforeach

                                                                                    </select>

                                                                                </div>

                                                                                {{-- Available From --}}
                                                                                <div class="col-md-6 mb-3">

                                                                                    <label class="form-label">
                                                                                        Available From <span class="text-danger">*</span>
                                                                                    </label>

                                                                                    <input type="time" name="available_from" class="form-control"
                                                                                        value="{{ $schedule->available_from
                                        ? \Carbon\Carbon::parse($schedule->available_from)->format('H:i')
                                        : '' }}" required>

                                                                                </div>

                                                                                {{-- Available To --}}
                                                                                <div class="col-md-6 mb-3">

                                                                                    <label class="form-label">
                                                                                        Available To <span class="text-danger">*</span>
                                                                                    </label>

                                                                                    <input type="time" name="available_to" class="form-control"
                                                                                        value="{{ $schedule->available_to
                                        ? \Carbon\Carbon::parse($schedule->available_to)->format('H:i')
                                        : '' }}" required>

                                                                                </div>

                                                                                {{-- Consultation Type --}}
                                                                                <div class="col-md-6 mb-3">

                                                                                    <label class="form-label">
                                                                                        Consultation Type
                                                                                    </label>

                                                                                    <select name="consultation_type" class="form-select">

                                                                                        <option value="">
                                                                                            Select Type
                                                                                        </option>

                                                                                        <option value="in_clinic" {{ $schedule->consultation_type == 'in_clinic' ? 'selected' : '' }}>
                                                                                            In Clinic
                                                                                        </option>

                                                                                        <option value="online" {{ $schedule->consultation_type == 'online' ? 'selected' : '' }}>
                                                                                            Online
                                                                                        </option>

                                                                                        <option value="home_visit" {{ $schedule->consultation_type == 'home_visit' ? 'selected' : '' }}>
                                                                                            Home Visit
                                                                                        </option>

                                                                                    </select>

                                                                                </div>

                                                                                {{-- Status --}}
                                                                                <div class="col-md-6 mb-3">

                                                                                    <label class="form-label d-block">
                                                                                        Status
                                                                                    </label>

                                                                                    <div class="form-check form-switch mt-2">

                                                                                        <input type="checkbox" name="status" value="1"
                                                                                            class="form-check-input" id="status{{ $schedule->id }}"
                                                                                            {{ $schedule->status ? 'checked' : '' }}>

                                                                                        <label class="form-check-label"
                                                                                            for="status{{ $schedule->id }}">

                                                                                            Active

                                                                                        </label>

                                                                                    </div>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <div class="modal-footer">

                                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">

                                                                                Cancel

                                                                            </button>

                                                                            <button type="submit" class="btn btn-primary">

                                                                                <i class="ti ti-device-floppy me-1"></i>

                                                                                Update Schedule

                                                                            </button>

                                                                        </div>

                                                                    </form>

                                                                </div>

                                                            </div>

                                                        </div>

                                    @endforeach

                                </div>

                            @else

                                <div class="text-center py-4">

                                    <i data-feather="calendar" style="width:40px;height:40px;"></i>

                                    <p class="text-muted mt-2 mb-3">
                                        No schedule configured for this doctor.
                                    </p>

                                    {{-- Add Schedule when empty --}}
                                    {{-- <a href="{{ route('hospital.doctor-schedules.create', [
                                            'doctor_id' => $doctor->id
                                        ]) }}" class="btn btn-primary btn-sm">

                                        <i class="ti ti-plus me-1"></i>
                                        Add Schedule

                                    </a> --}}

                                </div>

                            @endif

                        </div>

                    </div>



                    {{-- =====================================================
                    ACCOUNT INFORMATION
                    ====================================================== --}}

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">

                                <i class="ti ti-info-circle me-2"></i>

                                Account Information

                            </h5>

                        </div>


                        <div class="card-body">

                            <div class="row">


                                <div class="col-md-4">

                                    <small class="text-muted d-block mb-1">
                                        Doctor Code
                                    </small>

                                    <strong>
                                        {{ $doctor->doctor_code }}
                                    </strong>

                                </div>


                                <div class="col-md-4">

                                    <small class="text-muted d-block mb-1">
                                        Created On
                                    </small>

                                    <strong>

                                        {{ $doctor->created_at
        ? $doctor->created_at->format(
            'd M Y, h:i A'
        )
        : '-' }}

                                    </strong>

                                </div>


                                <div class="col-md-4">

                                    <small class="text-muted d-block mb-1">
                                        Last Updated
                                    </small>

                                    <strong>

                                        {{ $doctor->updated_at
        ? $doctor->updated_at->format(
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

    <!-- Add Doctor Schedule Modal -->
    <div class="modal fade" id="addDoctorScheduleModal" tabindex="-1" aria-labelledby="addDoctorScheduleModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <div>
                        <h5 class="modal-title" id="addDoctorScheduleModalLabel">
                            Add Doctor Schedule
                        </h5>

                        <small class="text-muted">
                            Add availability for {{ $doctor->doctor_name }}
                        </small>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>

                </div>

                <form action="{{ route('hospital.doctor-schedules.store') }}" method="POST">
                @csrf

                    <div class="modal-body">

                        <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                        <div class="row">

                            <!-- Doctor -->
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Doctor
                                </label>

                                <input type="text" class="form-control" value="{{ $doctor->doctor_name }}" readonly>

                            </div>

                            <!-- Day -->
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Day of Week <span class="text-danger">*</span>
                                </label>

                                <select name="day_of_week" class="form-select" required>

                                    <option value="">Select Day</option>

                                    <option value="monday">Monday</option>
                                    <option value="tuesday">Tuesday</option>
                                    <option value="wednesday">Wednesday</option>
                                    <option value="thursday">Thursday</option>
                                    <option value="friday">Friday</option>
                                    <option value="saturday">Saturday</option>
                                    <option value="sunday">Sunday</option>

                                </select>

                            </div>

                            <!-- Available From -->
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Available From <span class="text-danger">*</span>
                                </label>

                                <input type="time" name="available_from" class="form-control" required>

                            </div>

                            <!-- Available To -->
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Available To <span class="text-danger">*</span>
                                </label>

                                <input type="time" name="available_to" class="form-control" required>

                            </div>

                            <!-- Slot Duration -->
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Slot Duration <span class="text-danger">*</span>
                                </label>

                                <select name="slot_duration" class="form-select" required>

                                    <option value="">Select Duration</option>
                                    <option value="15">15 Minutes</option>
                                    <option value="20">20 Minutes</option>
                                    <option value="30">30 Minutes</option>
                                    <option value="45">45 Minutes</option>
                                    <option value="60">60 Minutes</option>

                                </select>

                            </div>

                            <!-- Consultation Type -->
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Consultation Type
                                </label>

                                <select name="consultation_type" class="form-select">

                                    <option value="">
                                        Select Consultation Type
                                    </option>

                                    <option value="in_clinic">
                                        In Clinic
                                    </option>

                                    <option value="online">
                                        Online
                                    </option>

                                    <option value="home_visit">
                                        Home Visit
                                    </option>

                                </select>

                            </div>

                            <!-- Status -->
                            <div class="col-md-12">

                                <div class="form-check form-switch">

                                    <input type="checkbox" class="form-check-input" name="status" value="1"
                                        id="scheduleStatus" checked>

                                    <label class="form-check-label" for="scheduleStatus">
                                        Active
                                    </label>

                                </div>

                            </div>

                            <div class="col-md-12 mt-3">

                                <div id="scheduleError" class="alert alert-danger d-none">
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">

                            Cancel

                        </button>

                        <button type="submit" class="btn btn-primary" id="saveScheduleBtn">

                            <i data-feather="save" class="me-1"></i>

                            Save Schedule

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>




@endsection