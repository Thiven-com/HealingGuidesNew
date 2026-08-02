<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {

            $table->id();

            $table->bigInteger('appointment_id')->nullable();

            $table->bigInteger('doctor_id')->nullable();

            $table->bigInteger('customer_id')->nullable();

            $table->bigInteger('family_member_id')->nullable();

            $table->text('symptoms')->nullable();

            $table->text('diagnosis')->nullable();

            $table->text('clinical_notes')->nullable();

            $table->text('advice')->nullable();

            $table->text('tests_recommended')->nullable();

            $table->date('followup_date')->nullable();

            $table->text('followup_notes')->nullable();

            $table->boolean('status')->default(1);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};