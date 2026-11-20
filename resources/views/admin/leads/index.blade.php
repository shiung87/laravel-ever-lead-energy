@extends('layouts.admin')

@section('title', 'Customer Leads')
@section('page_title', 'Customer Leads Management')

@section('content')
<div class="card kpi-card p-4 mb-4">
    <form method="GET" action="{{ route('admin.leads.index') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label small fw-semibold">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Ref, name, phone, car...">
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">All Statuses</option>
                @foreach(\App\Models\CustomerLead::STATUSES as $k => $v)
                    <option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold">Enquiry Type</label>
            <select name="enquiry_type" class="form-select form-select-sm">
                <option value="">All Types</option>
                @foreach(\App\Models\CustomerLead::ENQUIRY_TYPES as $k => $v)
                    <option value="{{ $k }}" @selected(request('enquiry_type') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold">Assigned To</label>
            <select name="assigned_to" class="form-select form-select-sm">
                <option value="">All Staff</option>
                @foreach($salesStaff as $staff)
                    <option value="{{ $staff->id }}" @selected(request('assigned_to') == $staff->id)>{{ $staff->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm flex-fill">Filter</button>
            <a href="{{ route('admin.leads.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            @if(auth()->user()->hasPermission('leads.export'))
                <a href="{{ route('admin.leads.export', request()->query()) }}" class="btn btn-success btn-sm"><i class="fa-solid fa-file-csv me-1"></i>CSV</a>
            @endif
        </div>
    </form>
</div>

<div class="card kpi-card p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
            <tr>
                <th>Ref</th>
                <th>Customer</th>
                <th>Location</th>
                <th>Vehicle / Battery</th>
                <th>Source</th>
                <th>Assigned</th>
                <th>Status</th>
                <th>Date</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($leads as $lead)
                <tr>
                    <td class="font-monospace small fw-bold">{{ $lead->reference_number }}</td>
                    <td>
                        <div class="fw-semibold">{{ $lead->full_name }}</div>
                        <div class="small text-muted">{{ $lead->mobile_number }}</div>
                    </td>
                    <td class="small">{{ $lead->city }}, {{ $lead->state }}</td>
                    <td class="small">
                        <div>{{ trim("{$lead->vehicle_brand} {$lead->vehicle_model} {$lead->vehicle_year}") ?: '-' }}</div>
                        <div class="text-muted">{{ $lead->battery_requirement }}</div>
                    </td>
                    <td class="small">{{ $lead->utm_source ?? 'direct' }}</td>
                    <td class="small">{{ $lead->assignee?->name ?? 'Unassigned' }}</td>
                    <td><span class="badge {{ $lead->status_badge_class }}">{{ $lead->status_label }}</span></td>
                    <td class="small text-muted">{{ $lead->created_at->format('d M Y') }}</td>
                    <td><a href="{{ route('admin.leads.show', $lead) }}" class="btn btn-sm btn-outline-primary">Manage</a></td>
                </tr>
            @empty
                <tr><td colspan="9" class="text-center text-muted py-4">No customer leads found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $leads->links('pagination::bootstrap-5') }}
</div>
@endsection
