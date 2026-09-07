<?php $page = 'dashboard'; ?>

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

        /* =========================================
           DASHBOARD HEADER
        ========================================= */

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
        }

        .dashboard-header p {
            opacity: .9;
            margin: 0;
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

        /* =========================================
           STAT CARDS
        ========================================= */

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

        /* =========================================
           COMMON CARD
        ========================================= */

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

        /* =========================================
           QUICK ACTIONS
        ========================================= */

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

        .quick-action:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(15, 23, 42, .08);
            color: var(--primary);
            border-color: #dbe7ff;
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

        /* =========================================
           SUMMARY
        ========================================= */

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

        /* =========================================
           SMALL INFO CARDS
        ========================================= */

        .mini-stat {
            text-align: center;
            padding: 25px 15px;
        }

        .mini-stat-icon {
            width: 58px;
            height: 58px;
            margin: 0 auto 15px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
        }

        .mini-stat h6 {
            color: #6B7280;
            font-size: 13px;
            margin-bottom: 7px;
        }

        .mini-stat h2 {
            font-size: 29px;
            color: #111827;
            margin: 0;
        }

        /* =========================================
           TABLE
        ========================================= */

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

        .patient-avatar {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #EFF6FF;
            color: #2563EB;
            font-weight: 700;
            flex-shrink: 0;
        }

        /* =========================================
           STATUS BOXES
        ========================================= */

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

        /* =========================================
           EMPTY
        ========================================= */

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

            {{-- =========================================================
            HEADER
            ========================================================== --}}

            <div class="dashboard-header">

                <div class="dashboard-header-content">

                    <div class="row align-items-center">

                        <div class="col-lg-8">

                            <h2>Welcome Back 👋</h2>

                            <p>
                                Here's what's happening across your Hospital
                                Management System today.
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


            {{-- =========================================================
            MAIN STATISTICS
            ========================================================== --}}

            <div class="row g-4">

                {{-- PATIENTS --}}

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <div class="dashboard-card">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="card-title-custom">
                                    Total Patients
                                </div>

                                <div class="card-count">
                                    {{ number_format($data['patients'] ?? 0) }}
                                </div>

                                @php
                                    $patientGrowth = $data['patient_growth'] ?? 0;
                                @endphp

                                <span class="card-growth {{ $patientGrowth < 0 ? 'danger' : '' }}">

                                    @if($patientGrowth > 0)

                                        <i class="ti ti-trending-up me-1"></i>
                                        +{{ $patientGrowth }}%

                                    @elseif($patientGrowth < 0)

                                        <i class="ti ti-trending-down me-1"></i>
                                        {{ $patientGrowth }}%

                                    @else

                                        0%

                                    @endif

                                    &nbsp;This Month

                                </span>

                            </div>

                            <div class="dashboard-icon bg-blue">
                                <i class="ti ti-users"></i>
                            </div>

                        </div>

                    </div>

                </div>


                {{-- DOCTORS --}}

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <div class="dashboard-card">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="card-title-custom">
                                    Total Doctors
                                </div>

                                <div class="card-count">
                                    {{ number_format($data['doctors'] ?? 0) }}
                                </div>

                                <span class="card-growth">
                                    <i class="ti ti-user-check me-1"></i>
                                    Registered
                                </span>

                            </div>

                            <div class="dashboard-icon bg-green">
                                <i class="ti ti-user-heart"></i>
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
                                    Today's Appointments
                                </div>

                                <div class="card-count">
                                    {{ number_format($data['today_appointments'] ?? $data['appointments'] ?? 0) }}
                                </div>

                                <span class="card-growth warning">
                                    <i class="ti ti-calendar-event me-1"></i>
                                    Today
                                </span>

                            </div>

                            <div class="dashboard-icon bg-orange">
                                <i class="ti ti-calendar-event"></i>
                            </div>

                        </div>

                    </div>

                </div>


                {{-- STAFF --}}

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <div class="dashboard-card">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="card-title-custom">
                                    Admin / Staff
                                </div>

                                <div class="card-count">
                                    {{ number_format($data['staff'] ?? 0) }}
                                </div>

                                <span class="card-growth info">
                                    <i class="ti ti-users-group me-1"></i>
                                    Registered
                                </span>

                            </div>

                            <div class="dashboard-icon bg-purple">
                                <i class="ti ti-users-group"></i>
                            </div>

                        </div>

                    </div>

                </div>


                {{-- HOSPITALS --}}

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <div class="dashboard-card">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="card-title-custom">
                                    Hospitals
                                </div>

                                <div class="card-count">
                                    {{ number_format($data['hospitals'] ?? 0) }}
                                </div>

                                <span class="card-growth info">
                                    Registered
                                </span>

                            </div>

                            <div class="dashboard-icon bg-blue">
                                <i class="ti ti-building-hospital"></i>
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
                                    {{ number_format($data['medicines'] ?? 0) }}
                                </div>

                                <span class="card-growth">
                                    Medicine Masters
                                </span>

                            </div>

                            <div class="dashboard-icon bg-green">
                                <i class="ti ti-pill"></i>
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
                                    {{ number_format($data['ambulances'] ?? 0) }}
                                </div>

                                <span class="card-growth warning">
                                    Fleet
                                </span>

                            </div>

                            <div class="dashboard-icon bg-red">
                                <i class="ti ti-ambulance"></i>
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
                                    {{ number_format($data['diagnostics'] ?? 0) }}
                                </div>

                                <span class="card-growth info">
                                    Diagnostic Centers
                                </span>

                            </div>

                            <div class="dashboard-icon bg-dark-custom">
                                <i class="ti ti-microscope"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =========================================================
            QUICK ACTIONS + TODAY SUMMARY
            ========================================================== --}}

            <div class="row g-4 mt-1">

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

                                <div class="col-lg-3 col-md-4 col-6">

                                    <a href="{{ route('admin.patient-medical-reports.index') }}" class="quick-action qa-blue">

                                        <i class="ti ti-user-plus"></i>

                                        Patient

                                    </a>

                                </div>

                                <div class="col-lg-3 col-md-4 col-6">

                                    <a href="{{ route('admin.doctors.index') }}" class="quick-action qa-green">

                                        <i class="ti ti-user-heart"></i>

                                        Doctor

                                    </a>

                                </div>

                                <div class="col-lg-3 col-md-4 col-6">

                                    <a href="{{ route('admin.appointments.index') }}" class="quick-action qa-orange">

                                        <i class="ti ti-calendar-plus"></i>

                                        Appointment

                                    </a>

                                </div>

                                <div class="col-lg-3 col-md-4 col-6">

                                    <a href="{{ route('admin.hospitals.index') }}" class="quick-action qa-cyan">

                                        <i class="ti ti-building-hospital"></i>

                                        Hospital

                                    </a>

                                </div>

                                <div class="col-lg-3 col-md-4 col-6">

                                    <a href="{{ route('admin.medicines.index') }}" class="quick-action qa-red">

                                        <i class="ti ti-pill"></i>

                                        Medicine

                                    </a>

                                </div>

                                <div class="col-lg-3 col-md-4 col-6">

                                    <a href="{{ route('admin.lab-tests.index') }}" class="quick-action qa-purple">

                                        <i class="ti ti-test-pipe"></i>

                                        Lab Test

                                    </a>

                                </div>

                                <div class="col-lg-3 col-md-4 col-6">

                                    <a href="{{ route('admin.ambulances.index') }}" class="quick-action qa-red">

                                        <i class="ti ti-ambulance"></i>

                                        Ambulance

                                    </a>

                                </div>

                                <div class="col-lg-3 col-md-4 col-6">

                                    <a href="{{ route('admin.marketing-staff.all') }}" class="quick-action qa-blue">

                                        <i class="ti ti-users-group"></i>

                                        Staff

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

                                <span>Appointments</span>

                                <strong>
                                    {{ number_format($data['today_appointments'] ?? 0) }}
                                </strong>

                            </div>

                            <div class="summary-row">

                                <span>Pending</span>

                                <strong class="text-warning">
                                    {{ number_format($data['pending_appointments'] ?? 0) }}
                                </strong>

                            </div>

                            <div class="summary-row">

                                <span>Confirmed</span>

                                <strong class="text-primary">
                                    {{ number_format($data['confirmed_appointments'] ?? 0) }}
                                </strong>

                            </div>

                            <div class="summary-row">

                                <span>Completed</span>

                                <strong class="text-success">
                                    {{ number_format($data['completed_appointments'] ?? 0) }}
                                </strong>

                            </div>

                            <div class="summary-row">

                                <span>Cancelled</span>

                                <strong class="text-danger">
                                    {{ number_format($data['cancelled_appointments'] ?? 0) }}
                                </strong>

                            </div>

                            <div class="summary-row">

                                <span>Today's Revenue</span>

                                <strong class="text-success">
                                    ₹{{ number_format($data['today_revenue'] ?? 0, 2) }}
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =========================================================
            CHARTS
            ========================================================== --}}

            <div class="row g-4 mt-1">

                <div class="col-xl-6">

                    <div class="dashboard-section-card">

                        <div class="card-header d-flex justify-content-between align-items-center">

                            <h5>
                                <i class="ti ti-chart-bar me-2 text-success"></i>
                                Appointment Status
                            </h5>

                            <span class="badge bg-light text-dark">
                                Today
                            </span>

                        </div>

                        <div class="card-body">

                            <div id="appointmentStatusChart" style="height:350px;"></div>

                        </div>

                    </div>

                </div>


                <div class="col-xl-6">

                    <div class="dashboard-section-card">

                        <div class="card-header d-flex justify-content-between align-items-center">

                            <h5>
                                <i class="ti ti-chart-histogram me-2 text-primary"></i>
                                Monthly Appointments
                            </h5>

                            <span class="badge bg-light text-dark">
                                {{ now()->year }}
                            </span>

                        </div>

                        <div class="card-body">

                            <div id="appointmentChart" style="height:350px;"></div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =========================================================
            SYSTEM OVERVIEW
            ========================================================== --}}

            <div class="row g-4 mt-1">

                <div class="col-lg-3 col-md-6">

                    <div class="dashboard-section-card">

                        <div class="mini-stat">

                            <div class="mini-stat-icon bg-primary-subtle text-primary">

                                <i class="ti ti-building-hospital"></i>

                            </div>

                            <h6>Total Hospitals</h6>

                            <h2>
                                {{ number_format($data['hospitals'] ?? 0) }}
                            </h2>

                        </div>

                    </div>

                </div>


                <div class="col-lg-3 col-md-6">

                    <div class="dashboard-section-card">

                        <div class="mini-stat">

                            <div class="mini-stat-icon bg-success-subtle text-success">

                                <i class="ti ti-user-heart"></i>

                            </div>

                            <h6>Total Doctors</h6>

                            <h2>
                                {{ number_format($data['doctors'] ?? 0) }}
                            </h2>

                        </div>

                    </div>

                </div>


                <div class="col-lg-3 col-md-6">

                    <div class="dashboard-section-card">

                        <div class="mini-stat">

                            <div class="mini-stat-icon bg-danger-subtle text-danger">

                                <i class="ti ti-ambulance"></i>

                            </div>

                            <h6>Total Ambulances</h6>

                            <h2>
                                {{ number_format($data['ambulances'] ?? 0) }}
                            </h2>

                        </div>

                    </div>

                </div>


                <div class="col-lg-3 col-md-6">

                    <div class="dashboard-section-card">

                        <div class="mini-stat">

                            <div class="mini-stat-icon bg-warning-subtle text-warning">

                                <i class="ti ti-calendar-event"></i>

                            </div>

                            <h6>Total Appointments</h6>

                            <h2>
                                {{ number_format($data['total_appointments'] ?? 0) }}
                            </h2>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =========================================================
            RECENT APPOINTMENTS + PATIENTS
            ========================================================== --}}

            <div class="row g-4 mt-1">

                {{-- APPOINTMENTS --}}

                <div class="col-xl-7">

                    <div class="dashboard-section-card">

                        <div class="card-header d-flex justify-content-between align-items-center">

                            <h5>
                                <i class="ti ti-calendar-time me-2 text-primary"></i>
                                Recent Appointments
                            </h5>

                            <a href="#" class="btn btn-sm btn-primary">

                                View All

                            </a>

                        </div>

                        <div class="card-body p-0">

                            <div class="table-responsive">

                                <table class="table dashboard-table table-hover mb-0">

                                    <thead>

                                        <tr>

                                            <th>Patient</th>

                                            <th>Doctor</th>

                                            <th>Date</th>

                                            <th>Status</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        @forelse($recentAppointments ?? [] as $appointment)

                                                                            @php

                                                                                $status = strtolower(
                                                                                    $appointment->status ?? 'pending'
                                                                                );

                                                                                $statusClass = match ($status) {
                                                                                    'completed' => 'success',
                                                                                    'confirmed' => 'primary',
                                                                                    'cancelled' => 'danger',
                                                                                    default => 'warning'
                                                                                };

                                                                            @endphp

                                                                            <tr>

                                                                                <td>

                                                                                    <div class="d-flex align-items-center">

                                                                                        <div class="patient-avatar me-2">

                                                                                            {{ strtoupper(
                                                substr(
                                                    $appointment->customer->name ?? 'P',
                                                    0,
                                                    1
                                                )
                                            ) }}

                                                                                        </div>

                                                                                        <div>

                                                                                            <strong>
                                                                                                {{ $appointment->customer->name ?? 'Patient' }}
                                                                                            </strong>

                                                                                            @if(!empty($appointment->customer->mobile))

                                                                                                <small class="d-block text-muted">

                                                                                                    {{ $appointment->customer->mobile }}

                                                                                                </small>

                                                                                            @endif

                                                                                        </div>

                                                                                    </div>

                                                                                </td>

                                                                                <td>

                                                                                    {{ $appointment->doctor->name ?? '-' }}

                                                                                </td>

                                                                                <td>

                                                                                    {{ optional($appointment->created_at)->format('d M Y') }}

                                                                                    <small class="d-block text-muted">

                                                                                        {{ optional($appointment->created_at)->format('h:i A') }}

                                                                                    </small>

                                                                                </td>

                                                                                <td>

                                                                                    <span class="badge bg-{{ $statusClass }}">

                                                                                        {{ ucfirst($status) }}

                                                                                    </span>

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


                {{-- PATIENTS --}}

                <div class="col-xl-5">

                    <div class="dashboard-section-card">

                        <div class="card-header d-flex justify-content-between align-items-center">

                            <h5>
                                <i class="ti ti-users me-2 text-success"></i>
                                Recent Patients
                            </h5>

                            <a href="#" class="btn btn-sm btn-success">

                                View All

                            </a>

                        </div>

                        <div class="card-body">

                            @forelse($recentPatients ?? [] as $patient)

                                                    <div class="d-flex align-items-center justify-content-between py-3 border-bottom">

                                                        <div class="d-flex align-items-center">

                                                            <div class="patient-avatar me-3">

                                                                {{ strtoupper(
                                    substr(
                                        $patient->name ?? 'P',
                                        0,
                                        1
                                    )
                                ) }}

                                                            </div>

                                                            <div>

                                                                <h6 class="mb-1 fw-bold">

                                                                    {{ $patient->name ?? 'Patient' }}

                                                                </h6>

                                                                <small class="text-muted">

                                                                    {{ $patient->mobile ?? $patient->email ?? '-' }}

                                                                </small>

                                                            </div>

                                                        </div>

                                                        <small class="text-muted">

                                                            {{ optional($patient->created_at)->diffForHumans() }}

                                                        </small>

                                                    </div>

                            @empty

                                <div class="empty-state">

                                    <i class="ti ti-users"></i>

                                    <h6>
                                        No Patients Available
                                    </h6>

                                    <p>
                                        Recently registered patients will appear here.
                                    </p>

                                </div>

                            @endforelse

                        </div>

                    </div>

                </div>

            </div>


            {{-- =========================================================
            REVENUE + PHARMACY + DIAGNOSTICS
            ========================================================== --}}

            <div class="row g-4 mt-1">

                {{-- REVENUE --}}

                <div class="col-xl-4">

                    <div class="dashboard-section-card">

                        <div class="card-header">

                            <h5>
                                <i class="ti ti-currency-rupee me-2 text-success"></i>
                                Revenue Summary
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center mb-4">

                                <div>

                                    <small class="text-muted">
                                        Today's Revenue
                                    </small>

                                    <h3 class="fw-bold text-success mt-1 mb-0">

                                        ₹{{ number_format($data['today_revenue'] ?? 0, 2) }}

                                    </h3>

                                </div>

                                <div class="dashboard-icon bg-green">

                                    <i class="ti ti-currency-rupee"></i>

                                </div>

                            </div>

                            <div class="summary-row">

                                <span>This Week</span>

                                <strong>
                                    ₹{{ number_format($data['week_revenue'] ?? 0, 2) }}
                                </strong>

                            </div>

                            <div class="summary-row">

                                <span>This Month</span>

                                <strong>
                                    ₹{{ number_format($data['month_revenue'] ?? 0, 2) }}
                                </strong>

                            </div>

                            <div class="summary-row">

                                <span>This Year</span>

                                <strong>
                                    ₹{{ number_format($data['year_revenue'] ?? 0, 2) }}
                                </strong>

                            </div>

                            <div class="summary-row">

                                <span>Pending Bills</span>

                                <strong class="text-danger">

                                    ₹{{ number_format($data['pending_bills'] ?? 0, 2) }}

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

                                        {{ number_format($data['available_medicines'] ?? $data['medicines'] ?? 0) }}

                                    </h3>

                                    <small class="text-muted">
                                        Medicines Available
                                    </small>

                                </div>

                            </div>

                            <div class="summary-row">

                                <span>Low Stock</span>

                                <span class="badge bg-warning">

                                    {{ number_format($data['low_stock_medicines'] ?? 0) }}

                                </span>

                            </div>

                            <div class="summary-row">

                                <span>Out Of Stock</span>

                                <span class="badge bg-danger">

                                    {{ number_format($data['out_of_stock_medicines'] ?? 0) }}

                                </span>

                            </div>

                            <div class="summary-row">

                                <span>Expired</span>

                                <span class="badge bg-secondary">

                                    {{ number_format($data['expired_medicines'] ?? 0) }}

                                </span>

                            </div>

                            <div class="summary-row">

                                <span>Total Medicines</span>

                                <strong>

                                    {{ number_format($data['medicines'] ?? 0) }}

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

                                        {{ number_format($data['diagnostics'] ?? 0) }}

                                    </h3>

                                    <small class="text-muted">
                                        Total Diagnostics
                                    </small>

                                </div>

                            </div>

                            <div class="summary-row">

                                <span>Pending</span>

                                <strong class="text-warning">

                                    {{ number_format($data['pending_diagnostics'] ?? 0) }}

                                </strong>

                            </div>

                            <div class="summary-row">

                                <span>Completed</span>

                                <strong class="text-success">

                                    {{ number_format($data['completed_diagnostics'] ?? 0) }}

                                </strong>

                            </div>

                            <div class="summary-row">

                                <span>Total Centers / Tests</span>

                                <strong>

                                    {{ number_format($data['diagnostics'] ?? 0) }}

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =========================================================
            AMBULANCE STATUS
            ========================================================== --}}

            <div class="row g-4 mt-1">

                <div class="col-xl-6">

                    <div class="dashboard-section-card">

                        <div class="card-header d-flex justify-content-between align-items-center">

                            <h5>

                                <i class="ti ti-ambulance me-2 text-danger"></i>

                                Ambulance Status

                            </h5>

                            <span class="badge bg-danger-subtle text-danger">

                                {{ number_format($data['ambulances'] ?? 0) }}
                                Total

                            </span>

                        </div>

                        <div class="card-body">

                            <div class="row g-3">

                                <div class="col-4">

                                    <div class="status-box status-success">

                                        <h2 class="text-success">

                                            {{ number_format($data['available_ambulances'] ?? 0) }}

                                        </h2>

                                        <small>Available</small>

                                    </div>

                                </div>

                                <div class="col-4">

                                    <div class="status-box status-warning">

                                        <h2 class="text-warning">

                                            {{ number_format($data['on_duty_ambulances'] ?? 0) }}

                                        </h2>

                                        <small>On Duty</small>

                                    </div>

                                </div>

                                <div class="col-4">

                                    <div class="status-box status-danger">

                                        <h2 class="text-danger">

                                            {{ number_format($data['maintenance_ambulances'] ?? 0) }}

                                        </h2>

                                        <small>Maintenance</small>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- APPOINTMENT OVERVIEW --}}

                <div class="col-xl-6">

                    <div class="dashboard-section-card">

                        <div class="card-header">

                            <h5>

                                <i class="ti ti-calendar-check me-2 text-primary"></i>

                                Appointment Overview

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="summary-row">

                                <span>Total Appointments</span>

                                <strong>

                                    {{ number_format($data['total_appointments'] ?? 0) }}

                                </strong>

                            </div>

                            <div class="summary-row">

                                <span>Today's Appointments</span>

                                <strong class="text-primary">

                                    {{ number_format($data['today_appointments'] ?? 0) }}

                                </strong>

                            </div>

                            <div class="summary-row">

                                <span>Pending Today</span>

                                <strong class="text-warning">

                                    {{ number_format($data['pending_appointments'] ?? 0) }}

                                </strong>

                            </div>

                            <div class="summary-row">

                                <span>Completed Today</span>

                                <strong class="text-success">

                                    {{ number_format($data['completed_appointments'] ?? 0) }}

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
    APEX CHART
    ========================================================== --}}

    {{-- =========================================================
    APEX CHARTS
    ========================================================= --}}

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    @php
        /*
        |--------------------------------------------------------------------------
        | Prepare Chart Data
        |--------------------------------------------------------------------------
        */

        $chartMonths = isset($appointmentMonths)
            ? $appointmentMonths
            : [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'May',
                'Jun',
                'Jul',
                'Aug',
                'Sep',
                'Oct',
                'Nov',
                'Dec'
            ];

        $chartAppointments = isset($monthlyAppointments)
            ? $monthlyAppointments
            : array_fill(0, 12, 0);

        $pendingAppointments = (int) ($data['pending_appointments'] ?? 0);
        $confirmedAppointments = (int) ($data['confirmed_appointments'] ?? 0);
        $completedAppointments = (int) ($data['completed_appointments'] ?? 0);
        $cancelledAppointments = (int) ($data['cancelled_appointments'] ?? 0);
    @endphp


    <script>
        document.addEventListener("DOMContentLoaded", function () {


            const monthlyAppointments = {!! json_encode($chartAppointments) !!};

            const appointmentMonths = {!! json_encode($chartMonths) !!};

            const pendingAppointments = {{ $pendingAppointments }};

            const confirmedAppointments = {{ $confirmedAppointments }};

            const completedAppointments = {{ $completedAppointments }};

            const cancelledAppointments = {{ $cancelledAppointments }};


            const monthlyOptions = {

                series: [
                    {
                        name: "Appointments",
                        data: monthlyAppointments
                    }
                ],

                chart: {
                    type: "bar",
                    height: 350,

                    toolbar: {
                        show: false
                    },

                    zoom: {
                        enabled: false
                    },

                    fontFamily: "inherit"
                },

                colors: [
                    "#2563EB"
                ],

                plotOptions: {

                    bar: {
                        borderRadius: 7,
                        columnWidth: "45%",
                        endingShape: "rounded"
                    }

                },

                dataLabels: {
                    enabled: false
                },

                stroke: {
                    show: true,
                    width: 0
                },

                grid: {
                    borderColor: "#F1F5F9",
                    strokeDashArray: 4,

                    xaxis: {
                        lines: {
                            show: false
                        }
                    },

                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                },

                xaxis: {

                    categories: appointmentMonths,

                    axisBorder: {
                        show: false
                    },

                    axisTicks: {
                        show: false
                    },

                    labels: {

                        style: {
                            colors: "#64748B",
                            fontSize: "12px"
                        }

                    }

                },

                yaxis: {

                    min: 0,

                    forceNiceScale: true,

                    labels: {

                        style: {
                            colors: "#64748B",
                            fontSize: "12px"
                        },

                        formatter: function (value) {
                            return Math.floor(value);
                        }

                    },

                    title: {

                        text: "Appointments",

                        style: {
                            color: "#64748B",
                            fontSize: "12px",
                            fontWeight: 500
                        }

                    }

                },

                tooltip: {

                    theme: "light",

                    y: {

                        formatter: function (value) {

                            return Math.floor(value) + " Appointments";

                        }

                    }

                },

                fill: {

                    opacity: 1

                },

                legend: {

                    show: false

                },

                noData: {

                    text: "No appointment data available",

                    align: "center",

                    verticalAlign: "middle",

                    style: {
                        color: "#94A3B8",
                        fontSize: "14px"
                    }

                }

            };

            const monthlyElement =
                document.querySelector("#appointmentChart");

            if (monthlyElement) {

                const monthlyChart = new ApexCharts(
                    monthlyElement,
                    monthlyOptions
                );

                monthlyChart.render();

            }


            /*
            |--------------------------------------------------------------------------
            | APPOINTMENT STATUS DATA
            |--------------------------------------------------------------------------
            */

            const statusSeries = [

                pendingAppointments,

                confirmedAppointments,

                completedAppointments,

                cancelledAppointments

            ];


            const statusOptions = {

                series: [
                    {
                        name: "Appointments",
                        data: statusSeries
                    }
                ],

                chart: {

                    type: "bar",

                    height: 350,

                    toolbar: {
                        show: false
                    },

                    zoom: {
                        enabled: false
                    },

                    fontFamily: "inherit"

                },

                colors: [

                    "#F59E0B",

                    "#2563EB",

                    "#10B981",

                    "#EF4444"

                ],

                plotOptions: {

                    bar: {

                        horizontal: true,

                        borderRadius: 7,

                        barHeight: "50%",

                        distributed: true,

                        dataLabels: {

                            position: "center"

                        }

                    }

                },

                dataLabels: {

                    enabled: true,

                    formatter: function (value) {

                        return Math.floor(value);

                    },

                    style: {

                        fontSize: "12px",

                        fontWeight: 600,

                        colors: [
                            "#FFFFFF"
                        ]

                    }

                },

                xaxis: {

                    categories: [

                        "Pending",

                        "Confirmed",

                        "Completed",

                        "Cancelled"

                    ],

                    min: 0,

                    forceNiceScale: true,

                    axisBorder: {

                        show: false

                    },

                    axisTicks: {

                        show: false

                    },

                    labels: {

                        style: {

                            colors: "#64748B",

                            fontSize: "12px"

                        },

                        formatter: function (value) {

                            return Math.floor(value);

                        }

                    }

                },

                yaxis: {

                    labels: {

                        style: {

                            colors: "#475569",

                            fontSize: "13px",

                            fontWeight: 500

                        }

                    }

                },

                grid: {

                    borderColor: "#F1F5F9",

                    strokeDashArray: 4,

                    xaxis: {

                        lines: {

                            show: true

                        }

                    },

                    yaxis: {

                        lines: {

                            show: false

                        }

                    }

                },

                legend: {

                    show: false

                },

                tooltip: {

                    theme: "light",

                    y: {

                        formatter: function (value) {

                            return Math.floor(value) + " Appointments";

                        }

                    }

                },

                noData: {

                    text: "No appointment data available",

                    align: "center",

                    verticalAlign: "middle",

                    style: {

                        color: "#94A3B8",

                        fontSize: "14px"

                    }

                }

            };


            const statusElement =
                document.querySelector("#appointmentStatusChart");

            if (statusElement) {

                const statusChart = new ApexCharts(
                    statusElement,
                    statusOptions
                );

                statusChart.render();

            }

        });
    </script>

@endsection