@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            <!-- =========================
                     PAGE HEADER
                ========================== -->
            <div class="page-header">

                <div class="page-title">
                    <h4>Hospital Details</h4>
                    <h6>View Hospital Profile</h6>
                </div>

                <div class="page-btn d-flex gap-2">

                  @if(Route::has('hospital.hospitalprofile.edit'))
    <a href="{{ route('hospital.hospitalprofile.edit') }}"
       class="btn btn-primary">
        <i class="ti ti-edit me-1"></i>
        Edit
    </a>
@endif

                    @if(Route::has('hospital.dashboard'))
                        <a href="{{ route('hospital.dashboard') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i>
                            Back
                        </a>
                    @else
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i>
                            Back
                        </a>
                    @endif

                </div>

            </div>


            <div class="row">

                <!-- =========================
                         LEFT SIDE
                    ========================== -->
                <div class="col-lg-4">

                    <div class="card border-0 shadow-sm hospital-profile-card">

                        <div class="card-body text-center">

                            {{-- Hospital Logo --}}
                            @if($hospital->logo)

                                <img src="{{ asset($hospital->logo) }}" alt="{{ $hospital->hospital_name }}"
                                    class="rounded-circle border mb-3 hospital-logo">

                            @else

                                <div class="hospital-default-logo">
                                    <i class="ti ti-building-hospital"></i>
                                </div>

                            @endif


                            {{-- Hospital Name --}}
                            <h4 class="mb-1">
                                {{ $hospital->hospital_name }}
                            </h4>

                            <p class="text-muted mb-2">
                                {{ $hospital->hospital_type ?? '-' }}
                            </p>


                            {{-- Status --}}
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


                            <div class="text-start hospital-basic-info">

                                <div class="info-item">
                                    <strong>Hospital Code</strong>
                                    <span>
                                        {{ $hospital->hospital_code ?? '-' }}
                                    </span>
                                </div>

                                <div class="info-item">
                                    <strong>Registration No</strong>
                                    <span>
                                        {{ $hospital->registration_number ?? '-' }}
                                    </span>
                                </div>

                                <div class="info-item">
                                    <strong>GST Number</strong>
                                    <span>
                                        {{ $hospital->gst_number ?? '-' }}
                                    </span>
                                </div>

                                <div class="info-item">
                                    <strong>PAN Number</strong>
                                    <span>
                                        {{ $hospital->pan_number ?? '-' }}
                                    </span>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- =========================
                         RIGHT SIDE
                    ========================== -->
                <div class="col-lg-8">


                    <!-- =========================
                             CONTACT INFORMATION
                        ========================== -->
                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header">

                            <h5 class="mb-0">
                                <i class="ti ti-phone me-2"></i>
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

                                    {{ $hospital->mobile ?? '-' }}

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

                                        <a href="{{ $hospital->website }}" target="_blank" rel="noopener noreferrer">

                                            {{ $hospital->website }}

                                        </a>

                                    @else

                                        -

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- =========================
                             ADDRESS INFORMATION
                        ========================== -->
                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header">

                            <h5 class="mb-0">
                                <i class="ti ti-map-pin me-2"></i>
                                Address Information
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-12 mb-3">

                                    <strong>Address</strong>

                                    <br>

                                    {{ $hospital->address ?? '-' }}

                                </div>


                                <div class="col-md-3 mb-3">

                                    <strong>Country</strong>

                                    <br>

                                    {{ $hospital->country ?? '-' }}

                                </div>


                                <div class="col-md-3 mb-3">

                                    <strong>State</strong>

                                    <br>

                                    {{ $hospital->state ?? '-' }}

                                </div>


                                <div class="col-md-3 mb-3">

                                    <strong>City</strong>

                                    <br>

                                    {{ $hospital->city ?? '-' }}

                                </div>


                                <div class="col-md-3 mb-3">

                                    <strong>Pincode</strong>

                                    <br>

                                    {{ $hospital->pincode ?? '-' }}

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- =========================
                             WORKING HOURS
                        ========================== -->
                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header">

                            <h5 class="mb-0">
                                <i class="ti ti-clock me-2"></i>
                                Working Hours
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-4 mb-3">

                                    <strong>Opening Time</strong>

                                    <br>

                                    {{ $hospital->opening_time ?? '-' }}

                                </div>


                                <div class="col-md-4 mb-3">

                                    <strong>Closing Time</strong>

                                    <br>

                                    {{ $hospital->closing_time ?? '-' }}

                                </div>


                                <div class="col-md-4 mb-3">

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


                    <!-- =========================
                             SPECIALIZATIONS
                        ========================== -->
                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header">

                            <h5 class="mb-0">
                                <i class="ti ti-stethoscope me-2"></i>
                                Hospital Specializations
                            </h5>

                        </div>

                        <div class="card-body">

                            @forelse($hospital->hospitalSpecializations as $hospitalSpecialization)

                                @if($hospitalSpecialization->specialization)

                                    <span class="badge bg-primary me-2 mb-2 px-3 py-2">

                                        {{ $hospitalSpecialization->specialization->specialization_name }}

                                    </span>

                                @endif

                            @empty

                                <span class="text-muted">
                                    No specializations assigned.
                                </span>

                            @endforelse

                        </div>

                    </div>


                    <!-- =========================
                             HOSPITAL BANNER
                        ========================== -->
                    @if($hospital->banner)

                        <div class="card border-0 shadow-sm mb-4">

                            <div class="card-header">

                                <h5 class="mb-0">
                                    <i class="ti ti-photo me-2"></i>
                                    Hospital Banner
                                </h5>

                            </div>

                            <div class="card-body">

                                @php
                                    $banners = array_filter(explode(',', $hospital->banner));
                                @endphp

                                <div class="row">

                                    @foreach($banners as $banner)

                                        @php
                                            $bannerPath = trim($banner);
                                        @endphp

                                        <div class="col-md-4 col-sm-6 mb-3">

                                            <div class="hospital-banner-wrapper position-relative">

                                                <img src="{{ asset($bannerPath) }}" alt="Hospital Banner"
                                                    class="hospital-banner-image">

                                                {{-- Delete Banner --}}
                                              @if(Route::has('hospital.hospitalprofile.banner.delete'))

    <form action="{{ route('hospital.hospitalprofile.banner.delete') }}"
          method="POST"
          class="delete-banner-form"
          onsubmit="return confirm('Are you sure you want to delete this banner?');">

        @csrf
        @method('DELETE')

        <input type="hidden"
               name="banner"
               value="{{ $bannerPath }}">

        <button type="submit"
                class="delete-banner-btn"
                title="Delete Banner">
            <i class="ti ti-trash"></i>
        </button>

    </form>

