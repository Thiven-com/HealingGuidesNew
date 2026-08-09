<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_intents', function (Blueprint $table) {

            $table->id();

            $table->string('name')->nullable();

            $table->string('slug')->nullable();

            $table->text('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Example:
            |
            | "headache, head pain, pain in head"
            |--------------------------------------------------------------------------
            */

            $table->json('keywords')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Example:
            |
            | doctor
            | hospital
            | diagnostic
            |--------------------------------------------------------------------------
            */

            $table->string('service')->nullable();

            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_intents');
    }
};