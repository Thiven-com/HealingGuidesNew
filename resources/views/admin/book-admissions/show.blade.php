@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- =========================================================
            PAGE HEADER
        ========================================================== --}}
        <div class="page-header">
            <div class="row align-items-center">

                <div class="col-sm-16">
                    <h4 class="page-title mb-1">
                        Admission Request Details
                    </h4>

                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                Dashboard /
                            </a>
                            <a href="{{ route('admin.book-admissions.index') }}">
                                Admission Requests / <span class="breadcrumb-item active">{{ $bookAdmission->familyMember->name ?? 'N/A' }} </span>
                            </a>
                        </li>

                    </ul>
                </div>

            </div>
            <div class="col-sm-4 text-sm-end mt-3 mt-sm-0">

                    <a
                        href="{{ route('admin.book-admissions.index') }}"
                        class="btn" style="background-color: #6D28D9; color: #ffffff;"
                    >
                        <i class="ti ti-arrow-left me-1"></i>
                        Back
                    </a>

                </div>
        </div>


        {{-- =========================================================
            SUCCESS MESSAGE
        ========================================================== --}}
        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">
                <i class="ti ti-circle-check me-2"></i>
                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>
            </div>

        @endif


        {{-- =========================================================
            TOP SUMMARY CARD
        ========================================================== --}}
        <div class="card admission-summary-card">

            <div class="card-body">

                <div class="row align-items-center">

                    {{-- REQUEST --}}
                    <div class="col-lg-4 col-md-6">

                        <div class="summary-item">

                            <div class="summary-icon">
                                <i class="ti ti-file-medical"></i>
                            </div>

                            <div>

                                <span class="summary-label">
                                    Admission Request
                                </span>

                                <h5 class="summary-value">
                                    {{ $bookAdmission->familyMember->name ?? 'N/A' }}
                                </h5>

                            </div>

                        </div>

                    </div>


                    {{-- ADMISSION TYPE --}}
                    <div class="col-lg-4 col-md-6">

                        <div class="summary-item">

                            <div class="summary-icon">
                                <i class="ti ti-building-hospital"></i>
                            </div>

                            <div>

                                <span class="summary-label">
                                    Admission Type
                                </span>

                                <h5 class="summary-value">

                                    @if($bookAdmission->admission_type === 'general_admission')

                                        <span class="type-badge general">
                                            <i class="ti ti-user-check"></i>
                                            General Admission
                                        </span>

                                    @elseif($bookAdmission->admission_type === 'surgery_admission')

                                        <span class="type-badge surgery">
                                            <i class="ti ti-heart-rate-monitor"></i>
                                            Surgery Admission
                                        </span>

                                    @else

                                        <span class="type-badge">
                                            {{ ucfirst(str_replace('_', ' ', $bookAdmission->admission_type)) }}
                                        </span>

                                    @endif

                                </h5>

                            </div>

                        </div>

                    </div>


                    {{-- STATUS --}}
                    <div class="col-lg-4 col-md-6 mt-3 mt-lg-0">

                        <div class="summary-item">

                            <div class="summary-icon status-icon">
                                <i class="ti ti-progress-check"></i>
                            </div>

                            <div>

                                <span class="summary-label">
                                    Current Status
                                </span>

                                <h5 class="summary-value">

                                    @if($bookAdmission->status === 'pending')

                                        <span class="status-badge pending">
                                            <span class="status-dot"></span>
                                            Pending
                                        </span>

                                    @elseif($bookAdmission->status === 'confirmed')

                                        <span class="status-badge confirmed">
                                            <span class="status-dot"></span>
                                            Confirmed
                                        </span>

                                    @elseif($bookAdmission->status === 'completed')

                                        <span class="status-badge completed">
                                            <span class="status-dot"></span>
                                            Completed
                                        </span>

                                    @elseif($bookAdmission->status === 'cancelled')

                                        <span class="status-badge cancelled">
                                            <span class="status-dot"></span>
                                            Cancelled
                                        </span>

                                    @else

                                        <span class="status-badge">
                                            {{ ucfirst($bookAdmission->status) }}
                                        </span>

                                    @endif

                                </h5>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="row">


            {{-- =====================================================
                LEFT SIDE
            ====================================================== --}}
            <div class="col-xl-8">


                {{-- =================================================
                    PATIENT / FAMILY MEMBER
                ================================================== --}}
                <div class="card details-card">

                    <div class="card-header custom-card-header">

                        <div class="header-title">

                            <div class="header-icon patient-icon">
                                <i class="ti ti-user"></i>
                            </div>

                            <div>
                                <h5 class="mb-1">
                                    Patient / Family Member
                                </h5>

                                <p class="mb-0">
                                    Patient information associated with this request
                                </p>
                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        <div class="row">

                            {{-- FAMILY MEMBER NAME --}}
                            <div class="col-md-6">

                                <div class="detail-item">

                                    <span class="detail-label">
                                        <i class="ti ti-user"></i>
                                        Family Member Name
                                    </span>

                                    <span class="detail-value">

                                        @if($bookAdmission->familyMember)

                                            {{ $bookAdmission->familyMember->name }}

                                        @else

                                            <span class="empty-value">
                                                Not Available
                                            </span>

                                        @endif

                                    </span>

                                </div>

                            </div>


                            {{-- CUSTOMER NAME --}}
                            <div class="col-md-6">

                                <div class="detail-item">

                                    <span class="detail-label">
                                        <i class="ti ti-user-square"></i>
                                        Customer Name
                                    </span>

                                    <span class="detail-value">

                                        @if($bookAdmission->customer)

                                            {{ $bookAdmission->customer->name ?? 'N/A' }}

                                        @else

                                            <span class="empty-value">
                                                Not Available
                                            </span>

                                        @endif

                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    ADMISSION INFORMATION
                ================================================== --}}
                <div class="card details-card">

                    <div class="card-header custom-card-header">

                        <div class="header-title">

                            <div class="header-icon admission-icon">
                                <i class="ti ti-building-hospital"></i>
                            </div>

                            <div>

                                <h5 class="mb-1">
                                    Admission Information
                                </h5>

                                <p class="mb-0">
                                    Requested admission details
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        <div class="row">

                            {{-- ADMISSION TYPE --}}
                            <div class="col-md-6">

                                <div class="detail-item">

                                    <span class="detail-label">
                                        <i class="ti ti-category"></i>
                                        Admission Type
                                    </span>

                                    <span class="detail-value">

                                        @if($bookAdmission->admission_type === 'general_admission')

                                            General Admission

                                        @elseif($bookAdmission->admission_type === 'surgery_admission')

                                            Surgery Admission

                                        @else

                                            {{ ucfirst(str_replace('_', ' ', $bookAdmission->admission_type)) }}

                                        @endif

                                    </span>

                                </div>

                            </div>


                            {{-- PREFERRED DATE --}}
                            <div class="col-md-6">

                                <div class="detail-item">

                                    <span class="detail-label">
                                        <i class="ti ti-calendar"></i>
                                        Preferred Admission Date
                                    </span>

                                    <span class="detail-value">

                                        @if($bookAdmission->preferred_admission_date)

                                            {{ $bookAdmission->preferred_admission_date->format('d M Y') }}

                                        @else

                                            <span class="empty-value">
                                                Not Provided
                                            </span>

                                        @endif

                                    </span>

                                </div>

                            </div>


                            {{-- PROCEDURE --}}
                            <div class="col-md-12">

                                <div class="detail-item">

                                    <span class="detail-label">
                                        <i class="ti ti-stethoscope"></i>
                                        Surgery / Procedure
                                    </span>

                                    <div class="procedure-box">

                                        @if($bookAdmission->surgery_procedure)

                                            <i class="ti ti-medical-cross me-2"></i>

                                            {{ $bookAdmission->surgery_procedure }}

                                        @else

                                            <span class="empty-value">
                                                Not Applicable
                                            </span>

                                        @endif

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    DOCTOR INFORMATION
                ================================================== --}}
                <div class="card details-card">

                    <div class="card-header custom-card-header">

                        <div class="header-title">

                            <div class="header-icon doctor-icon">
                                <i class="ti ti-stethoscope"></i>
                            </div>

                            <div>

                                <h5 class="mb-1">
                                    Preferred Doctor
                                </h5>

                                <p class="mb-0">
                                    Doctor selected by the customer
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        @if($bookAdmission->preferredDoctor)

                            <div class="doctor-profile">

                                <div class="doctor-avatar">

                                    @if(!empty($bookAdmission->preferredDoctor->image))

                                        <img
                                            src="{{ asset($bookAdmission->preferredDoctor->image) }}"
                                            alt="Doctor"
                                        >

                                    @else

                                        <i class="ti ti-user"></i>

                                    @endif

                                </div>


                                <div class="doctor-info">

                                    <h5>
                                        {{ $bookAdmission->preferredDoctor->doctor_name }}
                                    </h5>

                                    @if(!empty($bookAdmission->preferredDoctor->qualification))

                                        <p>
                                            {{ $bookAdmission->preferredDoctor->qualification }}
                                        </p>

                                    @endif

                                    @if(!empty($bookAdmission->preferredDoctor->designation))

                                        <span>
                                            {{ $bookAdmission->preferredDoctor->designation }}
                                        </span>

                                    @endif

                                </div>

                            </div>

                        @else

                            <div class="empty-state">

                                <i class="ti ti-user-off"></i>

                                <h6>
                                    No Doctor Selected
                                </h6>

                                <p>
                                    The customer did not select a preferred doctor.
                                </p>

                            </div>

                        @endif

                    </div>

                </div>


                {{-- =================================================
                    ADDITIONAL INFORMATION
                ================================================== --}}
                <div class="card details-card">

                    <div class="card-header custom-card-header">

                        <div class="header-title">

                            <div class="header-icon note-icon">
                                <i class="ti ti-notes"></i>
                            </div>

                            <div>

                                <h5 class="mb-1">
                                    Additional Information
                                </h5>

                                <p class="mb-0">
                                    Additional information provided by the customer
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        @if($bookAdmission->additional_information)

                            <div class="information-box">

                                <i class="ti ti-quote me-2"></i>

                                <span>
                                    {!! nl2br(e($bookAdmission->additional_information)) !!}
                                </span>

                            </div>

                        @else

                            <div class="empty-state">

                                <i class="ti ti-notes-off"></i>

                                <h6>
                                    No Additional Information
                                </h6>

                                <p>
                                    No additional information was provided.
                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            </div>


            {{-- =====================================================
                RIGHT SIDE
            ====================================================== --}}
            <div class="col-xl-4">


                {{-- =================================================
                    UPDATE STATUS
                ================================================== --}}
                <div class="card details-card status-card">

                    <div class="card-header custom-card-header">

                        <div class="header-title">

                            <div class="header-icon status-header-icon">
                                <i class="ti ti-progress-check"></i>
                            </div>

                            <div>

                                <h5 class="mb-1">
                                    Request Status
                                </h5>

                                <p class="mb-0">
                                    Manage admission request status
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.book-admissions.update-status',
                                $bookAdmission->id
                            ) }}"
                        >

                            @csrf

                            <div class="mb-3">

                                <label class="form-label">
                                    Status
                                </label>

                                <select
                                    name="status"
                                    class="form-select status-select"
                                >

                                    <option
                                        value="pending"
                                        {{ $bookAdmission->status === 'pending' ? 'selected' : '' }}
                                    >
                                        Pending
                                    </option>

                                    <option
                                        value="confirmed"
                                        {{ $bookAdmission->status === 'confirmed' ? 'selected' : '' }}
                                    >
                                        Confirmed
                                    </option>

                                    <option
                                        value="completed"
                                        {{ $bookAdmission->status === 'completed' ? 'selected' : '' }}
                                    >
                                        Completed
                                    </option>

                                    <option
                                        value="cancelled"
                                        {{ $bookAdmission->status === 'cancelled' ? 'selected' : '' }}
                                    >
                                        Cancelled
                                    </option>

                                </select>

                            </div>

                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                            >
                                <i class="ti ti-refresh me-1"></i>
                                Update Status
                            </button>

                        </form>

                    </div>

                </div>


                {{-- =================================================
                    REQUEST TIMELINE
                ================================================== --}}
                <div class="card details-card">

                    <div class="card-header custom-card-header">

                        <div class="header-title">

                            <div class="header-icon timeline-icon">
                                <i class="ti ti-clock"></i>
                            </div>

                            <div>

                                <h5 class="mb-1">
                                    Request Timeline
                                </h5>

                                <p class="mb-0">
                                    Request activity
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        {{-- <div class="timeline"> --}}

                            {{-- CREATED --}}
                            <div class="timeline-item">

                                <div class="timeline-dot">
                                    <i class="ti ti-plus"></i>
                                </div>

                                <div class="timeline-content">

                                    <h6>
                                        Admission Request Created
                                    </h6>

                                    <p>
                                        {{ $bookAdmission->created_at->format('d M Y, h:i A') }}
                                    </p>

                                </div>

                            </div>


                            {{-- UPDATED --}}
                            <div class="timeline-item">

                                <div class="timeline-dot">
                                    <i class="ti ti-edit"></i>
                                </div>

                                <div class="timeline-content">

                                    <h6>
                                        Last Updated
                                    </h6>

                                    <p>
                                        {{ $bookAdmission->updated_at->format('d M Y, h:i A') }}
                                    </p>

                                </div>

                            </div>

                        {{-- </div> --}}

                    </div>

                </div>



            </div>

        </div>

    </div>
