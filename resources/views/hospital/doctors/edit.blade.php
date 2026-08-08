<?php $page = 'hospital-doctors'; ?>

@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            {{-- Page Header --}}
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Edit Doctor</h4>
                        <h6>Update doctor information</h6>
                    </div>
                </div>

                <div class="page-btn">
                    <a href="{{ route('hospital.doctors.index') }}" class="btn btn-secondary">

                        <i class="ti ti-arrow-left me-1"></i>
                        Back to Doctors

                    </a>
                </div>
            </div>


            {{-- Validation Errors --}}
            @if($errors->any())

                <div class="alert alert-danger alert-dismissible fade show">

                    <div class="d-flex align-items-center">

                        <i class="ti ti-alert-circle me-2"></i>

                        <div>
                            <strong>Please check the form.</strong>
                            <div>{{ $errors->first() }}</div>
                        </div>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            <form action="{{ route('hospital.doctors.update', $doctor->id) }}" method="POST" enctype="multipart/form-data">

                @csrf
                @method('PUT')


                {{-- =====================================================
                BASIC INFORMATION
                ====================================================== --}}

                <div class="card">

                    <div class="card-header">

                        <div class="d-flex align-items-center">

                            <span class="avatar avatar-sm bg-primary-transparent me-2">
                                <i class="ti ti-user text-primary"></i>
                            </span>

                            <div>

                                <h5 class="card-title mb-0">
                                    Basic Information
                                </h5>

                                <small class="text-muted">
                                    Update doctor's basic details
                                </small>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            {{-- Doctor Photo --}}
                            <div class="col-lg-6">

                                <div class="mb-4">

                                    <label class="form-label">
                                        Doctor Photo
                                    </label>

                                    <div class="d-flex align-items-center">

                                        <div class="me-3">

                                            <img id="photoPreview" src="{{ $doctor->photo
        ? asset($doctor->photo)
        : asset('build/img/profiles/avator1.jpg') }}" alt="{{ $doctor->doctor_name }}" class="rounded-circle border"
                                                width="90" height="90" style="object-fit:cover;">

                                        </div>


                                        <div class="flex-grow-1">

                                            <input type="file" name="photo" id="photo"
                                                class="form-control @error('photo') is-invalid @enderror"
                                                accept=".jpg,.jpeg,.png,.webp">

                                            <small class="text-muted">
                                                Leave empty to keep existing photo.
                                                JPG, JPEG, PNG or WEBP. Maximum 5MB.
                                            </small>

                                            @error('photo')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </div>

                                    </div>

                                </div>

                            </div>



                            {{-- Certificate --}}
                            <div class="col-lg-6">

                                <div class="mb-4">

                                    <label class="form-label">
                                        Doctor Certificate
                                    </label>

                                    @if($doctor->certificate)

                                        <div class="mb-2">

                                            <a href="{{ asset($doctor->certificate) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">

                                                <i class="ti ti-file-certificate me-1"></i>

                                                View Current Certificate

                                            </a>

                                        </div>

                                    @endif


                                    <input type="file" name="certificate"
                                        class="form-control @error('certificate') is-invalid @enderror"
                                        accept=".jpg,.jpeg,.png,.pdf">

                                    <small class="text-muted">
                                        Leave empty to keep existing certificate.
                                        JPG, JPEG, PNG or PDF. Maximum 5MB.
                                    </small>

                                    @error('certificate')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>



                            {{-- Doctor Name --}}
                            <div class="col-lg-4 col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Doctor Name

                                        <span class="text-danger">*</span>

                                    </label>

                                    <input type="text" name="doctor_name"
                                        class="form-control @error('doctor_name') is-invalid @enderror"
                                        value="{{ old('doctor_name', $doctor->doctor_name) }}"
                                        placeholder="Enter doctor name">

                                    @error('doctor_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>



                            {{-- Specialization --}}
                            <div class="col-lg-4 col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Specialization

                                        <span class="text-danger">*</span>

                                    </label>

                                   <select name="hospital_specialization_id"
                                        class="form-select @error('hospital_specialization_id') is-invalid @enderror">

                                        <option value="">
                                            Select Specialization
                                        </option>

                                        @foreach($specializations as $hospitalSpecialization)

                                        <option value="{{ $hospitalSpecialization->id }}" {{
                                            old( 'hospital_specialization_id' , $doctor->hospital_specialization_id
                                            ) == $hospitalSpecialization->id ? 'selected' : '' }}
                                            >

                                            {{ $hospitalSpecialization->specialization->specialization_name ?? '-' }}

                                        </option>

                                        @endforeach

                                    </select>

                                    @error('hospital_specialization_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>



                            {{-- Qualification --}}
                            <div class="col-lg-4 col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Qualification

                                        <span class="text-danger">*</span>

                                    </label>

                                    <input type="text" name="qualification"
                                        class="form-control @error('qualification') is-invalid @enderror"
                                        value="{{ old('qualification', $doctor->qualification) }}"
                                        placeholder="Ex: MBBS, MD">

                                    @error('qualification')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>



                            {{-- Designation --}}
                            <div class="col-lg-4 col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Designation
                                    </label>

                                    <input type="text" name="designation" class="form-control"
                                        value="{{ old('designation', $doctor->designation) }}"
                                        placeholder="Ex: Senior Consultant">

                                </div>

                            </div>



                            {{-- Experience --}}
                            <div class="col-lg-4 col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Experience
                                    </label>

                                    <div class="input-group">

                                        <input type="number" name="experience" min="0" max="100" class="form-control"
                                            value="{{ old('experience', $doctor->experience) }}" placeholder="0">

                                        <span class="input-group-text">
                                            Years
                                        </span>

                                    </div>

                                </div>

                            </div>



                            {{-- Gender --}}
                            <div class="col-lg-4 col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Gender
                                    </label>

                                    <select name="gender" class="form-select">

                                        <option value="">
                                            Select Gender
                                        </option>

                                        <option value="male" {{ old('gender', $doctor->gender) == 'male' ? 'selected' : ''
                                            }}
                                            >
                                            Male
                                        </option>

                                        <option value="female" {{ old('gender', $doctor->gender) == 'female' ? 'selected' :
                                            '' }}
                                            >
                                            Female
                                        </option>

                                        <option value="other" {{ old('gender', $doctor->gender) == 'other' ? 'selected' : ''
                                            }}
                                            >
                                            Other
                                        </option>

                                    </select>

                                </div>

                            </div>



                            {{-- DOB --}}
                            <div class="col-lg-4 col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Date of Birth
                                    </label>

                                    <input type="date" name="dob" class="form-control"
                                        value="{{ old('dob', $doctor->dob ? \Carbon\Carbon::parse($doctor->dob)->format('Y-m-d') : '') }}"
                                        max="{{ date('Y-m-d') }}">

                                </div>

                            </div>



                            {{-- Blood Group --}}
                            <div class="col-lg-4 col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Blood Group
                                    </label>

                                    <select name="blood_group" class="form-select">

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

                                                                            <option value="{{ $bloodGroup }}" {{ old(
                                                'blood_group',
                                                $doctor->blood_group
                                            ) == $bloodGroup ? 'selected' : '' }}>

                                                                                {{ $bloodGroup }}

                                                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>


                            {{-- Doctor Code --}}
                            <div class="col-lg-4 col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Doctor Code
                                    </label>

                                    <input type="text" class="form-control" value="{{ $doctor->doctor_code }}" readonly>

                                    <small class="text-muted">
                                        Doctor code cannot be changed.
                                    </small>

                                </div>

                            </div>


                        </div>

                    </div>

                </div>



                {{-- =====================================================
                CONTACT INFORMATION
                ====================================================== --}}

                <div class="card">

                    <div class="card-header">

                        <div class="d-flex align-items-center">

                            <span class="avatar avatar-sm bg-success-transparent me-2">
                                <i class="ti ti-phone text-success"></i>
                            </span>

                            <div>

                                <h5 class="card-title mb-0">
                                    Contact Information
                                </h5>

                                <small class="text-muted">
                                    Doctor's contact and address details
                                </small>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            {{-- Mobile --}}
                            <div class="col-lg-6">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Mobile Number

                                        <span class="text-danger">*</span>

                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            +91
                                        </span>

                                        <input type="text" name="mobile" maxlength="10" inputmode="numeric"
                                            class="form-control @error('mobile') is-invalid @enderror"
                                            value="{{ old('mobile', $doctor->mobile) }}"
                                            placeholder="Enter 10 digit mobile number">

                                    </div>

                                    @error('mobile')
                                        <div class="text-danger fs-12 mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>



                            {{-- Email --}}
                            <div class="col-lg-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Email Address
                                    </label>

                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $doctor->email) }}" placeholder="doctor@example.com">

                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>



                            {{-- Address --}}
                            <div class="col-12">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Address
                                    </label>

                                    <textarea name="address" rows="3" class="form-control"
                                        placeholder="Enter address">{{ old('address', $doctor->address) }}</textarea>

                                </div>

                            </div>


                        </div>

                    </div>

                </div>



                {{-- =====================================================
                CONSULTATION FEES
                ====================================================== --}}

                <div class="card">

                    <div class="card-header">

                        <div class="d-flex align-items-center">

                            <span class="avatar avatar-sm bg-warning-transparent me-2">
                                <i class="ti ti-currency-rupee text-warning"></i>
                            </span>

                            <div>

                                <h5 class="card-title mb-0">
                                    Consultation Fees
                                </h5>

                                <small class="text-muted">
                                    Configure doctor's consultation charges
                                </small>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            {{-- Consultation Fee --}}
                            <div class="col-xl-3 col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Consultation Fee
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            ₹
                                        </span>

                                        <input type="number" name="consultation_fee" min="0" step="0.01"
                                            class="form-control" value="{{ old(
        'consultation_fee',
        $doctor->consultation_fee ?? 0
    ) }}">

                                    </div>

                                </div>

                            </div>



                            {{-- Video Fee --}}
                            <div class="col-xl-3 col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Video Consultation
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            ₹
                                        </span>

                                        <input type="number" name="video_consultation_fee" min="0" step="0.01"
                                            class="form-control" value="{{ old(
        'video_consultation_fee',
        $doctor->video_consultation_fee ?? 0
    ) }}">

                                    </div>

                                </div>

                            </div>



                            {{-- Chat Fee --}}
                            <div class="col-xl-3 col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Chat Consultation
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            ₹
                                        </span>

                                        <input type="number" name="chat_consultation_fee" min="0" step="0.01"
                                            class="form-control" value="{{ old(
        'chat_consultation_fee',
        $doctor->chat_consultation_fee ?? 0
    ) }}">

                                    </div>

                                </div>

                            </div>



                            {{-- Home Visit --}}
                            <div class="col-xl-3 col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Home Visit Fee
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            ₹
                                        </span>

                                        <input type="number" name="home_visit_fee" min="0" step="0.01" class="form-control"
                                            value="{{ old(
        'home_visit_fee',
        $doctor->home_visit_fee ?? 0
    ) }}">

                                    </div>

                                </div>

                            </div>


                        </div>

                    </div>

                </div>



                {{-- =====================================================
                AVAILABILITY
                ====================================================== --}}

                <div class="card">

                    <div class="card-header">

                        <div class="d-flex align-items-center">

                            <span class="avatar avatar-sm bg-info-transparent me-2">
                                <i class="ti ti-clock text-info"></i>
                            </span>

                            <div>

                                <h5 class="card-title mb-0">
                                    Availability
                                </h5>

                                <small class="text-muted">
                                    Set doctor's general available time
                                </small>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Available From
                                    </label>

                                    <input type="time" name="available_from" class="form-control" value="{{ old(
        'available_from',
        $doctor->available_from
        ? substr($doctor->available_from, 0, 5)
        : ''
    ) }}">

                                </div>

                            </div>



                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Available To
                                    </label>

                                    <input type="time" name="available_to" class="form-control" value="{{ old(
        'available_to',
        $doctor->available_to
        ? substr($doctor->available_to, 0, 5)
        : ''
    ) }}">

                                </div>

                            </div>


                        </div>

                    </div>

                </div>



                {{-- =====================================================
                ABOUT DOCTOR
                ====================================================== --}}

                <div class="card">

                    <div class="card-header">

                        <div class="d-flex align-items-center">

                            <span class="avatar avatar-sm bg-purple-transparent me-2">
                                <i class="ti ti-notes text-purple"></i>
                            </span>

                            <div>

                                <h5 class="card-title mb-0">
                                    About Doctor
                                </h5>

                                <small class="text-muted">
                                    Update professional information
                                </small>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        <textarea name="about" rows="5" class="form-control"
                            placeholder="Write about doctor's experience, expertise and professional background...">{{ old('about', $doctor->about) }}</textarea>

                    </div>

                </div>



                {{-- =====================================================
                ACTIONS
                ====================================================== --}}

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('hospital.doctors.index') }}" class="btn btn-light">

                                Cancel

                            </a>

                            <button type="submit" class="btn btn-primary">

                                <i class="ti ti-device-floppy me-1"></i>

                                Update Doctor

                            </button>

                        </div>

                    </div>

                </div>


            </form>

        </div>
    </div>



    {{-- =====================================================
    PHOTO PREVIEW
    ====================================================== --}}

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const photoInput =
                document.getElementById('photo');

            const photoPreview =
                document.getElementById('photoPreview');


            if (photoInput && photoPreview) {

                photoInput.addEventListener('change', function (event) {

                    const file =
                        event.target.files[0];

                    if (!file) {
                        return;
                    }


                    const allowedTypes = [
                        'image/jpeg',
                        'image/png',
                        'image/webp'
                    ];


                    if (!allowedTypes.includes(file.type)) {

                        alert(
                            'Please select JPG, JPEG, PNG or WEBP image.'
                        );

                        this.value = '';

                        return;
                    }


                    const reader =
                        new FileReader();


                    reader.onload = function (e) {

                        photoPreview.src =
                            e.target.result;

                    };


                    reader.readAsDataURL(file);

                });

            }

        });

    </script>

@endsection