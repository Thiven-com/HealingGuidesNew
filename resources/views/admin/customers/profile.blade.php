@extends('admin.customers.show')

@section('customer')

<style>
    /* =========================================
       PROFILE INFORMATION
    ========================================= */

    .profile-info-card {
        border-radius: 16px;
        border: 1px solid #f0f0f0;
    }

    .profile-info-title {
        font-weight: 700;
        color: #212529;
    }

    .profile-info-title i {
        color: #6d28d9;
    }

    .profile-info-item {
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .profile-info-item:last-child {
        border-bottom: 0;
    }

    .profile-label {
        color: #6b7280;
        font-size: 14px;
    }

    .profile-value {
        color: #212529;
        font-size: 14px;
        font-weight: 500;
        word-break: break-word;
    }

    .profile-value.fw-semibold {
        font-weight: 600 !important;
    }

    .profile-edit-btn {
        background: #6d28d9;
        border-color: #6d28d9;
        color: #fff;
        border-radius: 8px;
        padding: 9px 16px;
    }

    .profile-edit-btn:hover,
    .profile-edit-btn:focus {
        background: #6d28d9;
        border-color: #6d28d9;
        color: #fff;
    }

    .profile-back-btn {
        border-radius: 8px;
    }

    .profile-status-verified {
        background: #f3e8ff;
        color: #6d28d9;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .profile-status-active {
        background: #dcfce7;
        color: #15803d;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .profile-status-inactive {
        background: #fee2e2;
        color: #dc2626;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .profile-status-pending {
        background: #fef3c7;
        color: #92400e;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    @media (max-width: 768px) {

        .profile-info-item {
            padding: 13px 0;
        }

        .profile-label,
        .profile-value {
            font-size: 13px;
        }

        .profile-edit-btn,
        .profile-back-btn {
            padding: 8px 13px;
        }
    }
</style>

<div class="card border-0 shadow-sm profile-info-card">

<div class="card-body">

    {{-- =========================================
         HEADER
    ========================================== --}}

    <div class="mb-4">

        <h5 class="mb-1 profile-info-title">

            <i class="ti ti-user me-2"></i>

            Profile Information

        </h5>

        <p class="text-muted small mb-0">
            Customer personal and account information
        </p>

    </div>


    {{-- =========================================
         FULL NAME
    ========================================== --}}

    <div class="row profile-info-item">

        <div class="col-md-4 col-5 profile-label">
            Full Name
        </div>

        <div class="col-md-8 col-7 profile-value fw-semibold">
            {{ $customer->name ?? '—' }}
        </div>

    </div>


    {{-- =========================================
         CUSTOMER CODE
    ========================================== --}}

    <div class="row profile-info-item">

        <div class="col-md-4 col-5 profile-label">
            Customer Code
        </div>

        <div class="col-md-8 col-7 profile-value">
            {{ $customer->customer_code ?? '—' }}
        </div>

    </div>


    {{-- =========================================
         EMAIL
    ========================================== --}}

    <div class="row profile-info-item">

        <div class="col-md-4 col-5 profile-label">
            Email
        </div>

        <div class="col-md-8 col-7 profile-value">
            {{ $customer->email ?? '—' }}
        </div>

    </div>


    {{-- =========================================
         PHONE
    ========================================== --}}

    <div class="row profile-info-item">

        <div class="col-md-4 col-5 profile-label">
            Phone
        </div>

        <div class="col-md-8 col-7 profile-value">
            {{ $customer->mobile ?? '—' }}
        </div>

    </div>


    {{-- =========================================
         GENDER
    ========================================== --}}

    <div class="row profile-info-item">

        <div class="col-md-4 col-5 profile-label">
            Gender
        </div>

        <div class="col-md-8 col-7 profile-value">
            {{ $customer->gender ?? '—' }}
        </div>

    </div>


    {{-- =========================================
         DATE OF BIRTH
    ========================================== --}}

    <div class="row profile-info-item">

        <div class="col-md-4 col-5 profile-label">
            Date of Birth
        </div>

        <div class="col-md-8 col-7 profile-value">

            @if($customer->dob)

                {{ \Carbon\Carbon::parse($customer->dob)->format('d-m-Y') }}

            @else

                —

            @endif

        </div>

    </div>
     {{-- =========================================
         AGE
    ========================================== --}}

    <div class="row profile-info-item">
        <div class="col-md-4 col-5 profile-label">
            Age
        </div>

        <div class="col-md-8 col-7 profile-value">
            {{ $customer->age ? $customer->age . ' Years' : '—' }}
        </div>
    </div>
    {{-- =========================================
         BLOOD GROUP
    ========================================== --}}

    <div class="row profile-info-item">
        <div class="col-md-4 col-5 profile-label">
            Blood Group
        </div>

        <div class="col-md-8 col-7 profile-value">
            {{ $customer->blood_group ?? '—' }}
        </div>
    </div>

    {{-- =========================================
         HEIGHT
    ========================================== --}}

    <div class="row profile-info-item">
        <div class="col-md-4 col-5 profile-label">
            Height
        </div>

        <div class="col-md-8 col-7 profile-value">
            {{ $customer->height ? $customer->height . ' cm' : '—' }}
        </div>
    </div>


    {{-- =========================================
         WEIGHT
    ========================================== --}}

    <div class="row profile-info-item">
        <div class="col-md-4 col-5 profile-label">
            Weight
        </div>

        <div class="col-md-8 col-7 profile-value">
            {{ $customer->weight ? $customer->weight . ' kg' : '—' }}
        </div>
    </div>

     {{-- =========================================
         OCCUPATION
    ========================================== --}}

    <div class="row profile-info-item">
        <div class="col-md-4 col-5 profile-label">
            Occupation
        </div>

        <div class="col-md-8 col-7 profile-value">
            {{ $customer->occupation ?? '—' }}
        </div>
    </div>

    {{-- =========================================
         ADDRESS
    ========================================== --}}

    <div class="row profile-info-item">
        <div class="col-md-4 col-5 profile-label">
            Address
        </div>

        <div class="col-md-8 col-7 profile-value">
            {{ $customer->address ?? '—' }}
        </div>
    </div>





    {{-- =========================================
         CITY
    ========================================== --}}

    <div class="row profile-info-item">

        <div class="col-md-4 col-5 profile-label">
            City
        </div>

        <div class="col-md-8 col-7 profile-value">
            {{ $customer->city ?? '—' }}
        </div>

    </div>


    {{-- =========================================
         STATE
    ========================================== --}}

    <div class="row profile-info-item">

        <div class="col-md-4 col-5 profile-label">
            State
        </div>

        <div class="col-md-8 col-7 profile-value">
            {{ $customer->state ?? '—' }}
        </div>

    </div>


    {{-- =========================================
         COUNTRY
    ========================================== --}}

    <div class="row profile-info-item">

        <div class="col-md-4 col-5 profile-label">
            Country
        </div>

        <div class="col-md-8 col-7 profile-value">
            {{ $customer->country ?? '—' }}
        </div>

    </div>


    {{-- =========================================
         PINCODE
    ========================================== --}}

    <div class="row profile-info-item">

        <div class="col-md-4 col-5 profile-label">
            Pincode
        </div>

        <div class="col-md-8 col-7 profile-value">
            {{ $customer->pincode ?? '—' }}
        </div>

    </div>

    {{-- =========================================
         EMERGENCY CONTACT NAME
    ========================================== --}}

    <div class="row profile-info-item">
        <div class="col-md-4 col-5 profile-label">
            Emergency Contact Name
        </div>

        <div class="col-md-8 col-7 profile-value">
            {{ $customer->emergency_contact_name ?? '—' }}
        </div>
    </div>

    {{-- =========================================
         EMERGENCY CONTACT MOBILE
    ========================================== --}}

    <div class="row profile-info-item">
        <div class="col-md-4 col-5 profile-label">
            Emergency Contact Mobile
        </div>

        <div class="col-md-8 col-7 profile-value">
            {{ $customer->emergency_contact_mobile ?? '—' }}
        </div>
    </div>

    


    {{-- =========================================
         VERIFICATION
    ========================================== --}}

    <div class="row profile-info-item">

        <div class="col-md-4 col-5 profile-label">
            Verification
        </div>

        <div class="col-md-8 col-7 profile-value">

            @if($customer->is_verified)

                <span class="profile-status-verified">
                    <i class="ti ti-circle-check me-1"></i>
                    Verified
                </span>

            @else

                <span class="profile-status-pending">
                    <i class="ti ti-alert-circle me-1"></i>
                    Not Verified
                </span>

            @endif

        </div>

    </div>


    {{-- =========================================
         STATUS
    ========================================== --}}

    <div class="row profile-info-item">

        <div class="col-md-4 col-5 profile-label">
            Status
        </div>

        <div class="col-md-8 col-7 profile-value">

            @if($customer->status)

                <span class="profile-status-active">
                    <i class="ti ti-check me-1"></i>
                    Active
                </span>

            @else

                <span class="profile-status-inactive">
                    <i class="ti ti-x me-1"></i>
                    Inactive
                </span>

            @endif

        </div>

    </div>


    {{-- =========================================
         JOINED ON
    ========================================== --}}

    <div class="row profile-info-item">

        <div class="col-md-4 col-5 profile-label">
            Joined On
        </div>

        <div class="col-md-8 col-7 profile-value">

            @if($customer->created_at)

                {{ $customer->created_at->format('d-m-Y h:i A') }}

            @else

                —

            @endif

        </div>

    </div>


    {{-- =========================================
         ACTIONS
    ========================================== --}}

    <div class="border-top pt-3 mt-4">

        <a href="{{ route('admin.customers.edit', ['id' => $customer->id]) }}"
           class="btn profile-edit-btn">

            <i class="ti ti-edit me-1"></i>

            Edit Profile

        </a>


        <a href="{{ route('admin.customers.index') }}"
           class="btn btn-light border ms-2 profile-back-btn">

            <i class="ti ti-arrow-left me-1"></i>

            Back

        </a>

    </div>

</div>

</div>

@endsection
