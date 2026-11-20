<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();
            $table->string('badge_text', 60)->nullable();
            $table->string('promo_type', 40)->default('discount')->index();
            $table->string('discount_label', 80)->nullable();
            $table->longText('description')->nullable();
            $table->longText('terms_conditions')->nullable();
            $table->string('banner_image')->nullable();
            $table->timestamp('starts_at')->nullable()->index();
            $table->timestamp('ends_at')->nullable()->index();
            $table->string('cta_text', 80)->default('Claim This Offer');
            $table->string('cta_url')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('promotion_products', function (Blueprint $table) {
            $table->foreignId('promotion_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->primary(['promotion_id', 'product_id']);
        });

        Schema::create('homepage_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle', 500)->nullable();
            $table->string('badge_text', 80)->nullable();
            $table->string('image')->nullable();
            $table->string('mobile_image')->nullable();
            $table->string('cta_primary_text', 80)->nullable();
            $table->string('cta_primary_url')->nullable();
            $table->string('cta_secondary_text', 80)->nullable();
            $table->string('cta_secondary_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_role', 100)->nullable();
            $table->string('vehicle_info', 120)->nullable();
            $table->string('city', 80)->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('quote');
            $table->string('avatar')->nullable();
            $table->boolean('is_featured')->default(true)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('homepage_banners');
        Schema::dropIfExists('promotion_products');
        Schema::dropIfExists('promotions');
    }
};
