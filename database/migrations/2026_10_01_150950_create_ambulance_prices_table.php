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
        Schema::create('ambulance_prices', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('ambulance_id')->nullable();

            $table->enum('trip_type', [
                'local',
                'outstation'
            ])->nullable();

            $table->decimal('max_distance_km', 10, 2)->nullable();

            $table->decimal('amount', 10, 2)->default(0)->nullable();

            $table->boolean('status')->default(1)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ambulance_prices');
    }
};
