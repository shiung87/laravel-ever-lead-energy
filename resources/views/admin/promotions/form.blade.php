@extends('layouts.admin')

@section('title', $promotion->exists ? 'Edit Promotion' : 'New Promotion')
@section('page_title', $promotion->exists ? 'Edit Campaign: ' . $promotion->title : 'Create Promotional Campaign')

@section('content')
<div class="card kpi-card p-4">
    <form method="POST" action="{{ $promotion->exists ? route('admin.promotions.update', $promotion) : route('admin.promotions.store') }}" enctype="multipart/form-data">
        @csrf
        @if($promotion->exists) @method('PUT') @endif

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Campaign Title *</label>
                <input type="text" name="title" value="{{ old('title', $promotion->title) }}" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Promo Type *</label>
                <select name="promo_type" class="form-select">
                    <option value="discount" @selected(old('promo_type', $promotion->promo_type) === 'discount')>Discount</option>
                    <option value="free_installation" @selected(old('promo_type', $promotion->promo_type) === 'free_installation')>Free Installation</option>
                    <option value="bundle" @selected(old('promo_type', $promotion->promo_type) === 'bundle')>Bundle Offer</option>
                    <option value="seasonal" @selected(old('promo_type', $promotion->promo_type) === 'seasonal')>Seasonal</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Discount Label</label>
                <input type="text" name="discount_label" value="{{ old('discount_label', $promotion->discount_label) }}" class="form-control" placeholder="e.g. UP TO RM 60 OFF">
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Subtitle</label>
                <input type="text" name="subtitle" value="{{ old('subtitle', $promotion->subtitle) }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Badge Text</label>
                <input type="text" name="badge_text" value="{{ old('badge_text', $promotion->badge_text) }}" class="form-control" placeholder="LIMITED TIME">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">CTA Button Text *</label>
                <input type="text" name="cta_text" value="{{ old('cta_text', $promotion->cta_text ?? 'Claim This Offer') }}" class="form-control" required>
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-semibold">Start Date & Time</label>
                <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $promotion->starts_at?->format('Y-m-d\TH:i')) }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">End Date & Time</label>
                <input type="datetime-local" name="ends_at" value="{{ old('ends_at', $promotion->ends_at?->format('Y-m-d\TH:i')) }}" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Banner Image</label>
                <input type="file" name="banner_image" accept="image/*" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Campaign Description</label>
                <textarea name="description" rows="4" class="form-control">{{ old('description', $promotion->description) }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Terms & Conditions</label>
                <textarea name="terms_conditions" rows="4" class="form-control">{{ old('terms_conditions', $promotion->terms_conditions) }}</textarea>
            </div>

            <div class="col-12">
                <label class="form-label small fw-semibold">Applicable Products</label>
                <select name="product_ids[]" multiple class="form-select" size="4">
                    @php $selectedIds = $promotion->products->pluck('id')->toArray(); @endphp
                    @foreach($products as $prod)
                        <option value="{{ $prod->id }}" @selected(in_array($prod->id, old('product_ids', $selectedIds)))>{{ $prod->name }} ({{ $prod->model_number }})</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 d-flex gap-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $promotion->exists ? $promotion->is_active : true))>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" @checked(old('is_featured', $promotion->is_featured))>
                    <label class="form-check-label" for="is_featured">Featured on Homepage</label>
                </div>
            </div>

            <div class="col-12 pt-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">Save Campaign</button>
                <a href="{{ route('admin.promotions.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection
