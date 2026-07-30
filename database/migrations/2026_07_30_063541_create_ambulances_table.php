<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ambulances', function (Blueprint $table) {

            $table->id();

            $table->bigInteger('ambulance_type_id')->nullable();

            $table->bigInteger('hospital_id')->nullable();

            $table->string('ambulance_name')->nullable();

            $table->string('ambulance_code')->nullable();

            $table->string('vehicle_number')->nullable();

            $table->string('registration_number')->nullable();

            $table->string('driver_name')->nullable();

            $table->string('driver_mobile')->nullable();

            $table->string('driver_license_number')->nullable();

            $table->string('driver_photo')->nullable();

            $table->string('model')->nullable();

            $table->year('manufacturing_year')->nullable();

            $table->string('current_location')->nullable();

            $table->decimal('latitude', 10, 7)->nullable();

            $table->decimal('longitude', 10, 7)->nullable();

            $table->decimal('base_fare', 10, 2)->default(0);

            $table->decimal('price_per_km', 10, 2)->default(0);

            $table->boolean('is_available')->default(1);

            $table->boolean('status')->default(1);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ambulances');
    }
};