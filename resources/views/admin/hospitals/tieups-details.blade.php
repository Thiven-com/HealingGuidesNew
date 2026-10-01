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

                                    <img src="{{ asset($tieup->image) }}" alt="{{ $tieup->name }}">

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

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            @if ($errors->any())

                <div class="alert alert-danger">

                    <strong>Please fix the following:</strong>

                    <ul class="mb-0 mt-2">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            {{-- =========================================================
            HOSPITAL TIEUPS LIST
            ========================================================= --}}

            {{-- =========================================================
            HOSPITAL TIEUPS LIST
            ========================================================= --}}

            <div class="card border-0 shadow-sm hospital-tieup-list-card">

                {{-- HEADER --}}
                <div class="card-header bg-white border-0">

                    <div class="hospital-tieup-list-header">

                        <div class="hospital-tieup-list-heading">

                            <div class="hospital-tieup-heading-icon">
                                <i class="ti ti-link"></i>
                            </div>

                            <div>
                                <h4>Hospital Tieups List</h4>

                                <p>
                                    Manage tieups associated with this hospital
                                </p>
                            </div>

                        </div>


                        <div class="hospital-tieup-header-actions">

                            <div class="hospital-tieup-count">

                                <div class="count-number">
                                    {{ $allTieups->count() }}
                                </div>

                                <span class="count-label">
                                    {{ $allTieups->count() == 1 ? 'Tieup' : 'Tieups' }}
                                </span>

                            </div>


                            {{-- ADD TIEUP --}}
                            {{-- <button type="button" class="btn add-tieup-btn" data-bs-toggle="modal"
                                data-bs-target="#addTieupModal">

                                <i class="ti ti-plus me-1"></i>
                                Add Tieup

                            </button> --}}

                        </div>

                    </div>

                </div>


                {{-- BODY --}}
               <div class="card-body">

    <form action="{{ route('admin.hospitals.tieups.update', $hospitalTieup->id) }}" method="POST">
        @csrf

        <div class="health-provider-list">

            @forelse($allTieups as $tieupItem)

                <label class="health-provider-item">

                    {{-- SMALL IMAGE --}}
                    <div class="health-provider-logo">

                        @if(!empty($tieupItem->logo))
                            <img
                                src="{{ asset($tieupItem->logo) }}"
                                alt="{{ $tieupItem->name }}"
                            >
                        @else
                            <i class="ti ti-building-hospital"></i>
                        @endif

                    </div>

                    {{-- PROVIDER NAME --}}
                    <div class="health-provider-name">
                        {{ $tieupItem->name }}
                    </div>

                    {{-- CHECKBOX --}}
                    <div class="health-provider-checkbox">

                        <input
                            type="checkbox"
                            name="health_insurance_providers[]"
                            value="{{ $tieupItem->id }}"
                            {{ in_array($tieupItem->id, $selectedTieupIds) ? 'checked' : '' }}
                        >

                    </div>

                </label>

            @empty

                <div class="health-provider-empty">
                    <i class="ti ti-link-off"></i>

                    <h5>No Health Insurance Providers</h5>

                    <p>
                        No health insurance providers have been added yet.
                    </p>
                </div>

            @endforelse

        </div>

        {{-- SAVE BUTTON --}}
        @if($allTieups->count() > 0)

            <div class="d-flex justify-content-end mt-4">

                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-1"></i>
                    Save Tieups
                </button>

            </div>

        @endif

    </form>

