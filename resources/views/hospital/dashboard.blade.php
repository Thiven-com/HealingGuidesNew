<?php $page = 'hospital-dashboard'; ?>

@extends('layout.mainlayout')

@section('content')

<style>
    :root {
        --primary: #2563EB;
        --success: #10B981;
        --warning: #F59E0B;
        --danger: #EF4444;
        --info: #06B6D4;
        --purple: #7C3AED;
        --dark: #111827;
        --text: #6B7280;
        --border: #E5E7EB;
        --bg: #F5F7FB;
        --white: #ffffff;
    }

    .page-wrapper {
        background: var(--bg);
        min-height: 100vh;
    }

    .content {
        padding: 25px;
    }

    /* =====================================================
       HEADER
    ===================================================== */

    .dashboard-header {
        background: linear-gradient(135deg, #2563EB, #3B82F6);
        border-radius: 22px;
        padding: 35px;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 15px 35px rgba(37, 99, 235, .18);
    }

    .dashboard-header:before {
        content: "";
        position: absolute;
        width: 250px;
        height: 250px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .08);
        right: -80px;
        top: -80px;
    }

    .dashboard-header:after {
        content: "";
        position: absolute;
        width: 170px;
        height: 170px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .08);
        left: -40px;
        bottom: -50px;
    }

    .dashboard-header-content {
        position: relative;
        z-index: 2;
    }

    .dashboard-header h2 {
        font-size: 34px;
        font-weight: 700;
        margin-bottom: 8px;
        color: #fff;
    }

    .dashboard-header p {
        opacity: .9;
        margin: 0;
        color: #fff;
    }

    .dashboard-date {
        background: rgba(255, 255, 255, .15);
        border: 1px solid rgba(255, 255, 255, .15);
        padding: 10px 16px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    /* =====================================================
       STAT CARDS
    ===================================================== */

    .dashboard-card {
        background: #fff;
        border-radius: 18px;
        padding: 24px;
        transition: .35s;
        box-shadow: 0 8px 25px rgba(15, 23, 42, .05);
        border: 1px solid #eef1f5;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 18px 40px rgba(15, 23, 42, .10);
    }

    .dashboard-icon {
        width: 62px;
        height: 62px;
        border-radius: 17px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fff;
        font-size: 27px;
        flex-shrink: 0;
    }

    .bg-blue {
        background: linear-gradient(135deg, #2563EB, #60A5FA);
    }

    .bg-green {
        background: linear-gradient(135deg, #10B981, #34D399);
    }

    .bg-orange {
        background: linear-gradient(135deg, #F59E0B, #FBBF24);
    }

    .bg-red {
        background: linear-gradient(135deg, #EF4444, #F87171);
    }

    .bg-purple {
        background: linear-gradient(135deg, #7C3AED, #A78BFA);
    }

    .bg-cyan {
        background: linear-gradient(135deg, #06B6D4, #67E8F9);
    }

    .bg-pink {
        background: linear-gradient(135deg, #EC4899, #F472B6);
    }

    .bg-dark-custom {
        background: linear-gradient(135deg, #374151, #6B7280);
    }

    .card-title-custom {
        color: #6B7280;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .card-count {
        font-size: 31px;
        font-weight: 700;
        color: #111827;
        line-height: 1.1;
    }

    .card-growth {
        display: inline-flex;
        align-items: center;
        margin-top: 12px;
        background: #ECFDF5;
        color: #059669;
        font-size: 11px;
        font-weight: 600;
        padding: 6px 10px;
        border-radius: 30px;
    }

    .card-growth.warning {
        background: #FEF3C7;
        color: #B45309;
    }

    .card-growth.danger {
        background: #FEE2E2;
        color: #DC2626;
    }

    .card-growth.info {
        background: #EFF6FF;
        color: #2563EB;
    }

    /* =====================================================
       SECTION CARD
    ===================================================== */

    .dashboard-section-card {
        background: #fff;
        border: 1px solid #eef1f5;
        border-radius: 18px;
        box-shadow: 0 8px 25px rgba(15, 23, 42, .04);
        height: 100%;
        overflow: hidden;
    }

    .dashboard-section-card .card-header {
        padding: 20px 22px;
        background: #fff;
        border-bottom: 1px solid #f0f2f5;
    }

    .dashboard-section-card .card-header h5 {
        font-size: 17px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .dashboard-section-card .card-body {
        padding: 22px;
    }

    /* =====================================================
       QUICK ACTION
    ===================================================== */

    .quick-action {
        display: block;
        padding: 18px 10px;
        text-align: center;
        border-radius: 14px;
        border: 1px solid #e8edf5;
        background: #fff;
        color: #374151;
        font-size: 13px;
        font-weight: 600;
        transition: .3s;
        height: 100%;
    }

    .quick-action:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(15, 23, 42, .08);
        color: var(--primary);
        border-color: #dbe7ff;
    }

    .quick-action i {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        margin: 0 auto 10px;
        font-size: 21px;
    }

    .qa-blue i {
        background: #EFF6FF;
        color: #2563EB;
    }

    .qa-green i {
        background: #ECFDF5;
        color: #059669;
    }

    .qa-orange i {
        background: #FFF7ED;
        color: #EA580C;
    }

    .qa-purple i {
        background: #F5F3FF;
        color: #7C3AED;
    }

    .qa-red i {
        background: #FEF2F2;
        color: #DC2626;
    }

    .qa-cyan i {
        background: #ECFEFF;
        color: #0891B2;
    }

    /* =====================================================
       SUMMARY
    ===================================================== */

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 13px 0;
        border-bottom: 1px dashed #E5E7EB;
        font-size: 14px;
    }

    .summary-row:last-child {
        border-bottom: none;
    }

    .summary-row span {
        color: #6B7280;
    }

    .summary-row strong {
        color: #111827;
    }

    /* =====================================================
       STATUS BOX
    ===================================================== */

    .status-box {
        text-align: center;
        padding: 20px 10px;
        border-radius: 15px;
    }

    .status-box h2 {
        font-size: 28px;
        margin-bottom: 4px;
        font-weight: 700;
    }

    .status-box small {
        color: #6B7280;
    }

    .status-success {
        background: #ECFDF5;
    }

    .status-warning {
        background: #FFFBEB;
    }

    .status-danger {
        background: #FEF2F2;
    }

    /* =====================================================
       TABLE
    ===================================================== */

    .dashboard-table th {
        background: #F8FAFC;
        color: #64748B;
        font-size: 12px;
        font-weight: 600;
        padding: 14px 16px;
        white-space: nowrap;
        border-bottom: 1px solid #E5E7EB;
    }

    .dashboard-table td {
        padding: 14px 16px;
        font-size: 13px;
        vertical-align: middle;
        color: #374151;
    }

    .dashboard-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* =====================================================
       EMPTY
    ===================================================== */

    .empty-state {
        padding: 45px 20px;
        text-align: center;
    }

    .empty-state i {
        font-size: 46px;
        color: #CBD5E1;
        display: block;
        margin-bottom: 15px;
    }

    .empty-state h6 {
        font-weight: 700;
        margin-bottom: 6px;
    }

    .empty-state p {
        color: #94A3B8;
        font-size: 13px;
        margin: 0;
    }

    @media(max-width: 768px) {

        .dashboard-header {
            padding: 25px;
        }

        .dashboard-header h2 {
            font-size: 27px;
        }

        .content {
            padding: 18px;
        }

        .dashboard-date {
            margin-top: 18px;
        }

        .card-count {
            font-size: 27px;
        }
    }
</style>


<div class="page-wrapper">

    <div class="content">


        {{-- =====================================================
            WELCOME HEADER
        ====================================================== --}}

        <div class="dashboard-header">

            <div class="dashboard-header-content">

                <div class="row align-items-center">

                    <div class="col-lg-8">

                        <h2>
                            Welcome Back 👋
                        </h2>

                        <p>
                            {{ $hospital->hospital_name ?? $hospital->name ?? 'Hospital' }}
                            — here's what's happening at your hospital today.
                        </p>

                    </div>


                    <div class="col-lg-4 text-lg-end">

                        <div class="dashboard-date">

                            <i class="ti ti-calendar"></i>

                            {{ now()->format('d M Y') }}

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- =====================================================
            MAIN STATISTICS
        ====================================================== --}}

        <div class="row g-4">


            {{-- DOCTORS --}}

            <div class="col-xl-3 col-lg-4 col-md-6">

                <div class="dashboard-card">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="card-title-custom">
                                Total Doctors
                            </div>

                            <div class="card-count">
                                {{ number_format($totalDoctors ?? 0) }}
                            </div>

                            <span class="card-growth">

                                <i class="ti ti-user-check me-1"></i>

                                {{ number_format($activeDoctors ?? 0) }}
                                Active

                            </span>

                        </div>


                        <div class="dashboard-icon bg-green">

                            <i class="ti ti-stethoscope"></i>

                        </div>

                    </div>

                </div>

            </div>



            {{-- APPOINTMENTS --}}

            <div class="col-xl-3 col-lg-4 col-md-6">

                <div class="dashboard-card">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="card-title-custom">
                                Appointments
                            </div>

                            <div class="card-count">
                                {{ number_format($totalAppointments ?? 0) }}
                            </div>

                            <span class="card-growth info">

                                <i class="ti ti-calendar-event me-1"></i>

                                {{ number_format($todayAppointments ?? 0) }}
                                Today

                            </span>

                        </div>


                        <div class="dashboard-icon bg-blue">

                            <i class="ti ti-calendar-check"></i>

                        </div>

                    </div>

                </div>

            </div>



            {{-- AMBULANCES --}}

            <div class="col-xl-3 col-lg-4 col-md-6">

                <div class="dashboard-card">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="card-title-custom">
                                Ambulances
                            </div>

                            <div class="card-count">
                                {{ number_format($totalAmbulances ?? 0) }}
                            </div>

                            <span class="card-growth">

                                <i class="ti ti-circle-check me-1"></i>

                                {{ number_format($availableAmbulances ?? 0) }}
                                Available

                            </span>

                        </div>


                        <div class="dashboard-icon bg-red">

                            <i class="ti ti-ambulance"></i>

                        </div>

                    </div>

                </div>

            </div>



            {{-- MEDICINES --}}

            <div class="col-xl-3 col-lg-4 col-md-6">

                <div class="dashboard-card">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="card-title-custom">
                                Medicines
                            </div>

                            <div class="card-count">
                                {{ number_format($totalMedicines ?? 0) }}
                            </div>

                            <span class="card-growth danger">

                                <i class="ti ti-alert-triangle me-1"></i>

                                {{ number_format($lowStockMedicines ?? 0) }}
                                Low Stock

                            </span>

                        </div>


                        <div class="dashboard-icon bg-orange">

                            <i class="ti ti-pill"></i>

                        </div>

                    </div>

                </div>

            </div>



            {{-- AMBULANCE REQUESTS --}}

            <div class="col-xl-3 col-lg-4 col-md-6">

                <div class="dashboard-card">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="card-title-custom">
                                Ambulance Requests
                            </div>

                            <div class="card-count">
                                {{ number_format($totalAmbulanceBookings ?? 0) }}
                            </div>

                            <span class="card-growth warning">

                                <i class="ti ti-clock me-1"></i>

                                {{ number_format($pendingAmbulanceBookings ?? 0) }}
                                Pending

                            </span>

                        </div>


                        <div class="dashboard-icon bg-cyan">

                            <i class="ti ti-map-pin"></i>

                        </div>

                    </div>

                </div>

            </div>



            {{-- ACTIVE TRIPS --}}

            <div class="col-xl-3 col-lg-4 col-md-6">

                <div class="dashboard-card">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="card-title-custom">
                                Active Trips
                            </div>

                            <div class="card-count">
                                {{ number_format($activeAmbulanceTrips ?? 0) }}
                            </div>

                            <span class="card-growth info">

                                <i class="ti ti-route me-1"></i>

                                Ongoing Trips

                            </span>

                        </div>


                        <div class="dashboard-icon bg-purple">

                            <i class="ti ti-route"></i>

                        </div>

                    </div>

                </div>

            </div>



            {{-- MEDICINE ORDERS --}}

            <div class="col-xl-3 col-lg-4 col-md-6">

                <div class="dashboard-card">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="card-title-custom">
                                Medicine Orders
                            </div>

                            <div class="card-count">
                                {{ number_format($totalMedicineOrders ?? 0) }}
                            </div>

                            <span class="card-growth warning">

                                <i class="ti ti-clock me-1"></i>

                                {{ number_format($pendingMedicineOrders ?? 0) }}
                                Pending

                            </span>

                        </div>


                        <div class="dashboard-icon bg-pink">

                            <i class="ti ti-shopping-cart"></i>

                        </div>

                    </div>

                </div>

            </div>



            {{-- DIAGNOSTICS --}}

            <div class="col-xl-3 col-lg-4 col-md-6">

                <div class="dashboard-card">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="card-title-custom">
                                Diagnostics
                            </div>

                            <div class="card-count">
                                {{ number_format($totalDiagnostics ?? 0) }}
                            </div>

                            <span class="card-growth info">

                                <i class="ti ti-test-pipe me-1"></i>

                                {{ number_format($totalDiagnosticBookings ?? 0) }}
                                Bookings

                            </span>

                        </div>


                        <div class="dashboard-icon bg-dark-custom">

                            <i class="ti ti-microscope"></i>

                        </div>

                    </div>

                </div>

            </div>


        </div>



        {{-- =====================================================
            QUICK ACTIONS + TODAY SUMMARY
        ====================================================== --}}

        <div class="row g-4 mt-1">


            {{-- QUICK ACTIONS --}}

            <div class="col-xl-8">

                <div class="dashboard-section-card">

                    <div class="card-header">

                        <h5>

                            <i class="ti ti-bolt me-2 text-warning"></i>

                            Quick Actions

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">


                            <div class="col-lg-4 col-md-4 col-6">

                                <a href="{{ route('hospital.doctors.create') }}"
                                   class="quick-action qa-green">

                                    <i class="ti ti-user-plus"></i>

                                    Add Doctor

                                </a>

                            </div>


                            <div class="col-lg-4 col-md-4 col-6">

                                <a href="{{ route('hospital.appointments.index') }}"
                                   class="quick-action qa-blue">

                                    <i class="ti ti-calendar-event"></i>

                                    Appointments

                                </a>

                            </div>


                            <div class="col-lg-4 col-md-4 col-6">

                                <a href="{{ route('hospital.ambulances.create') }}"
                                   class="quick-action qa-red">

                                    <i class="ti ti-ambulance"></i>

                                    Add Ambulance

                                </a>

                            </div>


                            <div class="col-lg-4 col-md-4 col-6">

                                <a href="{{ route('hospital.ambulance-bookings.index') }}"
                                   class="quick-action qa-cyan">

                                    <i class="ti ti-map-pin"></i>

                                    Ambulance Requests

                                </a>

                            </div>


                            <div class="col-lg-4 col-md-4 col-6">

                                <a href="{{ route('hospital.medicines.create') }}"
                                   class="quick-action qa-orange">

                                    <i class="ti ti-pill"></i>

                                    Add Medicine

                                </a>

                            </div>


                            <div class="col-lg-4 col-md-4 col-6">

                                <a href="{{ route('hospital.medicine-orders.index') }}"
                                   class="quick-action qa-purple">

                                    <i class="ti ti-shopping-cart"></i>

                                    Medicine Orders

                                </a>

                            </div>


                        </div>

                    </div>

                </div>

            </div>



            {{-- TODAY SUMMARY --}}

            <div class="col-xl-4">

                <div class="dashboard-section-card">

                    <div class="card-header">

                        <h5>

                            <i class="ti ti-calendar-stats me-2 text-primary"></i>

                            Today's Summary

                        </h5>

                    </div>


                    <div class="card-body">


                        <div class="summary-row">

                            <span>
                                Appointments
                            </span>

                            <strong>
                                {{ number_format($todayAppointments ?? 0) }}
                            </strong>

                        </div>


                        <div class="summary-row">

                            <span>
                                Total Doctors
                            </span>

                            <strong>
                                {{ number_format($totalDoctors ?? 0) }}
                            </strong>

                        </div>


                        <div class="summary-row">

                            <span>
                                Active Doctors
                            </span>

                            <strong class="text-success">
                                {{ number_format($activeDoctors ?? 0) }}
                            </strong>

                        </div>


                        <div class="summary-row">

                            <span>
                                Ambulance Requests
                            </span>

                            <strong class="text-warning">
                                {{ number_format($pendingAmbulanceBookings ?? 0) }}
                            </strong>

                        </div>


                        <div class="summary-row">

                            <span>
                                Active Trips
                            </span>

                            <strong class="text-primary">
                                {{ number_format($activeAmbulanceTrips ?? 0) }}
                            </strong>

                        </div>


                        <div class="summary-row">

                            <span>
                                Pending Medicine Orders
                            </span>

                            <strong class="text-danger">
                                {{ number_format($pendingMedicineOrders ?? 0) }}
                            </strong>

                        </div>


                    </div>

                </div>

            </div>


        </div>



        {{-- =====================================================
            AMBULANCE + PHARMACY + DIAGNOSTICS
        ====================================================== --}}

        <div class="row g-4 mt-1">


            {{-- AMBULANCE STATUS --}}

            <div class="col-xl-4">

                <div class="dashboard-section-card">

                    <div class="card-header d-flex align-items-center justify-content-between">

                        <h5>

                            <i class="ti ti-ambulance me-2 text-danger"></i>

                            Ambulance Status

                        </h5>


                        <span class="badge bg-danger-subtle text-danger">

                            {{ number_format($totalAmbulances ?? 0) }}
                            Total

                        </span>

                    </div>


                    <div class="card-body">


                        <div class="row g-3">


                            <div class="col-6">

                                <div class="status-box status-success">

                                    <h2 class="text-success">

                                        {{ number_format($availableAmbulances ?? 0) }}

                                    </h2>

                                    <small>
                                        Available
                                    </small>

                                </div>

                            </div>


                            <div class="col-6">

                                <div class="status-box status-warning">

                                    <h2 class="text-warning">

                                        {{ number_format($activeAmbulanceTrips ?? 0) }}

                                    </h2>

                                    <small>
                                        Active Trips
                                    </small>

                                </div>

                            </div>


                        </div>


                        <div class="summary-row mt-3">

                            <span>
                                Total Requests
                            </span>

                            <strong>

                                {{ number_format($totalAmbulanceBookings ?? 0) }}

                            </strong>

                        </div>


                        <div class="summary-row">

                            <span>
                                Pending Requests
                            </span>

                            <strong class="text-warning">

                                {{ number_format($pendingAmbulanceBookings ?? 0) }}

                            </strong>

                        </div>


                    </div>

                </div>

            </div>



            {{-- PHARMACY --}}

            <div class="col-xl-4">

                <div class="dashboard-section-card">

                    <div class="card-header">

                        <h5>

                            <i class="ti ti-pill me-2 text-success"></i>

                            Pharmacy

                        </h5>

                    </div>


                    <div class="card-body">


                        <div class="d-flex align-items-center mb-4">

                            <div class="dashboard-icon bg-green me-3">

                                <i class="ti ti-pill"></i>

                            </div>


                            <div>

                                <h3 class="mb-1 fw-bold">

                                    {{ number_format($totalMedicines ?? 0) }}

                                </h3>

                                <small class="text-muted">

                                    Total Medicines

                                </small>

                            </div>

                        </div>


                        <div class="summary-row">

                            <span>
                                Low Stock
                            </span>

                            <strong class="text-danger">

                                {{ number_format($lowStockMedicines ?? 0) }}

                            </strong>

                        </div>


                        <div class="summary-row">

                            <span>
                                Medicine Orders
                            </span>

                            <strong>

                                {{ number_format($totalMedicineOrders ?? 0) }}

                            </strong>

                        </div>


                        <div class="summary-row">

                            <span>
                                Pending Orders
                            </span>

                            <strong class="text-warning">

                                {{ number_format($pendingMedicineOrders ?? 0) }}

                            </strong>

                        </div>


                    </div>

                </div>

            </div>



            {{-- DIAGNOSTICS --}}

            <div class="col-xl-4">

                <div class="dashboard-section-card">

                    <div class="card-header">

                        <h5>

                            <i class="ti ti-microscope me-2 text-primary"></i>

                            Diagnostics

                        </h5>

                    </div>


                    <div class="card-body">


                        <div class="d-flex align-items-center mb-4">

                            <div class="dashboard-icon bg-blue me-3">

                                <i class="ti ti-microscope"></i>

                            </div>


                            <div>

                                <h3 class="mb-1 fw-bold">

                                    {{ number_format($totalDiagnostics ?? 0) }}

                                </h3>

                                <small class="text-muted">

                                    Total Diagnostics

                                </small>

                            </div>

                        </div>


                        <div class="summary-row">

                            <span>
                                Diagnostics
                            </span>

                            <strong>

                                {{ number_format($totalDiagnostics ?? 0) }}

                            </strong>

                        </div>


                        <div class="summary-row">

                            <span>
                                Diagnostic Bookings
                            </span>

                            <strong class="text-primary">

                                {{ number_format($totalDiagnosticBookings ?? 0) }}

                            </strong>

                        </div>


                    </div>

                </div>

            </div>


        </div>



        {{-- =====================================================
            RECENT APPOINTMENTS
        ====================================================== --}}

        <div class="row g-4 mt-1">


            <div class="col-xl-6">

                <div class="dashboard-section-card">

                    <div class="card-header d-flex align-items-center justify-content-between">

                        <h5>

                            <i class="ti ti-calendar-time me-2 text-primary"></i>

                            Recent Appointments

                        </h5>


                        <a href="{{ route('hospital.appointments.index') }}"
                           class="btn btn-sm btn-primary">

                            View All

                        </a>

                    </div>


                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table dashboard-table table-hover mb-0">

                                <thead>

                                    <tr>

                                        <th>#</th>

                                        <th>Appointment</th>

                                        <th>Status</th>

                                        <th>Date</th>

                                    </tr>

                                </thead>


                                <tbody>


                                @forelse($recentAppointments ?? [] as $appointment)

                                    @php

                                        $appointmentStatus =
                                            strtolower(
                                                $appointment->status
                                                ?? 'pending'
                                            );

                                        $appointmentStatusClass =
                                            match($appointmentStatus) {

                                                'completed' => 'success',

                                                'confirmed' => 'primary',

                                                'accepted' => 'primary',

                                                'cancelled' => 'danger',

                                                'rejected' => 'danger',

                                                default => 'warning'
                                            };

                                    @endphp


                                    <tr>

                                        <td>

                                            {{ $loop->iteration }}

                                        </td>


                                        <td>

                                            <strong>

                                                #{{ $appointment->id }}

                                            </strong>

                                        </td>


                                        <td>

                                            <span class="badge bg-{{ $appointmentStatusClass }}">

                                                {{ ucfirst(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $appointmentStatus
                                                    )
                                                ) }}

                                            </span>

                                        </td>


                                        <td>

                                            {{ optional(
                                                $appointment->created_at
                                            )->format('d M Y') }}

                                            <small class="d-block text-muted">

                                                {{ optional(
                                                    $appointment->created_at
                                                )->format('h:i A') }}

                                            </small>

                                        </td>

                                    </tr>


                                @empty


                                    <tr>

                                        <td colspan="4">

                                            <div class="empty-state">

                                                <i class="ti ti-calendar-off"></i>

                                                <h6>
                                                    No Appointments Found
                                                </h6>

                                                <p>
                                                    Recent appointments will appear here.
                                                </p>

                                            </div>

                                        </td>

                                    </tr>


                                @endforelse


                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>



            {{-- =====================================================
                RECENT AMBULANCE REQUESTS
            ====================================================== --}}

            <div class="col-xl-6">

                <div class="dashboard-section-card">

                    <div class="card-header d-flex align-items-center justify-content-between">

                        <h5>

                            <i class="ti ti-ambulance me-2 text-danger"></i>

                            Recent Ambulance Requests

                        </h5>


                        <a href="{{ route('hospital.ambulance-bookings.index') }}"
                           class="btn btn-sm btn-danger">

                            View All

                        </a>

                    </div>


                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table dashboard-table table-hover mb-0">

                                <thead>

                                    <tr>

                                        <th>Booking</th>

                                        <th>Status</th>

                                        <th>Amount</th>

                                        <th>Date</th>

                                    </tr>

                                </thead>


                                <tbody>


                                @forelse($recentAmbulanceBookings ?? [] as $booking)

                                    @php

                                        $bookingStatus =
                                            strtolower(
                                                $booking->booking_status
                                                ?? 'pending'
                                            );

                                        $bookingStatusClass =
                                            match($bookingStatus) {

                                                'completed' => 'success',

                                                'accepted' => 'primary',

                                                'assigned' => 'info',

                                                'ongoing' => 'warning',

                                                'cancelled' => 'danger',

                                                'rejected' => 'danger',

                                                default => 'secondary'
                                            };

                                    @endphp


                                    <tr>


                                        <td>

                                            <strong>

                                                {{ $booking->booking_no
                                                    ?? '#' . $booking->id }}

                                            </strong>

                                        </td>


                                        <td>

                                            <span class="badge bg-{{ $bookingStatusClass }}">

                                                {{ ucfirst(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $bookingStatus
                                                    )
                                                ) }}

                                            </span>

                                        </td>


                                        <td>

                                            <strong>

                                                ₹{{ number_format(
                                                    (float) (
                                                        $booking->total_amount
                                                        ?? 0
                                                    ),
                                                    2
                                                ) }}

                                            </strong>

                                        </td>


                                        <td>

                                            {{ optional(
                                                $booking->created_at
                                            )->format('d M Y') }}

                                            <small class="d-block text-muted">

                                                {{ optional(
                                                    $booking->created_at
                                                )->format('h:i A') }}

                                            </small>

                                        </td>


                                    </tr>


                                @empty


                                    <tr>

                                        <td colspan="4">

                                            <div class="empty-state">

                                                <i class="ti ti-ambulance"></i>

                                                <h6>
                                                    No Ambulance Requests
                                                </h6>

                                                <p>
                                                    Recent ambulance requests will appear here.
                                                </p>

                                            </div>

                                        </td>

                                    </tr>


                                @endforelse


                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>


        </div>


    </div>

</div>

@endsection