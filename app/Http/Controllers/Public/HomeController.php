<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BatteryType;
use App\Models\ContactEnquiry;
use App\Models\HomepageBanner;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\Promotion;
use App\Models\Testimonial;
use App\Models\VehicleBrand;
use App\Models\WebsiteSetting;
use App\Models\WhatsappEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $banners = HomepageBanner::active()->get();
        $featuredProducts = Product::with(['brand', 'batteryType'])->featured()->take(6)->get();
        $activePromotions = Promotion::active()->take(3)->get();
        $testimonials = Testimonial::active()->where('is_featured', true)->take(6)->get();
        $vehicleBrands = VehicleBrand::active()->get();
        $brands = ProductBrand::active()->get();

        return view('public.home', compact(
            'banners',
            'featuredProducts',
            'activePromotions',
            'testimonials',
            'vehicleBrands',
            'brands'
        ));
    }

    public function contact(): View
    {
        $settings = Cache::remember('website_settings_map', 3600, fn () => WebsiteSetting::pluck('value', 'key')->toArray());

        return view('public.contact', compact('settings'));
    }

    public function storeContact(Request $request): RedirectResponse
    {
        if ($request->filled('website_url')) {
            return back()->with('success', 'Thank you for your message.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'mobile_number' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'subject' => ['required', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:2000'],
            'captcha_answer' => ['required', 'integer'],
            'captcha_expected' => ['required', 'integer'],
        ]);

        if ((int) $validated['captcha_answer'] !== (int) $validated['captcha_expected']) {
            return back()->withInput()->withErrors(['captcha_answer' => 'Incorrect security verification answer.']);
        }

        $enquiry = ContactEnquiry::create([
            'reference_number' => ContactEnquiry::generateReference(),
            'name' => $validated['name'],
            'mobile_number' => $validated['mobile_number'],
            'email' => $validated['email'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'new',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Thank you! Your message has been received (Ref: {$enquiry->reference_number}). Our team will respond shortly.");
    }

    public function trackWhatsapp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'context_type' => ['required', 'string', 'max:40'],
            'context_id' => ['nullable', 'integer'],
            'context_label' => ['nullable', 'string', 'max:255'],
            'page_url' => ['nullable', 'string', 'max:500'],
        ]);

        WhatsappEvent::create([
            'context_type' => $validated['context_type'],
            'context_id' => $validated['context_id'] ?? null,
            'context_label' => $validated['context_label'] ?? null,
            'page_url' => $validated['page_url'] ?? null,
            'utm_source' => session('attribution.utm_source'),
            'utm_campaign' => session('attribution.utm_campaign'),
            'ip_address' => $request->ip(),
        ]);

        return response()->json(['ok' => true]);
    }

    public function sitemap(): Response
    {
        $products = Product::active()->select('slug', 'updated_at')->get();
        $promotions = Promotion::active()->select('slug', 'updated_at')->get();

        $content = view('public.sitemap', compact('products', 'promotions'))->render();

        return response($content, 200)->header('Content-Type', 'application/xml');
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /login',
            'Sitemap: ' . url('/sitemap.xml'),
        ];

        return response(implode("\n", $lines), 200)->header('Content-Type', 'text/plain');
    }
}
