<?php $page = 'marketing-staff'; ?>

@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            {{-- PAGE HEADER --}}
            <div class="page-header d-flex align-items-center justify-content-between">
                <div class="page-title">
                    <h4>Marketing Staff</h4>
                    <h6>Manage Marketing Staff</h6>
                </div>

                {{-- <div class="page-btn">
                    <a href="{{ route('admin.marketing-staff.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i>
                        Add Marketing Staff
                    </a>
                </div> --}}
            </div>


            {{-- ALERTS --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif


            {{-- VALIDATION --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            {{-- FILTER --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-filter me-1"></i>
                        Filter Marketing Staff
                    </h5>
                </div>

                <div class="card-body">

                    <form method="GET" action="{{ route('admin.marketing-staff.all') }}">

                        <div class="row g-3">

                            {{-- Search --}}
                            <div class="col-md-4">
                                <label class="form-label">Search</label>
                                <input type="text" name="search" class="form-control"
                                    placeholder="Name, Employee Code, Mobile, Email..." value="{{ request('search') }}">
                            </div>

                            {{-- Gender --}}
                            <div class="col-md-2">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="">All</option>

                                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>
                                        Male
                                    </option>

                                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>
                                        Female
                                    </option>

                                    <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>
                                        Other
                                    </option>
                                </select>
                            </div>

                            {{-- Designation --}}
                            <div class="col-md-2">
                                <label class="form-label">Designation</label>

                                <input type="text" name="designation" class="form-control" placeholder="Designation"
                                    value="{{ request('designation') }}">
                            </div>

                            {{-- City --}}
                            <div class="col-md-2">
                                <label class="form-label">City</label>

                                <input type="text" name="city" class="form-control" placeholder="City"
                                    value="{{ request('city') }}">
                            </div>

                            {{-- Status --}}
                            <div class="col-md-2">
                                <label class="form-label">Status</label>

                                <select name="status" class="form-select">
                                    <option value="">All</option>

                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                            </div>

                            {{-- Availability --}}
                            <div class="col-md-2">
                                <label class="form-label">Availability</label>

                                <select name="is_available" class="form-select">
                                    <option value="">All</option>

                                    <option value="1" {{ request('is_available') === '1' ? 'selected' : '' }}>
                                        Available
                                    </option>

                                    <option value="0" {{ request('is_available') === '0' ? 'selected' : '' }}>
                                        Not Available
                                    </option>
                                </select>
                            </div>

                            {{-- State --}}
                            <div class="col-md-2">
                                <label class="form-label">State</label>

                                <input type="text" name="state" class="form-control" placeholder="State"
                                    value="{{ request('state') }}">
                            </div>

                            {{-- Buttons --}}
                            <div class="col-md-3 d-flex align-items-end gap-2">

                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-search me-1"></i>
                                    Filter
                                </button>

                                <a href="{{ route('admin.marketing-staff.all') }}" class="btn btn-light">
                                    <i class="ti ti-refresh me-1"></i>
                                    Reset
                                </a>

                            </div>

                        </div>

                    </form>

                </div>
            </div>


            {{-- TABLE --}}
            <div class="card">

                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">
                        Marketing Staff List
                    </h5>

                    <span class="badge bg-light text-dark">
                        {{ $marketingStaff->total() }} Staff
                    </span>
                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>
                                    <th>#</th>
                                    <th>Staff</th>
                                    <th>Employee Code</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Designation</th>
                                    <th>Location</th>
                                    <th>Joining Date</th>
                                    <th>Availability</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>

                            </thead>

                            <tbody>

                                @forelse($marketingStaff as $key => $staff)

                                                        <tr>

                                                            <td>
                                                                {{ $marketingStaff->firstItem() + $key }}
                                                            </td>


                                                            {{-- Staff --}}
                                                            <td>
                                                                <div class="d-flex align-items-center">

                                                                    @if($staff->photo)
                                                                        <img src="{{ asset('storage/' . $staff->photo) }}" alt="{{ $staff->name }}"
                                                                            class="rounded-circle me-2" width="40" height="40"
                                                                            style="object-fit: cover;">
                                                                    @else
                                                                        <div class="avatar avatar-sm bg-primary text-white rounded-circle me-2">
                                                                            {{ strtoupper(substr($staff->name ?? 'M', 0, 1)) }}
                                                                        </div>
                                                                    @endif

                                                                    <div>
                                                                        <strong>
                                                                            {{ $staff->name ?? '-' }}
                                                                        </strong>

                                                                        @if($staff->gender)
                                                                            <small class="d-block text-muted">
                                                                                {{ ucfirst($staff->gender) }}
                                                                            </small>
                                                                        @endif
                                                                    </div>

                                                                </div>
                                                            </td>


                                                            {{-- Employee Code --}}
                                                            <td>
                                                                {{ $staff->employee_code ?? '-' }}
                                                            </td>


                                                            {{-- Mobile --}}
                                                            <td>
                                                                {{ $staff->mobile ?? '-' }}
                                                            </td>


                                                            {{-- Email --}}
                                                            <td>
                                                                {{ $staff->email ?? '-' }}
                                                            </td>


                                                            {{-- Designation --}}
                                                            <td>
                                                                {{ $staff->designation ?? '-' }}
                                                            </td>


                                                            {{-- Location --}}
                                                            <td>
                                                                @if($staff->city || $staff->state)

                                                                    {{ $staff->city ?? '' }}

                                                                    @if($staff->city && $staff->state)
                                                                        ,
                                                                    @endif

                                                                    {{ $staff->state ?? '' }}

                                                                @else
                                                                    -
                                                                @endif
                                                            </td>


                                                            {{-- Joining Date --}}
                                                            <td>
                                                                {{ $staff->joining_date
                                    ? \Carbon\Carbon::parse($staff->joining_date)->format('d-m-Y')
                                    : '-' }}
                                                            </td>


                                                            {{-- Availability --}}
                                                            <td>

                                                                @if($staff->is_available)
                                                                    <span class="badge bg-success">
                                                                        Available
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-warning">
                                                                        Not Available
                                                                    </span>
                                                                @endif

                                                            </td>


                                                            {{-- Status --}}
                                                            <td>

                                                                @if($staff->status)
                                                                    <span class="badge bg-success">
                                                                        Active
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-danger">
                                                                        Inactive
                                                                    </span>
                                                                @endif

                                                            </td>


                                                            {{-- Actions --}}
                                                            <td class="text-end">

                                                                <div class="d-flex justify-content-end gap-1">

                                                                    {{-- <a href="{{ route('admin.marketing-staff.show', $staff->id) }}"
                                                                        class="btn btn-sm btn-light" title="View">
                                                                        <i class="ti ti-eye"></i>
                                                                    </a>

                                                                    <a href="{{ route('admin.marketing-staff.edit', $staff->id) }}"
                                                                        class="btn btn-sm btn-light" title="Edit">
                                                                        <i class="ti ti-edit"></i>
                                                                    </a>

                                                                    <form method="POST"
                                                                        action="{{ route('admin.marketing-staff.destroy', $staff->id) }}"
                                                                        onsubmit="return confirm('Are you sure you want to delete this staff?');">

                                                                        @csrf
                                                                        @method('DELETE')

                                                                        <button type="submit" class="btn btn-sm btn-light text-danger"
                                                                            title="Delete">
                                                                            <i class="ti ti-trash"></i>
                                                                        </button>

                                                                    </form> --}}

                                                                </div>

                                                            </td>

                                                        </tr>

                                @empty

                                    <tr>
                                        <td colspan="11" class="text-center py-5">

                                            <i class="ti ti-users" style="font-size:50px;"></i>

                                            <h5 class="mt-3">
                                                No Marketing Staff Found
                                            </h5>

                                            <p class="text-muted">
                                                No marketing staff records are available.
                                            </p>

                                            {{-- <a href="{{ route('admin.marketing-staff.create') }}" class="btn btn-primary">
                                                <i class="ti ti-plus me-1"></i>
                                                Add Marketing Staff
                                            </a> --}}

                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>


                @if($marketingStaff->hasPages())

                    <div class="card-footer">
                        {{ $marketingStaff->links() }}
                    </div>

                @endif

            </div>

        </div>
    </div>

@endsection