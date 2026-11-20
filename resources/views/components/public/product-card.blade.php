@props(['product'])

<div class="el-card h-100 p-3 d-flex flex-column">
    <div class="product-card-img-wrap mb-3 position-relative">
        <span class="badge bg-dark border border-secondary border-opacity-50 position-absolute top-0 start-0 m-2">
            {{ $product->brand?->name }}
        </span>
        @if($product->is_featured)
            <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">
                <i class="fa-solid fa-bolt me-1"></i>Popular
            </span>
        @endif
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" class="img-fluid">
    </div>

    <div class="mb-2 d-flex align-items-center justify-content-between gap-2">
        <span class="badge bg-info bg-opacity-10 text-cyan border border-info border-opacity-25">{{ $product->batteryType?->code ?? $product->batteryType?->name }}</span>
        <small class="text-secondary font-monospace">{{ $product->model_number }}</small>
    </div>

    <h5 class="fw-bold text-white mb-2">
        <a href="{{ route('products.show', $product->slug) }}" class="text-white text-decoration-none">
            {{ $product->name }}
        </a>
    </h5>

    <p class="small text-secondary mb-3 flex-grow-1">{{ Str::limit($product->short_description, 95) }}</p>

    <div class="row g-2 mb-3">
        <div class="col-4">
            <div class="spec-chip text-center">
                <div class="text-secondary" style="font-size: 0.7rem;">Capacity</div>
                <div class="fw-bold text-white">{{ $product->capacity_ah }}Ah</div>
            </div>
        </div>
        <div class="col-4">
            <div class="spec-chip text-center">
                <div class="text-secondary" style="font-size: 0.7rem;">CCA</div>
                <div class="fw-bold text-amber">{{ $product->cca }}</div>
            </div>
        </div>
        <div class="col-4">
            <div class="spec-chip text-center">
                <div class="text-secondary" style="font-size: 0.7rem;">Warranty</div>
                <div class="fw-bold text-cyan">{{ $product->warranty_months }}m</div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-auto">
        <a href="{{ route('enquiry.create', ['product_id' => $product->id]) }}" class="btn btn-amber btn-sm flex-fill">
            Enquire Now
        </a>
        <a href="{{ \App\Services\WhatsappService::buildUrl($product->whatsapp_text, 'sales') }}"
           target="_blank"
           rel="noopener"
           data-wa-track
           data-wa-type="product"
           data-wa-id="{{ $product->id }}"
           data-wa-label="{{ $product->name }}"
           class="btn btn-whatsapp btn-sm"
           title="WhatsApp Enquiry">
            <i class="fa-brands fa-whatsapp"></i>
        </a>
        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-light btn-sm" title="View Specs">
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</div>
