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
        Schema::table('surgery_quotation_requests', function (Blueprint $table) {
            //
            $table->string('surgery_category')->nullable()->after('surgery_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surgery_quotation_requests', function (Blueprint $table) {
            //
        });
    }
};
