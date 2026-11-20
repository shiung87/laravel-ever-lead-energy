@extends('layouts.public')

@section('meta_title', 'Promotions & Special Campaigns | Everlead Energy')
@section('meta_description', 'Explore active car battery trade-in rebates, free installation offers, and seasonal bundles.')

@section('content')
<section class="py-5 bg-navy border-bottom border-secondary border-opacity-25">
    <div class="container">
        <span class="text-amber text-uppercase small fw-bold">EXCLUSIVE OFFERS</span>
        <h1 class="fw-bold text-white mt-1">Promotions & Campaigns</h1>
        <p class="text-secondary mb-0">Active limited-time discounts, free installation offers, and fleet bundles.</p>
    </div>
</section>

<section class="py-5 bg-dark-surface">
    <div class="container">
        @if($promotions->isEmpty())
            <div class="el-card p-5 text-center">
                <h4 class="text-white fw-bold">No active campaigns right now</h4>
                <p class="text-secondary mb-3">Contact our sales team for today's best battery replacement rates.</p>
                <a href="{{ route('enquiry.create') }}" class="btn btn-amber">Enquire Now</a>
            </div>
        @else
            <div class="row g-4">
                @foreach($promotions as $promotion)
                    <div class="col-md-6 col-lg-4">
                        <x-public.promotion-card :promotion="$promotion" />
                    </div>
                @endforeach
            </div>
            <div class="mt-5 d-flex justify-content-center">
                {{ $promotions->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</section>
@endsection
