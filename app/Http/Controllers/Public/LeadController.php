<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\LeadAdminNotificationMail;
use App\Mail\LeadConfirmationMail;
use App\Models\CustomerLead;
use App\Models\LeadActivity;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\WebsiteSetting;
use App\Services\WhatsappService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function create(Request $request): View
    {
        $products = Product::active()->orderBy('name')->get();
        $promotions = Promotion::active()->get();
        $selectedProduct = $request->integer('product_id') ? Product::find($request->integer('product_id')) : null;
        $selectedPromotion = $request->integer('promotion_id') ? Promotion::find($request->integer('promotion_id')) : null;

        return view('public.enquiry', compact('products', 'promotions', 'selectedProduct', 'selectedPromotion'));
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('website_url')) {
            return redirect()->route('enquiry.create')->with('success', 'Thank you for your enquiry.');
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:150'],
            'mobile_number' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'state' => ['required', 'string', 'max:80'],
            'city' => ['required', 'string', 'max:80'],
            'vehicle_brand' => ['nullable', 'string', 'max:80'],
            'vehicle_model' => ['nullable', 'string', 'max:100'],
            'vehicle_year' => ['nullable', 'string', 'max:10'],
            'battery_requirement' => ['nullable', 'string', 'max:150'],
            'product_id' => ['nullable', 'exists:products,id'],
            'promotion_id' => ['nullable', 'exists:promotions,id'],
            'preferred_service_location' => ['nullable', 'string', 'max:150'],
            'enquiry_type' => ['required', Rule::in(array_keys(CustomerLead::ENQUIRY_TYPES))],
            'remarks' => ['nullable', 'string', 'max:2000'],
            'marketing_consent' => ['nullable', 'boolean'],
            'captcha_answer' => ['required', 'integer'],
            'captcha_expected' => ['required', 'integer'],
        ]);

        if ((int) $validated['captcha_answer'] !== (int) $validated['captcha_expected']) {
            return back()->withInput()->withErrors(['captcha_answer' => 'Incorrect security verification answer. Please try again.']);
        }

        $batteryReq = $validated['battery_requirement'] ?? null;
        if (empty($batteryReq) && ! empty($validated['product_id'])) {
            $prod = Product::find($validated['product_id']);
            $batteryReq = $prod ? "{$prod->name} ({$prod->model_number})" : null;
        }

        $lead = CustomerLead::create([
            'reference_number' => CustomerLead::generateReference(),
            'full_name' => $validated['full_name'],
            'mobile_number' => $validated['mobile_number'],
            'email' => $validated['email'] ?? null,
            'state' => $validated['state'],
            'city' => $validated['city'],
            'vehicle_brand' => $validated['vehicle_brand'] ?? null,
            'vehicle_model' => $validated['vehicle_model'] ?? null,
            'vehicle_year' => $validated['vehicle_year'] ?? null,
            'battery_requirement' => $batteryReq,
            'product_id' => $validated['product_id'] ?? null,
            'promotion_id' => $validated['promotion_id'] ?? null,
            'preferred_service_location' => $validated['preferred_service_location'] ?? null,
            'enquiry_type' => $validated['enquiry_type'],
            'remarks' => $validated['remarks'] ?? null,
            'marketing_consent' => (bool) ($validated['marketing_consent'] ?? false),
            'status' => 'new',
            'utm_source' => session('attribution.utm_source'),
            'utm_medium' => session('attribution.utm_medium'),
            'utm_campaign' => session('attribution.utm_campaign'),
            'utm_content' => session('attribution.utm_content'),
            'referrer' => session('attribution.referrer'),
            'landing_page' => session('attribution.landing_page', $request->headers->get('referer')),
            'first_touch_source' => session('attribution.first_touch_source', 'direct'),
            'latest_touch_source' => session('attribution.latest_touch_source', 'direct'),
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

        LeadActivity::create([
            'customer_lead_id' => $lead->id,
            'action' => 'submitted',
            'new_status' => 'new',
            'notes' => 'Lead submitted via public enquiry form.',
        ]);

        try {
            if ($lead->email) {
                Mail::to($lead->email)->queue(new LeadConfirmationMail($lead));
            }
            $adminEmail = WebsiteSetting::getValue('sales_notification_email', config('mail.from.address'));
            if ($adminEmail) {
                Mail::to($adminEmail)->queue(new LeadAdminNotificationMail($lead));
            }
        } catch (\Throwable $e) {
            Log::warning('Lead email dispatch failed: ' . $e->getMessage());
        }

        return redirect()->route('enquiry.thank-you', ['ref' => $lead->reference_number]);
    }

    public function thankYou(Request $request): View
    {
        $reference = (string) $request->query('ref');
        $lead = CustomerLead::where('reference_number', $reference)->firstOrFail();

        $waMessage = "Hello Everlead Energy, I just submitted an enquiry (Ref: {$lead->reference_number}) for {$lead->vehicle_brand} {$lead->vehicle_model}. Name: {$lead->full_name}.";
        $whatsappUrl = WhatsappService::buildUrl($waMessage, 'sales');

        return view('public.enquiry-thank-you', compact('lead', 'whatsappUrl'));
    }
}
