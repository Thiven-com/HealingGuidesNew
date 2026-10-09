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
        Schema::table('hospital_facilities_lists', function (Blueprint $table) {
            $table->decimal('actual_price', 10, 2)
                ->nullable()
                ->after('description');

            $table->decimal('offer_price', 10, 2)
                ->nullable()
                ->after('actual_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospital_facilities_lists', function (Blueprint $table) {
            //
        });
    }
};
