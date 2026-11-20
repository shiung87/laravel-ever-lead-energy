<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BatteryType;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['brand', 'batteryType'])->active();

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('model_number', 'like', "%{$search}%")
                  ->orWhere('suitable_vehicles_text', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if ($brandSlug = $request->query('brand')) {
            $query->whereHas('brand', fn ($q) => $q->where('slug', $brandSlug));
        }

        if ($typeSlug = $request->query('type')) {
            $query->whereHas('batteryType', fn ($q) => $q->where('slug', $typeSlug));
        }

        if ($vehicleType = $request->query('vehicle_type')) {
            $query->whereHas('compatibilities.vehicleModel', fn ($q) => $q->where('vehicle_type', $vehicleType));
        }

        $products = $query->orderBy('sort_order')->orderByDesc('is_featured')->paginate(9)->withQueryString();
        $brands = ProductBrand::active()->get();
        $batteryTypes = BatteryType::active()->get();
        $vehicleTypes = VehicleModel::active()->distinct()->pluck('vehicle_type');

        return view('public.products.index', compact('products', 'brands', 'batteryTypes', 'vehicleTypes'));
    }

    public function show(Product $product): View
    {
        abort_unless($product->status === 'active', 404);

        $product->load(['brand', 'batteryType', 'compatibilities.vehicleModel.brand']);

        $relatedProducts = Product::with(['brand', 'batteryType'])
            ->active()
            ->where('id', '!=', $product->id)
            ->where('battery_type_id', $product->battery_type_id)
            ->take(3)
            ->get();

        return view('public.products.show', compact('product', 'relatedProducts'));
    }
}
