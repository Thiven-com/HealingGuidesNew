<?php $page = 'hospital-ambulances'; ?>

@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- PAGE HEADER --}}
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Ambulances</h4>
                    <h6>Manage hospital ambulances and drivers</h6>
                </div>
            </div>

            <div class="page-btn">
                <a href="{{ route('hospital.ambulances.create') }}"
                   class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>
                    Add Ambulance
                </a>
            </div>
        </div>


        {{-- SUCCESS --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="ti ti-circle-check me-2"></i>
                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>
            </div>
        @endif


        {{-- FILTERS --}}
        <div class="card">
            <div class="card-body">

                <form method="GET"
                      action="{{ route('hospital.ambulances.index') }}">

                    <div class="row align-items-end">

                        {{-- SEARCH --}}
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="mb-3">

                                <label class="form-label">
                                    Search
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="ti ti-search"></i>
                                    </span>

                                    <input type="text"
                                           name="search"
                                           class="form-control"
                                           value="{{ request('search') }}"
                                           placeholder="Name, vehicle, driver, mobile...">
                                </div>

                            </div>
                        </div>


                        {{-- TYPE --}}
                        <div class="col-xl-2 col-lg-4 col-md-6">
                            <div class="mb-3">

                                <label class="form-label">
                                    Ambulance Type
                                </label>

                                <select name="ambulance_type_id"
                                        class="form-select">

                                    <option value="">
                                        All Types
                                    </option>

                                    @foreach($ambulanceTypes as $type)

                                        <option value="{{ $type->id }}"
                                            {{ request('ambulance_type_id') == $type->id ? 'selected' : '' }}>

                                            {{ $type->ambulance_type_name ?? '' }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>
                        </div>


                        {{-- AVAILABILITY --}}
                        <div class="col-xl-2 col-lg-4 col-md-6">
                            <div class="mb-3">

                                <label class="form-label">
                                    Availability
                                </label>

                                <select name="availability"
                                        class="form-select">

                                    <option value="">
                                        All
                                    </option>

                                    <option value="1"
                                        {{ request('availability') === '1' ? 'selected' : '' }}>
                                        Available
                                    </option>

                                    <option value="0"
                                        {{ request('availability') === '0' ? 'selected' : '' }}>
                                        Unavailable
                                    </option>

                                </select>

                            </div>
                        </div>


                        {{-- STATUS --}}
                        <div class="col-xl-2 col-lg-4 col-md-6">
                            <div class="mb-3">

                                <label class="form-label">
                                    Status
                                </label>

                                <select name="status"
                                        class="form-select">

                                    <option value="">
                                        All Status
                                    </option>

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
                        </div>


                        {{-- BUTTONS --}}
                        <div class="col-xl-2 col-lg-4 col-md-6">
                            <div class="mb-3 d-flex gap-2">

                                <button type="submit"
                                        class="btn btn-primary">

                                    <i class="ti ti-filter me-1"></i>
                                    Filter

                                </button>

                                <a href="{{ route('hospital.ambulances.index') }}"
                                   class="btn btn-light">

                                    <i class="ti ti-refresh"></i>

                                </a>

                            </div>
                        </div>

                    </div>

                </form>

            </div>
        </div>


        {{-- AMBULANCE LIST --}}
        <div class="card">

            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="card-title mb-0">
                            Ambulance List
                        </h5>
                    </div>

                    <span class="badge bg-primary">
                        {{ $ambulances->total() }} Ambulances
                    </span>

                </div>
            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Ambulance</th>
                                <th>Type</th>
                                <th>Vehicle</th>
                                <th>Driver</th>
                                <th>Location</th>
                                <th>Fare</th>
                                <th>Availability</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>


                        <tbody>

                        @forelse($ambulances as $ambulance)

                            <tr>

                                {{-- SERIAL --}}
                                <td>
                                    {{ $ambulances->firstItem() + $loop->index }}
                                </td>


                                {{-- AMBULANCE --}}
                                <td>

                                    <div class="d-flex align-items-center">

                                        @if($ambulance->image)

                                            <img src="{{ asset($ambulance->image) }}"
                                                 alt="{{ $ambulance->ambulance_name }}"
                                                 class="rounded me-2"
                                                 width="55"
                                                 height="45"
                                                 style="object-fit:cover;">

                                        @else

                                            <span class="avatar avatar-md bg-primary-transparent me-2">

                                                <i class="ti ti-ambulance fs-22 text-primary"></i>

                                            </span>

                                        @endif


                                        <div>

                                            <a href="{{ route(
                                                'hospital.ambulances.show',
                                                $ambulance->id
                                            ) }}"
                                               class="fw-semibold text-dark">

                                                {{ $ambulance->ambulance_name }}

                                            </a>

                                            <small class="d-block text-muted">

                                                {{ $ambulance->ambulance_code }}

                                            </small>

                                        </div>

                                    </div>

                                </td>


                                {{-- TYPE --}}
                                <td>

                                    <span class="badge bg-light text-dark border">

                                        {{ $ambulance->ambulanceType->ambulance_type_name ?? '-' }}

                                    </span>

                                </td>


                                {{-- VEHICLE --}}
                                <td>

                                    <strong>
                                        {{ $ambulance->vehicle_number }}
                                    </strong>

                                    @if($ambulance->model)

                                        <small class="d-block text-muted">

                                            {{ $ambulance->model }}

                                            @if($ambulance->manufacturing_year)
                                                • {{ $ambulance->manufacturing_year }}
                                            @endif

                                        </small>

                                    @endif

                                </td>


                                {{-- DRIVER --}}
                                <td>

                                    <div class="d-flex align-items-center">

                                        @if($ambulance->driver_photo)

                                            <img src="{{ asset($ambulance->driver_photo) }}"
                                                 alt=""
                                                 width="38"
                                                 height="38"
                                                 class="rounded-circle me-2"
                                                 style="object-fit:cover;">

                                        @else

                                            <span class="avatar avatar-sm bg-info-transparent rounded-circle me-2">

                                                <i class="ti ti-user text-info"></i>

                                            </span>

                                        @endif


                                        <div>

                                            <strong>
                                                {{ $ambulance->driver_name }}
                                            </strong>

                                            <small class="d-block text-muted">

                                                <i class="ti ti-phone me-1"></i>

                                                {{ $ambulance->driver_mobile }}

                                            </small>

                                        </div>

                                    </div>

                                </td>


                                {{-- LOCATION --}}
                                <td>

                                    @if($ambulance->current_location)

                                        <div style="max-width:180px;">

                                            <i class="ti ti-map-pin text-danger me-1"></i>

                                            {{ \Illuminate\Support\Str::limit(
                                                $ambulance->current_location,
                                                35
                                            ) }}

                                        </div>

                                    @elseif(
                                        $ambulance->latitude &&
                                        $ambulance->longitude
                                    )

                                        <small class="text-muted">
                                            Location available
                                        </small>

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- FARE --}}
                                <td>

                                    <strong class="text-primary">

                                        ₹{{ number_format(
                                            (float) ($ambulance->base_fare ?? 0),
                                            2
                                        ) }}

                                    </strong>

                                    <small class="d-block text-muted">

                                        ₹{{ number_format(
                                            (float) ($ambulance->price_per_km ?? 0),
                                            2
                                        ) }}/km

                                    </small>

                                </td>


                                {{-- AVAILABILITY --}}
                                <td>

                                    <div class="form-check form-switch">

                                        <input
                                            class="form-check-input ambulance-availability"
                                            type="checkbox"
                                            role="switch"
                                            data-id="{{ $ambulance->id }}"
                                            {{ $ambulance->is_available ? 'checked' : '' }}
                                            {{ !$ambulance->status ? 'disabled' : '' }}
                                        >

                                    </div>
                                    <br>
                                    <small class="{{ $ambulance->is_available
                                        ? 'text-success'
                                        : 'text-danger' }}">

                                        {{ $ambulance->is_available
                                            ? 'Available'
                                            : 'Unavailable' }}

                                    </small>

                                </td>


                                {{-- STATUS --}}
                                <td>

                                    @if($ambulance->status)

                                        <span class="badge bg-success">

                                            <i class="ti ti-circle-check me-1"></i>
                                            Active

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            <i class="ti ti-circle-x me-1"></i>
                                            Inactive

                                        </span>

                                    @endif

                                </td>


                                {{-- ACTION --}}
                                <td class="text-end">

                                    <div class="dropdown">

                                        <a href="javascript:void(0);"
                                           class="btn btn-sm btn-light"
                                           data-bs-toggle="dropdown">

                                            <i class="ti ti-dots-vertical"></i>

                                        </a>


                                        <div class="dropdown-menu dropdown-menu-end">

                                            {{-- VIEW --}}
                                            <a class="dropdown-item"
                                               href="{{ route(
                                                    'hospital.ambulances.show',
                                                    $ambulance->id
                                               ) }}">

                                                <i class="ti ti-eye me-2"></i>
                                                View Details

                                            </a>


                                            {{-- EDIT --}}
                                            <a class="dropdown-item"
                                               href="{{ route(
                                                    'hospital.ambulances.edit',
                                                    $ambulance->id
                                               ) }}">

                                                <i class="ti ti-edit me-2"></i>
                                                Edit

                                            </a>


                                            @if($ambulance->status)

                                                <div class="dropdown-divider"></div>

                                                <form method="POST"
                                                      action="{{ route(
                                                          'hospital.ambulances.destroy',
                                                          $ambulance->id
                                                      ) }}"
                                                      class="deactivate-form">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="dropdown-item text-danger">

                                                        <i class="ti ti-ban me-2"></i>
                                                        Deactivate

                                                    </button>

                                                </form>

                                            @endif

                                        </div>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="10"
                                    class="text-center py-5">

                                    <span class="avatar avatar-xl bg-light mx-auto mb-3">

                                        <i class="ti ti-ambulance fs-30 text-muted"></i>

                                    </span>

                                    <h6>
                                        No Ambulances Found
                                    </h6>

                                    <p class="text-muted mb-3">
                                        Add an ambulance to start managing your fleet.
                                    </p>

                                    <a href="{{ route('hospital.ambulances.create') }}"
                                       class="btn btn-primary">

                                        <i class="ti ti-plus me-1"></i>
                                        Add Ambulance

                                    </a>

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- PAGINATION --}}
            @if($ambulances->hasPages())

                <div class="card-footer">

                    <div class="d-flex justify-content-end">

                        {{ $ambulances->links('pagination::bootstrap-5') }}

                    </div>

                </div>

            @endif

        </div>

    </div>
