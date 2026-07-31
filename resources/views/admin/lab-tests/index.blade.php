<?php $page = 'lab-tests'; ?>

@extends('layout.mainlayout')

@section('content')

<style>
    .table-responsive {
        overflow-x: auto !important;
        overflow-y: visible !important;
    }

    .table {
        min-width: 1700px;
    }
</style>

<div class="page-wrapper">

    <div class="content">

        <!-- Page Header -->
        <div class="page-header">

            <div class="add-item d-flex">

                <div class="page-title">

                    <h4>Lab Tests</h4>

                    <h6>Manage Lab Tests</h6>

                </div>

            </div>

            <ul class="table-top-head">

                <li>

                    <a href="{{ route('admin.lab-tests.index') }}"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Refresh">

                        <i data-feather="rotate-ccw"></i>

                    </a>

                </li>

                <li>

                    <a id="collapse-header"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Collapse">

                        <i data-feather="chevron-up"></i>

                    </a>

                </li>

            </ul>

            <div class="page-btn">

                <a href="{{ route('admin.lab-tests.create') }}"
                    class="btn btn-added">

                    <i data-feather="plus-circle" class="me-2"></i>

                    Add Lab Test

                </a>

            </div>

        </div>
        <!-- /Page Header -->


        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                {{ session('success') }}

                <button class="btn-close" data-bs-dismiss="alert"></button>

            </div>

        @endif


        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show">

                {{ session('error') }}

                <button class="btn-close" data-bs-dismiss="alert"></button>

            </div>

        @endif


        <div class="card table-list-card">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table datanew">

                        <thead>

                            <tr>

                                <th width="60">#</th>

                                <th width="90">Image</th>

                                <th>Test Name</th>

                                <th>Test Code</th>

                                <th>Sample Type</th>

                                <th>Report Time</th>

                                <th>Fasting</th>

                                <th>Home Collection</th>

                                <th width="100">Status</th>

                                <th width="100" class="text-center">Action</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($labTests as $key => $labTest)

                                <tr>

                                    <td>{{ $key + 1 }}</td>

                                    <td>

                                        @if($labTest->image)

                                            <img src="{{ asset($labTest->image) }}"
                                                class="img-thumbnail"
                                                style="width:60px;height:60px;object-fit:cover;">

                                        @else

                                            <img src="{{ asset('assets/img/no-image.png') }}"
                                                class="img-thumbnail"
                                                style="width:60px;height:60px;object-fit:cover;">

                                        @endif

                                    </td>

                                    <td>

                                        <div>

                                            <strong>{{ $labTest->test_name }}</strong>

                                            <br>

                                            <small class="text-muted">

                                                {{ \Illuminate\Support\Str::limit($labTest->description,50) }}

                                            </small>

                                        </div>

                                    </td>

                                    <td>

                                        <span class="badge bg-light text-dark">

                                            {{ $labTest->test_code }}

                                        </span>

                                    </td>

                                    <td>

                                        {{ $labTest->sample_type }}

                                    </td>

                                    <td>

                                        {{ $labTest->report_time }}
                                        {{ $labTest->report_time_type }}

                                    </td>

                                    <td>

                                        @if($labTest->fasting_required)

                                            <span class="badge bg-success">

                                                Required

                                            </span>

                                        @else

                                            <span class="badge bg-danger">

                                                Not Required

                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        @if($labTest->home_collection)

                                            <span class="badge bg-success">

                                                Yes

                                            </span>

                                        @else

                                            <span class="badge bg-danger">

                                                No

                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        @if($labTest->status)

                                            <span class="badge bg-success">

                                                Active

                                            </span>

                                        @else

                                            <span class="badge bg-danger">

                                                Inactive

                                            </span>

                                        @endif

                                    </td>

                                    <td class="text-center">

                                        <div class="dropdown">

                                            <a href="javascript:void(0)"
                                                class="btn btn-sm btn-light"
                                                data-bs-toggle="dropdown">

                                                <i class="ti ti-dots-vertical"></i>

                                            </a>

                                            <div class="dropdown-menu dropdown-menu-end">

                                                <a class="dropdown-item"
                                                    href="{{ route('admin.lab-tests.show',$labTest->id) }}">

                                                    <i class="ti ti-eye me-2"></i>

                                                    View

                                                </a>

                                                <a class="dropdown-item"
                                                    href="{{ route('admin.lab-tests.edit',$labTest->id) }}">

                                                    <i class="ti ti-edit me-2"></i>

                                                    Edit

                                                </a>

                                                <form action="{{ route('admin.lab-tests.status',$labTest->id) }}"
                                                    method="POST">

                                                    @csrf

                                                    <button type="submit"
                                                        class="dropdown-item">

                                                        @if($labTest->status)

                                                            <i class="ti ti-lock me-2"></i>

                                                            Inactive

                                                        @else

                                                            <i class="ti ti-lock-open me-2"></i>

                                                            Active

                                                        @endif

                                                    </button>

                                                </form>

                                                <a href="javascript:void(0)"
                                                    class="dropdown-item text-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $labTest->id }}">

                                                    <i class="ti ti-trash me-2"></i>

                                                    Delete

                                                </a>

                                            </div>

                                        </div>

                                    </td>

                                </tr>

                                <!-- Delete Modal -->

                                <div class="modal fade"
                                    id="deleteModal{{ $labTest->id }}"
                                    tabindex="-1">

                                    <div class="modal-dialog">

                                        <div class="modal-content">

                                            <div class="modal-header">

                                                <h5 class="modal-title">

                                                    Delete Lab Test

                                                </h5>

                                                <button type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"></button>

                                            </div>

                                            <div class="modal-body">

                                                Are you sure you want to delete

                                                <strong>

                                                    {{ $labTest->test_name }}

                                                </strong>?

                                            </div>

                                            <div class="modal-footer">

                                                <button class="btn btn-secondary"
                                                    data-bs-dismiss="modal">

                                                    Cancel

                                                </button>

                                                <form action="{{ route('admin.lab-tests.destroy',$labTest->id) }}"
                                                    method="POST">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                        class="btn btn-danger">

                                                        Delete

                                                    </button>

                                                </form>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            @empty

                                <tr>

                                    <td colspan="10"
                                        class="text-center py-5">

                                        <h6>No Lab Tests Found</h6>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

$(document).ready(function(){

    if($('.datanew').length){

        $('.datanew').DataTable({

            responsive:true,

            autoWidth:false,

            ordering:true,

            pageLength:10,

            lengthMenu:[
                [10,25,50,100,-1],
                [10,25,50,100,"All"]
            ],

            language:{
                search:"",
                searchPlaceholder:"Search Lab Tests..."
            }

        });

    }

    if(typeof feather!=="undefined"){

        feather.replace();

    }

});

</script>

@endsection