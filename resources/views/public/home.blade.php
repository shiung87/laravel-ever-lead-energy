@extends('layouts.public')

@section('content')
@php
    $hero = $banners->first();
    $featuredPromo = $activePromotions->first();
@endphp

<!-- 1. Hero Banner -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="hero-badge mb-3">
                    <i class="fa-solid fa-bolt"></i>
                    <span>{{ $hero?->badge_text ?? 'NEXT-GEN AUTOMOTIVE POWER' }}</span>
                </div>
                <h1 class="display-3 fw-black text-white mb-3 lh-sm" style="font-weight: 800;">
                    {{ $hero?->title ?? 'Power Every Journey.' }}
                </h1>
                <p class="lead text-secondary mb-4">
                    {{ $hero?->subtitle ?? 'Reliable Car Battery Solutions for Every Drive. Engineered for tropical heat, backed by up to 24 months direct replacement warranty.' }}
                </p>

                <div class="d-flex flex-wrap gap-3 mb-5">
                    <a href="{{ $hero?->cta_primary_url ? url($hero->cta_primary_url) : route('battery-finder') }}" class="btn btn-amber btn-lg px-4">
                        <i class="fa-solid fa-magnifying-glass me-2"></i>{{ $hero?->cta_primary_text ?? 'Find Your Battery' }}
                    </a>
                    <a href="{{ $hero?->cta_secondary_url ? url($hero->cta_secondary_url) : route('dealer.index') }}" class="btn btn-outline-amber btn-lg px-4">
                        <i class="fa-solid fa-handshake me-2"></i>{{ $hero?->cta_secondary_text ?? 'Become Our Dealer' }}
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg px-4">
                        Contact Us
                    </a>
                </div>

                <!-- Animated Statistics -->
                <div class="row g-3">
                    <div class="col-4">
                        <div class="border-start border-warning border-3 ps-3">
                            <div class="h3 fw-bold text-white mb-0" data-counter="15000" data-suffix="+">15,000+</div>
                            <small class="text-secondary">Batteries Installed</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border-start border-info border-3 ps-3">
                            <div class="h3 fw-bold text-white mb-0" data-counter="120" data-suffix="+">120+</div>
                            <small class="text-secondary">Authorized Dealers</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border-start border-success border-3 ps-3">
                            <div class="h3 fw-bold text-white mb-0" data-counter="24" data-suffix=" Mo">24 Mo</div>
                            <small class="text-secondary">Max Warranty</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-left">
                <div class="battery-3d-stage text-center position-relative">
                    <img src="{{ $hero?->image_url ?? asset('images/hero-battery.svg') }}"
                         alt="Everlead Energy Automotive Battery"
                         class="img-fluid battery-3d-img">

                    <div class="floating-stat-pill position-absolute top-0 end-0 d-none d-md-flex align-items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-amber fs-4"></i>
                        <div class="text-start">
                            <div class="fw-bold text-white small">760 CCA Output</div>
                            <div class="text-secondary" style="font-size: 0.72rem;">Instant Cold Cranking</div>
                        </div>
                    </div>

                    <div class="floating-stat-pill position-absolute bottom-0 start-0 d-none d-md-flex align-items-center gap-2">
                        <i class="fa-solid fa-truck-fast text-cyan fs-4"></i>
                        <div class="text-start">
                            <div class="fw-bold text-white small">Rapid Dispatch</div>
                            <div class="text-secondary" style="font-size: 0.72rem;">On-Site Installation</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Why Choose Us -->
