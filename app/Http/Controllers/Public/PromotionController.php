<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\View\View;

class PromotionController extends Controller
{
    public function index(): View
    {
        $promotions = Promotion::with('products.brand')->active()->paginate(9);

        return view('public.promotions.index', compact('promotions'));
    }

    public function show(Promotion $promotion): View
    {
        abort_if(! $promotion->is_active || $promotion->is_expired, 404);

        $promotion->load(['products.brand', 'products.batteryType']);

        return view('public.promotions.show', compact('promotion'));
    }
}
