<?php $page = 'doctors'; ?>

@extends('layout.mainlayout')

@section('content')

    <style>
        .table-responsive {
            overflow-x: auto !important;
            overflow-y: visible !important;
        }

        .table {
            min-width: 1800px;
        }

        .doctor-photo {
            width: 55px;
            height: 55px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>

    <div class="page-wrapper">

        <div class="content">

            <!-- Page Header -->
            <div class="page-header">

                <div class="add-item d-flex">

                    <div class="page-title">

                        <h4>Doctors</h4>

                        <h6>Manage Doctors</h6>

                    </div>

                </div>

                <ul class="table-top-head">

                    <li>

                        <a href="{{ route('admin.doctors.index') }}"
                           data-bs-toggle="tooltip"
                           title="Refresh">

                            <i data-feather="rotate-ccw"></i>

                        </a>

                    </li>

                    <li>

                        <a id="collapse-header"
                           data-bs-toggle="tooltip"
                           title="Collapse">

                            <i data-feather="chevron-up"></i>

                        </a>

                    </li>

                </ul>

                <div class="page-btn">

                    <a href="{{ route('admin.doctors.create') }}"
                       class="btn btn-added">

                        <i data-feather="plus-circle"
                           class="me-2"></i>

                        Add Doctor

                    </a>

                </div>

            </div>
            <!-- /Page Header -->


            {{-- Success --}}
            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">

                    {{ session('success') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"></button>

                </div>

            @endif


            {{-- Error --}}
            @if(session('error'))

                <div class="alert alert-danger alert-dismissible fade show">

                    {{ session('error') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"></button>

                </div>

            @endif


            <!-- Doctor Table -->
            <div class="card table-list-card">
                

                <div class="card-body">
                    {{-- Filters --}}


        <form method="GET" action="{{ route('admin.doctors.index') }}">

            <div class="row g-3">

                {{-- Search --}}
                <div class="col-md-4">
                    <label class="form-label">Search</label>

                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Doctor name, code, mobile or email"
                           value="{{ request('search') }}">
                </div>

                {{-- Hospital --}}
                <div class="col-md-3">
                    <label class="form-label">Hospital</label>

                    <select name="hospital_id" class="form-select">

                        <option value="">All Hospitals</option>

                        @foreach($hospitals as $hospital)

                            <option value="{{ $hospital->id }}"
                                {{ request('hospital_id') == $hospital->id ? 'selected' : '' }}>

                                {{ $hospital->hospital_name }}

                            </option>

                        @endforeach

                    </select>
                </div>

                {{-- Status --}}
                <div class="col-md-2">
                    <label class="form-label">Status</label>

                    <select name="status" class="form-select">

                        <option value="">All Status</option>

                        <option value="1"
                            {{ request('status') === '1' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0"
                            {{ request('status') === '0' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>
                </div>

                {{-- Buttons --}}
                <div class="col-md-3 d-flex align-items-end gap-2">

                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-search me-1"></i>
                        Filter
                    </button>

                    <a href="{{ route('admin.doctors.index') }}"
                       class="btn btn-secondary">
                        <i class="ti ti-refresh me-1"></i>
                        Reset
                    </a>

                </div>

            </div>

        </form>

                    <div class="table-responsive">

                        <table class="table datanew">

                            <thead>

                                <tr>

                                    <th width="60">#</th>

                                    <th width="90">Photo</th>

                                    <th>Doctor</th>

                                    <th>Doctor Code</th>

                                    <th>Hospital</th>

                                    <th>Specialization</th>

                                    <th>Qualification</th>

                                    <th>Experience</th>

                                    <th>Consultation Fee</th>

                                    <th>Mobile</th>

                                    <th>Availability</th>

                                    <th>Status</th>

                                    <th width="90"
                                        class="text-center">

                                        Action

                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($doctors as $key => $doctor)

                                    <tr>

                                        {{-- # --}}
                                        <td>

                                            {{ $key + 1 }}

                                        </td>


                                        {{-- Photo --}}
                                        <td>

                                            @if($doctor->photo)

                                                <img src="{{ asset($doctor->photo) }}"
                                                     class="doctor-photo"
                                                     alt="{{ $doctor->doctor_name }}">

                                            @else

                                                <img src="{{ asset('assets/img/no-image.png') }}"
                                                     class="doctor-photo"
                                                     alt="No Image">

                                            @endif

                                        </td>


                                        {{-- Doctor --}}
                                        <td>

                                            <div>

                                                <strong>

                                                    {{ $doctor->doctor_name }}

                                                </strong>

                                                @if($doctor->designation)

                                                    <br>

                                                    <small class="text-muted">

                                                        {{ $doctor->designation }}

                                                    </small>

                                                @endif

                                            </div>

                                        </td>


                                        {{-- Doctor Code --}}
                                        <td>

                                            <span class="badge bg-light text-dark">

                                                {{ $doctor->doctor_code }}

                                            </span>

                                        </td>


                                        {{-- Hospital --}}
                                        <td>

                                            {{ optional($doctor->hospital)->hospital_name ?? '-' }}

                                        </td>


                                        {{-- Specialization --}}
                                        <td>

                                            {{ optional($doctor->hospitalSpecialization)->specialization->specialization_name ?? '-' }}

                                        </td>


                                        {{-- Qualification --}}
                                        <td>

                                            {{ $doctor->qualification ?? '-' }}

                                        </td>


                                        {{-- Experience --}}
                                        <td>

                                            @if($doctor->experience !== null)

                                                {{ $doctor->experience }} Years

                                            @else

                                                -

                                            @endif

                                        </td>


                                        {{-- Consultation Fee --}}
                                        <td>

                                            ₹ {{ number_format($doctor->consultation_fee ?? 0, 2) }}

                                        </td>


                                        {{-- Mobile --}}
                                        <td>

                                            {{ $doctor->mobile ?? '-' }}

                                        </td>


                                        {{-- Availability --}}
                                        <td>

                                            @if($doctor->available_from || $doctor->available_to)

                                                <span class="badge bg-info">

                                                    {{ $doctor->available_from
                                                        ? \Carbon\Carbon::parse($doctor->available_from)->format('h:i A')
                                                        : '-' }}

                                                    -

                                                    {{ $doctor->available_to
                                                        ? \Carbon\Carbon::parse($doctor->available_to)->format('h:i A')
                                                        : '-' }}

                                                </span>

                                            @else

                                                <span class="text-muted">

                                                    Not Set

                                                </span>

                                            @endif

                                        </td>


                                        {{-- Status --}}
                                        <td>

                                            @if($doctor->status)

                                                <span class="badge bg-success">

                                                    Active

                                                </span>

                                            @else

                                                <span class="badge bg-danger">

                                                    Inactive

                                                </span>

                                            @endif

                                        </td>


                                        {{-- Action --}}
                                        <td class="text-center">

                                            <div class="dropdown">

                                                <a href="javascript:void(0)"
                                                   class="btn btn-sm btn-light"
                                                   data-bs-toggle="dropdown">

                                                    <i class="ti ti-dots-vertical"></i>

                                                </a>


                                                <div class="dropdown-menu dropdown-menu-end">

                                                    {{-- View --}}
                                                    <a class="dropdown-item"
                                                       href="{{ route('admin.doctors.show', $doctor->id) }}">

                                                        <i class="ti ti-eye me-2"></i>

                                                        View

                                                    </a>


                                                    {{-- Edit --}}
                                                    <a class="dropdown-item"
                                                       href="{{ route('admin.doctors.edit', $doctor->id) }}">

                                                        <i class="ti ti-edit me-2"></i>

                                                        Edit

                                                    </a>


                                                    {{-- Status --}}
                                                    <form action="{{ route('admin.doctors.status', $doctor->id) }}"
                                                          method="POST">

                                                        @csrf

                                                        <button type="submit"
                                                                class="dropdown-item">

                                                            @if($doctor->status)

                                                                <i class="ti ti-lock me-2"></i>

                                                                Deactivate

                                                            @else

                                                                <i class="ti ti-lock-open me-2"></i>

                                                                Activate

                                                            @endif

                                                        </button>

                                                    </form>


                                                    {{-- Delete --}}
                                                    {{-- <a href="javascript:void(0)"
                                                       class="dropdown-item text-danger"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#deleteModal{{ $doctor->id }}">

                                                        <i class="ti ti-trash me-2"></i>

                                                        Delete

                                                    </a> --}}

                                                </div>

                                            </div>

                                        </td>

                                    </tr>


                                    <!-- Delete Modal -->
                                    <div class="modal fade"
                                         id="deleteModal{{ $doctor->id }}"
                                         tabindex="-1"
                                         aria-hidden="true">

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <h5 class="modal-title">

                                                        Delete Doctor

                                                    </h5>

                                                    <button type="button"
                                                            class="btn-close"
                                                            data-bs-dismiss="modal"></button>

                                                </div>


                                                <div class="modal-body">

                                                    Are you sure you want to delete

                                                    <strong>

                                                        {{ $doctor->doctor_name }}

                                                    </strong>

                                                    ?

                                                </div>


                                                <div class="modal-footer">

                                                    <button type="button"
                                                            class="btn btn-secondary"
                                                            data-bs-dismiss="modal">

                                                        Cancel

                                                    </button>


                                                    <form action="{{ route('admin.doctors.destroy', $doctor->id) }}"
                                                          method="POST">

                                                        @csrf

                                                        @method('DELETE')

                                                        <button type="submit"
                                                                class="btn btn-danger">

                                                            Delete

                                                        </button>

                                                    </form>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                @empty

                                    <tr>

                                        <td colspan="13"
                                            class="text-center py-5">

                                            <h6>

                                                No Doctors Found

                                            </h6>

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
    


    <script>

        document.addEventListener("DOMContentLoaded", function () {

            if ($('.datanew').length) {

                $('.datanew').DataTable({

                    responsive: true,

                    autoWidth: false,

                    ordering: true,

                    pageLength: 10,

                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"]
                    ],

                    language: {

                        search: "",

                        searchPlaceholder: "Search Doctors..."

                    }

                });

            }

            if (typeof feather !== "undefined") {

                feather.replace();

            }

        });

    </script>

@endsection