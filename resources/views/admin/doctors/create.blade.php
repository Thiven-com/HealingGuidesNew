<?php $page = 'doctors'; ?>

@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">

    <div class="content">

        <!-- Page Header -->
        <div class="page-header">

            <div class="add-item d-flex">

                <div class="page-title">

                    <h4>Add Doctor</h4>

                    <h6>Create New Doctor</h6>

                </div>

            </div>

            <ul class="table-top-head">

                <li>
                    <a href="{{ route('admin.doctors.index') }}"
                       data-bs-toggle="tooltip"
                       title="Back">

                        <i data-feather="arrow-left"></i>

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

        </div>
        <!-- /Page Header -->


        {{-- Validation Errors --}}
        @if($errors->any())

            <div class="alert alert-danger alert-dismissible fade show">

                <strong>Please fix the following errors:</strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        <form action="{{ route('admin.doctors.store') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <div class="row">

                <!-- Doctor Information -->
                <div class="col-lg-8">

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Doctor Information
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                {{-- Hospital --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Hospital
                                        {{-- <span class="text-danger">*</span> --}}
                                    </label>

                                    <select name="hospital_id"
                                            class="form-select"
                                            >

                                        <option value="0">
                                            Select Hospital
                                        </option>

                                        @foreach($hospitals as $hospital)

                                            <option value="{{ $hospital->id }}"
                                                {{ old('hospital_id') == $hospital->id ? 'selected' : '' }}>

                                                {{ $hospital->hospital_name }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                {{-- Specialization --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Specialization
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="hospital_specialization_id"
                                            class="form-select"
                                            required>

                                        <option value="">
                                            Select Specialization
                                        </option>

                                        @foreach($specializations as $hospitalSpecialization)

                                            <option value="{{ $hospitalSpecialization->id }}"
                                                {{ old('hospital_specialization_id') == $hospitalSpecialization->id ? 'selected' : '' }}>

                                                {{ optional($hospitalSpecialization->specialization)->specialization_name ?? 'Specialization #' . $hospitalSpecialization->id }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                {{-- Doctor Name --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Doctor Name
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           name="doctor_name"
                                           class="form-control"
                                           value="{{ old('doctor_name') }}"
                                           placeholder="Enter doctor name"
                                           required>

                                </div>


                                {{-- Designation --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Designation
                                    </label>

                                    <input type="text"
                                           name="designation"
                                           class="form-control"
                                           value="{{ old('designation') }}"
                                           placeholder="Example: Senior Consultant">

                                </div>


                                {{-- Qualification --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Qualification
                                    </label>

                                    <input type="text"
                                           name="qualification"
                                           class="form-control"
                                           value="{{ old('qualification') }}"
                                           placeholder="Example: MBBS, MD">

                                </div>


                                {{-- Experience --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Experience
                                    </label>

                                    <div class="input-group">

                                        <input type="number"
                                               name="experience"
                                               class="form-control"
                                               value="{{ old('experience') }}"
                                               min="0"
                                               step="0.1"
                                               placeholder="Years">

                                        <span class="input-group-text">
                                            Years
                                        </span>

                                    </div>

                                </div>


                                {{-- Email --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Email
                                    </label>

                                    <input type="email"
                                           name="email"
                                           class="form-control"
                                           value="{{ old('email') }}"
                                           placeholder="doctor@example.com">

                                </div>


                                {{-- Mobile --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Mobile
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           name="mobile"
                                           class="form-control"
                                           value="{{ old('mobile') }}"
                                           placeholder="Enter mobile number"
                                           required>

                                </div>


                                {{-- DOB --}}
                                <div class="col-md-4 mb-3">

                                    <label class="form-label">
                                        Date of Birth
                                    </label>

                                    <input type="date"
                                           name="dob"
                                           class="form-control"
                                           value="{{ old('dob') }}">

                                </div>


                                {{-- Gender --}}
                                <div class="col-md-4 mb-3">

                                    <label class="form-label">
                                        Gender
                                    </label>

                                    <select name="gender"
                                            class="form-select">

                                        <option value="">
                                            Select Gender
                                        </option>

                                        <option value="male"
                                            {{ old('gender') == 'male' ? 'selected' : '' }}>
                                            Male
                                        </option>

                                        <option value="female"
                                            {{ old('gender') == 'female' ? 'selected' : '' }}>
                                            Female
                                        </option>

                                        <option value="other"
                                            {{ old('gender') == 'other' ? 'selected' : '' }}>
                                            Other
                                        </option>

                                    </select>

                                </div>


                                {{-- Blood Group --}}
                                <div class="col-md-4 mb-3">

                                    <label class="form-label">
                                        Blood Group
                                    </label>

                                    <select name="blood_group"
                                            class="form-select">

                                        <option value="">
                                            Select Blood Group
                                        </option>

                                        @foreach([
                                            'A+',
                                            'A-',
                                            'B+',
                                            'B-',
                                            'AB+',
                                            'AB-',
                                            'O+',
                                            'O-'
                                        ] as $bloodGroup)

                                            <option value="{{ $bloodGroup }}"
                                                {{ old('blood_group') == $bloodGroup ? 'selected' : '' }}>

                                                {{ $bloodGroup }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                {{-- Address --}}
                                <div class="col-md-12 mb-3">

                                    <label class="form-label">
                                        Address
                                    </label>

                                    <textarea name="address"
                                              class="form-control"
                                              rows="3"
                                              placeholder="Enter doctor address">{{ old('address') }}</textarea>

                                </div>


                                {{-- About --}}
                                <div class="col-md-12 mb-3">

                                    <label class="form-label">
                                        About Doctor
                                    </label>

                                    <textarea name="about"
                                              class="form-control"
                                              rows="4"
                                              placeholder="Enter doctor profile/about">{{ old('about') }}</textarea>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- Consultation Fees -->
                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Consultation Fees
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                {{-- Consultation --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Consultation Fee
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            ₹
                                        </span>

                                        <input type="number"
                                               name="consultation_fee"
                                               class="form-control"
                                               value="{{ old('consultation_fee', 0) }}"
                                               min="0"
                                               step="0.01"
                                               placeholder="0.00"
                                               required>

                                    </div>

                                </div>


                                {{-- Video --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Video Consultation Fee
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            ₹
                                        </span>

                                        <input type="number"
                                               name="video_consultation_fee"
                                               class="form-control"
                                               value="{{ old('video_consultation_fee', 0) }}"
                                               min="0"
                                               step="0.01"
                                               placeholder="0.00">

                                    </div>

                                </div>


                                {{-- Chat --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Chat Consultation Fee
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            ₹
                                        </span>

                                        <input type="number"
                                               name="chat_consultation_fee"
                                               class="form-control"
                                               value="{{ old('chat_consultation_fee', 0) }}"
                                               min="0"
                                               step="0.01"
                                               placeholder="0.00">

                                    </div>

                                </div>


                                {{-- Home Visit --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Home Visit Fee
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            ₹
                                        </span>

                                        <input type="number"
                                               name="home_visit_fee"
                                               class="form-control"
                                               value="{{ old('home_visit_fee', 0) }}"
                                               min="0"
                                               step="0.01"
                                               placeholder="0.00">

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- Availability -->
                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Availability
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Available From
                                    </label>

                                    <input type="time"
                                           name="available_from"
                                           class="form-control"
                                           value="{{ old('available_from') }}">

                                </div>


                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Available To
                                    </label>

                                    <input type="time"
                                           name="available_to"
                                           class="form-control"
                                           value="{{ old('available_to') }}">

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- Right Side -->
                <div class="col-lg-4">

                    <!-- Doctor Photo -->
                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Doctor Photo
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="text-center mb-3">

                                <div id="photoPreview"
                                     class="mb-3">

                                    <img src="{{ asset('assets/img/no-image.png') }}"
                                         class="img-fluid rounded"
                                         style="width:180px;height:180px;object-fit:cover;"
                                         alt="Doctor Photo">

                                </div>

                            </div>

                            <label class="form-label">
                                Upload Photo
                            </label>

                            <input type="file"
                                   name="photo"
                                   id="photo"
                                   class="form-control"
                                   accept="image/jpeg,image/png,image/webp">

                            <small class="text-muted">
                                JPG, JPEG, PNG or WEBP. Maximum 5MB.
                            </small>

                        </div>

                    </div>


                    <!-- Certificate -->
                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Certificate
                            </h5>

                        </div>

                        <div class="card-body">

                            <label class="form-label">
                                Upload Certificate
                            </label>

                            <input type="file"
                                   name="certificate"
                                   class="form-control"
                                   accept=".jpg,.jpeg,.png,.pdf">

                            <small class="text-muted">
                                JPG, JPEG, PNG or PDF. Maximum 5MB.
                            </small>

                        </div>

                    </div>


                    <!-- Status -->
                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">
                                Status
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="form-check form-switch">

                                <input class="form-check-input"
                                       type="checkbox"
                                       name="status"
                                       value="1"
                                       id="status"
                                       {{ old('status', 1) ? 'checked' : '' }}>

                                <label class="form-check-label"
                                       for="status">

                                    Active

                                </label>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Form Buttons -->
            <div class="row">

                <div class="col-lg-12">

                    <div class="text-end mb-4">

                        <a href="{{ route('admin.doctors.index') }}"
                           class="btn btn-light me-2">

                            Cancel

                        </a>

                        <button type="submit"
                                class="btn btn-primary">

                            <i data-feather="save"
                               class="me-1"></i>

                            Save Doctor

                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>


<script>

document.addEventListener("DOMContentLoaded", function () {

    if (typeof feather !== "undefined") {
        feather.replace();
    }


    /*
    |--------------------------------------------------------------------------
    | Doctor Photo Preview
    |--------------------------------------------------------------------------
    */

    const photoInput = document.getElementById('photo');

    if (photoInput) {

        photoInput.addEventListener('change', function (event) {

            const file = event.target.files[0];

            if (!file) {
                return;
            }

            const reader = new FileReader();

            reader.onload = function (e) {

                document.querySelector('#photoPreview img').src =
                    e.target.result;

            };

            reader.readAsDataURL(file);

        });

    }

});

</script>

@endsection