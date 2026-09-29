@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            <!-- Page Header -->
            <div class="page-header">

                <div class="page-title">
                    <h4>Hospital Details</h4>
                    <h6>View Hospital Profile</h6>
                </div>

                <div class="page-btn d-flex gap-2">

                    <a href="{{ route('admin.hospitals.edit', $hospital) }}" class="btn btn-primary">

                        <i class="ti ti-edit me-1"></i>

                        Edit

                    </a>

                    <a href="{{ route('admin.hospitals.index') }}" class="btn btn-secondary">

                        <i class="ti ti-arrow-left me-1"></i>

                        Back

                    </a>

                </div>

            </div>

            <div class="row">

                <!-- Left -->

                <div class="col-lg-4">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body text-center">

                            @if($hospital->logo)

                                <img src="{{ asset($hospital->logo) }}" class="rounded-circle border mb-3" width="150"
                                    height="150" style="object-fit:cover;">

                            @else

                                <div class="avatar avatar-xl bg-primary text-white rounded-circle mx-auto mb-3">

                                    <i class="ti ti-building-hospital fs-40"></i>

                                </div>

                            @endif

                            <h4 class="mb-1">

                                {{ $hospital->hospital_name }}

                            </h4>

                            <p class="text-muted">

                                {{ $hospital->hospital_type }}

                            </p>

                            @if($hospital->status)

                                <span class="badge bg-success">

                                    Active

                                </span>

                            @else

                                <span class="badge bg-danger">

                                    Inactive

                                </span>

                            @endif

                            <hr>

                            <div class="text-start">

                                <p>

                                    <strong>Hospital Code</strong>

                                    <br>

                                    {{ $hospital->hospital_code }}

                                </p>

                                <p>

                                    <strong>Registration No</strong>

                                    <br>

                                    {{ $hospital->registration_number ?? '-' }}

                                </p>

                                <p>

                                    <strong>GST Number</strong>

                                    <br>

                                    {{ $hospital->gst_number ?? '-' }}

                                </p>

                                <p>

                                    <strong>PAN Number</strong>

                                    <br>

                                    {{ $hospital->pan_number ?? '-' }}

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Right -->

                <div class="col-lg-8">

                    <!-- Contact -->

                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header">

                            <h5 class="mb-0">

                                Contact Information

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <strong>Email</strong>

                                    <br>

                                    {{ $hospital->email ?? '-' }}

                                </div>

                                <div class="col-md-6 mb-3">

                                    <strong>Mobile</strong>

                                    <br>

                                    {{ $hospital->mobile }}

                                </div>

                                <div class="col-md-6 mb-3">

                                    <strong>Phone</strong>

                                    <br>

                                    {{ $hospital->phone ?? '-' }}

                                </div>

                                <div class="col-md-6 mb-3">

                                    <strong>Website</strong>

                                    <br>

                                    @if($hospital->website)

                                        <a href="{{ $hospital->website }}" target="_blank">

                                            {{ $hospital->website }}

                                        </a>

                                    @else

                                        -

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Address -->

                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header">

                            <h5 class="mb-0">

                                Address Information

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-12 mb-3">

                                    <strong>Address</strong>

                                    <br>

                                    {{ $hospital->address }}

                                </div>

                                <div class="col-md-3">

                                    <strong>Country</strong>

                                    <br>

                                    {{ $hospital->country }}

                                </div>

                                <div class="col-md-3">

                                    <strong>State</strong>

                                    <br>

                                    {{ $hospital->state }}

                                </div>

                                <div class="col-md-3">

                                    <strong>City</strong>

                                    <br>

                                    {{ $hospital->city }}

                                </div>

                                <div class="col-md-3">

                                    <strong>Pincode</strong>

                                    <br>

                                    {{ $hospital->pincode }}

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Working Hours -->

                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header">

                            <h5 class="mb-0">

                                Working Hours

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-4">

                                    <strong>Opening Time</strong>

                                    <br>

                                    {{ $hospital->opening_time ?? '-' }}

                                </div>

                                <div class="col-md-4">

                                    <strong>Closing Time</strong>

                                    <br>

                                    {{ $hospital->closing_time ?? '-' }}

                                </div>

                                <div class="col-md-4">

                                    <strong>Emergency</strong>

                                    <br>

                                    @if($hospital->emergency_available)

                                        <span class="badge bg-success">

                                            Available

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            Not Available

                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="ti ti-stethoscope me-2"></i>
                                Hospital Specializations
                            </h5>
                        </div>

                        <div class="card-body">

                            @forelse($hospital->hospitalSpecializations as $hospitalSpecialization)

                                <span class="badge bg-primary me-2 mb-2 px-3 py-2">
                                    {{ $hospitalSpecialization->specialization->specialization_name }}
                                </span>

                            @empty

                                <span class="text-muted">
                                    No specializations assigned.
                                </span>

                            @endforelse

                        </div>

                    </div>

                    <!-- Banner -->

                    @if($hospital->banner)

                        <div class="card border-0 shadow-sm">

                            <div class="card-header">

                                <h5 class="mb-0">

                                    Hospital Banner

                                </h5>

                            </div>

                           <div class="card-body">

   @if($hospital->banner)

    <div class="card border-0 shadow-sm">

        <div class="card-header">
            <h5 class="mb-0">
                Hospital Banner
            </h5>
        </div>

        <div class="card-body">

            @php
                $banners = array_filter(explode(',', $hospital->banner));
            @endphp

            <div class="row">

                @foreach($banners as $banner)

                    <div class="col-md-4 col-sm-6 mb-3">

                        <div
                            class="hospital-banner-wrapper"
                            style="
                                position: relative;
                                width: 100%;
                                height: 100px;
                                border-radius: 8px;
                                overflow: hidden;
                            "
                        >

                            {{-- Banner Image --}}
                            <img
                                src="{{ asset(trim($banner)) }}"
                                alt="Hospital Banner"
                                class="img-fluid"
                                style="
                                    width: 100%;
                                    height: 100px;
                                    object-fit: cover;
                                    display: block;
                                "
                            >

                            {{-- Delete Button --}}
                            <button
                                type="button"
                                class="delete-banner-btn"
                                onclick="deleteBanner('{{ trim($banner) }}')"
                                title="Delete Banner"
                                style="
                                    position: absolute;
                                    top: 6px;
                                    right: 6px;
                                    width: 30px;
                                    height: 30px;
                                    padding: 0;
                                    border: none;
                                    border-radius: 50%;
                                    background: rgba(220, 53, 69, 0.95);
                                    color: #fff;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    cursor: pointer;
                                    z-index: 10;
                                    opacity: 0;
                                    visibility: hidden;
                                    transform: scale(0.8);
                                    transition: all 0.2s ease;
                                "
                            >
                                <i
                                    class="ti ti-trash"
                                    style="font-size: 16px;"
                                ></i>
                            </button>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>

