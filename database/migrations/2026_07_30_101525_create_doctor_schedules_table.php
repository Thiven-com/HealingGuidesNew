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
        Schema::create('doctor_schedules', function (Blueprint $table) {

            $table->id();

            $table->bigInteger('doctor_id')->nullable();

            $table->string('day_of_week')->nullable();

            $table->time('available_from')->nullable();

            $table->time('available_to')->nullable();

            $table->integer('slot_duration')->default(15);

            $table->string('consultation_type')->nullable();

            $table->boolean('status')->default(1);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};