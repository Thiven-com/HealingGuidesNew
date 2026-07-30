<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('diagnostics', function (Blueprint $table) {

            $table->id();

            $table->string('diagnostic_name')->nullable();

            $table->string('diagnostic_code')->nullable();

            $table->string('slug')->nullable();

            $table->string('registration_number')->nullable();

            $table->string('email')->nullable();

            $table->string('mobile')->nullable();

            $table->string('phone')->nullable();

            $table->text('address')->nullable();

            $table->string('country')->nullable()->default('India');

            $table->string('state')->nullable();

            $table->string('city')->nullable();

            $table->string('pincode')->nullable();

            $table->decimal('latitude', 10, 7)->nullable();

            $table->decimal('longitude', 10, 7)->nullable();

            $table->time('opening_time')->nullable();

            $table->time('closing_time')->nullable();

            $table->boolean('home_collection')->default(0);

            $table->string('logo')->nullable();

            $table->string('banner')->nullable();

            $table->boolean('status')->default(1);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diagnostics');
    }
};