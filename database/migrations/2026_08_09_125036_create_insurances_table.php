<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insurances', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('customer_id')->nullable();

            $table->string('insurance_provider')->nullable();
            $table->string('policy_number')->nullable();

            $table->string('policy_type')->nullable();

            $table->string('policy_holder_name')->nullable();

            $table->string('member_id')->nullable();

            $table->decimal('coverage_amount', 12, 2)->nullable();

            $table->date('start_date')->nullable();
            $table->date('expiry_date')->nullable();

            $table->string('document')->nullable();

            $table->text('notes')->nullable();

            $table->enum('status', ['active','expired','inactive'])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insurances');
    }
};