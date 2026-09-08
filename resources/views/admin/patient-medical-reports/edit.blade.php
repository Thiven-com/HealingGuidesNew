
@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            {{-- Page Header --}}
            <div class="page-header">

                <div class="row align-items-center">

                    <div class="col">

                        <h4 class="page-title">
                            Edit Medical Report
                        </h4>

                        <p class="text-muted mb-0">
                            Update patient medical report
                        </p>

                    </div>


                    <div class="col-auto">

                        <a href="{{ route(
                            'admin.patient-medical-reports.show',
                            $report->id
                        ) }}"
                           class="btn btn-light">

                            <i class="ti ti-arrow-left"></i>
                            Back

                        </a>

                    </div>

                </div>

            </div>


            {{-- Validation Errors --}}
            @if($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- Edit Form --}}
            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-0">
                        Medical Report Information
                    </h5>

                </div>


                <div class="card-body">

                    <form action="{{ route(
                        'admin.patient-medical-reports.update',
                        $report->id
                    ) }}"
                          method="POST"
                          enctype="multipart/form-data">

                        @csrf

                        @method('PUT')


                        <div class="row g-3">


                            {{-- Patient --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Patient
                                </label>

                                <select name="customer_id"
                                        class="form-select">

                                    <option value="">
                                        Select Patient
                                    </option>


                                    @foreach($customers as $customer)

                                        <option value="{{ $customer->id }}"
                                            {{ old(
                                                'customer_id',
                                                $report->customer_id
                                            ) == $customer->id
                                                ? 'selected'
                                                : '' }}>

                                            {{ $customer->name }}

                                        </option>

                                    @endforeach

                                </select>


                                @error('customer_id')

                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>

                                @enderror

                            </div>


                            {{-- Family Member --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Family Member
                                </label>

                                <select name="family_member_id"
                                        class="form-select">

                                    <option value="">
                                        Select Family Member
                                    </option>


                                    @foreach($familyMembers as $member)

                                        <option value="{{ $member->id }}"
                                            {{ old(
                                                'family_member_id',
                                                $report->family_member_id
                                            ) == $member->id
                                                ? 'selected'
                                                : '' }}>

                                            {{ $member->name }}

                                        </option>

                                    @endforeach

                                </select>


                                @error('family_member_id')

                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>

                                @enderror

                            </div>


                            {{-- Doctor --}}
                            {{-- <div class="col-md-6">

                                <label class="form-label">
                                    Doctor
                                </label>

                                <select name="doctor_id"
                                        class="form-select">

                                    <option value="">
                                        Select Doctor
                                    </option>


                                    @foreach($doctors as $doctor)

                                        <option value="{{ $doctor->id }}"
                                            {{ old(
                                                'doctor_id',
                                                $report->doctor_id
                                            ) == $doctor->id
                                                ? 'selected'
                                                : '' }}>

                                            {{ $doctor->doctor_name }}

                                        </option>

                                    @endforeach

                                </select>


                                @error('doctor_id')

                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>

                                @enderror

                            </div> --}}


                            {{-- Appointment --}}
                            {{-- <div class="col-md-6">

                                <label class="form-label">
                                    Appointment
                                </label>

                                <select name="appointment_id"
                                        class="form-select">

                                    <option value="">
                                        Select Appointment
                                    </option>


                                    @foreach($appointments as $appointment)

                                        <option value="{{ $appointment->id }}"
                                            {{ old(
                                                'appointment_id',
                                                $report->appointment_id
                                            ) == $appointment->id
                                                ? 'selected'
                                                : '' }}>

                                            {{ $appointment->appointment_no }}

                                        </option>

                                    @endforeach

                                </select>


                                @error('appointment_id')

                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>

                                @enderror

                            </div> --}}


                            {{-- Report Type --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Report Type
                                </label>

                                <input type="text"
                                       name="report_type"
                                       class="form-control"
                                       value="{{ old(
                                           'report_type',
                                           $report->report_type
                                       ) }}"
                                       placeholder="Blood Test, X-Ray, Scan...">


                                @error('report_type')

                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>

                                @enderror

                            </div>


                            {{-- Report Name --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Report Name
                                </label>

                                <input type="text"
                                       name="report_name"
                                       class="form-control"
                                       value="{{ old(
                                           'report_name',
                                           $report->report_name
                                       ) }}"
                                       placeholder="Enter report name">


                                @error('report_name')

                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>

                                @enderror

                            </div>


                            {{-- Report Date --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Report Date
                                </label>

                                <input type="date"
                                       name="report_date"
                                       class="form-control"
                                       value="{{ old(
                                           'report_date',
                                           $report->report_date
                                               ? \Carbon\Carbon::parse(
                                                   $report->report_date
                                               )->format('Y-m-d')
                                               : ''
                                       ) }}">


                                @error('report_date')

                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>

                                @enderror

                            </div>


                            {{-- Report File --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Report File
                                </label>

                                <input type="file"
                                       name="report_file"
                                       class="form-control"
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">


                                <small class="text-muted">
                                    PDF, JPG, JPEG, PNG, DOC or DOCX. Max 10MB.
                                </small>


                                {{-- Current File --}}
                                @if($report->report_file)

                                    <div class="mt-3">

                                        <div class="text-muted mb-2">
                                            Current File
                                        </div>


                                        <a href="{{ asset(
                                            'storage/' . $report->report_file
                                        ) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-outline-primary">

                                            <i class="ti ti-file"></i>
                                            View Current File

                                        </a>

                                    </div>

                                @endif


                                @error('report_file')

                                    <small class="text-danger d-block">
                                        {{ $message }}
                                    </small>

                                @enderror

                            </div>


                            {{-- Notes --}}
                            <div class="col-12">

                                <label class="form-label">
                                    Notes
                                </label>

                                <textarea name="notes"
                                          rows="5"
                                          class="form-control"
                                          placeholder="Enter notes">{{ old(
                                              'notes',
                                              $report->notes
                                          ) }}</textarea>


                                @error('notes')

                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>

                                @enderror

                            </div>


                            {{-- Buttons --}}
                            <div class="col-12 mt-4">

                                <button type="submit"
                                        class="btn btn-primary me-2">

                                    <i class="ti ti-device-floppy"></i>
                                    Update Report

                                </button>


                                <a href="{{ route(
                                    'admin.patient-medical-reports.show',
                                    $report->id
                                ) }}"
                                   class="btn btn-light">

                                    Cancel

                                </a>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>

@endsection
