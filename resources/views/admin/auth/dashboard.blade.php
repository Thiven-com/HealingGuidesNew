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
            --white: #fff;
        }

        .page-wrapper {
            background: var(--bg);
            min-height: 100vh;
        }

        .content {
            padding: 25px;
        }

        /* HEADER */

        .dashboard-header {
            background: linear-gradient(135deg, #2563EB, #3B82F6);
            border-radius: 22px;
            padding: 35px;
            color: #fff;
            position: relative;
            overflow: hidden;
            margin-bottom: 30px;
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

        .dashboard-header h2 {
            font-size: 34px;
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
        }

        .dashboard-header p {
            opacity: .9;
            margin: 0;
            position: relative;
            z-index: 2;
        }

        /* CARD */

        .dashboard-card {
            background: #fff;
            border-radius: 18px;
            padding: 25px;
            transition: .35s;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .06);
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .dashboard-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, .12);
        }

        .dashboard-icon {
            width: 68px;
            height: 68px;
            border-radius: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-size: 28px;
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

        .bg-dark {
            background: linear-gradient(135deg, #374151, #6B7280);
        }

        .card-title {
            color: #6B7280;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .card-count {
            font-size: 34px;
            font-weight: 700;
            color: #111827;
            line-height: 1;
        }

        .card-growth {
            display: inline-block;
            margin-top: 12px;
            background: #ECFDF5;
            color: #059669;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 12px;
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

        @media(max-width:768px) {

            .dashboard-header {
                padding: 25px;
            }

            .dashboard-header h2 {
                font-size: 28px;
            }

            .content {
                padding: 18px;
            }

        }
    </style>

    <div class="page-wrapper">

        <div class="content">

            <div class="dashboard-header">

                <h2>Welcome Back 👋</h2>

                <p>
                    Hospital Management System Admin Dashboard
                </p>

            </div>

            <div class="row g-4">

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <div class="dashboard-card">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="card-title">
                                    Total Patients
                                </div>

                                <div class="card-count">
                                    {{ number_format($data['patients'] ?? 0) }}
                                </div>

                                <span class="card-growth">
                                    +12% This Month
                                </span>

                            </div>

                            <div class="dashboard-icon bg-blue">
                                <i class="ti ti-users"></i>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <div class="dashboard-card">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="card-title">
                                    Doctors
                                </div>

                                <div class="card-count">
                                    {{ number_format($data['doctors'] ?? 0) }}
                                </div>

                                <span class="card-growth">
                                    Active
                                </span>

                            </div>

                            <div class="dashboard-icon bg-green">
                                <i class="ti ti-user-heart"></i>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <div class="dashboard-card">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="card-title">
                                    Today's Appointments
                                </div>

                                <div class="card-count">
                                    {{ number_format($data['appointments'] ?? 0) }}
                                </div>

                                <span class="card-growth warning">
                                    Scheduled
                                </span>

                            </div>

                            <div class="dashboard-icon bg-orange">
                                <i class="ti ti-calendar-event"></i>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <div class="dashboard-card">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="card-title">
                                    Hospital Staff
                                </div>

                                <div class="card-count">
                                    {{ number_format($data['staff'] ?? 0) }}
                                </div>

                                <span class="card-growth">
                                    Working
                                </span>

                            </div>

                            <div class="dashboard-icon bg-purple">
                                <i class="ti ti-users-group"></i>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <div class="dashboard-card">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="card-title">
                                    Revenue
                                </div>

                                <div class="card-count">
                                    ₹{{ number_format($data['revenue'] ?? 0) }}
                                </div>

                                <span class="card-growth">
                                    Income
                                </span>

                            </div>

                            <div class="dashboard-icon bg-cyan">
                                <i class="ti ti-currency-rupee"></i>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <div class="dashboard-card">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="card-title">
                                    Medicines
                                </div>

                                <div class="card-count">
                                    {{ number_format($data['pharmacy'] ?? 0) }}
                                </div>

                                <span class="card-growth">
                                    Available
                                </span>

                            </div>

                            <div class="dashboard-icon bg-green">
                                <i class="ti ti-pill"></i>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <div class="dashboard-card">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="card-title">
                                    Ambulances
                                </div>

                                <div class="card-count">
                                    {{ number_format($data['ambulances'] ?? 0) }}
                                </div>

                                <span class="card-growth">
                                    Ready
                                </span>

                            </div>

                            <div class="dashboard-icon bg-red">
                                <i class="ti ti-ambulance"></i>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <div class="dashboard-card">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="card-title">
                                    Diagnostics
                                </div>

                                <div class="card-count">
                                    {{ number_format($data['diagnostics'] ?? 0) }}
                                </div>

                                <span class="card-growth">
                                    Tests
                                </span>

                            </div>

                            <div class="dashboard-icon bg-dark">
                                <i class="ti ti-microscope"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- =======================================================
                                                PART 2 - QUICK ACTIONS
                            ======================================================== -->

            <div class="row mt-4">

                <div class="col-xl-8">

                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="fw-bold mb-0">
                                Quick Actions
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="row g-3">

                                <div class="col-lg-3 col-md-4 col-6">
                                    <a href="#" class="btn btn-primary w-100 py-3">
                                        <i class="ti ti-user-plus fs-24 d-block mb-2"></i>
                                        Add Patient
                                    </a>
                                </div>

                                <div class="col-lg-3 col-md-4 col-6">
                                    <a href="#" class="btn btn-success w-100 py-3">
                                        <i class="ti ti-user-heart fs-24 d-block mb-2"></i>
                                        Add Doctor
                                    </a>
                                </div>

                                <div class="col-lg-3 col-md-4 col-6">
                                    <a href="#" class="btn btn-warning text-white w-100 py-3">
                                        <i class="ti ti-calendar-plus fs-24 d-block mb-2"></i>
                                        Appointment
                                    </a>
                                </div>

                                <div class="col-lg-3 col-md-4 col-6">
                                    <a href="#" class="btn btn-info text-white w-100 py-3">
                                        <i class="ti ti-building-hospital fs-24 d-block mb-2"></i>
                                        Hospital
                                    </a>
                                </div>

                                <div class="col-lg-3 col-md-4 col-6">
                                    <a href="#" class="btn btn-danger w-100 py-3">
                                        <i class="ti ti-pill fs-24 d-block mb-2"></i>
                                        Medicine
                                    </a>
                                </div>

                                <div class="col-lg-3 col-md-4 col-6">
                                    <a href="#" class="btn btn-secondary w-100 py-3">
                                        <i class="ti ti-test-pipe fs-24 d-block mb-2"></i>
                                        Lab Test
                                    </a>
                                </div>

                                <div class="col-lg-3 col-md-4 col-6">
                                    <a href="#" class="btn btn-dark w-100 py-3">
                                        <i class="ti ti-ambulance fs-24 d-block mb-2"></i>
                                        Ambulance
                                    </a>
                                </div>

                                <div class="col-lg-3 col-md-4 col-6">
                                    <a href="#" class="btn btn-outline-primary w-100 py-3">
                                        <i class="ti ti-users-group fs-24 d-block mb-2"></i>
                                        Staff
                                    </a>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-xl-4">

                    <div class="card border-0 shadow-sm rounded-4 h-100">

                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="fw-bold mb-0">
                                Today's Summary
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span>Appointments</span>
                                <strong>0</strong>
                            </div>

                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span>Admissions</span>
                                <strong>0</strong>
                            </div>

                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span>Discharges</span>
                                <strong>0</strong>
                            </div>

                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span>Emergency Cases</span>
                                <strong>0</strong>
                            </div>

                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span>Revenue</span>
                                <strong>₹0</strong>
                            </div>

                            <div class="d-flex justify-content-between py-2">
                                <span>Pending Bills</span>
                                <strong>₹0</strong>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
            <div class="row mt-4">

                <!-- Department Chart -->
                <div class="col-xl-6">

                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-header bg-white border-0">
                            <h5 class="fw-bold mb-0">Department Wise Patients</h5>
                        </div>

                        <div class="card-body">
                            <div id="departmentChart" style="height:350px;"></div>
                        </div>

                    </div>

                </div>

                <!-- Second Chart -->
                <div class="col-xl-6">

                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-header bg-white border-0">
                            <h5 class="fw-bold mb-0">Monthly Appointments</h5>
                        </div>

                        <div class="card-body">
                            <div id="appointmentChart" style="height:350px;"></div>
                        </div>

                    </div>

                </div>

            </div>
            <div class="row mt-4">

                <div class="col-lg-3 col-md-6">

                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-body text-center">

                            <i class="ti ti-building-hospital text-primary fs-40 mb-3"></i>

                            <h6>Total Hospitals</h6>

                            <h2 class="fw-bold">0</h2>

                        </div>

                    </div>

                </div>

                <div class="col-lg-3 col-md-6">

                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-body text-center">

                            <i class="ti ti-bed text-success fs-40 mb-3"></i>

                            <h6>Available Beds</h6>

                            <h2 class="fw-bold">0</h2>

                        </div>

                    </div>

                </div>

                <div class="col-lg-3 col-md-6">

                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-body text-center">

                            <i class="ti ti-ambulance text-danger fs-40 mb-3"></i>

                            <h6>Ambulances</h6>

                            <h2 class="fw-bold">0</h2>

                        </div>

                    </div>

                </div>

                <div class="col-lg-3 col-md-6">

                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-body text-center">

                            <i class="ti ti-shield-check text-warning fs-40 mb-3"></i>

                            <h6>Active Departments</h6>

                            <h2 class="fw-bold">0</h2>

                        </div>

                    </div>

                </div>

            </div>
            <div class="row mt-4">

                <div class="col-xl-7">

                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">

                            <h5 class="fw-bold mb-0">
                                Recent Appointments
                            </h5>

                            <a href="#" class="btn btn-sm btn-primary">
                                View All
                            </a>

                        </div>

                        <div class="card-body p-0">

                            <div class="table-responsive">

                                <table class="table table-hover align-middle mb-0">

                                    <thead class="table-light">

                                        <tr>
                                            <th>Patient</th>
                                            <th>Doctor</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Status</th>
                                        </tr>

                                    </thead>

                                    <tbody>

                                        <tr>

                                            <td colspan="5" class="text-center py-5">

                                                <i class="ti ti-calendar-off fs-48 text-muted mb-3 d-block"></i>

                                                <h6 class="fw-bold">
                                                    No Appointments Found
                                                </h6>

                                                <p class="text-muted mb-0">
                                                    Appointments will appear here.
                                                </p>

                                            </td>

                                        </tr>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- ============================
                                    RECENT PATIENTS
                            ============================== -->

                <div class="col-xl-5">

                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">

                            <h5 class="fw-bold mb-0">
                                Recent Patients
                            </h5>

                            <a href="#" class="btn btn-sm btn-success">
                                View All
                            </a>

                        </div>

                        <div class="card-body">

                            <div class="text-center py-5">

                                <i class="ti ti-users fs-52 text-primary mb-3"></i>

                                <h6 class="fw-bold">
                                    No Patients Available
                                </h6>

                                <p class="text-muted mb-4">
                                    Patients added to the system will appear here.
                                </p>

                                <a href="#" class="btn btn-primary">

                                    <i class="ti ti-user-plus me-1"></i>

                                    Add Patient

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
            {{-- <div class="row mt-4">

                <div class="col-md-6">

                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-header bg-white border-0">

                            <h5 class="fw-bold mb-0">

                                Bed Availability

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="d-flex justify-content-between mb-3">

                                <span>Total Beds</span>

                                <strong>0</strong>

                            </div>

                            <div class="progress mb-4" style="height:8px;">
                                <div class="progress-bar bg-primary" style="width:0%"></div>
                            </div>

                            <div class="row text-center">

                                <div class="col-4">

                                    <h4 class="text-success mb-1">0</h4>

                                    <small>Available</small>

                                </div>

                                <div class="col-4">

                                    <h4 class="text-warning mb-1">0</h4>

                                    <small>Occupied</small>

                                </div>

                                <div class="col-4">

                                    <h4 class="text-danger mb-1">0</h4>

                                    <small>Reserved</small>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-header bg-white border-0">

                            <h5 class="fw-bold mb-0">

                                Emergency Alerts

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="alert alert-success mb-3">

                                <i class="ti ti-check me-2"></i>

                                No Emergency Alerts

                            </div>

                            <div class="alert alert-info mb-3">

                                <i class="ti ti-info-circle me-2"></i>

                                All Ambulances Available

                            </div>

                            <div class="alert alert-warning mb-0">

                                <i class="ti ti-bell me-2"></i>

                                No Critical Notifications

                            </div>

                        </div>

                    </div>

                </div>

            </div> --}}
            <!-- ==========================================
                            REVENUE & PHARMACY OVERVIEW
                    ========================================== -->

            <div class="row mt-4">

                <!-- Revenue -->

                <div class="col-xl-4">

                    <div class="card border-0 shadow-sm rounded-4 h-100">

                        <div class="card-header bg-white border-0">
                            <h5 class="fw-bold mb-0">Revenue Summary</h5>
                        </div>

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center mb-4">

                                <div>
                                    <small class="text-muted">Today's Revenue</small>
                                    <h3 class="fw-bold text-success mb-0">₹0</h3>
                                </div>

                                <i class="ti ti-currency-rupee text-success fs-48"></i>

                            </div>

                            <hr>

                            <div class="d-flex justify-content-between py-2">
                                <span>This Week</span>
                                <strong>₹0</strong>
                            </div>

                            <div class="d-flex justify-content-between py-2">
                                <span>This Month</span>
                                <strong>₹0</strong>
                            </div>

                            <div class="d-flex justify-content-between py-2">
                                <span>This Year</span>
                                <strong>₹0</strong>
                            </div>

                            <div class="d-flex justify-content-between py-2">
                                <span>Pending Bills</span>
                                <strong class="text-danger">₹0</strong>
                            </div>

                        </div>

                    </div>

                </div>

                <!-- Pharmacy -->

                <div class="col-xl-4">

                    <div class="card border-0 shadow-sm rounded-4 h-100">

                        <div class="card-header bg-white border-0">
                            <h5 class="fw-bold mb-0">Pharmacy</h5>
                        </div>

                        <div class="card-body">

                            <div class="d-flex align-items-center mb-4">

                                <div class="avatar avatar-xl bg-success rounded-circle me-3">

                                    <i class="ti ti-pill text-white fs-28"></i>

                                </div>

                                <div>

                                    <h3 class="mb-0 fw-bold">0</h3>

                                    <small class="text-muted">
                                        Medicines Available
                                    </small>

                                </div>

                            </div>

                            <div class="d-flex justify-content-between mb-3">

                                <span>Low Stock</span>

                                <span class="badge bg-warning">0</span>

                            </div>

                            <div class="d-flex justify-content-between mb-3">

                                <span>Out Of Stock</span>

                                <span class="badge bg-danger">0</span>

                            </div>

                            <div class="d-flex justify-content-between">

                                <span>Expired</span>

                                <span class="badge bg-secondary">0</span>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Diagnostics -->

                <div class="col-xl-4">

                    <div class="card border-0 shadow-sm rounded-4 h-100">

                        <div class="card-header bg-white border-0">

                            <h5 class="fw-bold mb-0">

                                Diagnostics

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="d-flex align-items-center mb-4">

                                <div class="avatar avatar-xl bg-primary rounded-circle me-3">

                                    <i class="ti ti-microscope text-white fs-28"></i>

                                </div>

                                <div>

                                    <h3 class="mb-0 fw-bold">0</h3>

                                    <small class="text-muted">
                                        Tests Conducted
                                    </small>

                                </div>

                            </div>

                            <div class="d-flex justify-content-between mb-3">

                                <span>Pending Reports</span>

                                <strong>0</strong>

                            </div>

                            <div class="d-flex justify-content-between mb-3">

                                <span>Completed</span>

                                <strong>0</strong>

                            </div>

                            <div class="d-flex justify-content-between">

                                <span>Collected Samples</span>

                                <strong>0</strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ==========================================
                                AMBULANCE & HOME CARE
                    ========================================== -->

            <div class="row mt-4">

                <div class="col-xl-6">

                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-header bg-white border-0">

                            <h5 class="fw-bold mb-0">

                                Ambulance Status

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row text-center">

                                <div class="col-4">

                                    <h2 class="text-success fw-bold">0</h2>

                                    <small>Available</small>

                                </div>

                                <div class="col-4">

                                    <h2 class="text-warning fw-bold">0</h2>

                                    <small>On Duty</small>

                                </div>

                                <div class="col-4">

                                    <h2 class="text-danger fw-bold">0</h2>

                                    <small>Maintenance</small>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-xl-6">

                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-header bg-white border-0">

                            <h5 class="fw-bold mb-0">

                                Home Care Services

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="d-flex justify-content-between py-2">

                                <span>Active Requests</span>

                                <strong>0</strong>

                            </div>

                            <div class="d-flex justify-content-between py-2">

                                <span>Completed Visits</span>

                                <strong>0</strong>

                            </div>

                            <div class="d-flex justify-content-between py-2">

                                <span>Assigned Staff</span>

                                <strong>0</strong>

                            </div>

                            <div class="d-flex justify-content-between py-2">

                                <span>Pending Requests</span>

                                <strong>0</strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        var departmentOptions = {

            series: [{
                name: "Patients",
                data: [145, 120, 98, 84, 72, 65]
            }],

            chart: {
                type: "bar",
                height: 350,
                toolbar: {
                    show: false
                }
            },

            colors: ["#10B981"],

            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 6,
                    barHeight: "55%"
                }
            },

            dataLabels: {
                enabled: true
            },

            xaxis: {
                categories: [
                    "General",
                    "Cardiology",
                    "Orthopedic",
                    "Neurology",
                    "Pediatrics",
                    "ENT"
                ]
            },

            grid: {
                borderColor: "#f1f1f1"
            },

            legend: {
                show: false
            }

        };

        var departmentChart = new ApexCharts(
            document.querySelector("#departmentChart"),
            departmentOptions
        );

        departmentChart.render();
    </script>
    <script>
        var appointmentOptions = {

            series: [{
                name: "Appointments",
                data: [45, 52, 38, 60, 72, 65, 80, 74, 90, 102, 95, 110]
            }],

            chart: {
                type: "bar",
                height: 350,
                toolbar: {
                    show: false
                }
            },

            colors: ["#2563EB"],

            plotOptions: {
                bar: {
                    borderRadius: 8,
                    columnWidth: "45%"
                }
            },

            dataLabels: {
                enabled: false
            },

            stroke: {
                show: true,
                width: 2,
                colors: ["transparent"]
            },

            xaxis: {
                categories: [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dec"
                ]
            },

            yaxis: {
                title: {
                    text: "Appointments"
                }
            },

            fill: {
                opacity: 1
            },

            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + " Appointments";
                    }
                }
            }

        };

        var appointmentChart = new ApexCharts(
            document.querySelector("#appointmentChart"),
            appointmentOptions
        );

        appointmentChart.render();
    </script>
@endsection