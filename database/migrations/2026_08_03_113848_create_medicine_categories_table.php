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
        Schema::create('medicine_categories', function (Blueprint $table) {
            $table->id();

            $table->string('category_name')->nullable();

            $table->string('slug')->nullable();

            $table->string('image')->nullable();

            $table->text('description')->nullable();

            $table->integer('sort_order')->default(0)->nullable();

            $table->boolean('status')->default(true)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_categories');
    }
};
