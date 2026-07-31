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
        Schema::table('doctor_appointments', function (Blueprint $table) {

            // Video Consultation
            $table->string('meeting_provider')->nullable()->after('appointment_status');
            $table->string('meeting_id')->nullable()->after('meeting_provider');
            $table->string('meeting_link')->nullable()->after('meeting_id');
            $table->string('meeting_password')->nullable()->after('meeting_link');
            $table->timestamp('meeting_started_at')->nullable()->after('meeting_password');
            $table->timestamp('meeting_ended_at')->nullable()->after('meeting_started_at');

            // Chat Consultation
            $table->timestamp('chat_started_at')->nullable()->after('meeting_ended_at');
            $table->timestamp('chat_ended_at')->nullable()->after('chat_started_at');

            // Home Visit
            $table->text('visit_address')->nullable()->after('chat_ended_at');
            $table->string('visit_city')->nullable()->after('visit_address');
            $table->string('visit_state')->nullable()->after('visit_city');
            $table->string('visit_pincode', 10)->nullable()->after('visit_state');
            $table->decimal('visit_latitude', 10, 7)->nullable()->after('visit_address');
            $table->decimal('visit_longitude', 10, 7)->nullable()->after('visit_latitude');

            $table->enum('visit_status', [
                'scheduled',
                'accepted',
                'doctor_assigned',
                'on_the_way',
                'arrived',
                'completed',
                'cancelled'
            ])->nullable()->after('visit_longitude');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_appointments', function (Blueprint $table) {

        });
    }
};