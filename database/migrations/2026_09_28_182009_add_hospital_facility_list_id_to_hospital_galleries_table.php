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
        Schema::table('hospital_galleries', function (Blueprint $table) {
            $table->unsignedBigInteger('hospital_facility_list_id')
                ->nullable()
                ->after('hospital_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospital_galleries', function (Blueprint $table) {
            //
        });
    }
};
