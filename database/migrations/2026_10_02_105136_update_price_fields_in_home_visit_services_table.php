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
        Schema::table('home_visit_services', function (Blueprint $table) {
            // Change price_per to store hour/day
            $table->enum('price_per', ['hour', 'day'])
                ->default('hour')
                ->change();

            // Add actual price
            $table->decimal('price', 10, 2)
                ->nullable()
                ->after('price_per');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_visit_services', function (Blueprint $table) {
            //
        });
    }
};
