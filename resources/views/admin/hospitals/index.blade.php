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

                                            <a href="{{ route('admin.hospitals.show', $hospital) }}" class="btn btn-info btn-sm">

                                                <i class="ti ti-eye"></i>

                                            </a>

                                            <a href="{{ route('admin.hospitals.edit', $hospital) }}" class="btn btn-warning btn-sm">

                                                <i class="ti ti-edit"></i>

                                            </a>

                                            <form action="{{ route('admin.hospitals.destroy', $hospital) }}" method="POST"
                                                class="d-inline">

                                                @csrf

                                                @method('DELETE')

                                                <button class="btn btn-danger btn-sm">

                                                    <i class="ti ti-trash"></i>

                                                </button>

                                            </form>

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