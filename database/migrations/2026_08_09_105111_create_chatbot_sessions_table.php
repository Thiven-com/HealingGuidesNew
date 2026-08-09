<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_sessions', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('conversation_id')->nullable();

            $table->unsignedBigInteger('customer_id')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Current chatbot intent
            |--------------------------------------------------------------------------
            */

            $table->string('intent')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Current flow step
            |--------------------------------------------------------------------------
            */

            $table->string('current_step')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Example:
            | ["headache", "fever"]
            |--------------------------------------------------------------------------
            */

            $table->json('symptoms')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Example:
            |
            | {
            |     "location": "head",
            |     "duration": "2 days",
            |     "severity": "moderate"
            | }
            |--------------------------------------------------------------------------
            */

            $table->json('answers')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Selected service
            |--------------------------------------------------------------------------
            */

            $table->string('selected_service')->nullable();

            $table->unsignedBigInteger('selected_id')->nullable();

            /*
            |--------------------------------------------------------------------------
            | active
            | completed
            | cancelled
            |--------------------------------------------------------------------------
            */

            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_sessions');
    }
};