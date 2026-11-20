@extends('layouts.public')

@section('meta_title', ($promotion->meta_title ?: $promotion->title) . ' | Everlead Energy')
@section('meta_description', $promotion->meta_description ?: $promotion->subtitle)

@section('content')
<section class="py-5 bg-navy border-bottom border-secondary border-opacity-25">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="badge bg-warning text-dark fw-bold px-3 py-2 mb-3">{{ $promotion->badge_text ?? 'ACTIVE PROMOTION' }}</span>
                <h1 class="fw-bold text-white mb-3">{{ $promotion->title }}</h1>
                <p class="lead text-secondary mb-4">{{ $promotion->subtitle }}</p>

                @if($promotion->ends_at)
                    <div class="mb-4">
                        <div class="small text-amber fw-semibold mb-2 text-uppercase">Campaign Ends In:</div>
                        <div class="d-flex gap-2 flex-wrap" data-countdown="{{ $promotion->ends_at->toIso8601String() }}">
                            <div class="countdown-box"><div class="count-num" data-cd-days>00</div><div class="count-label">Days</div></div>
                            <div class="countdown-box"><div class="count-num" data-cd-hours>00</div><div class="count-label">Hours</div></div>
                            <div class="countdown-box"><div class="count-num" data-cd-mins>00</div><div class="count-label">Mins</div></div>
                            <div class="countdown-box"><div class="count-num" data-cd-secs>00</div><div class="count-label">Secs</div></div>
                        </div>
                    </div>
                @endif

                <a href="{{ \App\Services\WhatsappService::buildUrl($promotion->whatsapp_text, 'sales') }}"
                   target="_blank"
                   rel="noopener"
                   data-wa-track
                   data-wa-type="promotion"
                   data-wa-id="{{ $promotion->id }}"
                   data-wa-label="{{ $promotion->title }}"
                   class="btn btn-whatsapp btn-lg px-4">
                    <i class="fa-brands fa-whatsapp me-2"></i>Claim via WhatsApp
                </a>
            </div>
            <div class="col-lg-5">
                <img src="{{ $promotion->banner_image_url }}" alt="{{ $promotion->title }}" class="img-fluid rounded-4 shadow-lg">
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-dark-surface">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <div class="el-card p-4 mb-4">
                    <h4 class="fw-bold text-white mb-3">Campaign Details</h4>
                    <p class="text-secondary">{{ $promotion->description }}</p>
                    <hr class="border-secondary border-opacity-25">
                    <div class="small text-light">
                        <div><strong>Valid From:</strong> {{ $promotion->starts_at?->format('d M Y') ?? 'Immediate' }}</div>
                        <div><strong>Valid Until:</strong> {{ $promotion->ends_at?->format('d M Y') ?? 'While stocks last' }}</div>
                    </div>
                </div>

                @if($promotion->terms_conditions)
                    <div class="el-card p-4">
                        <h6 class="fw-bold text-amber mb-2">Terms & Conditions</h6>
                        <p class="small text-secondary mb-0" style="white-space: pre-line;">{{ $promotion->terms_conditions }}</p>
                    </div>
                @endif
            </div>

            <div class="col-lg-7">
                <h3 class="fw-bold text-white mb-3">{{ $promotion->cta_text }}</h3>
                <p class="text-secondary mb-4">Complete the form below to lock in this promotional offer.</p>
                <x-public.lead-form :products="$promotion->products" :selectedPromotion="$promotion" />
            </div>
        </div>
    </div>
</section>
@endsection
