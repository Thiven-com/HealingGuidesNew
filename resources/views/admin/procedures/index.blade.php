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

                    <h4>Procedures</h4>

                    <h6>Manage procedures and procedure benefits</h6>

                </div>

            </div>


            <ul class="table-top-head">

                <li>
                    <a href="javascript:void(0);"
                       class="refresh-page"
                       data-bs-toggle="tooltip"
                       title="Refresh">

                        <i class="ti ti-refresh"></i>

                    </a>
                </li>

                <li>
                    <a href="javascript:void(0);"
                       class="expand-table"
                       data-bs-toggle="tooltip"
                       title="Fullscreen">

                        <i class="ti ti-arrows-maximize"></i>

                    </a>
                </li>

            </ul>


            <div class="page-btn">

                <a href="{{ route('admin.procedures.create') }}"
                   class="btn btn-primary">

                    <i class="ti ti-plus me-1"></i>

                    Add Procedure

                </a>

            </div>

        </div>


        {{-- =========================================================
            SUCCESS MESSAGE
        ========================================================== --}}

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- =========================================================
            ERROR MESSAGE
        ========================================================== --}}

        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show">

                {{ session('error') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- =========================================================
            FILTER
        ========================================================== --}}

        <div class="card">

            <div class="card-body">

                <form method="GET"
                      action="{{ route('admin.procedures.index') }}">

                    <div class="row g-3">


                        {{-- SEARCH --}}

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
                                       value="{{ request('search') }}"
                                       placeholder="Search procedure...">

                            </div>

                        </div>


                        {{-- SPECIALIZATION --}}

                        <div class="col-md-3">

                            <label class="form-label">

                                Specialization

                            </label>

                            <select name="specialization_id"
                                    class="form-select">

                                <option value="">

                                    All Specializations

                                </option>

                                @foreach($specializations as $specialization)

                                    <option value="{{ $specialization->id }}"
                                        {{ request('specialization_id') == $specialization->id ? 'selected' : '' }}>

                                        {{ $specialization->specialization_name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- STATUS --}}

                        <div class="col-md-2">

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


                        {{-- BUTTONS --}}

                        <div class="col-md-3 d-flex align-items-end gap-2">

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="ti ti-filter me-1"></i>

                                Filter

                            </button>


                            <a href="{{ route('admin.procedures.index') }}"
                               class="btn btn-light">

                                <i class="ti ti-refresh me-1"></i>

                                Reset

                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- =========================================================
            PROCEDURES TABLE
        ========================================================== --}}

        <div class="card">

            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="card-title mb-0">

                            Procedure List

                        </h5>

                        <small class="text-muted">

                            {{ $procedures->total() }} procedures found

                        </small>

                    </div>

                </div>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover">

                        <thead>

                            <tr>

                                <th style="width:50px;">

                                    <label class="checkboxs">

                                        <input type="checkbox"
                                               id="select-all">

                                        <span class="checkmarks"></span>

                                    </label>

                                </th>


                                <th>

                                    Procedure

                                </th>


                                <th>

                                    Specialization

                                </th>


                                <th>

                                    Price

                                </th>


                                <th>

                                    Benefits

                                </th>


                                <th>

                                    Doctors

                                </th>


                                <th>

                                    Order

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

                        @forelse($procedures as $procedure)

                            <tr>


                                {{-- CHECKBOX --}}

                                <td>

                                    <label class="checkboxs">

                                        <input type="checkbox"
                                               class="procedure-checkbox"
                                               value="{{ $procedure->id }}">

                                        <span class="checkmarks"></span>

                                    </label>

                                </td>


                                {{-- PROCEDURE --}}

                                <td>

                                    <div class="d-flex align-items-center">

                                        <div class="avatar avatar-md me-2">

                                            @if($procedure->image)

                                                <img src="{{ asset( $procedure->image) }}"
                                                     alt="{{ $procedure->name }}"
                                                     class="rounded"
                                                     style="width:45px;height:45px;object-fit:cover;">

                                            @else

                                                <span class="avatar-title bg-light-primary text-primary rounded">

                                                    @if($procedure->icon)

                                                        <i class="{{ $procedure->icon }}"></i>

                                                    @else

                                                        <i class="ti ti-stethoscope"></i>

                                                    @endif

                                                </span>

                                            @endif

                                        </div>


                                        <div>

                                            <h6 class="mb-0">

                                                <a href="{{ route('admin.procedures.show', $procedure->id) }}"
                                                   class="text-dark">

                                                    {{ $procedure->name }}

                                                </a>

                                            </h6>


                                            @if($procedure->short_description)

                                                <small class="text-muted">

                                                    {{ \Illuminate\Support\Str::limit(
                                                        $procedure->short_description,
                                                        50
                                                    ) }}

                                                </small>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- SPECIALIZATION --}}

                                <td>

                                    @if($procedure->specialization)

                                        <span class="badge bg-light-info text-info">

                                            {{ $procedure->specialization->specialization_name ?? '' }}

                                        </span>

                                    @else

                                        <span class="text-muted">

                                            —

                                        </span>

                                    @endif

                                </td>


                                {{-- PRICE --}}

                                <td>

                                    <strong>

                                        ₹{{ number_format($procedure->price, 2) }}

                                    </strong>

                                </td>


                                {{-- BENEFITS --}}

                                <td>

                                    <a href="{{ route(
                                        'admin.procedures.show',
                                        $procedure->id
                                    ) }}">

                                        <span class="badge bg-light-primary text-primary">

                                            {{ $procedure->benefits_count ?? $procedure->benefits->count() }}

                                            Benefits

                                        </span>

                                    </a>

                                </td>


                                {{-- DOCTORS --}}

                                <td>

                                    <span class="badge bg-light-success text-success">

                                        {{ $procedure->doctors_count ?? $procedure->doctors->count() }}

                                        Doctors

                                    </span>

                                </td>


                                {{-- DISPLAY ORDER --}}

                                <td>

                                    {{ $procedure->display_order }}

                                </td>


                                {{-- STATUS --}}

                                <td>

                                    <div class="form-check form-switch">

                                        <input type="checkbox"
                                               class="form-check-input procedure-status-toggle"
                                               data-id="{{ $procedure->id }}"
                                               {{ $procedure->status ? 'checked' : '' }}>

                                    </div>

                                </td>


                                {{-- ACTION --}}

                                <td>

                                    <div class="action-table-data">

                                        <div class="edit-delete-action">


                                            {{-- VIEW --}}

                                            <a class="me-2 p-2"
                                               href="{{ route(
                                                   'admin.procedures.show',
                                                   $procedure->id
                                               ) }}"
                                               data-bs-toggle="tooltip"
                                               title="View">

                                                <i class="ti ti-eye"></i>

                                            </a>


                                            {{-- EDIT --}}

                                            <a class="me-2 p-2"
                                               href="{{ route(
                                                   'admin.procedures.edit',
                                                   $procedure->id
                                               ) }}"
                                               data-bs-toggle="tooltip"
                                               title="Edit">

                                                <i class="ti ti-edit"></i>

                                            </a>


                                            {{-- DELETE --}}

                                            <form action="{{ route(
                                                'admin.procedures.destroy',
                                                $procedure->id
                                            ) }}"
                                                  method="POST"
                                                  class="d-inline delete-procedure-form">

                                                @csrf

                                                @method('DELETE')

                                                <button type="submit"
                                                        class="confirm-text p-2 border-0 bg-transparent text-danger"
                                                        data-bs-toggle="tooltip"
                                                        title="Delete">

                                                    <i class="ti ti-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center py-5">

                                    <div class="empty-state">

                                        <i class="ti ti-stethoscope"
                                           style="font-size:50px;color:#adb5bd;">
                                        </i>

                                        <h5 class="mt-3">

                                            No Procedures Found

                                        </h5>

                                        <p class="text-muted">

                                            No procedures match your current filters.

                                        </p>

                                        <a href="{{ route('admin.procedures.create') }}"
                                           class="btn btn-primary">

                                            <i class="ti ti-plus me-1"></i>

                                            Add Procedure

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- =====================================================
                PAGINATION
            ====================================================== --}}

            @if($procedures->hasPages())

                <div class="card-footer">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <div class="text-muted">

                            Showing

                            {{ $procedures->firstItem() }}

                            to

                            {{ $procedures->lastItem() }}

                            of

                            {{ $procedures->total() }}

                            entries

                        </div>

                        <div>

                            {{ $procedures->links() }}

                        </div>

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>