@endif

</div>

<style>
    .hospital-banner-wrapper:hover .delete-banner-btn {
        opacity: 1 !important;
        visibility: visible !important;
        transform: scale(1) !important;
    }

    .delete-banner-btn:hover {
        background: #dc3545 !important;
        transform: scale(1.08) !important;
    }
</style>

                        </div>

                    @endif

                    <!-- Hospital Facilities -->
                    <div class="card border-0 shadow-sm mb-4 hospital-facilities-card">

                        <div class="card-header border-0 bg-white pt-4 px-4">

                            <div class="facility-title">

                                <span class="facility-title-line"></span>

                                <div>
                                    <h4 class="mb-1">
                                        Hospital Facilities
                                    </h4>

                                    <p class="mb-0 text-muted">
                                        Infrastructure and patient care
                                    </p>
                                </div>

                            </div>

                        </div>

                        <div class="card-body px-4 pb-4">

                            @forelse($hospital->facilities as $facility)

                                @php

                                    $icon = $facility->icon ?? 'ti-building-hospital';

                                    $colorClass = $facility->color ?? 'purple';

                                @endphp

                                <div class="facility-item">
                                    <a href="{{ route('admin.hospitals.facility.details', $facility->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        

                                            <div class="facility-box facility-{{ $colorClass }}">

                                                {{-- Facility Image --}}
                                                <div class="facility-icon">

                                                    @if($facility->facility->image)
                                                        <img src="{{ asset($facility->facility->image) }}" alt="{{ $facility->facility->name }}"
                                                            class="facility-image">
                                                    @else
                                                        <i class="ti ti-building-hospital"></i>
                                                    @endif

                                                </div>

                                                {{-- Facility Name --}}
                                                <h5>
                                                    {{ $facility->facility->name }}
                                                </h5>

                                                {{-- Arrow --}}
                                                <span class="facility-arrow"
                                                    title="{{ $facility->facility->name }}">

                                                    <i class="ti ti-arrow-up-right"></i>

                                            </span>

                                            </div>
                                        </a>    

                                </div>

                            @empty

                                <div class="facility-empty">

                                    <div class="facility-empty-icon">

                                        <i class="ti ti-building-hospital"></i>

                                    </div>

                                    <h5>
                                        No facilities assigned
                                    </h5>

                                    <p>
                                        No hospital facilities have been assigned to this hospital.
                                    </p>

                                </div>

                            @endforelse

                        </div>

                    </div>

                    <!-- Hospital Tieups -->
