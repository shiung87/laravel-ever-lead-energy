<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\BatteryType;
use App\Models\Product;
use App\Models\ProductBrand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['brand', 'batteryType']);

        if ($search = trim((string) $request->query('search'))) {
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('model_number', 'like', "%{$search}%"));
        }

        $products = $query->orderBy('sort_order')->latest()->paginate(15)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $brands = ProductBrand::orderBy('name')->get();
        $batteryTypes = BatteryType::orderBy('name')->get();

        return view('admin.products.form', ['product' => new Product(), 'brands' => $brands, 'batteryTypes' => $batteryTypes]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateProduct($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['features'] = $this->parseFeatures($request->input('features_text'));

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);
        AuditLog::record('product.created', "Created product {$product->name}", $product);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        $brands = ProductBrand::orderBy('name')->get();
        $batteryTypes = BatteryType::orderBy('name')->get();

        return view('admin.products.form', compact('product', 'brands', 'batteryTypes'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validateProduct($request, $product->id);
        $data['features'] = $this->parseFeatures($request->input('features_text'));

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        AuditLog::record('product.updated', "Updated product {$product->name}", $product);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;
        $product->delete();
        AuditLog::record('product.deleted', "Deleted product {$name}", $product);

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    private function validateProduct(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'product_brand_id' => ['required', 'exists:product_brands,id'],
            'battery_type_id' => ['required', 'exists:battery_types,id'],
            'name' => ['required', 'string', 'max:180'],
            'model_number' => ['required', 'string', 'max:80'],
            'sku' => ['nullable', 'string', 'max:80', 'unique:products,sku,' . ($ignoreId ?? 'NULL')],
            'image' => ['nullable', 'image', 'max:4096'],
            'capacity_ah' => ['required', 'integer', 'min:10', 'max:300'],
            'cca' => ['required', 'integer', 'min:100', 'max:2000'],
            'voltage' => ['required', 'string', 'max:20'],
            'dimensions' => ['nullable', 'string', 'max:100'],
            'terminal_type' => ['nullable', 'string', 'max:50'],
            'warranty_months' => ['required', 'integer', 'min:0', 'max:60'],
            'warranty_label' => ['nullable', 'string', 'max:60'],
            'price_range' => ['nullable', 'string', 'max:80'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'suitable_vehicles_text' => ['nullable', 'string', 'max:1000'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['required', 'in:active,inactive,out_of_stock'],
            'meta_title' => ['nullable', 'string', 'max:180'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        if (empty($validated['warranty_label'])) {
            $validated['warranty_label'] = $validated['warranty_months'] . ' Months Warranty';
        }

        return $validated;
    }

    private function parseFeatures(?string $text): array
    {
        if (! $text) {
            return [];
        }

        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $text))));
    }

    private function uniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $base = $slug;
        $i = 1;
        while (Product::withTrashed()->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
