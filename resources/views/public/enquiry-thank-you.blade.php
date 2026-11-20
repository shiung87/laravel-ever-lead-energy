@extends('layouts.public')

@section('meta_title', 'Enquiry Submitted | Everlead Energy')

@section('content')
<section class="py-5 bg-dark-surface">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="el-card p-5 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 text-success mb-4" style="width: 80px; height: 80px; font-size: 2.2rem;">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <h1 class="fw-bold text-white mb-2">Thank You, {{ $lead->full_name }}!</h1>
                    <p class="text-secondary mb-4">Your battery enquiry has been registered and a confirmation email has been sent (if provided).</p>

                    <div class="bg-navy border border-warning border-opacity-50 rounded-3 p-3 mb-4">
                        <div class="small text-secondary text-uppercase">Your Enquiry Reference Number</div>
                        <div class="h3 fw-bold text-amber mb-0 font-monospace">{{ $lead->reference_number }}</div>
                    </div>

                    <p class="small text-secondary mb-4">Need urgent battery replacement right now? Connect directly with our dispatch team on WhatsApp with your reference number pre-filled.</p>

                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <a href="{{ $whatsappUrl }}"
                           target="_blank"
                           rel="noopener"
                           data-wa-track
                           data-wa-type="lead_thankyou"
                           data-wa-id="{{ $lead->id }}"
                           data-wa-label="{{ $lead->reference_number }}"
                           class="btn btn-whatsapp btn-lg px-4">
                            <i class="fa-brands fa-whatsapp me-2"></i>Chat on WhatsApp Now
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-light btn-lg px-4">Back to Homepage</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
