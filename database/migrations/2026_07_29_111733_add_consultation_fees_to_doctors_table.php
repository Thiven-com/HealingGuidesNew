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
        Schema::table('doctors', function (Blueprint $table) {
            //
            $table->decimal('video_consultation_fee', 10, 2)->nullable()->after('consultation_fee');
            $table->decimal('chat_consultation_fee', 10, 2)->nullable()->after('video_consultation_fee');
            $table->decimal('home_visit_fee', 10, 2)->nullable()->after('chat_consultation_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            //
        });
    }
};
