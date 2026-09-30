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
        Schema::create('health_checkup_package_tests', function (Blueprint $table) {

            $table->id();

            $table->bigInteger('health_checkup_package_id')->nullable();

            $table->bigInteger('health_checkup_test_id')->nullable();

            $table->unsignedInteger('display_order')
                ->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_checkup_package_tests');
    }
};