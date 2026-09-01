@extends('admin.customers.show')

@section('customer')

<style>
    .family-card {
        border-radius: 16px;
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }

    .family-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(109, 40, 217, 0.10) !important;
    }

    .family-avatar {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background: #f3e8ff;
        color: #6d28d9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        font-weight: 600;
        flex-shrink: 0;
    }

    .family-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #f3e8ff;
        color: #6d28d9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .family-label {
        color: #6b7280;
        font-size: 13px;
    }

    .family-value {
        color: #212529;
        font-weight: 600;
    }

    .family-title {
        color: #212529;
        font-weight: 700;
    }

    .empty-family {
        padding: 50px 20px;
        text-align: center;
    }

    .empty-family-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 15px;
        border-radius: 50%;
        background: #f3e8ff;
        color: #6d28d9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
    }

    .family-badge {
        background: #f3e8ff;
        color: #6d28d9;
        border-radius: 20px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 600;
    }

    @media (max-width: 768px) {

        .family-card .card-body {
            padding: 16px;
        }

        .family-avatar {
            width: 48px;
            height: 48px;
            font-size: 19px;
        }

        .family-info {
            margin-top: 15px;
        }
    }
</style>

<div class="card border-0 shadow-sm family-card">

<div class="card-body">

    {{-- ================= HEADER ================= --}}

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">

        <div>
            <h5 class="mb-1 family-title">
                <i class="ti ti-users me-2" style="color:#6d28d9;"></i>
                Family Members
            </h5>

            <p class="text-muted mb-0 small">
                Family members associated with this customer
            </p>
        </div>

        <span class="family-badge">
            <i class="ti ti-user me-1"></i>

            @if(isset($familyMembers))
                {{ $familyMembers->count() }}
            @else
                0
            @endif

            Members
        </span>

    </div>


    {{-- =====================================================
    FAMILY MEMBERS
    ====================================================== --}}

    @if(isset($familyMembers) && $familyMembers->count() > 0)

        <div class="row g-3">

            @foreach($familyMembers as $member)

                <div class="col-xl-6 col-lg-6 col-md-6 col-12">

                    <div class="card border shadow-none h-100 family-card">

                        <div class="card-body">

                            {{-- Member Header --}}

                            <div class="d-flex align-items-center gap-3">

                                <div class="family-avatar">

                                    {{ strtoupper(substr($member->name ?? 'U', 0, 1)) }}

                                </div>

                                <div class="flex-grow-1">

                                    <h6 class="mb-1 fw-bold">

                                        {{ $member->name ?? '—' }}

                                    </h6>

                                    <small class="text-muted">

                                        {{ $member->relationship ?? 'Family Member' }}

                                    </small>

                                </div>

                                <div class="family-icon">

                                    <i class="ti ti-user"></i>

                                </div>

                            </div>


                            {{-- Member Details --}}

                            <div class="family-info mt-4">

                                <div class="row g-3">

                                    {{-- Customer Code --}}

                                    <div class="col-md-6">

                                        <div class="family-label mb-1">
                                            Customer Code
                                        </div>

                                        <div class="family-value">
                                            {{ $member->customer_code ?? '—' }}
                                        </div>

                                    </div>


                                    {{-- Relationship --}}

                                    <div class="col-md-6">

                                        <div class="family-label mb-1">
                                            Relationship
                                        </div>

                                        <div class="family-value">
                                            {{ $member->relationship ?? '—' }}
                                        </div>

                                    </div>


                                    {{-- Mobile --}}

                                    <div class="col-md-6">

                                        <div class="family-label mb-1">
                                            Mobile
                                        </div>

                                        <div class="family-value">
                                            {{ $member->mobile ?? '—' }}
                                        </div>

                                    </div>


                                    {{-- Email --}}

                                    <div class="col-md-6">

                                        <div class="family-label mb-1">
                                            Email
                                        </div>

                                        <div class="family-value text-break">
                                            {{ $member->email ?? '—' }}
                                        </div>

                                    </div>


                                    {{-- Gender --}}

                                    <div class="col-md-6">

                                        <div class="family-label mb-1">
                                            Gender
                                        </div>

                                        <div class="family-value">
                                            {{ $member->gender ?? '—' }}
                                        </div>

                                    </div>


                                    {{-- Date of Birth --}}

                                    <div class="col-md-6">

                                        <div class="family-label mb-1">
                                            Date of Birth
                                        </div>

                                        <div class="family-value">

                                            @if(!empty($member->dob))

                                                {{ \Carbon\Carbon::parse($member->dob)->format('d-m-Y') }}

                                            @else

                                                —

                                            @endif

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        {{-- ================= EMPTY STATE ================= --}}

        <div class="empty-family">

            <div class="empty-family-icon">

                <i class="ti ti-users"></i>

            </div>

            <h5 class="fw-bold mb-2">
                No Family Members
            </h5>

            <p class="text-muted mb-0">
                No family members have been added for this customer.
            </p>

        </div>

    @endif

</div>

</div>

@endsection
