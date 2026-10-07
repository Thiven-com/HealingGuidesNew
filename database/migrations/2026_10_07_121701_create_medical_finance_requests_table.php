<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('medical_finance_requests', function (Blueprint $table) {
            $table->id();

            $table->string('application_no')->nullable();

            $table->bigInteger('customer_id')->nullable();

            $table->unsignedBigInteger('family_member_id')->nullable();

            $table->bigInteger('finance_provider_id')->nullable();
            $table->string('purpose')->nullable();

            $table->decimal('requested_amount', 15, 2)->nullable();

            $table->unsignedInteger('tenure_months')->nullable();

            $table->decimal('interest_rate', 8, 2)->nullable();
            $table->decimal('processing_fee', 15, 2)->default(0);

            $table->decimal('approved_amount', 15, 2)->nullable();
            $table->decimal('disbursed_amount', 15, 2)->nullable();

            $table->string('cibil_status')->default('pending');

            $table->unsignedInteger('cibil_score')->nullable();

            $table->boolean('consent_given')->default(false);
            $table->timestamp('consent_at')->nullable();

            $table->string('status')->default('draft');

            $table->text('rejection_reason')->nullable();
            $table->text('admin_notes')->nullable();

            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('disbursed_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_finance_requests');
    }
};