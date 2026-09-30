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
        Schema::create('health_checkup_tests', function (Blueprint $table) {

            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('sample_type')->nullable();
            $table->string('report_time')->nullable();
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
        Schema::dropIfExists('health_checkup_tests');
    }
};