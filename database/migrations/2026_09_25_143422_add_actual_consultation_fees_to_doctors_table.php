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
            $table->decimal('actual_video_consultation_fee', 10, 2)
                ->nullable()
                ->after('video_consultation_fee');

            $table->decimal('actual_chat_consultation_fee', 10, 2)
                ->nullable()
                ->after('chat_consultation_fee');

            $table->decimal('actual_home_visit_fee', 10, 2)
                ->nullable()
                ->after('home_visit_fee');
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
