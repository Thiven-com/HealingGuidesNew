<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('finance_providers', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('code')->nullable();

            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->string('website')->nullable();

            $table->string('contact_name')->nullable();
            $table->string('contact_mobile')->nullable();
            $table->string('contact_email')->nullable();

            $table->decimal('min_amount', 15, 2)->default(0);
            $table->decimal('max_amount', 15, 2)->default(0);

            $table->decimal('interest_rate', 8, 2)->nullable();

            $table->decimal('processing_fee', 15, 2)->default(0);

            $table->unsignedInteger('tenure_min_months')->nullable();
            $table->unsignedInteger('tenure_max_months')->nullable();

            $table->boolean('cibil_required')->default(true);
            $table->boolean('medical_finance')->default(true);

            $table->boolean('hospitalization')->default(true);
            $table->boolean('surgery')->default(true);
            $table->boolean('diagnostics')->default(true);
            $table->boolean('medicines')->default(true);
            $table->boolean('doctor_consultation')->default(true);
            $table->boolean('dental_treatment')->default(true);
            $table->boolean('medical_treatment')->default(true);

            $table->boolean('status')->default(true);
            $table->unsignedInteger('display_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_providers');
    }
};