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
        Schema::table('diagnostic_lab_tests', function (Blueprint $table) {
            $table->boolean('free_ambulances')
                ->default(0)
                ->after('lab_test_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diagnostic_lab_tests', function (Blueprint $table) {
            //
        });
    }
};
