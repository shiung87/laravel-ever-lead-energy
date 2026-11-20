<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('battery_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code', 30)->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_brand_id')->constrained()->cascadeOnDelete();
            $table->foreignId('battery_type_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('model_number')->index();
            $table->string('sku')->nullable()->unique();
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->unsignedSmallInteger('capacity_ah');
            $table->unsignedSmallInteger('cca');
            $table->string('voltage', 20)->default('12V');
            $table->string('dimensions', 100)->nullable();
            $table->string('terminal_type', 50)->nullable();
            $table->unsignedSmallInteger('warranty_months')->default(12);
            $table->string('warranty_label', 60)->nullable();
            $table->string('price_range', 80)->nullable();
            $table->string('short_description', 500)->nullable();
            $table->longText('description')->nullable();
            $table->json('features')->nullable();
            $table->text('suitable_vehicles_text')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->string('status', 30)->default('active')->index();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('battery_types');
        Schema::dropIfExists('product_brands');
    }
};
