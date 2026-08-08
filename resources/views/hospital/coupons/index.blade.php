@extends('layout.mainlayout')

@section('title', 'Hospital Coupons')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            {{-- Page Header --}}
            <div class="page-header">
                <div class="page-title">
                    <h4>Coupons & Offers</h4>
                    <h6>Manage your hospital coupons and offers</h6>
                </div>

                <div class="page-btn">
                    <a href="{{ route('hospital.coupons.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i>
                        Add Coupon
                    </a>
                </div>
            </div>


            {{-- Success --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="ti ti-check me-1"></i>
                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif


            {{-- Error --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="ti ti-alert-circle me-1"></i>
                    {{ session('error') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif


            {{-- Validation --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">

                    <strong>Please fix the following:</strong>

                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>
            @endif


            {{-- Statistics --}}
            <div class="row">

                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex align-items-center justify-content-between">

                                <div>
                                    <p class="text-muted mb-1">
                                        Total Coupons
                                    </p>

                                    <h3 class="mb-0">
                                        {{ $coupons->total() }}
                                    </h3>
                                </div>

                                <span class="avatar avatar-lg bg-light-primary">
                                    <i class="ti ti-ticket text-primary fs-24"></i>
                                </span>

                            </div>

                        </div>
                    </div>
                </div>


                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex align-items-center justify-content-between">

                                <div>
                                    <p class="text-muted mb-1">
                                        Active
                                    </p>

                                    <h3 class="mb-0">
                                        {{ $coupons->where('status', 1)->count() }}
                                    </h3>
                                </div>

                                <span class="avatar avatar-lg bg-light-success">
                                    <i class="ti ti-check text-success fs-24"></i>
                                </span>

                            </div>

                        </div>
                    </div>
                </div>


                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex align-items-center justify-content-between">

                                <div>
                                    <p class="text-muted mb-1">
                                        Inactive
                                    </p>

                                    <h3 class="mb-0">
                                        {{ $coupons->where('status', 0)->count() }}
                                    </h3>
                                </div>

                                <span class="avatar avatar-lg bg-light-danger">
                                    <i class="ti ti-x text-danger fs-24"></i>
                                </span>

                            </div>

                        </div>
                    </div>
                </div>


                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex align-items-center justify-content-between">

                                <div>
                                    <p class="text-muted mb-1">
                                        Total Used
                                    </p>

                                    <h3 class="mb-0">
                                        {{ $coupons->sum('used_count') }}
                                    </h3>
                                </div>

                                <span class="avatar avatar-lg bg-light-warning">
                                    <i class="ti ti-chart-bar text-warning fs-24"></i>
                                </span>

                            </div>

                        </div>
                    </div>
                </div>

            </div>


            {{-- Coupon Table --}}
            <div class="card">

                <div class="card-header">

                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">

                        <div>
                            <h5 class="card-title mb-1">
                                My Coupons
                            </h5>

                            <p class="text-muted mb-0">
                                Coupons created for your hospital
                            </p>
                        </div>


                        <form method="GET" action="{{ route('hospital.coupons.index') }}" class="d-flex gap-2">

                            <input type="text" name="search" class="form-control" placeholder="Search coupon..."
                                value="{{ request('search') }}">


                            <select name="status" class="form-select">

                                <option value="">
                                    All Status
                                </option>

                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>
                                    Inactive
                                </option>

                            </select>


                            <button type="submit" class="btn btn-primary">

                                <i class="ti ti-search"></i>

                            </button>


                            @if(request()->hasAny(['search', 'status']))

                                <a href="{{ route('hospital.coupons.index') }}" class="btn btn-light">

                                    <i class="ti ti-refresh"></i>

                                </a>

                            @endif

                        </form>

                    </div>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table mb-0">

                            <thead>

                                <tr>

                                    <th>#</th>

                                    <th>Coupon</th>

                                    <th>Discount</th>

                                    <th>Applicable</th>

                                    <th>Customer Eligibility</th>

                                    <th>Usage</th>

                                    <th>Validity</th>

                                    <th>Status</th>

                                    <th class="text-end">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($coupons as $coupon)

                                    <tr>

                                        {{-- # --}}
                                        <td>
                                            {{ $coupons->firstItem() + $loop->index }}
                                        </td>


                                        {{-- Coupon --}}
                                        <td>

                                            <a href="{{ route('hospital.coupons.show', $coupon->id) }}"
                                                class="fw-semibold text-primary">

                                                {{ $coupon->code }}

                                            </a>

                                            <div class="small text-muted">
                                                {{ $coupon->title }}
                                            </div>

                                            @if($coupon->coupon_type === 'offer')

                                                <span class="badge bg-light-info text-info mt-1">
                                                    Offer
                                                </span>

                                            @else

                                                <span class="badge bg-light-secondary text-secondary mt-1">
                                                    Coupon
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Discount --}}
                                        <td>

                                            @if(
                                                    $coupon->free_appointment ||
                                                    $coupon->discount_type === 'free'
                                                )

                                                <span class="badge bg-light-success text-success">
                                                    FREE APPOINTMENT
                                                </span>

                                            @elseif($coupon->discount_type === 'percentage')

                                                <strong class="text-success">
                                                    {{ rtrim(rtrim(number_format($coupon->discount_value, 2), '0'), '.') }}%
                                                </strong>

                                                <span class="small text-muted">
                                                    OFF
                                                </span>

                                            @elseif($coupon->discount_type === 'fixed')

                                                <strong class="text-success">
                                                    ₹{{ number_format($coupon->discount_value, 2) }}
                                                </strong>

                                                <span class="small text-muted">
                                                    OFF
                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    -
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Applicable --}}
                                        <td>

                                            @if($coupon->applicable_to === 'appointment')

                                                <span class="badge bg-light-primary text-primary">
                                                    Appointment
                                                </span>

                                            @elseif($coupon->applicable_to === 'medicine')

                                                <span class="badge bg-light-success text-success">
                                                    Medicine
                                                </span>

                                            @else

                                                <span class="badge bg-light-info text-info">
                                                    All Services
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Eligibility --}}
                                        <td>

                                            @if($coupon->new_customer_only)

                                                <span class="badge bg-light-primary text-primary d-block mb-1"
                                                    style="width:max-content;">
                                                    New Customer
                                                </span>

                                            @endif


                                            @if($coupon->first_appointment_only)

                                                <span class="badge bg-light-warning text-warning d-block mb-1"
                                                    style="width:max-content;">
                                                    First Appointment
                                                </span>

                                            @endif


                                            @if(
                                                    !$coupon->new_customer_only &&
                                                    !$coupon->first_appointment_only
                                                )

                                                <span class="text-muted">
                                                    All Customers
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Usage --}}
                                        <td>

                                            <strong>
                                                {{ $coupon->used_count }}
                                            </strong>

                                            <span class="text-muted">

                                                @if($coupon->usage_limit)

                                                    / {{ $coupon->usage_limit }}

                                                @else

                                                    / ∞

                                                @endif

                                            </span>

                                        </td>


                                        {{-- Validity --}}
                                        <td>

                                            @if($coupon->expires_at)

                                                <span class="d-block">
                                                    {{ $coupon->expires_at->format('d M Y') }}
                                                </span>


                                                @if($coupon->expires_at->isPast())

                                                    <span class="badge bg-light-danger text-danger">
                                                        Expired
                                                    </span>

                                                @elseif(
                                                        $coupon->starts_at &&
                                                        $coupon->starts_at->isFuture()
                                                    )

                                                    <span class="badge bg-light-warning text-warning">
                                                        Upcoming
                                                    </span>

                                                @else

                                                    <span class="badge bg-light-success text-success">
                                                        Valid
                                                    </span>

                                                @endif

                                            @else

                                                <span class="text-muted">
                                                    No Expiry
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Status --}}
                                        <td>

                                            @if($coupon->status)

                                                <span class="badge bg-success">
                                                    <i class="ti ti-check me-1"></i>
                                                    Active
                                                </span>

                                            @else

                                                <span class="badge bg-danger">
                                                    <i class="ti ti-x me-1"></i>
                                                    Inactive
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Action --}}
                                        <td class="text-end">

                                            <div class="dropdown">

                                                <button type="button" class="btn btn-sm btn-light" data-bs-toggle="dropdown">

                                                    <i class="ti ti-dots-vertical"></i>

                                                </button>


                                                <ul class="dropdown-menu dropdown-menu-end">

                                                    {{-- View --}}
                                                    <li>

                                                        <a class="dropdown-item"
                                                            href="{{ route('hospital.coupons.show', $coupon->id) }}">

                                                            <i class="ti ti-eye me-2"></i>
                                                            View

                                                        </a>

                                                    </li>


                                                    {{-- Edit --}}
                                                    <li>

                                                        <a class="dropdown-item"
                                                            href="{{ route('hospital.coupons.edit', $coupon->id) }}">

                                                            <i class="ti ti-edit me-2"></i>
                                                            Edit

                                                        </a>

                                                    </li>


                                                    {{-- Status --}}
                                                    <li>

                                                        <a class="dropdown-item"
                                                            href="{{ route('hospital.coupons.status', $coupon->id) }}"
                                                            onclick="return confirm('{{ $coupon->status ? 'Deactivate this coupon?' : 'Activate this coupon?' }}')">

                                                            @if($coupon->status)

                                                                <i class="ti ti-toggle-left me-2"></i>
                                                                Deactivate

                                                            @else

                                                                <i class="ti ti-toggle-right me-2"></i>
                                                                Activate

                                                            @endif

                                                        </a>

                                                    </li>


                                                    {{-- Delete --}}
                                                    @if($coupon->used_count == 0)

                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>

                                                        <li>

                                                            <form method="POST"
                                                                action="{{ route('hospital.coupons.destroy', $coupon->id) }}"
                                                                onsubmit="return confirm('Are you sure you want to delete this coupon?')">

                                                                @csrf
                                                                @method('DELETE')

                                                                <button type="submit" class="dropdown-item text-danger">

                                                                    <i class="ti ti-trash me-2"></i>
                                                                    Delete

                                                                </button>

                                                            </form>

                                                        </li>

                                                    @endif

                                                </ul>

                                            </div>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="9" class="text-center py-5">

                                            <div class="text-muted">

                                                <i class="ti ti-ticket-off fs-1 d-block mb-2"></i>

                                                <h5>
                                                    No Coupons Found
                                                </h5>

                                                <p class="mb-3">
                                                    You haven't created any coupons yet.
                                                </p>

                                                <a href="{{ route('hospital.coupons.create') }}" class="btn btn-primary">

                                                    <i class="ti ti-plus me-1"></i>
                                                    Create Coupon

                                                </a>

                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- Pagination --}}
                @if($coupons->hasPages())

                    <div class="card-footer">

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                            <div class="text-muted">

                                Showing
                                <strong>{{ $coupons->firstItem() }}</strong>
                                to
                                <strong>{{ $coupons->lastItem() }}</strong>
                                of
                                <strong>{{ $coupons->total() }}</strong>
                                coupons

                            </div>

                            <div>
                                {{ $coupons->withQueryString()->links() }}
                            </div>

                        </div>

                    </div>

                @endif

            </div>

        </div>
    </div>

@endsection