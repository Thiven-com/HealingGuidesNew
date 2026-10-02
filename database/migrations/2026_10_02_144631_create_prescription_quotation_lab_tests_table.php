<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescription_quotation_lab_tests', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('prescription_quotation_id')->nullable();

            $table->unsignedBigInteger('lab_test_id')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Lab Test Snapshot
            |--------------------------------------------------------------------------
            */

            $table->string('test_name')->nullable();

            $table->string('test_code')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Price
            |--------------------------------------------------------------------------
            */

            $table->decimal('mrp', 10, 2)->default(0);

            $table->decimal('price', 10, 2)->default(0);

            $table->decimal('total', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_quotation_lab_tests');
    }
};