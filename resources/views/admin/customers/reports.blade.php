@extends('admin.customers.show')

@section('customer')

    @php
        use Illuminate\Support\Facades\DB;

        $reports = DB::table('patient_medical_reports')
            ->where('customer_id', $customer->id)
            ->latest('created_at')
            ->get();
        $familyMembers = DB::table('family_members')
            ->where('customer_id', $customer->id)
            ->latest('created_at')
            ->get();

        $reportsCount = $reports->count();
    @endphp

    <style>
        /* =========================================
                   REPORTS
                ========================================= */

        .reports-card {
            border-radius: 16px;
            border: 1px solid #f0f0f0;
        }

        .report-stat-card {
            border: 1px solid #f0f0f0;
            border-radius: 16px;
            padding: 20px;
            background: #fff;
            height: 100%;
            transition: all .3s ease;
        }

        .report-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(109, 40, 217, .10);
        }

        .report-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #f3e8ff;
            color: #6d28d9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 14px;
        }

        .report-stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #212529;
            margin-bottom: 3px;
        }

        .report-stat-label {
            color: #6b7280;
            font-size: 13px;
        }

        .report-section-title {
            font-weight: 700;
            color: #212529;
        }

        .report-table {
            margin-bottom: 0;
        }

        .report-table thead th {
            background: #f8f7fb;
            color: #6b7280;
            font-size: 13px;
            font-weight: 600;
            padding: 14px 15px;
            border-bottom: 1px solid #eee;
            white-space: nowrap;
        }

        .report-table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }

        .report-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .report-badge {
            background: #f3e8ff;
            color: #6d28d9;
            padding: 6px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .report-status-active {
            background: #dcfce7;
            color: #15803d;
            padding: 6px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .report-status-inactive {
            background: #fee2e2;
            color: #dc2626;
            padding: 6px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .empty-report {
            padding: 50px 20px;
            text-align: center;
        }

        .empty-report-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 15px;
            border-radius: 50%;
            background: #f3e8ff;
            color: #6d28d9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
        }

        @media (max-width: 768px) {

            .report-stat-card {
                padding: 16px;
            }

            .report-stat-value {
                font-size: 20px;
            }

            .report-table {
                min-width: 700px;
            }

            .table-responsive {
                overflow-x: auto;
            }
        }
    </style>

    <div class="card border-0 shadow-sm reports-card">

        <div class="card-body">

            {{-- =========================================
            REPORT HEADER
            ========================================== --}}

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">

                <div>

                    <h5 class="mb-1 report-section-title">

                        <i class="ti ti-file-report me-2" style="color:#6d28d9;"></i>

                        Medical Reports

                    </h5>

                    <p class="text-muted small mb-0">

                        Medical reports and information for
                        {{ $customer->name ?? 'Customer' }}

                    </p>

                </div>

            </div>


            {{-- =========================================
            STATISTICS
            ========================================== --}}

            <div class="row g-3 mb-4">

                {{-- Total Reports --}}

                <div class="col-xl-3 col-lg-6 col-md-6 col-12">

                    <div class="report-stat-card">

                        <div class="report-icon">
                            <i class="ti ti-file-text"></i>
                        </div>

                        <div class="report-stat-value">

                            {{ $reportsCount }}

                        </div>

                        <div class="report-stat-label">
                            Total Medical Reports
                        </div>

                    </div>

                </div>


                {{-- Customer Status --}}

                <div class="col-xl-3 col-lg-6 col-md-6 col-12">

                    <div class="report-stat-card">

                        <div class="report-icon">
                            <i class="ti ti-check"></i>
                        </div>

                        <div class="report-stat-value">

                            {{ $customer->status ? 'Active' : 'Inactive' }}

                        </div>

                        <div class="report-stat-label">
                            Customer Status
                        </div>

                    </div>

                </div>


                {{-- Verification --}}

                <div class="col-xl-3 col-lg-6 col-md-6 col-12">

                    <div class="report-stat-card">

                        <div class="report-icon">
                            <i class="ti ti-shield-check"></i>
                        </div>

                        <div class="report-stat-value">

                            {{ $customer->is_verified ? 'Verified' : 'Pending' }}

                        </div>

                        <div class="report-stat-label">
                            Verification Status
                        </div>

                    </div>

                </div>


                {{-- Customer Code --}}

                <div class="col-xl-3 col-lg-6 col-md-6 col-12">

                    <div class="report-stat-card">

                        <div class="report-icon">
                            <i class="ti ti-id"></i>
                        </div>

                        <div class="report-stat-value">

                            {{ $customer->customer_code ?? '—' }}

                        </div>

                        <div class="report-stat-label">
                            Customer Code
                        </div>

                    </div>

                </div>

            </div>


            {{-- =========================================
            CUSTOMER SUMMARY
            ========================================== --}}

            <div class="card border shadow-none reports-card mb-4">

                <div class="card-body">

                    <h6 class="fw-bold mb-4">

                        <i class="ti ti-user me-2" style="color:#6d28d9;"></i>

                        Customer Summary

                    </h6>


                    <div class="row g-4">

                        <div class="col-md-4 col-6">

                            <div class="text-muted small mb-1">
                                Customer Name
                            </div>

                            <div class="fw-semibold">
                                {{ $customer->name ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-4 col-6">

                            <div class="text-muted small mb-1">
                                Customer Code
                            </div>

                            <div class="fw-semibold">
                                {{ $customer->customer_code ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-4 col-6">

                            <div class="text-muted small mb-1">
                                Mobile
                            </div>

                            <div class="fw-semibold">
                                {{ $customer->mobile ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-4 col-6">

                            <div class="text-muted small mb-1">
                                Email
                            </div>

                            <div class="fw-semibold text-break">
                                {{ $customer->email ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-4 col-6">

                            <div class="text-muted small mb-1">
                                Gender
                            </div>

                            <div class="fw-semibold">
                                {{ $customer->gender ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-4 col-6">

                            <div class="text-muted small mb-1">
                                Date of Birth
                            </div>

                            <div class="fw-semibold">

                                @if($customer->dob)

                                    {{ \Carbon\Carbon::parse($customer->dob)->format('d-m-Y') }}

                                @else

                                    —

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =========================================
            MEDICAL REPORT HISTORY
            ========================================== --}}

            <div class="card border shadow-none reports-card">

                <div class="card-body p-0">

                    <div class="p-4 border-bottom">

                        <h6 class="fw-bold mb-0">

                            <i class="ti ti-history me-2" style="color:#6d28d9;"></i>

                            Medical Report History

                        </h6>

                    </div>


                    @if($reports->count() > 0)

                        <div class="table-responsive">

                            <table class="table report-table">

                                <thead>

                                    <tr>

                                        <th>#</th>

                                        <th>Report</th>

                                        <th>Family Member</th>

                                        <th>Description</th>

                                        <th>Date</th>

                                        <th>Status</th>
                                        <th>
                                            File
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach($reports as $index => $report)

                                        <tr>

                                            <td>

                                                {{ $index + 1 }}

                                            </td>


                                            <td class="fw-semibold">

                                                {{ $report->title ?? 'Medical Report' }}

                                            </td>

                                            <td class="fw-semibold">

                                                @php
                                                    $familyMember = $familyMembers->firstWhere(
                                                        'id',
                                                        $report->family_member_id
                                                    );
                                                @endphp

                                                @if($familyMember)
                                                    {{ $familyMember->name }}
                                                @else
                                                    {{ $customer->name ?? 'Customer' }}
                                                @endif

                                            </td>


                                            <td>

                                                {{ $report->description ?? '—' }}

                                            </td>


                                            <td>

                                                @if(!empty($report->created_at))

                                                    {{ \Carbon\Carbon::parse($report->created_at)->format('d-m-Y h:i A') }}

                                                @else

                                                    —

                                                @endif

                                            </td>


                                            <td>

                                                @if(isset($report->status))

                                                    @if($report->status)

                                                        <span class="report-status-active">
                                                            Active
                                                        </span>

                                                    @else

                                                        <span class="report-status-inactive">
                                                            Inactive
                                                        </span>

                                                    @endif

                                                @else

                                                    <span class="report-badge">
                                                        Available
                                                    </span>

                                                @endif

                                            </td>
                                            {{-- File --}}
                                        <td>

                                            @if($report->report_file)

                                                <a href="{{ asset(
                                                    'storage/' . $report->report_file
                                                ) }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-outline-primary">

                                                    <i class="ti ti-file"></i>
                                                    View

                                                </a>

                                            @else

                                                <span class="text-muted">
                                                    No File
                                                </span>

                                            @endif

                                        </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="empty-report">

                            <div class="empty-report-icon">

                                <i class="ti ti-file-off"></i>

                            </div>

                            <h6 class="fw-bold mb-2">
                                No Medical Reports Available
                            </h6>

                            <p class="text-muted small mb-0">

                                There are no medical reports available
                                for this customer.

                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

@endsection