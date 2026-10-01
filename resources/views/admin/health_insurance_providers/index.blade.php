@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            {{-- Page Header --}}
            <div class="page-header">
                <div class="row align-items-center">
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

                    <div class="col-auto">
                        <a href="{{ route('admin.health-insurance-providers.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>
                            Add Provider
                        </a>
                    </div>
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

@endsection