@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            {{-- Page Header --}}
            <div class="page-header">
                <div class="row align-items-center ">
                    <div class="col">
                        <h4 class="page-title">
                            Health Insurance Providers
                        </h4>

                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">
                                    Dashboard
                                </a>
                            </li>

                            <li class="breadcrumb-item active">
                                Health Insurance Providers
                            </li>
                        </ul>
                    </div>

                </div>

                <div class="page-btn d-flex gap-2">

                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#importProvidersModal">
                        <i class="ti ti-file-import me-1"></i>
                        Import Excel
                    </button>

                    <a href="{{ route('admin.health-insurance-providers.create') }}" class="btn btn-added">
                        <i data-feather="plus-circle" class="me-2"></i>
                        Add Provider
                    </a>

                </div>
            </div>


            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>
                </div>
            @endif


            {{-- Error Message --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>
                </div>
            @endif


            {{-- Providers Table --}}
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Health Insurance Providers
                    </h5>
                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-striped table-hover datatable">

                            <thead>
                                <tr>

                                    <th width="60">
                                        #
                                    </th>

                                    <th width="80">
                                        Logo
                                    </th>

                                    <th>
                                        Provider
                                    </th>

                                    <th>
                                        Type
                                    </th>

                                    <th>
                                        Website
                                    </th>

                                    <th>
                                        Display Order
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th width="150">
                                        Action
                                    </th>

                                </tr>
                            </thead>


                            <tbody>

                                @foreach($providers as $provider)

                                    <tr>

                                        {{-- # --}}
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>

                                        {{-- Logo --}}
                                        <td>
                                            @if($provider->logo)
                                                <img src="{{ asset($provider->logo) }}" alt="{{ $provider->name }}"
                                                    class="rounded border" style="
                                                                            width: 50px;
                                                                            height: 50px;
                                                                            object-fit: contain;
                                                                        ">
                                            @else
                                                <div class="avatar avatar-md bg-light">
                                                    <span class="avatar-title">
                                                        <i class="ti ti-building-hospital"></i>
                                                    </span>
                                                </div>
                                            @endif
                                        </td>

                                        {{-- Provider --}}
                                        <td>
                                            <h6 class="mb-1">
                                                {{ $provider->name }}
                                            </h6>

                                            @if($provider->description)
                                                <small class="text-muted">
                                                    {{ Str::limit($provider->description, 60) }}
                                                </small>
                                            @endif
                                        </td>

                                        {{-- Type --}}
                                        <td>

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

                                        </td>

                                        {{-- Website --}}
                                        <td>
                                            @if($provider->website_url)

                                                <a href="{{ $provider->website_url }}" target="_blank" class="text-primary">
                                                    Visit Website
                                                    <i class="ti ti-external-link ms-1"></i>
                                                </a>

                                            @else

                                                <span class="text-muted">-</span>

                                            @endif
                                        </td>

                                        {{-- Display Order --}}
                                        <td>
                                            {{ $provider->display_order }}
                                        </td>

                                        {{-- Status --}}
                                        <td>

                                            @if($provider->status)

                                                <a
                                                    href="{{ route('admin.health-insurance-providers.toggle-status', $provider->id) }}">
                                                    <span class="badge bg-success">
                                                        Active
                                                    </span>
                                                </a>

                                            @else

                                                <a
                                                    href="{{ route('admin.health-insurance-providers.toggle-status', $provider->id) }}">
                                                    <span class="badge bg-danger">
                                                        Inactive
                                                    </span>
                                                </a>

                                            @endif

                                        </td>

                                        {{-- Actions --}}
                                        <td>

                                            <div class="d-flex align-items-center gap-2">

                                                {{-- View --}}
                                                <a href="{{ route('admin.health-insurance-providers.show', $provider->id) }}"
                                                    class="btn btn-sm btn-light" title="View">
                                                    <i class="ti ti-eye"></i>
                                                </a>

                                                {{-- Edit --}}
                                                <a href="{{ route('admin.health-insurance-providers.edit', $provider->id) }}"
                                                    class="btn btn-sm btn-light" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>

                                                {{-- Delete --}}
                                                <form
                                                    action="{{ route('admin.health-insurance-providers.destroy', $provider->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this provider?')">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-sm btn-light text-danger"
                                                        title="Delete">
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

                </div>

            </div>

        </div>
    </div>
    <div class="modal fade" id="importProvidersModal" tabindex="-1" aria-labelledby="importProvidersLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form action="{{ route('admin.health-insurance-providers.import') }}" method="POST"
                    enctype="multipart/form-data">

                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title" id="importProvidersLabel">
                            Import Health Insurance Providers
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <div class="d-flex justify-content-between align-items-center
                                    p-3 mb-3 border rounded">
                            <div>
                                <h6 class="mb-1">Sample Excel Template</h6>
                                <small class="text-muted">
                                    Includes sample data and embedded logos.
                                </small>
                            </div>

                            <a href="{{ asset('samples/health_insurance_providers_import_sample.xlsx') }}"
                                class="btn btn-outline-success btn-sm flex-shrink-0" download>
                                <i class="ti ti-download me-1"></i>
                                Download
                            </a>
                        </div>

                        <div class="alert alert-info">
                            <strong>Excel columns</strong>
                            <ul class="mb-0 mt-2">
                                <li>name</li>
                                <li>type</li>
                                <li>slug</li>
                                <li>logo</li>
                                <li>description</li>
                                <li>website_url</li>
                                <li>support_email</li>
                                <li>support_phone</li>
                                <li>display_order</li>
                                <li>status</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Excel File <span class="text-danger">*</span>
                            </label>

                            <input type="file" name="excel_file" class="form-control" accept=".xlsx" required>

                            <small class="text-muted">
                                XLSX format only. Maximum size: 20 MB.
                            </small>
                        </div>

                        <div class="alert alert-warning mb-0">
                            Insert actual embedded pictures in the logo column.
                            Do not enter image paths or filenames.
                            Provider names and slugs must be unique.
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" class="btn btn-success">
                            <i class="ti ti-upload me-1"></i>
                            Import Providers
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection