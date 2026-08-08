@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            {{-- =====================================================
            PAGE HEADER
            ====================================================== --}}

            <div class="page-header">

                <div class="page-title">

                    <h4>Reschedule Appointment</h4>

                    <h6>Select a new available date and time slot</h6>

                </div>

                <div class="page-btn">

                    <a href="{{ route(
        'hospital.appointments.show',
        $appointment->id
    ) }}" class="btn btn-light">

                        <i class="ti ti-arrow-left me-1"></i>

                        Back

                    </a>

                </div>

            </div>


            {{-- =====================================================
            ERRORS
            ====================================================== --}}

            @if($errors->any())

                <div class="alert alert-danger alert-dismissible fade show">

                    <i class="ti ti-alert-circle me-1"></i>

                    <ul class="mb-0 mt-1">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            <div class="row">


                {{-- =================================================
                CURRENT APPOINTMENT
                ================================================== --}}

                <div class="col-lg-4">

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">

                                <i class="ti ti-calendar me-2"></i>

                                Current Appointment

                            </h5>

                        </div>


                        <div class="card-body">


                            <div class="mb-4">

                                <small class="text-muted d-block mb-1">

                                    Appointment Number

                                </small>

                                <h5 class="mb-0">

                                    {{ $appointment->appointment_no }}

                                </h5>

                            </div>


                            <div class="d-flex align-items-center mb-4">

                                <div class="
                                                        avatar
                                                        avatar-lg
                                                        bg-light-primary
                                                        rounded-circle
                                                        d-flex
                                                        align-items-center
                                                        justify-content-center
                                                        me-3
                                                    ">

                                    <i class="
                                                            ti
                                                            ti-user
                                                            text-primary
                                                            fs-24
                                                        "></i>

                                </div>

                                <div>

                                    <small class="text-muted d-block">

                                        Doctor

                                    </small>

                                    <h6 class="mb-0">

                                        {{ $appointment->doctor->doctor_name ?? '-' }}

                                    </h6>

                                </div>

                            </div>


                            <div class="mb-3">

                                <small class="text-muted d-block">

                                    Current Date

                                </small>

                                <strong>

                                    {{ $appointment->appointment_date
        ? \Carbon\Carbon::parse(
            $appointment->appointment_date
        )->format('d M Y')
        : '-' }}

                                </strong>

                            </div>


                            <div class="mb-3">

                                <small class="text-muted d-block">

                                    Current Time

                                </small>

                                <strong>

                                    {{ $appointment->appointment_time ?? '-' }}

                                </strong>

                            </div>


                            <div>

                                <small class="text-muted d-block">

                                    Consultation Type

                                </small>

                                <strong>

                                    {{ ucfirst(
        str_replace(
            '_',
            ' ',
            $appointment->consultation_type ?? '-'
        )
    ) }}

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                RESCHEDULE
                ================================================== --}}

                <div class="col-lg-8">

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">

                                <i class="ti ti-calendar-event me-2"></i>

                                Select New Schedule

                            </h5>

                        </div>


                        <div class="card-body">

                            <form method="POST"
                                action="{{ route('hospital.appointments.reschedule.update', $appointment->id) }}">

                                @csrf

                                @method('PUT')


                                {{-- Hidden Schedule ID --}}

                                <input type="hidden" name="doctor_schedule_id" id="doctor_schedule_id" value="">
                                <input type="hidden" name="appointment_time" id="appointment_time"
                                    value="{{ old('appointment_time') }}">


                                {{-- =================================================
                                DATE
                                ================================================== --}}

                                <div class="mb-4">

                                    <label class="form-label">

                                        Appointment Date

                                        <span class="text-danger">*</span>

                                    </label>

                                    <input type="date" name="appointment_date" id="appointment_date" class="form-control"
                                        min="{{ date('Y-m-d') }}"
                                        value="{{ old('appointment_date', $appointment->appointment_date) }}" required>

                                    <small class="text-muted">

                                        Select a date to see available slots.

                                    </small>

                                </div>


                                {{-- =================================================
                                SLOT AREA
                                ================================================== --}}

                                <div id="slot-section" style="display:none;">

                                    <div class="d-flex
                                                                    justify-content-between
                                                                    align-items-center
                                                                    mb-3">

                                        <div>

                                            <h6 class="mb-1">

                                                Available Time Slots

                                            </h6>

                                            <small class="text-muted" id="schedule-info">

                                            </small>

                                        </div>

                                    </div>


                                    {{-- Loading --}}

                                    <div id="slot-loading" class="text-center py-4" style="display:none;">

                                        <div class="spinner-border
                                                                        text-primary" role="status">

                                        </div>

                                        <p class="text-muted mt-2 mb-0">

                                            Checking available slots...

                                        </p>

                                    </div>


                                    {{-- Error --}}

                                    <div id="slot-error" class="alert alert-danger" style="display:none;">

                                        <i class="ti ti-alert-circle me-1"></i>

                                        <span id="slot-error-message"></span>

                                    </div>


                                    {{-- Slots --}}

                                    <div id="slots-container" class="row g-2">

                                    </div>


                                    {{-- No slots --}}

                                    <div id="no-slots" class="alert alert-warning" style="display:none;">

                                        <i class="ti ti-calendar-off me-1"></i>

                                        No available slots found for this date.

                                    </div>

                                </div>


                                {{-- =================================================
                                SELECTED SLOT
                                ================================================== --}}

                                <div id="selected-slot-box" class="alert alert-success mt-4" style="display:none;">

                                    <div class="d-flex
                                                                    align-items-center">

                                        <i class="
                                                                ti
                                                                ti-circle-check
                                                                fs-22
                                                                me-2
                                                            "></i>

                                        <div>

                                            <strong>
                                                Selected Slot
                                            </strong>

                                            <div id="selected-slot-text">

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                {{-- =================================================
                                BUTTONS
                                ================================================== --}}

                                <div class="
                                                        d-flex
                                                        justify-content-end
                                                        gap-2
                                                        mt-4
                                                    ">

                                    <a href="{{ route(
        'hospital.appointments.show',
        $appointment->id
    ) }}" class="btn btn-light">

                                        Cancel

                                    </a>


                                    <button type="submit" id="reschedule-btn" class="btn btn-primary" disabled>

                                        <i class="
                                                                ti
                                                                ti-calendar-event
                                                                me-1
                                                            "></i>

                                        Confirm Reschedule

                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =============================================================
    SLOT CSS
    ============================================================= --}}

    <style>
        .appointment-slot {
            width: 100%;
            border: 1px solid #e5e7eb;
            background: #fff;
            border-radius: 8px;
            padding: 12px 10px;
            text-align: center;
            cursor: pointer;
            transition: all .2s ease;
        }

        .appointment-slot:hover {
            border-color: #7367f0;
            background: #f8f7ff;
        }

        .appointment-slot.selected {
            border-color: #7367f0;
            background: #7367f0;
            color: #fff;
        }

        .appointment-slot.booked {
            background: #f8f8f8;
            border-color: #ddd;
            color: #999;
            cursor: not-allowed;
        }

        .appointment-slot.blocked {
            background: #fff4e5;
            border-color: #ffd59a;
            color: #b76e00;
            cursor: not-allowed;
        }

        .slot-time {
            font-weight: 600;
            font-size: 14px;
        }

        .slot-status {
            font-size: 11px;
            margin-top: 3px;
        }
    </style>


    {{-- =============================================================
    SLOT JAVASCRIPT
    ============================================================= --}}

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const dateInput =
                document.getElementById('appointment_date');

            const slotSection =
                document.getElementById('slot-section');

            const slotLoading =
                document.getElementById('slot-loading');

            const slotError =
                document.getElementById('slot-error');

            const slotErrorMessage =
                document.getElementById('slot-error-message');

            const slotsContainer =
                document.getElementById('slots-container');

            const noSlots =
                document.getElementById('no-slots');

            const scheduleInfo =
                document.getElementById('schedule-info');

            const scheduleInput =
                document.getElementById('doctor_schedule_id');

            const selectedSlotBox =
                document.getElementById('selected-slot-box');

            const selectedSlotText =
                document.getElementById('selected-slot-text');

            const rescheduleButton =
                document.getElementById('reschedule-btn');


            /*
            |--------------------------------------------------------------------------
            | Load Slots
            |--------------------------------------------------------------------------
            */

            function loadSlots() {

                const date = dateInput.value;

                if (!date) {

                    slotSection.style.display = 'none';

                    return;
                }


                slotSection.style.display = 'block';

                slotLoading.style.display = 'block';

                slotError.style.display = 'none';

                noSlots.style.display = 'none';

                slotsContainer.innerHTML = '';

                selectedSlotBox.style.display = 'none';

                scheduleInput.value = '';

                rescheduleButton.disabled = true;


                const url =
                    "{{ route(
        'hospital.appointments.reschedule.slots',
        $appointment->id
    ) }}"
                    + "?appointment_date="
                    + encodeURIComponent(date);


                fetch(url, {

                    method: 'GET',

                    headers: {

                        'Accept': 'application/json',

                        'X-Requested-With': 'XMLHttpRequest'

                    }

                })

                    .then(response => response.json())

                    .then(result => {

                        slotLoading.style.display = 'none';


                        if (!result.success) {

                            slotError.style.display = 'block';

                            slotErrorMessage.innerText =
                                result.message ||
                                'Unable to load available slots.';

                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Schedule Information
                        |--------------------------------------------------------------------------
                        */

                        scheduleInfo.innerText =
                            result.data.day
                            + ' • '
                            + formatTime(result.data.available_from)
                            + ' - '
                            + formatTime(result.data.available_to);


                        /*
                        |--------------------------------------------------------------------------
                        | Slots
                        |--------------------------------------------------------------------------
                        */

                        const slots =
                            result.data.slots || [];


                        if (!slots.length) {

                            noSlots.style.display = 'block';

                            return;
                        }


                        slots.forEach(function (slot) {

                            const col =
                                document.createElement('div');

                            col.className =
                                'col-xl-3 col-lg-4 col-md-4 col-sm-6';


                            const button =
                                document.createElement('button');

                            button.type = 'button';

                            button.className =
                                'appointment-slot';


                            /*
                            |--------------------------------------------------------------------------
                            | Available
                            |--------------------------------------------------------------------------
                            */

                            if (slot.status === 'Available') {

                                button.classList.add('available');

                                button.innerHTML = `

                                                <div class="slot-time">
                                                    ${slot.slot_time}
                                                </div>

                                                <div class="slot-status text-success">
                                                    Available
                                                </div>

                                            `;


                                button.addEventListener(
                                    'click',
                                    function () {

                                        /*
                                        | Remove previous selection
                                        */

                                        document
                                            .querySelectorAll(
                                                '.appointment-slot.selected'
                                            )
                                            .forEach(function (item) {

                                                item.classList.remove(
                                                    'selected'
                                                );

                                            });


                                        /*
                                        | Select current
                                        */

                                        button.classList.add(
                                            'selected'
                                        );


                                        /*
                                        | Set hidden fields
                                        */

                                        document.getElementById(
                                            'appointment_time'
                                        );


                                        /*
                                        | Create hidden appointment_time
                                        */

                                        let timeInput =
                                            document.getElementById(
                                                'appointment_time'
                                            );


                                        if (!timeInput) {

                                            timeInput =
                                                document.createElement(
                                                    'input'
                                                );

                                            timeInput.type =
                                                'hidden';

                                            timeInput.name =
                                                'appointment_time';

                                            timeInput.id =
                                                'appointment_time';

                                            document
                                                .querySelector('form')
                                                .appendChild(
                                                    timeInput
                                                );
                                        }


                                        timeInput.value =
                                            slot.slot;


                                        scheduleInput.value =
                                            result.data.schedule_id;


                                        /*
                                        | Selected display
                                        */

                                        selectedSlotText.innerText =
                                            slot.slot_time;


                                        selectedSlotBox.style.display =
                                            'block';


                                        rescheduleButton.disabled =
                                            false;

                                    }
                                );

                            }


                            /*
                            |--------------------------------------------------------------------------
                            | Booked
                            |--------------------------------------------------------------------------
                            */

                            else if (slot.status === 'Booked') {

                                button.classList.add('booked');

                                button.disabled = true;

                                button.innerHTML = `

                                                <div class="slot-time">
                                                    ${slot.slot_time}
                                                </div>

                                                <div class="slot-status text-danger">
                                                    Booked
                                                </div>

                                            `;

                            }


                            /*
                            |--------------------------------------------------------------------------
                            | Blocked
                            |--------------------------------------------------------------------------
                            */

                            else {

                                button.classList.add('blocked');

                                button.disabled = true;

                                button.innerHTML = `

                                                <div class="slot-time">
                                                    ${slot.slot_time}
                                                </div>

                                                <div class="slot-status">
                                                    ${slot.status}
                                                </div>

                                            `;

                            }


                            col.appendChild(button);

                            slotsContainer.appendChild(col);

                        });

                    })

                    .catch(function (error) {

                        console.error(error);

                        slotLoading.style.display = 'none';

                        slotError.style.display = 'block';

                        slotErrorMessage.innerText =
                            'Something went wrong while loading slots.';

                    });

            }


            /*
            |--------------------------------------------------------------------------
            | Date Changed
            |--------------------------------------------------------------------------
            */

            dateInput.addEventListener(
                'change',
                loadSlots
            );


            /*
            |--------------------------------------------------------------------------
            | Load Existing Date
            |--------------------------------------------------------------------------
            */

            if (dateInput.value) {

                loadSlots();

            }


            /*
            |--------------------------------------------------------------------------
            | Time Formatting
            |--------------------------------------------------------------------------
            */

            function formatTime(time) {

                if (!time) {
                    return '';
                }

                const parts =
                    time.split(':');

                let hour =
                    parseInt(parts[0]);

                const minute =
                    parts[1];

                const ampm =
                    hour >= 12 ? 'PM' : 'AM';

                hour =
                    hour % 12 || 12;

                return hour
                    + ':'
                    + minute
                    + ' '
                    + ampm;

            }

        });

    </script>

@endsection