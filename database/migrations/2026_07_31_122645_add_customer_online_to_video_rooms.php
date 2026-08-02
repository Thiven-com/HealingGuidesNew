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
        Schema::table('video_rooms', function (Blueprint $table) {
            //
            $table->boolean('doctor_online')->default(false)->after('status');
            $table->boolean('customer_online')->default(false)->after('doctor_online');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('video_rooms', function (Blueprint $table) {
            //
        });
    }
};
