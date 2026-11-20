@php
    $popupEnabled = \App\Models\WebsiteSetting::getValue('promo_popup_enabled', '1') === '1';
    $popupTitle = \App\Models\WebsiteSetting::getValue('promo_popup_title', 'Get Up to RM 60 Trade-In Rebate!');
    $popupText = \App\Models\WebsiteSetting::getValue('promo_popup_text', 'Enjoy free 15-point battery health check & doorstep installation.');
    $popupUrl = \App\Models\WebsiteSetting::getValue('promo_popup_cta_url', '/promotions');
@endphp

@if($popupEnabled && request()->routeIs('home'))
<div class="modal fade" id="promoPopupModal" tabindex="-1" aria-labelledby="promoPopupLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-navy text-white border border-warning border-opacity-50 rounded-4 overflow-hidden">
            <div class="modal-header border-0 pb-0">
                <span class="badge bg-warning text-dark fw-bold">SPECIAL OFFER</span>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10 text-amber" style="width: 64px; height: 64px; font-size: 1.75rem;">
                        <i class="fa-solid fa-bolt"></i>
                    </span>
                </div>
                <h4 id="promoPopupLabel" class="fw-bold mb-2">{{ $popupTitle }}</h4>
                <p class="text-secondary mb-4">{{ $popupText }}</p>
                <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
                    <a href="{{ url($popupUrl) }}" class="btn btn-amber px-4">View Active Promotions</a>
                    <a href="{{ route('enquiry.create') }}" class="btn btn-outline-light px-4">Enquire Now</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
