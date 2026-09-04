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
        background: rgba(255,255,255,.08);
        right: -80px;
        top: -80px;
    }

    .dashboard-header:after {
        content: "";
        position: absolute;
        width: 170px;
        height: 170px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
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
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.15);
        padding: 10px 16px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    /* Cards */

    .dashboard-card {
        background: #fff;
        border-radius: 18px;
        padding: 24px;
        transition: .35s;
        box-shadow: 0 8px 25px rgba(15,23,42,.05);
        border: 1px solid #eef1f5;
        height: 100%;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 18px 40px rgba(15,23,42,.10);
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
        background: linear-gradient(135deg,#2563EB,#60A5FA);
    }

    .bg-green {
        background: linear-gradient(135deg,#10B981,#34D399);
    }

    .bg-orange {
        background: linear-gradient(135deg,#F59E0B,#FBBF24);
    }

    .bg-red {
        background: linear-gradient(135deg,#EF4444,#F87171);
    }

    .bg-purple {
        background: linear-gradient(135deg,#7C3AED,#A78BFA);
    }

    .bg-cyan {
        background: linear-gradient(135deg,#06B6D4,#67E8F9);
    }

    .bg-pink {
        background: linear-gradient(135deg,#EC4899,#F472B6);
    }

    .bg-dark-custom {
        background: linear-gradient(135deg,#374151,#6B7280);
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

    /* Section */

    .dashboard-section-card {
        background: #fff;
        border: 1px solid #eef1f5;
        border-radius: 18px;
        box-shadow: 0 8px 25px rgba(15,23,42,.04);
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

    /* Quick Action */

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
        text-decoration: none;
    }

    .quick-action:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(15,23,42,.08);
        color: var(--primary);
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

    /* Summary */

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

    /* Chart */

    .chart-box {
        min-height: 340px;
    }

    /* Table */

    .dashboard-table th {
        background: #F8FAFC;
        color: #64748B;
        font-size: 12px;
        font-weight: 600;
        padding: 14px 16px;
        white-space: nowrap;
    }

    .dashboard-table td {
        padding: 14px 16px;
        font-size: 13px;
        vertical-align: middle;
    }

    @media(max-width:768px) {

        .content {
            padding: 15px;
        }

        .dashboard-header {
            padding: 25px;
        }

        .dashboard-header h2 {
            font-size: 26px;
        }

    }

</style>


<div class="page-wrapper">

    <div class="content">


        {{-- HEADER --}}

        <div class="dashboard-header">

            <div class="dashboard-header-content">

                <div class="row align-items-center">

                    <div class="col-lg-8">

                        <h2>
                            Welcome Back 👋
                        </h2>

                        <p>
                            {{ $hospital->hospital_name ?? 'Hospital' }}
                            — Here's what's happening today.
                        </p>

                    </div>

                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">

                        <div class="dashboard-date">

                            <i class="ti ti-calendar"></i>

                            {{ now()->format('d M Y') }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- MAIN STATISTICS --}}

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
                                {{ number_format($totalDoctors) }}
                            </div>

                            <span class="card-growth">

                                {{ number_format($activeDoctors) }}
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
                                {{ number_format($totalAppointments) }}
                            </div>

                            <span class="card-growth info">

                                {{ number_format($todayAppointments) }}
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
                                {{ number_format($totalAmbulances) }}
                            </div>

                            <span class="card-growth">

                                {{ number_format($availableAmbulances) }}
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
                                {{ number_format($totalMedicines) }}
                            </div>

                            <span class="card-growth danger">

                                {{ number_format($lowStockMedicines) }}
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
                                {{ number_format($totalAmbulanceBookings) }}
                            </div>

                            <span class="card-growth warning">

                                {{ number_format($pendingAmbulanceBookings) }}
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
                                {{ number_format($activeAmbulanceTrips) }}
                            </div>

                            <span class="card-growth info">
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
                                {{ number_format($totalMedicineOrders) }}
                            </div>

                            <span class="card-growth warning">

                                {{ number_format($pendingMedicineOrders) }}
                                Pending

                            </span>

                        </div>

                        <div class="dashboard-icon bg-pink">
                            <i class="ti ti-shopping-cart"></i>
                        </div>

                    </div>

                </div>

            </div>
        </div>


        {{-- REVENUE CARDS --}}

        <div class="row g-4 mt-1">

            <div class="col-xl-3 col-md-6">

                <div class="dashboard-card">

                    <div class="card-title-custom">
                        Appointment Revenue
                    </div>

                    <div class="card-count text-primary">
                        ₹{{ number_format($appointmentRevenue, 2) }}
                    </div>

                </div>

            </div>

            <div class="col-xl-3 col-md-6">

                <div class="dashboard-card">

                    <div class="card-title-custom">
                        Ambulance Revenue
                    </div>

                    <div class="card-count text-danger">
                        ₹{{ number_format($ambulanceRevenue, 2) }}
                    </div>

                </div>

            </div>

            <div class="col-xl-3 col-md-6">

                <div class="dashboard-card">

                    <div class="card-title-custom">
                        Medicine Revenue
                    </div>

                    <div class="card-count text-success">
                        ₹{{ number_format($medicineRevenue, 2) }}
                    </div>

                </div>

            </div>

            <div class="col-xl-3 col-md-6">

                <div class="dashboard-card bg-primary text-white">

                    <div class="card-title-custom text-white">
                        Total Revenue
                    </div>

                    <div class="card-count text-white">
                        ₹{{ number_format($totalRevenue, 2) }}
                    </div>

                </div>

            </div>

        </div>


        {{-- ANALYTICS CHARTS --}}

        <div class="row g-4 mt-1">

            <div class="col-xl-8">

                <div class="dashboard-section-card">

                    <div class="card-header">

                        <h5>
                            <i class="ti ti-chart-line me-2 text-primary"></i>
                            Weekly Appointment Overview
                        </h5>

                    </div>

                    <div class="card-body">

                        <div id="weeklyAppointmentChart"
                             class="chart-box">
                        </div>

                    </div>

                </div>

            </div>


            <div class="col-xl-4">

                <div class="dashboard-section-card">

                    <div class="card-header">

                        <h5>
                            Appointment Status
                        </h5>

                    </div>

                    <div class="card-body">

                        <div id="appointmentStatusChart"
                             class="chart-box">
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="row g-4 mt-1">

            <div class="col-xl-8">

                <div class="dashboard-section-card">

                    <div class="card-header">

                        <h5>
                            Monthly Appointment Trend
                        </h5>

                    </div>

                    <div class="card-body">

                        <div id="monthlyAppointmentChart"
                             class="chart-box">
                        </div>

                    </div>

                </div>

            </div>


            <div class="col-xl-4">

                <div class="dashboard-section-card">

                    <div class="card-header">

                        <h5>
                            Doctor Status
                        </h5>

                    </div>

                    <div class="card-body">

                        <div id="doctorStatusChart"
                             class="chart-box">
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="row g-4 mt-1">

            <div class="col-xl-8">

                <div class="dashboard-section-card">

                    <div class="card-header d-flex justify-content-between">

                        <h5>
                            Revenue Analytics
                        </h5>

                        <strong class="text-success">
                            ₹{{ number_format($totalRevenue, 2) }}
                        </strong>

                    </div>

                    <div class="card-body">

                        <div id="revenueChart"
                             class="chart-box">
                        </div>

                    </div>

                </div>

            </div>


            <div class="col-xl-4">

                <div class="dashboard-section-card">

                    <div class="card-header">

                        <h5>
                            Ambulance Availability
                        </h5>

                    </div>

                    <div class="card-body">

                        <div id="ambulanceStatusChart"
                             class="chart-box">
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- QUICK ACTIONS + SUMMARY --}}

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


            <div class="col-xl-4">

                <div class="dashboard-section-card">

                    <div class="card-header">

                        <h5>
                            Today's Summary
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="summary-row">
                            <span>Appointments</span>
                            <strong>{{ $todayAppointments }}</strong>
                        </div>

                        <div class="summary-row">
                            <span>Total Doctors</span>
                            <strong>{{ $totalDoctors }}</strong>
                        </div>

                        <div class="summary-row">
                            <span>Active Doctors</span>
                            <strong class="text-success">
                                {{ $activeDoctors }}
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>Pending Ambulance</span>
                            <strong class="text-warning">
                                {{ $pendingAmbulanceBookings }}
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>Active Trips</span>
                            <strong class="text-primary">
                                {{ $activeAmbulanceTrips }}
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>Pending Medicine Orders</span>
                            <strong class="text-danger">
                                {{ $pendingMedicineOrders }}
                            </strong>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- RECENT TABLES --}}

        <div class="row g-4 mt-1">


            {{-- RECENT APPOINTMENTS --}}

            <div class="col-xl-6">

                <div class="dashboard-section-card">

                    <div class="card-header d-flex justify-content-between align-items-center">

                        <h5>
                            Recent Appointments
                        </h5>

                        <a href="{{ route('hospital.appointments.index') }}"
                           class="btn btn-sm btn-primary">

                            View All

                        </a>

                    </div>

                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table dashboard-table mb-0">

                                <thead>

                                    <tr>
                                        <th>Appointment</th>
                                        <th>Doctor</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($recentAppointments as $appointment)

                                        @php

                                            $status = $appointment->appointment_status ?? 'pending';

                                            $class = match($status) {
                                                'completed' => 'success',
                                                'confirmed', 'approved' => 'primary',
                                                'cancelled' => 'danger',
                                                default => 'warning'
                                            };

                                        @endphp

                                        <tr>

                                            <td>
                                                <strong>
                                                    {{ $appointment->appointment_no ?? '#' . $appointment->id }}
                                                </strong>
                                            </td>

                                            <td>
                                                {{ $appointment->doctor->doctor_name ?? '-' }}
                                            </td>

                                            <td>

                                                <span class="badge bg-{{ $class }}">

                                                    {{ ucfirst($status) }}

                                                </span>

                                            </td>

                                            <td>

                                                {{ optional($appointment->appointment_date)->format('d M Y') ?? $appointment->appointment_date }}

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="4"
                                                class="text-center py-4 text-muted">

                                                No appointments found

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>


            {{-- RECENT AMBULANCE BOOKINGS --}}

            <div class="col-xl-6">

                <div class="dashboard-section-card">

                    <div class="card-header d-flex justify-content-between align-items-center">

                        <h5>
                            Recent Ambulance Requests
                        </h5>

                        <a href="{{ route('hospital.ambulance-bookings.index') }}"
                           class="btn btn-sm btn-danger">

                            View All

                        </a>

                    </div>

                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table dashboard-table mb-0">

                                <thead>

                                    <tr>
                                        <th>Booking</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($recentAmbulanceBookings as $booking)

                                        <tr>

                                            <td>
                                                <strong>
                                                    {{ $booking->booking_no ?? '#' . $booking->id }}
                                                </strong>
                                            </td>

                                            <td>
                                                {{ $booking->ambulanceType->name ?? '-' }}
                                            </td>

                                            <td>

                                                <span class="badge bg-info">

                                                    {{ ucfirst($booking->booking_status ?? 'pending') }}

                                                </span>

                                            </td>

                                            <td>

                                                ₹{{ number_format($booking->total_amount ?? 0, 2) }}

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="4"
                                                class="text-center py-4 text-muted">

                                                No ambulance requests found

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>


            {{-- RECENT MEDICINE ORDERS --}}

            <div class="col-xl-12">

                <div class="dashboard-section-card">

                    <div class="card-header d-flex justify-content-between align-items-center">

                        <h5>
                            Recent Medicine Orders
                        </h5>

                        <a href="{{ route('hospital.medicine-orders.index') }}"
                           class="btn btn-sm btn-success">

                            View All

                        </a>

                    </div>

                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table dashboard-table mb-0">

                                <thead>

                                    <tr>
                                        <th>Order</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($recentMedicineOrders as $order)

                                        <tr>

                                            <td>
                                                <strong>
                                                    {{ $order->order_no ?? '#' . $order->id }}
                                                </strong>
                                            </td>

                                            <td>

                                                <span class="badge bg-primary">

                                                    {{ ucfirst($order->order_status ?? 'pending') }}

                                                </span>

                                            </td>

                                            <td>

                                                <span class="badge bg-secondary">

                                                    {{ ucfirst($order->payment_status ?? 'pending') }}

                                                </span>

                                            </td>

                                            <td>

                                                ₹{{ number_format($order->total_amount ?? 0, 2) }}

                                            </td>

                                            <td>

                                                {{ optional($order->created_at)->format('d M Y h:i A') }}

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="5"
                                                class="text-center py-4 text-muted">

                                                No medicine orders found

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
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | Weekly Appointments
    |--------------------------------------------------------------------------
    */

    new ApexCharts(
        document.querySelector('#weeklyAppointmentChart'),
        {

            chart: {
                type: 'area',
                height: 330,
                toolbar: {
                    show: false
                }
            },

            series: [{
                name: 'Appointments',
                data: @json($weeklyAppointmentData)
            }],

            xaxis: {
                categories: @json($weeklyAppointmentLabels)
            },

            colors: ['#2563EB'],

            stroke: {
                curve: 'smooth',
                width: 3
            },

            dataLabels: {
                enabled: false
            },

            fill: {
                type: 'gradient',
                gradient: {
                    opacityFrom: 0.4,
                    opacityTo: 0.05
                }
            }

        }
    ).render();



    /*
    |--------------------------------------------------------------------------
    | Appointment Status
    |--------------------------------------------------------------------------
    */

    new ApexCharts(
        document.querySelector('#appointmentStatusChart'),
        {

            chart: {
                type: 'donut',
                height: 330
            },

            series: [
                {{ $pendingAppointments }},
                {{ $confirmedAppointments }},
                {{ $completedAppointments }},
                {{ $cancelledAppointments }}
            ],

            labels: [
                'Pending',
                'Confirmed',
                'Completed',
                'Cancelled'
            ],

            colors: [
                '#F59E0B',
                '#2563EB',
                '#10B981',
                '#EF4444'
            ],

            legend: {
                position: 'bottom'
            }

        }
    ).render();



    /*
    |--------------------------------------------------------------------------
    | Monthly Appointments
    |--------------------------------------------------------------------------
    */

    new ApexCharts(
        document.querySelector('#monthlyAppointmentChart'),
        {

            chart: {
                type: 'line',
                height: 330,
                toolbar: {
                    show: false
                }
            },

            series: [{
                name: 'Appointments',
                data: @json($appointmentChartData)
            }],

            xaxis: {
                categories: @json($appointmentChartLabels)
            },

            colors: ['#7C3AED'],

            stroke: {
                curve: 'smooth',
                width: 3
            },

            markers: {
                size: 5
            },

            dataLabels: {
                enabled: false
            }

        }
    ).render();



    /*
    |--------------------------------------------------------------------------
    | Doctor Status
    |--------------------------------------------------------------------------
    */

    new ApexCharts(
        document.querySelector('#doctorStatusChart'),
        {

            chart: {
                type: 'donut',
                height: 330
            },

            series: [
                {{ $activeDoctors }},
                {{ $inactiveDoctors }}
            ],

            labels: [
                'Active',
                'Inactive'
            ],

            colors: [
                '#10B981',
                '#CBD5E1'
            ],

            legend: {
                position: 'bottom'
            }

        }
    ).render();



    /*
    |--------------------------------------------------------------------------
    | Revenue Chart
    |--------------------------------------------------------------------------
    */

    new ApexCharts(
        document.querySelector('#revenueChart'),
        {

            chart: {
                type: 'bar',
                height: 330,
                toolbar: {
                    show: false
                }
            },

            series: [{
                name: 'Revenue',
                data: @json($revenueChartData)
            }],

            xaxis: {
                categories: @json($revenueChartLabels)
            },

            colors: ['#10B981'],

            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '45%'
                }
            },

            dataLabels: {
                enabled: false
            },

            tooltip: {

                y: {

                    formatter: function(value) {

                        return '₹' +
                            Number(value).toLocaleString('en-IN');

                    }

                }

            }

        }
    ).render();



    /*
    |--------------------------------------------------------------------------
    | Ambulance Status
    |--------------------------------------------------------------------------
    */

    new ApexCharts(
        document.querySelector('#ambulanceStatusChart'),
        {

            chart: {
                type: 'donut',
                height: 330
            },

            series: [
                {{ $availableAmbulances }},
                {{ $busyAmbulances }}
            ],

            labels: [
                'Available',
                'Busy'
            ],

            colors: [
                '#10B981',
                '#EF4444'
            ],

            legend: {
                position: 'bottom'
            }

        }
    ).render();


});

</script>

@endsection