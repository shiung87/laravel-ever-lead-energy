<?php

namespace Tests\Feature;

use App\Models\CustomerLead;
use App\Models\DealerApplication;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\User;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EverleadPlatformTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_public_pages_load_successfully(): void
    {
        $product = Product::active()->firstOrFail();
        $promotion = Promotion::active()->firstOrFail();

        $this->get('/')->assertStatus(200)->assertSee('Power Every Journey.');
        $this->get('/products')->assertStatus(200)->assertSee($product->name);
        $this->get('/products/' . $product->slug)->assertStatus(200)->assertSee($product->model_number);
        $this->get('/battery-finder')->assertStatus(200)->assertSee('Find the Right Battery');
        $this->get('/promotions')->assertStatus(200)->assertSee($promotion->title);
        $this->get('/promotions/' . $promotion->slug)->assertStatus(200);
        $this->get('/enquiry')->assertStatus(200);
        $this->get('/become-a-dealer')->assertStatus(200)->assertSee('Grow Your Business. Become Our Authorized Dealer.');
        $this->get('/contact')->assertStatus(200);
        $this->get('/sitemap.xml')->assertStatus(200);
        $this->get('/robots.txt')->assertStatus(200);
    }

    public function test_expired_promotions_are_hidden_and_return_404(): void
    {
        $expired = Promotion::where('ends_at', '<', now())->firstOrFail();

        $this->get('/promotions')->assertStatus(200)->assertDontSee($expired->title);
        $this->get('/promotions/' . $expired->slug)->assertStatus(404);
    }

    public function test_battery_finder_returns_compatible_products(): void
    {
        $brand = VehicleBrand::where('name', 'Perodua')->firstOrFail();
        $model = VehicleModel::where('vehicle_brand_id', $brand->id)->where('name', 'Ativa')->firstOrFail();

        $this->getJson("/api/vehicle-brands/{$brand->id}/models")
            ->assertStatus(200)
            ->assertJsonFragment(['name' => 'Ativa']);

        $this->get("/battery-finder?vehicle_brand_id={$brand->id}&vehicle_model_id={$model->id}&year=2023")
            ->assertStatus(200)
            ->assertSee('Everlead EcoStart M42R EFB');
    }

    public function test_customer_lead_submission_saves_attribution_and_redirects(): void
    {
        $response = $this->withSession([
            'attribution' => [
                'utm_source' => 'google',
                'utm_medium' => 'cpc',
                'utm_campaign' => 'year-end-promo',
                'first_touch_source' => 'google / cpc',
                'latest_touch_source' => 'google / cpc',
            ],
        ])->post('/enquiry', [
            'full_name' => 'Lim Kah Seng',
            'mobile_number' => '012-9988776',
            'email' => 'kahseng@example.com',
            'state' => 'Selangor',
            'city' => 'Subang Jaya',
            'vehicle_brand' => 'Honda',
            'vehicle_model' => 'City',
            'vehicle_year' => '2020',
            'enquiry_type' => 'battery_replacement',
            'marketing_consent' => '1',
            'captcha_expected' => 10,
            'captcha_answer' => 10,
        ]);

        $lead = CustomerLead::where('full_name', 'Lim Kah Seng')->first();
        $this->assertNotNull($lead);
        $this->assertEquals('google', $lead->utm_source);
        $this->assertEquals('year-end-promo', $lead->utm_campaign);
        $response->assertRedirect(route('enquiry.thank-you', ['ref' => $lead->reference_number]));
    }

    public function test_honeypot_blocks_bot_lead_submission(): void
    {
        $initialCount = CustomerLead::count();

        $this->post('/enquiry', [
            'website_url' => 'http://spam-bot.example.com',
            'full_name' => 'Spam Bot',
            'mobile_number' => '00000000',
            'state' => 'Selangor',
            'city' => 'KL',
            'enquiry_type' => 'general_enquiry',
            'captcha_expected' => 5,
            'captcha_answer' => 5,
        ])->assertRedirect(route('enquiry.create'));

        $this->assertEquals($initialCount, CustomerLead::count());
    }

    public function test_dealer_application_with_document_upload(): void
    {
        Storage::fake('local');

        $response = $this->post('/become-a-dealer', [
            'business_name' => 'Bintang Auto Battery Sdn Bhd',
            'registration_number' => '202201099887',
            'contact_person' => 'Encik Razali',
            'mobile_number' => '013-4455667',
            'email' => 'razali@bintangauto.my',
            'business_address' => 'No 10 Jalan Utama',
            'state' => 'Johor',
            'city' => 'Johor Bahru',
            'business_type' => 'Automotive Workshop',
            'years_in_operation' => 5,
            'estimated_monthly_volume' => '51 - 100 units / month',
            'number_of_outlets' => 1,
            'partnership_type' => 'Authorized Workshop Installer',
            'documents' => [UploadedFile::fake()->create('ssm-cert.pdf', 200, 'application/pdf')],
            'consent' => '1',
            'captcha_expected' => 8,
            'captcha_answer' => 8,
        ]);

        $app = DealerApplication::where('business_name', 'Bintang Auto Battery Sdn Bhd')->first();
        $this->assertNotNull($app);
        $this->assertCount(1, $app->documents);
        $response->assertRedirect(route('dealer.thank-you', ['ref' => $app->reference_number]));
    }

    public function test_admin_rbac_enforcement(): void
    {
        $this->get('/admin')->assertRedirect('/login');

        $salesExec = User::where('email', 'sales@everleadenergy.com')->firstOrFail();
        $this->actingAs($salesExec)->get('/admin')->assertStatus(200);
        $this->actingAs($salesExec)->get('/admin/leads')->assertStatus(200);
        $this->actingAs($salesExec)->get('/admin/users')->assertStatus(403);
        $this->actingAs($salesExec)->get('/admin/settings')->assertStatus(403);

        $superAdmin = User::where('email', 'superadmin@everleadenergy.com')->firstOrFail();
        $this->actingAs($superAdmin)->get('/admin/users')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/admin/settings')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/admin/audit-logs')->assertStatus(200);
    }
}
