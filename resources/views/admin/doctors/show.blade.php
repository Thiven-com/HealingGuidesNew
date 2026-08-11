<?php $page = 'doctors'; ?>

@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            <!-- Page Header -->
            <div class="page-header">

                <div class="add-item d-flex">

                    <div class="page-title">

                        <h4>Doctor Details</h4>

                        <h6>View Doctor Information</h6>

                    </div>

                </div>

                <ul class="table-top-head">

                    <li>
                        <a href="{{ route('admin.doctors.index') }}" data-bs-toggle="tooltip" title="Back">

                            <i data-feather="arrow-left"></i>

                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.doctors.edit', $doctor->id) }}" data-bs-toggle="tooltip"
                            title="Edit Doctor">

                            <i data-feather="edit"></i>

                        </a>
                    </li>

                    <li>
                        <a id="collapse-header" data-bs-toggle="tooltip" title="Collapse">

                            <i data-feather="chevron-up"></i>

                        </a>
                    </li>

                </ul>

                <div class="page-btn">

                    <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-primary">

                        <i data-feather="edit" class="me-2"></i>

                        Edit Doctor

                    </a>

                </div>

            </div>
            <!-- /Page Header -->


            {{-- Success --}}
            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">

                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            <div class="row">

                <!-- Left Side -->
                <div class="col-lg-8">

                    <!-- Doctor Profile -->
                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Doctor Profile
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row align-items-center">

                                <!-- Photo -->
                                <div class="col-md-3 text-center mb-3 mb-md-0">

                                    @if($doctor->photo)

                                        <img src="{{ asset($doctor->photo) }}" alt="{{ $doctor->doctor_name }}"
                                            class="rounded-circle" style="width:150px;height:150px;object-fit:cover;">

                                    @else

                                        <img src="{{ asset('assets/img/no-image.png') }}" alt="Doctor" class="rounded-circle"
                                            style="width:150px;height:150px;object-fit:cover;">

                                    @endif

                                </div>


                                <!-- Basic Details -->
                                <div class="col-md-9">

                                    <h4 class="mb-1">

                                        {{ $doctor->doctor_name }}

                                    </h4>

                                    @if($doctor->designation)

                                        <p class="text-muted mb-2">

                                            {{ $doctor->designation }}

                                        </p>

                                    @endif


                                    <div class="mb-2">

                                        @if($doctor->status)

                                            <span class="badge bg-success">
                                                Active
                                            </span>

                                        @else

                                            <span class="badge bg-danger">
                                                Inactive
                                            </span>

                                        @endif

                                    </div>


                                    <div class="row">

                                        <div class="col-md-6 mb-2">

                                            <small class="text-muted d-block">
                                                Doctor Code
                                            </small>

                                            <strong>
                                                {{ $doctor->doctor_code ?? '-' }}
                                            </strong>

                                        </div>


                                        <div class="col-md-6 mb-2">

                                            <small class="text-muted d-block">
                                                Qualification
                                            </small>

                                            <strong>
                                                {{ $doctor->qualification ?? '-' }}
                                            </strong>

                                        </div>


                                        <div class="col-md-6 mb-2">

                                            <small class="text-muted d-block">
                                                Experience
                                            </small>

                                            <strong>

                                                {{ $doctor->experience !== null
        ? $doctor->experience . ' Years'
        : '-' }}

                                            </strong>

                                        </div>


                                        <div class="col-md-6 mb-2">

                                            <small class="text-muted d-block">
                                                Specialization
                                            </small>

                                            <strong>

                                                {{ optional($doctor->hospitalSpecialization)->specialization->specialization_name ?? '-' }}

                                            </strong>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- Hospital Information -->
                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Hospital Information
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <small class="text-muted d-block">
                                        Hospital
                                    </small>

                                    <strong>

                                        {{ optional($doctor->hospital)->hospital_name ?? '-' }}

                                    </strong>

                                </div>


                                <div class="col-md-6 mb-3">

                                    <small class="text-muted d-block">
                                        Hospital ID
                                    </small>

                                    <strong>

                                        {{ $doctor->hospital_id ?? '-' }}

                                    </strong>

                                </div>


                                <div class="col-md-6 mb-3">

                                    <small class="text-muted d-block">
                                        Specialization
                                    </small>

                                    <strong>

                                        {{ optional($doctor->hospitalSpecialization)->specialization->specialization_name ?? '-' }}

                                    </strong>

                                </div>


                                <div class="col-md-6 mb-3">

                                    <small class="text-muted d-block">
                                        Designation
                                    </small>

                                    <strong>

                                        {{ $doctor->designation ?? '-' }}

                                    </strong>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- Personal Information -->
                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Personal Information
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <small class="text-muted d-block">
                                        Email
                                    </small>

                                    <strong>

                                        {{ $doctor->email ?? '-' }}

                                    </strong>

                                </div>


                                <div class="col-md-6 mb-3">

                                    <small class="text-muted d-block">
                                        Mobile
                                    </small>

                                    <strong>

                                        {{ $doctor->mobile ?? '-' }}

                                    </strong>

                                </div>


                                <div class="col-md-4 mb-3">

                                    <small class="text-muted d-block">
                                        Date of Birth
                                    </small>

                                    <strong>

                                        {{ $doctor->dob
        ? \Carbon\Carbon::parse($doctor->dob)->format('d M Y')
        : '-' }}

                                    </strong>

                                </div>


                                <div class="col-md-4 mb-3">

                                    <small class="text-muted d-block">
                                        Gender
                                    </small>

                                    <strong>

                                        {{ $doctor->gender
        ? ucfirst($doctor->gender)
        : '-' }}

                                    </strong>

                                </div>


                                <div class="col-md-4 mb-3">

                                    <small class="text-muted d-block">
                                        Blood Group
                                    </small>

                                    <strong>

                                        {{ $doctor->blood_group ?? '-' }}

                                    </strong>

                                </div>


                                <div class="col-md-12 mb-3">

                                    <small class="text-muted d-block">
                                        Address
                                    </small>

                                    <strong>

                                        {{ $doctor->address ?? '-' }}

                                    </strong>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- About -->
                    @if($doctor->about)

                        <div class="card">

                            <div class="card-header">

                                <h5 class="card-title mb-0">
                                    About Doctor
                                </h5>

                            </div>

                            <div class="card-body">

                                <p class="mb-0">

                                    {{ $doctor->about }}

                                </p>

                            </div>

                        </div>

                    @endif

                </div>


                <!-- Right Side -->
                <div class="col-lg-4">

                    <!-- Consultation Fees -->
                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Consultation Fees
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="d-flex justify-content-between mb-3">

                                <span>
                                    Hospital Consultation
                                </span>

                                <strong>

                                    ₹{{ number_format($doctor->consultation_fee ?? 0, 2) }}

                                </strong>

                            </div>


                            <div class="d-flex justify-content-between mb-3">

                                <span>
                                    Video Consultation
                                </span>

                                <strong>

                                    ₹{{ number_format($doctor->video_consultation_fee ?? 0, 2) }}

                                </strong>

                            </div>


                            <div class="d-flex justify-content-between mb-3">

                                <span>
                                    Chat Consultation
                                </span>

                                <strong>

                                    ₹{{ number_format($doctor->chat_consultation_fee ?? 0, 2) }}

                                </strong>

                            </div>


                            <div class="d-flex justify-content-between">

                                <span>
                                    Home Visit
                                </span>

                                <strong>

                                    ₹{{ number_format($doctor->home_visit_fee ?? 0, 2) }}

                                </strong>

                            </div>

                        </div>

                    </div>


                    <!-- Availability -->
                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Availability
                            </h5>

                        </div>

                        <div class="card-body">

                            @if($doctor->available_from || $doctor->available_to)

                                                <div class="d-flex align-items-center mb-3">

                                                    <div class="avatar avatar-md bg-light-primary rounded me-3">

                                                        <i data-feather="clock"></i>

                                                    </div>

                                                    <div>

                                                        <small class="text-muted d-block">
                                                            Available Time
                                                        </small>

                                                        <strong>

                                                            {{ $doctor->available_from
                                ? \Carbon\Carbon::parse($doctor->available_from)->format('h:i A')
                                : '-' }}

                                                            -

                                                            {{ $doctor->available_to
                                ? \Carbon\Carbon::parse($doctor->available_to)->format('h:i A')
                                : '-' }}

                                                        </strong>

                                                    </div>

                                                </div>

                            @else

                                <div class="text-muted">
                                    Availability not configured.
                                </div>

                            @endif

                        </div>

                    </div>


                    <!-- Certificate -->
                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Certificate
                            </h5>

                        </div>

                        <div class="card-body">

                            @if($doctor->certificate)

                                <a href="{{ asset($doctor->certificate) }}" target="_blank"
                                    class="btn btn-outline-primary w-100">

                                    <i data-feather="file-text" class="me-2"></i>

                                    View Certificate

                                </a>

                            @else

                                <p class="text-muted mb-0">
                                    No certificate uploaded.
                                </p>

                            @endif

                        </div>

                    </div>


                    <!-- Doctor Details -->
                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                System Information
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="mb-3">

                                <small class="text-muted d-block">
                                    Doctor Code
                                </small>

                                <strong>
                                    {{ $doctor->doctor_code ?? '-' }}
                                </strong>

                            </div>


                            <div class="mb-3">

                                <small class="text-muted d-block">
                                    Slug
                                </small>

                                <strong>
                                    {{ $doctor->slug ?? '-' }}
                                </strong>

                            </div>


                            <div class="mb-3">

                                <small class="text-muted d-block">
                                    Created At
                                </small>

                                <strong>

                                    {{ $doctor->created_at
        ? $doctor->created_at->format('d M Y h:i A')
        : '-' }}

                                </strong>

                            </div>


                            <div>

                                <small class="text-muted d-block">
                                    Last Updated
                                </small>

                                <strong>

                                    {{ $doctor->updated_at
        ? $doctor->updated_at->format('d M Y h:i A')
        : '-' }}

                                </strong>

                            </div>

                        </div>

                    </div>


                    <!-- Actions -->
                    <div class="card">

                        <div class="card-body">

                            <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-primary w-100 mb-2">

                                <i data-feather="edit" class="me-2"></i>

                                Edit Doctor

                            </a>


                            <form action="{{ route('admin.doctors.status', $doctor->id) }}" method="POST">

                                @csrf

                                <button type="submit" class="btn btn-light w-100">

                                    @if($doctor->status)

                                        <i data-feather="pause-circle" class="me-2"></i>

                                        Deactivate Doctor

                                    @else

                                        <i data-feather="check-circle" class="me-2"></i>

                                        Activate Doctor

                                    @endif

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Back Button -->
            <div class="text-end mb-4">

                <a href="{{ route('admin.doctors.index') }}" class="btn btn-light">

                    <i data-feather="arrow-left" class="me-1"></i>

                    Back to Doctors

                </a>

            </div>

        </div>

    </div>


    <script>

        document.addEventListener("DOMContentLoaded", function () {

            if (typeof feather !== "undefined") {
                feather.replace();
            }

        });

    </script>

@endsection