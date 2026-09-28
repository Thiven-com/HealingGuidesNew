<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {

            $table->id();

            $table->string('name')->nullable();

            $table->string('slug')->nullable();

            $table->text('description')->nullable();

            $table->decimal('price', 10, 2)->default(0)->nullable();

            $table->unsignedInteger('duration_days')->default(365)->nullable();

            $table->string('image')->nullable();

            $table->unsignedInteger('display_order')->default(0)->nullable();

            $table->boolean('status')->default(1)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};