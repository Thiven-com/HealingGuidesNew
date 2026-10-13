@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            {{-- PAGE HEADER --}}
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Procedure Categories</h4>
                        <h6>Manage procedure categories</h6>
                    </div>
                </div>

                <ul class="table-top-head">
                    <li>
                        <a href="javascript:void(0);" class="refresh-page" data-bs-toggle="tooltip" title="Refresh"
                            onclick="window.location.reload();">
                            <i class="ti ti-refresh"></i>
                        </a>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="expand-table" data-bs-toggle="tooltip" title="Fullscreen"
                            onclick="toggleCategoryFullscreen();">
                            <i class="ti ti-arrows-maximize"></i>
                        </a>
                    </li>
                </ul>

                <div class="page-btn">
                    <a href="{{ route('admin.procedure-categories.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i>
                        Add Procedure Category
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

            {{-- ERROR MESSAGE --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- VALIDATION ERRORS --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong>Please check the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- SEARCH FILTER --}}
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.procedure-categories.index') }}">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Search</label>

                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="ti ti-search"></i>
                                    </span>

                                    <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                                        placeholder="Search category name, slug or description...">
                                </div>
                            </div>

                            <div class="col-md-6 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-filter me-1"></i> Filter
                                </button>

                                <a href="{{ route('admin.procedure-categories.index') }}" class="btn btn-light">
                                    <i class="ti ti-refresh me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- PROCEDURE CATEGORIES TABLE --}}
            <div class="card" id="procedureCategoryTable">

                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">
                                Procedure Category List
                            </h5>

                            <small class="text-muted">
                                {{ $procedureCategories->total() }}
                                categories found
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width:70px;">ID</th>
                                    <th>Category</th>
                                    <th>Slug</th>
                                    <th>Description</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($procedureCategories as $category)
                                                            <tr>
                                                                {{-- ID --}}
                                                                <td>{{ $category->id }}</td>

                                                                {{-- CATEGORY --}}
                                                                <td>
                                                                    <div class="d-flex align-items-center">

                                                                        <div class="avatar avatar-md me-2">
                                                                            @if($category->image)
                                                                                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}"
                                                                                    class="rounded" style="width:45px;height:45px;object-fit:cover;">
                                                                            @else
                                                                                <span class="avatar-title bg-light-primary text-primary rounded"
                                                                                    style="width:45px;height:45px;display:flex;align-items:center;justify-content:center;">
                                                                                    <i class="ti ti-category"></i>
                                                                                </span>
                                                                            @endif
                                                                        </div>

                                                                        <div>
                                                                            <h6 class="mb-0">
                                                                                {{ $category->name }}
                                                                            </h6>

                                                                            {{-- <small class="text-muted">
                                                                                Category ID: {{ $category->id }}
                                                                            </small> --}}
                                                                        </div>

                                                                    </div>
                                                                </td>

                                                                {{-- SLUG --}}
                                                                <td>
                                                                    <span class="badge bg-light-info text-info">
                                                                        {{ $category->slug }}
                                                                    </span>
                                                                </td>

                                                                {{-- DESCRIPTION --}}
                                                                <td>
                                                                    {{ \Illuminate\Support\Str::limit(
                                        $category->description ?? '',
                                        70
                                    ) ?: '—' }}
                                                                </td>

                                                                {{-- ACTIONS --}}
                                                                <td>
                                                                    <div class="action-table-data">
                                                                        <div class="edit-delete-action">

                                                                            {{-- EDIT --}}
                                                                            <a class="me-2 p-2" href="{{ route(
                                        'admin.procedure-categories.edit',
                                        $category->id
                                    ) }}" data-bs-toggle="tooltip" title="Edit">
                                                                                <i class="ti ti-edit"></i>
                                                                            </a>

                                                                            {{-- DELETE --}}
                                                                            <form action="{{ route(
                                        'admin.procedure-categories.destroy',
                                        $category->id
                                    ) }}" method="POST" class="d-inline delete-category-form">

                                                                                @csrf
                                                                                @method('DELETE')

                                                                                <button type="submit"
                                                                                    class="confirm-text p-2 border-0 bg-transparent text-danger"
                                                                                    data-bs-toggle="tooltip" title="Delete">
                                                                                    <i class="ti ti-trash"></i>
                                                                                </button>

                                                                            </form>

                                                                        </div>
                                                                    </div>
                                                                </td>

                                                            </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="ti ti-category" style="font-size:50px;color:#adb5bd;"></i>

                                                <h5 class="mt-3">
                                                    No Procedure Categories Found
                                                </h5>

                                                <p class="text-muted">
                                                    No categories match your search.
                                                </p>

                                                <a href="{{ route('admin.procedure-categories.create') }}"
                                                    class="btn btn-primary">
                                                    <i class="ti ti-plus me-1"></i>
                                                    Add Procedure Category
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>

                {{-- PAGINATION --}}
                @if($procedureCategories->hasPages())
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                            <div class="text-muted">
                                Showing
                                {{ $procedureCategories->firstItem() }}
                                to
                                {{ $procedureCategories->lastItem() }}
                                of
                                {{ $procedureCategories->total() }}
                                entries
                            </div>

                            <div>
                                {{ $procedureCategories->withQueryString()->links() }}
                            </div>

                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>

    {{-- DELETE CONFIRMATION AND FULLSCREEN --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            document.querySelectorAll('.delete-category-form').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!confirm('Are you sure you want to delete this procedure category?')) {
                        event.preventDefault();
                    }
                });
            });

            // Initialize Bootstrap tooltips when Bootstrap is available.
            if (typeof bootstrap !== 'undefined') {
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (element) {
                    bootstrap.Tooltip.getOrCreateInstance(element);
                });
            }
        });

        function toggleCategoryFullscreen() {
            const table = document.getElementById('procedureCategoryTable');

            if (!document.fullscreenElement) {
                if (table.requestFullscreen) {
                    table.requestFullscreen();
                }
            } else if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    </script>

@endsection