<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_faqs', function (Blueprint $table) {

            $table->id();

            $table->string('question')->nullable();

            $table->text('answer')->nullable();

            $table->json('keywords')->nullable();

            $table->string('category')->nullable();

            $table->boolean('status')->default(true);

            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_faqs');
    }
};