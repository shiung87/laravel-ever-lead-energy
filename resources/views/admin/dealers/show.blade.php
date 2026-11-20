@extends('layouts.admin')

@section('title', 'Dealer ' . $dealer->reference_number)
@section('page_title', 'Dealer Application: ' . $dealer->business_name)

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.dealers.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-arrow-left me-1"></i>Back to Dealers</a>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card kpi-card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h5 class="fw-bold mb-1">{{ $dealer->business_name }}</h5>
                    <div class="text-muted small">Reg: {{ $dealer->registration_number }} • Ref: {{ $dealer->reference_number }}</div>
                </div>
                <span class="badge {{ $dealer->status_badge_class }} fs-6">{{ $dealer->status_label }}</span>
            </div>

            <div class="row g-3 small">
                <div class="col-md-6"><strong>Contact Person:</strong> {{ $dealer->contact_person }}</div>
                <div class="col-md-6"><strong>Mobile / Email:</strong> {{ $dealer->mobile_number }} / {{ $dealer->email }}</div>
                <div class="col-md-6"><strong>Business Type:</strong> {{ $dealer->business_type }}</div>
                <div class="col-md-6"><strong>Years in Operation:</strong> {{ $dealer->years_in_operation }} years</div>
                <div class="col-md-6"><strong>Outlets:</strong> {{ $dealer->number_of_outlets }}</div>
                <div class="col-md-6"><strong>Est. Monthly Volume:</strong> {{ $dealer->estimated_monthly_volume }}</div>
                <div class="col-md-6"><strong>Partnership Type:</strong> {{ $dealer->partnership_type }}</div>
                <div class="col-md-6"><strong>Existing Brands:</strong> {{ $dealer->existing_brands ?: '-' }}</div>
                <div class="col-12"><strong>Address:</strong> {{ $dealer->business_address }}, {{ $dealer->city }}, {{ $dealer->state }}</div>
                <div class="col-12"><strong>Remarks:</strong> {{ $dealer->remarks ?: '-' }}</div>
            </div>

            <hr>
            <h6 class="fw-bold">Uploaded Registration Documents</h6>
            @forelse($dealer->documents as $doc)
                <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded mb-2 small">
                    <span><i class="fa-solid fa-file-pdf text-danger me-2"></i>{{ $doc->original_name }} ({{ number_format($doc->file_size / 1024, 1) }} KB)</span>
                    <a href="{{ route('admin.dealers.document', [$dealer, $doc]) }}" class="btn btn-sm btn-outline-primary">Download</a>
                </div>
            @empty
                <p class="text-muted small mb-0">No documents uploaded.</p>
            @endforelse
        </div>

        <div class="card kpi-card p-4">
            <h6 class="fw-bold mb-3">Application Activity Log</h6>
            @forelse($dealer->activities as $act)
                <div class="border-start border-3 border-warning ps-3 mb-3">
                    <div class="small fw-bold">{{ ucfirst(str_replace('_', ' ', $act->action)) }}
                        <span class="text-muted fw-normal">by {{ $act->user?->name ?? 'System' }} • {{ $act->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="small">{{ $act->notes }}</div>
                </div>
            @empty
                <p class="text-muted small mb-0">No activities yet.</p>
            @endforelse
        </div>
    </div>

    <div class="col-lg-5">
        @if(auth()->user()->hasPermission('dealers.manage'))
        <div class="card kpi-card p-4">
            <h6 class="fw-bold mb-3">Review & Status Update</h6>
            <form method="POST" action="{{ route('admin.dealers.update', $dealer) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Application Status</label>
                    <select name="status" class="form-select">
                        @foreach(\App\Models\DealerApplication::STATUSES as $k => $v)
                            <option value="{{ $k }}" @selected($dealer->status === $k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Assign Owner</label>
                    <select name="assigned_to" class="form-select">
                        <option value="">Unassigned</option>
                        @foreach($staff as $u)
                            <option value="{{ $u->id }}" @selected($dealer->assigned_to === $u->id)>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Follow-Up Date</label>
                    <input type="date" name="follow_up_date" value="{{ $dealer->follow_up_date?->format('Y-m-d') }}" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Internal Notes</label>
                    <textarea name="internal_notes" rows="3" class="form-control">{{ $dealer->internal_notes }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Activity Note</label>
                    <input type="text" name="activity_note" class="form-control" placeholder="e.g. Verified SSM, scheduled site visit">
                </div>
                <button type="submit" class="btn btn-primary w-100">Update Dealer Application</button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
