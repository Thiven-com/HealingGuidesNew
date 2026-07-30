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
        Schema::create('postalcodes', function (Blueprint $table) {
            $table->id();
            $table->string('office_name')->nullable();
            $table->string('pincode', 100)->nullable();
            $table->string('taluk')->nullable();
            $table->string('district_name')->nullable();
            $table->string('state_name')->nullable();
            $table->string('country')->nullable()->default("India");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postalcodes');
    }
};
