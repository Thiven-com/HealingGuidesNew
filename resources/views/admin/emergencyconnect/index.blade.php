@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="row align-items-center">

                <div class="col">
                    <h4 class="page-title">
                        Emergency Connect
                    </h4>
                </div>

            </div>
            <div class="col-auto">
                    <button type="button"
                            class="btn btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#addEmergencyConnectModal">
                        <i class="ti ti-plus me-1"></i>
                        Add Emergency Connect
                    </button>
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

            <div class="alert alert-danger alert-dismissible fade show">

                <strong>Please fix the following:</strong>

                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- Filters --}}
        <div class="card mb-3">

            <div class="card-body">

                <form method="GET"
                      action="{{ route('admin.emergencyconnect.index') }}">

                    <div class="row align-items-end">

                        {{-- Title --}}
                        <div class="col-md-5 mb-3 mb-md-0">

                            <label class="form-label">
                                Title
                            </label>

                            <input type="text"
                                   name="title"
                                   class="form-control"
                                   value="{{ request('title') }}"
                                   placeholder="Search title">

                        </div>


                        {{-- Status --}}
                        <div class="col-md-4 mb-3 mb-md-0">

                            <label class="form-label">
                                Status
                            </label>

                            <select name="status"
                                    class="form-select">

                                <option value="">
                                    All Status
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


                        {{-- Buttons --}}
                        <div class="col-md-3">

                            <div class="d-flex gap-2">

                                <button type="submit"
                                        class="btn btn-primary">

                                    <i class="ti ti-search me-1"></i>
                                    Search

                                </button>

                                <a href="{{ route('admin.emergencyconnect.index') }}"
                                   class="btn btn-light">

                                    <i class="ti ti-refresh me-1"></i>
                                    Reset

                                </a>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- Table --}}
        <div class="card">

            <div class="card-header">

                <h5 class="card-title mb-0">
                    Emergency Connect List
                </h5>

            </div>


            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-striped table-hover">

                        <thead>

                            <tr>

                                <th width="70">#</th>

                                <th width="100">Image</th>

                                <th>Title</th>

                                <th>Slug</th>

                                <th width="120">Status</th>

                                <th width="180">Created Date</th>

                                <th width="180">Action</th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($emergencyConnects as $key => $emergencyConnect)

                                <tr>

                                    {{-- Number --}}
                                    <td>
                                        {{ $emergencyConnects->firstItem() + $key }}
                                    </td>


                                    {{-- Image --}}
                                    <td>

                                        @if($emergencyConnect->image)

                                            <img
                                                src="{{ asset($emergencyConnect->image) }}"
                                                alt="{{ $emergencyConnect->title }}"
                                                width="60"
                                                height="60"
                                                style="object-fit: cover; border-radius: 8px;"
                                            >

                                        @else

                                            <div class="d-flex align-items-center justify-content-center bg-light"
                                                 style="width:60px;height:60px;border-radius:8px;">

                                                <i class="ti ti-photo text-muted fs-4"></i>

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Title --}}
                                    <td>
                                        <strong>
                                            {{ $emergencyConnect->title }}
                                        </strong>
                                    </td>


                                    {{-- Slug --}}
                                    <td>
                                        <span class="text-muted">
                                            {{ $emergencyConnect->slug }}
                                        </span>
                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if($emergencyConnect->status)

                                            <span class="badge bg-success">
                                                Active
                                            </span>

                                        @else

                                            <span class="badge bg-danger">
                                                Inactive
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Date --}}
                                    <td>

                                        {{ $emergencyConnect->created_at
                                            ? $emergencyConnect->created_at->format('d M Y')
                                            : '-' }}

                                    </td>


                                    {{-- Actions --}}
                                    <td>

                                        <div class="d-flex align-items-center gap-1">

                                            {{-- Edit --}}
                                            <button type="button"
                                                    class="btn btn-sm btn-primary"
                                                    title="Edit"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editEmergencyConnectModal{{ $emergencyConnect->id }}">

                                                <i class="ti ti-edit"></i>

                                            </button>


                                            {{-- Status --}}
                                            <form action="{{ route(
                                                'admin.emergencyconnect.toggle-status',
                                                $emergencyConnect->id
                                            ) }}"
                                                  method="POST"
                                                  class="d-inline">

                                                @csrf

                                                <button type="submit"
                                                        class="btn btn-sm btn-warning"
                                                        title="Change Status">

                                                    <i class="ti ti-toggle-right"></i>

                                                </button>

                                            </form>


                                            {{-- Delete --}}
                                            <form action="{{ route(
                                                'admin.emergencyconnect.destroy',
                                                $emergencyConnect->id
                                            ) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this Emergency Connect?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        title="Delete">

                                                    <i class="ti ti-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>


                                {{-- ================= EDIT MODAL ================= --}}
                                <div class="modal fade"
                                     id="editEmergencyConnectModal{{ $emergencyConnect->id }}"
                                     tabindex="-1"
                                     aria-hidden="true">

                                    <div class="modal-dialog modal-lg modal-dialog-centered">

                                        <div class="modal-content">

                                            <div class="modal-header">

                                                <h5 class="modal-title">
                                                    Edit Emergency Connect
                                                </h5>

                                                <button type="button"
                                                        class="btn-close"
                                                        data-bs-dismiss="modal">
                                                </button>

                                            </div>


                                            <form action="{{ route(
                                                'admin.emergencyconnect.update',
                                                $emergencyConnect->id
                                            ) }}"
                                                  method="POST"
                                                  enctype="multipart/form-data">

                                                @csrf
                                                @method('PUT')


                                                <div class="modal-body">

                                                    <div class="row">

                                                        {{-- Current Image --}}
                                                        <div class="col-md-4 mb-3">

                                                            <label class="form-label">
                                                                Current Image
                                                            </label>

                                                            <div>

                                                                @if($emergencyConnect->image)

                                                                    <img src="{{ asset($emergencyConnect->image) }}"
                                                                         alt="{{ $emergencyConnect->title }}"
                                                                         class="img-thumbnail"
                                                                         style="width:120px;height:120px;object-fit:cover;">

                                                                @else

                                                                    <div class="d-flex align-items-center justify-content-center bg-light"
                                                                         style="width:120px;height:120px;border-radius:8px;">

                                                                        <i class="ti ti-photo fs-2 text-muted"></i>

                                                                    </div>

                                                                @endif

                                                            </div>

                                                        </div>


                                                        {{-- New Image --}}
                                                        <div class="col-md-8 mb-3">

                                                            <label class="form-label">
                                                                Change Image
                                                            </label>

                                                            <input type="file"
                                                                   name="image"
                                                                   class="form-control"
                                                                   accept=".jpg,.jpeg,.png,.webp">

                                                            <small class="text-muted">
                                                                JPG, JPEG, PNG or WEBP. Max 2MB.
                                                            </small>

                                                        </div>


                                                        {{-- Title --}}
