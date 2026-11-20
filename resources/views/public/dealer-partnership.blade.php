@extends('layouts.public')

@section('meta_title', 'Become Our Authorized Dealer | Everlead Energy')
@section('meta_description', 'Grow your automotive business with Everlead Energy. Competitive dealer pricing, marketing support, technical training, and fast warranty support.')

@section('content')
<section class="py-5 bg-navy border-bottom border-secondary border-opacity-25">
    <div class="container py-3">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark fw-bold px-3 py-2 mb-3">DEALER & PARTNER RECRUITMENT</span>
                <h1 class="display-5 fw-bold text-white mb-3">Grow Your Business. Become Our Authorized Dealer.</h1>
                <p class="lead text-secondary mb-0">Partner with a trusted automotive battery brand. Designed for automotive workshops, car accessory shops, battery retailers, mechanics, and entrepreneurs.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="#dealerApplicationForm" class="btn btn-amber btn-lg px-4">Apply Now <i class="fa-solid fa-arrow-down ms-2"></i></a>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-dark-surface">
    <div class="container">
        <!-- Benefits Grid -->
        <div class="row g-4 mb-5">
            @php
                $benefits = [
                    ['icon' => 'fa-percent', 'title' => 'Competitive Dealer Pricing', 'desc' => 'Attractive wholesale margins and volume-based tier rebates.'],
                    ['icon' => 'fa-bullhorn', 'title' => 'Marketing & Promo Support', 'desc' => 'Signage, display racks, digital leads, and co-branded campaigns.'],
                    ['icon' => 'fa-graduation-cap', 'title' => 'Product & Tech Training', 'desc' => 'Hands-on training on EFB, AGM, OBD-II registration, and diagnostics.'],
                    ['icon' => 'fa-shield-halved', 'title' => 'Fast Warranty Support', 'desc' => 'Streamlined 1-to-1 warranty claim processing to protect your reputation.'],
                ];
            @endphp
            @foreach($benefits as $b)
                <div class="col-md-6 col-lg-3">
                    <div class="el-card p-4 h-100">
                        <i class="fa-solid {{ $b['icon'] }} text-amber fs-2 mb-3"></i>
                        <h6 class="fw-bold text-white">{{ $b['title'] }}</h6>
                        <p class="small text-secondary mb-0">{{ $b['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div id="dealerApplicationForm" class="row justify-content-center">
            <div class="col-lg-10">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-white">Authorized Dealer Registration Form</h2>
                    <p class="text-secondary">Submit your business profile and SSM documents. Our partnership team will respond within 1–2 business days.</p>
                </div>
                <x-alerts />
                <x-public.dealer-form />
            </div>
        </div>
    </div>
</section>
@endsection
