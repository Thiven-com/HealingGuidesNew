<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_message_options', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('message_id')->nullable();

            $table->string('option_id')->nullable();

            $table->string('title')->nullable();

            $table->string('value')->nullable();

            /*
            |--------------------------------------------------------------------------
            | action examples:
            |
            | next
            | doctor
            | hospital
            | diagnostic
            | medicine
            | ambulance
            | booking
            |--------------------------------------------------------------------------
            */

            $table->string('action')->nullable();

            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_message_options');
    }
};