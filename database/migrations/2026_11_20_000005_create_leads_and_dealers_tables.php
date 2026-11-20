<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_leads', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number', 40)->unique();
            $table->string('full_name');
            $table->string('mobile_number', 30)->index();
            $table->string('email')->nullable();
            $table->string('state', 80)->index();
            $table->string('city', 80);
            $table->string('vehicle_brand', 80)->nullable();
            $table->string('vehicle_model', 100)->nullable();
            $table->string('vehicle_year', 10)->nullable();
            $table->string('battery_requirement', 150)->nullable();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('promotion_id')->nullable()->constrained()->nullOnDelete();
            $table->string('preferred_service_location', 150)->nullable();
            $table->string('enquiry_type', 40)->default('product_enquiry')->index();
            $table->text('remarks')->nullable();
            $table->boolean('marketing_consent')->default(false);
            $table->string('status', 30)->default('new')->index();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->date('follow_up_date')->nullable()->index();
            $table->text('internal_notes')->nullable();
            $table->string('utm_source', 100)->nullable()->index();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 150)->nullable()->index();
            $table->string('utm_content', 150)->nullable();
            $table->string('referrer', 500)->nullable();
            $table->string('landing_page', 500)->nullable();
            $table->string('first_touch_source', 150)->nullable();
            $table->string('latest_touch_source', 150)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('lead_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action', 60);
            $table->string('old_status', 30)->nullable();
            $table->string('new_status', 30)->nullable();
            $table->text('notes')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->timestamps();
        });

        Schema::create('lead_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_to')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('dealer_applications', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number', 40)->unique();
            $table->string('business_name');
            $table->string('registration_number', 80);
            $table->string('contact_person');
            $table->string('mobile_number', 30)->index();
            $table->string('email');
            $table->text('business_address');
            $table->string('state', 80)->index();
            $table->string('city', 80);
            $table->string('business_type', 80);
            $table->unsignedSmallInteger('years_in_operation')->default(1);
            $table->string('existing_brands', 255)->nullable();
            $table->string('estimated_monthly_volume', 80);
            $table->unsignedSmallInteger('number_of_outlets')->default(1);
            $table->string('partnership_type', 80);
            $table->text('remarks')->nullable();
            $table->boolean('consent')->default(false);
            $table->string('status', 30)->default('new')->index();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->date('follow_up_date')->nullable();
            $table->text('internal_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->string('utm_source', 100)->nullable();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 150)->nullable();
            $table->string('landing_page', 500)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('dealer_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dealer_application_id')->constrained()->cascadeOnDelete();
            $table->string('original_name');
            $table->string('file_path');
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('file_size')->default(0);
            $table->timestamps();
        });

        Schema::create('dealer_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dealer_application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action', 60);
            $table->string('old_status', 30)->nullable();
            $table->string('new_status', 30)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('contact_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number', 40)->unique();
            $table->string('name');
            $table->string('mobile_number', 30);
            $table->string('email')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->string('status', 30)->default('new')->index();
            $table->text('admin_notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_enquiries');
        Schema::dropIfExists('dealer_activities');
        Schema::dropIfExists('dealer_documents');
        Schema::dropIfExists('dealer_applications');
        Schema::dropIfExists('lead_assignments');
        Schema::dropIfExists('lead_activities');
        Schema::dropIfExists('customer_leads');
    }
};
