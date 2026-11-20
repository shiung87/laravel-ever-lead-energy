@extends('layouts.public')

@section('meta_title', 'Find Your Car Battery | Interactive Battery Finder | Everlead Energy')
@section('meta_description', 'Select your vehicle brand, model, year, and engine capacity to find the exact compatible car battery.')

@section('content')
<section class="py-5 bg-navy border-bottom border-secondary border-opacity-25">
    <div class="container">
        <span class="text-amber text-uppercase small fw-bold">SMART COMPATIBILITY TOOL</span>
        <h1 class="fw-bold text-white mt-1">Find the Right Battery</h1>
        <p class="text-secondary mb-0">Select your vehicle specifications below to view admin-verified compatible car batteries.</p>
    </div>
</section>

<section class="py-5 bg-dark-surface">
    <div class="container">
        <form method="GET" action="{{ route('battery-finder') }}" class="el-card p-4 p-lg-5 mb-5">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-light fw-semibold">Vehicle Manufacturer <span class="text-danger">*</span></label>
                    <select name="vehicle_brand_id" id="finderBrand" class="form-select el-form-select" required>
                        <option value="">Select Manufacturer</option>
                        @foreach($vehicleBrands as $vb)
                            <option value="{{ $vb->id }}" @selected($selectedBrandId === $vb->id)>{{ $vb->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small text-light fw-semibold">Vehicle Model <span class="text-danger">*</span></label>
                    <select name="vehicle_model_id" id="finderModel" class="form-select el-form-select" required>
                        <option value="">Select Model</option>
                        @foreach($models as $vm)
                            <option value="{{ $vm->id }}" @selected($selectedModelId === $vm->id)>{{ $vm->name }} ({{ $vm->vehicle_type }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small text-light fw-semibold">Manufacturing Year</label>
                    <select name="year" class="form-select el-form-select">
                        <option value="">Any Year</option>
                        @for($y = date('Y') + 1; $y >= 2005; $y--)
                            <option value="{{ $y }}" @selected($selectedYear === $y)>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small text-light fw-semibold">Engine Capacity</label>
                    <input type="text" name="engine_capacity" value="{{ $selectedEngine }}" class="form-control el-form-control" placeholder="e.g. 1.5L">
                </div>

                <div class="col-md-2">
                    <label class="form-label small text-light fw-semibold">Battery Type</label>
                    <select name="battery_type_id" class="form-select el-form-select">
                        <option value="">Any Type</option>
                        @foreach($batteryTypes as $bt)
                            <option value="{{ $bt->id }}" @selected($selectedTypeId === $bt->id)>{{ $bt->code ?? $bt->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 pt-2 d-flex flex-wrap gap-3 align-items-center justify-content-between">
                    <button type="submit" class="btn btn-amber btn-lg px-5">
                        <i class="fa-solid fa-magnifying-glass me-2"></i>Find Compatible Batteries
                    </button>
                    <a href="{{ route('enquiry.create') }}" class="text-amber text-decoration-none small fw-semibold">
                        <i class="fa-solid fa-headset me-1"></i>Can't find your battery? Contact our team.
                    </a>
                </div>
            </div>
        </form>

        @if($searched)
            @if($compatibilities->isEmpty())
                <div class="el-card p-5 text-center">
                    <i class="fa-solid fa-circle-question text-amber fs-1 mb-3"></i>
                    <h4 class="text-white fw-bold">No exact match found in our compatibility database</h4>
                    <p class="text-secondary mb-4">Don't worry—our technical team can match your exact battery model in under 5 minutes.</p>
                    <a href="{{ route('enquiry.create') }}" class="btn btn-amber btn-lg">Can't find your battery? Contact our team</a>
                </div>
            @else
                <h3 class="fw-bold text-white mb-4">Recommended Batteries ({{ $compatibilities->count() }} found)</h3>
                <div class="row g-4">
                    @foreach($compatibilities as $compat)
                        <div class="col-md-6 col-lg-4">
                            <div class="position-relative h-100">
                                @if($compat->is_recommended)
                                    <span class="badge bg-success position-absolute top-0 start-50 translate-middle-x z-2 mt-n2 px-3 py-1">
                                        <i class="fa-solid fa-check me-1"></i>OEM Recommended ({{ $compat->engine_capacity }})
                                    </span>
                                @endif
                                <x-public.product-card :product="$compat->product" />
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const brandSelect = document.getElementById('finderBrand');
    const modelSelect = document.getElementById('finderModel');
    if (!brandSelect || !modelSelect) return;

    brandSelect.addEventListener('change', async () => {
        const brandId = brandSelect.value;
        modelSelect.innerHTML = '<option value="">Loading models...</option>';
        if (!brandId) {
            modelSelect.innerHTML = '<option value="">Select Model</option>';
            return;
        }
        try {
            const res = await fetch(`/api/vehicle-brands/${brandId}/models`);
            const models = await res.json();
            modelSelect.innerHTML = '<option value="">Select Model</option>';
            models.forEach((m) => {
                const opt = document.createElement('option');
                opt.value = m.id;
                opt.textContent = `${m.name} (${m.vehicle_type})`;
                modelSelect.appendChild(opt);
            });
        } catch (e) {
            modelSelect.innerHTML = '<option value="">Unable to load models</option>';
        }
    });
});
</script>
@endpush
