@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            {{-- PAGE HEADER --}}
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Add Procedure Category</h4>
                        <h6>Create and manage procedure categories</h6>
                    </div>
                </div>

                <div class="page-btn">
                    <a href="{{ route('admin.procedure-categories.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Back to Categories
                    </a>
                </div>
            </div>

            {{-- SUCCESS MESSAGE --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- FORM CARD --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-category me-2"></i>
                        Procedure Category Details
                    </h5>
                </div>

                <div class="card-body">

                    <form action="{{ route('admin.procedure-categories.store') }}" method="POST"
                        enctype="multipart/form-data">

                        @csrf

                        @include('admin.procedure-categories.form')

                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection