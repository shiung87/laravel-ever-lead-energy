@extends('layouts.admin')

@section('title', 'Vehicle Compatibility')
@section('page_title', 'Vehicle Compatibility Configuration')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card kpi-card p-4 h-100">
            <h6 class="fw-bold mb-3">1. Add Vehicle Manufacturer</h6>
            <form method="POST" action="{{ route('admin.vehicles.brands.store') }}" class="d-flex flex-column gap-2">
                @csrf
                <input type="text" name="name" class="form-control form-control-sm" placeholder="e.g. Subaru" required>
                <input type="text" name="country" class="form-control form-control-sm" placeholder="Country (e.g. Japan)">
                <button type="submit" class="btn btn-primary btn-sm">Add Manufacturer</button>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card kpi-card p-4 h-100">
            <h6 class="fw-bold mb-3">2. Add Vehicle Model</h6>
            <form method="POST" action="{{ route('admin.vehicles.models.store') }}" class="d-flex flex-column gap-2">
                @csrf
                <select name="vehicle_brand_id" class="form-select form-select-sm" required>
                    <option value="">Select Manufacturer</option>
                    @foreach($vehicleBrands as $vb)
                        <option value="{{ $vb->id }}">{{ $vb->name }}</option>
                    @endforeach
                </select>
                <input type="text" name="name" class="form-control form-control-sm" placeholder="Model Name (e.g. Forester)" required>
                <select name="vehicle_type" class="form-select form-select-sm">
                    <option value="Sedan">Sedan</option>
                    <option value="SUV">SUV</option>
                    <option value="Hatchback">Hatchback</option>
                    <option value="MPV">MPV</option>
                    <option value="Pickup">Pickup</option>
                    <option value="Commercial">Commercial</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Add Model</button>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card kpi-card p-4 h-100">
            <h6 class="fw-bold mb-3">3. Link Battery Compatibility</h6>
            <form method="POST" action="{{ route('admin.vehicles.compatibilities.store') }}" class="d-flex flex-column gap-2">
                @csrf
                <select name="vehicle_model_id" class="form-select form-select-sm" required>
                    <option value="">Select Vehicle Model</option>
                    @foreach($vehicleModels as $vm)
                        <option value="{{ $vm->id }}">{{ $vm->brand?->name }} {{ $vm->name }}</option>
                    @endforeach
                </select>
                <select name="product_id" class="form-select form-select-sm" required>
                    <option value="">Select Compatible Battery</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->model_number }})</option>
                    @endforeach
                </select>
                <div class="row g-2">
                    <div class="col-6"><input type="number" name="year_from" value="2015" class="form-control form-control-sm" placeholder="Year From"></div>
                    <div class="col-6"><input type="number" name="year_to" value="2026" class="form-control form-control-sm" placeholder="Year To"></div>
                </div>
                <input type="text" name="engine_capacity" class="form-control form-control-sm" placeholder="Engine (e.g. 2.0L)">
                <button type="submit" class="btn btn-warning btn-sm fw-bold">Add Compatibility Rule</button>
            </form>
        </div>
    </div>
</div>

<div class="card kpi-card p-4">
    <h6 class="fw-bold mb-3">Configured Compatibility Rules</h6>
    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle">
            <thead><tr><th>Vehicle</th><th>Years</th><th>Engine</th><th>Compatible Battery</th><th></th></tr></thead>
            <tbody>
            @foreach($compatibilities as $c)
                <tr>
                    <td class="fw-semibold">{{ $c->vehicleModel?->brand?->name }} {{ $c->vehicleModel?->name }}</td>
                    <td class="small">{{ $c->year_from }} - {{ $c->year_to }}</td>
                    <td class="small">{{ $c->engine_capacity ?: '-' }}</td>
                    <td>{{ $c->product?->name }} <span class="text-muted small">({{ $c->product?->model_number }})</span></td>
                    <td class="text-end">
                        <form method="POST" action="{{ route('admin.vehicles.compatibilities.destroy', $c) }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $compatibilities->links('pagination::bootstrap-5') }}
</div>
@endsection
