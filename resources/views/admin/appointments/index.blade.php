<?php $page = 'hospital-appointments'; ?>

@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- =====================================================
            PAGE HEADER
        ====================================================== --}}

        <div class="page-header">

            <div class="add-item d-flex">

                <div class="page-title">

                    <h4>Appointments</h4>

                    <h6>
                        Manage hospital doctor appointments
                    </h6>

                </div>

            </div>

        </div>


        {{-- =====================================================
            SUCCESS MESSAGE
        ====================================================== --}}

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


        {{-- =====================================================
            FILTERS
        ====================================================== --}}

        <div class="card">

            <div class="card-body">

                <form
                    method="GET"
                    action="{{ route('admin.appointments.index') }}"
                >

                    <div class="row align-items-end">


                        {{-- Search --}}

                        <div class="col-xl-3 col-lg-4 col-md-6">

                            <div class="mb-3">

                                <label class="form-label">
                                    Search
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="ti ti-search"></i>
                                    </span>

                                    <input
                                        type="text"
                                        name="search"
                                        class="form-control"
                                        value="{{ request('search') }}"
                                        placeholder="Appointment, patient, doctor..."
                                    >

                                </div>

                            </div>

                        </div>


                        {{-- Date --}}

                        <div class="col-xl-2 col-lg-4 col-md-6">

                            <div class="mb-3">

                                <label class="form-label">
                                    Appointment Date
                                </label>

                                <input
                                    type="date"
                                    name="date"
                                    class="form-control"
                                    value="{{ request('date') }}"
                                >

                            </div>

                        </div>


                        {{-- Doctor --}}

                        <div class="col-xl-3 col-lg-4 col-md-6">

                            <div class="mb-3">

                                <label class="form-label">
                                    Doctor
                                </label>

                                <select
                                    name="doctor_id"
                                    class="form-select"
                                >

                                    <option value="">
                                        All Doctors
                                    </option>

                                    @foreach($doctors as $doctor)

                                        <option
                                            value="{{ $doctor->id }}"
                                            {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}
                                        >

                                            {{ $doctor->doctor_name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>


                        {{-- Appointment Status --}}

                        <div class="col-xl-2 col-lg-4 col-md-6">

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

                                    @foreach([
                                        'pending' => 'Pending',
                                        'confirmed' => 'Confirmed',
                                        'ongoing' => 'Ongoing',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                    ] as $value => $label)

                                        <option
                                            value="{{ $value }}"
                                            {{ request('status') == $value ? 'selected' : '' }}
                                        >
                                            {{ $label }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>


                        {{-- Consultation Type --}}

                        <div class="col-xl-2 col-lg-4 col-md-6">

                            <div class="mb-3">

                                <label class="form-label">
                                    Consultation
                                </label>

                                <select
                                    name="consultation_type"
                                    class="form-select"
                                >

                                    <option value="">
                                        All Types
                                    </option>

                                    <option
                                        value="clinic"
                                        {{ request('consultation_type') == 'clinic' ? 'selected' : '' }}
                                    >
                                        Clinic
                                    </option>

                                    <option
                                        value="video"
                                        {{ request('consultation_type') == 'video' ? 'selected' : '' }}
                                    >
                                        Video
                                    </option>

                                    <option
                                        value="chat"
                                        {{ request('consultation_type') == 'chat' ? 'selected' : '' }}
                                    >
                                        Chat
                                    </option>

                                    <option
                                        value="home_visit"
                                        {{ request('consultation_type') == 'home_visit' ? 'selected' : '' }}
                                    >
                                        Home Visit
                                    </option>

                                </select>

                            </div>

                        </div>


                        {{-- Payment --}}

                        <div class="col-xl-2 col-lg-4 col-md-6">

                            <div class="mb-3">

                                <label class="form-label">
                                    Payment
                                </label>

                                <select
                                    name="payment_status"
                                    class="form-select"
                                >

                                    <option value="">
                                        All Payments
                                    </option>

                                    <option
                                        value="pending"
                                        {{ request('payment_status') == 'pending' ? 'selected' : '' }}
                                    >
                                        Pending
                                    </option>

                                    <option
                                        value="paid"
                                        {{ request('payment_status') == 'paid' ? 'selected' : '' }}
                                    >
                                        Paid
                                    </option>

                                    <option
                                        value="failed"
                                        {{ request('payment_status') == 'failed' ? 'selected' : '' }}
                                    >
                                        Failed
                                    </option>

                                    <option
                                        value="refunded"
                                        {{ request('payment_status') == 'refunded' ? 'selected' : '' }}
                                    >
                                        Refunded
                                    </option>

                                </select>

                            </div>

                        </div>


                        {{-- Buttons --}}

                        <div class="col-xl-4 col-lg-8 col-md-6">

                            <div class="mb-3">

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >

                                    <i class="ti ti-filter me-1"></i>

                                    Filter

                                </button>


                                <a
                                    href="{{ route('admin.appointments.index') }}"
                                    class="btn btn-light ms-2"
                                >

                                    <i class="ti ti-refresh me-1"></i>

                                    Reset

                                </a>

                            </div>

                        </div>


                    </div>

                </form>

            </div>

        </div>



        {{-- =====================================================
            APPOINTMENTS TABLE
        ====================================================== --}}

        <div class="card">

            <div class="card-header">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <h5 class="card-title mb-0">
                            Appointment List
                        </h5>

                    </div>


                    <div>

                        <span class="badge bg-primary">

                            {{ $appointments->total() }}

                            Appointments

                        </span>

                    </div>

                </div>

            </div>


            <div class="card-body p-0">


                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead class="thead-light">

                            <tr>

                                <th>#</th>

                                <th>
                                    Appointment
                                </th>

                                <th>
                                    Patient
                                </th>

                                <th>
                                    Doctor
                                </th>

                                <th>
                                    Date & Time
                                </th>

                                <th>
                                    Token
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Amount
                                </th>

                                <th>
                                    Payment
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

                        @forelse($appointments as $appointment)

                            <tr>


                                {{-- Serial --}}

                                <td>

                                    {{ $appointments->firstItem() + $loop->index }}

                                </td>



                                {{-- Appointment --}}

                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.appointments.show',
                                            $appointment->id
                                        ) }}"
                                        class="fw-semibold text-primary"
                                    >

                                        {{ $appointment->appointment_no }}

                                    </a>


                                    <small class="d-block text-muted">

                                        {{ $appointment->created_at
                                            ? $appointment->created_at->format('d M Y, h:i A')
                                            : '-' }}

                                    </small>

                                </td>



                                {{-- Patient --}}

                                <td>

                                    @if($appointment->family_member_id)

                                        <div class="fw-semibold">

                                            {{ $appointment->familyMember->name ?? '-' }}

                                        </div>

                                        <small class="text-muted">

                                            Family Member

                                        </small>

                                    @else

                                        <div class="fw-semibold">

                                            {{ $appointment->customer->name ?? '-' }}

                                        </div>

                                        <small class="text-muted">

                                            Customer

                                        </small>

                                    @endif

                                </td>



                                {{-- Doctor --}}

                                <td>

                                    <div class="d-flex align-items-center">


                                        @if($appointment->doctor?->photo)

                                            <img
                                                src="{{ asset($appointment->doctor->photo) }}"
                                                class="rounded-circle me-2"
                                                width="38"
                                                height="38"
                                                style="object-fit:cover;"
                                                alt=""
                                            >

                                        @else

                                            <span
                                                class="avatar avatar-sm bg-primary-transparent rounded-circle me-2"
                                            >

                                                <i class="ti ti-stethoscope text-primary"></i>

                                            </span>

                                        @endif


                                        <div>

                                            <strong>

                                                {{ $appointment->doctor->doctor_name ?? '-' }}

                                            </strong>

                                            @if(
                                                $appointment->doctor?->hospitalSpecialization?->specialization
                                            )

                                                <small class="d-block text-muted">

                                                    {{ $appointment->doctor
                                                        ->hospitalSpecialization
                                                        ->specialization
                                                        ->specialization_name
                                                        ?? $appointment->doctor
                                                            ->hospitalSpecialization
                                                            ->specialization
                                                            ->name
                                                        ?? '-' }}

                                                </small>

                                            @endif

                                        </div>

                                    </div>

                                </td>



                                {{-- Date & Time --}}

                                <td>

                                    <div class="fw-semibold">

                                        {{ $appointment->appointment_date
                                            ? \Carbon\Carbon::parse(
                                                $appointment->appointment_date
                                            )->format('d M Y')
                                            : '-' }}

                                    </div>


                                    <small class="text-muted">

                                        <i class="ti ti-clock me-1"></i>

                                        {{ $appointment->appointment_time
                                            ? \Carbon\Carbon::parse(
                                                $appointment->appointment_time
                                            )->format('h:i A')
                                            : '-' }}

                                    </small>

                                </td>



                                {{-- Token --}}

                                <td>

                                    @if($appointment->token_no)

                                        <span class="badge bg-light text-dark border">

                                            #{{ $appointment->token_no }}

                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- Consultation Type --}}

                                <td>

                                    @php

                                        $type =
                                            strtolower(
                                                $appointment->consultation_type
                                                ?? ''
                                            );

                                    @endphp


                                    @if($type === 'clinic')

                                        <span class="badge bg-primary-transparent text-primary">

                                            <i class="ti ti-building-hospital me-1"></i>

                                            Clinic

                                        </span>


                                    @elseif($type === 'video')

                                        <span class="badge bg-info-transparent text-info">

                                            <i class="ti ti-video me-1"></i>

                                            Video

                                        </span>


                                    @elseif($type === 'chat')

                                        <span class="badge bg-success-transparent text-success">

                                            <i class="ti ti-message me-1"></i>

                                            Chat

                                        </span>


                                    @elseif(
                                        $type === 'home_visit' ||
                                        $type === 'home visit'
                                    )

                                        <span class="badge bg-warning-transparent text-warning">

                                            <i class="ti ti-home-heart me-1"></i>

                                            Home Visit

                                        </span>


                                    @else

                                        <span class="badge bg-light text-dark">

                                            {{ ucfirst(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $appointment->consultation_type
                                                    ?? '-'
                                                )
                                            ) }}

                                        </span>

                                    @endif

                                </td>



                                {{-- Amount --}}

                                <td>

                                    <strong>

                                        ₹{{ number_format(
                                            (float) (
                                                $appointment->total_amount
                                                ?? 0
                                            ),
                                            2
                                        ) }}

                                    </strong>


                                    @if(
                                        $appointment->discount &&
                                        $appointment->discount > 0
                                    )

                                        <small class="d-block text-success">

                                            Discount:
                                            ₹{{ number_format(
                                                (float) $appointment->discount,
                                                2
                                            ) }}

                                        </small>

                                    @endif

                                </td>



                                {{-- Payment Status --}}

                                <td>

                                    @php

                                        $paymentStatus =
                                            strtolower(
                                                $appointment->payment_status
                                                ?? 'pending'
                                            );

                                    @endphp


                                    @if($paymentStatus === 'paid')

                                        <span class="badge bg-success">

                                            <i class="ti ti-circle-check me-1"></i>

                                            Paid

                                        </span>


                                    @elseif($paymentStatus === 'failed')

                                        <span class="badge bg-danger">

                                            Failed

                                        </span>


                                    @elseif($paymentStatus === 'refunded')

                                        <span class="badge bg-info">

                                            Refunded

                                        </span>


                                    @else

                                        <span class="badge bg-warning">

                                            Pending

                                        </span>

                                    @endif

                                </td>



                                {{-- Appointment Status --}}

                                <td>

                                    @php

                                        $status =
                                            strtolower(
                                                $appointment->appointment_status
                                                ?? 'pending'
                                            );

                                    @endphp


                                    @if($status === 'pending')

                                        <span class="badge bg-warning">

                                            Pending

                                        </span>


                                    @elseif($status === 'confirmed')

                                        <span class="badge bg-primary">

                                            Confirmed

                                        </span>


                                    @elseif($status === 'ongoing')

                                        <span class="badge bg-info">

                                            Ongoing

                                        </span>


                                    @elseif($status === 'completed')

                                        <span class="badge bg-success">

                                            Completed

                                        </span>


                                    @elseif($status === 'cancelled')

                                        <span class="badge bg-danger">

                                            Cancelled

                                        </span>


                                    @else

                                        <span class="badge bg-secondary">

                                            {{ ucfirst(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $status
                                                )
                                            ) }}

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
                                                    'admin.appointments.show',
                                                    $appointment->id
                                                ) }}"
                                            >

                                                <i class="ti ti-eye me-2"></i>

                                                View Details

                                            </a>



                                            @if($status === 'pending')

                                                <button
                                                    type="button"
                                                    class="dropdown-item appointment-status-btn"
                                                    data-id="{{ $appointment->id }}"
                                                    data-status="confirmed"
                                                >

                                                    <i class="ti ti-circle-check me-2 text-success"></i>

                                                    Confirm

                                                </button>

                                            @endif



                                            @if($status === 'confirmed')

                                                <button
                                                    type="button"
                                                    class="dropdown-item appointment-status-btn"
                                                    data-id="{{ $appointment->id }}"
                                                    data-status="ongoing"
                                                >

                                                    <i class="ti ti-player-play me-2 text-info"></i>

                                                    Start Appointment

                                                </button>

                                            @endif



                                            @if($status === 'ongoing')

                                                <button
                                                    type="button"
                                                    class="dropdown-item appointment-status-btn"
                                                    data-id="{{ $appointment->id }}"
                                                    data-status="completed"
                                                >

                                                    <i class="ti ti-circle-check me-2 text-success"></i>

                                                    Complete

                                                </button>

                                            @endif



                                            @if(
                                                !in_array(
                                                    $status,
                                                    [
                                                        'completed',
                                                        'cancelled'
                                                    ]
                                                )
                                            )

                                                <button
                                                    type="button"
                                                    class="dropdown-item text-danger cancel-appointment-btn"
                                                    data-id="{{ $appointment->id }}"
                                                >

                                                    <i class="ti ti-circle-x me-2"></i>

                                                    Cancel Appointment

                                                </button>

                                            @endif


                                        </div>

                                    </div>

                                </td>


                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="11"
                                    class="text-center py-5"
                                >

                                    <span
                                        class="avatar avatar-xl bg-light mx-auto mb-3"
                                    >

                                        <i class="ti ti-calendar-event fs-30 text-muted"></i>

                                    </span>


                                    <h6>
                                        No Appointments Found
                                    </h6>


                                    <p class="text-muted mb-0">

                                        There are no appointments matching your filters.

                                    </p>

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>



            {{-- =====================================================
                PAGINATION
            ====================================================== --}}

            @if($appointments->hasPages())

                <div class="card-footer">

                    <div class="d-flex justify-content-end">

                        {{ $appointments->links('pagination::bootstrap-5') }}

                    </div>

                </div>

            @endif

        </div>

    </div>
