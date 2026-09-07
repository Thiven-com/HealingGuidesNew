
<?php $page = 'marketing-leads'; ?>

@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            {{-- =========================================================
                PAGE HEADER
            ========================================================== --}}
            <div class="page-header">

                <div class="add-item d-flex">

                    <div class="page-title">

                        <h4>Marketing Leads</h4>

                        <h6>Manage Marketing Leads</h6>

                    </div>

                </div>

                <ul class="table-top-head">

                    <li>
                        <a href="javascript:void(0);"
                            data-bs-toggle="tooltip"
                            title="Refresh"
                            onclick="window.location.reload();">

                            <i data-feather="refresh-cw"></i>

                        </a>
                    </li>

                    <li>
                        <a id="collapse-header"
                            data-bs-toggle="tooltip"
                            title="Collapse">

                            <i data-feather="chevron-up"></i>

                        </a>
                    </li>

                </ul>

                {{-- <div class="page-btn">

                    <a href="{{ route('admin.marketing-leads.create') }}"
                        class="btn btn-primary">

                        <i data-feather="plus" class="me-2"></i>

                        Add Lead

                    </a>

                </div> --}}

            </div>


            {{-- =========================================================
                SUCCESS MESSAGE
            ========================================================== --}}
            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show"
                    role="alert">

                    {{ session('success') }}

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                    </button>

                </div>

            @endif


            {{-- =========================================================
                ERROR MESSAGE
            ========================================================== --}}
            @if(session('error'))

                <div class="alert alert-danger alert-dismissible fade show"
                    role="alert">

                    {{ session('error') }}

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                    </button>

                </div>

            @endif


            {{-- =========================================================
                VALIDATION ERRORS
            ========================================================== --}}
            @if($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- =========================================================
                FILTERS
            ========================================================== --}}
            <div class="card mb-3">

                <div class="card-header">

                    <h5 class="card-title mb-0">
                        Filter Leads
                    </h5>

                </div>

                <div class="card-body">

                    <form method="GET"
                        action="{{ route('admin.marketing-leads.all') }}">

                        <div class="row g-3">


                            {{-- Search --}}
                            <div class="col-md-3">

                                <label class="form-label">
                                    Search
                                </label>

                                <input type="text"
                                    name="search"
                                    class="form-control"
                                    placeholder="Name / Mobile / Email"
                                    value="{{ request('search') }}">

                            </div>


                            {{-- Lead Type --}}
                            <div class="col-md-3">

                                <label class="form-label">
                                    Lead Type
                                </label>

                                <select name="lead_type"
                                    class="form-select">

                                    <option value="">
                                        All Lead Types
                                    </option>

                                    <option value="hospital"
                                        {{ request('lead_type') == 'hospital' ? 'selected' : '' }}>
                                        Hospital
                                    </option>

                                    <option value="doctor"
                                        {{ request('lead_type') == 'doctor' ? 'selected' : '' }}>
                                        Doctor
                                    </option>

                                    <option value="organization"
                                        {{ request('lead_type') == 'organization' ? 'selected' : '' }}>
                                        Organization
                                    </option>

                                    <option value="other"
                                        {{ request('lead_type') == 'other' ? 'selected' : '' }}>
                                        Other
                                    </option>

                                </select>

                            </div>


                            {{-- Lead Status --}}
                            <div class="col-md-3">

                                <label class="form-label">
                                    Lead Status
                                </label>

                                <select name="lead_status"
                                    class="form-select">

                                    <option value="">
                                        All Status
                                    </option>

                                    <option value="new"
                                        {{ request('lead_status') == 'new' ? 'selected' : '' }}>
                                        New
                                    </option>

                                    <option value="contacted"
                                        {{ request('lead_status') == 'contacted' ? 'selected' : '' }}>
                                        Contacted
                                    </option>

                                    <option value="follow_up"
                                        {{ request('lead_status') == 'follow_up' ? 'selected' : '' }}>
                                        Follow Up
                                    </option>

                                    <option value="interested"
                                        {{ request('lead_status') == 'interested' ? 'selected' : '' }}>
                                        Interested
                                    </option>

                                    <option value="not_interested"
                                        {{ request('lead_status') == 'not_interested' ? 'selected' : '' }}>
                                        Not Interested
                                    </option>

                                    <option value="converted"
                                        {{ request('lead_status') == 'converted' ? 'selected' : '' }}>
                                        Converted
                                    </option>

                                    <option value="closed"
                                        {{ request('lead_status') == 'closed' ? 'selected' : '' }}>
                                        Closed
                                    </option>

                                </select>

                            </div>


                            {{-- Priority --}}
                            <div class="col-md-3">

                                <label class="form-label">
                                    Priority
                                </label>

                                <select name="priority"
                                    class="form-select">

                                    <option value="">
                                        All Priority
                                    </option>

                                    <option value="high"
                                        {{ request('priority') == 'high' ? 'selected' : '' }}>
                                        High
                                    </option>

                                    <option value="normal"
                                        {{ request('priority') == 'normal' ? 'selected' : '' }}>
                                        Normal
                                    </option>

                                    <option value="low"
                                        {{ request('priority') == 'low' ? 'selected' : '' }}>
                                        Low
                                    </option>

                                </select>

                            </div>


                            {{-- Source --}}
                            <div class="col-md-3">

                                <label class="form-label">
                                    Source
                                </label>

                                <input type="text"
                                    name="source"
                                    class="form-control"
                                    placeholder="Lead source"
                                    value="{{ request('source') }}">

                            </div>


                            {{-- City --}}
                            <div class="col-md-3">

                                <label class="form-label">
                                    City
                                </label>

                                <input type="text"
                                    name="city"
                                    class="form-control"
                                    placeholder="Enter city"
                                    value="{{ request('city') }}">

                            </div>


                            {{-- State --}}
                            <div class="col-md-3">

                                <label class="form-label">
                                    State
                                </label>

                                <input type="text"
                                    name="state"
                                    class="form-control"
                                    placeholder="Enter state"
                                    value="{{ request('state') }}">

                            </div>


                            {{-- Marketing Staff --}}
                            <div class="col-md-3">

                                <label class="form-label">
                                    Marketing Staff
                                </label>

                                <select name="marketing_staff_id"
                                    class="form-select">

                                    <option value="">
                                        All Staff
                                    </option>

                                    @foreach($marketingStaff as $staff)

                                        <option value="{{ $staff->id }}"
                                            {{ request('marketing_staff_id') == $staff->id ? 'selected' : '' }}>

                                            {{ $staff->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Buttons --}}
                            <div class="col-md-12">

                                <div class="d-flex gap-2">

                                    <button type="submit"
                                        class="btn btn-primary">

                                        <i data-feather="filter"
                                            class="me-1">
                                        </i>

                                        Filter

                                    </button>


                                    <a href="{{ route('admin.marketing-leads.all') }}"
                                        class="btn btn-light">

                                        <i data-feather="rotate-ccw"
                                            class="me-1">
                                        </i>

                                        Reset

                                    </a>

                                </div>

                            </div>

                        </div>

                    </form>

                </div>

            </div>


            {{-- =========================================================
                LEADS TABLE
            ========================================================== --}}
            <div class="card">

                <div class="card-header">

                    <div class="d-flex align-items-center justify-content-between">

                        <h5 class="card-title mb-0">
                            Marketing Leads List
                        </h5>

                        <span class="badge bg-light text-dark">

                            {{ $leads->total() }}

                            Leads

                        </span>

                    </div>

                </div>


                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table datanew">

                            <thead>

                                <tr>

                                    <th>
                                        #
                                    </th>

                                    <th>
                                        Lead
                                    </th>

                                    <th>
                                        Lead Type
                                    </th>

                                    <th>
                                        Organization
                                    </th>

                                    <th>
                                        Contact Person
                                    </th>

                                    <th>
                                        Specialization
                                    </th>

                                    <th>
                                        Location
                                    </th>

                                    <th>
                                        Source
                                    </th>

                                    <th>
                                        Priority
                                    </th>

                                    <th>
                                        Lead Status
                                    </th>

                                    <th>
                                        Next Follow-up
                                    </th>

                                    <th>
                                        Conversion
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th class="text-end">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($leads as $key => $lead)

                                    <tr>


                                        {{-- ID --}}
                                        <td>

                                            {{ $leads->firstItem() + $key }}

                                        </td>


                                        {{-- Lead --}}
                                        <td>

                                            <div>

                                                <h6 class="mb-1">

                                                    {{ $lead->name ?? '-' }}

                                                </h6>

                                                @if($lead->mobile)

                                                    <span class="d-block text-muted">

                                                        {{ $lead->mobile }}

                                                    </span>

                                                @endif

                                                @if($lead->email)

                                                    <small class="text-muted">

                                                        {{ $lead->email }}

                                                    </small>

                                                @endif

                                            </div>

                                        </td>


                                        {{-- Lead Type --}}
                                        <td>

                                            @if($lead->lead_type)

                                                <span class="badge bg-light-primary text-primary">

                                                    {{ ucwords(str_replace('_', ' ', $lead->lead_type)) }}

                                                </span>

                                            @else

                                                -

                                            @endif

                                        </td>


                                        {{-- Organization --}}
                                        <td>

                                            {{ $lead->organization_name ?? '-' }}

                                        </td>


                                        {{-- Contact Person --}}
                                        <td>

                                            {{ $lead->contact_person ?? '-' }}

                                        </td>


                                        {{-- Specialization --}}
                                        <td>

                                            @if($lead->specialization)

                                                {{ $lead->specialization }}

                                                @if($lead->qualification)

                                                    <small class="d-block text-muted">

                                                        {{ $lead->qualification }}

                                                    </small>

                                                @endif

                                            @else

                                                -

                                            @endif

                                        </td>


                                        {{-- Location --}}
                                        <td>

                                            @if($lead->city || $lead->state)

                                                {{ $lead->city ?? '' }}

                                                @if($lead->city && $lead->state)
                                                    ,
                                                @endif

                                                {{ $lead->state ?? '' }}

                                            @else

                                                -

                                            @endif

                                        </td>


                                        {{-- Source --}}
                                        <td>

                                            {{ $lead->source ?? '-' }}

                                        </td>


                                        {{-- Priority --}}
                                        <td>

                                            @if($lead->priority === 'high')

                                                <span class="badge bg-danger">

                                                    High

                                                </span>

                                            @elseif($lead->priority === 'low')

                                                <span class="badge bg-info">

                                                    Low

                                                </span>

                                            @else

                                                <span class="badge bg-warning text-dark">

                                                    Normal

                                                </span>

                                            @endif

                                        </td>


                                        {{-- Lead Status --}}
                                        <td>

                                            @switch($lead->lead_status)

                                                @case('new')

                                                    <span class="badge bg-primary">
                                                        New
                                                    </span>

                                                    @break

                                                @case('contacted')

                                                    <span class="badge bg-info">
                                                        Contacted
                                                    </span>

                                                    @break

                                                @case('follow_up')

                                                    <span class="badge bg-warning text-dark">
                                                        Follow Up
                                                    </span>

                                                    @break

                                                @case('interested')

                                                    <span class="badge bg-success">
                                                        Interested
                                                    </span>

                                                    @break

                                                @case('not_interested')

                                                    <span class="badge bg-danger">
                                                        Not Interested
                                                    </span>

                                                    @break

                                                @case('converted')

                                                    <span class="badge bg-success">
                                                        Converted
                                                    </span>

                                                    @break

                                                @case('closed')

                                                    <span class="badge bg-secondary">
                                                        Closed
                                                    </span>

                                                    @break

                                                @default

                                                    <span class="badge bg-light text-dark">
                                                        -
                                                    </span>

                                            @endswitch

                                        </td>


                                        {{-- Follow-up --}}
                                        <td>

                                            @if($lead->next_followup_at)

                                                <span>

                                                    {{ \Carbon\Carbon::parse($lead->next_followup_at)->format('d M Y') }}

                                                </span>

                                                <small class="d-block text-muted">

                                                    {{ \Carbon\Carbon::parse($lead->next_followup_at)->format('h:i A') }}

                                                </small>

                                            @else

                                                -

                                            @endif

                                        </td>


                                        {{-- Conversion --}}
                                        <td>

                                            @if($lead->converted_at)

                                                <span class="badge bg-success">

                                                    Converted

                                                </span>

                                                <small class="d-block text-muted">

                                                    {{ \Carbon\Carbon::parse($lead->converted_at)->format('d M Y') }}

                                                </small>

                                            @else

                                                <span class="badge bg-light text-dark">

                                                    Not Converted

                                                </span>

                                            @endif

                                        </td>


                                        {{-- Active Status --}}
                                        <td>

                                            @if($lead->status)

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

                                            <div class="edit-delete-action">


                                                {{-- View --}}
                                                {{-- <a href="{{ route('admin.marketing-leads.show', $lead->id) }}"
                                                    class="me-2 p-2"
                                                    data-bs-toggle="tooltip"
                                                    title="View">

                                                    <i data-feather="eye"></i>

                                                </a> --}}


                                                {{-- Edit --}}
                                                {{-- <a href="{{ route('admin.marketing-leads.edit', $lead->id) }}"
                                                    class="me-2 p-2"
                                                    data-bs-toggle="tooltip"
                                                    title="Edit">

                                                    <i data-feather="edit"></i>

                                                </a> --}}


                                                {{-- Delete --}}
                                                {{-- <form action="{{ route('admin.marketing-leads.destroy', $lead->id) }}"
                                                    method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this lead?');">

                                                    @csrf

                                                    @method('DELETE')

                                                    <button type="submit"
                                                        class="btn p-2 text-danger"
                                                        data-bs-toggle="tooltip"
                                                        title="Delete">

                                                        <i data-feather="trash-2"></i>

                                                    </button>

                                                </form> --}}

                                            </div>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="14"
                                            class="text-center py-5">

                                            <div class="text-muted">

                                                <i data-feather="users"
                                                    style="width:50px;height:50px;">
                                                </i>

                                                <h5 class="mt-3">

                                                    No Marketing Leads Found

                                                </h5>

                                                <p class="mb-3">

                                                    No marketing leads have been added yet.

                                                </p>

                                                {{-- <a href="{{ route('admin.marketing-leads.create') }}"
                                                    class="btn btn-primary">

                                                    <i data-feather="plus"
                                                        class="me-2">
                                                    </i>

                                                    Add Lead

                                                </a> --}}

                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- Pagination --}}
                    @if($leads->hasPages())

                        <div class="mt-3">

                            {{ $leads->links() }}

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FEATHER ICONS
    ========================================================== --}}
    <script>

        document.addEventListener("DOMContentLoaded", function () {

            if (typeof feather !== "undefined") {

                feather.replace();

            }

        });

    </script>

@endsection
