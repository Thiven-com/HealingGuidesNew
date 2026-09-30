@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- Page Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">

            <div>
                <h4 class="mb-1">Health Checkup Package</h4>

                <p class="text-muted mb-0">
                    View package details and included tests.
                </p>
            </div>

            <div class="d-flex gap-2">

                <a
                    href="{{ route(
                        'admin.health-checkup-packages.edit',
                        $healthCheckupPackage->id
                    ) }}"
                    class="btn btn-primary">

                    <i class="ti ti-edit me-1"></i>

                    Edit

                </a>

                <a
                    href="{{ route(
                        'admin.health-checkup-packages.index'
                    ) }}"
                    class="btn btn-light">

                    <i class="ti ti-arrow-left me-1"></i>

                    Back

                </a>

            </div>

        </div>


        <div class="row g-4">

            {{-- ========================================================= --}}
            {{-- Package Overview --}}
            {{-- ========================================================= --}}

            <div class="col-lg-8">

                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Package Details
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-4">


                            {{-- Image --}}
                            <div class="col-md-4">

                                @if($healthCheckupPackage->image)

                                    <img
                                        src="{{ asset(
                                            $healthCheckupPackage->image
                                        ) }}"
                                        alt="{{ $healthCheckupPackage->name }}"
                                        class="img-fluid rounded border"
                                        style="
                                            width:100%;
                                            height:220px;
                                            object-fit:cover;
                                        ">

                                @else

                                    <div
                                        class="rounded border d-flex align-items-center justify-content-center bg-light"
                                        style="
                                            width:100%;
                                            height:220px;
                                        ">

                                        <i
                                            class="ti ti-package"
                                            style="font-size:60px;">
                                        </i>

                                    </div>

                                @endif

                            </div>


                            {{-- Details --}}
                            <div class="col-md-8">

                                <div class="d-flex align-items-start justify-content-between">

                                    <div>

                                        <h3 class="mb-2">

                                            {{ $healthCheckupPackage->name }}

                                        </h3>


                                        @if($healthCheckupPackage->healthCheckup)

                                            <span
                                                class="badge bg-info-subtle text-info">

                                                <i class="ti ti-stethoscope me-1"></i>

                                                {{ $healthCheckupPackage->healthCheckup->name }}

                                            </span>

                                        @endif

                                    </div>


                                    @if($healthCheckupPackage->status)

                                        <span
                                            class="badge bg-success-subtle text-success">

                                            Active

                                        </span>

                                    @else

                                        <span
                                            class="badge bg-danger-subtle text-danger">

                                            Inactive

                                        </span>

                                    @endif

                                </div>


                                @if($healthCheckupPackage->short_description)

                                    <p class="text-muted mt-3 mb-3">

                                        {{ $healthCheckupPackage->short_description }}

                                    </p>

                                @endif


                                <div class="row mt-4">

                                    <div class="col-sm-6 mb-3">

                                        <small class="text-muted d-block">
                                            Package Price
                                        </small>

                                        <h4 class="mb-0 text-primary">

                                            ₹{{ number_format(
                                                $healthCheckupPackage->price,
                                                2
                                            ) }}

                                        </h4>

                                    </div>


                                    @if(
                                        $healthCheckupPackage->mrp >
                                        $healthCheckupPackage->price
                                    )

                                        @php

                                            $discount =
                                                (
                                                    (
                                                        $healthCheckupPackage->mrp -
                                                        $healthCheckupPackage->price
                                                    )
                                                    /
                                                    $healthCheckupPackage->mrp
                                                ) * 100;

                                        @endphp

                                        <div class="col-sm-6 mb-3">

                                            <small class="text-muted d-block">
                                                Original Price
                                            </small>

                                            <div>

                                                <span
                                                    class="text-muted text-decoration-line-through">

                                                    ₹{{ number_format(
                                                        $healthCheckupPackage->mrp,
                                                        2
                                                    ) }}

                                                </span>

                                                <span
                                                    class="badge bg-success-subtle text-success ms-2">

                                                    {{ round($discount) }}% OFF

                                                </span>

                                            </div>

                                        </div>

                                    @endif

                                </div>

                            </div>


                            {{-- Description --}}
                            @if($healthCheckupPackage->description)

                                <div class="col-md-12">

                                    <hr>

                                    <h6 class="mb-2">
                                        Description
                                    </h6>

                                    <div class="text-muted">

                                        {!! nl2br(
                                            e($healthCheckupPackage->description)
                                        ) !!}

                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- Included Tests --}}
                {{-- ===================================================== --}}

                <div class="card">

                    <div class="card-header">

                        <div class="d-flex align-items-center justify-content-between">

                            <div>

                                <h5 class="card-title mb-1">

                                    <i class="ti ti-test-pipe me-2"></i>

                                    Included Tests

                                </h5>

                                <p class="text-muted mb-0">
                                    Tests included in this package.
                                </p>

                            </div>


                            <span
                                class="badge bg-primary-subtle text-primary">

                                {{ $healthCheckupPackage->tests->count() }}

                                {{ $healthCheckupPackage->tests->count() == 1
                                    ? 'Test'
                                    : 'Tests'
                                }}

                            </span>

                        </div>

                    </div>


                    <div class="card-body">

                        @if($healthCheckupPackage->tests->count())

                            <div class="table-responsive">

                                <table class="table table-hover align-middle mb-0">

                                    <thead class="table-light">

                                        <tr>

                                            <th width="70">
                                                #
                                            </th>

                                            <th>
                                                Test
                                            </th>

                                            <th>
                                                Sample Type
                                            </th>

                                            <th>
                                                Report Time
                                            </th>

                                            <th>
                                                Status
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        @foreach(
                                            $healthCheckupPackage->tests
                                            as $test
                                        )

                                            <tr>

                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>


                                                <td>

                                                    <div class="d-flex align-items-center">

                                                        <div
                                                            class="avatar avatar-sm bg-primary-subtle rounded">

                                                            <span
                                                                class="avatar-title text-primary">

                                                                <i class="ti ti-test-pipe"></i>

                                                            </span>

                                                        </div>


                                                        <div class="ms-2">

                                                            <h6 class="mb-0">

                                                                {{ $test->name }}

                                                            </h6>

                                                            @if($test->code)

                                                                <small class="text-muted">

                                                                    {{ $test->code }}

                                                                </small>

                                                            @endif

                                                        </div>

                                                    </div>

                                                </td>


                                                <td>

                                                    @if($test->sample_type)

                                                        <span class="text-muted">

                                                            {{ $test->sample_type }}

                                                        </span>

                                                    @else

                                                        <span class="text-muted">
                                                            —
                                                        </span>

                                                    @endif

                                                </td>


                                                <td>

                                                    @if($test->report_time)

                                                        {{ $test->report_time }}

                                                    @else

                                                        <span class="text-muted">
                                                            —
                                                        </span>

                                                    @endif

                                                </td>


                                                <td>

                                                    @if($test->status)

                                                        <span
                                                            class="badge bg-success-subtle text-success">

                                                            Active

                                                        </span>

                                                    @else

                                                        <span
                                                            class="badge bg-danger-subtle text-danger">

                                                            Inactive

                                                        </span>

                                                    @endif

                                                </td>

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        @else

                            <div class="text-center py-5">

                                <i
                                    class="ti ti-test-pipe-off"
                                    style="font-size:50px;">
                                </i>

                                <h6 class="mt-3">
                                    No tests added
                                </h6>

                                <p class="text-muted mb-0">
                                    No tests have been assigned to this package.
                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- Sidebar --}}
            {{-- ========================================================= --}}

            <div class="col-lg-4">


                {{-- Health Checkup --}}
                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Health Checkup
                        </h5>

                    </div>


                    <div class="card-body">

                        @if($healthCheckupPackage->healthCheckup)

                            <div class="d-flex align-items-center">

                                @if($healthCheckupPackage->healthCheckup->image)

                                    <img
                                        src="{{ asset(
                                            $healthCheckupPackage
                                                ->healthCheckup
                                                ->image
                                        ) }}"
                                        class="rounded"
                                        style="
                                            width:65px;
                                            height:65px;
                                            object-fit:cover;
                                        "
                                        alt="">

                                @else

                                    <div
                                        class="rounded bg-primary-subtle d-flex align-items-center justify-content-center"
                                        style="
                                            width:65px;
                                            height:65px;
                                        ">

                                        <i class="ti ti-heart-rate-monitor fs-3 text-primary"></i>

                                    </div>

                                @endif


                                <div class="ms-3">

                                    <h6 class="mb-1">

                                        {{ $healthCheckupPackage->healthCheckup->name }}

                                    </h6>

                                    @if($healthCheckupPackage->healthCheckup->slug)

                                        <small class="text-muted">

                                            {{ $healthCheckupPackage->healthCheckup->slug }}

                                        </small>

                                    @endif

                                </div>

                            </div>

                        @else

                            <span class="text-muted">
                                No health checkup assigned.
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Pricing Summary --}}
                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Pricing Summary
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Original Price
                            </span>

                            <span>

                                ₹{{ number_format(
                                    $healthCheckupPackage->mrp,
                                    2
                                ) }}

                            </span>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Offer Price
                            </span>

                            <strong>

                                ₹{{ number_format(
                                    $healthCheckupPackage->price,
                                    2
                                ) }}

                            </strong>

                        </div>


                        @if(
                            $healthCheckupPackage->mrp >
                            $healthCheckupPackage->price
                        )

                            @php

                                $saved =
                                    $healthCheckupPackage->mrp -
                                    $healthCheckupPackage->price;

                                $discount =
                                    (
                                        $saved /
                                        $healthCheckupPackage->mrp
                                    ) * 100;

                            @endphp

                            <hr>

                            <div class="d-flex justify-content-between">

                                <span class="text-muted">
                                    You Save
                                </span>

                                <span class="text-success fw-semibold">

                                    ₹{{ number_format($saved, 2) }}

                                    ({{ round($discount) }}% OFF)

                                </span>

                            </div>

                        @endif

                    </div>

                </div>


                {{-- Package Information --}}
                <div class="card">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Package Information
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Slug
                            </small>

                            <span>
                                {{ $healthCheckupPackage->slug ?: '—' }}
                            </span>

                        </div>


                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Display Order
                            </small>

                            <span>
                                {{ $healthCheckupPackage->display_order }}
                            </span>

                        </div>


                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Status
                            </small>

                            @if($healthCheckupPackage->status)

                                <span
                                    class="badge bg-success-subtle text-success">

                                    Active

                                </span>

                            @else

                                <span
                                    class="badge bg-danger-subtle text-danger">

                                    Inactive

                                </span>

                            @endif

                        </div>


                        <div>

                            <small class="text-muted d-block">
                                Created At
                            </small>

                            <span>

                                {{ $healthCheckupPackage->created_at
                                    ? $healthCheckupPackage->created_at->format(
                                        'd M Y, h:i A'
                                    )
                                    : '—'
                                }}

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

@endsection