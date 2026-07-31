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
        Schema::create('video_rooms', function (Blueprint $table) {

            $table->id();

            $table->bigInteger('appointment_id')->nullable();

            $table->bigInteger('hospital_id')->nullable();

            $table->bigInteger('doctor_id')->nullable();

            $table->bigInteger('customer_id')->nullable();

            $table->string('room_id')->nullable();

            $table->enum('status', [
                'waiting',
                'doctor_joined',
                'customer_joined',
                'live',
                'ended',
                'cancelled'
            ])->default('waiting')->nullable();

            $table->timestamp('doctor_joined_at')->nullable();

            $table->timestamp('customer_joined_at')->nullable();

            $table->timestamp('started_at')->nullable();

            $table->timestamp('ended_at')->nullable();

            $table->integer('duration')->default(0)
                ->comment('Duration in seconds');

            $table->string('ended_by')->nullable()
                ->comment('doctor/customer/system');

            $table->string('end_reason')->nullable();

            $table->boolean('is_recording')->default(false);

            $table->string('recording_path')->nullable();

            $table->string('doctor_ip')->nullable();

            $table->string('customer_ip')->nullable();

            $table->json('metadata')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_rooms');
    }
};