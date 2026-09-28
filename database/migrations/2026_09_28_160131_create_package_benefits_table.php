<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('package_benefits', function (Blueprint $table) {

            $table->id();

            $table->bigInteger('package_id')
                ->nullable();

            $table->string('benefit_type')->nullable();

            $table->string('benefit_name')->nullable();

            $table->unsignedInteger('quantity')->default(1)->nullable();

            $table->text('description')->nullable();

            $table->unsignedInteger('display_order')->default(0)->nullable();

            $table->boolean('status')->default(1)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_benefits');
    }
};