<section class="py-5 bg-dark-surface">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="text-amber text-uppercase small fw-bold letter-spacing-1">ENGINEERED FOR TRUST</span>
            <h2 class="fw-bold text-white mt-1">Why Choose Everlead Energy</h2>
            <div class="section-heading-line mx-auto mt-3"></div>
        </div>

        <div class="row g-4">
            @php
                $pillars = [
                    ['icon' => 'fa-gauge-high', 'title' => 'Reliable Performance', 'desc' => 'High Cold Cranking Amps (CCA) engineered for effortless ignition in all weather conditions.'],
                    ['icon' => 'fa-microchip', 'title' => 'Long-Lasting Technology', 'desc' => 'Calcium-silver, EFB, and AGM plate architecture built to resist tropical engine-bay heat.'],
                    ['icon' => 'fa-screwdriver-wrench', 'title' => 'Professional Installation', 'desc' => 'Trained technicians with alternator diagnostics & OBD-II memory-saver protection.'],
                    ['icon' => 'fa-tags', 'title' => 'Competitive Pricing', 'desc' => 'Direct-to-driver value with instant old-battery trade-in rebates and transparent quotes.'],
                    ['icon' => 'fa-award', 'title' => 'Warranty Support', 'desc' => 'Hassle-free 1-to-1 replacement warranty up to 24 months across our dealer network.'],
                ];
            @endphp

            @foreach($pillars as $i => $pillar)
                <div class="col-md-6 col-lg" data-aos="fade-up" data-aos-delay="{{ $i * 75 }}">
                    <div class="el-card p-4 h-100 text-center">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-warning bg-opacity-10 text-amber mb-3" style="width: 54px; height: 54px; font-size: 1.4rem;">
                            <i class="fa-solid {{ $pillar['icon'] }}"></i>
                        </div>
                        <h6 class="fw-bold text-white mb-2">{{ $pillar['title'] }}</h6>
                        <p class="small text-secondary mb-0">{{ $pillar['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- 3. Featured Products -->
<section class="py-5 bg-navy border-top border-secondary border-opacity-25">
    <div class="container py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5 gap-3">
            <div data-aos="fade-right">
                <span class="text-amber text-uppercase small fw-bold">HIGH-PERFORMANCE RANGE</span>
                <h2 class="fw-bold text-white mt-1 mb-0">Featured Car Batteries</h2>
                <div class="section-heading-line mt-3"></div>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-outline-amber">
                View Full Catalogue <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach($featuredProducts as $product)
                <div class="col-md-6 col-lg-4" data-aos="fade-up">
                    <x-public.product-card :product="$product" />
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- 4. Promotional Campaign -->
@if($featuredPromo)
<section class="py-5 bg-dark-surface">
    <div class="container py-4">
        <div class="el-card overflow-hidden p-4 p-lg-5" style="background: linear-gradient(135deg, #111a2e 0%, #1e1b2e 100%); border-color: rgba(245, 158, 11, 0.35);" data-aos="zoom-in">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="badge bg-warning text-dark fw-bold px-3 py-2 mb-3">{{ $featuredPromo->badge_text ?? 'HOT CAMPAIGN' }}</span>
                    <h2 class="fw-bold text-white mb-2">{{ $featuredPromo->title }}</h2>
                    <p class="text-secondary mb-4">{{ $featuredPromo->subtitle }}</p>

                    @if($featuredPromo->ends_at)
                        <div class="mb-4">
                            <div class="small text-amber fw-semibold mb-2 text-uppercase">Offer Ends In:</div>
                            <div class="d-flex gap-2 flex-wrap" data-countdown="{{ $featuredPromo->ends_at->toIso8601String() }}">
                                <div class="countdown-box"><div class="count-num" data-cd-days>00</div><div class="count-label">Days</div></div>
                                <div class="countdown-box"><div class="count-num" data-cd-hours>00</div><div class="count-label">Hours</div></div>
                                <div class="countdown-box"><div class="count-num" data-cd-mins>00</div><div class="count-label">Mins</div></div>
                                <div class="countdown-box"><div class="count-num" data-cd-secs>00</div><div class="count-label">Secs</div></div>
                            </div>
                        </div>
                    @endif

                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('promotions.show', $featuredPromo->slug) }}" class="btn btn-amber btn-lg px-4">
                            {{ $featuredPromo->cta_text }}
                        </a>
                        <a href="{{ route('promotions.index') }}" class="btn btn-outline-light btn-lg px-4">
                            All Promotions
                        </a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <img src="{{ $featuredPromo->banner_image_url }}" alt="{{ $featuredPromo->title }}" class="img-fluid rounded-4 shadow-lg" loading="lazy">
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- 5. Dealer Partnership Section -->
<section class="py-5 bg-navy border-top border-secondary border-opacity-25">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="text-cyan text-uppercase small fw-bold">B2B PARTNERSHIP OPPORTUNITY</span>
                <h2 class="fw-bold text-white mt-1 mb-3">Grow Your Business. Become Our Authorized Dealer.</h2>
                <p class="text-secondary mb-4">Join Malaysia’s fastest-growing automotive battery distribution network. Ideal for workshops, car accessory shops, tyre centres, and battery entrepreneurs.</p>
                <div class="row g-3 mb-4">
                    <div class="col-sm-6"><i class="fa-solid fa-circle-check text-amber me-2"></i>Competitive Dealer Margins</div>
                    <div class="col-sm-6"><i class="fa-solid fa-circle-check text-amber me-2"></i>Co-Branded Marketing Support</div>
                    <div class="col-sm-6"><i class="fa-solid fa-circle-check text-amber me-2"></i>EFB & AGM Technical Training</div>
                    <div class="col-sm-6"><i class="fa-solid fa-circle-check text-amber me-2"></i>Fast Warranty Claim Turnaround</div>
                </div>
                <a href="{{ route('dealer.index') }}" class="btn btn-amber btn-lg px-4">
                    <i class="fa-solid fa-handshake me-2"></i>Become an Authorized Dealer
                </a>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="el-card p-4">
                            <i class="fa-solid fa-wrench text-amber fs-2 mb-3"></i>
                            <h6 class="fw-bold text-white">Workshops & Mechanics</h6>
                            <p class="small text-secondary mb-0">High-demand SKUs ready for fast turnaround.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="el-card p-4">
                            <i class="fa-solid fa-store text-cyan fs-2 mb-3"></i>
                            <h6 class="fw-bold text-white">Retail & Accessory Shops</h6>
                            <p class="small text-secondary mb-0">Display racks, signage & promotional kits.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="el-card p-4">
                            <i class="fa-solid fa-truck-moving text-cyan fs-2 mb-3"></i>
                            <h6 class="fw-bold text-white">Regional Stockists</h6>
                            <p class="small text-secondary mb-0">Volume tier rebates & territory support.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="el-card p-4">
                            <i class="fa-solid fa-chart-line text-amber fs-2 mb-3"></i>
                            <h6 class="fw-bold text-white">Battery Entrepreneurs</h6>
                            <p class="small text-secondary mb-0">Launch your mobile battery delivery business.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 6. Customer Benefits -->
<section class="py-5 bg-dark-surface">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="text-amber text-uppercase small fw-bold">SEAMLESS EXPERIENCE</span>
            <h2 class="fw-bold text-white mt-1">Customer Benefits</h2>
            <div class="section-heading-line mx-auto mt-3"></div>
        </div>
        <div class="row g-4">
            <div class="col-md-3" data-aos="fade-up">
                <div class="el-card p-4 h-100">
                    <i class="fa-solid fa-car-burst text-amber fs-3 mb-3"></i>
                    <h6 class="fw-bold text-white">Replacement Assistance</h6>
                    <p class="small text-secondary mb-0">Quick battery diagnosis and replacement at your home, office, or roadside.</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="80">
                <div class="el-card p-4 h-100">
                    <i class="fa-solid fa-certificate text-cyan fs-3 mb-3"></i>
                    <h6 class="fw-bold text-white">Genuine Warranty</h6>
                    <p class="small text-secondary mb-0">Official manufacturer warranty coverage up to 24 months with digital tracking.</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="160">
                <div class="el-card p-4 h-100">
                    <i class="fa-solid fa-headset text-amber fs-3 mb-3"></i>
                    <h6 class="fw-bold text-white">Specialist Support</h6>
                    <p class="small text-secondary mb-0">Friendly battery advisors help you pick the exact spec for your vehicle.</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="240">
                <div class="el-card p-4 h-100">
                    <i class="fa-solid fa-bolt text-cyan fs-3 mb-3"></i>
                    <h6 class="fw-bold text-white">1-Minute Enquiry</h6>
                    <p class="small text-secondary mb-0">No complicated checkout—submit an enquiry or chat on WhatsApp instantly.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 7. Testimonials -->
@if($testimonials->isNotEmpty())
<section class="py-5 bg-navy border-top border-secondary border-opacity-25">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="text-amber text-uppercase small fw-bold">TRUSTED BY DRIVERS & WORKSHOPS</span>
            <h2 class="fw-bold text-white mt-1">What Our Customers Say</h2>
            <div class="section-heading-line mx-auto mt-3"></div>
        </div>
        <div class="row g-4">
            @foreach($testimonials as $t)
                <div class="col-md-6 col-lg-3" data-aos="fade-up">
                    <div class="el-card p-4 h-100 d-flex flex-column">
                        <div class="text-amber mb-2">
                            @for($s = 0; $s < $t->rating; $s++)
                                <i class="fa-solid fa-star"></i>
                            @endfor
                        </div>
                        <p class="small text-light flex-grow-1">"{{ $t->quote }}"</p>
                        <div class="pt-3 border-top border-secondary border-opacity-25 mt-2">
                            <div class="fw-bold text-white small">{{ $t->customer_name }}</div>
                            <div class="text-secondary" style="font-size: 0.75rem;">{{ $t->vehicle_info ?? $t->customer_role }} • {{ $t->city }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- 8. Contact & WhatsApp CTA -->
<x-public.cta-section />
@endsection
