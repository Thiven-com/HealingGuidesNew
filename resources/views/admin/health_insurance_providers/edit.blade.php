@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="row align-items-center">

                <div class="col">
                    <h4 class="page-title">
                        Edit Health Insurance Provider
                    </h4>

                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.health-insurance-providers.index') }}">
                                Health Insurance Providers
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Edit Provider
                        </li>
                    </ul>
                </div>

                <div class="col-auto">

                    <a
                        href="{{ route('admin.health-insurance-providers.index') }}"
                        class="btn btn-light"
                    >
                        <i class="ti ti-arrow-left me-1"></i>
                        Back
                    </a>

                </div>

            </div>
        </div>


        {{-- Validation Errors --}}
        @if($errors->any())

            <div class="alert alert-danger alert-dismissible fade show">

                <ul class="mb-0">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- Success --}}
        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        <form
            action="{{ route('admin.health-insurance-providers.update', $provider->id) }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf

            @method('PUT')


            <div class="row">

                {{-- Provider Details --}}
                <div class="col-lg-8">

                    <div class="card">

                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Provider Details
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="row">

                                {{-- Provider Name --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Provider Name
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control"
                                        value="{{ old('name', $provider->name) }}"
                                        placeholder="Enter provider name"
                                        required
                                    >

                                </div>


                                {{-- Type --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Type
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select
                                        name="type"
                                        class="form-select"
                                        required
                                    >

                                        <option value="">
                                            Select Type
                                        </option>

                                        <option
                                            value="central_government"
                                            {{ old('type', $provider->type) == 'central_government' ? 'selected' : '' }}
                                        >
                                            Central Government
                                        </option>

                                        <option
                                            value="state_government"
                                            {{ old('type', $provider->type) == 'state_government' ? 'selected' : '' }}
                                        >
                                            State Government
                                        </option>

                                        <option
                                            value="private"
                                            {{ old('type', $provider->type) == 'private' ? 'selected' : '' }}
                                        >
                                            Private
                                        </option>

                                        <option
                                            value="public_sector"
                                            {{ old('type', $provider->type) == 'public_sector' ? 'selected' : '' }}
                                        >
                                            Public Sector
                                        </option>

                                        <option
                                            value="tpa"
                                            {{ old('type', $provider->type) == 'tpa' ? 'selected' : '' }}
                                        >
                                            TPA
                                        </option>

                                        <option
                                            value="other"
                                            {{ old('type', $provider->type) == 'other' ? 'selected' : '' }}
                                        >
                                            Other
                                        </option>

                                    </select>

                                </div>


                                {{-- Slug --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Slug
                                    </label>

                                    <input
                                        type="text"
                                        name="slug"
                                        class="form-control"
                                        value="{{ old('slug', $provider->slug) }}"
                                        placeholder="provider-slug"
                                    >

                                </div>


                                {{-- Website --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Website URL
                                    </label>

                                    <input
                                        type="text"
                                        name="website_url"
                                        class="form-control"
                                        value="{{ old('website_url', $provider->website_url) }}"
                                        placeholder="https://example.com"
                                    >

                                </div>


                                {{-- Support Email --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Support Email
                                    </label>

                                    <input
                                        type="email"
                                        name="support_email"
                                        class="form-control"
                                        value="{{ old('support_email', $provider->support_email) }}"
                                        placeholder="support@example.com"
                                    >

                                </div>


                                {{-- Support Phone --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Support Phone
                                    </label>

                                    <input
                                        type="text"
                                        name="support_phone"
                                        class="form-control"
                                        value="{{ old('support_phone', $provider->support_phone) }}"
                                        placeholder="Enter support phone"
                                    >

                                </div>


                                {{-- Description --}}
                                <div class="col-md-12 mb-3">

                                    <label class="form-label">
                                        Description
                                    </label>

                                    <textarea
                                        name="description"
                                        class="form-control"
                                        rows="5"
                                        placeholder="Enter provider description"
                                    >{{ old('description', $provider->description) }}</textarea>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Right Side --}}
                <div class="col-lg-4">

                    {{-- Logo --}}
                    <div class="card">

                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Provider Logo
                            </h5>
                        </div>

                        <div class="card-body">

                            @if($provider->logo)

                                <div
                                    class="text-center mb-3"
                                    id="currentLogo"
                                >

                                    <img
                                        src="{{ asset($provider->logo) }}"
                                        alt="{{ $provider->name }}"
                                        class="rounded border"
                                        style="
                                            width: 160px;
                                            height: 160px;
                                            object-fit: contain;
                                        "
                                    >

                                </div>

                            @endif


                            <label class="form-label">
                                Change Logo
                            </label>

                            <input
                                type="file"
                                name="logo"
                                class="form-control"
                                accept=".jpg,.jpeg,.png,.webp"
                            >

                            <small class="text-muted d-block mt-2">
                                JPG, JPEG, PNG or WEBP. Maximum 5MB.
                            </small>


                            {{-- New Logo Preview --}}
                            <div
                                id="logoPreview"
                                class="mt-3 text-center"
                                style="display:none;"
                            >

                                <p class="text-muted mb-2">
                                    New Logo Preview
                                </p>

                                <img
                                    id="previewImage"
                                    src=""
                                    alt="Logo Preview"
                                    class="img-fluid rounded border"
                                    style="
                                        width: 160px;
                                        height: 160px;
                                        object-fit: contain;
                                    "
                                >

                            </div>

                        </div>

                    </div>


                    {{-- Settings --}}
                    <div class="card">

                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Settings
                            </h5>
                        </div>

                        <div class="card-body">

                            {{-- Display Order --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Display Order
                                </label>

                                <input
                                    type="number"
                                    name="display_order"
                                    class="form-control"
                                    value="{{ old('display_order', $provider->display_order ?? 0) }}"
                                    min="0"
                                >

                            </div>


                            {{-- Status --}}
                            <div class="form-check form-switch">

                                <input
                                    type="hidden"
                                    name="status"
                                    value="0"
                                >

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="status"
                                    value="1"
                                    id="status"
                                    {{ old('status', $provider->status) ? 'checked' : '' }}
                                >

                                <label
                                    class="form-check-label"
                                    for="status"
                                >
                                    Active
                                </label>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Buttons --}}
            <div class="text-end mt-3">

                <a
                    href="{{ route('admin.health-insurance-providers.index') }}"
                    class="btn btn-light me-2"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="ti ti-check me-1"></i>
                    Update Provider
                </button>

            </div>

        </form>

    </div>
</div>


{{-- Logo Preview --}}
<script>

    document.addEventListener('DOMContentLoaded', function () {

        const logoInput = document.querySelector('input[name="logo"]');
        const previewContainer = document.getElementById('logoPreview');
        const previewImage = document.getElementById('previewImage');

        if (logoInput) {

            logoInput.addEventListener('change', function (event) {

                const file = event.target.files[0];

                if (!file) {

                    previewContainer.style.display = 'none';
                    previewImage.src = '';

                    return;
                }

                const reader = new FileReader();

                reader.onload = function (e) {

                    previewImage.src = e.target.result;

                    previewContainer.style.display = 'block';

                };

                reader.readAsDataURL(file);

            });

        }

    });

</script>

@endsection