@php
    $phone = \App\Models\WebsiteSetting::getValue('contact_phone', '012-447 2622');
    $email = \App\Models\WebsiteSetting::getValue('contact_email', 'everleadenergy@gmail.com');
    $address = \App\Models\WebsiteSetting::getValue('company_address', '19-1 Jalan PKNK 1/4, Taman Ria Jaya, 08000 Sungai Petani, Kedah.');
    $hours = \App\Models\WebsiteSetting::getValue('business_hours', 'Mon - Sun: 8:00 AM - 10:00 PM');
    $reg = \App\Models\WebsiteSetting::getValue('company_registration', 'EVERLEAD ENERGY SDN BHD (1648893-U)');
    $fb = \App\Models\WebsiteSetting::getValue('facebook_url', '#');
    $ig = \App\Models\WebsiteSetting::getValue('instagram_url', '#');
    $tt = \App\Models\WebsiteSetting::getValue('tiktok_url', '#');
@endphp
<footer class="footer-surface pt-5 pb-4 text-secondary">
    <div class="container">
        <div class="row g-4 pb-4 border-bottom border-secondary border-opacity-25">
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="bg-warning text-dark rounded-2 px-2 py-1 fw-bold"><i class="fa-solid fa-bolt"></i></span>
                    <span class="fs-5 fw-bold text-white">EVERLEAD <span class="text-amber">ENERGY</span></span>
                </div>
                <p class="small mb-3">High-performance automotive battery solutions engineered for tropical reliability, backed by nationwide warranty support and an expanding authorized dealer network.</p>
                <div class="d-flex gap-2">
                    <a href="{{ $fb }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm rounded-circle" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="{{ $ig }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm rounded-circle" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="{{ $tt }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm rounded-circle" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="{{ \App\Services\WhatsappService::buildUrl() }}" target="_blank" rel="noopener" data-wa-track data-wa-type="footer" class="btn btn-outline-success btn-sm rounded-circle" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>

            <div class="col-6 col-lg-2">
                <h6 class="text-white fw-bold mb-3">Quick Links</h6>
                <ul class="list-unstyled small d-flex flex-column gap-2">
                    <li><a href="{{ route('home') }}" class="text-secondary text-decoration-none">Home</a></li>
                    <li><a href="{{ route('products.index') }}" class="text-secondary text-decoration-none">Battery Catalogue</a></li>
                    <li><a href="{{ route('battery-finder') }}" class="text-secondary text-decoration-none">Battery Finder</a></li>
                    <li><a href="{{ route('promotions.index') }}" class="text-secondary text-decoration-none">Promotions</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-2">
                <h6 class="text-white fw-bold mb-3">Partnership</h6>
                <ul class="list-unstyled small d-flex flex-column gap-2">
                    <li><a href="{{ route('dealer.index') }}" class="text-secondary text-decoration-none">Become a Dealer</a></li>
                    <li><a href="{{ route('enquiry.create') }}" class="text-secondary text-decoration-none">Customer Enquiry</a></li>
                    <li><a href="{{ route('contact') }}" class="text-secondary text-decoration-none">Contact Us</a></li>
                    <li><a href="{{ route('login') }}" class="text-secondary text-decoration-none">Admin Portal</a></li>
                </ul>
            </div>

            <div class="col-lg-4">
                <h6 class="text-white fw-bold mb-3">Contact & Support</h6>
                <ul class="list-unstyled small d-flex flex-column gap-2">
                    <li><i class="fa-solid fa-phone text-amber me-2"></i>{{ $phone }}</li>
                    <li><i class="fa-solid fa-envelope text-amber me-2"></i>{{ $email }}</li>
                    <li><i class="fa-solid fa-location-dot text-amber me-2"></i>{{ $address }}</li>
                    <li><i class="fa-solid fa-clock text-amber me-2"></i>{{ $hours }}</li>
                </ul>
            </div>
        </div>

        <div class="pt-3 d-flex flex-column flex-md-row justify-content-between align-items-center small gap-2">
            <div>&copy; {{ date('Y') }} {{ $reg }}. All rights reserved.</div>
            <div><a href="{{ route('sitemap') }}" class="text-secondary text-decoration-none">Sitemap</a></div>
        </div>
    </div>
</footer>
