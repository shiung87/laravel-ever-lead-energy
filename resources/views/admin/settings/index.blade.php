@extends('layouts.admin')

@section('title', 'Settings & SEO')
@section('page_title', 'Website, WhatsApp, SEO & System Settings')

@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf
    @foreach($settings as $group => $items)
        <div class="card kpi-card p-4 mb-4">
            <h6 class="fw-bold text-uppercase text-primary mb-3">{{ ucfirst($group) }} Configuration</h6>
            <div class="row g-3">
                @foreach($items as $setting)
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">{{ $setting->label ?? $setting->key }}</label>
                        @if($setting->type === 'boolean')
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="settings[{{ $setting->key }}]" value="1" @checked($setting->value === '1')>
                                <label class="form-check-label small">Enabled</label>
                            </div>
                        @elseif($setting->type === 'textarea')
                            <textarea name="settings[{{ $setting->key }}]" rows="2" class="form-control form-control-sm">{{ $setting->value }}</textarea>
                        @else
                            <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" class="form-control form-control-sm">
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <button type="submit" class="btn btn-primary btn-lg px-5">Save All Settings</button>
</form>
@endsection
