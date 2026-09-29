<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_package_benefits', function (Blueprint $table) {

            $table->id();

            $table->bigInteger('customer_id')->nullable();

            $table->bigInteger('package_id')->nullable();

            $table->string('benefit_type')->nullable();

            $table->string('benefit_name')->nullable();

            $table->unsignedInteger('total_quantity')
                ->default(0)->nullable();

            $table->unsignedInteger('used_quantity')
                ->default(0)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_package_benefits');
    }
};