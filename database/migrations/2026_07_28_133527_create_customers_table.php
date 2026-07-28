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
        Schema::create('customers', function (Blueprint $table) {

            $table->id();

            $table->string('customer_code')->unique();

            $table->string('name')->nullable();

            $table->string('mobile')->nullable();

            $table->string('alternate_mobile')->nullable();

            $table->string('email')->nullable();

            $table->string('password')->nullable();

            $table->string('otp')->nullable();

            $table->string('gender')->nullable();

            $table->date('dob')->nullable();

            $table->integer('age')->nullable();

            $table->string('blood_group')->nullable();

            $table->decimal('height', 10, 2)->nullable();

            $table->decimal('weight', 10, 2)->nullable();

            $table->string('occupation')->nullable();

            $table->string('aadhaar_no')->nullable();

            $table->string('photo')->nullable();

            $table->text('address')->nullable();

            $table->string('country')->default('India');

            $table->string('state')->nullable();

            $table->string('city')->nullable();

            $table->string('pincode')->nullable();

            $table->string('emergency_contact_name')->nullable();

            $table->string('emergency_contact_mobile')->nullable();

            $table->boolean('is_verified')->default(1);

            $table->boolean('status')->default(1);

            $table->rememberToken();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
