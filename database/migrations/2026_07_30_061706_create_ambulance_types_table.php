<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ambulance_types', function (Blueprint $table) {

            $table->id();

            $table->string('ambulance_type_name')->nullable();
            $table->string('ambulance_type_code')->nullable();
            $table->string('slug')->nullable();

            $table->text('description')->nullable();

            $table->string('image')->nullable();

            $table->boolean('status')->default(1);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ambulance_types');
    }
};