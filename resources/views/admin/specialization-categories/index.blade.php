@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="page-title">
                <h4>Specialization Categories</h4>
                <h6>Manage specialization categories</h6>
            </div>

            <div class="page-btn">
                <a href="{{ route('admin.specialization-categories.create') }}"
                   class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>
                    Add Category
                </a>
            </div>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="ti ti-check me-2"></i>
                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table datanew">

                        <thead>
                            <tr>
                                <th>#</th>
                                {{-- <th>Image</th> --}}
                                <th>Category Name</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($categories as $key => $category)

                                <tr>

                                    <td>
                                        {{ $categories->firstItem() + $key }}
                                    </td>

                                    {{-- <td>
                                        @if($category->image)
                                            <img src="{{ asset($category->image) }}"
                                                 alt="{{ $category->category_name }}"
                                                 style="
                                                    width:50px;
                                                    height:50px;
                                                    object-fit:cover;
                                                    border-radius:8px;
                                                 ">
                                        @else
                                            <div style="
                                                width:50px;
                                                height:50px;
                                                background:#f1f3f5;
                                                border-radius:8px;
                                                display:flex;
                                                align-items:center;
                                                justify-content:center;
                                            ">
                                                <i class="ti ti-photo text-muted"></i>
                                            </div>
                                        @endif
                                    </td> --}}

                                    <td>
                                        <strong>
                                            {{ $category->category_name }}
                                        </strong>
                                    </td>


                                    <td>
                                        @if($category->status)
                                            <span class="badge bg-success">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $category->created_at?->format('d-m-Y') }}
                                    </td>

                                    <td class="text-center">

                                        <div class="action-table-data">
                                            <div class="edit-delete-action">

                                                {{-- Edit --}}
                                                <a href="{{ route(
                                                    'admin.specialization-categories.edit',
                                                    $category->id
                                                ) }}"
                                                   class="me-2 p-2"
                                                   title="Edit">

                                                    <i class="ti ti-edit"></i>

                                                </a>

                                                {{-- Delete --}}
                                                <form action="{{ route(
                                                    'admin.specialization-categories.destroy',
                                                    $category->id
                                                ) }}"
                                                      method="POST"
                                                      style="display:inline-block;"
                                                      onsubmit="return confirm('Are you sure you want to delete this category?');">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-link text-danger p-2"
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
                                    <td colspan="8"
                                        class="text-center py-4">

                                        No specialization categories found.

                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $categories->links() }}
                </div>

            </div>
        </div>

    </div>
</div>

@endsection