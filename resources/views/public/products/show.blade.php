@extends('layouts.public')

@section('meta_title', $product->meta_title ?: ($product->name . ' | Everlead Energy'))
@section('meta_description', $product->meta_description ?: $product->short_description)
@section('og_type', 'product')

@push('schema')
@php
    $productSchema = [
        '@context' => 'https://schema.org/',
        '@type' => 'Product',
        'name' => $product->name,
        'sku' => $product->sku ?? $product->model_number,
        'mpn' => $product->model_number,
        'description' => $product->short_description ?? $product->description,
        'brand' => [
            '@type' => 'Brand',
            'name' => $product->brand?->name ?? 'Everlead Energy',
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($productSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
<section class="py-5 bg-dark-surface">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-amber text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-amber text-decoration-none">Catalogue</a></li>
                <li class="breadcrumb-item active text-secondary" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row g-5 align-items-start">
            <div class="col-lg-5">
                <div class="el-card p-4 text-center">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid" style="max-height: 360px;">
                </div>
            </div>

            <div class="col-lg-7">
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <span class="badge bg-warning text-dark fw-bold">{{ $product->brand?->name }}</span>
                    <span class="badge bg-info bg-opacity-10 text-cyan border border-info border-opacity-25">{{ $product->batteryType?->name }}</span>
                    <span class="badge bg-dark border border-secondary font-monospace">{{ $product->model_number }}</span>
                </div>

                <h1 class="fw-bold text-white mb-3">{{ $product->name }}</h1>
                <p class="lead text-secondary mb-4">{{ $product->short_description }}</p>

                @if($product->price_range)
                    <div class="mb-4">
                        <span class="small text-secondary text-uppercase">Estimated Price Range</span>
                        <div class="h4 fw-bold text-amber mb-0">{{ $product->price_range }}</div>
                    </div>
                @endif

                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="spec-chip"><small class="text-secondary d-block">Capacity</small><strong class="text-white">{{ $product->capacity_ah }} Ah</strong></div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="spec-chip"><small class="text-secondary d-block">CCA</small><strong class="text-amber">{{ $product->cca }} CCA</strong></div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="spec-chip"><small class="text-secondary d-block">Voltage</small><strong class="text-white">{{ $product->voltage }}</strong></div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="spec-chip"><small class="text-secondary d-block">Warranty</small><strong class="text-cyan">{{ $product->warranty_label }}</strong></div>
                    </div>
                    @if($product->dimensions)
                    <div class="col-6">
                        <div class="spec-chip"><small class="text-secondary d-block">Dimensions</small><strong class="text-white">{{ $product->dimensions }}</strong></div>
                    </div>
                    @endif
                    @if($product->terminal_type)
                    <div class="col-6">
                        <div class="spec-chip"><small class="text-secondary d-block">Terminal Type</small><strong class="text-white">{{ $product->terminal_type }}</strong></div>
                    </div>
                    @endif
                </div>

                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="{{ route('enquiry.create', ['product_id' => $product->id]) }}" class="btn btn-amber btn-lg px-4">
                        <i class="fa-solid fa-paper-plane me-2"></i>Enquire About This Battery
                    </a>
                    <a href="{{ \App\Services\WhatsappService::buildUrl($product->whatsapp_text, 'sales') }}"
                       target="_blank"
                       rel="noopener"
                       data-wa-track
                       data-wa-type="product"
                       data-wa-id="{{ $product->id }}"
                       data-wa-label="{{ $product->name }}"
                       class="btn btn-whatsapp btn-lg px-4">
                        <i class="fa-brands fa-whatsapp me-2"></i>WhatsApp Enquiry
                    </a>
                </div>

                @if(! empty($product->features))
                    <div class="el-card p-4 mb-4">
                        <h6 class="fw-bold text-white mb-3">Key Technology Features</h6>
                        <div class="row g-2">
                            @foreach($product->features as $feat)
                                <div class="col-sm-6 small text-light"><i class="fa-solid fa-check text-amber me-2"></i>{{ $feat }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="el-card p-4">
                    <h6 class="fw-bold text-white mb-2">Description</h6>
                    <p class="small text-secondary mb-3">{{ $product->description }}</p>
                    @if($product->suitable_vehicles_text)
                        <h6 class="fw-bold text-white mb-1">Suitable Vehicles</h6>
                        <p class="small text-cyan mb-0">{{ $product->suitable_vehicles_text }}</p>
                    @endif
                </div>
            </div>
        </div>

        @if($relatedProducts->isNotEmpty())
            <div class="mt-5 pt-5 border-top border-secondary border-opacity-25">
                <h3 class="fw-bold text-white mb-4">Related Battery Solutions</h3>
                <div class="row g-4">
                    @foreach($relatedProducts as $rel)
                        <div class="col-md-4"><x-public.product-card :product="$rel" /></div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
