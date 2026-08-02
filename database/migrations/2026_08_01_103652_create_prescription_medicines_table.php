<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prescription_medicines', function (Blueprint $table) {

            $table->id();

            $table->bigInteger('prescription_id')->nullable();

            $table->string('medicine_name')->nullable();

            $table->string('dosage')->nullable();

            $table->string('frequency')->nullable();

            $table->string('duration')->nullable();

            $table->string('route')->nullable();

            // Dose Schedule
            $table->boolean('morning')->default(false);
            $table->boolean('afternoon')->default(false);
            $table->boolean('night')->default(false);

            // Food Timing
            $table->string('timing')->nullable();

            $table->text('instructions')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_medicines');
    }
};