<div class="card border-0 shadow-sm mb-4 hospital-tieups-card">

    <div class="card-header border-0 bg-white pt-4 px-4">

        <div class="tieup-title">

            <span class="tieup-title-line"></span>

            <div>
                <h4 class="mb-1">
                    Hospital Tieups
                </h4>

                <p class="mb-0 text-muted">
                    Partner organizations and healthcare tieups
                </p>
            </div>

        </div>

    </div>

    <div class="card-body px-4 pb-4">


        @forelse($hospitalTieups as $index => $hospitalTieup)

            @php
                $tieup = $hospitalTieup->tieup;
            @endphp

            @if($tieup)

                <div class="tieup-item">

                    {{-- Tieup Card --}}
                    <a href="{{ route('admin.hospitals.tieups-details', $hospitalTieup->id) }}"
                       style="text-decoration: none; color: inherit;">

                        <div class="tieup-box tieup-{{ $colorClass }}">

                            {{-- Tieup Image --}}
                            <div class="tieup-icon">

                                @if($tieup->image)

                                    <img
                                        src="{{ asset($tieup->image) }}"
                                        alt="{{ $tieup->name }}"
                                        class="tieup-image"
                                    >

                                @else

                                    <i class="ti ti-link"></i>

                                @endif

                            </div>

                            {{-- Tieup Name --}}
                            <h5>
                                {{ $tieup->name }}
                            </h5>

                            {{-- Arrow --}}
                            <span
                                class="tieup-arrow"
                                title="{{ $tieup->name }}"
                            >

                                <i class="ti ti-arrow-up-right"></i>

                            </span>

                        </div>

                    </a>

                </div>

            @endif

        @empty

            <div class="tieup-empty">

                <div class="tieup-empty-icon">

                    <i class="ti ti-link"></i>

                </div>

                <h5>
                    No tieups assigned
                </h5>

                <p>
                    No hospital tieups have been assigned to this hospital.
                </p>

            </div>

        @endforelse

    </div>