{{-- =============================================================
    DELETE CONFIRMATION
============================================================== --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /*
        |--------------------------------------------------------------------------
        | Select All
        |--------------------------------------------------------------------------
        */

        const selectAll =
            document.getElementById('select-all');


        if (selectAll) {

            selectAll.addEventListener(
                'change',
                function () {

                    document
                        .querySelectorAll(
                            '.procedure-checkbox'
                        )
                        .forEach(function (checkbox) {

                            checkbox.checked =
                                selectAll.checked;

                        });

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Delete Confirmation
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.delete-procedure-form'
            )
            .forEach(function (form) {

                form.addEventListener(
                    'submit',
                    function (event) {

                        if (
                            !confirm(
                                'Are you sure you want to delete this procedure?'
                            )
                        ) {

                            event.preventDefault();

                        }

                    }
                );

            });


        /*
        |--------------------------------------------------------------------------
        | Status Toggle
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.procedure-status-toggle'
            )
            .forEach(function (toggle) {

                toggle.addEventListener(
                    'change',
                    function () {

                        const id =
                            this.dataset.id;

                        const url =
                            "{{ url('admin/procedures') }}"
                            + "/"
                            + id
                            + "/status";


                        fetch(url, {

                            method: 'POST',

                            headers: {

                                'X-CSRF-TOKEN':
                                    '{{ csrf_token() }}',

                                'Accept':
                                    'application/json',

                                'Content-Type':
                                    'application/json',

                            },

                            body: JSON.stringify({})

                        })
                        .then(function (response) {

                            if (!response.ok) {

                                throw new Error(
                                    'Unable to update status.'
                                );

                            }

                            return response.json()
                                .catch(function () {

                                    return {};

                                });

                        })
                        .catch(function (error) {

                            console.error(error);

                            /*
                            |--------------------------------------------------------------------------
                            | Restore Toggle
                            |--------------------------------------------------------------------------
                            */

                            toggle.checked =
                                !toggle.checked;

                            alert(
                                'Unable to update procedure status.'
                            );

                        });

                    }
                );

            });

    }
);

</script>

@endsection