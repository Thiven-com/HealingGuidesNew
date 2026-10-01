<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surgeries', function (Blueprint $table) {

            $table->id();

            $table->string('name')->nullable();

            $table->string('slug')->nullable();

            $table->string('image')->nullable();

            $table->string('short_description')->nullable();

            $table->text('description')->nullable();

            $table->string('duration')->nullable();

            $table->string('recovery_time')->nullable();

            $table->text('preparation_instructions')->nullable();

            $table->text('post_surgery_care')->nullable();

            $table->unsignedInteger('display_order')->default(0);

            $table->boolean('status')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surgeries');
    }
};