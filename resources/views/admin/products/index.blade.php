@extends('layouts.admin')

@section('title', 'Products')
@section('page_title', 'Battery Product Catalogue')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <form method="GET" action="{{ route('admin.products.index') }}" class="d-flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search name or model...">
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
    </form>
    <a href="{{ route('admin.products.create') }}" class="btn btn-warning btn-sm fw-bold">
        <i class="fa-solid fa-plus me-1"></i>Add Battery Product
    </a>
</div>

<div class="card kpi-card p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
            <tr>
                <th>Product</th>
                <th>Brand / Type</th>
                <th>Specs</th>
                <th>Warranty</th>
                <th>Featured</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $product->image_url }}" alt="" style="width: 44px; height: 44px; object-fit: contain;">
                            <div>
                                <div class="fw-semibold">{{ $product->name }}</div>
                                <div class="small text-muted font-monospace">{{ $product->model_number }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="small">{{ $product->brand?->name }}<div class="text-muted">{{ $product->batteryType?->code }}</div></td>
                    <td class="small">{{ $product->capacity_ah }}Ah / {{ $product->cca }} CCA</td>
                    <td class="small">{{ $product->warranty_months }} months</td>
                    <td>{!! $product->is_featured ? '<span class="badge bg-warning text-dark">Featured</span>' : '-' !!}</td>
                    <td><span class="badge {{ $product->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($product->status) }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="d-inline" onsubmit="return confirm('Delete this product?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $products->links('pagination::bootstrap-5') }}
</div>
@endsection
