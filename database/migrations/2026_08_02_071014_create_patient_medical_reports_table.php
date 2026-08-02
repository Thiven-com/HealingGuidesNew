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
        Schema::create('patient_medical_reports', function (Blueprint $table) {

            $table->id();

            $table->bigInteger('customer_id')->nullable();

            $table->bigInteger('family_member_id')->nullable();

            $table->bigInteger('appointment_id')->nullable();

            $table->bigInteger('doctor_id')->nullable();

            $table->string('report_type')->nullable();

            $table->string('report_name')->nullable();

            $table->string('report_file')->nullable();

            $table->date('report_date')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_medical_reports');
    }
};
