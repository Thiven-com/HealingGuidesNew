<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_registrations', function (Blueprint $table) {
            $table->id();

            $table->string('full_name')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('email')->nullable();

            $table->date('date_of_birth')->nullable();

            $table->string('gender')->nullable();

            $table->string('city')->nullable();

            // Static membership selected by user
            $table->string('membership')->nullable();

            $table->boolean('status')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_registrations');
    }
};
