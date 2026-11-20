@extends('layouts.admin')

@section('title', 'Homepage Banners')
@section('page_title', 'Homepage Banner Management')

@section('content')
<div class="card kpi-card p-4 mb-4">
    <h6 class="fw-bold mb-3">Add Homepage Hero Banner</h6>
    <form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data" class="row g-3">
        @csrf
        <div class="col-md-4"><input type="text" name="title" class="form-control form-control-sm" placeholder="Headline *" required></div>
        <div class="col-md-4"><input type="text" name="badge_text" class="form-control form-control-sm" placeholder="Badge Text"></div>
        <div class="col-md-4"><input type="file" name="image" accept="image/*" class="form-control form-control-sm"></div>
        <div class="col-12"><input type="text" name="subtitle" class="form-control form-control-sm" placeholder="Subtitle"></div>
        <div class="col-md-3"><input type="text" name="cta_primary_text" class="form-control form-control-sm" placeholder="Primary CTA Text"></div>
        <div class="col-md-3"><input type="text" name="cta_primary_url" class="form-control form-control-sm" placeholder="Primary CTA URL (/battery-finder)"></div>
        <div class="col-md-3"><input type="text" name="cta_secondary_text" class="form-control form-control-sm" placeholder="Secondary CTA Text"></div>
        <div class="col-md-3"><input type="text" name="cta_secondary_url" class="form-control form-control-sm" placeholder="Secondary CTA URL (/become-a-dealer)"></div>
        <div class="col-12"><button type="submit" class="btn btn-primary btn-sm">Add Banner</button></div>
    </form>
</div>

<div class="card kpi-card p-4">
    <table class="table align-middle mb-0">
        <thead><tr><th>Title</th><th>Primary CTA</th><th>Status</th><th></th></tr></thead>
        <tbody>
        @foreach($banners as $banner)
            <tr>
                <td><div class="fw-semibold">{{ $banner->title }}</div><div class="small text-muted">{{ $banner->subtitle }}</div></td>
                <td class="small">{{ $banner->cta_primary_text }} ({{ $banner->cta_primary_url }})</td>
                <td><span class="badge {{ $banner->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $banner->is_active ? 'Active' : 'Inactive' }}</span></td>
                <td class="text-end">
                    <form method="POST" action="{{ route('admin.banners.toggle', $banner) }}" class="d-inline">@csrf
                        <button class="btn btn-sm btn-outline-secondary">Toggle</button>
                    </form>
                    <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" class="d-inline" onsubmit="return confirm('Delete banner?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
