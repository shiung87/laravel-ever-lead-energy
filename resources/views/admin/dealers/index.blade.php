@extends('layouts.admin')

@section('title', 'Dealer Applications')
@section('page_title', 'Dealer Partnership Applications')

@section('content')
<div class="card kpi-card p-4 mb-4">
    <form method="GET" action="{{ route('admin.dealers.index') }}" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label small fw-semibold">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Ref, business name, contact...">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-semibold">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">All Statuses</option>
                @foreach(\App\Models\DealerApplication::STATUSES as $k => $v)
                    <option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-5 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            <a href="{{ route('admin.dealers.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            @if(auth()->user()->hasPermission('dealers.export'))
                <a href="{{ route('admin.dealers.export') }}" class="btn btn-success btn-sm ms-auto"><i class="fa-solid fa-file-csv me-1"></i>Export CSV</a>
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
                <th>Business</th>
                <th>Contact</th>
                <th>Location</th>
                <th>Est. Volume</th>
                <th>Docs</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($applications as $app)
                <tr>
                    <td class="font-monospace small fw-bold">{{ $app->reference_number }}</td>
                    <td><div class="fw-semibold">{{ $app->business_name }}</div><div class="small text-muted">{{ $app->business_type }}</div></td>
                    <td><div>{{ $app->contact_person }}</div><div class="small text-muted">{{ $app->mobile_number }}</div></td>
                    <td class="small">{{ $app->city }}, {{ $app->state }}</td>
                    <td class="small">{{ $app->estimated_monthly_volume }}</td>
                    <td><span class="badge bg-light text-dark border">{{ $app->documents->count() }}</span></td>
                    <td><span class="badge {{ $app->status_badge_class }}">{{ $app->status_label }}</span></td>
                    <td><a href="{{ route('admin.dealers.show', $app) }}" class="btn btn-sm btn-outline-primary">Review</a></td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No dealer applications found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $applications->links('pagination::bootstrap-5') }}
</div>
@endsection
