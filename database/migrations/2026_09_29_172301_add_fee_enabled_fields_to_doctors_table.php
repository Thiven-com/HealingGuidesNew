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
        Schema::table('doctors', function (Blueprint $table) {
             $table->boolean('consultation_enabled')
                ->default(1)
                ->after('actual_fee');

            $table->boolean('video_consultation_enabled')
                ->default(1)
                ->after('actual_video_consultation_fee');

            $table->boolean('chat_consultation_enabled')
                ->default(1)
                ->after('actual_chat_consultation_fee');

            $table->boolean('home_visit_enabled')
                ->default(1)
                ->after('actual_home_visit_fee');
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
