@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            {{-- =========================================================
            PAGE HEADER
            ========================================================== --}}

            <div class="page-header">

                <div class="add-item d-flex">

                    <div class="page-title">

                        <h4>Procedure Details</h4>

                        <h6>View procedure information and benefits</h6>

                    </div>

                </div>

                <div class="page-btn">

                    <a href="{{ route('admin.procedures.index') }}" class="btn btn-light me-2">

                        <i class="ti ti-arrow-left me-1"></i>

                        Back

                    </a>

                    <a href="{{ route('admin.procedures.edit', $procedure->id) }}" class="btn btn-primary">

                        <i class="ti ti-edit me-1"></i>

                        Edit Procedure

                    </a>

                </div>

            </div>


            {{-- =========================================================
            PROCEDURE OVERVIEW
            ========================================================== --}}

            <div class="row">


                {{-- LEFT SIDE --}}

                <div class="col-xl-4 col-lg-5">

                    <div class="card">

                        <div class="card-body text-center">


                            {{-- IMAGE / ICON --}}

                            <div class="mb-3">

                                @if($procedure->image)

                                    <img src="{{ asset($procedure->image) }}" alt="{{ $procedure->name }}" class="rounded"
                                        style="
                                                                width:160px;
                                                                height:160px;
                                                                object-fit:cover;
                                                             ">

                                @else

                                    <div class="d-inline-flex align-items-center justify-content-center bg-light-primary text-primary rounded"
                                        style="
                                                                width:160px;
                                                                height:160px;
                                                                font-size:60px;
                                                             ">

                                        @if($procedure->icon)

                                            <i class="{{ $procedure->icon }}"></i>

                                        @else

                                            <i class="ti ti-stethoscope"></i>

                                        @endif

                                    </div>

                                @endif

                            </div>


                            {{-- NAME --}}

                            <h4 class="mb-1">

                                {{ $procedure->name }}

                            </h4>


                            {{-- SPECIALIZATION --}}

                            @if($procedure->specialization)

                                <span class="badge bg-light-info text-info">

                                    {{ $procedure->specialization->name }}

                                </span>

                            @endif


                            {{-- STATUS --}}

                            <div class="mt-3">

                                @if($procedure->status)

                                    <span class="badge bg-success">

                                        Active

                                    </span>

                                @else

                                    <span class="badge bg-danger">

                                        Inactive

                                    </span>

                                @endif

                            </div>


                            {{-- PRICE --}}

                            <div class="mt-4">

                                <h3 class="text-primary mb-0">

                                    ₹{{ number_format($procedure->price, 2) }}

                                </h3>

                                <small class="text-muted">

                                    Procedure Price

                                </small>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                    QUICK INFORMATION
                    ================================================== --}}

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">

                                Quick Information

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="mb-3">

                                <small class="text-muted d-block">

                                    Slug

                                </small>

                                <strong>

                                    {{ $procedure->slug ?: '—' }}

                                </strong>

                            </div>


                            <div class="mb-3">

                                <small class="text-muted d-block">

                                    Duration

                                </small>

                                <strong>

                                    {{ $procedure->duration ?: '—' }}

                                </strong>

                            </div>


                            <div class="mb-3">

                                <small class="text-muted d-block">

                                    Hospital Stay

                                </small>

                                <strong>

                                    {{ $procedure->hospital_stay ?: '—' }}

                                </strong>

                            </div>


                            <div class="mb-3">

                                <small class="text-muted d-block">

                                    Recovery

                                </small>

                                <strong>

                                    {{ $procedure->recovery ?: '—' }}

                                </strong>

                            </div>


                            <div>

                                <small class="text-muted d-block">

                                    Display Order

                                </small>

                                <strong>

                                    {{ $procedure->display_order }}

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- RIGHT SIDE --}}

                <div class="col-xl-8 col-lg-7">


                    {{-- =================================================
                    DESCRIPTION
                    ================================================== --}}

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">

                                Procedure Information

                            </h5>

                        </div>

                        <div class="card-body">


                            @if($procedure->short_description)

                                <div class="mb-4">

                                    <h6>

                                        Short Description

                                    </h6>

                                    <p class="text-muted mb-0">

                                        {{ $procedure->short_description }}

                                    </p>

                                </div>

                            @endif


                            @if($procedure->description)

                                <div class="mb-4">

                                    <h6>

                                        Description

                                    </h6>

                                    <p class="text-muted mb-0">

                                        {!! nl2br(e($procedure->description)) !!}

                                    </p>

                                </div>

                            @endif


                            @if($procedure->about)

                                <div>

                                    <h6>

                                        About Procedure

                                    </h6>

                                    <p class="text-muted mb-0">

                                        {!! nl2br(e($procedure->about)) !!}

                                    </p>

                                </div>

                            @endif


                            @if(
                                    !$procedure->short_description &&
                                    !$procedure->description &&
                                    !$procedure->about
                                )

                                <p class="text-muted mb-0">

                                    No description available.

                                </p>

                            @endif

                        </div>

                    </div>

                    {{-- =================================================
                    MEDIA
                    ================================================== --}}

                    <div class="card">

                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Media
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="row">

                                {{-- BANNER --}}
                                <div class="col-md-6 mb-3">

                                    <small class="text-muted d-block mb-2">
                                        Banner
                                    </small>

                                    @if($procedure->banner)

                                            <a href="{{ asset($procedure->banner) }}" target="_blank">

                                                <img src="{{ asset($procedure->banner) }}" alt="{{ $procedure->name }} Banner"
                                                    class="img-fluid rounded" style="
                                            width:100%;
                                            max-height:250px;
                                            object-fit:cover;
                                        ">

                                            </a>

                                    @else

                                                <div class="border rounded p-4 text-center">

                                                    <i class="ti ti-photo-off" style="
                                               font-size:40px;
                                               color:#adb5bd;
                                           ">
                                                    </i>

                                                    <p class="text-muted mb-0 mt-2">
                                                        No banner uploaded.
                                                    </p>

                                                </div>

                                    @endif

                                </div>


                                {{-- YOUTUBE VIDEO --}}
                                <div class="col-md-6 mb-3">

                                    <small class="text-muted d-block mb-2">
                                        YouTube Video
                                    </small>

                                    @if($procedure->youtube_video)

                                        @php
                                            $youtubeId = null;

                                            $url = $procedure->youtube_video;

                                            if (
                                                preg_match(
                                                    '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&?\/]+)/',
                                                    $url,
                                                    $matches
                                                )
                                            ) {
                                                $youtubeId = $matches[1];
                                            }
                                        @endphp


                                        @if($youtubeId)

                                            <div class="ratio ratio-16x9">

                                                <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}" title="YouTube Video"
                                                    allowfullscreen>
                                                </iframe>

                                            </div>

                                        @else

                                                <div class="border rounded p-4 text-center">

                                                    <i class="ti ti-brand-youtube" style="
                                                   font-size:40px;
                                                   color:#adb5bd;
                                               ">
                                                    </i>

                                                    <p class="text-muted mb-2 mt-2">
                                                        Invalid YouTube URL.
                                                    </p>

                                                    <a href="{{ $procedure->youtube_video }}" target="_blank"
                                                        class="btn btn-sm btn-light">

                                                        <i class="ti ti-external-link me-1"></i>
                                                        Open Video

                                                    </a>

                                                </div>

                                        @endif

                                    @else

                                                <div class="border rounded p-4 text-center">

                                                    <i class="ti ti-brand-youtube" style="
                                               font-size:40px;
                                               color:#adb5bd;
                                           ">
                                                    </i>

                                                    <p class="text-muted mb-0 mt-2">
                                                        No YouTube video added.
                                                    </p>

                                                </div>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                    PROCEDURE BENEFITS
                    ================================================== --}}

                    <div class="card">

                        <div class="card-header">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <h5 class="card-title mb-0">

                                        Procedure Benefits

                                    </h5>

                                    <small class="text-muted">

                                        {{ $procedure->benefits->count() }}

                                        benefits

                                    </small>

                                </div>

                            </div>

                        </div>


                        <div class="card-body">


                            @forelse(
                                    $procedure->benefits->sortBy('display_order')
                                    as $benefit
                                )

                                <div class="border rounded p-3 mb-3">

                                    <div class="d-flex align-items-start">


                                        {{-- ICON --}}

                                        <div class="me-3">

                                            <div class="avatar avatar-md bg-light-primary text-primary rounded">

                                                @if($benefit->icon)

                                                    <i class="{{ $benefit->icon }}"></i>

                                                @else

                                                    <i class="ti ti-check"></i>

                                                @endif

                                            </div>

                                        </div>


                                        {{-- CONTENT --}}

                                        <div class="flex-grow-1">

                                            <div class="d-flex justify-content-between">

                                                <h6 class="mb-1">

                                                    {{ $benefit->title }}

                                                </h6>


                                                @if($benefit->status)

                                                    <span class="badge bg-success">

                                                        Active

                                                    </span>

                                                @else

                                                    <span class="badge bg-danger">

                                                        Inactive

                                                    </span>

                                                @endif

                                            </div>


                                            @if($benefit->description)

                                                <p class="text-muted mb-0">

                                                    {{ $benefit->description }}

                                                </p>

                                            @endif

                                        </div>

                                    </div>

                                </div>

                            @empty

                                <div class="text-center py-4">

                                    <i class="ti ti-list-check" style="
                                                              font-size:45px;
                                                              color:#adb5bd;
                                                           ">

                                    </i>

                                    <h6 class="mt-2">

                                        No Benefits Added

                                    </h6>

                                    <p class="text-muted">

                                        No benefits have been configured
                                        for this procedure.

                                    </p>

                                </div>

                            @endforelse

                        </div>

                    </div>


                    {{-- =================================================
                    DOCTORS
                    ================================================== --}}

                    <div class="card">

                        <div class="card-header">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <h5 class="card-title mb-0">

                                        Doctors Following This Procedure

                                    </h5>

                                    <small class="text-muted">

                                        {{ $procedure->doctors->count() }}

                                        doctors

                                    </small>

                                </div>

                            </div>

                        </div>


                        <div class="card-body">

                            @forelse($procedure->doctors as $doctor)

                                <div class="d-flex align-items-center border-bottom py-3">

                                    {{-- DOCTOR IMAGE --}}

                                    <div class="avatar avatar-md me-3">

                                        @if(!empty($doctor->image))

                                            <img src="{{ asset($doctor->image) }}" alt="{{ $doctor->name }}" class="rounded-circle"
                                                style="
                                                                                    width:45px;
                                                                                    height:45px;
                                                                                    object-fit:cover;
                                                                                 ">

                                        @else

                                            <span class="avatar-title bg-light-primary text-primary rounded-circle">

                                                <i class="ti ti-user"></i>

                                            </span>

                                        @endif

                                    </div>


                                    {{-- DOCTOR INFO --}}

                                    <div>

                                        <h6 class="mb-1">

                                            {{ $doctor->doctor_name }}

                                        </h6>

                                        @if(isset($doctor->hospitalSpecialization))

                                            <small class="text-muted">

                                                {{ optional($doctor->hospitalSpecialization)->specialization->specialization_name ?? ''}}

                                            </small>

                                        @endif

                                    </div>

                                </div>

                            @empty

                                <div class="text-center py-4">

                                    <i class="ti ti-user-off" style="
                                                              font-size:40px;
                                                              color:#adb5bd;
                                                           ">

                                    </i>

                                    <p class="text-muted mt-2 mb-0">

                                        No doctors have been assigned
                                        to this procedure.

                                    </p>

                                </div>

                            @endforelse

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection