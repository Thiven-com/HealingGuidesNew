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
        Schema::create('lab_tests', function (Blueprint $table) {
            $table->id();
            $table->string('test_name')->nullable();

            $table->string('test_code')->nullable();

            $table->string('slug')->nullable();

            $table->text('description')->nullable();

            $table->string('sample_type')->nullable();

            $table->text('preparation')->nullable();

            $table->string('report_time')->nullable();

            $table->string('report_time_type')->default('Hours'); // Hours / Days

            $table->boolean('fasting_required')->default(0);

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
        Schema::dropIfExists('lab_tests');
    }
};
