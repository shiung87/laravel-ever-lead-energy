@props([
    'title' => 'Need a Reliable Car Battery Today?',
    'subtitle' => 'Get expert recommendation, transparent pricing, and rapid installation support.',
])

<section class="py-5 bg-navy border-top border-bottom border-secondary border-opacity-25">
    <div class="container">
        <div class="el-card p-4 p-lg-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.14), rgba(14, 165, 233, 0.12));">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark mb-2">FAST RESPONSE</span>
                    <h2 class="fw-bold text-white mb-2">{{ $title }}</h2>
                    <p class="text-secondary mb-0">{{ $subtitle }}</p>
                </div>
                <div class="col-lg-4 d-flex flex-wrap gap-2 justify-content-lg-end">
                    <a href="{{ route('enquiry.create') }}" class="btn btn-amber px-4 py-2">
                        <i class="fa-solid fa-paper-plane me-1"></i> Submit Enquiry
                    </a>
                    <a href="{{ \App\Services\WhatsappService::buildUrl() }}"
                       target="_blank"
                       rel="noopener"
                       data-wa-track
                       data-wa-type="cta_section"
                       class="btn btn-whatsapp px-4 py-2">
                        <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
