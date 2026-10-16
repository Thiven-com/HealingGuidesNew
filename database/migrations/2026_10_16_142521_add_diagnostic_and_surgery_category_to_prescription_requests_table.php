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
        Schema::table('prescription_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('diagnostic_id')->nullable()->after('request_type');
            $table->unsignedBigInteger('surgery_category_id')->nullable()->after('diagnostic_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescription_requests', function (Blueprint $table) {
            //
        });
    }
};
