@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">
        <div class="content">

            {{-- Page Header --}}
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="page-title">Home Visit Services</h4>
                    </div>
                </div>
                <div class="col-auto">
                        <button type="button"
                            class="btn btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#addServiceModal">

                            <i class="ti ti-plus me-1"></i>
                            Add Home Visit Service

                        </button>
                    </div>
            </div>


            {{-- Success Message --}}
            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">

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

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- SERVICES TABLE --}}
            {{-- ========================================================= --}}

            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-0">
                        Home Visit Services List
                    </h5>

                </div>


                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead>

                                <tr>

                                    <th width="60">
                                        #
                                    </th>

                                    <th width="100">
                                        Image
                                    </th>

                                    <th>
                                        Category
                                    </th>

                                    <th>
                                        Service Name
                                    </th>


                                    <th>
                                        Price
                                    </th>

                                    <th>
                                        Price Per
                                    </th>

                                    <th>
                                        Description
                                    </th>

                                    <th width="150">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($services as $service)

                                    <tr>

                                        {{-- ID --}}
                                        <td>

                                            {{ $loop->iteration + ($services->currentPage() - 1) * $services->perPage() }}

                                        </td>


                                        {{-- Image --}}
                                        <td>

                                            @if($service->image)

                                                <img src="{{ asset($service->image) }}"
                                                    alt="{{ $service->name }}"
                                                    style="
                                                        width:60px;
                                                        height:60px;
                                                        object-fit:cover;
                                                        border-radius:8px;
                                                        border:1px solid #ddd;
                                                    ">

                                            @else

                                                <div style="
                                                    width:60px;
                                                    height:60px;
                                                    display:flex;
                                                    align-items:center;
                                                    justify-content:center;
                                                    background:#f5f5f5;
                                                    border-radius:8px;
                                                    color:#999;
                                                ">

                                                    <i class="ti ti-photo fs-20"></i>

                                                </div>

                                            @endif

                                        </td>


                                        {{-- Category --}}
                                        <td>

                                            <span class="badge bg-primary-transparent">

                                                {{ $service->category->name ?? '-' }}

                                            </span>

                                        </td>


                                        {{-- Service Name --}}
                                        <td>

                                            <strong>
                                                {{ $service->name }}
                                            </strong>

                                        </td>


                                        {{-- Price --}}
                                        <td>

                                            @if($service->price !== null)

                                                <strong>
                                                    ₹{{ number_format($service->price, 2) }}
                                                </strong>

                                            @else

                                                -

                                            @endif

                                        </td>


                                        {{-- Price Per --}}
                                        <td>

                                            @if($service->price_per === 'day')

                                                <span class="badge bg-success-transparent">
                                                    Per Day
                                                </span>

                                            @elseif($service->price_per === 'hour')

                                                <span class="badge bg-info-transparent">
                                                    Per Hour
                                                </span>

                                            @else

                                                -

                                            @endif

                                        </td>


                                        {{-- Description --}}
                                        <td>

                                            @if($service->description)

                                                {{ \Illuminate\Support\Str::limit($service->description, 50) }}

                                            @else

                                                -

                                            @endif

                                        </td>


                                        {{-- Actions --}}
                                        <td>

                                            <div class="d-flex align-items-center gap-2">

                                                {{-- Edit --}}
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-primary editServiceBtn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editServiceModal"

                                                    data-id="{{ $service->id }}"

                                                    data-category="{{ $service->home_visit_service_categories_id }}"

                                                    data-name="{{ $service->name }}"

                                                    data-slug="{{ $service->slug }}"

                                                    data-price="{{ $service->price }}"

                                                    data-price-per="{{ $service->price_per }}"

                                                    data-description="{{ $service->description }}"

                                                    data-image="{{ $service->image ? asset($service->image) : '' }}"

                                                    data-update-url="{{ route('admin.home-visit-services.update', $service->id) }}">

                                                    <i class="ti ti-edit"></i>

                                                </button>


                                                {{-- Delete --}}
                                                <form id="delete-form-{{ $service->id }}"
                                                    action="{{ route('admin.home-visit-services.destroy', $service->id) }}"
                                                    method="POST"
                                                    style="display:inline;">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="deleteService({{ $service->id }})">

                                                        <i class="ti ti-trash"></i>

                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    </tr>


                                @empty

                                    <tr>

                                        <td colspan="9"
                                            class="text-center py-4">

                                            <div class="text-muted">

                                                <i class="ti ti-info-circle fs-24 d-block mb-2"></i>

                                                No Home Visit Services Found

                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- Pagination --}}
                    @if($services->hasPages())

                        <div class="mt-3">

                            {{ $services->links() }}

                        </div>

                    @endif

                </div>

            </div>

        </div>
    </div>



    {{-- ========================================================= --}}
    {{-- ADD SERVICE MODAL --}}
    {{-- ========================================================= --}}

    <div class="modal fade"
        id="addServiceModal"
        tabindex="-1"
        aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">


                {{-- Header --}}
                <div class="modal-header">

                    <h5 class="modal-title">
                        Add Home Visit Service
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>


                {{-- Form --}}
                <form action="{{ route('admin.home-visit-services.store') }}"
                    method="POST"
                    enctype="multipart/form-data">

                    @csrf


                    <div class="modal-body">

                        <div class="row">


                            {{-- Category --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Category
                                    <span class="text-danger">*</span>

                                </label>


                                <select name="home_visit_service_categories_id"
                                    id="add_category"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        Select Category
                                    </option>


                                    @foreach($categories as $category)

                                        <option value="{{ $category->id }}">

                                            {{ $category->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Service Name --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Service Name
                                    <span class="text-danger">*</span>

                                </label>


                                <input type="text"
                                    name="name"
                                    id="add_name"
                                    class="form-control"
                                    placeholder="Enter service name"
                                    required>

                            </div>


                            {{-- Slug --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Slug
                                </label>


                                <input type="text"
                                    name="slug"
                                    id="add_slug"
                                    class="form-control"
                                    placeholder="Auto generated slug"
                                    readonly>

                            </div>


                            {{-- Price --}}
                            <div class="col-md-6">
    <label class="form-label">
        Price <span class="text-danger">*</span>
    </label>

    <div class="input-group">
        <span class="input-group-text">₹</span>

        <input type="number"
               name="price"
               id="add_price"
               class="form-control"
               placeholder="Enter price"
               min="0"
               step="0.01">

        <select name="price_per"
                id="add_price_per"
                class="form-select"
                style="max-width: 140px;">

            <option value="hour">Per Hour</option>
            <option value="day">Per Day</option>

        </select>
    </div>
</div>


                            {{-- Image --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Image
                                </label>


                                <input type="file"
                                    name="image"
                                    id="add_image"
                                    class="form-control"
                                    accept=".jpg,.jpeg,.png,.webp">

                            </div>


                            {{-- Description --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Description
                                </label>


                                <textarea name="description"
                                    id="add_description"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Enter description"></textarea>

                            </div>

                        </div>

                    </div>


                    {{-- Footer --}}
                    <div class="modal-footer">

                        <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">

                            Cancel

                        </button>


                        <button type="submit"
                            class="btn btn-primary">

                            <i class="ti ti-check me-1"></i>

                            Save Service

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- EDIT SERVICE MODAL --}}
    {{-- ========================================================= --}}

    <div class="modal fade"
        id="editServiceModal"
        tabindex="-1"
        aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">


                {{-- Header --}}
                <div class="modal-header">

                    <h5 class="modal-title">
                        Edit Home Visit Service
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>


                {{-- Form --}}
                <form id="editServiceForm"
                    method="POST"
                    enctype="multipart/form-data">

                    @csrf
                    @method('PUT')


                    <div class="modal-body">

                        <div class="row">


                            {{-- Category --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Category
                                    <span class="text-danger">*</span>

                                </label>


                                <select name="home_visit_service_categories_id"
                                    id="edit_category"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        Select Category
                                    </option>


                                    @foreach($categories as $category)

                                        <option value="{{ $category->id }}">

                                            {{ $category->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Service Name --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Service Name
                                    <span class="text-danger">*</span>

                                </label>


                                <input type="text"
                                    name="name"
                                    id="edit_name"
                                    class="form-control"
                                    required>

                            </div>


                            {{-- Slug --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Slug
                                </label>


                                <input type="text"
                                    name="slug"
                                    id="edit_slug"
                                    class="form-control"
                                    placeholder="Auto generated slug"
                                    readonly>

                            </div>


                            {{-- Price --}}
                            <div class="col-md-6">
    <label class="form-label">
        Price <span class="text-danger">*</span>
    </label>

    <div class="input-group">
        <span class="input-group-text">₹</span>

        <input type="number"
               name="price"
               id="edit_price"
               class="form-control"
               placeholder="Enter price"
               min="0"
               step="0.01">

        <select name="price_per"
                id="edit_price_per"
                class="form-select"
                style="max-width: 140px;">

            <option value="hour">Per Hour</option>
            <option value="day">Per Day</option>

        </select>
    </div>
</div>


                            {{-- Current Image --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Current Image
                                </label>


                                <div id="editCurrentImageWrapper"
                                    style="display:none;">

                                    <img id="edit_image_preview"
                                        src=""
                                        alt="Service Image"
                                        style="
                                            width:100px;
                                            height:100px;
                                            object-fit:cover;
                                            border-radius:8px;
                                            border:1px solid #ddd;
                                        ">

                                </div>

                            </div>


                            {{-- New Image --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Change Image
                                </label>


                                <input type="file"
                                    name="image"
                                    id="edit_image"
                                    class="form-control"
                                    accept=".jpg,.jpeg,.png,.webp">

                            </div>


                            {{-- Description --}}
                            <div class="col-12 mb-3">

                                <label class="form-label">
                                    Description
                                </label>


                                <textarea name="description"
                                    id="edit_description"
                                    class="form-control"
                                    rows="4"></textarea>

                            </div>

                        </div>

                    </div>


                    {{-- Footer --}}
                    <div class="modal-footer">

                        <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">

                            Cancel

                        </button>


                        <button type="submit"
                            class="btn btn-primary">

                            <i class="ti ti-check me-1"></i>

                            Update Service

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ========================================================= --}}

   <script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Generate Slug
    |--------------------------------------------------------------------------
    */
    function generateSlug(value) {
        return String(value || '')
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    }


    /*
    |--------------------------------------------------------------------------
    | ADD SERVICE - AUTO SLUG
    |--------------------------------------------------------------------------
    */
    const addNameInput = document.getElementById('add_name');
    const addSlugInput = document.getElementById('add_slug');

    if (addNameInput && addSlugInput) {

        addNameInput.addEventListener('input', function () {
            addSlugInput.value = generateSlug(this.value);
        });

    }


    /*
    |--------------------------------------------------------------------------
    | EDIT SERVICE
    |--------------------------------------------------------------------------
    */
    const editButtons = document.querySelectorAll('.editServiceBtn');

    editButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            /*
            |--------------------------------------------------------------------------
            | Get Data
            |--------------------------------------------------------------------------
            */
            const category = this.dataset.category || '';
            const name = this.dataset.name || '';
            const slug = this.dataset.slug || '';
            const price = this.dataset.price || '';
            const pricePer = this.dataset.pricePer || 'hour';
            const description = this.dataset.description || '';
            const image = this.dataset.image || '';
            const updateUrl = this.dataset.updateUrl || '';


            /*
            |--------------------------------------------------------------------------
            | Edit Form
            |--------------------------------------------------------------------------
            */
            const form = document.getElementById('editServiceForm');

            if (form && updateUrl) {
                form.action = updateUrl;
            }


            /*
            |--------------------------------------------------------------------------
            | Category
            |--------------------------------------------------------------------------
            */
            const categoryInput = document.getElementById('edit_category');

            if (categoryInput) {
                categoryInput.value = category;
            }


            /*
            |--------------------------------------------------------------------------
            | Name
            |--------------------------------------------------------------------------
            */
            const nameInput = document.getElementById('edit_name');

            if (nameInput) {
                nameInput.value = name;
            }


            /*
            |--------------------------------------------------------------------------
            | Slug
            |--------------------------------------------------------------------------
            */
            const slugInput = document.getElementById('edit_slug');

            if (slugInput) {
                slugInput.value = slug;
            }


            /*
            |--------------------------------------------------------------------------
            | Price
            |--------------------------------------------------------------------------
            */
            const priceInput = document.getElementById('edit_price');

            if (priceInput) {
                priceInput.value = price;
            }


            /*
            |--------------------------------------------------------------------------
            | Price Per
            |--------------------------------------------------------------------------
            */
            const pricePerInput = document.getElementById('edit_price_per');

            if (pricePerInput) {

                // Make sure the selected value exists
                const optionExists = Array.from(
                    pricePerInput.options
                ).some(function (option) {
                    return option.value === pricePer;
                });

                pricePerInput.value = optionExists
                    ? pricePer
                    : 'hour';
            }


            /*
            |--------------------------------------------------------------------------
            | Description
            |--------------------------------------------------------------------------
            */
            const descriptionInput =
                document.getElementById('edit_description');

            if (descriptionInput) {
                descriptionInput.value = description;
            }


            /*
            |--------------------------------------------------------------------------
            | Current Image
            |--------------------------------------------------------------------------
            */
            const preview =
                document.getElementById('edit_image_preview');

            const imageWrapper =
                document.getElementById('editCurrentImageWrapper');

            if (image) {

                if (preview) {
                    preview.src = image;
                }

                if (imageWrapper) {
                    imageWrapper.style.display = 'block';
                }

            } else {

                if (preview) {
                    preview.removeAttribute('src');
                }

                if (imageWrapper) {
                    imageWrapper.style.display = 'none';
                }

            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | EDIT SERVICE - AUTO SLUG
    |--------------------------------------------------------------------------
    */
    const editNameInput =
        document.getElementById('edit_name');

    const editSlugInput =
        document.getElementById('edit_slug');

    if (editNameInput && editSlugInput) {

        editNameInput.addEventListener('input', function () {

            editSlugInput.value =
                generateSlug(this.value);

        });

    }


    /*
    |--------------------------------------------------------------------------
    | DELETE SERVICE
    |--------------------------------------------------------------------------
    */
    window.deleteService = function (id) {

        if (!id) {
            return;
        }

        const confirmed = confirm(
            'Are you sure you want to delete this home visit service?'
        );

        if (!confirmed) {
            return;
        }

        const form =
            document.getElementById('delete-form-' + id);

        if (form) {
            form.submit();
        }

    };

});
</script>

@endsection