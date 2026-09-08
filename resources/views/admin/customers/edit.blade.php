@extends('layout.mainlayout')

@section('content')

<style>
    .edit-profile-card {
        border: 0;
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, .06);
    }

    .edit-profile-header {
        background: #6d28d9;
        color: #fff;
        padding: 18px 22px;
        border-radius: 18px 18px 0 0;
    }

    .edit-profile-header h5 {
        margin: 0;
        font-weight: 600;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
    }

    .form-control,
    .form-select {
        min-height: 45px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #6d28d9;
        box-shadow: 0 0 0 .15rem rgba(109, 40, 217, .12);
    }

    .btn-purple {
        background: #6d28d9;
        border-color: #6d28d9;
        color: #fff;
        border-radius: 8px;
        padding: 9px 20px;
        font-weight: 600;
    }

    .btn-purple:hover {
        background: #5b21b6;
        border-color: #5b21b6;
        color: #fff;
    }

    .profile-image-preview {
        width: 100px;
        height: 100px;
        border-radius: 15px;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 3px 15px rgba(0, 0, 0, .12);
    }

    .default-profile-image {
        width: 100px;
        height: 100px;
        border-radius: 15px;
        background: #6d28d9;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 35px;
    }

    .section-title {
        color: #6d28d9;
        font-size: 16px;
        font-weight: 700;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    @media(max-width: 768px) {
        .edit-profile-card {
            border-radius: 12px;
        }

        .edit-profile-header {
            border-radius: 12px 12px 0 0;
        }
    }
</style>

<div class="page-wrapper">

<div class="content">

    {{-- ================= HEADER ================= --}}

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">

        <div>
            <h4 class="mb-1">Edit Customer Profile</h4>

            <p class="text-muted mb-0">
                Update customer information
            </p>
        </div>

        <div>
            <a href="{{ route('admin.customers.show', [
                'id' => $customer->id,
                'slug' => 'profile'
            ]) }}"
               class="btn btn-light border">

                <i class="ti ti-arrow-left me-1"></i>
                Back
            </a>
        </div>

    </div>


    {{-- ================= EDIT FORM ================= --}}

    <div class="card edit-profile-card">

        <div class="edit-profile-header">

            <h5>
                <i class="ti ti-user-edit me-2"></i>
                Customer Information
            </h5>

        </div>


        <div class="card-body p-4">

            @if(session('success'))

                <div class="alert alert-success">
                    {{ session('success') }}
                </div>

            @endif


            @if($errors->any())

                <div class="alert alert-danger">

                    <strong>Please fix the following errors:</strong>

                    <ul class="mb-0 mt-2">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <form action="{{ route('admin.customers.update', $customer->id) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')


                {{-- ================= PROFILE PHOTO ================= --}}

                <div class="section-title">
                    Profile Photo
                </div>

                <div class="row mb-4">

                    <div class="col-md-6">

                        <div class="d-flex align-items-center gap-3">

                            <div>

                                @if(!empty($customer->photo) &&
                                    file_exists(public_path($customer->photo)))

                                    <img src="{{ asset($customer->photo) }}"
                                         class="profile-image-preview"
                                         id="profilePreview">

                                @else

                                    <div class="default-profile-image"
                                         id="profilePlaceholder">

                                        <i class="fa fa-user"></i>

                                    </div>

                                @endif

                            </div>

                            <div class="flex-grow-1">

                                <label class="form-label">
                                    Change Photo
                                </label>

                                <input type="file"
                                       name="photo"
                                       class="form-control"
                                       accept="image/*"
                                       onchange="previewProfileImage(this)">

                                <small class="text-muted">
                                    JPG, JPEG or PNG
                                </small>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================= BASIC INFORMATION ================= --}}

                <div class="section-title">
                    Basic Information
                </div>


                <div class="row">

                    {{-- Full Name --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Full Name <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name', $customer->name) }}"
                               placeholder="Enter full name"
                               required>

                    </div>


                    {{-- Customer Code --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Customer Code
                        </label>

                        <input type="text"
                               class="form-control bg-light"
                               value="{{ $customer->customer_code ?? '' }}"
                               readonly>

                    </div>


                    {{-- Email --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Email <span class="text-danger">*</span>
                        </label>

                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email', $customer->email) }}"
                               placeholder="Enter email"
                               required>

                    </div>


                    {{-- Mobile --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Mobile Number <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="mobile"
                               class="form-control"
                               value="{{ old('mobile', $customer->mobile) }}"
                               placeholder="Enter mobile number"
                               required>

                    </div>


                    {{-- Gender --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Gender
                        </label>

                        <select name="gender" class="form-select">

                            <option value="">
                                Select Gender
                            </option>

                            <option value="Male"
                                {{ old('gender', $customer->gender) == 'Male' ? 'selected' : '' }}>
                                Male
                            </option>

                            <option value="Female"
                                {{ old('gender', $customer->gender) == 'Female' ? 'selected' : '' }}>
                                Female
                            </option>

                            <option value="Other"
                                {{ old('gender', $customer->gender) == 'Other' ? 'selected' : '' }}>
                                Other
                            </option>

                        </select>

                    </div>


                    {{-- DOB --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Date of Birth
                        </label>

                        <input type="date"
                               name="dob"
                               class="form-control"
                               value="{{ old('dob', $customer->dob) }}">

                    </div>
                     <div class="col-md-6 mb-3">

        <label class="form-label">
            Age
        </label>

        <input type="number"
               name="age"
               class="form-control"
               value="{{ old('age', $customer->age) }}"
               placeholder="Enter age"
               min="0"
               max="120">

    </div>

    {{-- Blood Group --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Blood Group
        </label>

        <select name="blood_group" class="form-select">

            <option value="">Select Blood Group</option>

            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bloodGroup)

                <option value="{{ $bloodGroup }}"
                    {{ old('blood_group', $customer->blood_group) == $bloodGroup ? 'selected' : '' }}>
                    {{ $bloodGroup }}
                </option>

            @endforeach

        </select>

    </div>

    {{-- Height --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Height (cm)
        </label>

        <input type="number"
               name="height"
               class="form-control"
               value="{{ old('height', $customer->height) }}"
               placeholder="Enter height"
               step="0.01"
               min="0">

    </div>

    {{-- Weight --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Weight (kg)
        </label>

        <input type="number"
               name="weight"
               class="form-control"
               value="{{ old('weight', $customer->weight) }}"
               placeholder="Enter weight"
               step="0.01"
               min="0">

    </div>

    {{-- Occupation --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Occupation
        </label>

        <input type="text"
               name="occupation"
               class="form-control"
               value="{{ old('occupation', $customer->occupation) }}"
               placeholder="Enter occupation">

    </div>

                </div>


                {{-- ================= ADDRESS ================= --}}

                <div class="section-title mt-4">
                    Address Information
                </div>


                <div class="row">

                    {{-- Address --}}

    <div class="col-md-12 mb-3">

        <label class="form-label">
            Address
        </label>

        <textarea name="address"
                  class="form-control"
                  rows="3"
                  placeholder="Enter full address">{{ old('address', $customer->address) }}</textarea>

    </div>

                    {{-- City --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            City
                        </label>

                        <input type="text"
                               name="city"
                               class="form-control"
                               value="{{ old('city', $customer->city) }}"
                               placeholder="Enter city">

                    </div>


                    {{-- State --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            State
                        </label>

                        <input type="text"
                               name="state"
                               class="form-control"
                               value="{{ old('state', $customer->state) }}"
                               placeholder="Enter state">

                    </div>


                    {{-- Country --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Country
                        </label>

                        <input type="text"
                               name="country"
                               class="form-control"
                               value="{{ old('country', $customer->country) }}"
                               placeholder="Enter country">

                    </div>


                    {{-- Pincode --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Pincode
                        </label>

                        <input type="text"
                               name="pincode"
                               class="form-control"
                               value="{{ old('pincode', $customer->pincode) }}"
                               placeholder="Enter pincode">

                    </div>

                </div>
                {{-- ================= EMERGENCY CONTACT ================= --}}

<div class="section-title mt-4">
    Emergency Contact
</div>

<div class="row">

    {{-- Emergency Contact Name --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Emergency Contact Name
        </label>

        <input type="text"
               name="emergency_contact_name"
               class="form-control"
               value="{{ old('emergency_contact_name', $customer->emergency_contact_name) }}"
               placeholder="Enter emergency contact name">

    </div>


    {{-- Emergency Contact Mobile --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Emergency Contact Number
        </label>

        <input type="text"
               name="emergency_contact_mobile"
               class="form-control"
               value="{{ old('emergency_contact_mobile', $customer->emergency_contact_mobile) }}"
               placeholder="Enter emergency contact number">

    </div>

</div>


                {{-- ================= STATUS ================= --}}

                <div class="section-title mt-4">
                    Account Status
                </div>


                <div class="row">

                    {{-- Verification --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Verification
                        </label>

                        <select name="is_verified" class="form-select">

                            <option value="1"
                                {{ old('is_verified', $customer->is_verified) == 1 ? 'selected' : '' }}>
                                Verified
                            </option>

                            <option value="0"
                                {{ old('is_verified', $customer->is_verified) == 0 ? 'selected' : '' }}>
                                Not Verified
                            </option>

                        </select>

                    </div>


                    {{-- Status --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status" class="form-select">

                            <option value="1"
                                {{ old('status', $customer->status) == 1 ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0"
                                {{ old('status', $customer->status) == 0 ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>

                </div>


                {{-- ================= ACTIONS ================= --}}

                <div class="border-top pt-4 mt-4">

                    <button type="submit"
                            class="btn btn-purple">

                        <i class="ti ti-device-floppy me-1"></i>

                        Save Changes

                    </button>


                    <a href="{{ route('admin.customers.show', [
                        'id' => $customer->id,
                        'slug' => 'profile'
                    ]) }}"
                       class="btn btn-light border ms-2">

                        Cancel

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>


</div>

<script>

function previewProfileImage(input)
{
    if (input.files && input.files[0]) {

        const reader = new FileReader();

        reader.onload = function(e) {

            let preview = document.getElementById('profilePreview');

            let placeholder = document.getElementById('profilePlaceholder');

            if (preview) {

                preview.src = e.target.result;

            } else {

                if (placeholder) {

                    const img = document.createElement('img');

                    img.src = e.target.result;

                    img.className = 'profile-image-preview';

                    img.id = 'profilePreview';

                    placeholder.replaceWith(img);

                }

            }

        };

        reader.readAsDataURL(input.files[0]);

    }
}

</script>

@endsection
