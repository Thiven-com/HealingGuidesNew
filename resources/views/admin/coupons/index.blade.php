@extends('layout.mainlayout')

@section('title', 'Coupons')

@section('content')

<div class="page-wrapper">

    <div class="content">

        {{-- Page Header --}}
        <div class="page-header">

            <div class="page-title">
                <h4>Coupons & Offers</h4>
                <h6>Manage coupons and customer offers</h6>
            </div>

            <div class="page-btn">
                <a href="{{ route('admin.coupons.create') }}"
                   class="btn btn-primary">

                    <i class="ti ti-plus me-1"></i>
                    Add Coupon

                </a>
            </div>

        </div>


        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="ti ti-check me-1"></i>
                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>
            </div>
        @endif


        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="ti ti-alert-circle me-1"></i>
                {{ session('error') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>
            </div>
        @endif


        {{-- Coupon Card --}}
        <div class="card">

            <div class="card-header">

                <div class="row align-items-center">

                    <div class="col-md-6">
                        <h5 class="card-title mb-0">
                            All Coupons
                        </h5>
                    </div>

                    <div class="col-md-6 text-md-end">

                        <span class="badge bg-light text-dark">
                            Total:
                            {{ $coupons->total() }}
                        </span>

                    </div>

                </div>

            </div>


            {{-- Filters --}}
            <div class="card-body border-bottom">

                <form method="GET"
                      action="{{ route('admin.coupons.index') }}">

                    <div class="row g-3">

                        {{-- Search --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Search
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="ti ti-search"></i>
                                </span>

                                <input type="text"
                                       name="search"
                                       class="form-control"
                                       placeholder="Code or title"
                                       value="{{ request('search') }}">

                            </div>

                        </div>


                        {{-- Applicable --}}
                        <div class="col-md-3">

                            <label class="form-label">
                                Applicable To
                            </label>

                            <select name="applicable_to"
                                    class="form-select">

                                <option value="">
                                    All
                                </option>

                                <option value="appointment"
                                    {{ request('applicable_to') == 'appointment' ? 'selected' : '' }}>
                                    Appointment
                                </option>

                                <option value="medicine"
                                    {{ request('applicable_to') == 'medicine' ? 'selected' : '' }}>
                                    Medicine
                                </option>

                                <option value="all"
                                    {{ request('applicable_to') == 'all' ? 'selected' : '' }}>
                                    All Services
                                </option>

                            </select>

                        </div>


                        {{-- Status --}}
                        <div class="col-md-3">

                            <label class="form-label">
                                Status
                            </label>

                            <select name="status"
                                    class="form-select">

                                <option value="">
                                    All
                                </option>

                                <option value="1"
                                    {{ request('status') === '1' ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="0"
                                    {{ request('status') === '0' ? 'selected' : '' }}>
                                    Inactive
                                </option>

                            </select>

                        </div>


                        {{-- Buttons --}}
                        <div class="col-md-2 d-flex align-items-end">

                            <div class="d-flex gap-2 w-100">

                                <button type="submit"
                                        class="btn btn-primary flex-fill">

                                    <i class="ti ti-search me-1"></i>
                                    Filter

                                </button>

                                <a href="{{ route('admin.coupons.index') }}"
                                   class="btn btn-light">

                                    <i class="ti ti-refresh"></i>

                                </a>

                            </div>

                        </div>

                    </div>

                </form>

            </div>


            {{-- Table --}}
            <div class="card-body">

                <div class="table-responsive">

                    <table class="table datanew">

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>Coupon</th>

                                <th>Type</th>

                                <th>Discount</th>

                                <th>Applicable</th>

                                <th>Validity</th>

                                <th>Usage</th>

                                <th>Status</th>

                                <th class="text-end">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($coupons as $coupon)

                                <tr>

                                    {{-- ID --}}
                                    <td>
                                        {{ $coupons->firstItem() + $loop->index }}
                                    </td>


                                    {{-- Coupon --}}
                                    <td>

                                        <div>

                                            <strong class="text-dark">
                                                {{ $coupon->code }}
                                            </strong>

                                            <div class="text-muted small">
                                                {{ $coupon->title }}
                                            </div>

                                            @if($coupon->new_customer_only)

                                                <span class="badge bg-info mt-1">
                                                    New Customer
                                                </span>

                                            @endif

                                            @if($coupon->first_appointment_only)

                                                <span class="badge bg-warning text-dark mt-1">
                                                    First Appointment
                                                </span>

                                            @endif

                                            @if($coupon->free_appointment)

                                                <span class="badge bg-success mt-1">
                                                    Free Appointment
                                                </span>

                                            @endif

                                        </div>

                                    </td>


                                    {{-- Type --}}
                                    <td>

                                        @if($coupon->coupon_type === 'offer')

                                            <span class="badge bg-primary">
                                                Offer
                                            </span>

                                        @else

                                            <span class="badge bg-light text-dark">
                                                Coupon
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Discount --}}
                                    <td>

                                        @if(
                                            $coupon->discount_type === 'free'
                                            || $coupon->free_appointment
                                        )

                                            <span class="fw-semibold text-success">
                                                FREE
                                            </span>

                                        @elseif(
                                            $coupon->discount_type === 'percentage'
                                        )

                                            <span class="fw-semibold">
                                                {{ rtrim(rtrim(number_format($coupon->discount_value, 2), '0'), '.') }}%
                                            </span>

                                            @if($coupon->max_discount)
                                                <div class="text-muted small">
                                                    Max ₹{{ number_format($coupon->max_discount, 2) }}
                                                </div>
                                            @endif

                                        @else

                                            <span class="fw-semibold">
                                                ₹{{ number_format($coupon->discount_value, 2) }}
                                            </span>

                                        @endif

                                        @if($coupon->min_order_amount > 0)

                                            <div class="text-muted small">
                                                Min ₹{{ number_format($coupon->min_order_amount, 2) }}
                                            </div>

                                        @endif

                                    </td>


                                    {{-- Applicable --}}
                                    <td>

                                        @switch($coupon->applicable_to)

                                            @case('appointment')

                                                <span class="badge bg-light-primary text-primary">
                                                    Appointment
                                                </span>

                                                @break

                                            @case('medicine')

                                                <span class="badge bg-light-success text-success">
                                                    Medicine
                                                </span>

                                                @break

                                            @default

                                                <span class="badge bg-light-info text-info">
                                                    All
                                                </span>

                                        @endswitch

                                    </td>


                                    {{-- Validity --}}
                                    <td>

                                        @if($coupon->starts_at)

                                            <div class="small">
                                                <i class="ti ti-calendar me-1"></i>
                                                {{ $coupon->starts_at->format('d M Y') }}
                                            </div>

                                        @else

                                            <div class="small text-muted">
                                                Immediately
                                            </div>

                                        @endif


                                        @if($coupon->expires_at)

                                            <div class="small text-danger">

                                                <i class="ti ti-calendar-off me-1"></i>

                                                {{ $coupon->expires_at->format('d M Y') }}

                                            </div>

                                        @else

                                            <div class="small text-success">
                                                No expiry
                                            </div>

                                        @endif

                                    </td>


                                    {{-- Usage --}}
                                    <td>

                                        <strong>
                                            {{ $coupon->used_count }}
                                        </strong>

                                        @if($coupon->usage_limit)

                                            <span class="text-muted">
                                                /
                                                {{ $coupon->usage_limit }}
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                / Unlimited
                                            </span>

                                        @endif

                                        <div class="small text-muted">
                                            {{ $coupon->usage_per_customer }}
                                            per customer
                                        </div>

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


                                    {{-- Actions --}}
                                    <td>

                                        <div class="action-table-data">

                                            <div class="edit-delete-action">

                                                {{-- View --}}
                                                <a href="{{ route(
                                                    'admin.coupons.show',
                                                    $coupon->id
                                                ) }}"
                                                   class="me-2 p-2"
                                                   title="View">

                                                    <i class="ti ti-eye"></i>

                                                </a>


                                                {{-- Edit --}}
                                                <a href="{{ route(
                                                    'admin.coupons.edit',
                                                    $coupon->id
                                                ) }}"
                                                   class="me-2 p-2"
                                                   title="Edit">

                                                    <i class="ti ti-edit"></i>

                                                </a>


                                                {{-- Status --}}
                                                <a href="{{ route(
                                                    'admin.coupons.status',
                                                    $coupon->id
                                                ) }}"
                                                   class="me-2 p-2"
                                                   title="{{ $coupon->status ? 'Deactivate' : 'Activate' }}"
                                                   onclick="return confirm(
                                                       '{{ $coupon->status
                                                           ? 'Are you sure you want to deactivate this coupon?'
                                                           : 'Are you sure you want to activate this coupon?' }}'
                                                   )">

                                                    @if($coupon->status)

                                                        <i class="ti ti-toggle-right text-success"></i>

                                                    @else

                                                        <i class="ti ti-toggle-left text-danger"></i>

                                                    @endif

                                                </a>


                                                {{-- Delete --}}
                                                @if($coupon->used_count == 0)

                                                    <form action="{{ route(
                                                        'admin.coupons.destroy',
                                                        $coupon->id
                                                    ) }}"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm(
                                                              'Are you sure you want to delete this coupon?'
                                                          )">

                                                        @csrf

                                                        @method('DELETE')

                                                        <button type="submit"
                                                                class="btn p-2 text-danger"
                                                                title="Delete">

                                                            <i class="ti ti-trash"></i>

                                                        </button>

                                                    </form>

                                                @else

                                                    <span class="p-2 text-muted"
                                                          title="Coupon already used">

                                                        <i class="ti ti-lock"></i>

                                                    </span>

                                                @endif

                                            </div>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="9"
                                        class="text-center py-5">

                                        <div class="text-muted">

                                            <i class="ti ti-ticket-off fs-1 d-block mb-2"></i>

                                            <h6>
                                                No coupons found
                                            </h6>

                                            <p class="mb-0">
                                                Create your first coupon or offer.
                                            </p>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if($coupons->hasPages())

                    <div class="mt-3">

                        {{ $coupons->links('pagination::bootstrap-5') }}

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection