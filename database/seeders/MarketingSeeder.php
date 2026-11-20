<?php

namespace Database\Seeders;

use App\Models\HomepageBanner;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class MarketingSeeder extends Seeder
{
    public function run(): void
    {
        HomepageBanner::updateOrCreate(
            ['title' => 'Power Every Journey.'],
            [
                'subtitle' => 'Reliable Car Battery Solutions for Every Drive. Engineered for tropical heat, backed by up to 24 months direct replacement warranty and rapid doorstep installation.',
                'badge_text' => 'NEXT-GEN AUTOMOTIVE POWER',
                'cta_primary_text' => 'Find Your Battery',
                'cta_primary_url' => '/battery-finder',
                'cta_secondary_text' => 'Become Our Dealer',
                'cta_secondary_url' => '/become-a-dealer',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $promos = [
            [
                'title' => 'Year-End Trade-In & Free Installation Blitz',
                'slug' => 'year-end-trade-in-free-installation',
                'subtitle' => 'Save up to RM 60 when you trade in your old car battery plus complimentary doorstep installation.',
                'badge_text' => 'LIMITED TIME OFFER',
                'promo_type' => 'free_installation',
                'discount_label' => 'UP TO RM 60 OFF + FREE INSTALL',
                'description' => 'Upgrade to an Everlead UltraCharge, EcoStart EFB, or Varta battery this season and enjoy instant trade-in rebate up to RM 60, complimentary 15-point alternator & starter health check, and free doorstep installation within Klang Valley, Penang, and Johor Bahru.',
                'terms_conditions' => "1. Valid for selected passenger and SUV battery models.\n2. Old battery must be surrendered upon installation for trade-in rebate.\n3. Free installation applies within covered service zones.\n4. Cannot be combined with other dealer vouchers.",
                'starts_at' => now()->subDays(5),
                'ends_at' => now()->addDays(25),
                'cta_text' => 'Claim RM 60 Rebate',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'EFB & AGM Start-Stop Upgrade Campaign',
                'slug' => 'efb-agm-start-stop-upgrade',
                'subtitle' => '15% Off on all Varta AGM and Everlead EFB Start-Stop batteries with 24-Month Warranty.',
                'badge_text' => 'START-STOP SPECIAL',
                'promo_type' => 'discount',
                'discount_label' => '15% OFF EFB & AGM',
                'description' => 'Protect your vehicle’s sensitive Start-Stop electronics and regenerative braking system. Enjoy 15% off our entire EFB and AGM range plus complimentary OBD-II battery registration for Continental vehicles.',
                'terms_conditions' => "1. Applicable to Everlead EcoStart, ProDrive Q85, and Varta AGM series.\n2. Includes complimentary battery diagnostic report.\n3. Valid while promotional stocks last.",
                'starts_at' => now()->subDays(2),
                'ends_at' => now()->addDays(40),
                'cta_text' => 'Enquire Start-Stop Offer',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Workshop & Fleet Twin-Pack Bundle',
                'slug' => 'workshop-fleet-twin-pack-bundle',
                'subtitle' => 'Special bundle pricing for commercial pickups, vans, and corporate fleet operators.',
                'badge_text' => 'FLEET BUNDLE',
                'promo_type' => 'bundle',
                'discount_label' => 'BUNDLE SAVE 18%',
                'description' => 'Keep your delivery vans and 4x4 pickups moving with zero downtime. Order 2 or more Century Marathoner Max or Bosch MegaPower batteries and receive priority fleet support and 18% bundle savings.',
                'terms_conditions' => "1. Minimum order of 2 units per transaction.\n2. Valid for registered businesses and fleet owners.\n3. Priority on-site replacement available.",
                'starts_at' => now()->subDays(10),
                'ends_at' => now()->addDays(50),
                'cta_text' => 'Request Fleet Quote',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Past Merdeka Roadtrip Promo (Expired Demo)',
                'slug' => 'past-merdeka-roadtrip-promo',
                'subtitle' => 'Historical campaign automatically hidden from active listings.',
                'badge_text' => 'EXPIRED',
                'promo_type' => 'seasonal',
                'discount_label' => 'RM 40 OFF',
                'description' => 'This campaign has ended and is kept in the database to verify automatic expiry filtering.',
                'terms_conditions' => 'Expired campaign.',
                'starts_at' => now()->subDays(60),
                'ends_at' => now()->subDays(10),
                'cta_text' => 'Expired',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 99,
            ],
        ];

        $productIds = Product::pluck('id')->take(4)->toArray();

        foreach ($promos as $promoData) {
            $promo = Promotion::updateOrCreate(['slug' => $promoData['slug']], $promoData);
            if ($promo->products()->count() === 0 && ! empty($productIds)) {
                $promo->products()->sync($productIds);
            }
        }

        $testimonials = [
            [
                'customer_name' => 'Farid Razak',
                'customer_role' => 'Honda Civic Owner',
                'vehicle_info' => 'Honda Civic 1.5 TC-P',
                'city' => 'Subang Jaya, Selangor',
                'rating' => 5,
                'quote' => 'My car wouldn’t start before a morning meeting. Submitted an enquiry online and the Everlead technician arrived in 35 minutes with an Amaron NS60L, tested my alternator, and had me back on the road!',
                'sort_order' => 1,
            ],
            [
                'customer_name' => 'Kenneth Wong',
                'customer_role' => 'Authorized Workshop Partner',
                'vehicle_info' => 'KW Auto Care & Tyres',
                'city' => 'George Town, Penang',
                'rating' => 5,
                'quote' => 'Partnering with Everlead Energy boosted our workshop battery margins by 25%. Their stock availability, EFB/AGM technical training, and fast warranty turnaround keep our customers coming back.',
                'sort_order' => 2,
            ],
            [
                'customer_name' => 'Nurul Huda',
                'customer_role' => 'Perodua Ativa Owner',
                'vehicle_info' => 'Perodua Ativa AV',
                'city' => 'Johor Bahru, Johor',
                'rating' => 5,
                'quote' => 'The Battery Finder matched the exact EcoStart M42R EFB for my Ativa in seconds. Transparent pricing, polite installer, and I received my warranty confirmation right away.',
                'sort_order' => 3,
            ],
            [
                'customer_name' => 'Suresh Nair',
                'customer_role' => 'BMW 330i Owner',
                'vehicle_info' => 'BMW G20 330i',
                'city' => 'Mont Kiara, Kuala Lumpur',
                'rating' => 5,
                'quote' => 'Finding a genuine Varta Silver Dynamic AGM with proper OBD battery registration used to be a hassle. Everlead handled everything professionally at my condo carpark.',
                'sort_order' => 4,
            ],
        ];

        foreach ($testimonials as $t) {
            Testimonial::updateOrCreate(
                ['customer_name' => $t['customer_name']],
                array_merge($t, ['is_featured' => true, 'is_active' => true])
            );
        }
    }
}
