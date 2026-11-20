@extends('layouts.public')

@section('meta_title', 'Dealer Application Received | Everlead Energy')

@section('content')
<section class="py-5 bg-dark-surface">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="el-card p-5 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 text-success mb-4" style="width: 80px; height: 80px; font-size: 2.2rem;">
                        <i class="fa-solid fa-handshake"></i>
                    </div>
                    <h1 class="fw-bold text-white mb-2">Application Submitted!</h1>
                    <p class="text-secondary mb-4">Thank you, <strong>{{ $application->contact_person }}</strong>. Your dealer application for <strong>{{ $application->business_name }}</strong> has been received.</p>

                    <div class="bg-navy border border-warning border-opacity-50 rounded-3 p-3 mb-4">
                        <div class="small text-secondary text-uppercase">Dealer Application Reference</div>
                        <div class="h3 fw-bold text-amber mb-0 font-monospace">{{ $application->reference_number }}</div>
                    </div>

                    <p class="small text-secondary mb-4">Want to fast-track your partnership discussion? Message our Partnership Team directly via WhatsApp.</p>

                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <a href="{{ $whatsappUrl }}"
                           target="_blank"
                           rel="noopener"
                           data-wa-track
                           data-wa-type="dealer_thankyou"
                           data-wa-id="{{ $application->id }}"
                           data-wa-label="{{ $application->reference_number }}"
                           class="btn btn-whatsapp btn-lg px-4">
                            <i class="fa-brands fa-whatsapp me-2"></i>WhatsApp Partnership Team
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-light btn-lg px-4">Back to Homepage</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
