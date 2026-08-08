@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            {{-- PAGE HEADER --}}
            <div class="page-header">
                <div class="page-title">
                    <h4>Ambulance Details</h4>
                    <h6>View ambulance information</h6>
                </div>

                <div class="page-btn d-flex gap-2">

                    <a href="{{ route('hospital.ambulances.index') }}" class="btn btn-light">
                        <i class="ti ti-arrow-left me-1"></i>
                        Back
                    </a>

                    <a href="{{ route('hospital.ambulances.edit', $ambulance->id) }}" class="btn btn-primary">
                        <i class="ti ti-edit me-1"></i>
                        Edit
                    </a>

                </div>
            </div>


            {{-- SUCCESS --}}
            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">

                    <i class="ti ti-circle-check me-2"></i>

                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            {{-- BASIC DETAILS --}}
            <div class="row">

                {{-- LEFT SIDE --}}
                <div class="col-xl-4 col-lg-5">

                    <div class="card">

                        <div class="card-body text-center">

                            {{-- IMAGE --}}
                            <div class="mb-3">

                                @if($ambulance->image)

                                    <img src="{{ asset($ambulance->image) }}" alt="Ambulance" style="
                                                width:220px;
                                                height:160px;
                                                object-fit:cover;
                                                border-radius:12px;
                                                border:1px solid #e5e7eb;
                                             ">

                                @else

                                    <div class="d-flex align-items-center justify-content-center mx-auto" style="
                                                width:220px;
                                                height:160px;
                                                border-radius:12px;
                                                background:#f5f5f5;
                                             ">

                                        <i class="ti ti-ambulance" style="font-size:70px;color:#6f2cff;">
                                        </i>

                                    </div>

                                @endif

                            </div>


                            {{-- NAME --}}
                            <h4 class="mb-1">

                                {{ $ambulance->ambulance_name
        ?? 'Ambulance' }}

                            </h4>


                            {{-- CODE --}}
                            @if($ambulance->ambulance_code)

                                <p class="text-muted mb-3">

                                    {{ $ambulance->ambulance_code }}

                                </p>

                            @endif


                            {{-- STATUS --}}
                            @if($ambulance->status === 'active')

                                <span class="badge bg-success fs-9 px-3 py-2">

                                    <i class="ti ti-circle-check me-1"></i>

                                    Active

                                </span>

                            @else

                                <span class="badge bg-danger fs-9 px-3 py-2">

                                    <i class="ti ti-circle-x me-1"></i>

                                    Inactive

                                </span>

                            @endif


                            {{-- AVAILABILITY --}}
                            <div class="mt-3">

                                @if($ambulance->is_available)

                                    <span class="badge bg-success-subtle text-success px-3 py-2">

                                        <i class="ti ti-check me-1"></i>

                                        Available

                                    </span>

                                @else

                                    <span class="badge bg-danger-subtle text-danger px-3 py-2">

                                        <i class="ti ti-x me-1"></i>

                                        Unavailable

                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- DRIVER CARD --}}
                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Driver Information
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="text-center mb-3">

                                @if($ambulance->driver_photo)

                                    <img src="{{ asset($ambulance->driver_photo) }}" alt="Driver" style="
                                                width:90px;
                                                height:90px;
                                                object-fit:cover;
                                                border-radius:50%;
                                                border:1px solid #ddd;
                                             ">

                                @else

                                    <img src="{{ asset('build/img/profiles/avatar-01.jpg') }}" alt="Driver" style="
                                                width:90px;
                                                height:90px;
                                                object-fit:cover;
                                                border-radius:50%;
                                             ">

                                @endif

                            </div>


                            <h5 class="text-center mb-1">

                                {{ $ambulance->driver_name ?? 'Not Available' }}

                            </h5>


                            @if($ambulance->driver_mobile)

                                <p class="text-center text-muted mb-3">

                                    <i class="ti ti-phone me-1"></i>

                                    {{ $ambulance->driver_mobile }}

                                </p>

                            @endif


                            <div class="border-top pt-3">

                                <div class="d-flex justify-content-between mb-3">

                                    <span class="text-muted">
                                        License Number
                                    </span>

                                    <strong>
                                        {{ $ambulance->driver_license_number ?? '-' }}
                                    </strong>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- RIGHT SIDE --}}
                <div class="col-xl-8 col-lg-7">


                    {{-- VEHICLE INFORMATION --}}
                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Vehicle Information
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">


                                {{-- TYPE --}}
                                <div class="col-md-6 mb-4">

                                    <div class="text-muted mb-1">
                                        Ambulance Type
                                    </div>

                                    <h6 class="mb-0">

                                        @if(isset($ambulance->ambulanceType))
                                            {{ $ambulance->ambulanceType->ambulance_type_name ?? '-' }}
                                        @else

                                            -

                                        @endif

                                    </h6>

                                </div>


                                {{-- VEHICLE NUMBER --}}
                                <div class="col-md-6 mb-4">

                                    <div class="text-muted mb-1">
                                        Vehicle Number
                                    </div>

                                    <h6 class="mb-0">

                                        {{ $ambulance->vehicle_number ?? '-' }}

                                    </h6>

                                </div>


                                {{-- REGISTRATION --}}
                                <div class="col-md-6 mb-4">

                                    <div class="text-muted mb-1">
                                        Registration Number
                                    </div>

                                    <h6 class="mb-0">

                                        {{ $ambulance->registration_number ?? '-' }}

                                    </h6>

                                </div>


                                {{-- MODEL --}}
                                <div class="col-md-6 mb-4">

                                    <div class="text-muted mb-1">
                                        Vehicle Model
                                    </div>

                                    <h6 class="mb-0">

                                        {{ $ambulance->model ?? '-' }}

                                    </h6>

                                </div>


                                {{-- YEAR --}}
                                <div class="col-md-6 mb-4">

                                    <div class="text-muted mb-1">
                                        Manufacturing Year
                                    </div>

                                    <h6 class="mb-0">

                                        {{ $ambulance->manufacturing_year ?? '-' }}

                                    </h6>

                                </div>


                                {{-- HOSPITAL --}}
                                <div class="col-md-6 mb-4">

                                    <div class="text-muted mb-1">
                                        Hospital
                                    </div>

                                    <h6 class="mb-0">

                                        {{ Auth::guard('hospital')->user()->hospital_name
        ?? Auth::guard('hospital')->user()->name
        ?? '-' }}

                                    </h6>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- FARE INFORMATION --}}
                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Fare Information
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-6">

                                    <div class="p-3 border rounded">

                                        <span class="text-muted d-block mb-1">
                                            Base Fare
                                        </span>

                                        <h3 class="mb-0 text-primary">

                                            ₹{{ number_format(
        (float) ($ambulance->base_fare ?? 0),
        2
    ) }}

                                        </h3>

                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <div class="p-3 border rounded">

                                        <span class="text-muted d-block mb-1">
                                            Price Per KM
                                        </span>

                                        <h3 class="mb-0 text-primary">

                                            ₹{{ number_format(
        (float) ($ambulance->price_per_km ?? 0),
        2
    ) }}

                                            <small class="fs-14 text-muted">
                                                /km
                                            </small>

                                        </h3>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- LOCATION --}}
                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Current Location
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <div class="text-muted mb-1">
                                        Latitude
                                    </div>

                                    <h6>
                                        {{ $ambulance->latitude ?? '-' }}
                                    </h6>

                                </div>


                                <div class="col-md-6 mb-3">

                                    <div class="text-muted mb-1">
                                        Longitude
                                    </div>

                                    <h6>
                                        {{ $ambulance->longitude ?? '-' }}
                                    </h6>

                                </div>


                                <div class="col-12">

                                    @if(
                                            !empty($ambulance->latitude) &&
                                            !empty($ambulance->longitude)
                                        )

                                        <a href="https://www.google.com/maps?q={{ $ambulance->latitude }},{{ $ambulance->longitude }}"
                                            target="_blank" class="btn btn-outline-primary">

                                            <i class="ti ti-map-pin me-1"></i>

                                            View on Google Maps

                                        </a>

                                    @else

                                        <span class="text-muted">
                                            Location not available
                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- TIMESTAMPS --}}
                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Record Information
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-6">

                                    <div class="text-muted mb-1">
                                        Created At
                                    </div>

                                    <h6>

                                        {{ $ambulance->created_at
        ? $ambulance->created_at->format('d M Y, h:i A')
        : '-' }}

                                    </h6>

                                </div>


                                <div class="col-md-6">

                                    <div class="text-muted mb-1">
                                        Last Updated
                                    </div>

                                    <h6>

                                        {{ $ambulance->updated_at
        ? $ambulance->updated_at->format('d M Y, h:i A')
        : '-' }}

                                    </h6>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- STATUS ACTION --}}
                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Ambulance Status
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">

                                <div>

                                    @if($ambulance->status === 1)

                                        <h6 class="mb-1 text-success">
                                            Ambulance is Active
                                        </h6>

                                        <p class="text-muted mb-0">
                                            This ambulance can be used for hospital bookings.
                                        </p>

                                    @else

                                        <h6 class="mb-1 text-danger">
                                            Ambulance is Inactive
                                        </h6>

                                        <p class="text-muted mb-0">
                                            This ambulance is currently disabled.
                                        </p>

                                    @endif

                                </div>


                                <form method="POST" action="{{ route('hospital.ambulances.status') }}">

                                    @csrf

                                    <input type="hidden" name="id" value="{{ $ambulance->id }}">


                                    @if($ambulance->status === 1)

                                        <input type="hidden" name="status" value="0">

                                        <button type="submit" class="btn btn-danger" onclick="return confirm(
                                                        'Are you sure you want to deactivate this ambulance?'
                                                    )">

                                            <i class="ti ti-ban me-1"></i>

                                            Deactivate

                                        </button>

                                    @else

                                        <input type="hidden" name="status" value="1">

                                        <button type="submit" class="btn btn-success" onclick="return confirm(
                                                        'Are you sure you want to activate this ambulance?'
                                                    )">

                                            <i class="ti ti-circle-check me-1"></i>

                                            Activate

                                        </button>

                                    @endif

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

@endsection