</div>
<style>
    /* ==========================================
       Hospital Tieups
    ========================================== */

    .hospital-tieups-card {
        border-radius: 16px;
        overflow: hidden;
    }


    /* ==========================================
       Header
    ========================================== */

    .tieup-title {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .tieup-title-line {
        width: 5px;
        height: 48px;
        display: block;
        border-radius: 10px;

        background: linear-gradient(
            180deg,
            #7651d6,
            #9c5cff
        );
    }

    .tieup-title h4 {
        font-size: 24px;
        font-weight: 700;
        color: #202b3c;
    }

    .tieup-title p {
        font-size: 14px;
        color: #8a93a3 !important;
    }


    /* ==========================================
       Tieup Grid
    ========================================== */

    .hospital-tieups-card .card-body {
        display: grid;

        grid-template-columns:
            repeat(4, minmax(0, 1fr));

        gap: 16px;
    }


    /* ==========================================
       Tieup Item
    ========================================== */

    .tieup-item {
        width: 100%;
    }


    /* ==========================================
       Tieup Box
    ========================================== */

    .tieup-box {
        position: relative;

        width: 100%;
        min-height: 180px;

        padding: 22px;

        border: 1px solid #e5e7eb;

        border-radius: 20px;

        background: #ffffff;

        display: flex;
        flex-direction: column;
        justify-content: space-between;

        transition:
            transform 0.25s ease,
            box-shadow 0.25s ease,
            border-color 0.25s ease;

        overflow: hidden;
    }

    .tieup-box:hover {

        transform: translateY(-4px);

        box-shadow:
            0 12px 30px rgba(0, 0, 0, 0.08);

        border-color: transparent;
    }


    /* ==========================================
       Tieup Icon
    ========================================== */

    .tieup-icon {

        width: 82px;
        height: 82px;

        border-radius: 24px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-bottom: 18px;

        overflow: hidden;
    }

    .tieup-image {

        width: 100%;
        height: 100%;

        object-fit: fill;

        display: block;
    }

    .tieup-icon i {
        font-size: 40px;
    }


    /* ==========================================
       Tieup Name
    ========================================== */

    .tieup-box h5 {

        margin: 0;

        padding-right: 45px;

        font-size: 15px;

        line-height: 1.35;

        font-weight: 700;
        width: fit-content;

        color: #202b3c;
    }


    /* ==========================================
       Arrow
    ========================================== */

    .tieup-arrow {

        position: absolute;

        right: 15px;
        bottom: 15px;

        width: 42px;
        height: 42px;

        border-radius: 50%;

        display: flex;

        align-items: center;
        justify-content: center;

        text-decoration: none;

        transition: all 0.25s ease;
    }

    .tieup-arrow i {
        font-size: 22px;
    }

    .tieup-arrow:hover {
        transform: translate(3px, -3px);
    }


    /* ==========================================
       PURPLE
    ========================================== */

    .tieup-purple .tieup-icon {
        background: #f0ebff;
    }

    .tieup-purple .tieup-icon i {
        color: #7651d6;
    }

    .tieup-purple .tieup-arrow {
        background: #f4f0ff;
    }

    .tieup-purple .tieup-arrow i {
        color: #7651d6;
    }


    /* ==========================================
       BLUE
    ========================================== */

    .tieup-blue .tieup-icon {
        background: #eaf4ff;
    }

    .tieup-blue .tieup-icon i {
        color: #3787e8;
    }

    .tieup-blue .tieup-arrow {
        background: #f0f7ff;
    }

    .tieup-blue .tieup-arrow i {
        color: #3787e8;
    }


    /* ==========================================
       PINK
    ========================================== */

    .tieup-pink .tieup-icon {
        background: #fff0f6;
    }

    .tieup-pink .tieup-icon i {
        color: #e45b9e;
    }

    .tieup-pink .tieup-arrow {
        background: #fff5f9;
    }

    .tieup-pink .tieup-arrow i {
        color: #e45b9e;
    }


    /* ==========================================
       GREEN
    ========================================== */

    .tieup-green .tieup-icon {
        background: #eaf9f6;
    }

    .tieup-green .tieup-icon i {
        color: #13a69c;
    }

    .tieup-green .tieup-arrow {
        background: #effbf9;
    }

    .tieup-green .tieup-arrow i {
        color: #13a69c;
    }


    /* ==========================================
       ORANGE
    ========================================== */

    .tieup-orange .tieup-icon {
        background: #fff7e9;
    }

    .tieup-orange .tieup-icon i {
        color: #e49a20;
    }

    .tieup-orange .tieup-arrow {
        background: #fffaf1;
    }

    .tieup-orange .tieup-arrow i {
        color: #e49a20;
    }


    /* ==========================================
       Empty State
    ========================================== */

    .tieup-empty {

        grid-column: 1 / -1;

        text-align: center;

        padding: 50px 20px;
    }

    .tieup-empty-icon {

        width: 75px;
        height: 75px;

        margin: 0 auto 15px;

        border-radius: 50%;

        background: #f4f5f7;

        display: flex;
        align-items: center;
        justify-content: center;
    }

    .tieup-empty-icon i {

        font-size: 38px;

        color: #adb5bd;
    }

    .tieup-empty h5 {

        margin-bottom: 5px;

        color: #6c757d;
    }

    .tieup-empty p {

        margin: 0;

        color: #adb5bd;
    }


    /* ==========================================
       Responsive
    ========================================== */

    @media (max-width: 1199px) {

        .hospital-tieups-card .card-body {

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

        }

    }


    @media (max-width: 991px) {

        .hospital-tieups-card .card-body {

            grid-template-columns:
                repeat(2, minmax(0, 1fr));

        }

    }


    @media (max-width: 575px) {

        .hospital-tieups-card .card-body {

            grid-template-columns: 1fr;

        }

        .tieup-box {

            min-height: 165px;

        }

        .tieup-title h4 {

            font-size: 20px;

        }

    }
</style>
                    <style>
                        /* ==========================================
                           Hospital Facilities
                        ========================================== */

                        .hospital-facilities-card {
                            border-radius: 16px;
                            overflow: hidden;
                        }

                        /* Header */

                        .facility-title {
                            display: flex;
                            align-items: center;
                            gap: 14px;
                        }

                        .facility-title-line {
                            width: 5px;
                            height: 48px;
                            display: block;
                            border-radius: 10px;
                            background: linear-gradient(180deg,
                                    #7651d6,
                                    #9c5cff);
                        }

                        .facility-title h4 {
                            font-size: 24px;
                            font-weight: 700;
                            color: #202b3c;
                        }

                        .facility-title p {
                            font-size: 14px;
                            color: #8a93a3 !important;
                        }


                        /* ==========================================
                           Facility Grid
                        ========================================== */

                        .hospital-facilities-card .card-body {
                            display: grid;

                            grid-template-columns:
                                repeat(4, minmax(0, 1fr));

                            gap: 16px;
                        }


                        /* ==========================================
                           Facility Item
                        ========================================== */

                        .facility-item {
                            width: 100%;
                        }


                        /* ==========================================
                           Facility Box
                        ========================================== */

                        .facility-box {
                            position: relative;

                            width: 100%;
                            min-height: 180px;

                            padding: 22px;

                            border: 1px solid #e5e7eb;

                            border-radius: 20px;

                            background: #ffffff;

                            display: flex;
                            flex-direction: column;
                            justify-content: space-between;

                            transition:
                                transform 0.25s ease,
                                box-shadow 0.25s ease,
                                border-color 0.25s ease;

                            overflow: hidden;
                        }

                        .facility-box:hover {

                            transform: translateY(-4px);

                            box-shadow:
                                0 12px 30px rgba(0, 0, 0, 0.08);

                            border-color: transparent;
                        }


                        /* ==========================================
                           Facility Icon
                        ========================================== */

                        .facility-icon {
                            width: 82px;
                            height: 82px;

                            border-radius: 24px;

                            display: flex;
                            align-items: center;
                            justify-content: center;

                            margin-bottom: 18px;

                            overflow: hidden;
                        }

                        .facility-image {
                            width: 100%;
                            height: 100%;

                            object-fit: fill;


                            display: block;
                        }

                        .facility-icon i {
                            font-size: 40px;
                        }


                        /* ==========================================
                           Facility Name
                        ========================================== */

                        .facility-box h5 {

                            margin: 0;

                            padding-right: 45px;

                            font-size: 15px;

                            line-height: 1.35;

                            font-weight: 700;
                            width: fit-content;

                            color: #202b3c;
                        }


                        /* ==========================================
                           Arrow
                        ========================================== */

                        .facility-arrow {

                            position: absolute;

                            right: 15px;
                            bottom: 15px;

                            width: 42px;
                            height: 42px;

                            border-radius: 50%;

                            display: flex;

                            align-items: center;
                            justify-content: center;

                            text-decoration: none;

                            transition: all 0.25s ease;
                        }

                        .facility-arrow i {
                            font-size: 22px;
                        }

                        .facility-arrow:hover {
                            transform: translate(3px, -3px);
                        }


                        /* ==========================================
                           PURPLE
                        ========================================== */

                        .facility-purple .facility-icon {
                            background: #f0ebff;
                        }

                        .facility-purple .facility-icon i {
                            color: #7651d6;
                        }

                        .facility-purple .facility-arrow {
                            background: #f4f0ff;
                        }

                        .facility-purple .facility-arrow i {
                            color: #7651d6;
                        }


                        /* ==========================================
                           BLUE
                        ========================================== */

                        .facility-blue .facility-icon {
                            background: #eaf4ff;
                        }

                        .facility-blue .facility-icon i {
                            color: #3787e8;
                        }

                        .facility-blue .facility-arrow {
                            background: #f0f7ff;
                        }

                        .facility-blue .facility-arrow i {
                            color: #3787e8;
                        }


                        /* ==========================================
                           PINK
                        ========================================== */

                        .facility-pink .facility-icon {
                            background: #fff0f6;
                        }

                        .facility-pink .facility-icon i {
                            color: #e45b9e;
                        }

                        .facility-pink .facility-arrow {
                            background: #fff5f9;
                        }

                        .facility-pink .facility-arrow i {
                            color: #e45b9e;
                        }


                        /* ==========================================
                           GREEN
                        ========================================== */

                        .facility-green .facility-icon {
                            background: #eaf9f6;
                        }

                        .facility-green .facility-icon i {
                            color: #13a69c;
                        }

                        .facility-green .facility-arrow {
                            background: #effbf9;
                        }

                        .facility-green .facility-arrow i {
                            color: #13a69c;
                        }


                        /* ==========================================
                           ORANGE
                        ========================================== */

                        .facility-orange .facility-icon {
                            background: #fff7e9;
                        }

                        .facility-orange .facility-icon i {
                            color: #e49a20;
                        }

                        .facility-orange .facility-arrow {
                            background: #fffaf1;
                        }

                        .facility-orange .facility-arrow i {
                            color: #e49a20;
                        }


                        /* ==========================================
                           Empty State
                        ========================================== */

                        .facility-empty {

                            grid-column: 1 / -1;

                            text-align: center;

                            padding: 50px 20px;
                        }

                        .facility-empty-icon {

                            width: 75px;
                            height: 75px;

                            margin: 0 auto 15px;

                            border-radius: 50%;

                            background: #f4f5f7;

                            display: flex;
                            align-items: center;
                            justify-content: center;
                        }

                        .facility-empty-icon i {

                            font-size: 38px;

                            color: #adb5bd;
                        }

                        .facility-empty h5 {

                            margin-bottom: 5px;

                            color: #6c757d;
                        }

                        .facility-empty p {

                            margin: 0;

                            color: #adb5bd;
                        }


                        /* ==========================================
                           Responsive
                        ========================================== */

                        @media (max-width: 1199px) {

                            .hospital-facilities-card .card-body {

                                grid-template-columns:
                                    repeat(3, minmax(0, 1fr));

                            }

                        }


                        @media (max-width: 991px) {

                            .hospital-facilities-card .card-body {

                                grid-template-columns:
                                    repeat(2, minmax(0, 1fr));

                            }

                        }


                        @media (max-width: 575px) {

                            .hospital-facilities-card .card-body {

                                grid-template-columns: 1fr;

                            }

                            .facility-box {

                                min-height: 165px;

                            }

                            .facility-title h4 {

                                font-size: 20px;

                            }

                        }
                    </style>

                </div>

            </div>

        </div>

    </div>
   <script>
    function deleteBanner(banner) {

        if (!confirm('Are you sure you want to delete this banner?')) {
            return;
        }

        fetch("{{ route('admin.hospitals.banner.delete', $hospital->id) }}", {
            method: 'DELETE',

            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },

            body: JSON.stringify({
                banner: banner
            })
        })
        .then(response => {

            if (!response.ok) {
                throw new Error('Server error: ' + response.status);
            }

            return response.json();
        })
        .then(data => {

            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Unable to delete banner.');
            }

        })
        .catch(error => {

            console.error('Delete Banner Error:', error);

            alert('Something went wrong while deleting the banner.');
        });
    }
</script>

@endsection