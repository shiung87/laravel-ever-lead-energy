@extends('layouts.admin')

@section('title', 'Contact Enquiries')
@section('page_title', 'General Contact Enquiries')

@section('content')
<div class="card kpi-card p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead><tr><th>Ref</th><th>Sender</th><th>Subject & Message</th><th>Status</th><th>Update</th></tr></thead>
            <tbody>
            @forelse($enquiries as $enq)
                <tr>
                    <td class="font-monospace small fw-bold">{{ $enq->reference_number }}</td>
                    <td><div class="fw-semibold">{{ $enq->name }}</div><div class="small text-muted">{{ $enq->mobile_number }} • {{ $enq->email }}</div></td>
                    <td class="small"><strong>{{ $enq->subject }}</strong><div>{{ $enq->message }}</div></td>
                    <td><span class="badge bg-info text-dark">{{ ucfirst($enq->status) }}</span></td>
                    <td>
                        <form method="POST" action="{{ route('admin.enquiries.update', $enq) }}" class="d-flex gap-1">
                            @csrf
                            <select name="status" class="form-select form-select-sm" style="width: 110px;">
                                @foreach(['new', 'read', 'replied', 'closed'] as $st)
                                    <option value="{{ $st }}" @selected($enq->status === $st)>{{ ucfirst($st) }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-primary">Save</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No contact enquiries yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $enquiries->links('pagination::bootstrap-5') }}
</div>
@endsection
