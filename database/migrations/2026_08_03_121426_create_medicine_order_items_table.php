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
        Schema::create('medicine_order_items', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('medicine_order_id')->nullable();

            $table->unsignedBigInteger('medicine_id')->nullable();

            $table->string('medicine_name')->nullable();

            $table->string('medicine_code')->nullable();

            $table->string('strength')->nullable();

            $table->string('pack_size')->nullable();

            $table->integer('quantity')->nullable();

            $table->decimal('mrp', 15, 2)->nullable();

            $table->decimal('price', 15, 2)->nullable();

            $table->decimal('total', 15, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_order_items');
    }
};
