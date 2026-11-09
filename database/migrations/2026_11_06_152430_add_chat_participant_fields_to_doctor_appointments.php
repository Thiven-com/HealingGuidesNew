<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('doctor_appointments', function (Blueprint $table) {

            $table->timestamp('customer_chat_joined_at')->nullable()->after('chat_started_at');

            $table->timestamp('doctor_chat_joined_at')->nullable()->after('customer_chat_joined_at');

            $table->timestamp('customer_chat_ended_at')->nullable()->after('doctor_chat_joined_at');

            $table->timestamp('doctor_chat_ended_at')->nullable()->after('customer_chat_ended_at');

            $table->unsignedInteger('chat_duration_minutes')
                ->default(10)->after('doctor_chat_ended_at');

            $table->timestamp('chat_expires_at')->nullable()->after('chat_duration_minutes');

        });
    }

    public function down(): void
    {
        Schema::table('doctor_appointments', function (Blueprint $table) {

            $table->dropColumn([
                'customer_chat_joined_at',
                'doctor_chat_joined_at',
                'customer_chat_ended_at',
                'doctor_chat_ended_at',
                'chat_duration_minutes',
                'chat_expires_at',
            ]);

        });
    }
};