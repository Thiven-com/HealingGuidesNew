<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('f_c_m_tokens', function (Blueprint $table) {
            $table->id();
            $table->longText('token')->nullable();
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->string('now_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('f_c_m_tokens');
    }
};
