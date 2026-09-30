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
        Schema::create('health_checkup_packages', function (Blueprint $table) {

            $table->id();
            $table->bigInteger('health_checkup_id')
                ->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('image')->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('total_tests')
                ->default(0);
            $table->decimal('mrp', 10, 2)
                ->default(0);
            $table->decimal('price', 10, 2)
                ->default(0);
            $table->decimal('discount_percentage', 5, 2)
                ->default(0);

            $table->boolean('home_collection')
                ->default(false);

            $table->boolean('centre_collection')
                ->default(true);

            $table->string('report_delivery')
                ->nullable();

            $table->boolean('fasting_required')
                ->default(false);
            $table->text('preparation_instructions')
                ->nullable();

            $table->unsignedInteger('display_order')
                ->default(0);

            $table->boolean('status')
                ->default(1);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_checkup_packages');
    }
};