@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">

    <div class="content">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="row align-items-center w-100">

                <div class="col">
                    <h4 class="page-title">
                        Diagnostic Categories
                    </h4>

                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/admin/dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Diagnostic Categories
                        </li>
                    </ul>
                </div>

                <div class="col-auto">

                    <a href="{{ route('admin.diagnostic-categories.create') }}"
                       class="btn btn-primary">

                        <i class="fa fa-plus me-1"></i>

                        Add Category

                    </a>

                </div>

            </div>
        </div>

        {{-- Card --}}
        <div class="card">

            <div class="card-body">

                {{-- Search --}}
                <form method="GET"
                      action="{{ route('admin.diagnostic-categories.index') }}">

                    <div class="row align-items-end">

                        <div class="col-md-5">

                            <label class="form-label">
                                Search
                            </label>

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                value="{{ request('search') }}"
                                placeholder="Search category..."
                            >

                        </div>

                        <div class="col-md-3">

                            <label class="form-label">
                                Status
                            </label>

                            <select
                                name="status"
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

                        <div class="col-md-auto">

                            <button
                                type="submit"
                                class="btn btn-primary">

                                <i class="fa fa-search me-1"></i>

                                Search

                            </button>

                            <a
                                href="{{ route('admin.diagnostic-categories.index') }}"
                                class="btn btn-light">

                                Reset

                            </a>

                        </div>

                    </div>

                </form>

                <div class="table-responsive mt-4">

                    <table class="table table-striped table-hover">

                        <thead>

                            <tr>

                                <th width="60">
                                    #
                                </th>

                                <th width="80">
                                    Image
                                </th>

                                <th>
                                    Category
                                </th>

                                <th>
                                    Slug
                                </th>

                                <th>
                                    Diagnostics
                                </th>

                                <th>
                                    Display Order
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

                        @forelse($categories as $category)

                            <tr>

                                <td>
                                    {{ $categories->firstItem() + $loop->index }}
                                </td>

                                <td>

                                    @if($category->image)

                                        <img
                                            src="{{ asset($category->image) }}"
                                            alt="{{ $category->name }}"
                                            width="50"
                                            height="50"
                                            class="rounded"
                                            style="object-fit:cover;">

                                    @else

                                        <div
                                            class="avatar avatar-md bg-light">

                                            <i class="fa fa-flask"></i>

                                        </div>

                                    @endif

                                </td>

                                <td>

                                    <strong>
                                        {{ $category->name }}
                                    </strong>

                                    @if($category->short_description)

                                        <div class="text-muted small">
                                            {{ Str::limit($category->short_description, 60) }}
                                        </div>

                                    @endif

                                </td>

                                <td>
                                    {{ $category->slug }}
                                </td>

                                <td>

                                    <span class="badge bg-info">
                                        {{ $category->diagnostics_count ?? $category->diagnostics()->count() }}
                                    </span>

                                </td>

                                <td>
                                    {{ $category->display_order }}
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

                                <td class="text-end">

                                    <div class="dropdown">

                                        <button
                                            class="btn btn-sm btn-light"
                                            type="button"
                                            data-bs-toggle="dropdown">

                                            <i class="fa fa-ellipsis-v"></i>

                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end">

                                            <li>

                                                <a
                                                    class="dropdown-item"
                                                    href="{{ route('admin.diagnostic-categories.show', $category->id) }}">

                                                    <i class="fa fa-eye me-2"></i>
                                                    View

                                                </a>

                                            </li>

                                            <li>

                                                <a
                                                    class="dropdown-item"
                                                    href="{{ route('admin.diagnostic-categories.edit', $category->id) }}">

                                                    <i class="fa fa-edit me-2"></i>
                                                    Edit

                                                </a>

                                            </li>

                                            <li>

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.diagnostic-categories.status', $category->id) }}">

                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="dropdown-item">

                                                        <i class="fa fa-toggle-on me-2"></i>

                                                        {{ $category->status ? 'Deactivate' : 'Activate' }}

                                                    </button>

                                                </form>

                                            </li>

                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>

                                            <li>

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.diagnostic-categories.destroy', $category->id) }}"
                                                    onsubmit="return confirm('Are you sure you want to delete this category?');">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="dropdown-item text-danger">

                                                        <i class="fa fa-trash me-2"></i>

                                                        Delete

                                                    </button>

                                                </form>

                                            </li>

                                        </ul>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="fa fa-flask fa-2x mb-3"></i>

                                        <h5>
                                            No Diagnostic Categories Found
                                        </h5>

                                        <p>
                                            Create your first diagnostic category.
                                        </p>

                                        <a
                                            href="{{ route('admin.diagnostic-categories.create') }}"
                                            class="btn btn-primary">

                                            <i class="fa fa-plus me-1"></i>
                                            Add Category

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

                @if($categories->hasPages())

                    <div class="mt-3">

                        {{ $categories->links() }}

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection