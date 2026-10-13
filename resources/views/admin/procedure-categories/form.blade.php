
@php
    $isEdit = isset($procedureCategory);
@endphp

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

{{-- Category Name --}}
<div class="mb-3">
    <label for="name" class="form-label">
        Category Name <span class="text-danger">*</span>
    </label>

    <input
        type="text"
        name="name"
        id="name"
        class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $procedureCategory->name ?? '') }}"
        placeholder="Enter procedure category name"
        required
    >

    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Auto-generated Slug Preview --}}
<div class="mb-3">
    <label for="slug_preview" class="form-label">Slug Preview</label>

    <input
        type="text"
        id="slug_preview"
        class="form-control bg-light"
        value="{{ old('name', $procedureCategory->name ?? '') ? \Illuminate\Support\Str::slug(old('name', $procedureCategory->name ?? '')) : '' }}"
        placeholder="Slug will generate automatically"
        readonly
    >

    <small class="text-muted">
        Automatically generated from the category name.
    </small>
</div>

{{-- Category Image --}}
<div class="mb-3">
    <label for="image" class="form-label">Category Image</label>

    <input
        type="file"
        name="image"
        id="image"
        class="form-control @error('image') is-invalid @enderror"
        accept=".jpg,.jpeg,.png,.webp"
    >

    @error('image')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    <small class="text-muted">
        JPG, JPEG, PNG, or WEBP. Maximum size: 2 MB.
    </small>

    @if($isEdit && $procedureCategory->image)
        <div class="mt-3">
            <p class="mb-2">Current image:</p>

            <img
                src="{{ asset($procedureCategory->image) }}"
                alt="{{ $procedureCategory->name }}"
                width="120"
                style="height: 100px; object-fit: cover; border-radius: 8px;"
            >
        </div>
    @endif
</div>

{{-- Description --}}
<div class="mb-3">
    <label for="description" class="form-label">Description</label>

    <textarea
        name="description"
        id="description"
        rows="4"
        class="form-control @error('description') is-invalid @enderror"
        placeholder="Enter category description"
    >{{ old('description', $procedureCategory->description ?? '') }}</textarea>

    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Buttons --}}
<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">
        {{ $isEdit ? 'Update Category' : 'Save Category' }}
    </button>

    <a href="{{ route('admin.procedure-categories.index') }}"
       class="btn btn-secondary">
        Cancel
    </a>
</div>

{{-- Auto Slug Preview Script --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const nameInput = document.getElementById('name');
    const slugPreview = document.getElementById('slug_preview');

    if (!nameInput || !slugPreview) return;

    function generateSlug(value) {
        return value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/[\s-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    function updateSlug() {
        slugPreview.value = generateSlug(nameInput.value);
    }

    nameInput.addEventListener('input', updateSlug);

    updateSlug();
});
</script>