</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Ambulance Availability
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.ambulance-availability')
        .forEach(function (input) {

            input.addEventListener('change', function () {

                const checkbox = this;

                const ambulanceId =
                    checkbox.dataset.id;

                const availability =
                    checkbox.checked ? 1 : 0;


                checkbox.disabled = true;


                fetch(
                    "{{ route('hospital.ambulances.availability') }}",
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
                            id: ambulanceId,
                            is_available: availability
                        })
                    }
                )
                .then(response => response.json())

                .then(data => {

                    if (data.success == 1) {

                        window.location.reload();

                        return;
                    }


                    checkbox.checked =
                        !checkbox.checked;

                    checkbox.disabled =
                        false;

                    alert(
                        data.message
                        ?? 'Unable to update availability.'
                    );

                })

                .catch(error => {

                    console.error(error);

                    checkbox.checked =
                        !checkbox.checked;

                    checkbox.disabled =
                        false;

                    alert(
                        'Something went wrong.'
                    );

                });

            });

        });


    /*
    |--------------------------------------------------------------------------
    | Deactivate Confirmation
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.deactivate-form')
        .forEach(function (form) {

            form.addEventListener(
                'submit',
                function (event) {

                    if (
                        !confirm(
                            'Are you sure you want to deactivate this ambulance?'
                        )
                    ) {

                        event.preventDefault();

                    }

                }
            );

        });

});

</script>

@endsection