@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- ========================================================= --}}
        {{-- Page Header --}}
        {{-- ========================================================= --}}

        <div class="d-flex align-items-center justify-content-between mb-4">

            <div>
                <h4 class="mb-1">
                    Add Family Member Health Report
                </h4>

                <p class="text-muted mb-0">
                    Add health checkup report value and percentage.
                </p>
            </div>

            <a href="{{ route('admin.family-member-health-checkups.index') }}"
               class="btn btn-light">

                <i class="ti ti-arrow-left me-1"></i>
                Back

            </a>

        </div>


        {{-- ========================================================= --}}
        {{-- Validation Errors --}}
        {{-- ========================================================= --}}

        @if($errors->any())

            <div class="alert alert-danger alert-dismissible fade show">

                <div class="d-flex">

                    <i class="ti ti-alert-circle me-2 fs-4"></i>

                    <div>

                        <strong>
                            Please fix the following errors:
                        </strong>

                        <ul class="mb-0 mt-2">

                            @foreach($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- Form --}}
        {{-- ========================================================= --}}

        <form method="POST"
              action="{{ route('admin.family-member-health-checkups.store') }}">

            @csrf


            <div class="row">

                {{-- ================================================= --}}
                {{-- Main Form --}}
                {{-- ================================================= --}}

                <div class="col-lg-8">

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-1">
                                Health Checkup Report
                            </h5>

                            <p class="text-muted mb-0">
                                Select the family member and health checkup.
                            </p>

                        </div>


                        <div class="card-body">

                            <div class="row g-3">


                                {{-- ================================= --}}
                                {{-- Family Member --}}
                                {{-- ================================= --}}

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Family Member
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="family_member_id"
                                            id="family_member_id"
                                            class="form-select @error('family_member_id') is-invalid @enderror"
                                            required>

                                        <option value="">
                                            Select Family Member
                                        </option>

                                        @foreach($familyMembers as $familyMember)

                                            <option value="{{ $familyMember->id }}"
                                                {{ old('family_member_id') == $familyMember->id ? 'selected' : '' }}>

                                                {{ $familyMember->name }}

                                                @if($familyMember->customer)

                                                    -
                                                    {{ $familyMember->customer->name }}

                                                @endif

                                            </option>

                                        @endforeach

                                    </select>

                                    @error('family_member_id')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- ================================= --}}
                                {{-- Health Checkup --}}
                                {{-- ================================= --}}

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Health Checkup
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="health_checkup_id"
                                            id="health_checkup_id"
                                            class="form-select @error('health_checkup_id') is-invalid @enderror"
                                            required>

                                        <option value="">
                                            Select Health Checkup
                                        </option>

                                        @foreach($healthCheckups as $healthCheckup)

                                            <option value="{{ $healthCheckup->id }}"
                                                {{ old('health_checkup_id') == $healthCheckup->id ? 'selected' : '' }}>

                                                {{ $healthCheckup->name }}

                                            </option>

                                        @endforeach

                                    </select>

                                    @error('health_checkup_id')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- ================================= --}}
                                {{-- Report Value --}}
                                {{-- ================================= --}}

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Report Value
                                    </label>

                                    <input type="text"
                                           name="report_value"
                                           value="{{ old('report_value') }}"
                                           class="form-control @error('report_value') is-invalid @enderror"
                                           placeholder="Example: 92 mg/dL">

                                    <small class="text-muted">
                                        Enter the actual health report value.
                                    </small>

                                    @error('report_value')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- ================================= --}}
                                {{-- Percentage --}}
                                {{-- ================================= --}}

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Percentage
                                    </label>

                                    <div class="input-group">

                                        <input type="number"
                                               name="percentage"
                                               value="{{ old('percentage') }}"
                                               class="form-control @error('percentage') is-invalid @enderror"
                                               min="0"
                                               max="100"
                                               step="1"
                                               placeholder="0 - 100">

                                        <span class="input-group-text">
                                            %
                                        </span>

                                    </div>

                                    <small class="text-muted">
                                        Enter a value between 0 and 100.
                                    </small>

                                    @error('percentage')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- ================================= --}}
                                {{-- Status --}}
                                {{-- ================================= --}}

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Status
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="status"
                                            class="form-select @error('status') is-invalid @enderror"
                                            required>

                                        <option value="pending"
                                            {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>

                                            Pending

                                        </option>

                                        <option value="completed"
                                            {{ old('status') == 'completed' ? 'selected' : '' }}>

                                            Completed

                                        </option>

                                    </select>

                                    @error('status')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- ================================= --}}
                                {{-- Checked Date --}}
                                {{-- ================================= --}}

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Checked Date
                                    </label>

                                    <input type="date"
                                           name="checked_at"
                                           value="{{ old('checked_at') }}"
                                           class="form-control @error('checked_at') is-invalid @enderror">

                                    @error('checked_at')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- ================================= --}}
                                {{-- Remarks --}}
                                {{-- ================================= --}}

                                <div class="col-12">

                                    <label class="form-label">
                                        Remarks
                                    </label>

                                    <textarea name="remarks"
                                              rows="4"
                                              class="form-control @error('remarks') is-invalid @enderror"
                                              placeholder="Enter any additional remarks...">{{ old('remarks') }}</textarea>

                                    @error('remarks')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- Preview / Information --}}
                {{-- ================================================= --}}

                <div class="col-lg-4">

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Report Information
                            </h5>

                        </div>


                        <div class="card-body">

                            <div class="d-flex align-items-start mb-4">

                                <div class="avatar avatar-lg bg-primary-subtle rounded">

                                    <i class="ti ti-report-medical text-primary fs-3"></i>

                                </div>

                                <div class="ms-3">

                                    <h6 class="mb-1">
                                        Health Report
                                    </h6>

                                    <p class="text-muted mb-0 small">
                                        Add the actual report value and health percentage for the selected family member.
                                    </p>

                                </div>

                            </div>


                            <div class="border rounded p-3 mb-3">

                                <div class="d-flex justify-content-between mb-2">

                                    <span class="text-muted">
                                        Report Value
                                    </span>

                                    <strong id="preview_report_value">
                                        —
                                    </strong>

                                </div>


                                <div class="d-flex justify-content-between">

                                    <span class="text-muted">
                                        Percentage
                                    </span>

                                    <strong id="preview_percentage">
                                        —
                                    </strong>

                                </div>

                            </div>


                            <div class="alert alert-info mb-0">

                                <div class="d-flex">

                                    <i class="ti ti-info-circle me-2 fs-4"></i>

                                    <div class="small">

                                        <strong>
                                            Note
                                        </strong>

                                        <br>

                                        The report value is the actual test result.
                                        The percentage is used for the family member's health score.

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Actions --}}

                    <div class="card">

                        <div class="card-body">

                            <button type="submit"
                                    class="btn btn-primary w-100 mb-2">

                                <i class="ti ti-device-floppy me-1"></i>

                                Save Health Report

                            </button>


                            <a href="{{ route(
                                'admin.family-member-health-checkups.index'
                            ) }}"
                               class="btn btn-light w-100">

                                Cancel

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>
</div>

@endsection


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const reportValue = document.querySelector(
        'input[name="report_value"]'
    );

    const percentage = document.querySelector(
        'input[name="percentage"]'
    );

    const previewReportValue = document.getElementById(
        'preview_report_value'
    );

    const previewPercentage = document.getElementById(
        'preview_percentage'
    );


    if (reportValue && previewReportValue) {

        reportValue.addEventListener('input', function () {

            previewReportValue.textContent =
                this.value.trim() || '—';

        });

    }


    if (percentage && previewPercentage) {

        percentage.addEventListener('input', function () {

            previewPercentage.textContent =
                this.value !== ''
                    ? this.value + '%'
                    : '—';

        });

    }

});

</script>

@endpush