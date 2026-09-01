<?php $page = 'customer-details'; ?>

@extends('layout.mainlayout')

@section('content')

   <style>
    /* =========================================
       CUSTOMER PROFILE
    ========================================= */

    .customer-profile-card {
        border-radius: 22px;
    }

    .profile-cover {
        height: 70px;
        background: #6d28d9;
    }

    .profile-avatar-wrapper {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .profile-avatar,
    .default-avatar {
        width: 120px;
        height: 120px;
        border-radius: 20px;
        object-fit: cover;
        border: 5px solid #fff;
        box-shadow: 0 5px 20px rgba(0, 0, 0, .12);
        background: #fff;
    }

    .default-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #6d28d9;
        color: #fff;
        font-size: 40px;
    }


    /* =========================================
       CUSTOMER TABS
    ========================================= */

    .customer-tabs {
        margin: 0;
    }

    .customer-tabs .nav-link {
        border-radius: 12px;
        padding: 8px 14px;
        font-weight: 600;
        color: #6b7280;
        transition: all .3s ease;
        white-space: nowrap;
    }

    .customer-tabs .nav-link:hover {
        background: #f3e8ff;
        color: #6d28d9;
    }

    .customer-tabs .nav-link.active {
        background: #6d28d9;
        color: #fff !important;
        box-shadow: 0 5px 15px rgba(109, 40, 217, .25);
    }


    /* =========================================
       PROFILE INFORMATION
    ========================================= */

    .profile-info-item {
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .profile-info-item:last-child {
        border-bottom: 0;
    }


    /* =========================================
       BUTTONS
    ========================================= */

    .btn-primary {
        background: #6d28d9 !important;
        border-color: #6d28d9 !important;
    }

    .btn-primary:hover,
    .btn-primary:focus,
    .btn-primary:active {
        background: #6d28d9 !important;
        border-color: #6d28d9 !important;
        box-shadow: 0 5px 15px rgba(109, 40, 217, .25);
    }

    .text-primary {
        color: #6d28d9 !important;
    }


    /* =========================================
       MOBILE
    ========================================= */

    @media (max-width: 768px) {

        .profile-cover {
            height: 50px;
        }

        .profile-avatar-wrapper {
            margin-top: -55px;
        }

        .profile-avatar,
        .default-avatar {
            width: 95px;
            height: 95px;
        }

        .default-avatar {
            font-size: 30px;
        }

        .customer-tabs {
            flex-wrap: nowrap;
            overflow-x: auto;
            padding-bottom: 5px;
            scrollbar-width: none;
        }

        .customer-tabs::-webkit-scrollbar {
            display: none;
        }

        .customer-tabs .nav-item {
            flex-shrink: 0;
        }
    }
</style>


    <div class="page-wrapper">

        <div class="content">

            {{-- =====================================================
            HEADER
            ====================================================== --}}

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">

                <div>

                    <a href="{{ route('admin.customers.index') }}" class="btn btn-light border rounded-pill px-3">

                        <i class="ti ti-arrow-left me-1"></i>

                        Back

                    </a>

                </div>

            </div>


            {{-- =====================================================
            CUSTOMER PROFILE CARD
            ====================================================== --}}

            <div class="card border-0 shadow-lg overflow-hidden mb-4 customer-profile-card">

                {{-- Gradient Cover --}}

                <div class="profile-cover"></div>


                <div class="card-body position-relative">

                    <div class="row align-items-center">

                        {{-- =========================================
                        LEFT PROFILE
                        ========================================== --}}

                        <div class="col-lg-7">

                            <div class="d-flex align-items-center flex-wrap gap-3">

                                {{-- Avatar --}}

                                <div class="profile-avatar-wrapper">

                                    @if(
                                            !empty($customer->photo) &&
                                            file_exists(public_path($customer->photo))
                                        )

                                        <img src="{{ asset($customer->photo) }}" alt="{{ $customer->name ?? 'Customer' }}"
                                            class="profile-avatar">

                                    @else

                                        <div class="default-avatar">

                                            <i class="fa fa-user"></i>

                                        </div>

                                    @endif

                                </div>


                                {{-- Customer Information --}}

                                <div>

                                    <h3 class="mb-2 fw-bold text-dark">

                                        {{ $customer->name ?? 'Customer' }}

                                    </h3>


                                    <div class="text-muted">

                                        {{-- Mobile --}}

                                        <div class="mb-1">

                                            <i class="ti ti-phone me-1 text-primary"></i>

                                            {{ $customer->mobile ?? 'N/A' }}

                                        </div>


                                        {{-- Customer Code --}}

                                        <div class="mb-1">

                                            <i class="ti ti-id me-1 text-success"></i>

                                            {{ $customer->customer_code ?? 'N/A' }}

                                        </div>


                                        {{-- Email --}}

                                        <div class="mb-1">

                                            <i class="ti ti-mail me-1 text-danger"></i>

                                            {{ $customer->email ?? 'N/A' }}

                                        </div>


                                        {{-- Gender --}}

                                        <div class="mb-1">

                                            <i class="ti ti-user me-1 text-info"></i>

                                            <strong>Gender:</strong>

                                            {{ $customer->gender ?? 'N/A' }}

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- =========================================
                        RIGHT STATUS
                        ========================================== --}}

                        <div class="col-lg-5 mt-4 mt-lg-0">

                            <div class="d-flex justify-content-lg-end flex-wrap gap-2">

                                {{-- Verification --}}

                                @if($customer->is_verified)

                                    <span class="badge bg-success px-3 py-2">

                                        <i class="ti ti-circle-check me-1"></i>

                                        Verified

                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark px-3 py-2">

                                        <i class="ti ti-alert-circle me-1"></i>

                                        Not Verified

                                    </span>

                                @endif


                                {{-- Status --}}

                                @if($customer->status)

                                    <span class="badge bg-success px-3 py-2">

                                        <i class="ti ti-check me-1"></i>

                                        Active

                                    </span>

                                @else

                                    <span class="badge bg-danger px-3 py-2">

                                        <i class="ti ti-x me-1"></i>

                                        Inactive

                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
            CUSTOMER TABS
            ====================================================== --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body py-2">

                    <ul class="nav nav-pills customer-tabs gap-2">


                        {{-- =================================================
                        PROFILE
                        ================================================== --}}

                        <li class="nav-item">

                            <a href="{{ route('admin.customers.show', [
        'id' => $customer->id,
        'slug' => 'profile'
    ]) }}" class="nav-link {{ request('slug', 'profile') === 'profile' ? 'active' : '' }}">

                                <i class="ti ti-user me-1"></i>

                                Profile

                            </a>

                        </li>


                        {{-- =================================================
                        FAMILY MEMBERS
                        ================================================== --}}

                        <li class="nav-item">

                            <a href="{{ route('admin.customers.show', [
        'id' => $customer->id,
        'slug' => 'family-members'
    ]) }}" class="nav-link {{ request('slug') === 'family-members' ? 'active' : '' }}">

                                <i class="ti ti-users me-1"></i>

                                Family Members

                            </a>

                        </li>


                        {{-- =================================================
                        REPORTS
                        ================================================== --}}

                        <li class="nav-item">

                            <a href="{{ route('admin.customers.show', [
        'id' => $customer->id,
        'slug' => 'reports'
    ]) }}" class="nav-link {{ request('slug') === 'reports' ? 'active' : '' }}">

                                <i class="ti ti-file-report me-1"></i>

                                Reports

                            </a>

                        </li>

                    </ul>

                </div>

            </div>


            {{-- =====================================================
            TAB CONTENT
            ====================================================== --}}

            @yield('customer')


        </div>


        {{-- =========================================================
        FOOTER
        ========================================================== --}}

        <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">

            <p class="mb-0">

                2026 © {{ $site->site_name ?? '' }}. All Rights Reserved

            </p>

            <p class="mb-0">

                Designed & Developed by

                <span class="text-primary">

                    {{ $site->site_name ?? '' }}

                </span>

            </p>

        </div>

    </div>

@endsection