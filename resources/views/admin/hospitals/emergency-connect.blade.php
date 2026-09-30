@extends('layout.mainlayout')

@section('content')


@php
$slug = $slug ?? request()->route('slug');
    $emergencyNames = [
        'front-office' => 'Front Office',
        'diagnostics' => 'Diagnostics',
        'room-service' => 'Room Service',
        'ambulance' => 'Ambulance',
    ];

    $emergencyTitle = $emergencyNames[$slug] ?? ucwords(str_replace('-', ' ', $slug));
@endphp

<div class="page-wrapper">

    <div class="content">

        {{-- =========================================================
            ERROR MESSAGE
        ========================================================= --}}

        @if ($errors->any())

            <div class="alert alert-danger">

                <strong>Please fix the following:</strong>

                <ul class="mb-0 mt-2">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =========================================================
            PAGE HEADER
        ========================================================= --}}

        <div class="page-header">

            <div class="page-title">

                <h4>
    {{ $emergencyTitle }}
</h4>

<h6>
    Manage {{ $emergencyTitle }} contacts for {{ $hospital->hospital_name }}
</h6>

            </div>


            <div class="page-btn">

                <a
                    href="{{ url()->previous() }}"
                    class="btn emergency-back-btn"
                >

                    <i class="ti ti-arrow-left me-1"></i>

                    Back

                </a>

            </div>

        </div>


        {{-- =========================================================
            BREADCRUMB
        ========================================================= --}}

        <div class="emergency-breadcrumb">

            <a href="{{ route('admin.hospitals.index') }}">

                <i class="ti ti-building-hospital"></i>

                Hospital Management

            </a>

            <span>

                <i class="ti ti-chevron-right"></i>

            </span>

            <span>

                Emergency Connect

            </span>

        </div>


        {{-- =========================================================
            EMERGENCY CONNECT LIST
        ========================================================= --}}

        <div class="card border-0 shadow-sm emergency-list-card">


            {{-- HEADER --}}

            <div class="card-header bg-white border-0">

                <div class="emergency-list-header">


                    {{-- TITLE --}}

                    <div class="emergency-list-heading">

                        <div class="emergency-heading-icon">

                            <i class="ti ti-phone-call"></i>

                        </div>

                        <div>

                            <h4>
                                Emergency Connect List
                            </h4>

                            <p>
                                Manage emergency contacts associated with this hospital
                            </p>

                        </div>

                    </div>


                    {{-- ACTIONS --}}

                    <div class="emergency-header-actions">


                        {{-- COUNT --}}

                        <div class="emergency-count">

                            <span class="count-number">
                                {{ $emergencyConnects->count() }}
                            </span>

                            <span class="count-label">
                                {{ $emergencyConnects->count() == 1 ? 'Contact' : 'Contacts' }}
                            </span>

                        </div>


                        {{-- ADD --}}

                        <button
                            type="button"
                            class="btn add-emergency-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#addEmergencyConnectModal"
                        >

                            <i class="ti ti-plus me-1"></i>

                            Add Emergency Contact

                        </button>

                    </div>

                </div>

            </div>


            {{-- BODY --}}

            <div class="card-body">


                @forelse($emergencyConnects as $emergency)


                    <div class="emergency-row">


                        {{-- IMAGE --}}

                        <div class="emergency-image">

                            @if(!empty($emergency->image))

                                <img
                                    src="{{ asset($emergency->image) }}"
                                    alt="{{ $emergency->name }}"
                                >

                            @else

                                <div class="emergency-image-placeholder">

                                    <i class="ti ti-user"></i>

                                </div>

                            @endif

                        </div>


                        {{-- DETAILS --}}

                        <div class="emergency-details">


                            <div class="emergency-title-row">

                                <h5>
                                    {{ $emergency->name }}
                                </h5>

                                <span class="emergency-badge">

                                    <i class="ti ti-alert-circle"></i>

                                    Emergency

                                </span>

                            </div>


                            {{-- DESIGNATION --}}

                            @if($emergency->designation)

                                <div class="emergency-designation">

                                    <i class="ti ti-id-badge"></i>

                                    {{ $emergency->designation }}

                                </div>

                            @endif


                            {{-- DEPARTMENT --}}

                            @if($emergency->department)

                                <div class="emergency-department">

                                    <i class="ti ti-building-hospital"></i>

                                    {{ $emergency->department }}

                                </div>

                            @endif


                            {{-- CONTACT DETAILS --}}

                            <div class="emergency-contact-details">


                                @if($emergency->contact_number)

                                    <a
                                        href="tel:{{ $emergency->contact_number }}"
                                        class="emergency-contact-link"
                                    >

                                        <i class="ti ti-phone"></i>

                                        {{ $emergency->contact_number }}

                                    </a>

                                @endif


                                @if($emergency->whatsapp_number)

                                    <a
                                        href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $emergency->whatsapp_number) }}"
                                        target="_blank"
                                        class="emergency-whatsapp-link"
                                    >

                                        <i class="ti ti-brand-whatsapp"></i>

                                        {{ $emergency->whatsapp_number }}

                                    </a>

                                @endif

                            </div>

                        </div>


                        {{-- ACTIONS --}}

                        <div class="emergency-actions">


                            {{-- EDIT --}}

                            <button
                                type="button"
                                class="emergency-action-btn emergency-edit-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#editEmergencyModal{{ $emergency->id }}"
                                title="Edit Emergency Contact"
                            >

                                <i class="ti ti-edit"></i>

                                <span>
                                    Edit
                                </span>

                            </button>


                            {{-- DELETE --}}

                            <form
    action="{{ route('admin.emergency-connect.destroy', $emergency->id) }}"
    method="POST"
    onsubmit="return confirm('Are you sure you want to delete this emergency contact?');"