@endif

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                            </div>

                        </div>

                    @endif


                    <!-- =========================
                             HOSPITAL FACILITIES
                        ========================== -->
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

                                    $facilityData = $facility->facility;

                                @endphp


                                @if($facilityData)

                                    <div class="facility-item">

                                        @if(Route::has('hospital.hospitals.facility.details'))

                                                            <a href="{{ route(
                                                'hospital.hospitals.facility.details',
                                                $facility->id
                                            ) }}" style="text-decoration:none;color:inherit;">

                                        @else

                                                <a href="javascript:void(0);" style="text-decoration:none;color:inherit;">

                                            @endif


                                                <div class="facility-box facility-{{ $colorClass }}">

                                                    {{-- Image --}}
                                                    <div class="facility-icon">

                                                        @if($facilityData->image)

                                                            <img src="{{ asset($facilityData->image) }}" alt="{{ $facilityData->name }}"
                                                                class="facility-image">

                                                        @else

                                                            <i class="ti {{ $icon }}"></i>

                                                        @endif

                                                    </div>


                                                    {{-- Name --}}
                                                    <h5>
                                                        {{ $facilityData->name }}
                                                    </h5>


                                                    {{-- Arrow --}}
                                                    <span class="facility-arrow">

                                                        <i class="ti ti-arrow-up-right"></i>

                                                    </span>

                                                </div>

                                            </a>

                                    </div>

                                @endif

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


                    <!-- =========================
                             HOSPITAL TIEUPS
                        ========================== -->
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

                                    /*
                                     * Give each tieup a different color.
                                     */
                                    $tieupColors = [
                                        'purple',
                                        'blue',
                                        'pink',
                                        'green',
                                        'orange'
                                    ];

                                    $colorClass = $tieupColors[
                                        $index % count($tieupColors)
                                    ];
                                @endphp


                               @if($tieup)

    <div class="tieup-item">

        <a href="{{ route('hospital.hospitalprofile.tieups-details', ['tieup' => $tieup->id]) }}"
           style="text-decoration:none;color:inherit;">

            <div class="tieup-box tieup-{{ $colorClass }}">

                {{-- Image --}}
                <div class="tieup-icon">

                    @if($tieup->image)

                        <img src="{{ asset($tieup->image) }}"
                             alt="{{ $tieup->name }}"
                             class="tieup-image">

                    @else

                        <i class="ti ti-link"></i>

                    @endif

                </div>

                {{-- Name --}}
                <h5>
                    {{ $tieup->name }}
                </h5>

                {{-- Arrow --}}
                <span class="tieup-arrow">
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


                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
             CSS
        ====================================================== -->
    <style>
        /* =========================
               Hospital Profile
            ========================== */

        .hospital-profile-card {
            border-radius: 16px;
            overflow: hidden;
        }

        .hospital-logo {
            width: 150px;
            height: 150px;
            object-fit: cover;
        }

        .hospital-default-logo {
            width: 150px;
            height: 150px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #f0ebff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hospital-default-logo i {
            font-size: 60px;
            color: #7651d6;
        }

        .hospital-basic-info .info-item {
            margin-bottom: 18px;
        }

        .hospital-basic-info .info-item strong {
            display: block;
            margin-bottom: 3px;
            color: #202b3c;
        }

        .hospital-basic-info .info-item span {
            color: #6c757d;
        }


        /* =========================
               Facilities + Tieups
            ========================== */

        .hospital-facilities-card,
        .hospital-tieups-card {
            border-radius: 16px;
            overflow: hidden;
        }

        .facility-title,
        .tieup-title {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .facility-title-line,
        .tieup-title-line {
            width: 5px;
            height: 48px;
            display: block;
            border-radius: 10px;
            background: linear-gradient(180deg,
                    #7651d6,
                    #9c5cff);
        }

        .facility-title h4,
        .tieup-title h4 {
            font-size: 24px;
            font-weight: 700;
            color: #202b3c;
        }

        .facility-title p,
        .tieup-title p {
            font-size: 14px;
            color: #8a93a3 !important;
        }


        /* =========================
               Grid
            ========================== */

        .hospital-facilities-card .card-body,
        .hospital-tieups-card .card-body {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }


        .facility-item,
        .tieup-item {
            width: 100%;
        }


        /* =========================
               Facility / Tieup Box
            ========================== */

        .facility-box,
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
            overflow: hidden;
            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease,
                border-color 0.25s ease;
        }

        .facility-box:hover,
        .tieup-box:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
            border-color: transparent;
        }


        /* =========================
               Facility / Tieup Image
            ========================== */

        .facility-icon,
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

        .facility-image,
        .tieup-image {
            width: 100%;
            height: 100%;
            object-fit: fill;
            display: block;
        }

        .facility-icon i,
        .tieup-icon i {
            font-size: 40px;
        }


        /* =========================
               Name
            ========================== */

        .facility-box h5,
        .tieup-box h5 {
            margin: 0;
            padding-right: 45px;
            font-size: 15px;
            line-height: 1.35;
            font-weight: 700;
            width: fit-content;
            color: #202b3c;
        }


        /* =========================
               Arrow
            ========================== */

        .facility-arrow,
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
            transition: all 0.25s ease;
        }

        .facility-arrow i,
        .tieup-arrow i {
            font-size: 22px;
        }

        .facility-arrow:hover,
        .tieup-arrow:hover {
            transform: translate(3px, -3px);
        }


        /* =========================
               PURPLE
            ========================== */

        .facility-purple .facility-icon,
        .tieup-purple .tieup-icon {
            background: #f0ebff;
        }

        .facility-purple .facility-icon i,
        .tieup-purple .tieup-icon i {
            color: #7651d6;
        }

        .facility-purple .facility-arrow,
        .tieup-purple .tieup-arrow {
            background: #f4f0ff;
        }

        .facility-purple .facility-arrow i,
        .tieup-purple .tieup-arrow i {
            color: #7651d6;
        }


        /* =========================
               BLUE
            ========================== */

        .facility-blue .facility-icon,
        .tieup-blue .tieup-icon {
            background: #eaf4ff;
        }

        .facility-blue .facility-icon i,
        .tieup-blue .tieup-icon i {
            color: #3787e8;
        }

        .facility-blue .facility-arrow,
        .tieup-blue .tieup-arrow {
            background: #f0f7ff;
        }

        .facility-blue .facility-arrow i,
        .tieup-blue .tieup-arrow i {
            color: #3787e8;
        }


        /* =========================
               PINK
            ========================== */

        .facility-pink .facility-icon,
        .tieup-pink .tieup-icon {
            background: #fff0f6;
        }

        .facility-pink .facility-icon i,
        .tieup-pink .tieup-icon i {
            color: #e45b9e;
        }

        .facility-pink .facility-arrow,
        .tieup-pink .tieup-arrow {
            background: #fff5f9;
        }

        .facility-pink .facility-arrow i,
        .tieup-pink .tieup-arrow i {
            color: #e45b9e;
        }


        /* =========================
               GREEN
            ========================== */

        .facility-green .facility-icon,
        .tieup-green .tieup-icon {
            background: #eaf9f6;
        }

        .facility-green .facility-icon i,
        .tieup-green .tieup-icon i {
            color: #13a69c;
        }

        .facility-green .facility-arrow,
        .tieup-green .tieup-arrow {
            background: #effbf9;
        }

        .facility-green .facility-arrow i,
        .tieup-green .tieup-arrow i {
            color: #13a69c;
        }


        /* =========================
               ORANGE
            ========================== */

        .facility-orange .facility-icon,
        .tieup-orange .tieup-icon {
            background: #fff7e9;
        }

        .facility-orange .facility-icon i,
        .tieup-orange .tieup-icon i {
            color: #e49a20;
        }

        .facility-orange .facility-arrow,
        .tieup-orange .tieup-arrow {
            background: #fffaf1;
        }

        .facility-orange .facility-arrow i,
        .tieup-orange .tieup-arrow i {
            color: #e49a20;
        }


        /* =========================
               Empty States
            ========================== */

        .facility-empty,
        .tieup-empty {
            grid-column: 1 / -1;
            text-align: center;
            padding: 50px 20px;
        }

        .facility-empty-icon,
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

        .facility-empty-icon i,
        .tieup-empty-icon i {
            font-size: 38px;
            color: #adb5bd;
        }

        .facility-empty h5,
        .tieup-empty h5 {
            margin-bottom: 5px;
            color: #6c757d;
        }

        .facility-empty p,
        .tieup-empty p {
            margin: 0;
            color: #adb5bd;
        }


        /* =========================
               Banner
            ========================== */

        .hospital-banner-wrapper {
            position: relative;
            width: 100%;
            height: 100px;
            border-radius: 8px;
            overflow: hidden;
        }

        .hospital-banner-image {
            width: 100%;
            height: 100px;
            object-fit: cover;
            display: block;
        }

        .delete-banner-btn {
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
        }

        .hospital-banner-wrapper:hover .delete-banner-btn {
            opacity: 1;
            visibility: visible;
            transform: scale(1);
        }

        .delete-banner-btn:hover {
            background: #dc3545;
            transform: scale(1.08);
        }


        /* =========================
               Responsive
            ========================== */

        @media (max-width: 1199px) {

            .hospital-facilities-card .card-body,
            .hospital-tieups-card .card-body {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

        }


        @media (max-width: 991px) {

            .hospital-facilities-card .card-body,
            .hospital-tieups-card .card-body {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

        }


        @media (max-width: 575px) {

            .hospital-facilities-card .card-body,
            .hospital-tieups-card .card-body {
                grid-template-columns: 1fr;
            }

            .facility-box,
            .tieup-box {
                min-height: 165px;
            }

            .facility-title h4,
            .tieup-title h4 {
                font-size: 20px;
            }

        }
    </style>


    <!-- =====================================================
             DELETE BANNER SCRIPT
        ====================================================== -->
    @if(Route::has('hospital.hospitals.banner.delete'))

        <script>

            function deleteBanner(banner) {

                if (!confirm('Are you sure you want to delete this banner?')) {
                    return;
                }

                fetch(
                    "{{ route('hospital.hospitals.banner.delete', $hospital->id) }}",
                    {
                        method: 'DELETE',

                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },

                        body: JSON.stringify({
                            banner: banner
                        })
                    }
                )

                    .then(response => {

                        if (!response.ok) {
                            throw new Error(
                                'Server error: ' + response.status
                            );
                        }

                        return response.json();

                    })

                    .then(data => {

                        if (data.success) {

                            location.reload();

                        } else {

                            alert(
                                data.message ||
                                'Unable to delete banner.'
                            );

                        }

                    })

                    .catch(error => {

                        console.error(
                            'Delete Banner Error:',
                            error
                        );

                        alert(
                            'Something went wrong while deleting the banner.'
                        );

                    });

            }

        </script>

    @endif

@endsection