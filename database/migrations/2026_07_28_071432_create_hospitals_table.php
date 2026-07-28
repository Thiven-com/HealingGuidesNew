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
        Schema::create('hospitals', function (Blueprint $table) {

            $table->id();

            $table->string('hospital_name')->nullable();
            $table->string('hospital_slug')->nullable();

            $table->string('hospital_code')->nullable();
            $table->string('hospital_type')->nullable();

            $table->string('registration_number')->nullable();

            $table->string('gst_number')->nullable();

            $table->string('pan_number')->nullable();

            $table->string('email')->nullable();

            $table->string('mobile')->nullable();

            $table->string('phone')->nullable();

            $table->string('logo')->nullable();

            $table->string('banner')->nullable();

            $table->text('address')->nullable();

            $table->string('country')->nullable();

            $table->string('state')->nullable();

            $table->string('city')->nullable();

            $table->string('pincode')->nullable();

            $table->decimal('latitude', 10, 7)->nullable();

            $table->decimal('longitude', 10, 7)->nullable();

            $table->time('opening_time')->nullable();

            $table->time('closing_time')->nullable();

            $table->boolean('emergency_available')->default(1);

            $table->boolean('status')->default(1);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospitals');
    }
};
