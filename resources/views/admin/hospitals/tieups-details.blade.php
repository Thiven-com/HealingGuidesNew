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

                <a href="{{ url()->previous() }}"
                   class="btn tieup-back-btn">

                    <i class="ti ti-arrow-left me-1"></i>
                    Back

                </a>

            </div>

        </div>


        {{-- =========================
            BREADCRUMB
        ========================== --}}
        <div class="tieup-breadcrumb">

            <a href="{{ route('admin.hospitals.index') }}">
                <i class="ti ti-building-hospital"></i>
                Hospital Management
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

                <div class="tieup-details-wrapper">

                    {{-- TIEUP IMAGE --}}
                    <div class="tieup-image-section">

                        <div class="tieup-main-image">

                            @if(!empty($tieup->image))

                                <img
                                    src="{{ asset($tieup->image) }}"
                                    alt="{{ $tieup->name }}"
                                >

                            @else

                                <div class="tieup-image-placeholder">

                                    <i class="ti ti-link"></i>

                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- TIEUP INFORMATION --}}
                    <div class="tieup-info-section">

                        <span class="tieup-badge">

                            <i class="ti ti-link me-1"></i>

                            Hospital Tieup

                        </span>


                        <div class="tieup-name-box">

                            <div class="tieup-name-icon">

                                <i class="ti ti-building-hospital"></i>

                            </div>

                            <div>

                                <h2>
                                    {{ $tieup->name }}
                                </h2>

                                <span>
                                    Partner Organization
                                </span>

                            </div>

                        </div>


                        {{-- DESCRIPTION --}}
                        <div class="tieup-description">

                            <h5>
                                <i class="ti ti-info-circle me-1"></i>
                                Description
                            </h5>

                            @if(!empty($tieup->description))

                                <p>
                                    {{ $tieup->description }}
                                </p>

                            @else

                                <p class="text-muted">
                                    No description available for this tieup.
                                </p>

                            @endif

                        </div>


                        {{-- TIEUP DETAILS --}}
                        <div class="tieup-meta-grid">

                            <div class="tieup-meta-item">

                                <span class="meta-label">
                                    Tieup Name
                                </span>

                                <strong>
                                    {{ $tieup->name }}
                                </strong>

                            </div>


                            {{-- <div class="tieup-meta-item">

                                <span class="meta-label">
                                    Slug
                                </span>

                                <strong>
                                    {{ $tieup->slug }}
                                </strong>

                            </div> --}}


                            {{-- <div class="tieup-meta-item">

                                <span class="meta-label">
                                    Hospitals
                                </span>

                                <strong>
                                    {{ $hospitalTieups->count() }}
                                </strong>

                            </div> --}}


                            {{-- <div class="tieup-meta-item">

                                <span class="meta-label">
                                    Created Date
                                </span>

                                <strong>
                                    {{ $tieup->created_at ? $tieup->created_at->format('d M Y') : '-' }}
                                </strong>

                            </div> --}}

                        </div>

                    </div>

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
    ========================== */

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
    ========================== */

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
    ========================== */

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
    ========================== */

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
    ========================== */

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
    ========================== */

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
    ========================== */

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
    ========================== */

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
    ========================== */

    .tieup-meta-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
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


    /* =========================
       HOSPITAL LIST CARD
    ========================== */

    .hospital-tieup-list-card {
        border: 1px solid #e8edf5 !important;
        border-radius: 18px;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .hospital-tieup-list-card .card-header {
        padding: 22px 24px;
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
        background: linear-gradient(
            180deg,
            #18a77a,
            #48c59d
        );
    }

    .hospital-list-title h4 {
        font-size: 22px;
        font-weight: 700;
        color: #202b3c;
        margin-bottom: 3px;
    }

    .hospital-list-title p {
        color: #8a93a3 !important;
        font-size: 14px;
        margin-bottom: 0;
    }

    .hospital-tieup-list-card .card-body {
        padding: 0 24px 24px;
    }


    /* =========================
       HOSPITAL ITEM
    ========================== */

    .hospital-tieup-item {
        display: flex;
        align-items: center;
        gap: 18px;
        padding: 16px;
        border: 1px solid #e9eef5;
        border-radius: 14px;
        margin-bottom: 12px;
        background: #ffffff;
        transition: all 0.25s ease;
    }

    .hospital-tieup-item:last-child {
        margin-bottom: 0;
    }

    .hospital-tieup-item:hover {
        transform: translateY(-2px);
        border-color: #dbe7f7;
        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.06);
    }


    /* =========================
       HOSPITAL IMAGE
    ========================== */

    .hospital-tieup-image {
        width: 72px;
        height: 72px;
        min-width: 72px;
        border-radius: 12px;
        overflow: hidden;
        background: #f1f5fb;
        border: 1px solid #e8edf5;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hospital-tieup-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
    }

    .hospital-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #edf5ff;
    }

    .hospital-image-placeholder i {
        font-size: 30px;
        color: #5891ed;
    }


    /* =========================
       HOSPITAL INFO
    ========================== */

    .hospital-tieup-info {
        flex: 1;
        min-width: 0;
    }

    .hospital-tieup-info h5 {
        color: #26364f;
        font-size: 17px;
        font-weight: 700;
        margin-bottom: 7px;
    }

    .hospital-tieup-info p {
        color: #7a879b;
        font-size: 13px;
        line-height: 1.5;
        margin: 7px 0 0;
    }


    /* =========================
       HOSPITAL TYPE
    ========================== */

    .hospital-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #f0edff;
        color: #7258d8;
        border-radius: 20px;
        padding: 5px 10px;
        font-size: 12px;
        font-weight: 600;
    }

    .hospital-type-badge i {
        font-size: 14px;
    }


    /* =========================
       STATUS
    ========================== */

    .hospital-tieup-status {
        min-width: 90px;
        display: flex;
        justify-content: flex-end;
    }

    .hospital-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border-radius: 20px;
        padding: 6px 11px;
        font-size: 12px;
        font-weight: 600;
    }

    .hospital-status.active {
        background: #e9faf4;
        color: #159a72;
    }

    .hospital-status.inactive {
        background: #fff0f1;
        color: #db5965;
    }

    .hospital-status i {
        font-size: 14px;
    }


    /* =========================
       EMPTY STATE
    ========================== */

    .tieup-empty-state {
        text-align: center;
        padding: 50px 20px;
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
    ========================== */

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

        .tieup-meta-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

    }


    @media (max-width: 767px) {

        .tieup-details-card .card-body {
            padding: 20px;
        }

        .hospital-tieup-list-card .card-header {
            padding: 18px 20px;
        }

        .hospital-tieup-list-card .card-body {
            padding: 0 20px 20px;
        }

        .hospital-tieup-item {
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .hospital-tieup-info {
            width: calc(100% - 90px);
        }

        .hospital-tieup-status {
            width: 100%;
            justify-content: flex-start;
            padding-left: 90px;
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
            font-size: 20px;
        }

        .tieup-meta-grid {
            grid-template-columns: 1fr;
        }

        .hospital-tieup-list-card .card-header {
            padding: 16px;
        }

        .hospital-tieup-list-card .card-body {
            padding: 0 16px 16px;
        }

        .hospital-tieup-item {
            gap: 12px;
            padding: 12px;
        }

        .hospital-tieup-image {
            width: 60px;
            height: 60px;
            min-width: 60px;
        }

        .hospital-tieup-info {
            width: calc(100% - 72px);
        }

        .hospital-tieup-info h5 {
            font-size: 15px;
        }

        .hospital-tieup-status {
            padding-left: 72px;
        }

        .hospital-list-title h4 {
            font-size: 19px;
        }

    }

</style>

@endsection