<div class="col-md-6 mb-3">

    <label class="form-label">
        Title <span class="text-danger">*</span>
    </label>

    <input type="text"
           name="title"
           id="editEmergencyConnectTitle{{ $emergencyConnect->id }}"
           class="form-control"
           value="{{ $emergencyConnect->title }}"
           required>

</div>


{{-- Slug --}}
<div class="col-md-6 mb-3">

    <label class="form-label">
        Slug
    </label>

    <input type="text"
           name="slug"
           id="editEmergencyConnectSlug{{ $emergencyConnect->id }}"
           class="form-control"
           value="{{ $emergencyConnect->slug }}"
           readonly>

    <small class="text-muted">
        Slug is generated automatically from the title.
    </small>

</div>


                                                        {{-- Status --}}
                                                        <div class="col-md-6 mb-3">

                                                            <label class="form-label">
                                                                Status
                                                            </label>

                                                            <select name="status"
                                                                    class="form-select">

                                                                <option value="1"
                                                                    {{ $emergencyConnect->status ? 'selected' : '' }}>
                                                                    Active
                                                                </option>

                                                                <option value="0"
                                                                    {{ !$emergencyConnect->status ? 'selected' : '' }}>
                                                                    Inactive
                                                                </option>

                                                            </select>

                                                        </div>

                                                    </div>

                                                </div>


                                                <div class="modal-footer">

                                                    <button type="button"
                                                            class="btn btn-light"
                                                            data-bs-dismiss="modal">
                                                        Cancel
                                                    </button>

                                                    <button type="submit"
                                                            class="btn btn-primary">
                                                        <i class="ti ti-device-floppy me-1"></i>
                                                        Update
                                                    </button>

                                                </div>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            @empty

                                <tr>

                                    <td colspan="7"
                                        class="text-center py-4">

                                        <div class="text-muted">

                                            <i class="ti ti-alert-circle fs-2 d-block mb-2"></i>

                                            No Emergency Connect records found.

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if($emergencyConnects->hasPages())

                    <div class="mt-3">

                        {{ $emergencyConnects->links() }}

                    </div>

                @endif

            </div>

        </div>

    </div>
