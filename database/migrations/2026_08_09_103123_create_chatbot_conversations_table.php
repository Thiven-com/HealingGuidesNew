<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_conversations', function (Blueprint $table) {

            $table->id();

            $table->string('conversation_id')->nullable();

            $table->unsignedBigInteger('customer_id')->nullable();

            $table->string('title')->nullable();

            /*
            |--------------------------------------------------------------------------
            | symptom
            | doctor
            | hospital
            | diagnostic
            | medicine
            | ambulance
            | appointment
            | medical_report
            | prescription
            | vitals
            | general
            |--------------------------------------------------------------------------
            */

            $table->string('intent')->nullable();

            /*
            |--------------------------------------------------------------------------
            | active
            | completed
            | cancelled
            |--------------------------------------------------------------------------
            */

            $table->string('status')->default('active')->nullable();

            $table->timestamp('started_at')->nullable();

            $table->timestamp('last_message_at')->nullable();

            $table->timestamp('ended_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_conversations');
    }
};