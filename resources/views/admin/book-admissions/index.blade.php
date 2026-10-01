@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- PAGE HEADER --}}
        <div class="page-header">
            <div class="row align-items-center">

                <div class="col">
                    <h4 class="page-title">
                        Book Admission Requests
                    </h4>

                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Book Admissions
                        </li>
                    </ul>
                </div>

            </div>
        </div>


        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        {{-- FILTER CARD --}}
        <div class="card">
            <div class="card-body">

                <form method="GET"
                      action="{{ route('admin.book-admissions.index') }}">

                    <div class="row g-3">

                        {{-- SEARCH --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Search
                            </label>

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Family member, doctor, procedure..."
                                value="{{ request('search') }}"
                            >

                        </div>


                        {{-- ADMISSION TYPE --}}
                        <div class="col-md-3">

                            <label class="form-label">
                                Admission Type
                            </label>

                            <select
                                name="admission_type"
                                class="form-select"
                            >

                                <option value="">
                                    All Types
                                </option>

                                <option
                                    value="general_admission"
                                    {{ request('admission_type') == 'general_admission' ? 'selected' : '' }}
                                >
                                    General Admission
                                </option>

                                <option
                                    value="surgery_admission"
                                    {{ request('admission_type') == 'surgery_admission' ? 'selected' : '' }}
                                >
                                    Surgery Admission
                                </option>

                            </select>

                        </div>


                        {{-- STATUS --}}
                        <div class="col-md-3">

                            <label class="form-label">
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-select"
                            >

                                <option value="">
                                    All Status
                                </option>

                                <option
                                    value="pending"
                                    {{ request('status') == 'pending' ? 'selected' : '' }}
                                >
                                    Pending
                                </option>

                                <option
                                    value="confirmed"
                                    {{ request('status') == 'confirmed' ? 'selected' : '' }}
                                >
                                    Confirmed
                                </option>

                                <option
                                    value="completed"
                                    {{ request('status') == 'completed' ? 'selected' : '' }}
                                >
                                    Completed
                                </option>

                                <option
                                    value="cancelled"
                                    {{ request('status') == 'cancelled' ? 'selected' : '' }}
                                >
                                    Cancelled
                                </option>

                            </select>

                        </div>


                        {{-- BUTTONS --}}
                        <div class="col-md-2 d-flex align-items-end gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="ti ti-search"></i>
                                Search
                            </button>

                            <a
                                href="{{ route('admin.book-admissions.index') }}"
                                class="btn btn-light"
                            >
                                Reset
                            </a>

                        </div>

                    </div>

                </form>

            </div>
        </div>


        {{-- LIST --}}
        <div class="card">

            <div class="card-header">
                <h5 class="card-title mb-0">
                    Admission Requests
                </h5>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead>
                            <tr>

                                <th>#</th>

                                <th>
                                    Family Member
                                </th>

                                <th>
                                    Admission Type
                                </th>

                                <th>
                                    Preferred Date
                                </th>

                                <th>
                                    Surgery / Procedure
                                </th>

                                <th>
                                    Preferred Doctor
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Created
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>
                        </thead>

                        <tbody>

                            @forelse($bookAdmissions as $key => $admission)

                                <tr>

                                    {{-- ID --}}
                                    <td>
                                        {{ $bookAdmissions->firstItem() + $key }}
                                    </td>


                                    {{-- FAMILY MEMBER --}}
                                    <td>

                                        @if($admission->familyMember)

                                            <strong>
                                                {{ $admission->familyMember->name }}
                                            </strong>

                                        @else

                                            <span class="text-muted">
                                                N/A
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ADMISSION TYPE --}}
                                    <td>

                                        @if($admission->admission_type === 'general_admission')

                                            <span class="badge bg-info">
                                                General Admission
                                            </span>

                                        @else

                                            <span class="badge bg-warning">
                                                Surgery Admission
                                            </span>

                                        @endif

                                    </td>


                                    {{-- DATE --}}
                                    <td>

                                        @if($admission->preferred_admission_date)

                                            {{ $admission->preferred_admission_date->format('d M Y') }}

                                        @else

                                            <span class="text-muted">
                                                N/A
                                            </span>

                                        @endif

                                    </td>


                                    {{-- PROCEDURE --}}
                                    <td>

                                        @if($admission->surgery_procedure)

                                            {{ $admission->surgery_procedure }}

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- DOCTOR --}}
                                    <td>

                                        @if($admission->preferredDoctor)

                                            {{ $admission->preferredDoctor->doctor_name }}

                                        @else

                                            <span class="text-muted">
                                                Not Selected
                                            </span>

                                        @endif

                                    </td>


                                    {{-- STATUS --}}
                                    <td>

                                        @switch($admission->status)

                                            @case('pending')

                                                <span class="badge bg-warning">
                                                    Pending
                                                </span>

                                                @break

                                            @case('confirmed')

                                                <span class="badge bg-success">
                                                    Confirmed
                                                </span>

                                                @break

                                            @case('completed')

                                                <span class="badge bg-primary">
                                                    Completed
                                                </span>

                                                @break

                                            @case('cancelled')

                                                <span class="badge bg-danger">
                                                    Cancelled
                                                </span>

                                                @break

                                            @default

                                                <span class="badge bg-secondary">
                                                    {{ ucfirst($admission->status) }}
                                                </span>

                                        @endswitch

                                    </td>


                                    {{-- CREATED --}}
                                    <td>

                                        {{ $admission->created_at->format('d M Y') }}

                                        <br>

                                        <small class="text-muted">
                                            {{ $admission->created_at->format('h:i A') }}
                                        </small>

                                    </td>


                                    {{-- ACTION --}}
                                    <td>

                                        <a
                                            href="{{ route('admin.book-admissions.show', $admission->id) }}"
                                            class="btn btn-sm btn-primary"
                                        >
                                            <i class="ti ti-eye"></i>
                                            View
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="9"
                                        class="text-center py-5"
                                    >

                                        <div class="text-muted">

                                            <i
                                                class="ti ti-inbox"
                                                style="font-size:40px;"
                                            ></i>

                                            <p class="mt-2 mb-0">
                                                No admission requests found.
                                            </p>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- PAGINATION --}}
                @if($bookAdmissions->hasPages())

                    <div class="mt-3">

                        {{ $bookAdmissions->links() }}

                    </div>

                @endif

            </div>

        </div>

    </div>
</div>

@endsection