>
                            {{-- <form
                                action="{{ route('admin.emergency-connect.destroy', $emergency->id) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this emergency contact?');"
                            > --}}

                                @csrf

                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="emergency-action-btn emergency-delete-btn"
                                    title="Delete Emergency Contact"
                                >

                                    <i class="ti ti-trash"></i>

                                    <span>
                                        Delete
                                    </span>

                                </button>

                            </form>

                        </div>

                    </div>


                    {{-- =====================================================
                        EDIT MODAL
                    ====================================================== --}}

                    <div
                        class="modal fade"
                        id="editEmergencyModal{{ $emergency->id }}"
                        tabindex="-1"
                        aria-hidden="true"
                    >

                        <div class="modal-dialog modal-dialog-centered modal-lg">

                            <div class="modal-content emergency-modal-content">

                                <form
                                    action="{{ route('admin.emergency-connect.update', $emergency->id) }}"
                                    method="POST"
                                    enctype="multipart/form-data"
                                >

                                    @csrf

                                    @method('PUT')


                                    <input
                                        type="hidden"
                                        name="hospital_id"
                                        value="{{ $hospital->id }}"
                                    >


                                    {{-- HEADER --}}

                                    <div class="modal-header emergency-modal-header">

                                        <div class="emergency-modal-title">

                                            <div class="emergency-modal-icon">

                                                <i class="ti ti-edit"></i>

                                            </div>

                                            <div>

                                                <h5>
                                                    Edit Emergency Contact
                                                </h5>

                                                <p>
                                                    Update emergency contact information
                                                </p>

                                            </div>

                                        </div>


                                        <button
                                            type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"
                                        ></button>

                                    </div>


                                    {{-- BODY --}}

                                    <div class="modal-body">

                                        <div class="row">


                                            {{-- NAME --}}

                                            <div class="col-md-6 mb-3">

                                                <label class="form-label">

                                                    Name

                                                    <span class="text-danger">
                                                        *
                                                    </span>

                                                </label>

                                                <input
                                                    type="text"
                                                    name="name"
                                                    class="form-control"
                                                    value="{{ $emergency->name }}"
                                                    placeholder="Enter name"
                                                    required
                                                >

                                            </div>


                                            {{-- DESIGNATION --}}

                                            <div class="col-md-6 mb-3">

                                                <label class="form-label">
                                                    Designation
                                                </label>

                                                <input
                                                    type="text"
                                                    name="designation"
                                                    class="form-control"
                                                    value="{{ $emergency->designation }}"
                                                    placeholder="Enter designation"
                                                >

                                            </div>


                                            {{-- DEPARTMENT --}}

                                            <div class="col-md-6 mb-3">

                                                <label class="form-label">
                                                    Department
                                                </label>

                                                <input
                                                    type="text"
                                                    name="department"
                                                    class="form-control"
                                                    value="{{ $emergency->department }}"
                                                    placeholder="Enter department"
                                                >

                                            </div>


                                            {{-- CONTACT NUMBER --}}

                                            <div class="col-md-6 mb-3">

                                                <label class="form-label">
                                                    Contact Number
                                                </label>

                                                <input
                                                    type="text"
                                                    name="contact_number"
                                                    class="form-control"
                                                    value="{{ $emergency->contact_number }}"
                                                    placeholder="Enter contact number"
                                                >

                                            </div>


                                            {{-- WHATSAPP --}}

                                            <div class="col-md-6 mb-3">

                                                <label class="form-label">
                                                    WhatsApp Number
                                                </label>

                                                <input
                                                    type="text"
                                                    name="whatsapp_number"
                                                    class="form-control"
                                                    value="{{ $emergency->whatsapp_number }}"
                                                    placeholder="Enter WhatsApp number"
                                                >

                                            </div>


                                            {{-- IMAGE --}}

                                            <div class="col-md-6 mb-3">

                                                <label class="form-label">
                                                    Image
                                                </label>


                                                @if(!empty($emergency->image))

                                                    <div class="current-emergency-image">

                                                        <img
                                                            src="{{ asset($emergency->image) }}"
                                                            alt="{{ $emergency->name }}"
                                                        >

                                                        <span>
                                                            Current Image
                                                        </span>

                                                    </div>

                                                @endif


                                                <input
                                                    type="file"
                                                    name="image"
                                                    class="form-control"
                                                    accept="image/*"
                                                >

                                                <small class="text-muted">

                                                    Leave empty to keep current image.

                                                </small>

                                            </div>


                                        </div>

                                    </div>


                                    {{-- FOOTER --}}

                                    <div class="modal-footer">

                                        <button
                                            type="button"
                                            class="btn btn-light"
                                            data-bs-dismiss="modal"
                                        >
                                            Cancel
                                        </button>


                                        <button
                                            type="submit"
                                            class="btn emergency-save-btn"
                                        >

                                            <i class="ti ti-check me-1"></i>

                                            Update Contact

                                        </button>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>


                @empty


                    <div class="emergency-empty-state">

                        <div class="emergency-empty-icon">

                            <i class="ti ti-phone-off"></i>

                        </div>

                        <h5>
                            No Emergency Contacts
                        </h5>

                        <p>
                            No emergency contacts have been added for this hospital.
                        </p>

                    </div>


                @endforelse

            </div>

        </div>

    </div>

