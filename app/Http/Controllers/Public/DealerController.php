<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\DealerAdminNotificationMail;
use App\Mail\DealerApplicationAckMail;
use App\Models\DealerActivity;
use App\Models\DealerApplication;
use App\Models\DealerDocument;
use App\Models\WebsiteSetting;
use App\Services\WhatsappService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class DealerController extends Controller
{
    public function index(): View
    {
        return view('public.dealer-partnership');
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('website_url')) {
            return redirect()->route('dealer.index')->with('success', 'Application received.');
        }

        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:180'],
            'registration_number' => ['required', 'string', 'max:80'],
            'contact_person' => ['required', 'string', 'max:120'],
            'mobile_number' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:150'],
            'business_address' => ['required', 'string', 'max:500'],
            'state' => ['required', 'string', 'max:80'],
            'city' => ['required', 'string', 'max:80'],
            'business_type' => ['required', 'string', 'max:80'],
            'years_in_operation' => ['required', 'integer', 'min:0', 'max:100'],
            'existing_brands' => ['nullable', 'string', 'max:255'],
            'estimated_monthly_volume' => ['required', 'string', 'max:80'],
            'number_of_outlets' => ['required', 'integer', 'min:1', 'max:500'],
            'partnership_type' => ['required', 'string', 'max:80'],
            'remarks' => ['nullable', 'string', 'max:2000'],
            'documents' => ['nullable', 'array', 'max:5'],
            'documents.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'consent' => ['accepted'],
            'captcha_answer' => ['required', 'integer'],
            'captcha_expected' => ['required', 'integer'],
        ]);

        if ((int) $validated['captcha_answer'] !== (int) $validated['captcha_expected']) {
            return back()->withInput()->withErrors(['captcha_answer' => 'Incorrect security verification answer.']);
        }

        $application = DealerApplication::create([
            'reference_number' => DealerApplication::generateReference(),
            'business_name' => $validated['business_name'],
            'registration_number' => $validated['registration_number'],
            'contact_person' => $validated['contact_person'],
            'mobile_number' => $validated['mobile_number'],
            'email' => $validated['email'],
            'business_address' => $validated['business_address'],
            'state' => $validated['state'],
            'city' => $validated['city'],
            'business_type' => $validated['business_type'],
            'years_in_operation' => $validated['years_in_operation'],
            'existing_brands' => $validated['existing_brands'] ?? null,
            'estimated_monthly_volume' => $validated['estimated_monthly_volume'],
            'number_of_outlets' => $validated['number_of_outlets'],
            'partnership_type' => $validated['partnership_type'],
            'remarks' => $validated['remarks'] ?? null,
            'consent' => true,
            'status' => 'new',
            'utm_source' => session('attribution.utm_source'),
            'utm_medium' => session('attribution.utm_medium'),
            'utm_campaign' => session('attribution.utm_campaign'),
            'landing_page' => session('attribution.landing_page', $request->headers->get('referer')),
        ]);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('dealer-documents/' . $application->id, 'local');
                DealerDocument::create([
                    'dealer_application_id' => $application->id,
                    'original_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'mime_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        DealerActivity::create([
            'dealer_application_id' => $application->id,
            'action' => 'submitted',
            'new_status' => 'new',
            'notes' => 'Dealer application submitted online.',
        ]);

        try {
            Mail::to($application->email)->queue(new DealerApplicationAckMail($application));
            $dealerAdminEmail = WebsiteSetting::getValue('dealer_notification_email', config('mail.from.address'));
            if ($dealerAdminEmail) {
                Mail::to($dealerAdminEmail)->queue(new DealerAdminNotificationMail($application));
            }
        } catch (\Throwable $e) {
            Log::warning('Dealer email dispatch failed: ' . $e->getMessage());
        }

        return redirect()->route('dealer.thank-you', ['ref' => $application->reference_number]);
    }

    public function thankYou(Request $request): View
    {
        $reference = (string) $request->query('ref');
        $application = DealerApplication::where('reference_number', $reference)->firstOrFail();

        $waMessage = "Hello Everlead Energy Partnership Team, I just submitted an Authorized Dealer application (Ref: {$application->reference_number}) for {$application->business_name}.";
        $whatsappUrl = WhatsappService::buildUrl($waMessage, 'dealer');

        return view('public.dealer-thank-you', compact('application', 'whatsappUrl'));
    }
}
