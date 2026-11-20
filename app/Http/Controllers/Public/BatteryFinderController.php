<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BatteryType;
use App\Models\VehicleBrand;
use App\Models\VehicleCompatibility;
use App\Models\VehicleModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BatteryFinderController extends Controller
{
    public function index(Request $request): View
    {
        $vehicleBrands = VehicleBrand::active()->get();
        $batteryTypes = BatteryType::active()->get();

        $selectedBrandId = $request->integer('vehicle_brand_id');
        $selectedModelId = $request->integer('vehicle_model_id');
        $selectedYear = $request->integer('year');
        $selectedEngine = trim((string) $request->query('engine_capacity'));
        $selectedTypeId = $request->integer('battery_type_id');

        $models = $selectedBrandId
            ? VehicleModel::active()->where('vehicle_brand_id', $selectedBrandId)->get()
            : collect();

        $compatibilities = collect();
        $searched = $request->hasAny(['vehicle_brand_id', 'vehicle_model_id']);

        if ($selectedModelId) {
            $query = VehicleCompatibility::with(['product.brand', 'product.batteryType', 'vehicleModel.brand'])
                ->where('vehicle_model_id', $selectedModelId)
                ->whereHas('product', fn ($q) => $q->where('status', 'active'));

            if ($selectedYear) {
                $query->where('year_from', '<=', $selectedYear)
                      ->where('year_to', '>=', $selectedYear);
            }

            if ($selectedEngine !== '') {
                $query->where('engine_capacity', 'like', "%{$selectedEngine}%");
            }

            if ($selectedTypeId) {
                $query->whereHas('product', fn ($q) => $q->where('battery_type_id', $selectedTypeId));
            }

            $compatibilities = $query->orderByDesc('is_recommended')->get();
        }

        return view('public.battery-finder', compact(
            'vehicleBrands',
            'batteryTypes',
            'models',
            'compatibilities',
            'searched',
            'selectedBrandId',
            'selectedModelId',
            'selectedYear',
            'selectedEngine',
            'selectedTypeId'
        ));
    }

    public function modelsByBrand(VehicleBrand $vehicleBrand): JsonResponse
    {
        $models = $vehicleBrand->models()
            ->where('is_active', true)
            ->select('id', 'name', 'vehicle_type', 'year_from', 'year_to')
            ->get();

        return response()->json($models);
    }
}
