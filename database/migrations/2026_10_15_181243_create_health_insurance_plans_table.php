<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('health_insurance_plans', function (Blueprint $table) {
             /**
             * Run the migrations.
             */
            $table->id();
 
            // Insurance Provider
            $table->foreignId('health_insurance_provider_id')
                ->constrained('health_insurance_providers')
                ->cascadeOnDelete();
 
            // Basic Plan Details
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
 
            // Insurance Coverage
            $table->unsignedInteger('policy_term_years')->nullable();
 
            // Pricing Details
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('discount_price', 12, 2)->default(0);
            $table->decimal('tax_price', 12, 2)->default(0);
 
 
            // Eligibility
            $table->unsignedInteger('minimum_entry_age')->nullable();
            $table->unsignedInteger('maximum_entry_age')->nullable();
 
 
            // Plan Benefits and Conditions
            $table->text('key_benefits')->nullable();
 
            // Display and Status
            $table->boolean('status')->default(1);
 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_insurance_plans');
    }
};