</div>



{{-- =========================================================
    ADD EMERGENCY CONTACT MODAL
========================================================= --}}

<div
    class="modal fade"
    id="addEmergencyConnectModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content emergency-modal-content">


            <form
                action="{{ route('admin.emergency-connect.store', ['hospital' => $hospital->id]) }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf


                <input type="hidden" name="hospital_id" value="{{ $hospital->id }}">
                <input type="hidden" name="slug" value="{{ $slug }}">


                {{-- HEADER --}}

                <div class="modal-header emergency-modal-header">

                    <div class="emergency-modal-title">

                        <div class="emergency-modal-icon">

                            <i class="ti ti-phone-plus"></i>

                        </div>

                        <div>

                            <h5>
                                Add Emergency Contact
                            </h5>

                            <p>
                                Add a new emergency contact for this hospital
                            </p>

                        </div>

                    </div>


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                {{-- BODY --}}

                <div class="modal-body">

                    <div class="row">


                        {{-- NAME --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Name

                                <span class="text-danger">
                                    *
                                </span>

                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                placeholder="Enter name"
                                required
                            >

                        </div>


                        {{-- DESIGNATION --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Designation
                            </label>

                            <input
                                type="text"
                                name="designation"
                                class="form-control"
                                placeholder="Enter designation"
                            >

                        </div>


                        {{-- DEPARTMENT --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Department
                            </label>

                            <input
                                type="text"
                                name="department"
                                class="form-control"
                                placeholder="Enter department"
                            >

                        </div>


                        {{-- CONTACT NUMBER --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Contact Number
                            </label>

                            <input
                                type="text"
                                name="contact_number"
                                class="form-control"
                                placeholder="Enter contact number"
                            >

                        </div>


                        {{-- WHATSAPP NUMBER --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                WhatsApp Number
                            </label>

                            <input
                                type="text"
                                name="whatsapp_number"
                                class="form-control"
                                placeholder="Enter WhatsApp number"
                            >

                        </div>


                        {{-- IMAGE --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Image
                            </label>

                            <input
                                type="file"
                                name="image"
                                class="form-control"
                                accept="image/*"
                            >

                            <small class="text-muted">
                                JPG, PNG or WEBP. Maximum 2MB.
                            </small>

                        </div>


                    </div>

                </div>


                {{-- FOOTER --}}

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        class="btn emergency-save-btn"
                    >

                        <i class="ti ti-plus me-1"></i>

                        Add Contact

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



<style>

/* =========================================================
   PAGE HEADER
========================================================= */

.page-title h4 {

    color: #172b4d;

    font-size: 24px;

    font-weight: 700;

    margin-bottom: 5px;

}

.page-title h6 {

    color: #8a98ad;

    font-size: 14px;

    font-weight: 400;

}


/* =========================================================
   BREADCRUMB
========================================================= */

.emergency-breadcrumb {

    display: flex;

    align-items: center;

    gap: 8px;

    margin-bottom: 18px;

    font-size: 14px;

    color: #71809a;

}

.emergency-breadcrumb a {

    display: inline-flex;

    align-items: center;

    gap: 6px;

    color: #4f8df7;

    text-decoration: none;

    font-weight: 500;

}

.emergency-breadcrumb a:hover {

    color: #2563eb;

}


/* =========================================================
   BACK BUTTON
========================================================= */

.emergency-back-btn {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    background: #ffffff;

    color: #52627a;

    border: 1px solid #dce5f2;

    border-radius: 8px;

    padding: 8px 15px;

    font-size: 14px;

    font-weight: 500;

}

.emergency-back-btn:hover {

    background: #4f8df7;

    border-color: #4f8df7;

    color: #ffffff;

}


/* =========================================================
   MAIN LIST CARD
========================================================= */

.emergency-list-card {

    border: 1px solid #e8edf5 !important;

    border-radius: 18px;

    overflow: hidden;

    background: #ffffff;

    margin-bottom: 24px;

}


/* =========================================================
   HEADER
========================================================= */

.emergency-list-card .card-header {

    padding: 20px 24px;

    border-bottom: 1px solid #edf1f6 !important;

}

.emergency-list-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

}

.emergency-list-heading {

    display: flex;

    align-items: center;

    gap: 13px;

}

.emergency-heading-icon {

    width: 46px;

    height: 46px;

    min-width: 46px;

    border-radius: 12px;

    background: #fff0f3;

    color: #d9536f;

    display: flex;

    align-items: center;

    justify-content: center;

}

.emergency-heading-icon i {

    font-size: 22px;

}

.emergency-list-heading h4 {

    margin: 0 0 3px;

    color: #1f3048;

    font-size: 19px;

    font-weight: 700;

}

.emergency-list-heading p {

    margin: 0;

    color: #8a96a8;

    font-size: 13px;

}


/* =========================================================
   HEADER ACTIONS
========================================================= */

.emergency-header-actions {

    display: flex;

    align-items: center;

    gap: 10px;

}


.emergency-count {

    display: flex;

    align-items: center;

    gap: 6px;

    padding: 7px 13px;

    border-radius: 20px;

    background: #f5f7fb;

    border: 1px solid #e8edf4;

    white-space: nowrap;

}

.count-number {

    color: #4f7cff;

    font-size: 14px;

    font-weight: 700;

}

.count-label {

    color: #718096;

    font-size: 13px;

    font-weight: 500;

}


.add-emergency-btn {

    height: 38px;

    padding: 0 15px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    border-radius: 8px;

    background: #4f7cff;

    border: 1px solid #4f7cff;

    color: #ffffff;

    font-size: 13px;

    font-weight: 600;

}

.add-emergency-btn:hover {

    background: #3d68df;

    border-color: #3d68df;

    color: #ffffff;

}


/* =========================================================
   BODY
========================================================= */

.emergency-list-card .card-body {

    padding: 18px 24px 24px;

}


/* =========================================================
   EMERGENCY ROW
========================================================= */

.emergency-row {

    display: flex;

    align-items: center;

    gap: 17px;

    padding: 15px;

    margin-bottom: 12px;

    background: #ffffff;

    border: 1px solid #e7ecf3;

    border-radius: 14px;

    transition: all 0.2s ease;

}

.emergency-row:last-child {

    margin-bottom: 0;

}

.emergency-row:hover {

    border-color: #d5e0f1;

    box-shadow: 0 6px 18px rgba(30, 55, 90, 0.07);

    transform: translateY(-1px);

}


/* =========================================================
   IMAGE
========================================================= */

.emergency-image {

    width: 78px;

    height: 78px;

    min-width: 78px;

    border-radius: 12px;

    overflow: hidden;

    background: #f5f7fb;

    border: 1px solid #e5eaf1;

    display: flex;

    align-items: center;

    justify-content: center;

}

.emergency-image img {

    width: 100%;

    height: 100%;

    object-fit: cover;

    display: block;

}

.emergency-image-placeholder {

    width: 100%;

    height: 100%;

    display: flex;

    align-items: center;

    justify-content: center;

    background: #fff0f3;

    color: #d9536f;

}

.emergency-image-placeholder i {

    font-size: 30px;

}


/* =========================================================
   DETAILS
========================================================= */

.emergency-details {

    flex: 1;

    min-width: 0;

}

.emergency-title-row {

    display: flex;

    align-items: center;

    gap: 9px;

    flex-wrap: wrap;

}

.emergency-title-row h5 {

    margin: 0;

    color: #25364f;

    font-size: 16px;

    font-weight: 700;

    line-height: 1.4;

}

.emergency-badge {

    display: inline-flex;

    align-items: center;

    gap: 4px;

    padding: 4px 8px;

    border-radius: 20px;

    background: #fff0f3;

    color: #d9536f;

    font-size: 11px;

    font-weight: 600;

}

.emergency-badge i {

    font-size: 12px;

}


/* =========================================================
   DESIGNATION / DEPARTMENT
========================================================= */

.emergency-designation,
.emergency-department {

    display: inline-flex;

    align-items: center;

    gap: 5px;

    color: #78869a;

    font-size: 12px;

    margin-top: 6px;

    margin-right: 15px;

}

.emergency-designation i,
.emergency-department i {

    font-size: 14px;

    color: #71809a;

}


/* =========================================================
   CONTACT
========================================================= */

.emergency-contact-details {

    display: flex;

    align-items: center;

    flex-wrap: wrap;

    gap: 12px;

    margin-top: 8px;

}

.emergency-contact-link,
.emergency-whatsapp-link {

    display: inline-flex;

    align-items: center;

    gap: 5px;

    font-size: 12px;

    text-decoration: none;

}

.emergency-contact-link {

    color: #4f7cff;

}

.emergency-whatsapp-link {

    color: #159a72;

}

.emergency-contact-link:hover,
.emergency-whatsapp-link:hover {

    text-decoration: underline;

}


/* =========================================================
   ACTIONS
========================================================= */

.emergency-actions {

    display: flex;

    align-items: center;

    gap: 8px;

    margin-left: auto;

}

.emergency-actions form {

    margin: 0;

}

.emergency-action-btn {

    height: 36px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 6px;

    padding: 0 12px;

    border-radius: 8px;

    font-size: 12px;

    font-weight: 600;

    cursor: pointer;

    transition: all 0.2s ease;

}

.emergency-action-btn i {

    font-size: 15px;

}


/* EDIT */

.emergency-edit-btn {

    background: #f2edff;

    border: 1px solid #ddd3fa;

    color: #6c4ed7;

}

.emergency-edit-btn:hover {

    background: #6c4ed7;

    border-color: #6c4ed7;

    color: #ffffff;

}


/* DELETE */

.emergency-delete-btn {

    background: #fff1f2;

    border: 1px solid #f5d4d8;

    color: #d95360;

}

.emergency-delete-btn:hover {

    background: #d95360;

    border-color: #d95360;

    color: #ffffff;

}


/* =========================================================
   EMPTY STATE
========================================================= */

.emergency-empty-state {

    text-align: center;

    padding: 55px 20px;

}

.emergency-empty-icon {

    width: 68px;

    height: 68px;

    margin: 0 auto 14px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 18px;

    background: #fff0f3;

    color: #d9536f;

}

.emergency-empty-icon i {

    font-size: 32px;

}

.emergency-empty-state h5 {

    margin: 0 0 5px;

    color: #26364d;

    font-size: 17px;

    font-weight: 700;

}

.emergency-empty-state p {

    margin: 0;

    color: #8b97a8;

    font-size: 13px;

}


/* =========================================================
   MODAL
========================================================= */

.emergency-modal-content {

    border: 0;

    border-radius: 16px;

    overflow: hidden;

    box-shadow: 0 20px 60px rgba(20, 40, 80, 0.15);

}

.emergency-modal-header {

    padding: 20px 24px;

    border-bottom: 1px solid #edf1f6;

    background: #ffffff;

}

.emergency-modal-title {

    display: flex;

    align-items: center;

    gap: 12px;

}

.emergency-modal-icon {

    width: 42px;

    height: 42px;

    min-width: 42px;

    border-radius: 11px;

    display: flex;

    align-items: center;

    justify-content: center;

    background: #fff0f3;

    color: #d9536f;

}

.emergency-modal-icon i {

    font-size: 21px;

}

.emergency-modal-title h5 {

    margin: 0 0 2px;

    color: #26364d;

    font-size: 18px;

    font-weight: 700;

}

.emergency-modal-title p {

    margin: 0;

    color: #8b97a8;

    font-size: 12px;

}


/* =========================================================
   MODAL BODY
========================================================= */

.emergency-modal-content .modal-body {

    padding: 24px;

}

.emergency-modal-content .form-label {

    color: #34445c;

    font-size: 13px;

    font-weight: 600;

    margin-bottom: 7px;

}

.emergency-modal-content .form-control {

    min-height: 42px;

    border: 1px solid #dfe5ee;

    border-radius: 8px;

    color: #27364d;

    font-size: 13px;

    box-shadow: none;

}

.emergency-modal-content .form-control:focus {

    border-color: #6d8ff5;

    box-shadow:
        0 0 0 3px rgba(79, 124, 255, 0.08);

}


/* =========================================================
   CURRENT IMAGE
========================================================= */

.current-emergency-image {

    display: flex;

    align-items: center;

    gap: 12px;

    margin-bottom: 10px;

    padding: 8px 10px;

    border: 1px solid #e7ecf3;

    border-radius: 9px;

    background: #f8fafc;

}

.current-emergency-image img {

    width: 55px;

    height: 55px;

    border-radius: 8px;

    object-fit: cover;

    background: #ffffff;

    border: 1px solid #e4e9f0;

}

.current-emergency-image span {

    color: #7d899b;

    font-size: 12px;

}


/* =========================================================
   MODAL FOOTER
========================================================= */

.emergency-modal-content .modal-footer {

    padding: 15px 24px;

    border-top: 1px solid #edf1f6;

}

.emergency-modal-content .btn-light {

    height: 38px;

    padding: 0 16px;

    border: 1px solid #dfe5ee;

    border-radius: 8px;

    color: #627087;

    font-size: 13px;

    font-weight: 600;

    background: #ffffff;

}

.emergency-save-btn {

    height: 38px;

    padding: 0 17px;

    border-radius: 8px;

    background: #4f7cff;

    border: 1px solid #4f7cff;

    color: #ffffff;

    font-size: 13px;

    font-weight: 600;

}

.emergency-save-btn:hover {

    background: #3d68df;

    border-color: #3d68df;

    color: #ffffff;

}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 991px) {

    .emergency-row {

        align-items: flex-start;

    }

    .emergency-actions {

        flex-direction: column;

    }

    .emergency-action-btn {

        width: 90px;

    }

}


@media (max-width: 767px) {

    .emergency-list-card .card-header {

        padding: 18px;

    }

    .emergency-list-card .card-body {

        padding: 15px 18px 18px;

    }

    .emergency-list-header {

        align-items: flex-start;

    }

    .emergency-header-actions {

        flex-wrap: wrap;

        justify-content: flex-end;

    }

    .emergency-row {

        flex-wrap: wrap;

        gap: 13px;

        padding: 13px;

    }

    .emergency-image {

        width: 65px;

        height: 65px;

        min-width: 65px;

    }

    .emergency-details {

        width: calc(100% - 80px);

        flex: none;

    }

    .emergency-actions {

        width: 100%;

        flex-direction: row;

        justify-content: flex-end;

        padding-top: 3px;

        border-top: 1px solid #edf1f6;

    }

    .emergency-action-btn {

        width: auto;

        min-width: 90px;

    }

}


@media (max-width: 575px) {

    .emergency-list-header {

        flex-direction: column;

        align-items: flex-start;

        gap: 12px;

    }

    .emergency-list-heading h4 {

        font-size: 17px;

    }

    .emergency-list-heading p {

        font-size: 12px;

    }

    .emergency-row {

        display: grid;

        grid-template-columns: 60px 1fr;

        gap: 12px;

    }

    .emergency-image {

        width: 60px;

        height: 60px;

        min-width: 60px;

    }

    .emergency-details {

        width: auto;

    }

    .emergency-title-row h5 {

        font-size: 14px;

    }

    .emergency-contact-details {

        flex-direction: column;

        align-items: flex-start;

        gap: 5px;

    }

    .emergency-actions {

        grid-column: 1 / -1;

        justify-content: flex-end;

    }

}

</style>

@endsection