</div>


{{-- ========================================================= --}}
{{-- ADD EMERGENCY CONNECT MODAL --}}
{{-- ========================================================= --}}

<div class="modal fade"
     id="addEmergencyConnectModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Add Emergency Connect
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>


            <form action="{{ route('admin.emergencyconnect.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf


                <div class="modal-body">

                    <div class="row">

                        {{-- Image --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Image
                            </label>

                            <input type="file"
                                   name="image"
                                   id="emergencyConnectImage"
                                   class="form-control"
                                   accept=".jpg,.jpeg,.png,.webp">

                            <small class="text-muted">
                                JPG, JPEG, PNG or WEBP. Max 2MB.
                            </small>

                            <div class="mt-3">

                                <img id="emergencyConnectImagePreview"
                                     src=""
                                     alt="Image Preview"
                                     style="display:none;width:120px;height:120px;object-fit:cover;border-radius:8px;border:1px solid #ddd;">

                            </div>

                        </div>


                        {{-- Title --}}
<div class="col-md-6 mb-3">

    <label class="form-label">
        Title <span class="text-danger">*</span>
    </label>

    <input type="text"
           name="title"
           id="emergencyConnectTitle"
           class="form-control"
           placeholder="Enter title"
           value="{{ old('title') }}"
           required>

</div>


{{-- Slug --}}
<div class="col-md-6 mb-3">

    <label class="form-label">
        Slug
    </label>

    <input type="text"
           name="slug"
           id="emergencyConnectSlug"
           class="form-control"
           placeholder="Auto generated slug"
           value="{{ old('slug') }}"
           readonly>

    <small class="text-muted">
        Slug will be generated automatically from the title.
    </small>

</div>

                        {{-- Status --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Status
                            </label>

                            <select name="status"
                                    class="form-select">

                                <option value="1"
                                    {{ old('status', '1') == '1' ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="0"
                                    {{ old('status') === '0' ? 'selected' : '' }}>
                                    Inactive
                                </option>

                            </select>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="ti ti-device-floppy me-1"></i>
                        Save

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- Image Preview --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const imageInput = document.getElementById('emergencyConnectImage');
    const imagePreview = document.getElementById('emergencyConnectImagePreview');

    if (imageInput) {

        imageInput.addEventListener('change', function (event) {

            const file = event.target.files[0];

            if (file) {

                const reader = new FileReader();

                reader.onload = function (e) {

                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';

                };

                reader.readAsDataURL(file);

            } else {

                imagePreview.src = '';
                imagePreview.style.display = 'none';

            }

        });

    }

});

</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const titleInput = document.getElementById('emergencyConnectTitle');
    const slugInput = document.getElementById('emergencyConnectSlug');

    if (titleInput && slugInput) {

        titleInput.addEventListener('input', function () {

            let title = this.value;

            let slug = title
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');

            slugInput.value = slug;
        });
    }


    @foreach($emergencyConnects as $emergencyConnect)

        const editTitle{{ $emergencyConnect->id }} =
            document.getElementById(
                'editEmergencyConnectTitle{{ $emergencyConnect->id }}'
            );

        const editSlug{{ $emergencyConnect->id }} =
            document.getElementById(
                'editEmergencyConnectSlug{{ $emergencyConnect->id }}'
            );

        if (editTitle{{ $emergencyConnect->id }} &&
            editSlug{{ $emergencyConnect->id }}) {

            editTitle{{ $emergencyConnect->id }}.addEventListener(
                'input',
                function () {

                    let title = this.value;

                    let slug = title
                        .toLowerCase()
                        .trim()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-');

                    editSlug{{ $emergencyConnect->id }}.value = slug;
                }
            );
        }

    @endforeach

});
</script>

@endsection