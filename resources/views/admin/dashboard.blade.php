@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Executive Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card kpi-card p-3">
            <div class="text-muted small fw-semibold">Total Customer Leads</div>
            <div class="h3 fw-bold text-primary mb-0 mt-1">{{ $kpis['total_leads'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card kpi-card p-3">
            <div class="text-muted small fw-semibold">New Leads Today</div>
            <div class="h3 fw-bold text-success mb-0 mt-1">{{ $kpis['new_leads_today'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card kpi-card p-3">
            <div class="text-muted small fw-semibold">Leads This Month</div>
            <div class="h3 fw-bold text-info mb-0 mt-1">{{ $kpis['leads_this_month'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card kpi-card p-3">
            <div class="text-muted small fw-semibold">Pending Enquiries</div>
            <div class="h3 fw-bold text-warning mb-0 mt-1">{{ $kpis['pending_enquiries'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card kpi-card p-3">
            <div class="text-muted small fw-semibold">Total Dealer Applications</div>
            <div class="h3 fw-bold text-dark mb-0 mt-1">{{ $kpis['total_dealers'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card kpi-card p-3">
            <div class="text-muted small fw-semibold">Pending Dealers</div>
            <div class="h3 fw-bold text-warning mb-0 mt-1">{{ $kpis['pending_dealers'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card kpi-card p-3">
            <div class="text-muted small fw-semibold">Approved Dealers</div>
            <div class="h3 fw-bold text-success mb-0 mt-1">{{ $kpis['approved_dealers'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card kpi-card p-3">
            <div class="text-muted small fw-semibold">Active Promotions</div>
            <div class="h3 fw-bold text-danger mb-0 mt-1">{{ $kpis['active_promotions'] }}</div>
        </div>
    </div>
</div>

<!-- Charts Row 1 -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card kpi-card p-4 h-100">
            <h6 class="fw-bold mb-3">Monthly Lead Generation & Conversion Trend</h6>
            <canvas id="monthlyLeadsChart" height="110"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card kpi-card p-4 h-100">
            <h6 class="fw-bold mb-3">Leads by Source</h6>
            <canvas id="leadsSourceChart" height="220"></canvas>
        </div>
    </div>
</div>

<!-- Charts Row 2 -->
<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card kpi-card p-4 h-100">
            <h6 class="fw-bold mb-3">Enquiry Types</h6>
            <canvas id="enquiryTypeChart" height="210"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card kpi-card p-4 h-100">
            <h6 class="fw-bold mb-3">Leads by Campaign</h6>
            <canvas id="leadsCampaignChart" height="210"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card kpi-card p-4 h-100">
            <h6 class="fw-bold mb-3">Dealer Applications by Status</h6>
            <canvas id="dealerStatusChart" height="210"></canvas>
        </div>
    </div>
</div>

<!-- Recent Tables -->
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card kpi-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Recent Customer Leads</h6>
                <a href="{{ route('admin.leads.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead><tr><th>Ref</th><th>Customer</th><th>Vehicle</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($recentLeads as $lead)
                        <tr>
                            <td><a href="{{ route('admin.leads.show', $lead) }}" class="fw-semibold text-decoration-none">{{ $lead->reference_number }}</a></td>
                            <td>{{ $lead->full_name }}<div class="text-muted small">{{ $lead->mobile_number }}</div></td>
                            <td class="small">{{ trim("{$lead->vehicle_brand} {$lead->vehicle_model}") ?: '-' }}</td>
                            <td><span class="badge {{ $lead->status_badge_class }}">{{ $lead->status_label }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No leads yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card kpi-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Recent Dealer Applications</h6>
                <a href="{{ route('admin.dealers.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead><tr><th>Business</th><th>Location</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($recentDealers as $dealer)
                        <tr>
                            <td><a href="{{ route('admin.dealers.show', $dealer) }}" class="fw-semibold text-decoration-none">{{ $dealer->business_name }}</a></td>
                            <td class="small">{{ $dealer->city }}, {{ $dealer->state }}</td>
                            <td><span class="badge {{ $dealer->status_badge_class }}">{{ $dealer->status_label }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">No applications yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const palette = ['#f59e0b', '#0ea5e9', '#10b981', '#6366f1', '#ef4444', '#8b5cf6'];

    new window.Chart(document.getElementById('monthlyLeadsChart'), {
        type: 'line',
        data: {
            labels: @json($monthlyLabels),
            datasets: [
                {
                    label: 'Total Leads',
                    data: @json($monthlyLeads),
                    borderColor: '#0ea5e9',
                    backgroundColor: 'rgba(14, 165, 233, 0.15)',
                    fill: true,
                    tension: 0.35
                },
                {
                    label: 'Converted',
                    data: @json($monthlyConverted),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.12)',
                    fill: true,
                    tension: 0.35
                }
            ]
        }
    });

    new window.Chart(document.getElementById('leadsSourceChart'), {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($leadsBySource)),
            datasets: [{ data: @json(array_values($leadsBySource)), backgroundColor: palette }]
        }
    });

    new window.Chart(document.getElementById('enquiryTypeChart'), {
        type: 'pie',
        data: {
            labels: @json(array_keys($enquiryTypes)),
            datasets: [{ data: @json(array_values($enquiryTypes)), backgroundColor: palette }]
        }
    });

    new window.Chart(document.getElementById('leadsCampaignChart'), {
        type: 'bar',
        data: {
            labels: @json(array_keys($leadsByCampaign)),
            datasets: [{ label: 'Leads', data: @json(array_values($leadsByCampaign)), backgroundColor: '#f59e0b' }]
        },
        options: { plugins: { legend: { display: false } } }
    });

    new window.Chart(document.getElementById('dealerStatusChart'), {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($dealerStatuses)),
            datasets: [{ data: @json(array_values($dealerStatuses)), backgroundColor: palette }]
        }
    });
});
</script>
@endpush
