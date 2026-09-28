

@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            {{-- =========================
            PAGE HEADER
            ========================== --}}
            <div class="page-header">

                <div class="page-title">

                    <h4>Tieup Details</h4>

                    <h6>
                        View hospital tieup information
                    </h6>

                </div>

                <div class="page-btn">

                    <a href="{{ url()->previous() }}" class="btn tieup-back-btn">

                        <i class="ti ti-arrow-left me-1"></i>
                        Back

                    </a>

                </div>

            </div>


            {{-- =========================
            BREADCRUMB
            ========================== --}}
            <div class="tieup-breadcrumb">

                <a href="{{ route('hospital.hospitalprofile.index') }}">
                    <i class="ti ti-building-hospital"></i>
                    Hospital Profile
                </a>

                <span>
                    <i class="ti ti-chevron-right"></i>
                </span>

                <span>
                    Tieup Details
                </span>

            </div>


            {{-- =========================
            TIEUP DETAILS CARD
            ========================== --}}
            <div class="card border-0 shadow-sm tieup-details-card">

                <div class="card-body">

                    @if($tieupData)

                        <div class="tieup-details-wrapper">

                            {{-- =========================
                            TIEUP IMAGE
                            ========================== --}}
                            <div class="tieup-image-section">

                                <div class="tieup-main-image">

                                    @if(!empty($tieupData->image))

                                        <img src="{{ asset($tieupData->image) }}" alt="{{ $tieupData->name }}">

                                    @else

                                        <div class="tieup-image-placeholder">

                                            <i class="ti ti-link"></i>

                                        </div>

                                    @endif

                                </div>

                            </div>


                            {{-- =========================
                            TIEUP INFORMATION
                            ========================== --}}
                            <div class="tieup-info-section">

                                {{-- Badge --}}
                                <span class="tieup-badge">

                                    <i class="ti ti-link me-1"></i>

                                    Hospital Tieup

                                </span>


                                {{-- Name --}}
                                <div class="tieup-name-box">

                                    <div class="tieup-name-icon">

                                        <i class="ti ti-building-hospital"></i>

                                    </div>

                                    <div>

                                        <h2>
                                            {{ $tieupData->name }}
                                        </h2>

                                        <span>
                                            Partner Organization
                                        </span>

                                    </div>

                                </div>


                                {{-- Description --}}
                                <div class="tieup-description">

                                    <h5>

                                        <i class="ti ti-info-circle me-1"></i>

                                        Description

                                    </h5>

                                    @if(!empty($tieupData->description))

                                        <p>
                                            {{ $tieupData->description }}
                                        </p>

                                    @else

                                        <p class="text-muted">
                                            No description available for this tieup.
                                        </p>

                                    @endif

                                </div>


                                {{-- Tieup Meta --}}
                                <div class="tieup-meta-grid">

                                    <div class="tieup-meta-item">

                                        <span class="meta-label">
                                            Tieup Name
                                        </span>

                                        <strong>
                                            {{ $tieupData->name }}
                                        </strong>

                                    </div>

                                    <div class="tieup-meta-item">

                                        <span class="meta-label">
                                            Status
                                        </span>

                                        <strong class="status-active">

                                            <i class="ti ti-circle-check me-1"></i>

                                            Assigned

                                        </strong>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @else

                        <div class="tieup-empty-state">

                            <div class="tieup-empty-icon">

                                <i class="ti ti-link-off"></i>

                            </div>

                            <h5>
                                Tieup Details Not Found
                            </h5>

                            <p>
                                The requested hospital tieup could not be found.
                            </p>

                        </div>

                    @endif

                </div>

            </div>


            {{-- =========================
            CONTACT INFORMATION
            ========================== --}}
            @if($tieupData)

                @if(
                        !empty($tieupData->phone) ||
                        !empty($tieupData->email) ||
                        !empty($tieupData->address) ||
                        !empty($tieupData->website)
                    )

                    <div class="card border-0 shadow-sm tieup-contact-card">

                        <div class="card-header">

                            <div class="contact-title">

                                <span class="contact-title-line"></span>

                                <div>

                                    <h4>
                                        Contact Information
                                    </h4>

                                    <p>
                                        Contact details for this partner organization
                                    </p>

                                </div>

                            </div>

                        </div>


                        <div class="card-body">

                            <div class="row g-3">

                                {{-- Phone --}}
                                @if(!empty($tieupData->phone))

                                    <div class="col-lg-3 col-md-6">

                                        <div class="contact-info-item">

                                            <div class="contact-info-icon phone">

                                                <i class="ti ti-phone"></i>

                                            </div>

                                            <div class="contact-info-content">

                                                <span>
                                                    Phone
                                                </span>

                                                <strong>
                                                    {{ $tieupData->phone }}
                                                </strong>

                                            </div>

                                        </div>

                                    </div>

                                @endif


                                {{-- Email --}}
                                @if(!empty($tieupData->email))

                                    <div class="col-lg-3 col-md-6">

                                        <div class="contact-info-item">

                                            <div class="contact-info-icon email">

                                                <i class="ti ti-mail"></i>

                                            </div>

                                            <div class="contact-info-content">

                                                <span>
                                                    Email
                                                </span>

                                                <strong>
                                                    {{ $tieupData->email }}
                                                </strong>

                                            </div>

                                        </div>

                                    </div>

                                @endif


                                {{-- Address --}}
                                @if(!empty($tieupData->address))

                                    <div class="col-lg-3 col-md-6">

                                        <div class="contact-info-item">

                                            <div class="contact-info-icon address">

                                                <i class="ti ti-map-pin"></i>

                                            </div>

                                            <div class="contact-info-content">

                                                <span>
                                                    Address
                                                </span>

                                                <strong>
                                                    {{ $tieupData->address }}
                                                </strong>

                                            </div>

                                        </div>

                                    </div>

                                @endif


                                {{-- Website --}}
                                @if(!empty($tieupData->website))

                                    <div class="col-lg-3 col-md-6">

                                        <div class="contact-info-item">

                                            <div class="contact-info-icon website">

                                                <i class="ti ti-world"></i>

                                            </div>

                                            <div class="contact-info-content">

                                                <span>
                                                    Website
                                                </span>

                                                <a href="{{ $tieupData->website }}" target="_blank" rel="noopener noreferrer">

                                                    Visit Website

                                                    <i class="ti ti-external-link ms-1"></i>

                                                </a>

                                            </div>

                                        </div>

                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>

                @endif

            @endif


            {{-- =========================
            HOSPITAL INFORMATION
            ========================== --}}
            <div class="card border-0 shadow-sm hospital-info-card">

                <div class="card-header">

                    <div class="hospital-list-title">

                        <span class="hospital-title-line"></span>

                        <div>

                            <h4>
                                Hospital Information
                            </h4>

                            <p>
                                Hospital associated with this tieup
                            </p>

                        </div>

                    </div>

                </div>


                <div class="card-body">

                    <div class="row g-3">

                        {{-- Hospital Name --}}
                        <div class="col-lg-4 col-md-6">

                            <div class="hospital-info-box">

                                <div class="hospital-info-icon">

                                    <i class="ti ti-building-hospital"></i>

                                </div>

                                <div>

                                    <span>
                                        Hospital Name
                                    </span>

                                    <strong>
                                        {{ $hospital->name ?? '-' }}
                                    </strong>

                                </div>

                            </div>

                        </div>


                        {{-- Hospital Phone --}}
                        @if(!empty($hospital->phone))

                            <div class="col-lg-4 col-md-6">

                                <div class="hospital-info-box">

                                    <div class="hospital-info-icon">

                                        <i class="ti ti-phone"></i>

                                    </div>

                                    <div>

                                        <span>
                                            Hospital Phone
                                        </span>

                                        <strong>
                                            {{ $hospital->phone }}
                                        </strong>

                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- Hospital Email --}}
                        @if(!empty($hospital->email))

                            <div class="col-lg-4 col-md-6">

                                <div class="hospital-info-box">

                                    <div class="hospital-info-icon">

                                        <i class="ti ti-mail"></i>

                                    </div>

                                    <div>

                                        <span>
                                            Hospital Email
                                        </span>

                                        <strong>
                                            {{ $hospital->email }}
                                        </strong>

                                    </div>

                                </div>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- =========================================================
    CSS
    ========================================================= --}}

    <style>
        /* =========================
       PAGE HEADER
    ========================= */

        .page-title h4 {
            color: #172b4d;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .page-title h6 {
            color: #8a98ad;
            font-size: 14px;
            font-weight: 400;
        }


        /* =========================
       BREADCRUMB
    ========================= */

        .tieup-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            font-size: 14px;
            color: #71809a;
        }

        .tieup-breadcrumb a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #4f8df7;
            text-decoration: none;
            font-weight: 500;
        }

        .tieup-breadcrumb a:hover {
            color: #2563eb;
        }

        .tieup-breadcrumb span {
            display: inline-flex;
            align-items: center;
        }

        .tieup-breadcrumb i {
            font-size: 16px;
        }


        /* =========================
       BACK BUTTON
    ========================= */

        .tieup-back-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            color: #52627a;
            border: 1px solid #dce5f2;
            border-radius: 8px;
            padding: 8px 15px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .tieup-back-btn:hover {
            background: #4f8df7;
            border-color: #4f8df7;
            color: #ffffff;
        }


        /* =========================
       MAIN DETAILS CARD
    ========================= */

        .tieup-details-card {
            border: 1px solid #e8edf5 !important;
            border-radius: 18px;
            background: #ffffff;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .tieup-details-card .card-body {
            padding: 28px;
        }

        .tieup-details-wrapper {
            display: flex;
            align-items: flex-start;
            gap: 30px;
        }


        /* =========================
       TIEUP IMAGE
    ========================= */

        .tieup-image-section {
            flex: 0 0 180px;
        }

        .tieup-main-image {
            width: 180px;
            height: 180px;
            border-radius: 18px;
            overflow: hidden;
            background: #f1f5fb;
            border: 1px solid #e8edf5;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .tieup-main-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .tieup-image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #edf5ff;
        }

        .tieup-image-placeholder i {
            font-size: 65px;
            color: #5891ed;
        }


        /* =========================
       TIEUP INFO
    ========================= */

        .tieup-info-section {
            flex: 1;
            min-width: 0;
        }

        .tieup-badge {
            display: inline-flex;
            align-items: center;
            background: #edf5ff;
            color: #397fe5;
            border-radius: 20px;
            padding: 6px 12px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 14px;
        }


        /* =========================
       NAME BOX
    ========================= */

        .tieup-name-box {
            display: flex;
            align-items: center;
            gap: 14px;
            background: #f0f6ff;
            border-left: 4px solid #4f7cff;
            border-radius: 10px;
            padding: 15px 18px;
            margin-bottom: 18px;
        }

        .tieup-name-icon {
            width: 46px;
            height: 46px;
            min-width: 46px;
            border-radius: 12px;
            background: #e2ecff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .tieup-name-icon i {
            font-size: 23px;
            color: #4f7cff;
        }

        .tieup-name-box h2 {
            color: #1f3b64;
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            line-height: 1.3;
        }

        .tieup-name-box span {
            display: block;
            color: #71809a;
            font-size: 13px;
            margin-top: 2px;
        }


        /* =========================
       DESCRIPTION
    ========================= */

        .tieup-description {
            margin-bottom: 20px;
        }

        .tieup-description h5 {
            color: #26364f;
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 7px;
        }

        .tieup-description h5 i {
            color: #4f8df7;
        }

        .tieup-description p {
            color: #71809a;
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 0;
        }


        /* =========================
       META GRID
    ========================= */

        .tieup-meta-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .tieup-meta-item {
            background: #f8fafc;
            border: 1px solid #edf1f7;
            border-radius: 10px;
            padding: 12px 14px;
        }

        .tieup-meta-item .meta-label {
            display: block;
            color: #8a98ad;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .tieup-meta-item strong {
            display: block;
            color: #27364d;
            font-size: 14px;
            font-weight: 600;
            word-break: break-word;
        }

        .status-active {
            color: #159a72 !important;
        }


        /* =========================
       CONTACT CARD
    ========================= */

        .tieup-contact-card {
            border: 1px solid #e8edf5 !important;
            border-radius: 18px;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .tieup-contact-card .card-header,
        .hospital-info-card .card-header {
            padding: 22px 24px;
            background: #ffffff;
            border-bottom: 1px solid #edf1f7;
        }

        .tieup-contact-card .card-body,
        .hospital-info-card .card-body {
            padding: 20px 24px 24px;
        }

        .contact-title {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .contact-title-line {
            width: 5px;
            height: 48px;
            display: block;
            border-radius: 10px;
            background: linear-gradient(180deg,
                    #4f8df7,
                    #72a8ff);
        }

        .contact-title h4 {
            font-size: 21px;
            font-weight: 700;
            color: #202b3c;
            margin-bottom: 3px;
        }

        .contact-title p {
            color: #8a93a3;
            font-size: 13px;
            margin-bottom: 0;
        }


        /* =========================
       CONTACT ITEMS
    ========================= */

        .contact-info-item {
            display: flex;
            align-items: center;
            gap: 12px;
            height: 100%;
            padding: 15px;
            background: #ffffff;
            border: 1px solid #e9eef5;
            border-radius: 13px;
            transition: all 0.25s ease;
        }

        .contact-info-item:hover {
            transform: translateY(-2px);
            border-color: #dbe7f7;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        .contact-info-icon {
            width: 44px;
            height: 44px;
            min-width: 44px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .contact-info-icon.phone {
            background: #e9faf4;
            color: #159a72;
        }

        .contact-info-icon.email {
            background: #edf5ff;
            color: #397fe5;
        }

        .contact-info-icon.address {
            background: #fff3e8;
            color: #e88635;
        }

        .contact-info-icon.website {
            background: #f0edff;
            color: #7258d8;
        }

        .contact-info-content {
            min-width: 0;
        }

        .contact-info-content span {
            display: block;
            color: #8a98ad;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .contact-info-content strong,
        .contact-info-content a {
            display: block;
            color: #27364d;
            font-size: 13px;
            font-weight: 600;
            word-break: break-word;
            text-decoration: none;
        }

        .contact-info-content a {
            color: #4f8df7;
        }

        .contact-info-content a:hover {
            color: #2563eb;
        }


        /* =========================
       HOSPITAL INFORMATION
    ========================= */

        .hospital-info-card {
            border: 1px solid #e8edf5 !important;
            border-radius: 18px;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .hospital-list-title {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .hospital-title-line {
            width: 5px;
            height: 48px;
            display: block;
            border-radius: 10px;
            background: linear-gradient(180deg,
                    #18a77a,
                    #48c59d);
        }

        .hospital-list-title h4 {
            font-size: 21px;
            font-weight: 700;
            color: #202b3c;
            margin-bottom: 3px;
        }

        .hospital-list-title p {
            color: #8a93a3;
            font-size: 13px;
            margin-bottom: 0;
        }

        .hospital-info-box {
            display: flex;
            align-items: center;
            gap: 13px;
            height: 100%;
            padding: 15px;
            background: #f8fafc;
            border: 1px solid #edf1f7;
            border-radius: 13px;
        }

        .hospital-info-icon {
            width: 44px;
            height: 44px;
            min-width: 44px;
            border-radius: 11px;
            background: #edf5ff;
            color: #4f8df7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .hospital-info-box span {
            display: block;
            color: #8a98ad;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .hospital-info-box strong {
            display: block;
            color: #27364d;
            font-size: 14px;
            font-weight: 600;
            word-break: break-word;
        }


        /* =========================
       EMPTY STATE
    ========================= */

        .tieup-empty-state {
            text-align: center;
            padding: 55px 20px;
        }

        .tieup-empty-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 15px;
            border-radius: 20px;
            background: #edf5ff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .tieup-empty-icon i {
            font-size: 35px;
            color: #5891ed;
        }

        .tieup-empty-state h5 {
            color: #27364d;
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .tieup-empty-state p {
            color: #8a98ad;
            font-size: 14px;
            margin-bottom: 0;
        }


        /* =========================
       RESPONSIVE
    ========================= */

        @media (max-width: 991px) {

            .tieup-details-wrapper {
                flex-direction: column;
            }

            .tieup-image-section {
                flex: none;
            }

            .tieup-main-image {
                width: 155px;
                height: 155px;
            }

            .contact-info-item {
                min-height: 74px;
            }

        }


        @media (max-width: 767px) {

            .tieup-details-card .card-body {
                padding: 20px;
            }

            .tieup-contact-card .card-header,
            .hospital-info-card .card-header {
                padding: 18px 20px;
            }

            .tieup-contact-card .card-body,
            .hospital-info-card .card-body {
                padding: 18px 20px 20px;
            }

            .tieup-name-box h2 {
                font-size: 21px;
            }

        }


        @media (max-width: 575px) {

            .tieup-details-card .card-body {
                padding: 16px;
            }

            .tieup-main-image {
                width: 130px;
                height: 130px;
            }

            .tieup-name-box {
                padding: 12px;
            }

            .tieup-name-box h2 {
                font-size: 19px;
            }

            .tieup-meta-grid {
                grid-template-columns: 1fr;
            }

            .tieup-contact-card .card-header,
            .hospital-info-card .card-header {
                padding: 16px;
            }

            .tieup-contact-card .card-body,
            .hospital-info-card .card-body {
                padding: 16px;
            }

            .contact-info-item,
            .hospital-info-box {
                padding: 12px;
            }

        }
    </style>

@endsection