<?php $page = 'membership-registrations'; ?>

@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- PAGE HEADER --}}
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Membership Registrations</h4>
                    <h6>Manage Membership Registrations</h6>
                </div>
            </div>

            {{-- <div class="page-btn">
                <a href="{{ route('admin.membership-registrations.create') }}"
                   class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>
                    Add Membership
                </a>
            </div> --}}
        </div>

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ERROR MESSAGE --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- FILTERS --}}
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-filter me-1"></i>
                    Filters
                </h5>
            </div>

            <div class="card-body">

                <form method="GET"
                      action="{{ route('admin.membership-registrations.all') }}">

                    <div class="row g-3">

                        {{-- SEARCH --}}
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text"
                                   name="search"
                                   class="form-control"
                                   placeholder="Name / Mobile / Email / City"
                                   value="{{ request('search') }}">
                        </div>

                        {{-- GENDER --}}
                        <div class="col-md-2">
                            <label class="form-label">Gender</label>

                            <select name="gender" class="form-select">
                                <option value="">All Gender</option>

                                <option value="male"
                                    {{ request('gender') == 'male' ? 'selected' : '' }}>
                                    Male
                                </option>

                                <option value="female"
                                    {{ request('gender') == 'female' ? 'selected' : '' }}>
                                    Female
                                </option>

                                <option value="other"
                                    {{ request('gender') == 'other' ? 'selected' : '' }}>
                                    Other
                                </option>
                            </select>
                        </div>

                        {{-- MEMBERSHIP --}}
                        <div class="col-md-2">
                            <label class="form-label">Membership</label>

                            <input type="text"
                                   name="membership"
                                   class="form-control"
                                   placeholder="Membership"
                                   value="{{ request('membership') }}">
                        </div>

                        {{-- CITY --}}
                        <div class="col-md-2">
                            <label class="form-label">City</label>

                            <input type="text"
                                   name="city"
                                   class="form-control"
                                   placeholder="City"
                                   value="{{ request('city') }}">
                        </div>

                        {{-- STATUS --}}
                        <div class="col-md-2">
                            <label class="form-label">Status</label>

                            <select name="status" class="form-select">
                                <option value="">All Status</option>

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

                        {{-- BUTTONS --}}
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit"
                                    class="btn btn-primary me-1">
                                <i class="ti ti-search"></i>
                            </button>

                            <a href="{{ route('admin.membership-registrations.all') }}"
                               class="btn btn-light">
                                <i class="ti ti-refresh"></i>
                            </a>
                        </div>

                    </div>

                </form>

            </div>
        </div>


        {{-- TABLE --}}
        <div class="card">

            <div class="card-header">
                <h5 class="card-title mb-0">
                    Membership Registration List
                </h5>
            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th>#</th>
                                <th>Member</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Date of Birth</th>
                                <th>Gender</th>
                                <th>City</th>
                                <th>Membership</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($membershipRegistrations as $registration)

                                <tr>

                                    <td>
                                        {{ $membershipRegistrations->firstItem() + $loop->index }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $registration->full_name ?? '-' }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $registration->mobile_number ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $registration->email ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $registration->date_of_birth
                                            ? \Carbon\Carbon::parse($registration->date_of_birth)->format('d-m-Y')
                                            : '-' }}
                                    </td>

                                    <td>
                                        @if($registration->gender)
                                            {{ ucfirst($registration->gender) }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td>
                                        {{ $registration->city ?? '-' }}
                                    </td>

                                    <td>
                                        @if($registration->membership)
                                            <span class="badge bg-info">
                                                {{ $registration->membership }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td>

                                        @if($registration->status)

                                            <span class="badge bg-success">
                                                Active
                                            </span>

                                        @else

                                            <span class="badge bg-danger">
                                                Inactive
                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        <div class="d-flex gap-1">

                                            {{-- VIEW --}}
                                            {{-- <a href="{{ route(
                                                'admin.membership-registrations.show',
                                                $registration->id
                                            ) }}"
                                               class="btn btn-sm btn-light"
                                               title="View">

                                                <i class="ti ti-eye"></i>

                                            </a> --}}

                                            {{-- EDIT --}}
                                            {{-- <a href="{{ route(
                                                'admin.membership-registrations.edit',
                                                $registration->id
                                            ) }}"
                                               class="btn btn-sm btn-light"
                                               title="Edit">

                                                <i class="ti ti-edit"></i>

                                            </a> --}}

                                            {{-- DELETE --}}
                                            {{-- <form action="{{ route(
                                                'admin.membership-registrations.destroy',
                                                $registration->id
                                            ) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this registration?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-light text-danger"
                                                        title="Delete">

                                                    <i class="ti ti-trash"></i>

                                                </button>

                                            </form> --}}

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="10"
                                        class="text-center py-5">

                                        <div class="text-muted">

                                            <i class="ti ti-users"
                                               style="font-size:40px;"></i>

                                            <p class="mt-2 mb-0">
                                                No membership registrations found.
                                            </p>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            @if($membershipRegistrations->hasPages())

                <div class="card-footer">

                    {{ $membershipRegistrations->links() }}

                </div>

            @endif

        </div>

    </div>
</div>

@endsection