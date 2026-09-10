@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            <div class="page-header">

                <div class="page-title">
                    <h4>Hospitals</h4>
                    <h6>Manage Hospitals</h6>
                </div>

                <div class="page-btn">
                    <a href="{{ route('admin.hospitals.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus"></i>
                        Add Hospital
                    </a>
                </div>

            </div>

            <div class="card">
                <div class="card-body">

                        {{-- Filters --}}
                        <form method="GET" action="{{ route('admin.hospitals.index') }}" class="mb-4">

                            <div class="row g-3">

                                {{-- Hospital Name --}}
                                <div class="col-md-3">
                                    <label class="form-label">Hospital Name</label>
                                    <input type="text" name="hospital_name" class="form-control"
                                        placeholder="Enter hospital name" value="{{ request('hospital_name') }}">
                                </div>

                                {{-- Hospital Code --}}
                                <div class="col-md-2">
                                    <label class="form-label">Hospital Code</label>
                                    <input type="text" name="hospital_code" class="form-control" placeholder="Enter code"
                                        value="{{ request('hospital_code') }}">
                                </div>

                                {{-- Hospital Type --}}
                                <div class="col-md-2">
                                    <label class="form-label">Hospital Type</label>
                                    <select name="hospital_type" class="form-select">
                                        <option value="">All Types</option>

                                        @foreach($hospitalTypes as $type)
                                            <option value="{{ $type }}" {{ request('hospital_type') == $type ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Mobile --}}
                                <div class="col-md-2">
                                    <label class="form-label">Mobile</label>
                                    <input type="text" name="mobile" class="form-control" placeholder="Enter mobile"
                                        value="{{ request('mobile') }}">
                                </div>

                                {{-- City --}}
                                <div class="col-md-2">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" class="form-control" placeholder="Enter city"
                                        value="{{ request('city') }}">
                                </div>

                                {{-- Status --}}
                                <div class="col-md-1">
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

                            </div>

                            <div class="row mt-3">

                                <div class="col-md-12 d-flex gap-2">

                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-search me-1"></i>
                                        Filter
                                    </button>

                                    <a href="{{ route('admin.hospitals.index') }}" class="btn btn-secondary">
                                        <i class="ti ti-refresh me-1"></i>
                                        Reset
                                    </a>

                                </div>

                            </div>

                        </form>

                

                    <div class="table-responsive">

                        <table class="table datatable">

                            <thead>

                                <tr>

                                    <th>Logo</th>

                                    <th>Name</th>

                                    <th>Code</th>

                                    <th>Type</th>

                                    <th>Mobile</th>

                                    <th>City</th>

                                    <th>Status</th>

                                    <th>Action</th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach($hospitals as $hospital)

                                    <tr>

                                        <td>

                                            @if($hospital->logo)

                                                <img src="{{ asset($hospital->logo) }}" width="45" class="rounded">

                                            @endif

                                        </td>

                                        <td>{{ $hospital->hospital_name }}</td>

                                        <td>{{ $hospital->hospital_code }}</td>

                                        <td>{{ $hospital->hospital_type }}</td>

                                        <td>{{ $hospital->mobile }}</td>

                                        <td>{{ $hospital->city }}</td>

                                        <td>

                                            @if($hospital->status)

                                                <span class="badge bg-success">Active</span>

                                            @else

                                                <span class="badge bg-danger">Inactive</span>

                                            @endif

                                        </td>

                                        <td>

                                            <a href="{{ route('admin.hospitals.show', $hospital) }}"
                                                class="btn btn-info btn-sm">

                                                <i class="ti ti-eye"></i>

                                            </a>

                                            <a href="{{ route('admin.hospitals.edit', $hospital) }}"
                                                class="btn btn-warning btn-sm">

                                                <i class="ti ti-edit"></i>

                                            </a>

                                            {{-- <form action="{{ route('admin.hospitals.destroy', $hospital) }}" method="POST"
                                                class="d-inline">

                                                @csrf

                                                @method('DELETE')

                                                <button class="btn btn-danger btn-sm">

                                                    <i class="ti ti-trash"></i>

                                                </button>

                                            </form> --}}

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection