<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Product;
use App\Models\VehicleBrand;
use App\Models\VehicleCompatibility;
use App\Models\VehicleModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VehicleCompatibilityController extends Controller
{
    public function index(): View
    {
        $vehicleBrands = VehicleBrand::withCount('models')->orderBy('sort_order')->get();
        $vehicleModels = VehicleModel::with('brand')->orderBy('vehicle_brand_id')->orderBy('name')->get();
        $products = Product::active()->orderBy('name')->get();
        $compatibilities = VehicleCompatibility::with(['vehicleModel.brand', 'product.brand'])
            ->latest()
            ->paginate(20);

        return view('admin.vehicles.index', compact('vehicleBrands', 'vehicleModels', 'products', 'compatibilities'));
    }

    public function storeBrand(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:80'],
        ]);

        $brand = VehicleBrand::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'country' => $validated['country'] ?? null,
            'is_active' => true,
        ]);

        AuditLog::record('vehicle_brand.created', "Added vehicle brand {$brand->name}", $brand);

        return back()->with('success', 'Vehicle manufacturer added.');
    }

    public function storeModel(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vehicle_brand_id' => ['required', 'exists:vehicle_brands,id'],
            'name' => ['required', 'string', 'max:120'],
            'vehicle_type' => ['required', 'string', 'max:50'],
            'year_from' => ['nullable', 'integer', 'min:1980', 'max:2035'],
            'year_to' => ['nullable', 'integer', 'min:1980', 'max:2035'],
        ]);

        $model = VehicleModel::create([
            'vehicle_brand_id' => $validated['vehicle_brand_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'vehicle_type' => $validated['vehicle_type'],
            'year_from' => $validated['year_from'] ?? 2010,
            'year_to' => $validated['year_to'] ?? 2026,
            'is_active' => true,
        ]);

        AuditLog::record('vehicle_model.created', "Added vehicle model {$model->name}", $model);

        return back()->with('success', 'Vehicle model added.');
    }

    public function storeCompatibility(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vehicle_model_id' => ['required', 'exists:vehicle_models,id'],
            'product_id' => ['required', 'exists:products,id'],
            'year_from' => ['required', 'integer', 'min:1980', 'max:2035'],
            'year_to' => ['required', 'integer', 'min:1980', 'max:2035'],
            'engine_capacity' => ['nullable', 'string', 'max:50'],
            'fuel_type' => ['nullable', 'string', 'max:40'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $compat = VehicleCompatibility::create(array_merge($validated, [
            'is_recommended' => $request->boolean('is_recommended', true),
        ]));

        AuditLog::record('compatibility.created', 'Added vehicle-battery compatibility rule.', $compat);

        return back()->with('success', 'Compatibility rule added.');
    }

    public function destroyCompatibility(VehicleCompatibility $compatibility): RedirectResponse
    {
        $compatibility->delete();
        AuditLog::record('compatibility.deleted', 'Deleted vehicle compatibility rule.', $compatibility);

        return back()->with('success', 'Compatibility rule removed.');
    }
}
