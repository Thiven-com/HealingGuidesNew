<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescription_quotation_medicines', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('prescription_quotation_id')->nullable();

            $table->unsignedBigInteger('medicine_id')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Medicine Snapshot
            |--------------------------------------------------------------------------
            */

            $table->string('medicine_name')->nullable();

            $table->string('medicine_code')->nullable();

            $table->string('strength')->nullable();

            $table->string('pack_size')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Quantity / Price
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('quantity')->default(1);

            $table->decimal('mrp', 10, 2)->default(0);

            $table->decimal('price', 10, 2)->default(0);

            $table->decimal('total', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_quotation_medicines');
    }
};