</div>


{{-- =============================================================
    PAGE CSS
============================================================== --}}
<style>

.admission-summary-card {
    border: 0;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
    margin-bottom: 20px;
}

.summary-item {
    display: flex;
    align-items: center;
    gap: 14px;
}

.summary-icon {
    width: 48px;
    height: 48px;
    min-width: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f0f4ff;
    color: #4f46e5;
    font-size: 23px;
}

.status-icon {
    background: #eefbf4;
    color: #198754;
}

.summary-label {
    display: block;
    font-size: 12px;
    color: #8a8f98;
    margin-bottom: 4px;
}

.summary-value {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #20242a;
}

.details-card {
    border: 0;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.045);
    margin-bottom: 20px;
    overflow: hidden;
}

.custom-card-header {
    background: #fff;
    border-bottom: 1px solid #edf0f3;
    padding: 18px 20px;
}

.header-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-title h5 {
    font-size: 16px;
    font-weight: 600;
    color: #20242a;
}

.header-title p {
    font-size: 12px;
    color: #8a8f98;
}

.header-icon {
    width: 40px;
    height: 40px;
    min-width: 40px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.patient-icon {
    background: #eef4ff;
    color: #3867d6;
}

.admission-icon {
    background: #f3efff;
    color: #7656d6;
}

.doctor-icon {
    background: #eafaf4;
    color: #179b6b;
}

.note-icon {
    background: #fff7e8;
    color: #e59b1b;
}

.status-header-icon {
    background: #edf8f2;
    color: #198754;
}

.timeline-icon {
    background: #f1f3f5;
    color: #495057;
}

.database-icon {
    background: #eef2f7;
    color: #495057;
}

.detail-item {
    padding: 14px 0;
    border-bottom: 1px solid #f0f1f3;
}

.detail-item:last-child {
    border-bottom: 0;
}

.detail-label {
    display: block;
    font-size: 12px;
    color: #8a8f98;
    margin-bottom: 7px;
}

.detail-label i {
    margin-right: 5px;
    font-size: 14px;
}

.detail-value {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #252a31;
}

.empty-value {
    color: #9ca3af;
    font-weight: 400;
}

.type-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}