</div>



{{-- =====================================================
    CANCEL APPOINTMENT MODAL
====================================================== --}}

<div
    class="modal fade"
    id="cancelAppointmentModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="ti ti-alert-circle text-danger me-2"></i>

                    Cancel Appointment

                </h5>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body">

                <input
                    type="hidden"
                    id="cancelAppointmentId"
                >


                <div class="mb-3">

                    <label class="form-label">

                        Cancellation Reason

                        <span class="text-danger">*</span>

                    </label>


                    <textarea
                        id="cancelReason"
                        class="form-control"
                        rows="4"
                        placeholder="Enter reason for cancelling this appointment..."
                    ></textarea>

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-light"
                    data-bs-dismiss="modal"
                >

                    Close

                </button>


                <button
                    type="button"
                    class="btn btn-danger"
                    id="confirmCancelAppointment"
                >

                    <i class="ti ti-circle-x me-1"></i>

                    Cancel Appointment

                </button>

            </div>

        </div>

    </div>

</div>



{{-- =====================================================
    STATUS SCRIPT
====================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Update Status
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.appointment-status-btn')
        .forEach(function (button) {

            button.addEventListener('click', function () {

                const appointmentId =
                    this.dataset.id;

                const status =
                    this.dataset.status;


                let message =
                    'Are you sure you want to update this appointment?';


                if (status === 'confirmed') {

                    message =
                        'Confirm this appointment?';

                }


                if (status === 'ongoing') {

                    message =
                        'Start this appointment?';

                }


                if (status === 'completed') {

                    message =
                        'Mark this appointment as completed?';

                }


                if (!confirm(message)) {
                    return;
                }


                updateAppointmentStatus(
                    appointmentId,
                    status
                );

            });

        });



    /*
    |--------------------------------------------------------------------------
    | Open Cancel Modal
    |--------------------------------------------------------------------------
    */

    const cancelModalElement =
        document.getElementById(
            'cancelAppointmentModal'
        );


    let cancelModal = null;


    if (cancelModalElement) {

        cancelModal =
            new bootstrap.Modal(
                cancelModalElement
            );

    }


    document
        .querySelectorAll('.cancel-appointment-btn')
        .forEach(function (button) {

            button.addEventListener('click', function () {

                document.getElementById(
                    'cancelAppointmentId'
                ).value =
                    this.dataset.id;


                document.getElementById(
                    'cancelReason'
                ).value = '';


                cancelModal.show();

            });

        });



    /*
    |--------------------------------------------------------------------------
    | Confirm Cancellation
    |--------------------------------------------------------------------------
    */

    const confirmCancelButton =
        document.getElementById(
            'confirmCancelAppointment'
        );


    if (confirmCancelButton) {

        confirmCancelButton.addEventListener(
            'click',
            function () {

                const appointmentId =
                    document.getElementById(
                        'cancelAppointmentId'
                    ).value;


                const reason =
                    document.getElementById(
                        'cancelReason'
                    ).value.trim();


                if (!reason) {

                    alert(
                        'Please enter cancellation reason.'
                    );

                    return;
                }


                updateAppointmentStatus(
                    appointmentId,
                    'cancelled',
                    reason
                );

            }
        );

    }



    /*
    |--------------------------------------------------------------------------
    | API Request
    |--------------------------------------------------------------------------
    */

    function updateAppointmentStatus(
        appointmentId,
        status,
        cancelReason = null
    ) {

        const payload = {

            id:
                appointmentId,

            appointment_status:
                status

        };


        if (cancelReason) {

            payload.cancel_reason =
                cancelReason;

        }


        fetch(
            "{{ route('admin.appointments.status') }}",
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

                body:
                    JSON.stringify(payload)

            }
        )

        .then(response => response.json())

        .then(data => {

            if (data.success == 1) {

                window.location.reload();

                return;

            }


            alert(
                data.message
                ?? 'Something went wrong'
            );

        })

        .catch(error => {

            console.error(error);

            alert(
                'Something went wrong while updating appointment.'
            );

        });

    }

});

</script>

@endsection