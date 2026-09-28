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
        Schema::table('hospital_specializations', function (Blueprint $table) {
            $table->bigInteger('specialization_category_id')
                ->nullable()
                ->after('hospital_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospital_specializations', function (Blueprint $table) {
            //
        });
    }
};