.type-badge.general {
    background: #eaf4ff;
    color: #2877c7;
}

.type-badge.surgery {
    background: #fff2e8;
    color: #d66b16;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 6px 11px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    display: inline-block;
}

.status-badge.pending {
    background: #fff7df;
    color: #a36b00;
}

.status-badge.pending .status-dot {
    background: #e8a500;
}

.status-badge.confirmed {
    background: #eaf8f0;
    color: #16804f;
}

.status-badge.confirmed .status-dot {
    background: #16804f;
}

.status-badge.completed {
    background: #eaf1ff;
    color: #3568c8;
}

.status-badge.completed .status-dot {
    background: #3568c8;
}

.status-badge.cancelled {
    background: #fff0f0;
    color: #d43b3b;
}

.status-badge.cancelled .status-dot {
    background: #d43b3b;
}

.procedure-box {
    margin-top: 8px;
    padding: 14px 16px;
    border: 1px solid #edf0f3;
    background: #f9fafb;
    border-radius: 8px;
    color: #252a31;
    font-size: 14px;
    font-weight: 500;
}

.information-box {
    display: flex;
    align-items: flex-start;
    padding: 16px;
    background: #f8f9fb;
    border: 1px solid #edf0f3;
    border-radius: 9px;
    color: #454b53;
    font-size: 14px;
    line-height: 1.7;
}

