@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            {{-- Page Header --}}
            <div class="page-header">
                <div class="row align-items-center">

                    <div class="col">
                        <h4 class="page-title">
                            Health Insurance Provider Details
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
                                {{ $provider->name }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-auto">

                        <a href="{{ route('admin.health-insurance-providers.edit', $provider->id) }}"
                            class="btn btn-primary me-2">
                            <i class="ti ti-edit me-1"></i>
                            Edit
                        </a>

                        <a href="{{ route('admin.health-insurance-providers.index') }}" class="btn btn-light">
                            <i class="ti ti-arrow-left me-1"></i>
                            Back
                        </a>

                    </div>

                </div>
            </div>


            <div class="row">

                {{-- Provider Information --}}
                <div class="col-lg-8">

                    <div class="card">

                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Provider Information
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="row">

                                {{-- Name --}}
                                <div class="col-md-6 mb-4">

                                    <label class="text-muted d-block mb-1">
                                        Provider Name
                                    </label>

                                    <h6 class="mb-0">
                                        {{ $provider->name ?: '-' }}
                                    </h6>

                                </div>


                                {{-- Type --}}
                                <div class="col-md-6 mb-4">

                                    <label class="text-muted d-block mb-1">
                                        Type
                                    </label>

                                    @php

                                        $typeLabels = [
                                            'central_government' => 'Central Government',
                                            'state_government' => 'State Government',
                                            'private' => 'Private',
                                            'public_sector' => 'Public Sector',
                                            'tpa' => 'TPA',
                                            'other' => 'Other',
                                        ];

                                    @endphp

                                    <span class="badge bg-light text-dark">
                                        {{ $typeLabels[$provider->type] ?? ucfirst(str_replace('_', ' ', $provider->type)) }}
                                    </span>

                                </div>


                                {{-- Slug --}}
                                <div class="col-md-6 mb-4">

                                    <label class="text-muted d-block mb-1">
                                        Slug
                                    </label>

                                    <span>
                                        {{ $provider->slug ?: '-' }}
                                    </span>

                                </div>


                                {{-- Website --}}
                                <div class="col-md-6 mb-4">

                                    <label class="text-muted d-block mb-1">
                                        Website
                                    </label>

                                    @if($provider->website_url)

                                        <a href="{{ $provider->website_url }}" target="_blank" class="text-primary">
                                            {{ $provider->website_url }}

                                            <i class="ti ti-external-link ms-1"></i>
                                        </a>

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </div>


                                {{-- Support Email --}}
                                <div class="col-md-6 mb-4">

                                    <label class="text-muted d-block mb-1">
                                        Support Email
                                    </label>

                                    @if($provider->support_email)

                                        <a href="mailto:{{ $provider->support_email }}" class="text-primary">
                                            {{ $provider->support_email }}
                                        </a>

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </div>


                                {{-- Support Phone --}}
                                <div class="col-md-6 mb-4">

                                    <label class="text-muted d-block mb-1">
                                        Support Phone
                                    </label>

                                    @if($provider->support_phone)

                                        <a href="tel:{{ $provider->support_phone }}" class="text-primary">
                                            {{ $provider->support_phone }}
                                        </a>

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </div>


                                {{-- Description --}}
                                <div class="col-md-12 mb-2">

                                    <label class="text-muted d-block mb-2">
                                        Description
                                    </label>

                                    @if($provider->description)

                                        <div class="p-3 bg-light rounded">
                                            {!! nl2br(e($provider->description)) !!}
                                        </div>

                                    @else

                                        <span class="text-muted">
                                            No description available.
                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Timestamps --}}
                    <div class="card">

                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Record Information
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-6">

                                    <label class="text-muted d-block mb-1">
                                        Created At
                                    </label>

                                    <span>
                                        {{ $provider->created_at?->format('d M Y, h:i A') ?? '-' }}
                                    </span>

                                </div>


                                <div class="col-md-6">

                                    <label class="text-muted d-block mb-1">
                                        Last Updated
                                    </label>

                                    <span>
                                        {{ $provider->updated_at?->format('d M Y, h:i A') ?? '-' }}
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- Health Insurance Plans --}}

                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Health Insurance Plans</h5>

                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#addPlanModal">
                                <i class="ti ti-plus me-1"></i> Add Plan
                            </button>
                        </div>

                        <div class="card-body">

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if($provider->plans->isNotEmpty())

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Image</th>
                                                <th>Plan Name</th>
                                                <th>Description</th>
                                                <th>Policy Term</th>
                                                <th>Actual Price</th>
                                                <th>Price</th>
                                                <th>Discount</th>
                                                <th>Tax</th>
                                                <th>Minimum Entry Age</th>
                                                <th>Maximum Entry Age</th>
                                                <th>Key Benefits</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($provider->plans as $plan)
                                                                            <tr>
                                                                                <td>{{ $loop->iteration }}</td>

                                                                                <td>
                                                                                    @if($plan->image)
                                                                                        <img src="{{ asset($plan->image) }}" alt="{{ $plan->name }}" width="70"
                                                                                            height="60" class="rounded border" style="object-fit: cover;">
                                                                                    @else
                                                                                        <span class="text-muted">No Image</span>
                                                                                    @endif
                                                                                </td>

                                                                                <td>
                                                                                    <strong>{{ $plan->name }}</strong>
                                                                                </td>

                                                                                <td>
                                                                                    {{ $plan->description ?: '-' }}
                                                                                </td>

                                                                                <td>
                                                                                    {{ $plan->policy_term_years
                                                    ? $plan->policy_term_years . ' year(s)'
                                                    : '-' }}
                                                                                </td>
                                                                                <td>
                                                                                    ₹{{ number_format((float) $plan->actual_price, 2) }}
                                                                                </td>

                                                                                <td>
                                                                                    ₹{{ number_format((float) $plan->price, 2) }}
                                                                                </td>

                                                                                <td>
                                                                                    ₹{{ number_format((float) $plan->discount_price, 2) }}
                                                                                </td>

                                                                                <td>
                                                                                    ₹{{ number_format((float) $plan->tax_price, 2) }}
                                                                                </td>

                                                                                <td>
                                                                                    {{ $plan->minimum_entry_age ?? '-' }}
                                                                                </td>

                                                                                <td>
                                                                                    {{ $plan->maximum_entry_age ?? '-' }}
                                                                                </td>

                                                                                <td style="min-width: 250px; white-space: normal;">
                                                                                    @if($plan->key_benefits)
                                                                                        {!! nl2br(e($plan->key_benefits)) !!}
                                                                                    @else
                                                                                        -
                                                                                    @endif
                                                                                </td>

                                                                                <td>
                                                                                    @if($plan->status)
                                                                                        <span class="badge bg-success">Active</span>
                                                                                    @else
                                                                                        <span class="badge bg-danger">Inactive</span>
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    <div class="d-flex align-items-center gap-1">

                                                                                        {{-- Edit Button --}}
                                                                                        <button type="button" class="btn btn-sm btn-warning"
                                                                                            data-bs-toggle="modal" data-bs-target="#editPlanModal"
                                                                                            data-update-url="{{ route('admin.health-insurance-providers.plans.update', [
                                                    'provider' => $provider->id,
                                                    'plan' => $plan->id
                                                ]) }}" data-name="{{ $plan->name }}" data-slug="{{ $plan->slug }}"
                                                                                            data-description="{{ $plan->description }}"
                                                                                            data-policy-term-years="{{ $plan->policy_term_years }}"
                                                                                            data-price="{{ $plan->price }}"
                                                                                            data-discount-price="{{ $plan->discount_price }}"
                                                                                            data-tax-price="{{ $plan->tax_price }}"
                                                                                            data-minimum-entry-age="{{ $plan->minimum_entry_age }}"
                                                                                            data-maximum-entry-age="{{ $plan->maximum_entry_age }}"
                                                                                            data-key-benefits="{{ $plan->key_benefits }}"
                                                                                            data-status="{{ (int) $plan->status }}">

                                                                                            <i class="ti ti-edit"></i>
                                                                                        </button>

                                                                                        {{-- Delete Button --}}
                                                                                        <form
                                                                                            action="{{ route('admin.health-insurance-providers.plans.destroy', [$provider->id, $plan->id]) }}"
                                                                                            method="POST"
                                                                                            onsubmit="return confirm('Are you sure you want to delete this health insurance plan?');">

                                                                                            @csrf
                                                                                            @method('DELETE')

                                                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                                                title="Delete Plan">
                                                                                                <i class="ti ti-trash"></i>
                                                                                            </button>
                                                                                        </form>

                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>

                            @else

                                <div class="text-center py-4">
                                    <i class="ti ti-shield-check text-muted" style="font-size: 42px;"></i>

                                    <h6 class="mt-2">No Plans Added Yet</h6>

                                    <p class="text-muted mb-3">
                                        Add a health insurance plan for this provider.
                                    </p>

                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addPlanModal">
                                        <i class="ti ti-plus me-1"></i> Add First Plan
                                    </button>
                                </div>

                            @endif

                        </div>
                    </div>

                    {{-- Add Plan Modal --}}
                    {{-- Add Plan Modal --}}
                    <div class="modal fade" id="addPlanModal" tabindex="-1" aria-labelledby="addPlanModalLabel"
                        aria-hidden="true">

                        <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 900px;">

                            <div class="modal-content" style="height: 85vh; max-height: 85vh;">

                                <form action="{{ route('admin.health-insurance-providers.plans.store', $provider->id) }}"
                                    method="POST" enctype="multipart/form-data"
                                    style="display: flex; flex-direction: column; height: 100%; min-height: 0;">
                                    @csrf

                                    <div class="modal-header flex-shrink-0">
                                        <h5 class="modal-title" id="addPlanModalLabel">
                                            Add Health Insurance Plan
                                        </h5>

                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body"
                                        style="overflow-y: scroll !important; overflow-x: hidden; flex: 1 1 auto; min-height: 0;">

                                        {{-- KEEP ALL YOUR EXISTING FORM FIELDS HERE --}}

                                        @if($errors->any())
                                            <div class="alert alert-danger">
                                                <strong>Please correct the following errors:</strong>
                                                <ul class="mb-0 mt-2">
                                                    @foreach($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="alert alert-light border">
                                            <strong>Provider:</strong>
                                            {{ $provider->name }}
                                        </div>

                                        <div class="row">

                                            {{-- Plan Name --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    Plan Name <span class="text-danger">*</span>
                                                </label>

                                                <input type="text" name="name"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    value="{{ old('name') }}" placeholder="Enter plan name" required>

                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Slug --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Slug</label>

                                                <input type="text" name="slug"
                                                    class="form-control @error('slug') is-invalid @enderror"
                                                    value="{{ old('slug') }}" placeholder="e.g. family-health-plan">

                                                @error('slug')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            {{-- Plan Image --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Plan Image</label>

                                                <input type="file" name="image"
                                                    class="form-control @error('image') is-invalid @enderror"
                                                    accept=".jpg,.jpeg,.png,.webp"
                                                    onchange="previewPlanImage(this, 'addPlanImagePreview')">

                                                @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror

                                                <div class="mt-2">
                                                    <img id="addPlanImagePreview" src="" alt="Plan Image Preview"
                                                        class="rounded border d-none"
                                                        style="width: 120px; height: 100px; object-fit: cover;">
                                                </div>
                                            </div>

                                            {{-- Description --}}
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Description</label>

                                                <textarea name="description"
                                                    class="form-control @error('description') is-invalid @enderror" rows="3"
                                                    placeholder="Enter plan description">{{ old('description') }}</textarea>

                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Policy Term --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    Policy Term (Years)
                                                </label>

                                                <input type="number" name="policy_term_years"
                                                    class="form-control @error('policy_term_years') is-invalid @enderror"
                                                    value="{{ old('policy_term_years') }}" min="1" placeholder="e.g. 1">

                                                @error('policy_term_years')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            {{-- Actual Price --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    Actual Price (₹) <span class="text-danger">*</span>
                                                </label>

                                                <input type="number" name="actual_price"
                                                    class="form-control @error('actual_price') is-invalid @enderror"
                                                    value="{{ old('actual_price', '0.00') }}" min="0" step="0.01" required>

                                                @error('actual_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Price --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    Price (₹) <span class="text-danger">*</span>
                                                </label>

                                                <input type="number" name="price"
                                                    class="form-control @error('price') is-invalid @enderror"
                                                    value="{{ old('price', '0.00') }}" min="0" step="0.01" required>

                                                @error('price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Discount Price --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    Discount Price (₹)
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <input type="number" name="discount_price"
                                                    class="form-control @error('discount_price') is-invalid @enderror"
                                                    value="{{ old('discount_price', '0.00') }}" min="0" step="0.01"
                                                    required>

                                                @error('discount_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Tax Price --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    Tax Price (₹)
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <input type="number" name="tax_price"
                                                    class="form-control @error('tax_price') is-invalid @enderror"
                                                    value="{{ old('tax_price', '0.00') }}" min="0" step="0.01" required>

                                                @error('tax_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Minimum Entry Age --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Minimum Entry Age</label>

                                                <input type="number" name="minimum_entry_age"
                                                    class="form-control @error('minimum_entry_age') is-invalid @enderror"
                                                    value="{{ old('minimum_entry_age') }}" min="0"
                                                    placeholder="Enter minimum age">

                                                @error('minimum_entry_age')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Maximum Entry Age --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Maximum Entry Age</label>

                                                <input type="number" name="maximum_entry_age"
                                                    class="form-control @error('maximum_entry_age') is-invalid @enderror"
                                                    value="{{ old('maximum_entry_age') }}" min="0"
                                                    placeholder="Enter maximum age">

                                                @error('maximum_entry_age')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Key Benefits --}}
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Key Benefits</label>

                                                <textarea name="key_benefits"
                                                    class="form-control @error('key_benefits') is-invalid @enderror"
                                                    rows="4"
                                                    placeholder="Enter key benefits">{{ old('key_benefits') }}</textarea>

                                                @error('key_benefits')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Status --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    Status <span class="text-danger">*</span>
                                                </label>

                                                <select name="status"
                                                    class="form-select @error('status') is-invalid @enderror" required>
                                                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>
                                                        Active
                                                    </option>

                                                    <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>
                                                        Inactive
                                                    </option>
                                                </select>

                                                @error('status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>

                                    <div class="modal-footer flex-shrink-0">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                            Cancel
                                        </button>

                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-device-floppy me-1"></i>
                                            Save Plan
                                        </button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>

                    {{-- Edit Health Insurance Plan Modal --}}

                    {{-- Edit Health Insurance Plan Modal --}}
                    <div class="modal fade" id="editPlanModal" tabindex="-1" aria-labelledby="editPlanModalLabel"
                        aria-hidden="true">

                        <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 900px;">

                            <div class="modal-content" style="height: 85vh; max-height: 85vh;">

                                <form id="editPlanForm" method="POST" enctype="multipart/form-data"
                                    style="display: flex; flex-direction: column; height: 100%; min-height: 0;">

                                    @csrf
                                    @method('PUT')

                                    <div class="modal-header flex-shrink-0">
                                        <h5 class="modal-title" id="editPlanModalLabel">
                                            <i class="ti ti-edit me-2"></i>
                                            Edit Health Insurance Plan
                                        </h5>

                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body"
                                        style="overflow-y: scroll !important; overflow-x: hidden; flex: 1 1 auto; min-height: 0;">

                                        {{-- Validation Errors --}}
                                        <div id="editPlanErrors" class="alert alert-danger d-none">
                                            <strong>Please correct the following errors:</strong>
                                            <ul class="mb-0 mt-2" id="editPlanErrorList"></ul>
                                        </div>

                                        {{-- Provider --}}
                                        <div class="alert alert-light border">
                                            <strong>Provider:</strong> {{ $provider->name }}
                                        </div>

                                        <div class="row">

                                            {{-- Plan Name --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    Plan Name <span class="text-danger">*</span>
                                                </label>

                                                <input type="text" name="name" id="edit_name" class="form-control"
                                                    value="{{ old('name') }}" placeholder="Enter plan name" required>
                                            </div>

                                            {{-- Slug --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Slug</label>

                                                <input type="text" name="slug" id="edit_slug" class="form-control"
                                                    value="{{ old('slug') }}" placeholder="e.g. family-health-plan">
                                            </div>
                                            {{-- Plan Image --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Plan Image</label>

                                                <input type="file" name="image" id="edit_image" class="form-control"
                                                    accept="image/*"
                                                    onchange="previewPlanImage(this, 'editPlanImagePreview')">

                                                <div class="mt-2">
                                                    <img id="editPlanImagePreview" src="" alt="Plan Image Preview"
                                                        class="rounded border d-none"
                                                        style="width: 120px; height: 100px; object-fit: cover;">
                                                </div>

                                                <small class="text-muted">Leave empty to keep the existing image.</small>
                                            </div>

                                            {{-- Description --}}
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Description</label>

                                                <textarea name="description" id="edit_description" class="form-control"
                                                    rows="3"
                                                    placeholder="Enter plan description">{{ old('description') }}</textarea>
                                            </div>

                                            {{-- Policy Term --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Policy Term (Years)</label>

                                                <input type="number" name="policy_term_years" id="edit_policy_term_years"
                                                    class="form-control" value="{{ old('policy_term_years') }}" min="1"
                                                    placeholder="e.g. 1">
                                            </div>
                                            {{-- Actual Price --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    Actual Price (₹) <span class="text-danger">*</span>
                                                </label>

                                                <input type="number" name="actual_price" id="edit_actual_price"
                                                    class="form-control" min="0" step="0.01" required>
                                            </div>

                                            {{-- Price --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    Price (₹) <span class="text-danger">*</span>
                                                </label>

                                                <input type="number" name="price" id="edit_price" class="form-control"
                                                    value="{{ old('price', '0.00') }}" min="0" step="0.01" required>
                                            </div>

                                            {{-- Discount Price --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    Discount Price (₹) <span class="text-danger">*</span>
                                                </label>

                                                <input type="number" name="discount_price" id="edit_discount_price"
                                                    class="form-control" value="{{ old('discount_price', '0.00') }}" min="0"
                                                    step="0.01" required>
                                            </div>

                                            {{-- Tax Price --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    Tax Price (₹) <span class="text-danger">*</span>
                                                </label>

                                                <input type="number" name="tax_price" id="edit_tax_price"
                                                    class="form-control" value="{{ old('tax_price', '0.00') }}" min="0"
                                                    step="0.01" required>
                                            </div>

                                            {{-- Minimum Entry Age --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Minimum Entry Age</label>

                                                <input type="number" name="minimum_entry_age" id="edit_minimum_entry_age"
                                                    class="form-control" value="{{ old('minimum_entry_age') }}" min="0"
                                                    placeholder="Enter minimum age">
                                            </div>

                                            {{-- Maximum Entry Age --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Maximum Entry Age</label>

                                                <input type="number" name="maximum_entry_age" id="edit_maximum_entry_age"
                                                    class="form-control" value="{{ old('maximum_entry_age') }}" min="0"
                                                    placeholder="Enter maximum age">
                                            </div>

                                            {{-- Key Benefits --}}
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Key Benefits</label>

                                                <textarea name="key_benefits" id="edit_key_benefits" class="form-control"
                                                    rows="4"
                                                    placeholder="Enter key benefits">{{ old('key_benefits') }}</textarea>
                                            </div>

                                            {{-- Status --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">
                                                    Status <span class="text-danger">*</span>
                                                </label>

                                                <select name="status" id="edit_status" class="form-select" required>
                                                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>
                                                        Active
                                                    </option>

                                                    <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>
                                                        Inactive
                                                    </option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>

                                    {{-- Footer --}}
                                    <div class="modal-footer flex-shrink-0">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                            Cancel
                                        </button>

                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-device-floppy me-1"></i>
                                            Update Plan
                                        </button>
                                    </div>

                                </form>
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

                        <div class="card-body text-center">

                            @if($provider->logo)

                                <a href="{{ asset($provider->logo) }}" target="_blank">

                                    <img src="{{ asset($provider->logo) }}" alt="{{ $provider->name }}"
                                        class="img-fluid rounded border"
                                        style="
                                                                                                                                                                        width: 220px;
                                                                                                                                                                        height: 220px;
                                                                                                                                                                        object-fit: contain;
                                                                                                                                                                    ">

                                </a>

                            @else

                                <div class="d-flex align-items-center justify-content-center bg-light rounded border"
                                    style="
                                                                                                                                                                    width: 220px;
                                                                                                                                                                    height: 220px;
                                                                                                                                                                    margin: auto;
                                                                                                                                                                ">

                                    <div class="text-muted">

                                        <i class="ti ti-building-hospital" style="font-size: 50px;"></i>

                                        <div class="mt-2">
                                            No Logo
                                        </div>

                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="card">

                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Status
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="d-flex align-items-center justify-content-between">

                                <span class="text-muted">
                                    Current Status
                                </span>

                                @if($provider->status)

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Inactive
                                    </span>

                                @endif

                            </div>


                            <hr>


                            <div class="d-flex align-items-center justify-content-between">

                                <span class="text-muted">
                                    Display Order
                                </span>

                                <strong>
                                    {{ $provider->display_order ?? 0 }}
                                </strong>

                            </div>

                        </div>

                    </div>


                    {{-- Actions --}}
                    <div class="card">

                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Actions
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="d-grid gap-2">

                                <a href="{{ route('admin.health-insurance-providers.edit', $provider->id) }}"
                                    class="btn btn-primary">
                                    <i class="ti ti-edit me-1"></i>
                                    Edit Provider
                                </a>


                                <a href="{{ route('admin.health-insurance-providers.toggle-status', $provider->id) }}"
                                    class="btn btn-outline-secondary"
                                    onclick="return confirm('Are you sure you want to change the provider status?')">

                                    @if($provider->status)
                                        <i class="ti ti-ban me-1"></i>
                                        Deactivate Provider
                                    @else
                                        <i class="ti ti-check me-1"></i>
                                        Activate Provider
                                    @endif

                                </a>


                                <form action="{{ route('admin.health-insurance-providers.destroy', $provider->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this provider? This action cannot be undone.')">

                                    @csrf

                                    @method('DELETE')

                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        <i class="ti ti-trash me-1"></i>
                                        Delete Provider
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('addPlanModal');

            if (!modal) return;

            modal.addEventListener('shown.bs.modal', function () {
                const body = modal.querySelector('.modal-body');

                if (body) {
                    body.style.overflowY = 'scroll';
                    body.style.maxHeight = 'none';
                }
            });
        });
    </script>

    <script>

        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('editPlanModal');
            const form = document.getElementById('editPlanForm');

            if (!modal || !form) return;

            modal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                if (!button) return;

                form.action = button.dataset.updateUrl || '';

                const fields = {
                    edit_name: 'name',
                    edit_slug: 'slug',
                    edit_description: 'description',
                    edit_policy_term_years: 'policyTermYears',
                    edit_price: 'price',
                    edit_discount_price: 'discountPrice',
                    edit_tax_price: 'taxPrice',
                    edit_minimum_entry_age: 'minimumEntryAge',
                    edit_maximum_entry_age: 'maximumEntryAge',
                    edit_key_benefits: 'keyBenefits',
                    edit_status: 'status'
                };

                Object.entries(fields).forEach(([id, key]) => {
                    const input = document.getElementById(id);

                    if (input) {
                        input.value = button.dataset[key] ?? '';
                    }
                });
            });
        });

    </script>

    <script>
        function previewPlanImage(input, previewId) {
            const preview = document.getElementById(previewId);

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>


@endsection