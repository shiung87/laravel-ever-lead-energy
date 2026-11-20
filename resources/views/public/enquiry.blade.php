@extends('layouts.public')

@section('meta_title', 'Customer Battery Enquiry | Everlead Energy')
@section('meta_description', 'Request a car battery quote, doorstep replacement, or warranty assistance from Everlead Energy.')

@section('content')
<section class="py-5 bg-navy border-bottom border-secondary border-opacity-25">
    <div class="container">
        <span class="text-amber text-uppercase small fw-bold">FAST LEAD CONVERSION</span>
        <h1 class="fw-bold text-white mt-1">Customer Battery Enquiry</h1>
        <p class="text-secondary mb-0">Tell us about your vehicle and location—our battery team will respond with exact compatibility and pricing.</p>
    </div>
</section>

<section class="py-5 bg-dark-surface">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <x-alerts />
                <x-public.lead-form :products="$products" :promotions="$promotions" :selectedProduct="$selectedProduct" :selectedPromotion="$selectedPromotion" />
            </div>
        </div>
    </div>
</section>
@endsection
