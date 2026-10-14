@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            <!-- Page Header -->
            <div class="page-header">

                <div class="page-title">

                    <h4>Add Diagnostic</h4>

                    <h6>Create New Diagnostic Center</h6>

                </div>

                <div class="page-btn">

                    <a href="{{ route('admin.diagnostics.index') }}" class="btn btn-secondary">

                        <i class="ti ti-arrow-left me-1"></i>

                        Back

                    </a>

                </div>

            </div>
            <!-- /Page Header -->


            <!-- Validation Errors -->

            @if ($errors->any())

                <div class="alert alert-danger alert-dismissible fade show">

                    <strong>Please fix the following errors:</strong>

                    <ul class="mb-0 mt-2">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>

            @endif


            <form action="{{ route('admin.diagnostics.store') }}" method="POST" enctype="multipart/form-data">

                @csrf


                <!-- Basic Information -->

                <div class="card border-0 shadow-sm">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-building-hospital me-2"></i>

                            Diagnostic Information

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Diagnostic Name -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Diagnostic Name

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="text" name="diagnostic_name" id="diagnostic_name" class="form-control"
                                    value="{{ old('diagnostic_name') }}" placeholder="Enter Diagnostic Name">

                            </div>

                            <!-- Diagnostic Code -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Diagnostic Code

                                </label>

                                <input type="text" class="form-control" placeholder="Auto Generated" readonly>

                            </div>

                            <!-- Slug -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Slug

                                </label>

                                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}"
                                    placeholder="Auto Generated" readonly>

                            </div>

                            <!-- Registration Number -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Registration Number

                                </label>

                                <input type="text" name="registration_number" class="form-control"
                                    value="{{ old('registration_number') }}" placeholder="Registration Number">

                            </div>

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">
                                    Hospital
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="hospital_id" id="hospital_id" class="form-select">

                                    <option value="">Select Hospital</option>

                                    @foreach($hospitals as $hospital)

                                        <option value="{{ $hospital->id }}" @selected(old('hospital_id') == $hospital->id)>

                                            {{ $hospital->hospital_name }}

                                        </option>

                                    @endforeach

                                </select>

                                @error('hospital_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>
                            {{-- <div class="col-lg-6 mb-3">

    <label class="form-label">
        Diagnostic Category
        <span class="text-danger">*</span>
    </label>

    <select name="diagnostic_category_id"
            id="diagnostic_category_id"
            class="form-select">

        <option value="">Select Diagnostic Category</option>

        @foreach($diagnostic_categories as $diagnosticCategory)

            <option value="{{ $diagnosticCategory->id }}"
                @selected(old('diagnostic_category_id') == $diagnosticCategory->id)>

                {{ $diagnosticCategory->name }}

            </option>

        @endforeach

    </select>

    @error('diagnostic_category_id')
        <span class="text-danger">{{ $message }}</span>
    @enderror

</div> --}}

                        </div>

                    </div>
                    

                </div>
           
<!-- Diagnostic Categories -->
<div class="card border-0 shadow-sm mt-4">

    <div class="card-header">
        <h5 class="mb-0">
            <i class="ti ti-category me-2"></i>
            Diagnostic Categories
            <span class="text-danger">*</span>
        </h5>
    </div>

    <div class="card-body">

        <div class="row">

            @forelse($diagnostic_categories as $diagnosticCategory)

                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12 mb-3">

                    <div class="form-check">

                        <input
    type="checkbox"
    class="form-check-input diagnostic-category-checkbox"
    name="diagnostic_category_ids[]"
    value="{{ $diagnosticCategory->id }}"
    id="diagnostic_category_{{ $diagnosticCategory->id }}"
    @checked(
        in_array(
            (int) $diagnosticCategory->id,
            array_map('intval', old('diagnostic_category_ids', []))
        )
    )
>

                        <label
                            class="form-check-label fw-medium"
                            for="diagnostic_category_{{ $diagnosticCategory->id }}"
                        >
                            {{ $diagnosticCategory->name }}
                        </label>

                    </div>

                </div>

            @empty

                <div class="col-12">
                    <div class="alert alert-info mb-0">
                        No diagnostic categories available.
                    </div>
                </div>

            @endforelse

        </div>

        @error('diagnostic_category_ids')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror

        @error('diagnostic_category_ids.*')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror

    </div>

</div>


                <!-- Lab Tests -->


<!-- Lab Tests -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="ti ti-flask me-2"></i>
            Lab Tests
        </h5>
    </div>

    <div class="card-body">
        <div id="lab-tests-container" class="row">
            <div class="col-12 text-muted">
                Select a diagnostic category to view its lab tests.
            </div>
        </div>

        @error('lab_test_ids')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror

        @error('lab_test_ids.*')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
    </div>
</div>

                <!-- Contact Information -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-phone me-2"></i>

                            Contact Information

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Email -->

                            <div class="col-lg-4 mb-3">

                                <label class="form-label">

                                    Email

                                </label>

                                <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                    placeholder="Enter Email Address">

                            </div>

                            <!-- Mobile -->

                            <div class="col-lg-4 mb-3">

                                <label class="form-label">

                                    Mobile Number

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}"
                                    placeholder="Enter Mobile Number">

                            </div>

                            <!-- Phone -->

                            <div class="col-lg-4 mb-3">

                                <label class="form-label">

                                    Phone Number

                                </label>

                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}"
                                    placeholder="Enter Phone Number">

                            </div>

                        </div>

                    </div>

                </div>


                <!-- Address Information -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-map-pin me-2"></i>

                            Address Information

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Address -->

                            <div class="col-lg-12 mb-3">

                                <label class="form-label">

                                    Address

                                </label>

                                <textarea name="address" rows="4" class="form-control"
                                    placeholder="Enter Complete Address">{{ old('address') }}</textarea>

                            </div>

                            <!-- Country -->

                            <div class="col-lg-3 mb-3">

                                <label class="form-label">

                                    Country

                                </label>

                                <input type="text" name="country" class="form-control" value="{{ old('country') }}"
                                    placeholder="Country">

                            </div>

                            <!-- State -->

                            <div class="col-lg-3 mb-3">

                                <label class="form-label">

                                    State

                                </label>

                                <input type="text" name="state" class="form-control" value="{{ old('state') }}"
                                    placeholder="State">

                            </div>

                            <!-- City -->

                            <div class="col-lg-3 mb-3">

                                <label class="form-label">

                                    City

                                </label>

                                <input type="text" name="city" class="form-control" value="{{ old('city') }}"
                                    placeholder="City">

                            </div>

                            <!-- Pincode -->

                            <div class="col-lg-3 mb-3">

                                <label class="form-label">

                                    Pincode

                                </label>

                                <input type="text" name="pincode" class="form-control" value="{{ old('pincode') }}"
                                    placeholder="Pincode">

                            </div>

                        </div>

                    </div>

                </div>
                <!-- Location & Working Hours -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-map-search me-2"></i>

                            Location & Working Hours

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Latitude -->

                            <div class="col-lg-3 mb-3">

                                <label class="form-label">

                                    Latitude

                                </label>

                                <input type="text" name="latitude" class="form-control" value="{{ old('latitude') }}"
                                    placeholder="Latitude">

                            </div>

                            <!-- Longitude -->

                            <div class="col-lg-3 mb-3">

                                <label class="form-label">

                                    Longitude

                                </label>

                                <input type="text" name="longitude" class="form-control" value="{{ old('longitude') }}"
                                    placeholder="Longitude">

                            </div>

                            <!-- Opening Time -->

                            <div class="col-lg-3 mb-3">

                                <label class="form-label">

                                    Opening Time

                                </label>

                                <input type="time" name="opening_time" class="form-control"
                                    value="{{ old('opening_time') }}">

                            </div>

                            <!-- Closing Time -->

                            <div class="col-lg-3 mb-3">

                                <label class="form-label">

                                    Closing Time

                                </label>

                                <input type="time" name="closing_time" class="form-control"
                                    value="{{ old('closing_time') }}">

                            </div>

                            <!-- Home Collection -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Home Collection

                                </label>

                                <select name="home_collection" class="form-select">

                                    <option value="1" {{ old('home_collection', 1) == 1 ? 'selected' : '' }}>

                                        Yes

                                    </option>

                                    <option value="0" {{ old('home_collection') === '0' ? 'selected' : '' }}>

                                        No

                                    </option>

                                </select>

                            </div>

                            <!-- Status -->

                            <div class="col-lg-6 mb-3">

                                <label class="form-label">

                                    Status

                                </label>

                                <select name="status" class="form-select">

                                    <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>

                                        Active

                                    </option>

                                    <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>

                                        Inactive

                                    </option>

                                </select>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- Media Upload -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="ti ti-photo me-2"></i>

                            Media Upload

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Logo Upload -->

                            <div class="col-lg-6">

                                <div class="border rounded-3 p-4 text-center h-100">

                                    <h6 class="mb-3">

                                        Diagnostic Logo

                                    </h6>

                                    <img id="logoPreview" src="{{ asset('assets/img/no-image.png') }}"
                                        class="img-thumbnail mb-3" style="width:140px;height:140px;object-fit:contain;">

                                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*">

                                    <small class="text-muted d-block mt-2">

                                        Supported: JPG, PNG, SVG, WEBP

                                    </small>

                                </div>

                            </div>

                            <!-- Banner Upload -->

                            <div class="col-lg-6">

                                <div class="border rounded-3 p-4 text-center h-100">

                                    <h6 class="mb-3">

                                        Diagnostic Banner

                                    </h6>

                                    <img id="bannerPreview" src="{{ asset('assets/img/no-image.png') }}"
                                        class="img-thumbnail mb-3" style="width:250px;height:150px;object-fit:cover;">

                                    <input type="file" class="form-control" id="banner" name="banner" accept="image/*">

                                    <small class="text-muted d-block mt-2">

                                        Recommended Size: 1200 × 600 px

                                    </small>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
                <!-- Action Buttons -->

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('admin.diagnostics.index') }}" class="btn btn-light">

                                <i class="ti ti-arrow-left me-1"></i>

                                Cancel

                            </a>

                            <button type="submit" id="submitBtn" class="btn btn-primary">

                                <span id="submitText">

                                    <i class="ti ti-device-floppy me-1"></i>

                                    Save Diagnostic

                                </span>

                                <span id="loadingText" class="d-none">

                                    <span class="spinner-border spinner-border-sm me-2"></span>

                                    Saving...

                                </span>

                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>

        $(function () {

            // Auto Slug Generator

            $('#diagnostic_name').on('keyup change', function () {

                let slug = $(this).val()
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');

                $('#slug').val(slug);

            });


            // Logo Preview

            $('#logo').change(function () {

                const file = this.files[0];

                if (file) {

                    const reader = new FileReader();

                    reader.onload = function (e) {

                        $('#logoPreview').attr('src', e.target.result);

                    }

                    reader.readAsDataURL(file);

                }

            });


            // Banner Preview

            $('#banner').change(function () {

                const file = this.files[0];

                if (file) {

                    const reader = new FileReader();

                    reader.onload = function (e) {

                        $('#bannerPreview').attr('src', e.target.result);

                    }

                    reader.readAsDataURL(file);

                }

            });


            // Loading Button

            $('form').submit(function () {

                $('#submitBtn').prop('disabled', true);

                $('#submitText').addClass('d-none');

                $('#loadingText').removeClass('d-none');

            });

        });

    </script>
  