.information-box > i {
    color: #7b61c9;
    font-size: 20px;
    margin-top: 2px;
}

.doctor-profile {
    display: flex;
    align-items: center;
    padding: 5px;
}

.doctor-avatar {
    width: 64px;
    height: 64px;
    min-width: 64px;
    border-radius: 50%;
    overflow: hidden;
    background: #eef2f7;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #87909a;
    font-size: 27px;
}

.doctor-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.doctor-info {
    margin-left: 14px;
}

.doctor-info h5 {
    margin-bottom: 3px;
    font-size: 15px;
    font-weight: 600;
}

.doctor-info p {
    margin-bottom: 3px;
    font-size: 12px;
    color: #6c757d;
}

.doctor-info span {
    font-size: 12px;
    color: #198754;
}

.doctor-id {
    margin-left: auto;
    text-align: right;
}

.doctor-id small {
    display: block;
    font-size: 11px;
    color: #8a8f98;
}

.doctor-id strong {
    font-size: 13px;
}

.empty-state {
    text-align: center;
    padding: 25px 10px;
}

.empty-state > i {
    font-size: 35px;
    color: #b4bac2;
}

.empty-state h6 {
    margin-top: 10px;
    margin-bottom: 4px;
    font-size: 14px;
}

.empty-state p {
    color: #8a8f98;
    font-size: 12px;
    margin-bottom: 0;
}

