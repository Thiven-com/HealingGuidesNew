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
        Schema::table('hospital_facilities_lists', function (Blueprint $table) {
            $table->unsignedBigInteger('hospital_id')
                ->nullable()
                ->after('id');

            $table->unsignedBigInteger('hospital_facilities_id')
                ->nullable()
                ->after('hospital_id');
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
