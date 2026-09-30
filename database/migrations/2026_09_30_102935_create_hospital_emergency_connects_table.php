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
        Schema::create('hospital_emergency_connects', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('hospital_id');

            $table->string('name');

            $table->string('image')->nullable();

            $table->string('designation')->nullable();

            $table->string('department')->nullable();

            $table->string('whatsapp_number')->nullable();

            $table->string('contact_number')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_emergency_connects');
    }
};
