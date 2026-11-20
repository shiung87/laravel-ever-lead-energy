<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PromotionController extends Controller
{
    public function index(): View
    {
        $promotions = Promotion::withCount('products')->orderBy('sort_order')->latest()->paginate(15);

        return view('admin.promotions.index', compact('promotions'));
    }

    public function create(): View
    {
        $products = Product::active()->orderBy('name')->get();

        return view('admin.promotions.form', ['promotion' => new Promotion(), 'products' => $products]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePromotion($request);
        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(4);

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->store('promotions', 'public');
        }

        $promotion = Promotion::create($data);
        $promotion->products()->sync($request->input('product_ids', []));
        AuditLog::record('promotion.created', "Created promotion {$promotion->title}", $promotion);

        return redirect()->route('admin.promotions.index')->with('success', 'Promotion created.');
    }

    public function edit(Promotion $promotion): View
    {
        $products = Product::active()->orderBy('name')->get();
        $promotion->load('products');

        return view('admin.promotions.form', compact('promotion', 'products'));
    }

    public function update(Request $request, Promotion $promotion): RedirectResponse
    {
        $data = $this->validatePromotion($request);

        if ($request->hasFile('banner_image')) {
            if ($promotion->banner_image && Storage::disk('public')->exists($promotion->banner_image)) {
                Storage::disk('public')->delete($promotion->banner_image);
            }
            $data['banner_image'] = $request->file('banner_image')->store('promotions', 'public');
        }

        $promotion->update($data);
        $promotion->products()->sync($request->input('product_ids', []));
        AuditLog::record('promotion.updated', "Updated promotion {$promotion->title}", $promotion);

        return redirect()->route('admin.promotions.index')->with('success', 'Promotion updated.');
    }

    public function destroy(Promotion $promotion): RedirectResponse
    {
        $promotion->delete();
        AuditLog::record('promotion.deleted', "Deleted promotion {$promotion->title}", $promotion);

        return redirect()->route('admin.promotions.index')->with('success', 'Promotion deleted.');
    }

    private function validatePromotion(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'badge_text' => ['nullable', 'string', 'max:60'],
            'promo_type' => ['required', 'in:discount,bundle,free_installation,seasonal'],
            'discount_label' => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string'],
            'terms_conditions' => ['nullable', 'string'],
            'banner_image' => ['nullable', 'image', 'max:4096'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'cta_text' => ['required', 'string', 'max:80'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        return $validated;
    }
}
