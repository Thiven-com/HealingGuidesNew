<?php $page = 'hospital-doctors'; ?>

@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">

    <div class="content">


        {{-- Page Header --}}

        <div class="page-header">

            <div class="add-item d-flex">

                <div class="page-title">

                    <h4>
                        Doctors
                    </h4>

                    <h6>
                        Manage Hospital Doctors
                    </h6>

                </div>

            </div>


            <div class="page-btn">

                <a href="{{ route('hospital.doctors.create') }}"
                   class="btn btn-primary">

                    <i class="ti ti-circle-plus me-1"></i>

                    Add Doctor

                </a>

            </div>

        </div>


        {{-- Success --}}

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                <i class="ti ti-circle-check me-2"></i>

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

        @endif



        <div class="card">

            <div class="card-body">


                {{-- Filters --}}

                <form method="GET"
                      action="{{ route('hospital.doctors.index') }}">

                    <div class="row align-items-end mb-4">


                        <div class="col-lg-5 col-md-6">

                            <div class="mb-3">

                                <label class="form-label">
                                    Search Doctor
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">

                                        <i class="ti ti-search"></i>

                                    </span>

                                    <input
                                        type="text"
                                        name="search"
                                        class="form-control"
                                        placeholder="Name, code, mobile, email..."
                                        value="{{ request('search') }}"
                                    >

                                </div>

                            </div>

                        </div>


                        <div class="col-lg-3 col-md-3">

                            <div class="mb-3">

                                <label class="form-label">
                                    Status
                                </label>

                                <select
                                    name="status"
                                    class="form-select"
                                >

                                    <option value="">
                                        All Status
                                    </option>

                                    <option
                                        value="1"
                                        {{ request('status') === '1' ? 'selected' : '' }}
                                    >
                                        Active
                                    </option>

                                    <option
                                        value="0"
                                        {{ request('status') === '0' ? 'selected' : '' }}
                                    >
                                        Inactive
                                    </option>

                                </select>

                            </div>

                        </div>


                        <div class="col-lg-4 col-md-3">

                            <div class="mb-3">

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >

                                    <i class="ti ti-filter me-1"></i>

                                    Filter

                                </button>


                                <a
                                    href="{{ route('hospital.doctors.index') }}"
                                    class="btn btn-light ms-2"
                                >

                                    Reset

                                </a>

                            </div>

                        </div>


                    </div>

                </form>



                {{-- Table --}}

                <div class="table-responsive">

                    <table class="table table-hover">

                        <thead class="thead-light">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Doctor
                                </th>

                                <th>
                                    Contact
                                </th>

                                <th>
                                    Qualification
                                </th>

                                <th>
                                    Experience
                                </th>

                                <th>
                                    Fee
                                </th>

                                <th>
                                    Availability
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                        @forelse($doctors as $doctor)

                            <tr>


                                <td>

                                    {{ $doctors->firstItem() + $loop->index }}

                                </td>



                                {{-- Doctor --}}

                                <td>

                                    <div class="d-flex align-items-center">


                                        <div class="avatar avatar-md me-2">

                                            @if($doctor->photo)

                                                <img
                                                    src="{{ asset($doctor->photo) }}"
                                                    alt="{{ $doctor->doctor_name }}"
                                                    class="rounded-circle"
                                                >

                                            @else

                                                <span
                                                    class="avatar-title rounded-circle bg-primary text-white"
                                                >

                                                    {{ strtoupper(
                                                        substr(
                                                            $doctor->doctor_name,
                                                            0,
                                                            1
                                                        )
                                                    ) }}

                                                </span>

                                            @endif

                                        </div>


                                        <div>

                                            <a
                                                href="{{ route(
                                                    'hospital.doctors.show',
                                                    $doctor->id
                                                ) }}"
                                                class="fw-semibold text-dark"
                                            >

                                                {{ $doctor->doctor_name }}

                                            </a>


                                            <small class="d-block text-muted">

                                                {{ $doctor->doctor_code }}

                                            </small>

                                        </div>

                                    </div>

                                </td>



                                {{-- Contact --}}

                                <td>

                                    <div>

                                        <i class="ti ti-phone me-1"></i>

                                        {{ $doctor->mobile }}

                                    </div>

                                    @if($doctor->email)

                                        <small class="text-muted">

                                            {{ $doctor->email }}

                                        </small>

                                    @endif

                                </td>



                                {{-- Qualification --}}

                                <td>

                                    <strong>

                                        {{ $doctor->qualification ?? '-' }}

                                    </strong>

                                    @if($doctor->designation)

                                        <small class="d-block text-muted">

                                            {{ $doctor->designation }}

                                        </small>

                                    @endif

                                </td>



                                {{-- Experience --}}

                                <td>

                                    {{ $doctor->experience ?? 0 }}
                                    Years

                                </td>



                                {{-- Fee --}}

                                <td>

                                    ₹{{ number_format(
                                        (float) (
                                            $doctor->consultation_fee
                                            ?? 0
                                        ),
                                        2
                                    ) }}

                                </td>



                                {{-- Availability --}}

                                <td>

                                    @if(
                                        $doctor->available_from &&
                                        $doctor->available_to
                                    )

                                        <small>

                                            {{ date(
                                                'h:i A',
                                                strtotime(
                                                    $doctor->available_from
                                                )
                                            ) }}

                                            -

                                            {{ date(
                                                'h:i A',
                                                strtotime(
                                                    $doctor->available_to
                                                )
                                            ) }}

                                        </small>

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



                                {{-- Actions --}}

                                <td class="text-end">

                                    <div class="dropdown">

                                        <a
                                            href="javascript:void(0);"
                                            class="btn btn-sm btn-light"
                                            data-bs-toggle="dropdown"
                                        >

                                            <i class="ti ti-dots-vertical"></i>

                                        </a>


                                        <div class="dropdown-menu dropdown-menu-end">


                                            <a
                                                class="dropdown-item"
                                                href="{{ route(
                                                    'hospital.doctors.show',
                                                    $doctor->id
                                                ) }}"
                                            >

                                                <i class="ti ti-eye me-2"></i>

                                                View

                                            </a>


                                            <a
                                                class="dropdown-item"
                                                href="{{ route(
                                                    'hospital.doctors.edit',
                                                    $doctor->id
                                                ) }}"
                                            >

                                                <i class="ti ti-edit me-2"></i>

                                                Edit

                                            </a>


                                            <button
                                                type="button"
                                                class="dropdown-item doctor-status-btn"
                                                data-id="{{ $doctor->id }}"
                                                data-status="{{ $doctor->status ? 0 : 1 }}"
                                            >

                                                @if($doctor->status)

                                                    <i class="ti ti-ban me-2 text-danger"></i>

                                                    Deactivate

                                                @else

                                                    <i class="ti ti-circle-check me-2 text-success"></i>

                                                    Activate

                                                @endif

                                            </button>


                                        </div>

                                    </div>

                                </td>


                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center py-5"
                                >

                                    <i class="ti ti-stethoscope fs-40 text-muted"></i>

                                    <h6 class="mt-2">
                                        No Doctors Found
                                    </h6>

                                    <p class="text-muted mb-3">
                                        Add your first doctor to get started.
                                    </p>

                                    <a
                                        href="{{ route('hospital.doctors.create') }}"
                                        class="btn btn-primary btn-sm"
                                    >

                                        Add Doctor

                                    </a>

                                </td>

                            </tr>

                        @endforelse


                        </tbody>

                    </table>

                </div>



                {{-- Pagination --}}

                @if($doctors->hasPages())

                    <div class="d-flex justify-content-end mt-3">

                        {{ $doctors->links('pagination::bootstrap-5') }}

                    </div>

                @endif


            </div>

        </div>


    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    document
        .querySelectorAll('.doctor-status-btn')
        .forEach(function (button) {

            button.addEventListener('click', function () {

                const doctorId =
                    this.dataset.id;

                const status =
                    this.dataset.status;


                if (!confirm(
                    'Are you sure you want to update doctor status?'
                )) {
                    return;
                }


                fetch(
                    "{{ route('hospital.doctors.status') }}",
                    {
                        method: 'POST',

                        headers: {

                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json',

                            'X-CSRF-TOKEN':
                                "{{ csrf_token() }}"
                        },

                        body: JSON.stringify({

                            id: doctorId,

                            status: status

                        })
                    }
                )

                .then(response => response.json())

                .then(data => {

                    if (data.success == 1) {

                        location.reload();

                    } else {

                        alert(
                            data.message
                            ?? 'Something went wrong'
                        );
                    }

                })

                .catch(() => {

                    alert(
                        'Something went wrong'
                    );

                });

            });

        });

});

</script>

@endsection