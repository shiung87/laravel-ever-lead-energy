@extends('layouts.public')

@section('meta_title', 'Contact Us | Everlead Energy')

@section('content')
@php
    $numA = random_int(2, 9);
    $numB = random_int(1, 9);
@endphp
<section class="py-5 bg-navy border-bottom border-secondary border-opacity-25">
    <div class="container">
        <span class="text-amber text-uppercase small fw-bold">GET IN TOUCH</span>
        <h1 class="fw-bold text-white mt-1">Contact Everlead Energy</h1>
        <p class="text-secondary mb-0">Questions about battery compatibility, warranty, or dealer partnerships? Reach out to our team.</p>
    </div>
</section>

<section class="py-5 bg-dark-surface">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <div class="el-card p-4 mb-4">
                    <h5 class="fw-bold text-white mb-4">Headquarters & Support</h5>
                    <div class="d-flex flex-column gap-3 small text-light">
                        <div><i class="fa-solid fa-building text-amber me-2"></i><strong>Company:</strong> {{ $settings['company_registration'] ?? 'EVERLEAD ENERGY SDN BHD (1648893-U)' }}</div>
                        <div><i class="fa-solid fa-phone text-amber me-2"></i><strong>Phone:</strong> {{ $settings['contact_phone'] ?? '012-447 2622' }}</div>
                        <div><i class="fa-solid fa-envelope text-amber me-2"></i><strong>Email:</strong> {{ $settings['contact_email'] ?? 'everleadenergy@gmail.com' }}</div>
                        <div><i class="fa-solid fa-location-dot text-amber me-2"></i><strong>Address:</strong> {{ $settings['company_address'] ?? '19-1 Jalan PKNK 1/4, Taman Ria Jaya, 08000 Sungai Petani, Kedah.' }}</div>
                        <div><i class="fa-solid fa-clock text-amber me-2"></i><strong>Hours:</strong> {{ $settings['business_hours'] ?? 'Mon - Sun: 8:00 AM - 10:00 PM' }}</div>
                    </div>
                    <hr class="border-secondary border-opacity-25 my-4">
                    <a href="{{ \App\Services\WhatsappService::buildUrl() }}" target="_blank" rel="noopener" data-wa-track data-wa-type="contact_page" class="btn btn-whatsapp w-100">
                        <i class="fa-brands fa-whatsapp me-2"></i>Chat Instantly on WhatsApp
                    </a>
                </div>

                @if(! empty($settings['map_embed_url']))
                    <div class="el-card overflow-hidden" style="height: 260px;">
                        <iframe src="{{ $settings['map_embed_url'] }}" width="100%" height="100%" style="border:0;" loading="lazy" title="Everlead Energy Location"></iframe>
                    </div>
                @endif
            </div>

            <div class="col-lg-7">
                <x-alerts />
                <form method="POST" action="{{ route('contact.store') }}" class="el-card p-4 p-lg-5">
                    @csrf
                    <div class="d-none" aria-hidden="true"><input type="text" name="website_url" tabindex="-1" autocomplete="off"></div>
                    <h4 class="fw-bold text-white mb-4">Send Us a Message</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-light fw-semibold">Your Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control el-form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-light fw-semibold">Mobile Number <span class="text-danger">*</span></label>
                            <input type="tel" name="mobile_number" value="{{ old('mobile_number') }}" class="form-control el-form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-light fw-semibold">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control el-form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-light fw-semibold">Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" value="{{ old('subject') }}" class="form-control el-form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-light fw-semibold">Message <span class="text-danger">*</span></label>
                            <textarea name="message" rows="4" class="form-control el-form-control" required>{{ old('message') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-amber fw-semibold">Security Check: {{ $numA }} + {{ $numB }} = ? <span class="text-danger">*</span></label>
                            <input type="hidden" name="captcha_expected" value="{{ $numA + $numB }}">
                            <input type="number" name="captcha_answer" class="form-control el-form-control" required>
                        </div>
                        <div class="col-12 pt-2">
                            <button type="submit" class="btn btn-amber btn-lg w-100">Send Message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
