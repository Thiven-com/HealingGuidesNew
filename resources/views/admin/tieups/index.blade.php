@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            {{-- SUCCESS MESSAGE --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ti ti-check me-2"></i>
                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>
                </div>
            @endif

            {{-- PAGE HEADER --}}
            <div class="page-header">

                <div class="page-title">
                    <h4>Tieups</h4>
                    <h6>Manage hospital tieups</h6>
                </div>

                <div class="page-btn">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTieupModal">

                        <i class="ti ti-plus me-1"></i>
                        Add Tieup

                    </button>
                </div>

            </div>

            {{-- TIEUP CARD --}}
            <div class="card">

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table datanew">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Created Date</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($tieups as $key => $tieup)

                                                        <tr>

                                                            {{-- NUMBER --}}
                                                            <td>
                                                                {{ $tieups->firstItem() + $key }}
                                                            </td>

                                                            {{-- IMAGE --}}
                                                            <td>

                                                                @if($tieup->image)

                                                                    <img src="{{ asset($tieup->image) }}" alt="{{ $tieup->name }}" style="
                                                                                                        width: 55px;
                                                                                                        height: 55px;
                                                                                                        object-fit: cover;
                                                                                                        border-radius: 8px;
                                                                                                        border: 1px solid #e5e7eb;
                                                                                                     ">

                                                                @else

                                                                    <div
                                                                        style="
                                                                                                                                                                                width: 55px;
                                                                                                                                                                                height: 55px;
                                                                                                                                                                                border-radius: 8px;
                                                                                                                                                                                background: #f3f4f6;
                                                                                                                                                                                display: flex;
                                                                                                                                                                                align-items: center;
                                                                                                                                                                                justify-content: center;
                                                                                                                                                                                color: #9ca3af;
                                                                                                                                                                            ">
                                                                        <i class="ti ti-photo" style="font-size: 22px;">
                                                                        </i>
                                                                    </div>

                                                                @endif

                                                            </td>

                                                            {{-- NAME --}}
                                                            <td>
                                                                <strong>
                                                                    {{ $tieup->name }}
                                                                </strong>
                                                            </td>

                                                            {{-- DATE --}}
                                                            <td>
                                                                {{ $tieup->created_at
                                    ? $tieup->created_at->format('d M Y')
                                    : 'N/A'
                                                                                                                                                        }}
                                                            </td>

                                                            {{-- ACTION --}}
                                                            <td class="text-center">

                                                                <div class="d-flex justify-content-center gap-2">

                                                                    {{-- EDIT --}}
                                                                    <button type="button" class="btn btn-sm btn-outline-primary" title="Edit"
                                                                        data-bs-toggle="modal" data-bs-target="#editTieupModal{{ $tieup->id }}">

                                                                        <i class="ti ti-edit"></i>

                                                                    </button>

                                                                    {{-- DELETE --}}
                                                                    <form action="{{ route('admin.tieups.destroy', $tieup->id) }}" method="POST"
                                                                        onsubmit="return confirm('Are you sure you want to delete this tieup?');">

                                                                        @csrf
                                                                        @method('DELETE')

                                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">

                                                                            <i class="ti ti-trash"></i>

                                                                        </button>

                                                                    </form>

                                                                </div>

                                                            </td>

                                                        </tr>

                                @empty

                                    <tr>

                                        <td colspan="7" class="text-center py-5">

                                            <div class="text-muted">

                                                <i class="ti ti-link" style="font-size: 40px;">
                                                </i>

                                                <h5 class="mt-2">
                                                    No Tieups Found
                                                </h5>

                                                <p class="mb-0">
                                                    Add your first tieup.
                                                </p>

                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    {{-- PAGINATION --}}
                    @if($tieups->hasPages())

                        <div class="mt-3">
                            {{ $tieups->links() }}
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ADD TIEUP MODAL --}}
    {{-- ========================================================= --}}

    <div class="modal fade" id="addTieupModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">

                <form action="{{ route('admin.tieups.store') }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="modal-header">

                        <h5 class="modal-title">
                            <i class="ti ti-link me-2"></i>
                            Add Tieup
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        {{-- NAME --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Tieup Name
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text" name="name" id="tieupName" class="form-control"
                                placeholder="Enter tieup name" value="{{ old('name') }}" required>

                            @error('name')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Slug
                            </label>

                            <input type="text" name="slug" id="tieupSlug" class="form-control bg-light"
                                value="{{ old('slug') }}" readonly>

                            <small class="text-muted">
                                Slug will be generated automatically from the name.
                            </small>

                        </div>

                        {{-- DESCRIPTION --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea name="description" class="form-control" rows="4"
                                placeholder="Enter tieup description">{{ old('description') }}</textarea>

                            @error('description')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>

                        {{-- IMAGE --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Image
                            </label>

                            <input type="file" name="image" class="form-control"
                                accept="image/jpeg,image/png,image/webp,image/gif">

                            <small class="text-muted">
                                JPG, PNG, WEBP or GIF. Maximum 5 MB.
                            </small>

                            @error('image')
                                <span class="text-danger d-block">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" class="btn btn-primary">

                            <i class="ti ti-check me-1"></i>
                            Save Tieup

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- EDIT TIEUP MODALS --}}
    {{-- ========================================================= --}}

    @foreach($tieups as $tieup)

        <div class="modal fade" id="editTieupModal{{ $tieup->id }}" tabindex="-1" aria-hidden="true">

            <div class="modal-dialog modal-lg modal-dialog-centered">

                <div class="modal-content">

                    <form action="{{ route('admin.tieups.update', $tieup->id) }}" method="POST" enctype="multipart/form-data">

                        @csrf
                        @method('PUT')

                        <div class="modal-header">

                            <h5 class="modal-title">
                                <i class="ti ti-edit me-2"></i>
                                Edit Tieup
                            </h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            {{-- NAME --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Tieup Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="name" class="form-control" value="{{ $tieup->name }}"
                                    placeholder="Enter tieup name" required>

                                @error('name')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>


                            {{-- SLUG --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Slug
                                </label>

                                <input type="text" class="form-control bg-light" value="{{ $tieup->slug }}" readonly>

                                <small class="text-muted">
                                    Slug will be generated automatically from the name.
                                </small>

                            </div>


                            {{-- DESCRIPTION --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Description
                                </label>

                                <textarea name="description" class="form-control" rows="4"
                                    placeholder="Enter tieup description">{{ $tieup->description }}</textarea>

                                @error('description')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>


                            {{-- CURRENT IMAGE --}}
                            @if($tieup->image)

                                            <div class="mb-3">

                                                <label class="form-label">
                                                    Current Image
                                                </label>

                                                <div>

                                                    <img src="{{ asset($tieup->image) }}" alt="{{ $tieup->name }}" style="
                                    width: 120px;
                                    height: 90px;
                                    object-fit: cover;
                                    border-radius: 8px;
                                    border: 1px solid #e5e7eb;
                                 ">

                                                </div>

                                            </div>

                            @endif


                            {{-- NEW IMAGE --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    {{ $tieup->image ? 'Change Image' : 'Image' }}
                                </label>

                                <input type="file" name="image" class="form-control"
                                    accept="image/jpeg,image/png,image/webp,image/gif">

                                <small class="text-muted">
                                    JPG, PNG, WEBP or GIF. Maximum 5 MB.
                                </small>

                                @error('image')
                                    <span class="text-danger d-block">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                                Cancel

                            </button>

                            <button type="submit" class="btn btn-primary">

                                <i class="ti ti-check me-1"></i>
                                Update Tieup

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endforeach
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const nameInput = document.getElementById('tieupName');
            const slugInput = document.getElementById('tieupSlug');

            if (nameInput && slugInput) {

                nameInput.addEventListener('input', function () {

                    let slug = this.value
                        .toLowerCase()
                        .trim()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-');

                    slugInput.value = slug;
                });

            }

        });
    </script>

@endsection