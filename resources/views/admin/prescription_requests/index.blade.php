@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        <div class="page-header">
            <div class="page-title">
                <h4>Prescription Requests</h4>
                <h6>Manage prescription quotation requests</h6>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">

            <div class="card-header">
                <form method="GET"
                      action="{{ route('admin.prescription-requests.index') }}">

                    <div class="row">

                        <div class="col-md-4">
                            <label>Search</label>
                            <input type="text"
                                   name="search"
                                   class="form-control"
                                   value="{{ request('search') }}"
                                   placeholder="ID / Request Type / Status">
                        </div>

                        <div class="col-md-3">
                            <label>Request Type</label>

                            <select name="request_type"
                                    class="form-select">

                                <option value="">All</option>

                                <option value="medicines"
                                    {{ request('request_type') == 'medicines' ? 'selected' : '' }}>
                                    Medicines
                                </option>

                                <option value="lab_tests"
                                    {{ request('request_type') == 'lab_tests' ? 'selected' : '' }}>
                                    Lab Tests
                                </option>

                                <option value="both"
                                    {{ request('request_type') == 'both' ? 'selected' : '' }}>
                                    Medicines & Lab Tests
                                </option>

                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Status</label>

                            <select name="status"
                                    class="form-select">

                                <option value="">All</option>

                                @foreach([
                                    'pending',
                                    'reviewing',
                                    'quotation_sent',
                                    'approved',
                                    'rejected',
                                    'paid',
                                    'completed',
                                    'cancelled'
                                ] as $status)

                                    <option value="{{ $status }}"
                                        {{ request('status') == $status ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </option>

                                @endforeach

                            </select>
                        </div>

                        <div class="col-md-2 d-flex align-items-end gap-2">

                            <button type="submit"
                                    class="btn btn-primary">
                                Search
                            </button>

                            <a href="{{ route('admin.prescription-requests.index') }}"
                               class="btn btn-secondary">
                                Reset
                            </a>

                        </div>

                    </div>

                </form>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Family Member</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th width="180">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                        @forelse($requests as $requestItem)

                            <tr>

                                <td>
                                    {{ $requestItem->id }}
                                </td>

                                <td>
                                    {{ optional($requestItem->customer)->name ?? '-' }}
                                </td>

                                <td>
                                    {{ optional($requestItem->familyMember)->name ?? '-' }}
                                </td>

                                <td>
                                    <span class="badge bg-info">
                                        {{ ucfirst(str_replace('_', ' ', $requestItem->request_type)) }}
                                    </span>
                                </td>

                                <td>

                                    @php
                                        $statusClass = match($requestItem->status) {
                                            'pending' => 'bg-warning',
                                            'reviewing' => 'bg-info',
                                            'quotation_sent' => 'bg-primary',
                                            'approved' => 'bg-success',
                                            'paid' => 'bg-success',
                                            'completed' => 'bg-success',
                                            'rejected', 'cancelled' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp

                                    <span class="badge {{ $statusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $requestItem->status)) }}
                                    </span>

                                </td>

                                <td>
                                    {{ optional($requestItem->created_at)->format('d M Y h:i A') }}
                                </td>

                                <td>

                                    <a href="{{ route(
                                        'admin.prescription-requests.show',
                                        $requestItem->id
                                    ) }}"
                                       class="btn btn-sm btn-primary">
                                        View
                                    </a>

                                    <form action="{{ route(
                                        'admin.prescription-requests.destroy',
                                        $requestItem->id
                                    ) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Delete this prescription request?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-danger">
                                            Delete
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7"
                                    class="text-center">
                                    No prescription requests found.
                                </td>
                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="mt-3">
                    {{ $requests->links() }}
                </div>

            </div>

        </div>

    </div>
</div>

@endsection