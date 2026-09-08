@extends('layout.mainlayout')

@section('content')

    <style>
        .patient-card {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .06);
        }

        .patient-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
        }

        .patient-avatar-placeholder {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #6d28d9;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 18px;
        }

        .patient-name {
            font-weight: 600;
            color: #1f2937;
        }

        .patient-code {
            font-size: 12px;
            color: #6b7280;
        }

        .patient-type {
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 20px;
            background: #f3e8ff;
            color: #6d28d9;
            font-weight: 600;
        }

        .patient-info {
            color: #6b7280;
            font-size: 13px;
        }

        .filter-card {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .05);
        }

        @media(max-width: 768px) {

            .patient-table thead {
                display: none;
            }

            .patient-table tbody tr {
                display: block;
                border-bottom: 1px solid #eee;
                padding: 15px 0;
            }

            .patient-table tbody td {
                display: block;
                border: 0;
                padding: 5px 10px;
            }

            .patient-table tbody td:first-child {
                padding-top: 10px;
            }
        }
    </style>


    <div class="page-wrapper">

        <div class="content">


            {{-- =========================================
            PAGE HEADER
            ========================================== --}}

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">

                <div>

                    <h4 class="mb-1">
                        Patients
                    </h4>

                    <p class="text-muted mb-0">
                        Manage all unique patients
                    </p>

                </div>

            </div>


            {{-- =========================================
            FILTER
            ========================================== --}}

            <div class="card filter-card mb-4">

                <div class="card-body">

                    <form method="GET" action="{{ route('hospital.patients.all') }}">

                        <div class="row align-items-end">

                            {{-- Search --}}

                            <div class="col-md-6 mb-3 mb-md-0">

                                <label class="form-label">
                                    Search Patient
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="ti ti-search"></i>
                                    </span>

                                    <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                                        placeholder="Name, mobile, email or customer code">

                                </div>

                            </div>


                            {{-- Hospital --}}

                            <div class="col-md-4 mb-3 mb-md-0">

                                <label class="form-label">
                                    Hospital ID
                                </label>

                                <input type="number" name="hospital_id" class="form-control"
                                    value="{{ request('hospital_id', $hospitalId) }}" placeholder="Enter hospital ID">

                            </div>


                            {{-- Button --}}

                            <div class="col-md-2">

                                <button type="submit" class="btn btn-primary w-100">

                                    <i class="ti ti-search me-1"></i>
                                    Search

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>


            {{-- =========================================
            PATIENT LIST
            ========================================== --}}

            <div class="card patient-card">

                <div class="card-header">

                    <div class="d-flex align-items-center justify-content-between">

                        <div>

                            <h5 class="mb-1">
                                All Patients
                            </h5>

                            <small class="text-muted">
                                {{ $patients->count() }} unique patients
                            </small>

                        </div>

                    </div>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table patient-table align-middle mb-0">

                            <thead>

                                <tr>

                                    <th class="px-4">
                                        Patient
                                    </th>

                                    <th>
                                        Mobile
                                    </th>

                                    <th>
                                        Gender
                                    </th>

                                    <th>
                                        Age
                                    </th>

                                    <th>
                                        Blood Group
                                    </th>

                                    {{-- <th>
                                        Location
                                    </th>

                                    <th>
                                        Type
                                    </th> --}}

                                    <th class="text-end px-4">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($patients as $patient)

                                    <tr>

                                        {{-- Patient --}}

                                        <td class="px-4">

                                            <div class="d-flex align-items-center gap-2">

                                                @if(!empty($patient['photo']))

                                                    <img src="{{ asset($patient['photo']) }}" class="patient-avatar" alt="Patient">

                                                @else

                                                    <div class="patient-avatar-placeholder">

                                                        {{ strtoupper(substr($patient['patientname'], 0, 1)) }}

                                                    </div>

                                                @endif


                                                <div>

                                                    <div class="patient-name">
                                                        {{ $patient['patientname'] }}
                                                    </div>

                                                    @if(!empty($patient['customer_id']))
                                                        <div class="patient-code">
                                                            Customer #{{ $patient['customername'] ?? '—' }}
                                                        </div>
                                                    @endif

                                                </div>

                                            </div>

                                        </td>


                                        {{-- Mobile --}}

                                        <td>

                                            <span class="patient-info">

                                                <i class="ti ti-phone me-1"></i>

                                                {{ $patient['mobile'] }}

                                            </span>

                                        </td>


                                        {{-- Gender --}}

                                        <td>

                                            {{ $patient['gender'] ?? '—' }}

                                        </td>


                                        {{-- Age --}}

                                        <td>

                                            {{ $patient['age'] ? $patient['age'] . ' Years' : '—' }}

                                        </td>


                                        {{-- Blood Group --}}

                                        <td>

                                            @if($patient['blood_group'])

                                                <span class="badge bg-danger-subtle text-danger">

                                                    {{ $patient['blood_group'] }}

                                                </span>

                                            @else

                                                —

                                            @endif

                                        </td>


                                        {{-- Location --}}

                                        {{-- <td>

                                            <span class="patient-info">

                                                {{ $patient['city'] ?? '' }}

                                                @if($patient['city'] && $patient['state'])
                                                    ,
                                                @endif

                                                {{ $patient['state'] ?? '' }}

                                            </span>

                                        </td> --}}


                                        {{-- Type --}}

                                        {{-- <td>

                                            <span class="patient-type">

                                                {{ $patient['type'] }}

                                            </span>

                                        </td> --}}


                                        {{-- Action --}}

                                        <td class="text-end px-4">

                                            @if($patient['family_member_id'])

                                                                            <a href="{{ route('hospital.patients.show', [
                                                    'type' => $patient['type'] === 'Family Member' ? 'family' : 'customer',
                                                    'id' => $patient['type'] === 'Family Member'
                                                        ? $patient['family_member_id']
                                                        : $patient['customer_id'],
                                                ]) }}" class="btn btn-sm btn-light">
                                                                                <i class="ti ti-eye"></i>
                                                                            </a>

                                            @else

                                                                            <a href="{{ route('hospital.patients.show', [
                                                    'customer_id' => $patient['customer_id']
                                                ]) }}" class="btn btn-sm btn-light border">

                                                                                <i class="ti ti-eye"></i>

                                                                            </a>

                                            @endif

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="8" class="text-center py-5">

                                            <div class="text-muted">

                                                <i class="ti ti-users fs-40 d-block mb-2"></i>

                                                No patients found.

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

@endsection