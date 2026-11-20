@extends('layouts.admin')

@section('title', 'Promotions')
@section('page_title', 'Promotions & Campaigns')

@section('content')
<div class="d-flex justify-content-end mb-4">
    <a href="{{ route('admin.promotions.create') }}" class="btn btn-warning btn-sm fw-bold"><i class="fa-solid fa-plus me-1"></i>New Promotion</a>
</div>

<div class="card kpi-card p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead><tr><th>Campaign</th><th>Type / Discount</th><th>Schedule</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @foreach($promotions as $promo)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $promo->title }}</div>
                        <div class="small text-muted">{{ $promo->products_count }} linked products</div>
                    </td>
                    <td class="small">{{ ucfirst(str_replace('_', ' ', $promo->promo_type)) }}<div class="fw-bold text-danger">{{ $promo->discount_label }}</div></td>
                    <td class="small">{{ $promo->starts_at?->format('d M Y') ?? '-' }} to {{ $promo->ends_at?->format('d M Y') ?? '-' }}</td>
                    <td>
                        @if($promo->is_expired)
                            <span class="badge bg-secondary">Expired</span>
                        @elseif($promo->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-warning text-dark">Inactive</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.promotions.edit', $promo) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form method="POST" action="{{ route('admin.promotions.destroy', $promo) }}" class="d-inline" onsubmit="return confirm('Delete campaign?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $promotions->links('pagination::bootstrap-5') }}
</div>
@endsection