<script>
document.addEventListener('DOMContentLoaded', function () {
    const categoryCheckboxes = document.querySelectorAll(
        '.diagnostic-category-checkbox'
    );

    const container = document.getElementById('lab-tests-container');

    // Restore selected tests if validation previously failed.
    const previouslySelectedLabTests = @json(
        array_map('intval', old('lab_test_ids', []))
    );

    function loadLabTests() {
        const categoryIds = Array.from(categoryCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        if (categoryIds.length === 0) {
            container.innerHTML = `
                <div class="col-12 text-muted">
                    Select a diagnostic category to view its lab tests.
                </div>
            `;
            return;
        }

        container.innerHTML = `
            <div class="col-12 text-muted">
                Loading lab tests...
            </div>
        `;

        const params = new URLSearchParams();

        categoryIds.forEach(id => {
            params.append('category_ids[]', id);
        });

        fetch(
            `{{ route('admin.diagnostics.category-lab-tests') }}?${params.toString()}`,
            {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }
        )
        .then(response => {
            if (!response.ok) {
                throw new Error('Unable to load lab tests.');
            }

            return response.json();
        })
        .then(labTests => {
            container.innerHTML = '';

            if (labTests.length === 0) {
                container.innerHTML = `
                    <div class="col-12 text-muted">
                        No lab tests found for the selected categories.
                    </div>
                `;
                return;
            }

            labTests.forEach(labTest => {
                const column = document.createElement('div');
                column.className =
                    'col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12 mb-3';

                const wrapper = document.createElement('div');
                wrapper.className = 'form-check';

                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.className = 'form-check-input';
                checkbox.name = 'lab_test_ids[]';
                checkbox.value = labTest.id;
                checkbox.id = `lab_test_${labTest.id}`;

                checkbox.checked = previouslySelectedLabTests.includes(
                    Number(labTest.id)
                );

                const label = document.createElement('label');
                label.className = 'form-check-label fw-medium';
                label.htmlFor = checkbox.id;
                label.textContent = labTest.test_name;

                wrapper.appendChild(checkbox);
                wrapper.appendChild(label);
                column.appendChild(wrapper);
                container.appendChild(column);
            });
        })
        .catch(error => {
            container.innerHTML = `
                <div class="col-12 text-danger">
                    Unable to load lab tests. Please try again.
                </div>
            `;

            console.error(error);
        });
    }

    categoryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', loadLabTests);
    });

    // Also restore the selected categories after validation errors.
    loadLabTests();
});
</script>

@endsection