</div>

            </div>

        </div>

    </div>

    <style>
        /* =========================================================
                       TIEUP HEADER ACTIONS
                    ========================================================= */

        .hospital-tieup-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .add-tieup-btn {
            height: 38px;
            padding: 0 15px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border-radius: 8px;

            background: #4f7cff;
            border: 1px solid #4f7cff;

            color: #ffffff;

            font-size: 13px;
            font-weight: 600;

            transition: all 0.2s ease;
        }

        .add-tieup-btn:hover {
            background: #3d68df;
            border-color: #3d68df;
            color: #ffffff;
        }


        /* =========================================================
                       TIEUP MODAL
                    ========================================================= */

        .tieup-modal-content {
            border: 0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(20, 40, 80, 0.15);
        }

        .tieup-modal-header {
            padding: 20px 24px;

            border-bottom: 1px solid #edf1f6;

            background: #ffffff;
        }

        .tieup-modal-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .tieup-modal-icon {
            width: 42px;
            height: 42px;

            min-width: 42px;

            border-radius: 11px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #eef4ff;
            color: #4f7cff;
        }

        .tieup-modal-icon i {
            font-size: 21px;
        }

        .tieup-modal-title h5 {
            margin: 0 0 2px;

            color: #26364d;

            font-size: 18px;
            font-weight: 700;
        }

        .tieup-modal-title p {
            margin: 0;

            color: #8b97a8;

            font-size: 12px;
        }


        /* =========================================================
                       MODAL BODY
                    ========================================================= */

        .tieup-modal-content .modal-body {
            padding: 24px;
        }

        .tieup-modal-content .form-label {
            color: #34445c;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 7px;
        }

        .tieup-modal-content .form-control {
            min-height: 42px;

            border: 1px solid #dfe5ee;
            border-radius: 8px;

            color: #27364d;

            font-size: 13px;

            box-shadow: none;
        }

        .tieup-modal-content .form-control:focus {
            border-color: #6d8ff5;
            box-shadow: 0 0 0 3px rgba(79, 124, 255, 0.08);
        }

        .tieup-modal-content textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }


        /* =========================================================
                       CURRENT IMAGE
                    ========================================================= */

        .current-tieup-image {
            display: flex;
            align-items: center;
            gap: 12px;

            margin-bottom: 10px;

            padding: 8px 10px;

            border: 1px solid #e7ecf3;
            border-radius: 9px;

            background: #f8fafc;
        }

        .current-tieup-image img {
            width: 55px;
            height: 55px;

            border-radius: 8px;

            object-fit: contain;

            background: #ffffff;

            border: 1px solid #e4e9f0;
        }

        .current-tieup-image span {
            color: #7d899b;
            font-size: 12px;
        }


        /* =========================================================
                       MODAL FOOTER
                    ========================================================= */

        .tieup-modal-content .modal-footer {
            padding: 15px 24px;

            border-top: 1px solid #edf1f6;
        }

        .tieup-modal-content .btn-light {
            height: 38px;

            padding: 0 16px;

            border: 1px solid #dfe5ee;
            border-radius: 8px;

            color: #627087;

            font-size: 13px;
            font-weight: 600;

            background: #ffffff;
        }

        .tieup-save-btn {
            height: 38px;

            padding: 0 17px;

            border-radius: 8px;

            background: #4f7cff;
            border: 1px solid #4f7cff;

            color: #ffffff;

            font-size: 13px;
            font-weight: 600;
        }

        .tieup-save-btn:hover {
            background: #3d68df;
            border-color: #3d68df;
            color: #ffffff;
        }


        /* =========================================================
                       RESPONSIVE
                    ========================================================= */

        @media (max-width: 767px) {

            .hospital-tieup-header-actions {
                flex-wrap: wrap;
                justify-content: flex-end;
            }

            .hospital-tieup-list-header {
                align-items: flex-start;
            }

            .tieup-modal-content .modal-body {
                padding: 18px;
            }

            .tieup-modal-header {
                padding: 16px 18px;
            }

            .tieup-modal-content .modal-footer {
                padding: 13px 18px;
            }

        }
    </style>
    <style>
        .health-provider-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.health-provider-item {
    display: flex;
    align-items: center;
    width: 100%;
    min-height: 68px;
    padding: 10px 14px;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.health-provider-item:hover {
    border-color: #0d6efd;
    background: #f8fbff;
}

.health-provider-logo {
    width: 44px;
    height: 44px;
    min-width: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    background: #fff;
}

.health-provider-logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 5px;
}

.health-provider-logo i {
    font-size: 22px;
    color: #6c757d;
}

.health-provider-name {
    flex: 1;
    margin-left: 14px;
    font-size: 14px;
    font-weight: 600;
    color: #212529;
}

.health-provider-checkbox {
    margin-left: 15px;
    display: flex;
    align-items: center;
}

.health-provider-checkbox input {
    width: 20px;
    height: 20px;
    margin: 0;
    cursor: pointer;
    accent-color: #6f42c1;
}

.health-provider-item:has(input:checked) {
    border-color: #6f42c1;
    background: #ede3ff;
}

.health-provider-empty {
    text-align: center;
    padding: 50px 20px;
    border: 1px dashed #d9dee3;
    border-radius: 10px;
    color: #6c757d;
}

.health-provider-empty i {
    font-size: 40px;
    display: block;
    margin-bottom: 10px;
}

