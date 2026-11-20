<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->string('country', 80)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('vehicle_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_brand_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('vehicle_type', 50)->default('Sedan')->index();
            $table->unsignedSmallInteger('year_from')->nullable();
            $table->unsignedSmallInteger('year_to')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->unique(['vehicle_brand_id', 'slug']);
        });

        Schema::create('vehicle_compatibilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_model_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('year_from')->default(2005);
            $table->unsignedSmallInteger('year_to')->default(2027);
            $table->string('engine_capacity', 50)->nullable();
            $table->string('fuel_type', 40)->nullable();
            $table->string('notes')->nullable();
            $table->boolean('is_recommended')->default(true);
            $table->timestamps();

            $table->index(['vehicle_model_id', 'year_from', 'year_to'], 'vc_model_year_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_compatibilities');
        Schema::dropIfExists('vehicle_models');
        Schema::dropIfExists('vehicle_brands');
    }
};
