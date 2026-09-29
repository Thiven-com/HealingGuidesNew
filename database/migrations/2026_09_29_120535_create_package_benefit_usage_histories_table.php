<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('package_benefit_usage_histories', function (Blueprint $table) {

            $table->id();

            $table->bigInteger('customer_id')
                ->nullable();

            $table->bigInteger('package_id')
                ->nullable();

            $table->bigInteger('package_benefit_id')
                ->nullable();

            $table->string('benefit_type')->nullable();

            $table->string('benefit_name')->nullable();

            $table->unsignedInteger('quantity')->default(1)->nullable();

            $table->string('reference_type')->nullable();

            $table->unsignedBigInteger('reference_id')->nullable();

            $table->string('usage_type')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_benefit_usage_histories');
    }
};
