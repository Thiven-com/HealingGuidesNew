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
        Schema::create('care_service_bookings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('family_member_id')->nullable();

            $table->unsignedBigInteger('home_visit_service_categories_id')->nullable();

            $table->unsignedBigInteger('home_visit_services_id')->nullable();

            $table->text('reason')->nullable();

            $table->date('preferred_date')->nullable();

            $table->time('preferred_time')->nullable();

            $table->string('country')->nullable();

            $table->string('state')->nullable();

            $table->string('city')->nullable();

            $table->string('pincode', 20)->nullable();

            $table->text('additional_note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('care_service_bookings');
    }
};
