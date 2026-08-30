@php
    $notes = $product->scent_notes ?? [];
@endphp

<div class="mb-3">
    <label class="form-label">Product Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="3" required>{{ old('description', $product->description ?? '') }}</textarea>
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Top Notes (comma-separated)</label>
        <input type="text" name="scent_notes_top" class="form-control" value="{{ old('scent_notes_top', implode(', ', $notes['top'] ?? [])) }}" placeholder="Bergamot, Lemon">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Middle Notes</label>
        <input type="text" name="scent_notes_middle" class="form-control" value="{{ old('scent_notes_middle', implode(', ', $notes['middle'] ?? [])) }}" placeholder="Rose, Jasmine">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Base Notes</label>
        <input type="text" name="scent_notes_base" class="form-control" value="{{ old('scent_notes_base', implode(', ', $notes['base'] ?? [])) }}" placeholder="Musk, Amber">
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Price (EGP)</label>
        <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price', $product->price ?? '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Stock Quantity</label>
        <input type="number" min="0" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $product->stock_quantity ?? '') }}" required>
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Product Image</label>
    <input type="file" name="image" class="form-control" accept="image/png, image/jpeg, image/webp">
    @if(!empty($product->image))
        <img src="{{ $product->imageUrl() }}" class="mt-2 rounded" style="width: 80px; height: 80px; object-fit: cover;">
    @endif
</div>
