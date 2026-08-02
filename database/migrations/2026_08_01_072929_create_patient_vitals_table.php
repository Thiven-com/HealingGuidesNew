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
        Schema::create('patient_vitals', function (Blueprint $table) {

            $table->id();

            $table->bigInteger('appointment_id')->nullable();
            $table->bigInteger('doctor_id')->nullable();
            $table->bigInteger('customer_id')->nullable();
            $table->bigInteger('family_member_id')->nullable();

            $table->string('blood_pressure')->nullable(); // 120/80

            $table->decimal('blood_sugar', 8, 2)->nullable();
            $table->string('blood_sugar_type')->nullable(); // fasting/random/post_meal

            $table->decimal('height', 6, 2)->nullable(); // cm
            $table->decimal('weight', 6, 2)->nullable(); // kg
            $table->decimal('bmi', 5, 2)->nullable();

            $table->decimal('temperature', 5, 2)->nullable();
            $table->integer('pulse_rate')->nullable();
            $table->integer('spo2')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_vitals');
    }
};
