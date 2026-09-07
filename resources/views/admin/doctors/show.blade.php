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

                                                                        <form action="{{ route('admin.doctor-schedules.update', $schedule->id) }}"
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

                <form action="{{ route('admin.doctor-schedules.store') }}" method="POST">
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


    <script>

        document.addEventListener("DOMContentLoaded", function () {

            if (typeof feather !== "undefined") {
                feather.replace();
            }

        });

    </script>

@endsection