@extends('layouts.admin')

@section('title', 'Audit Logs')
@section('page_title', 'System Audit Logs')

@section('content')
<div class="card kpi-card p-4">
    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle">
            <thead><tr><th>Timestamp</th><th>User</th><th>Action</th><th>Description</th><th>IP</th></tr></thead>
            <tbody>
            @forelse($logs as $log)
                <tr>
                    <td class="small text-muted">{{ $log->created_at->format('d M Y H:i:s') }}</td>
                    <td class="small fw-semibold">{{ $log->user?->name ?? 'System / Guest' }}</td>
                    <td><span class="badge bg-light text-dark border font-monospace">{{ $log->action }}</span></td>
                    <td class="small">{{ $log->description }}</td>
                    <td class="small text-muted font-monospace">{{ $log->ip_address }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No audit records found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $logs->links('pagination::bootstrap-5') }}
</div>
@endsection
