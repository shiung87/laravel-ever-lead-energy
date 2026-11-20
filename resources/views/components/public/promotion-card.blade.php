@props(['promotion'])

<div class="el-card h-100 overflow-hidden d-flex flex-column">
    <div class="position-relative" style="height: 190px;">
        <img src="{{ $promotion->banner_image_url }}" alt="{{ $promotion->title }}" loading="lazy" class="w-100 h-100 object-fit-cover">
        @if($promotion->badge_text)
            <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 px-3 py-2 fw-bold">
                {{ $promotion->badge_text }}
            </span>
        @endif
        @if($promotion->discount_label)
            <span class="badge bg-danger position-absolute bottom-0 end-0 m-3 px-3 py-2 fw-bold">
                {{ $promotion->discount_label }}
            </span>
        @endif
    </div>

    <div class="p-4 d-flex flex-column flex-grow-1">
        <h5 class="fw-bold text-white mb-2">{{ $promotion->title }}</h5>
        <p class="small text-secondary mb-3 flex-grow-1">{{ $promotion->subtitle }}</p>

        @if($promotion->ends_at)
            <div class="small text-amber mb-3">
                <i class="fa-regular fa-clock me-1"></i>Valid until {{ $promotion->ends_at->format('d M Y') }}
            </div>
        @endif

        <div class="d-flex gap-2 mt-auto">
            <a href="{{ route('promotions.show', $promotion->slug) }}" class="btn btn-amber btn-sm flex-fill">
                {{ $promotion->cta_text }}
            </a>
            <a href="{{ \App\Services\WhatsappService::buildUrl($promotion->whatsapp_text, 'sales') }}"
               target="_blank"
               rel="noopener"
               data-wa-track
               data-wa-type="promotion"
               data-wa-id="{{ $promotion->id }}"
               data-wa-label="{{ $promotion->title }}"
               class="btn btn-whatsapp btn-sm">
                <i class="fa-brands fa-whatsapp"></i>
            </a>
        </div>
    </div>
</div>
