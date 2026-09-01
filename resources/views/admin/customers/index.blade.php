@extends('layout.mainlayout')

@section('content')

    <style>
        .customer-photo {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 50%;
        }

        .customer-placeholder {
            width: 45px;
            height: 45px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #f1f1f1;
            color: #777;
            font-size: 20px;
        }

        .customer-filter-card {
            margin-bottom: 20px;
        }

        .customer-filter-card .form-label {
            font-weight: 500;
            margin-bottom: 6px;
        }

        .filter-buttons {
            display: flex;
            align-items: end;
            gap: 8px;
        }

        @media (max-width: 767px) {
            .filter-buttons {
                margin-top: 10px;
            }
        }
    </style>


    <div class="page-wrapper">

        <div class="content">

            <!-- ================= PAGE HEADER ================= -->

            <div class="page-header">

                <div class="page-title">

                    <h4>Customers</h4>

                    <h6>Manage Customers</h6>

                </div>

            </div>


            <!-- ================= ALERTS ================= -->

            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">

                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>

            @endif


            @if(session('error'))

                <div class="alert alert-danger alert-dismissible fade show">

                    {{ session('error') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>

            @endif


            <!-- ================= FILTER CARD ================= -->

            <div class="card customer-filter-card">

                <div class="card-body">
                    <form method="GET" action="{{ route('admin.customers.index') }}">

                        <div class="row g-3 align-items-end">

                            <!-- Search -->
                            <div class="col-lg-3 col-md-6">

                                <label class="form-label">
                                    Search Customer
                                </label>

                                <input type="text" name="search" class="form-control" placeholder="Search customer..."
                                    value="{{ request('search') }}">

                            </div>


                            <!-- Gender -->
                            <div class="col-lg-2 col-md-6">

                                <label class="form-label">
                                    Gender
                                </label>

                                <select name="gender" class="form-select">
                                    <option value="">All Gender</option>

                                    @foreach($genders as $gender)
                                        <option value="{{ $gender }}" {{ request('gender') == $gender ? 'selected' : '' }}>
                                            {{ $gender }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>


                            <!-- City -->
                            <div class="col-lg-2 col-md-6">

                                <label class="form-label">
                                    City
                                </label>

                                <select id="cityFilter" class="form-select">

                                    <option value="">All Cities</option>

                                    @foreach($customers->pluck('city')->filter()->unique()->sort() as $city)

                                        <option value="{{ strtolower($city) }}">
                                            {{ $city }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            <!-- Verified -->
                            <div class="col-lg-2 col-md-6">

                                <label class="form-label">
                                    Verification
                                </label>

                                <select name="is_verified" class="form-select">
                                    <option value="">All Verification</option>

                                    <option value="1" {{ request('is_verified') == '1' ? 'selected' : '' }}>
                                        Verified
                                    </option>

                                    <option value="0" {{ request('is_verified') == '0' ? 'selected' : '' }}>
                                        Not Verified
                                    </option>
                                </select>

                            </div>


                            <!-- Status -->
                            <div class="col-lg-2 col-md-6">

                                <label class="form-label">
                                    Status
                                </label>

                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>

                            </div>


                            <!-- Reset -->
                            <div class="col-lg-1 col-md-6">

                                <div class="d-flex align-items-center gap-2">

                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-filter me-1"></i>
                                        Filter
                                    </button>

                                    <a href="{{ route('admin.customers.index') }}" class="btn btn-light">
                                        <i class="ti ti-refresh me-1"></i>
                                        Reset
                                    </a>

                                </div>

                            </div>

                        </div>
                    </form>

                </div>

            </div>


            <!-- ================= CUSTOMERS TABLE ================= -->

            <div class="card">

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table datatable" id="customersTable">

                            <thead>

                                <tr>

                                    <th>#</th>

                                    <th>Photo</th>

                                    <th>Name</th>

                                    <th>Customer Code</th>

                                    <th>Mobile</th>

                                    <th>Email</th>

                                    <th>Gender</th>

                                    <th>City</th>

                                    <th>Verified</th>

                                    <th>Status</th>

                                    <th>Action</th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($customers as $key => $customer)

                                    <tr>

                                        <!-- # -->

                                        <td>
                                            {{ $key + 1 }}
                                        </td>


                                        <!-- Photo -->

                                        <td>

                                            @if($customer->photo)

                                                <img src="{{ asset($customer->photo) }}" alt="{{ $customer->name }}"
                                                    class="customer-photo">

                                            @else

                                                <span class="customer-placeholder">

                                                    <i class="ti ti-user"></i>

                                                </span>

                                            @endif

                                        </td>


                                        <!-- Name -->

                                        <td>

                                            <strong>
                                                {{ $customer->name ?? 'N/A' }}
                                            </strong>

                                        </td>


                                        <!-- Customer Code -->

                                        <td>

                                            <span class="text-muted">
                                                {{ $customer->customer_code }}
                                            </span>

                                        </td>


                                        <!-- Mobile -->

                                        <td>
                                            {{ $customer->mobile ?? 'N/A' }}
                                        </td>


                                        <!-- Email -->

                                        <td>
                                            {{ $customer->email ?? 'N/A' }}
                                        </td>


                                        <!-- Gender -->

                                        <td>

                                            {{ $customer->gender ?? 'N/A' }}

                                        </td>


                                        <!-- City -->

                                        <td>

                                            {{ $customer->city ?? 'N/A' }}

                                        </td>


                                        <!-- Verified -->

                                        <td>

                                            @if($customer->is_verified)

                                                <span class="badge bg-success">
                                                    Verified
                                                </span>

                                            @else

                                                <span class="badge bg-warning text-dark">
                                                    Not Verified
                                                </span>

                                            @endif

                                        </td>


                                        <!-- Status -->

                                        <td>

                                            @if($customer->status)

                                                <span class="badge bg-success">
                                                    Active
                                                </span>

                                            @else

                                                <span class="badge bg-danger">
                                                    Inactive
                                                </span>

                                            @endif

                                        </td>


                                        <!-- Action -->

                                        <td>

                                            <div class="d-flex gap-1">

                                                <!-- View -->

                                                <a href="{{ route('admin.customers.show', $customer) }}"
                                                    class="btn btn-info btn-sm" title="View">

                                                    <i class="ti ti-eye"></i>

                                                </a>


                                                <!-- Edit -->

                                                <a href="{{ route('admin.customers.edit', $customer) }}"
                                                    class="btn btn-warning btn-sm" title="Edit">

                                                    <i class="ti ti-edit"></i>

                                                </a>


                                                <!-- Delete -->

                                                <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST"
                                                    class="d-inline">

                                                    @csrf

                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this customer?')">

                                                        <i class="ti ti-trash"></i>

                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="11" class="text-center py-4">

                                            <h6 class="mb-1">
                                                No Customers Found
                                            </h6>

                                            <p class="text-muted mb-0">
                                                No customer records are available.
                                            </p>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection


@push('scripts')

    <script>

        $(document).ready(function () {

            /* ============================================
               DATATABLE
            ============================================ */

            let table = $('.datatable').DataTable({

                responsive: true,

                autoWidth: false,

                ordering: true,

                pageLength: 10,

                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],

                language: {

                    search: "",

                    searchPlaceholder: "Search..."

                }

            });


            /* ============================================
               CUSTOM SEARCH
            ============================================ */

            $('#customerSearch').on('keyup', function () {

                table.search(this.value).draw();

            });


            /* ============================================
               GENDER FILTER
            ============================================ */

            $('#genderFilter').on('change', function () {

                table
                    .column(6)
                    .search(this.value)
                    .draw();

            });


            /* ============================================
               CITY FILTER
            ============================================ */

            $('#cityFilter').on('change', function () {

                table
                    .column(7)
                    .search(this.value)
                    .draw();

            });


            /* ============================================
               VERIFIED FILTER
            ============================================ */

            $('#verifiedFilter').on('change', function () {

                table
                    .column(8)
                    .search(this.value)
                    .draw();

            });


            /* ============================================
               STATUS FILTER
            ============================================ */

            $('#statusFilter').on('change', function () {

                table
                    .column(9)
                    .search(this.value)
                    .draw();

            });


            /* ============================================
               RESET FILTERS
            ============================================ */

            $('#resetFilters').on('click', function () {

                $('#customerSearch').val('');

                $('#genderFilter').val('');

                $('#cityFilter').val('');

                $('#verifiedFilter').val('');

                $('#statusFilter').val('');

                table
                    .search('')
                    .columns()
                    .search('')
                    .draw();

            });


            /* ============================================
               FEATHER ICONS
            ============================================ */

            if (typeof feather !== 'undefined') {

                feather.replace();

            }

        });

    </script>

@endpush