<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ContactEnquiryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DealerController as AdminDealerController;
use App\Http\Controllers\Admin\LeadController as AdminLeadController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\Admin\VehicleCompatibilityController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Public\BatteryFinderController;
use App\Http\Controllers\Public\DealerController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\LeadController;
use App\Http\Controllers\Public\ProductController;
use App\Http\Controllers\Public\PromotionController;
use Illuminate\Support\Facades\Route;

// Public Website Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/battery-finder', [BatteryFinderController::class, 'index'])->name('battery-finder');
Route::get('/api/vehicle-brands/{vehicleBrand}/models', [BatteryFinderController::class, 'modelsByBrand'])->name('api.vehicle-models');

Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions.index');
Route::get('/promotions/{promotion:slug}', [PromotionController::class, 'show'])->name('promotions.show');

Route::get('/enquiry', [LeadController::class, 'create'])->name('enquiry.create');
Route::post('/enquiry', [LeadController::class, 'store'])->middleware('throttle:10,1')->name('enquiry.store');
Route::get('/enquiry/thank-you', [LeadController::class, 'thankYou'])->name('enquiry.thank-you');

Route::get('/become-a-dealer', [DealerController::class, 'index'])->name('dealer.index');
Route::post('/become-a-dealer', [DealerController::class, 'store'])->middleware('throttle:8,1')->name('dealer.store');
Route::get('/become-a-dealer/thank-you', [DealerController::class, 'thankYou'])->name('dealer.thank-you');

Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'storeContact'])->middleware('throttle:10,1')->name('contact.store');

Route::post('/whatsapp-track', [HomeController::class, 'trackWhatsapp'])->middleware('throttle:30,1')->name('whatsapp.track');
Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [HomeController::class, 'robots'])->name('robots');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Admin Dashboard Routes (Backend RBAC Enforced)
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])
        ->middleware('permission:dashboard.view')
        ->name('dashboard');

    // Customer Leads
    Route::middleware('permission:leads.view')->group(function () {
        Route::get('/leads', [AdminLeadController::class, 'index'])->name('leads.index');
        Route::get('/leads/export', [AdminLeadController::class, 'export'])->middleware('permission:leads.export')->name('leads.export');
        Route::get('/leads/{lead}', [AdminLeadController::class, 'show'])->name('leads.show');
        Route::post('/leads/{lead}/status', [AdminLeadController::class, 'updateStatus'])->middleware('permission:leads.manage')->name('leads.status');
        Route::post('/leads/{lead}/assign', [AdminLeadController::class, 'assign'])->middleware('permission:leads.assign')->name('leads.assign');
    });

    // Dealer Applications
    Route::middleware('permission:dealers.view')->group(function () {
        Route::get('/dealers', [AdminDealerController::class, 'index'])->name('dealers.index');
        Route::get('/dealers/export', [AdminDealerController::class, 'export'])->middleware('permission:dealers.export')->name('dealers.export');
        Route::get('/dealers/{dealer}', [AdminDealerController::class, 'show'])->name('dealers.show');
        Route::post('/dealers/{dealer}', [AdminDealerController::class, 'update'])->middleware('permission:dealers.manage')->name('dealers.update');
        Route::get('/dealers/{dealer}/documents/{document}', [AdminDealerController::class, 'downloadDocument'])->name('dealers.document');
    });

    // Products
    Route::middleware('permission:products.manage')->group(function () {
        Route::resource('/products', AdminProductController::class)->except(['show']);
    });

    // Brands & Battery Types
    Route::middleware('permission:brands.manage')->group(function () {
        Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
        Route::post('/brands', [BrandController::class, 'storeBrand'])->name('brands.store');
        Route::delete('/brands/{brand}', [BrandController::class, 'destroyBrand'])->name('brands.destroy');
        Route::post('/battery-types', [BrandController::class, 'storeBatteryType'])->name('battery-types.store');
        Route::delete('/battery-types/{batteryType}', [BrandController::class, 'destroyBatteryType'])->name('battery-types.destroy');
    });

    // Vehicle Compatibility
    Route::middleware('permission:vehicles.manage')->group(function () {
        Route::get('/vehicles', [VehicleCompatibilityController::class, 'index'])->name('vehicles.index');
        Route::post('/vehicles/brands', [VehicleCompatibilityController::class, 'storeBrand'])->name('vehicles.brands.store');
        Route::post('/vehicles/models', [VehicleCompatibilityController::class, 'storeModel'])->name('vehicles.models.store');
        Route::post('/vehicles/compatibilities', [VehicleCompatibilityController::class, 'storeCompatibility'])->name('vehicles.compatibilities.store');
        Route::delete('/vehicles/compatibilities/{compatibility}', [VehicleCompatibilityController::class, 'destroyCompatibility'])->name('vehicles.compatibilities.destroy');
    });

    // Promotions & Campaigns
    Route::middleware('permission:promotions.manage')->group(function () {
        Route::resource('/promotions', AdminPromotionController::class)->except(['show']);
    });

    // Homepage Banners
    Route::middleware('permission:banners.manage')->group(function () {
        Route::get('/banners', [BannerController::class, 'index'])->name('banners.index');
        Route::post('/banners', [BannerController::class, 'store'])->name('banners.store');
        Route::post('/banners/{banner}/toggle', [BannerController::class, 'toggle'])->name('banners.toggle');
        Route::delete('/banners/{banner}', [BannerController::class, 'destroy'])->name('banners.destroy');
    });

    // Testimonials
    Route::middleware('permission:testimonials.manage')->group(function () {
        Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
        Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
        Route::delete('/testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');
    });

    // Contact Enquiries
    Route::middleware('permission:enquiries.manage')->group(function () {
        Route::get('/enquiries', [ContactEnquiryController::class, 'index'])->name('enquiries.index');
        Route::post('/enquiries/{enquiry}', [ContactEnquiryController::class, 'update'])->name('enquiries.update');
    });

    // Website, WhatsApp & SEO Settings
    Route::middleware('permission:settings.manage')->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

    // Users & Roles
    Route::middleware('permission:users.manage')->group(function () {
        Route::get('/users', [UserRoleController::class, 'index'])->name('users.index');
        Route::post('/users', [UserRoleController::class, 'storeUser'])->name('users.store');
        Route::post('/users/{user}/toggle', [UserRoleController::class, 'toggleUser'])->name('users.toggle');
        Route::post('/roles/{role}/permissions', [UserRoleController::class, 'updateRolePermissions'])->name('roles.permissions');
    });

    // Audit Logs
    Route::get('/audit-logs', [AuditLogController::class, 'index'])
        ->middleware('permission:audit.view')
        ->name('audit.index');
});
