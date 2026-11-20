@extends('layouts.admin')

@section('title', $product->exists ? 'Edit Product' : 'Add Product')
@section('page_title', $product->exists ? 'Edit: ' . $product->name : 'Add New Battery Product')

@section('content')
<div class="card kpi-card p-4">
    <form method="POST" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        @if($product->exists) @method('PUT') @endif

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Product Name *</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Brand *</label>
                <select name="product_brand_id" class="form-select" required>
                    @foreach($brands as $b)
                        <option value="{{ $b->id }}" @selected(old('product_brand_id', $product->product_brand_id) == $b->id)>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Battery Type *</label>
                <select name="battery_type_id" class="form-select" required>
                    @foreach($batteryTypes as $t)
                        <option value="{{ $t->id }}" @selected(old('battery_type_id', $product->battery_type_id) == $t->id)>{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-semibold">Model Number *</label>
                <input type="text" name="model_number" value="{{ old('model_number', $product->model_number) }}" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">SKU</label>
                <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Capacity (Ah) *</label>
                <input type="number" name="capacity_ah" value="{{ old('capacity_ah', $product->capacity_ah ?? 45) }}" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">CCA *</label>
                <input type="number" name="cca" value="{{ old('cca', $product->cca ?? 400) }}" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Voltage *</label>
                <input type="text" name="voltage" value="{{ old('voltage', $product->voltage ?? '12V') }}" class="form-control" required>
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-semibold">Dimensions</label>
                <input type="text" name="dimensions" value="{{ old('dimensions', $product->dimensions) }}" class="form-control" placeholder="238 x 129 x 227 mm">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Terminal Type</label>
                <input type="text" name="terminal_type" value="{{ old('terminal_type', $product->terminal_type) }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Warranty (Months) *</label>
                <input type="number" name="warranty_months" value="{{ old('warranty_months', $product->warranty_months ?? 18) }}" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Price Range</label>
                <input type="text" name="price_range" value="{{ old('price_range', $product->price_range) }}" class="form-control" placeholder="RM 250 - RM 300">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Status *</label>
                <select name="status" class="form-select">
                    <option value="active" @selected(old('status', $product->status ?? 'active') === 'active')>Active</option>
                    <option value="inactive" @selected(old('status', $product->status) === 'inactive')>Inactive</option>
                    <option value="out_of_stock" @selected(old('status', $product->status) === 'out_of_stock')>Out of Stock</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Product Image</label>
                <input type="file" name="image" accept="image/*" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Suitable Vehicles Summary</label>
                <input type="text" name="suitable_vehicles_text" value="{{ old('suitable_vehicles_text', $product->suitable_vehicles_text) }}" class="form-control">
            </div>

            <div class="col-12">
                <label class="form-label small fw-semibold">Short Description</label>
                <input type="text" name="short_description" value="{{ old('short_description', $product->short_description) }}" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Full Description</label>
                <textarea name="description" rows="4" class="form-control">{{ old('description', $product->description) }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Key Features (one per line)</label>
                <textarea name="features_text" rows="4" class="form-control">{{ old('features_text', is_array($product->features) ? implode("\n", $product->features) : '') }}</textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">SEO Meta Title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">SEO Meta Description</label>
                <input type="text" name="meta_description" value="{{ old('meta_description', $product->meta_description) }}" class="form-control">
            </div>

            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" @checked(old('is_featured', $product->is_featured))>
                    <label class="form-check-label fw-semibold" for="is_featured">Feature this product on Homepage</label>
                </div>
            </div>

            <div class="col-12 pt-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">Save Product</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection
