<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerLead;
use App\Models\DealerApplication;
use App\Models\Promotion;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $isSalesExec = $user->hasRole('sales-executive');

        $leadsQuery = CustomerLead::query();
        if ($isSalesExec) {
            $leadsQuery->where('assigned_to', $user->id);
        }

        $kpis = [
            'total_leads' => (clone $leadsQuery)->count(),
            'new_leads_today' => (clone $leadsQuery)->whereDate('created_at', today())->count(),
            'leads_this_month' => (clone $leadsQuery)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'pending_enquiries' => (clone $leadsQuery)->whereIn('status', ['new', 'contacted', 'follow_up'])->count(),
            'total_dealers' => DealerApplication::count(),
            'pending_dealers' => DealerApplication::whereIn('status', ['new', 'under_review', 'contacted'])->count(),
            'approved_dealers' => DealerApplication::where('status', 'approved')->count(),
            'active_promotions' => Promotion::active()->count(),
        ];

        // Monthly Lead Generation & Conversions (last 6 months)
        $months = collect(range(5, 0))->map(fn ($i) => now()->subMonths($i));
        $monthlyLabels = $months->map(fn ($m) => $m->format('M Y'))->toArray();
        $monthlyLeads = $months->map(function ($m) use ($leadsQuery) {
            return (clone $leadsQuery)->whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count();
        })->toArray();
        $monthlyConverted = $months->map(function ($m) use ($leadsQuery) {
            return (clone $leadsQuery)->where('status', 'converted')->whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count();
        })->toArray();

        // Leads by Source
        $leadsBySource = (clone $leadsQuery)
            ->select(DB::raw("COALESCE(NULLIF(utm_source, ''), 'direct') as source_name"), DB::raw('COUNT(*) as total'))
            ->groupBy('source_name')
            ->orderByDesc('total')
            ->limit(6)
            ->pluck('total', 'source_name')
            ->toArray();

        // Leads by Campaign
        $leadsByCampaign = (clone $leadsQuery)
            ->select(DB::raw("COALESCE(NULLIF(utm_campaign, ''), 'organic / none') as campaign_name"), DB::raw('COUNT(*) as total'))
            ->groupBy('campaign_name')
            ->orderByDesc('total')
            ->limit(6)
            ->pluck('total', 'campaign_name')
            ->toArray();

        // Enquiry Types
        $enquiryTypes = (clone $leadsQuery)
            ->select('enquiry_type', DB::raw('COUNT(*) as total'))
            ->groupBy('enquiry_type')
            ->pluck('total', 'enquiry_type')
            ->mapWithKeys(fn ($count, $type) => [CustomerLead::ENQUIRY_TYPES[$type] ?? $type => $count])
            ->toArray();

        // Dealer Applications by Status
        $dealerStatuses = DealerApplication::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->mapWithKeys(fn ($count, $status) => [DealerApplication::STATUSES[$status] ?? $status => $count])
            ->toArray();

        $recentLeads = (clone $leadsQuery)->with('assignee')->latest()->take(6)->get();
        $recentDealers = DealerApplication::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'kpis',
            'monthlyLabels',
            'monthlyLeads',
            'monthlyConverted',
            'leadsBySource',
            'leadsByCampaign',
            'enquiryTypes',
            'dealerStatuses',
            'recentLeads',
            'recentDealers'
        ));
    }
}
