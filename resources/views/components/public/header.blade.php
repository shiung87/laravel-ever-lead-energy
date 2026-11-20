@php
    $navBrands = \App\Models\ProductBrand::active()->take(5)->get();
    $navTypes = \App\Models\BatteryType::active()->take(5)->get();
@endphp
<nav class="navbar navbar-expand-lg navbar-dark el-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('home') }}">
            <span class="bg-warning text-dark rounded-2 px-2 py-1 d-inline-flex align-items-center justify-content-center">
                <i class="fa-solid fa-bolt"></i>
            </span>
            <span class="letter-spacing-1">EVERLEAD <span class="text-amber">ENERGY</span></span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav" aria-controls="publicNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="publicNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                </li>

                <!-- Mega Menu -->
                <li class="nav-item dropdown mega-dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('products.*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Batteries
                    </a>
                    <div class="dropdown-menu mega-menu">
                        <div class="container">
                            <div class="row g-4">
                                <div class="col-lg-4">
                                    <h6 class="text-amber text-uppercase small fw-bold mb-3">By Battery Technology</h6>
                                    @foreach($navTypes as $type)
                                        <a class="dropdown-item" href="{{ route('products.index', ['type' => $type->slug]) }}">
                                            <i class="fa-solid fa-car-battery me-2 text-amber"></i>{{ $type->name }}
                                        </a>
                                    @endforeach
                                </div>
                                <div class="col-lg-4">
                                    <h6 class="text-amber text-uppercase small fw-bold mb-3">By Brand</h6>
                                    @foreach($navBrands as $brand)
                                        <a class="dropdown-item" href="{{ route('products.index', ['brand' => $brand->slug]) }}">
                                            <i class="fa-solid fa-shield-halved me-2 text-cyan"></i>{{ $brand->name }}
                                        </a>
                                    @endforeach
                                </div>
                                <div class="col-lg-4">
                                    <div class="p-3 rounded-3 bg-dark-surface border border-secondary border-opacity-25 h-100 d-flex flex-column justify-content-between">
                                        <div>
                                            <span class="badge bg-warning text-dark mb-2">Smart Match</span>
                                            <h6 class="text-white fw-bold">Not sure which battery fits?</h6>
                                            <p class="small text-secondary mb-3">Use our interactive Battery Finder by car brand, model, and year.</p>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('battery-finder') }}" class="btn btn-amber btn-sm flex-fill">Battery Finder</a>
                                            <a href="{{ route('products.index') }}" class="btn btn-outline-light btn-sm flex-fill">All Products</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('battery-finder') ? 'active' : '' }}" href="{{ route('battery-finder') }}">Battery Finder</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('promotions.*') ? 'active' : '' }}" href="{{ route('promotions.index') }}">Promotions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dealer.*') ? 'active' : '' }}" href="{{ route('dealer.index') }}">Become a Dealer</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('enquiry.create') }}" class="btn btn-amber btn-sm px-3 py-2">
                    <i class="fa-solid fa-headset me-1"></i> Enquire Now
                </a>
                <a href="{{ \App\Services\WhatsappService::buildUrl() }}"
                   target="_blank"
                   rel="noopener"
                   data-wa-track
                   data-wa-type="header"
                   data-wa-label="Header WhatsApp"
                   class="btn btn-whatsapp btn-sm px-3 py-2">
                    <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp
                </a>
            </div>
        </div>
    </div>
</nav>