.health-provider-empty h5 {
    margin-bottom: 5px;
    color: #343a40;
}

.health-provider-empty p {
    margin: 0;
    font-size: 14px;
}

@media (min-width: 768px) {
    .health-provider-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
}

@media (min-width: 1200px) {
    .health-provider-list {
        grid-template-columns: repeat(3, 1fr);
    }
}
    </style>



    <style>
        /* =========================================================
                           HOSPITAL TIEUPS LIST
                        ========================================================= */

        .hospital-tieup-list-card {
            border: 1px solid #e7ecf3 !important;
            border-radius: 18px;
            overflow: hidden;
            background: #ffffff;
            margin-bottom: 24px;
        }


        /* =========================================================
                           HEADER
                        ========================================================= */

        .hospital-tieup-list-card .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #edf1f6 !important;
        }

        .hospital-tieup-list-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .hospital-tieup-list-heading {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .hospital-tieup-heading-icon {
            width: 46px;
            height: 46px;
            min-width: 46px;
            border-radius: 12px;
            background: #ede3ff;
            color: #6f42c1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hospital-tieup-heading-icon i {
            font-size: 22px;
        }

        .hospital-tieup-list-heading h4 {
            margin: 0 0 3px;
            color: #1f3048;
            font-size: 19px;
            font-weight: 700;
        }

        .hospital-tieup-list-heading p {
            margin: 0;
            color: #8a96a8;
            font-size: 13px;
        }


        /* =========================================================
                           COUNT
                        ========================================================= */

        .hospital-tieup-count {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 7px 13px;
            border-radius: 20px;
            background: #f5f7fb;
            border: 1px solid #e8edf4;
            white-space: nowrap;
        }

        .count-number {
            color: #4f7cff;
            font-size: 14px;
            font-weight: 700;
        }

        .count-label {
            color: #718096;
            font-size: 13px;
            font-weight: 500;
        }


        /* =========================================================
                           BODY
                        ========================================================= */

        .hospital-tieup-list-card .card-body {
            padding: 18px 24px 24px;
        }


        /* =========================================================
                           TIEUP ROW
                        ========================================================= */

        .hospital-tieup-row {
            display: flex;
            align-items: center;
            gap: 17px;
            padding: 15px;
            margin-bottom: 12px;

            background: #ffffff;

            border: 1px solid #e7ecf3;
            border-radius: 14px;

            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                transform 0.2s ease;
        }

        .hospital-tieup-row:last-child {
            margin-bottom: 0;
        }

        .hospital-tieup-row:hover {
            border-color: #d5e0f1;
            box-shadow: 0 6px 18px rgba(30, 55, 90, 0.07);
            transform: translateY(-1px);
        }


        /* =========================================================
                           IMAGE
                        ========================================================= */

        .hospital-tieup-image {
            width: 78px;
            height: 78px;
            min-width: 78px;

            border-radius: 12px;

            overflow: hidden;

            background: #f5f7fb;
            border: 1px solid #e5eaf1;

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

        .hospital-tieup-image-placeholder {
            width: 100%;
            height: 100%;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #eef4ff;
            color: #5a86df;
        }

        .hospital-tieup-image-placeholder i {
            font-size: 30px;
        }


        /* =========================================================
                           DETAILS
                        ========================================================= */

        .hospital-tieup-details {
            flex: 1;
            min-width: 0;
        }

        .hospital-tieup-title-row {
            display: flex;
            align-items: center;
            gap: 9px;
            flex-wrap: wrap;
        }

        .hospital-tieup-title-row h5 {
            margin: 0;
            color: #25364f;
            font-size: 16px;
            font-weight: 700;
            line-height: 1.4;
        }

        .hospital-tieup-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;

            padding: 4px 8px;

            border-radius: 20px;

            background: #f1edff;
            color: #7359d8;

            font-size: 11px;
            font-weight: 600;
        }

        .hospital-tieup-badge i {
            font-size: 12px;
        }


        /* DESCRIPTION */

        .hospital-tieup-description {
            color: #78869a;
            font-size: 13px;
            line-height: 1.55;

            margin: 6px 0 7px;

            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }


        /* DATE */

        .hospital-tieup-date {
            display: inline-flex;
            align-items: center;
            gap: 5px;

            color: #98a3b3;

            font-size: 11px;
        }

        .hospital-tieup-date i {
            font-size: 13px;
        }


        /* =========================================================
                           ACTIONS
                        ========================================================= */

        .hospital-tieup-actions {
            display: flex;
            align-items: center;
            gap: 8px;

            margin-left: auto;
        }

        .hospital-tieup-actions form {
            margin: 0;
        }


        /* COMMON BUTTON */

        .tieup-action-btn {
            height: 36px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            gap: 6px;

            padding: 0 12px;

            border-radius: 8px;

            font-size: 12px;
            font-weight: 600;

            text-decoration: none;

            cursor: pointer;

            transition: all 0.2s ease;
        }

        .tieup-action-btn i {
            font-size: 15px;
        }


        /* EDIT */

        .tieup-edit-btn {
            background: #f2edff;
            border: 1px solid #ddd3fa;
            color: #6c4ed7;
        }

        .tieup-edit-btn:hover {
            background: #6c4ed7;
            border-color: #6c4ed7;
            color: #ffffff;
        }


        /* DELETE */

        .tieup-delete-btn {
            background: #fff1f2;
            border: 1px solid #f5d4d8;
            color: #d95360;
        }

        .tieup-delete-btn:hover {
            background: #d95360;
            border-color: #d95360;
            color: #ffffff;
        }


        /* =========================================================
                           EMPTY STATE
                        ========================================================= */

        .tieup-empty-state {
            text-align: center;
            padding: 55px 20px;
        }

        .tieup-empty-icon {
            width: 68px;
            height: 68px;

            margin: 0 auto 14px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 18px;

            background: #eef4ff;
            color: #5b86df;
        }

        .tieup-empty-icon i {
            font-size: 32px;
        }

        .tieup-empty-state h5 {
            margin: 0 0 5px;

            color: #26364d;

            font-size: 17px;
            font-weight: 700;
        }

        .tieup-empty-state p {
            margin: 0;

            color: #8b97a8;

            font-size: 13px;
        }


        /* =========================================================
                           RESPONSIVE
                        ========================================================= */

        @media (max-width: 991px) {

            .hospital-tieup-row {
                align-items: flex-start;
            }

            .hospital-tieup-actions {
                flex-direction: column;
            }

            .tieup-action-btn {
                width: 90px;
            }

        }


        @media (max-width: 767px) {

            .hospital-tieup-list-card .card-header {
                padding: 18px;
            }

            .hospital-tieup-list-card .card-body {
                padding: 15px 18px 18px;
            }

            .hospital-tieup-list-header {
                align-items: flex-start;
            }

            .hospital-tieup-count {
                padding: 6px 10px;
            }

            .hospital-tieup-row {
                flex-wrap: wrap;
                gap: 13px;
                padding: 13px;
            }

            .hospital-tieup-image {
                width: 65px;
                height: 65px;
                min-width: 65px;
            }

            .hospital-tieup-details {
                width: calc(100% - 78px);
                flex: none;
            }

            .hospital-tieup-actions {
                width: 100%;
                flex-direction: row;
                justify-content: flex-end;
                padding-top: 3px;
                border-top: 1px solid #edf1f6;
            }

            .tieup-action-btn {
                width: auto;
                min-width: 90px;
            }

        }


        @media (max-width: 575px) {

            .hospital-tieup-list-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .hospital-tieup-list-heading h4 {
                font-size: 17px;
            }

            .hospital-tieup-list-heading p {
                font-size: 12px;
            }

            .hospital-tieup-row {
                display: grid;
                grid-template-columns: 60px 1fr;
                gap: 12px;
            }

            .hospital-tieup-image {
                width: 60px;
                height: 60px;
                min-width: 60px;
            }

            .hospital-tieup-details {
                width: auto;
            }

            .hospital-tieup-title-row h5 {
                font-size: 14px;
            }

            .hospital-tieup-description {
                font-size: 12px;
            }

            .hospital-tieup-actions {
                grid-column: 1 / -1;
                justify-content: flex-end;
            }

        }
    </style>

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
            color: #6D28D9;
            text-decoration: none;
            font-weight: 500;
        }

        .tieup-breadcrumb a:hover {
            color: #6D28D9;
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
            background: #ede3ff;
            border: 1px solid #aa4fff;
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
            background: #ede3ff;
            color: #aa4fff;
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
            background: #ede3ff;
            border-left: 4px solid #aa4fff;
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
            background: linear-gradient(180deg,
                    #18a77a,
                    #48c59d);
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