@php
    $editing = isset($product);
@endphp

<div class="product-form-grid">
    <div class="product-form-card">
        <div class="product-field">
            <label for="name">Product Name</label>
            <input id="name" name="name" class="product-input" value="{{ old('name', $product->name ?? '') }}" required>
            @error('name') <span class="product-error">{{ $message }}</span> @enderror
        </div>

        <div class="product-field">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="product-textarea" rows="5">{{ old('description', $product->description ?? '') }}</textarea>
            @error('description') <span class="product-error">{{ $message }}</span> @enderror
        </div>

        <div class="product-field">
            <label for="category">Category</label>
            <input id="category" name="category" list="productCategories" class="product-input" value="{{ old('category', $product->category ?? '') }}" placeholder="Pomade, Shampoo, Tools">
            <datalist id="productCategories">
                @foreach($categories as $category)
                    <option value="{{ $category }}">
                @endforeach
            </datalist>
            @error('category') <span class="product-error">{{ $message }}</span> @enderror
        </div>

        <div class="product-form-grid product-form-fields">
            <div class="product-field">
                <label for="price">Price (RM)</label>
                <input id="price" type="number" step="0.01" min="0" name="price" class="product-input" value="{{ old('price', $product->price ?? '') }}" required>
                @error('price') <span class="product-error">{{ $message }}</span> @enderror
            </div>

            <div class="product-field">
                <label for="stock">Stock</label>
                <input id="stock" type="number" min="0" name="stock" class="product-input" value="{{ old('stock', $product->stock ?? 0) }}" required>
                @error('stock') <span class="product-error">{{ $message }}</span> @enderror
            </div>

            <div class="product-field">
                <label for="status">Status</label>
                <select id="status" name="status" class="product-select" required>
                    <option value="active" {{ old('status', $product->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $product->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status') <span class="product-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="product-field">
            <label for="image">Product Image</label>
            <input id="image" name="image" type="file" class="product-input" accept="image/*">
            <small class="product-help">Upload JPG, PNG, WEBP, or GIF. Images are saved to Cloudinary.</small>
            @error('image') <span class="product-error">{{ $message }}</span> @enderror
        </div>

        <div class="product-actions">
            <button type="submit" class="product-btn primary">
                <i class="fas fa-save"></i> {{ $editing ? 'Update Product' : 'Create Product' }}
            </button>
            <a href="{{ $editing ? route('admin.products.show', $product) : route('admin.products.index') }}" class="product-btn">
                <i class="fas fa-arrow-left"></i> Cancel
            </a>
        </div>
    </div>

    <div class="product-form-card">
        <div class="product-preview" id="imagePreview">
            @if($product->image_url ?? null)
                <img src="{{ $product->image_url }}" alt="Product image preview">
            @else
                <i class="fas fa-box-open"></i>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('image');
    const preview = document.getElementById('imagePreview');

    input?.addEventListener('change', function () {
        const file = this.files?.[0];

        if (!file) {
            return;
        }

        const reader = new FileReader();
        reader.onload = function (event) {
            preview.innerHTML = `<img src="${event.target.result}" alt="Product image preview">`;
        };
        reader.readAsDataURL(file);
    });
});
</script>
