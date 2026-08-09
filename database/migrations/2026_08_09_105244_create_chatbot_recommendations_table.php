<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_recommendations', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('conversation_id')->nullable();

            $table->unsignedBigInteger('customer_id')->nullable();

            $table->string('recommendation_type')->nullable();

            $table->unsignedBigInteger('reference_id')->nullable();

            $table->string('title')->nullable();

            $table->text('description')->nullable();

            $table->json('metadata')->nullable();

            $table->integer('sort_order')->default(0);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_recommendations');
    }
};