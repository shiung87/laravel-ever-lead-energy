<?php

namespace Database\Seeders;

use App\Models\CustomerLead;
use App\Models\DealerApplication;
use App\Models\LeadActivity;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\User;
use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;

class LeadsAndSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['group' => 'general', 'key' => 'site_name', 'value' => 'Everlead Energy', 'type' => 'text', 'label' => 'Brand / Website Name'],
            ['group' => 'general', 'key' => 'site_tagline', 'value' => 'Power Every Journey. Reliable Car Battery Solutions for Every Drive.', 'type' => 'text', 'label' => 'Tagline'],
            ['group' => 'general', 'key' => 'company_registration', 'value' => 'EVERLEAD ENERGY SDN BHD (1648893-U)', 'type' => 'text', 'label' => 'Company Registration'],
            // Contact
            ['group' => 'contact', 'key' => 'contact_phone', 'value' => '012-447 2622', 'type' => 'text', 'label' => 'Primary Phone'],
            ['group' => 'contact', 'key' => 'contact_email', 'value' => 'everleadenergy@gmail.com', 'type' => 'text', 'label' => 'Support Email'],
            ['group' => 'contact', 'key' => 'sales_notification_email', 'value' => 'everleadenergy@gmail.com', 'type' => 'text', 'label' => 'Sales Admin Notification Email'],
            ['group' => 'contact', 'key' => 'dealer_notification_email', 'value' => 'everleadenergy@gmail.com', 'type' => 'text', 'label' => 'Dealer Partnership Email'],
            ['group' => 'contact', 'key' => 'company_address', 'value' => '19-1 Jalan PKNK 1/4, Taman Ria Jaya, 08000 Sungai Petani, Kedah.', 'type' => 'textarea', 'label' => 'Headquarters Address'],
            ['group' => 'contact', 'key' => 'business_hours', 'value' => 'Mon - Sun: 8:00 AM - 10:00 PM (24/7 Emergency Breakdown Support)', 'type' => 'text', 'label' => 'Operating Hours'],
            ['group' => 'contact', 'key' => 'map_embed_url', 'value' => 'https://www.openstreetmap.org/export/embed.html?bbox=100.4850%2C5.6350%2C100.5150%2C5.6650&layer=mapnik', 'type' => 'text', 'label' => 'Map Embed URL'],
            // WhatsApp
            ['group' => 'whatsapp', 'key' => 'whatsapp_sales_number', 'value' => '60124472622', 'type' => 'text', 'label' => 'Customer Sales WhatsApp Number (with country code, no +)'],
            ['group' => 'whatsapp', 'key' => 'whatsapp_dealer_number', 'value' => '60124472622', 'type' => 'text', 'label' => 'Dealer Recruitment WhatsApp Number'],
            ['group' => 'whatsapp', 'key' => 'whatsapp_default_message', 'value' => 'Hello Everlead Energy, I would like to enquire about a car battery.', 'type' => 'textarea', 'label' => 'Default Floating WhatsApp Message'],
            ['group' => 'whatsapp', 'key' => 'whatsapp_enabled', 'value' => '1', 'type' => 'boolean', 'label' => 'Enable Floating WhatsApp Button'],
            // SEO & Analytics
            ['group' => 'seo', 'key' => 'meta_title_default', 'value' => 'Everlead Energy | Premium Car Battery Solutions & Authorized Dealer Network', 'type' => 'text', 'label' => 'Default Meta Title'],
            ['group' => 'seo', 'key' => 'meta_description_default', 'value' => 'Find the right car battery for your vehicle with Everlead Energy. Up to 24 months warranty, fast installation, and lucrative authorized dealer partnerships.', 'type' => 'textarea', 'label' => 'Default Meta Description'],
            ['group' => 'seo', 'key' => 'ga4_measurement_id', 'value' => '', 'type' => 'text', 'label' => 'Google Analytics 4 Measurement ID (G-XXXXXXXXXX)'],
            ['group' => 'seo', 'key' => 'gtm_container_id', 'value' => '', 'type' => 'text', 'label' => 'Google Tag Manager ID (GTM-XXXXXXX)'],
            ['group' => 'seo', 'key' => 'meta_pixel_id', 'value' => '', 'type' => 'text', 'label' => 'Meta Pixel ID'],
            // Popup
            ['group' => 'popup', 'key' => 'promo_popup_enabled', 'value' => '1', 'type' => 'boolean', 'label' => 'Enable Promotional Popup'],
            ['group' => 'popup', 'key' => 'promo_popup_title', 'value' => 'Get Up to RM 60 Trade-In Rebate!', 'type' => 'text', 'label' => 'Popup Title'],
            ['group' => 'popup', 'key' => 'promo_popup_text', 'value' => 'Need a new car battery today? Enjoy free 15-point battery health check & doorstep installation.', 'type' => 'textarea', 'label' => 'Popup Description'],
            ['group' => 'popup', 'key' => 'promo_popup_cta_url', 'value' => '/promotions', 'type' => 'text', 'label' => 'Popup CTA URL'],
            // Social
            ['group' => 'social', 'key' => 'facebook_url', 'value' => 'https://facebook.com/everleadenergy', 'type' => 'text', 'label' => 'Facebook URL'],
            ['group' => 'social', 'key' => 'instagram_url', 'value' => 'https://instagram.com/everleadenergy', 'type' => 'text', 'label' => 'Instagram URL'],
            ['group' => 'social', 'key' => 'tiktok_url', 'value' => 'https://tiktok.com/@everleadenergy', 'type' => 'text', 'label' => 'TikTok URL'],
        ];

        foreach ($settings as $s) {
            WebsiteSetting::updateOrCreate(['key' => $s['key']], $s);
        }
        WebsiteSetting::clearCache();

        if (CustomerLead::count() === 0) {
            $salesExec = User::where('email', 'sales@everleadenergy.com')->first();
            $product = Product::first();
            $promo = Promotion::where('is_featured', true)->first();

            $sampleLeads = [
                [
                    'reference_number' => 'EL-261118-A102B',
                    'full_name' => 'Muhammad Hafiz',
                    'mobile_number' => '012-3849201',
                    'email' => 'hafiz.azman@example.com',
                    'state' => 'Selangor',
                    'city' => 'Shah Alam',
                    'vehicle_brand' => 'Perodua',
                    'vehicle_model' => 'Ativa',
                    'vehicle_year' => '2022',
                    'battery_requirement' => 'Everlead EcoStart M42R EFB',
                    'product_id' => $product?->id,
                    'promotion_id' => $promo?->id,
                    'preferred_service_location' => 'Shah Alam Seksyen 7',
                    'enquiry_type' => 'battery_replacement',
                    'remarks' => 'Need installation today afternoon if available.',
                    'marketing_consent' => true,
                    'status' => 'converted',
                    'assigned_to' => $salesExec?->id,
                    'utm_source' => 'google',
                    'utm_medium' => 'cpc',
                    'utm_campaign' => 'year-end-trade-in',
                    'first_touch_source' => 'google / cpc',
                    'latest_touch_source' => 'google / cpc',
                    'created_at' => now()->subDays(4),
                ],
                [
                    'reference_number' => 'EL-261119-B204C',
                    'full_name' => 'Tan Wei Ming',
                    'mobile_number' => '016-7781209',
                    'email' => 'weiming.tan@example.com',
                    'state' => 'Kuala Lumpur',
                    'city' => 'Cheras',
                    'vehicle_brand' => 'Proton',
                    'vehicle_model' => 'X50',
                    'vehicle_year' => '2021',
                    'battery_requirement' => 'Varta Blue Dynamic DIN55',
                    'enquiry_type' => 'product_enquiry',
                    'remarks' => 'Asking about warranty coverage and trade-in price.',
                    'marketing_consent' => true,
                    'status' => 'quotation_sent',
                    'assigned_to' => $salesExec?->id,
                    'follow_up_date' => now()->addDay(),
                    'utm_source' => 'facebook',
                    'utm_medium' => 'paid_social',
                    'utm_campaign' => 'efb-agm-upgrade',
                    'first_touch_source' => 'facebook / paid_social',
                    'latest_touch_source' => 'facebook / paid_social',
                    'created_at' => now()->subDays(2),
                ],
                [
                    'reference_number' => 'EL-261120-C309D',
                    'full_name' => 'Priya Devi',
                    'mobile_number' => '019-5542100',
                    'email' => 'priya.devi@example.com',
                    'state' => 'Penang',
                    'city' => 'Bayan Lepas',
                    'vehicle_brand' => 'Honda',
                    'vehicle_model' => 'City',
                    'vehicle_year' => '2019',
                    'battery_requirement' => 'Everlead UltraCharge NS40ZL',
                    'enquiry_type' => 'price_enquiry',
                    'remarks' => 'Battery weak during morning start.',
                    'marketing_consent' => true,
                    'status' => 'new',
                    'utm_source' => 'direct',
                    'utm_medium' => 'organic',
                    'first_touch_source' => 'direct',
                    'latest_touch_source' => 'direct',
                    'created_at' => now(),
                ],
            ];

            foreach ($sampleLeads as $leadData) {
                $lead = CustomerLead::create($leadData);
                LeadActivity::create([
                    'customer_lead_id' => $lead->id,
                    'user_id' => $salesExec?->id,
                    'action' => 'created',
                    'new_status' => $lead->status,
                    'notes' => 'Lead seeded into CRM.',
                ]);
            }
        }

        if (DealerApplication::count() === 0) {
            DealerApplication::create([
                'reference_number' => 'DLR-261119-X901A',
                'business_name' => 'Apex Motorworks & Service Sdn Bhd',
                'registration_number' => '202101034512',
                'contact_person' => 'Jason Lee',
                'mobile_number' => '012-9098877',
                'email' => 'jason@apexmotorworks.my',
                'business_address' => 'No. 14, Jalan Industri PBP 3, Taman Perindustrian Pusat Bandar Puchong',
                'state' => 'Selangor',
                'city' => 'Puchong',
                'business_type' => 'Automotive Workshop',
                'years_in_operation' => 6,
                'existing_brands' => 'Century, Amaron',
                'estimated_monthly_volume' => '50 - 100 units / month',
                'number_of_outlets' => 2,
                'partnership_type' => 'Authorized Stockist & Installer',
                'remarks' => 'Looking for competitive AGM & EFB dealer tier.',
                'consent' => true,
                'status' => 'under_review',
            ]);
        }
    }
}