.status-select {
    min-height: 44px;
}

.timeline {
    position: relative;
    padding-left: 8px;
}

.timeline-item {
    display: flex;
    position: relative;
    padding-bottom: 25px;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-item:not(:last-child)::before {
    content: "";
    position: absolute;
    left: 15px;
    top: 32px;
    bottom: 0;
    width: 1px;
    background: #e4e7eb;
}

.timeline-dot {
    width: 31px;
    height: 31px;
    min-width: 31px;
    border-radius: 50%;
    background: #f0f3f6;
    color: #58616c;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    z-index: 1;
}

.timeline-content {
    margin-left: 12px;
}

.timeline-content h6 {
    margin: 2px 0 4px;
    font-size: 13px;
    font-weight: 600;
}

.timeline-content p {
    margin: 0;
    color: #8a8f98;
    font-size: 11px;
}

.record-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 11px 0;
    border-bottom: 1px solid #f0f1f3;
    font-size: 13px;
}

.record-row:last-child {
    border-bottom: 0;
}

.record-row span {
    color: #8a8f98;
}

.record-row strong {
    color: #30353b;
    font-weight: 600;
}

@media (max-width: 767px) {

    .summary-item {
        margin-bottom: 18px;
    }

    .doctor-profile {
        align-items: flex-start;
    }

    .doctor-id {
        display: none;
    }

    .custom-card-header {
        padding: 15px;
    }

    .details-card .card-body {
        padding: 15px;
    }

}

</style>

@endsection