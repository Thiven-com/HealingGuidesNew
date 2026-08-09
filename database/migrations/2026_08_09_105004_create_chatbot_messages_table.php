<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_messages', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('conversation_id')->nullable();

            /*
            |--------------------------------------------------------------------------
            | customer
            | bot
            | system
            |--------------------------------------------------------------------------
            */

            $table->string('sender')->nullable();

            $table->text('message')->nullable();

            /*
            |--------------------------------------------------------------------------
            | text
            | question
            | answer
            | option
            | recommendation
            | error
            |--------------------------------------------------------------------------
            */

            $table->string('message_type')->default('text')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Optional structured data
            |--------------------------------------------------------------------------
            */

            $table->json('metadata')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_messages');
    }
};