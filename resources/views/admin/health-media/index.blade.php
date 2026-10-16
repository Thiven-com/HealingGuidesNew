
@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">

        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Health Media</h4>

            <a href="{{ route('admin.health-media.create') }}"
               class="btn btn-primary">
                <i class="fa fa-plus me-1"></i> Add Health Media
            </a>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Search and Filter --}}
        <div class="card mb-4 health-media-card">
            <div class="card-body">
                <form action="{{ route('admin.health-media.index') }}"
                      method="GET">

                    <div class="row g-3">

                        <div class="col-12 col-md-5">
                            <label class="form-label">Search</label>

                            <input type="text"
                                   name="search"
                                   class="form-control"
                                   placeholder="Search name or description"
                                   value="{{ request('search') }}">
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label">Status</label>

                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>

                                <option value="active"
                                    @selected(request('status') === 'active')>
                                    Active
                                </option>

                                <option value="inactive"
                                    @selected(request('status') === 'inactive')>
                                    Inactive
                                </option>
                            </select>
                        </div>

                        <div class="col-12 col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search me-1"></i> Search
                            </button>

                            <a href="{{ route('admin.health-media.index') }}"
                               class="btn btn-secondary">
                                Reset
                            </a>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- Health Media Table --}}
        <div class="card health-media-card">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table align-middle">

                        <thead class="table-light">
                            <tr>
                                <th style="width:70px;">S.No</th>
                                <th style="width:120px;">Image</th>
                                <th>Name</th>
                                {{-- <th>Description</th> --}}
                                <th style="width:110px;">Status</th>
                                <th style="width:130px;">Created At</th>
                                <th style="width:160px;">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($healthMedia as $media)
                                <tr>
                                    {{-- Serial Number --}}
                                    <td>
                                        {{ $healthMedia->firstItem() + $loop->index }}
                                    </td>

                                    {{-- Image --}}
                                    <td>
                                        @if ($media->image)
                                            <a href="{{ asset($media->image) }}"
                                               target="_blank"
                                               rel="noopener noreferrer">

                                                <img
                                                    src="{{ asset($media->image) }}"
                                                    alt="{{ $media->name }}"
                                                    width="80"
                                                    height="60"
                                                    loading="lazy"
                                                    style="object-fit:cover;border-radius:6px;border:1px solid #eee;">
                                            </a>
                                        @else
                                            <span class="text-muted">
                                                No image
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Name --}}
                                    <td>
                                        <strong>{{ $media->name }}</strong>
                                    </td>

                                    {{-- Description --}}
                                    {{-- <td>
                                        {{ $media->description
                                            ? \Illuminate\Support\Str::limit($media->description, 80)
                                            : 'No description' }}
                                    </td> --}}

                                    {{-- Status --}}
                                    <td>
                                        @if ($media->status === 'active')
                                            <span class="badge bg-success">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Created Date --}}
                                    <td>
                                        {{ $media->created_at
                                            ? $media->created_at->format('d-m-Y')
                                            : 'N/A' }}
                                    </td>

                                    {{-- Actions --}}
                                    <td>
                                        <div class="d-flex align-items-center gap-2">

                                            {{-- View --}}
                                            <a href="{{ route('admin.health-media.show', $media->id) }}"
                                               class="btn btn-sm btn-info"
                                               title="View">
                                                <i class="fa fa-eye"></i>
                                            </a>

                                            {{-- Edit --}}
                                            <a href="{{ route('admin.health-media.edit', $media->id) }}"
                                               class="btn btn-sm btn-primary"
                                               title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            {{-- Delete --}}
                                            <form
                                                action="{{ route('admin.health-media.destroy', $media->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this health media?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fa fa-image fa-2x text-muted mb-2"></i>

                                        <p class="mb-0">
                                            No health media records found.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $healthMedia->appends(request()->query())->links() }}
                </div>

            </div>
        </div>

    </div>
</div>

<style>
    .health-media-card {
        border: 1px solid #e8e8e8;
        border-radius: 10px;
        overflow: hidden;
    }

    .health-media-card .card-body {
        padding: 20px;
    }

    .health-media-card .form-label {
        font-weight: 500;
        font-size: 14px;
    }

    .health-media-card .table th {
        font-weight: 600;
        white-space: nowrap;
    }

    .health-media-card .table td {
        vertical-align: middle;
    }

    .health-media-card .btn {
        white-space: nowrap;
    }

    @media (max-width: 576px) {
        .health-media-card .card-body {
            padding: 12px;
        }

        .page-wrapper .content > .d-flex {
            gap: 12px;
        }

        .page-wrapper .content > .d-flex h4 {
            font-size: 18px;
        }
    }
</style>
@endsection