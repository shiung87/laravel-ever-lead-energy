<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\BatteryType;
use App\Models\ProductBrand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(): View
    {
        $brands = ProductBrand::withCount('products')->orderBy('sort_order')->get();
        $batteryTypes = BatteryType::withCount('products')->orderBy('sort_order')->get();

        return view('admin.brands.index', compact('brands', 'batteryTypes'));
    }

    public function storeBrand(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:500'],
            'website' => ['nullable', 'url', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $brand = ProductBrand::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(3),
            'description' => $validated['description'] ?? null,
            'website' => $validated['website'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        AuditLog::record('brand.created', "Created product brand {$brand->name}", $brand);

        return back()->with('success', 'Brand added.');
    }

    public function destroyBrand(ProductBrand $brand): RedirectResponse
    {
        $brand->delete();
        AuditLog::record('brand.deleted', "Deleted product brand {$brand->name}", $brand);

        return back()->with('success', 'Brand deleted.');
    }

    public function storeBatteryType(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'code' => ['nullable', 'string', 'max:30'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $type = BatteryType::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(3),
            'code' => $validated['code'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_active' => true,
        ]);

        AuditLog::record('battery_type.created', "Created battery type {$type->name}", $type);

        return back()->with('success', 'Battery type added.');
    }

    public function destroyBatteryType(BatteryType $batteryType): RedirectResponse
    {
        $batteryType->delete();
        AuditLog::record('battery_type.deleted', "Deleted battery type {$batteryType->name}", $batteryType);

        return back()->with('success', 'Battery type deleted.');
    }
}
