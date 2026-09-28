@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">

    <div class="content">

        <!-- Page Header -->
        <div class="page-header">

            <div class="page-title">
                <h4>Facilities</h4>
                <h6>Manage Hospital Facilities</h6>
            </div>

            <div class="page-btn">
                <a href="{{ route('admin.facilities.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>
                    Add Facility
                </a>
            </div>

        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ti ti-check me-2"></i>
                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Facilities List -->
        <div class="card">

            <div class="card-header">
                <h5 class="card-title mb-0">
                    Facility List
                </h5>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table datanew">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Description</th>
                                <th>Created Date</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($facilities as $facility)

                                <tr>

                                    <td>
                                        {{ $facilities->firstItem() + $loop->index }}
                                    </td>

                                    <!-- Image -->
                                    <td>

                                        @if($facility->image)

                                            <img
                                                src="{{ asset($facility->image) }}"
                                                alt="{{ $facility->name }}"
                                                style="
                                                    width:60px;
                                                    height:60px;
                                                    object-fit:cover;
                                                    border-radius:8px;
                                                "
                                            >

                                        @else

                                            <div
                                                class="d-flex align-items-center justify-content-center bg-light rounded"
                                                style="
                                                    width:60px;
                                                    height:60px;
                                                "
                                            >
                                                <i class="ti ti-photo text-muted fs-24"></i>
                                            </div>

                                        @endif

                                    </td>

                                    <!-- Name -->
                                    <td>
                                        <strong>
                                            {{ $facility->name }}
                                        </strong>
                                    </td>

                                    <!-- Slug -->
                                    <td>
                                        <span class="text-muted">
                                            {{ $facility->slug }}
                                        </span>
                                    </td>

                                    <!-- Description -->
                                    <td>

                                        @if($facility->description)

                                            {{ \Illuminate\Support\Str::limit($facility->description, 80) }}

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>

                                    <!-- Created Date -->
                                    <td>
                                        {{ $facility->created_at?->format('d-m-Y') }}
                                    </td>

                                    <!-- Action -->
                                    <td>

                                        <div class="d-flex justify-content-center gap-2">

                                            <!-- Edit -->
                                            <a
                                                href="{{ route('admin.facilities.edit', $facility) }}"
                                                class="btn btn-sm btn-primary"
                                                title="Edit"
                                            >
                                                <i class="ti ti-edit"></i>
                                            </a>

                                            <!-- Delete -->
                                            <form
                                                action="{{ route('admin.facilities.destroy', $facility) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this facility?');"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    title="Delete"
                                                >
                                                    <i class="ti ti-trash"></i>
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="text-center py-4">

                                        <div class="text-muted">

                                            <i class="ti ti-building-hospital fs-40"></i>

                                            <p class="mb-0 mt-2">
                                                No facilities found.
                                            </p>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <!-- Pagination -->
                @if($facilities->hasPages())

                    <div class="mt-3">
                        {{ $facilities->links() }}
                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection