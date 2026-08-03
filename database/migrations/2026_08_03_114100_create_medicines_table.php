<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('hospital_id')->nullable();
            $table->unsignedBigInteger('medicine_category_id')->nullable();

            $table->string('medicine_name')->nullable();

            $table->string('medicine_code')->nullable();

            $table->string('slug')->nullable();

            $table->string('generic_name')->nullable();

            $table->string('brand_name')->nullable();

            $table->string('manufacturer')->nullable();

            $table->string('medicine_type')->nullable();

            $table->string('strength')->nullable();

            $table->string('pack_size')->nullable();

            $table->string('image')->nullable();

            $table->longText('description')->nullable();

            $table->longText('composition')->nullable();

            $table->longText('usage_instructions')->nullable();

            $table->longText('side_effects')->nullable();

            $table->longText('storage_instructions')->nullable();
            $table->decimal('mrp',15,2)->default(0)->nullable();

            $table->decimal('selling_price',15,2)->default(0)->nullable();
            $table->integer('stock_quantity')->default(0)->nullable();
            $table->boolean('prescription_required')->default(false)->nullable();
            $table->boolean('status')->default(true)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};