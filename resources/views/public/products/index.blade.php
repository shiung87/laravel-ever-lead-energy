@extends('layouts.public')

@section('meta_title', 'Car Battery Catalogue | Everlead Energy')
@section('meta_description', 'Browse our full range of Maintenance-Free, EFB Start-Stop, AGM, and Commercial car batteries.')

@section('content')
<section class="py-5 bg-navy border-bottom border-secondary border-opacity-25">
    <div class="container">
        <span class="text-amber text-uppercase small fw-bold">COMPLETE BATTERY RANGE</span>
        <h1 class="fw-bold text-white mt-1">Car Battery Catalogue</h1>
        <p class="text-secondary mb-0">Search by model, brand, battery technology, or vehicle category and enquire directly.</p>
    </div>
</section>

<section class="py-5 bg-dark-surface">
    <div class="container">
        <!-- Search & Filter Bar -->
        <form method="GET" action="{{ route('products.index') }}" class="el-card p-4 mb-5">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small text-light fw-semibold">Search Battery or Vehicle</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control el-form-control" placeholder="e.g. NS40ZL, AGM, Myvi, Vios...">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-light fw-semibold">Brand</label>
                    <select name="brand" class="form-select el-form-select">
                        <option value="">All Brands</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->slug }}" @selected(request('brand') === $brand->slug)>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-light fw-semibold">Battery Type</label>
                    <select name="type" class="form-select el-form-select">
                        <option value="">All Types</option>
                        @foreach($batteryTypes as $type)
                            <option value="{{ $type->slug }}" @selected(request('type') === $type->slug)>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-light fw-semibold">Vehicle Type</label>
                    <select name="vehicle_type" class="form-select el-form-select">
                        <option value="">All Vehicles</option>
                        @foreach($vehicleTypes as $vt)
                            <option value="{{ $vt }}" @selected(request('vehicle_type') === $vt)>{{ $vt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-amber flex-fill">Filter</button>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-light" title="Reset"><i class="fa-solid fa-rotate-left"></i></a>
                </div>
            </div>
        </form>

        @if($products->isEmpty())
            <div class="el-card p-5 text-center">
                <i class="fa-solid fa-car-battery text-amber fs-1 mb-3"></i>
                <h4 class="text-white fw-bold">No matching batteries found</h4>
                <p class="text-secondary mb-4">Try clearing your filters or ask our battery specialists for a direct recommendation.</p>
                <a href="{{ route('enquiry.create') }}" class="btn btn-amber">Contact Our Team</a>
            </div>
        @else
            <div class="row g-4">
                @foreach($products as $product)
                    <div class="col-md-6 col-lg-4">
                        <x-public.product-card :product="$product" />
                    </div>
                @endforeach
            </div>

            <div class="mt-5 d-flex justify-content-center">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</section>

<x-public.cta-section />
@endsection
