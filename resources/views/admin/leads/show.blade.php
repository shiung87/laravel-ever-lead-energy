@extends('layouts.admin')

@section('title', 'Lead ' . $lead->reference_number)
@section('page_title', 'Lead Detail: ' . $lead->reference_number)

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.leads.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-arrow-left me-1"></i>Back to Leads</a>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card kpi-card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h5 class="fw-bold mb-1">{{ $lead->full_name }}</h5>
                    <div class="text-muted small">{{ $lead->mobile_number }} @if($lead->email) • {{ $lead->email }} @endif</div>
                </div>
                <span class="badge {{ $lead->status_badge_class }} fs-6">{{ $lead->status_label }}</span>
            </div>

            <div class="row g-3 small">
                <div class="col-md-6"><strong>Enquiry Type:</strong> {{ $lead->enquiry_type_label }}</div>
                <div class="col-md-6"><strong>Location:</strong> {{ $lead->city }}, {{ $lead->state }}</div>
                <div class="col-md-6"><strong>Vehicle:</strong> {{ trim("{$lead->vehicle_brand} {$lead->vehicle_model} {$lead->vehicle_year}") ?: '-' }}</div>
                <div class="col-md-6"><strong>Battery Requirement:</strong> {{ $lead->battery_requirement ?: '-' }}</div>
                <div class="col-md-6"><strong>Service Location:</strong> {{ $lead->preferred_service_location ?: '-' }}</div>
                <div class="col-md-6"><strong>Marketing Consent:</strong> {{ $lead->marketing_consent ? 'Yes' : 'No' }}</div>
                <div class="col-12"><strong>Customer Remarks:</strong><div class="p-2 bg-light rounded mt-1">{{ $lead->remarks ?: 'None' }}</div></div>
            </div>

            <hr>
            <h6 class="fw-bold small text-uppercase text-muted">Marketing Attribution</h6>
            <div class="row g-2 small">
                <div class="col-md-4"><strong>UTM Source:</strong> {{ $lead->utm_source ?? 'direct' }}</div>
                <div class="col-md-4"><strong>UTM Medium:</strong> {{ $lead->utm_medium ?? '-' }}</div>
                <div class="col-md-4"><strong>UTM Campaign:</strong> {{ $lead->utm_campaign ?? '-' }}</div>
                <div class="col-md-6"><strong>First Touch:</strong> {{ $lead->first_touch_source ?? '-' }}</div>
                <div class="col-md-6"><strong>Latest Touch:</strong> {{ $lead->latest_touch_source ?? '-' }}</div>
            </div>
        </div>

        <div class="card kpi-card p-4">
            <h6 class="fw-bold mb-3">Lead Activity History</h6>
            @forelse($lead->activities as $act)
                <div class="border-start border-3 border-primary ps-3 mb-3">
                    <div class="small fw-bold">{{ ucfirst(str_replace('_', ' ', $act->action)) }}
                        <span class="text-muted fw-normal">by {{ $act->user?->name ?? 'System' }} • {{ $act->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="small text-dark">{{ $act->notes }}</div>
                </div>
            @empty
                <p class="text-muted small mb-0">No activities logged yet.</p>
            @endforelse
        </div>
    </div>

    <div class="col-lg-5">
        @if(auth()->user()->hasPermission('leads.manage'))
        <div class="card kpi-card p-4 mb-4">
            <h6 class="fw-bold mb-3">Update Status & Follow-Up</h6>
            <form method="POST" action="{{ route('admin.leads.status', $lead) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        @foreach(\App\Models\CustomerLead::STATUSES as $k => $v)
                            <option value="{{ $k }}" @selected($lead->status === $k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Follow-Up Date</label>
                    <input type="date" name="follow_up_date" value="{{ $lead->follow_up_date?->format('Y-m-d') }}" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Internal Notes</label>
                    <textarea name="internal_notes" rows="2" class="form-control">{{ $lead->internal_notes }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Activity Log Note</label>
                    <input type="text" name="activity_note" class="form-control" placeholder="e.g. Called customer, sent quotation">
                </div>
                <button type="submit" class="btn btn-primary w-100">Save Lead Update</button>
            </form>
        </div>
        @endif

        @if(auth()->user()->hasPermission('leads.assign'))
        <div class="card kpi-card p-4">
            <h6 class="fw-bold mb-3">Assign Lead</h6>
            <form method="POST" action="{{ route('admin.leads.assign', $lead) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Sales Personnel</label>
                    <select name="assigned_to" class="form-select" required>
                        <option value="">Select Staff</option>
                        @foreach($salesStaff as $staff)
                            <option value="{{ $staff->id }}" @selected($lead->assigned_to === $staff->id)>{{ $staff->name }} ({{ $staff->role?->name }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Assignment Note</label>
                    <input type="text" name="notes" class="form-control" placeholder="Optional instruction">
                </div>
                <button type="submit" class="btn btn-outline-primary w-100">Assign Lead</button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
