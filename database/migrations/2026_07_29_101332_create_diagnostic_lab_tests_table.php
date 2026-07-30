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
        Schema::create('diagnostic_lab_tests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('diagnostic_id')->nullable();

            $table->bigInteger('lab_test_id')->nullable();

            $table->decimal('price', 10, 2)->nullable();

            $table->decimal('offer_price', 10, 2)->nullable();

            $table->string('report_time')->nullable();

            $table->string('report_time_type')->nullable();

            $table->boolean('home_collection')->default(1);

            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnostic_lab_tests');
    }
};
