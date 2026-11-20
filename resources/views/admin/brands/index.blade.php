@extends('layouts.admin')

@section('title', 'Brands & Battery Types')
@section('page_title', 'Brand & Battery Type Management')

@section('content')
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card kpi-card p-4 mb-4">
            <h6 class="fw-bold mb-3">Add Product Brand</h6>
            <form method="POST" action="{{ route('admin.brands.store') }}" class="row g-2">
                @csrf
                <div class="col-md-6"><input type="text" name="name" class="form-control form-control-sm" placeholder="Brand Name" required></div>
                <div class="col-md-4"><input type="number" name="sort_order" class="form-control form-control-sm" placeholder="Order" value="0"></div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm w-100">Add</button></div>
                <div class="col-12"><input type="text" name="description" class="form-control form-control-sm" placeholder="Short description"></div>
            </form>
        </div>

        <div class="card kpi-card p-4">
            <h6 class="fw-bold mb-3">Product Brands</h6>
            <table class="table table-sm align-middle mb-0">
                <thead><tr><th>Name</th><th>Products</th><th></th></tr></thead>
                <tbody>
                @foreach($brands as $brand)
                    <tr>
                        <td class="fw-semibold">{{ $brand->name }}</td>
                        <td>{{ $brand->products_count }}</td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}" onsubmit="return confirm('Delete brand?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card kpi-card p-4 mb-4">
            <h6 class="fw-bold mb-3">Add Battery Type</h6>
            <form method="POST" action="{{ route('admin.battery-types.store') }}" class="row g-2">
                @csrf
                <div class="col-md-6"><input type="text" name="name" class="form-control form-control-sm" placeholder="Type Name (e.g. AGM)" required></div>
                <div class="col-md-4"><input type="text" name="code" class="form-control form-control-sm" placeholder="Code (AGM)"></div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm w-100">Add</button></div>
                <div class="col-12"><input type="text" name="description" class="form-control form-control-sm" placeholder="Description"></div>
            </form>
        </div>

        <div class="card kpi-card p-4">
            <h6 class="fw-bold mb-3">Battery Types</h6>
            <table class="table table-sm align-middle mb-0">
                <thead><tr><th>Name</th><th>Code</th><th>Products</th><th></th></tr></thead>
                <tbody>
                @foreach($batteryTypes as $bt)
                    <tr>
                        <td class="fw-semibold">{{ $bt->name }}</td>
                        <td><span class="badge bg-secondary">{{ $bt->code }}</span></td>
                        <td>{{ $bt->products_count }}</td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('admin.battery-types.destroy', $bt) }}" onsubmit="return confirm('Delete type?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
