<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('marketing_staff', function (Blueprint $table) {

            $table->id();
            $table->string('name')->nullable();

            $table->string('employee_code')->nullable();

            $table->string('mobile')->nullable();

            $table->string('email')->nullable();

            $table->string('password')->nullable();

            $table->string('otp')->nullable();

            $table->date('dob')->nullable();

            $table->string('gender')->nullable();

            $table->string('photo')->nullable();

            $table->text('address')->nullable();

            $table->string('country')->nullable()->default('India');

            $table->string('state')->nullable();

            $table->string('city')->nullable();

            $table->string('pincode')->nullable();

            $table->string('designation')->nullable();

            $table->date('joining_date')->nullable();

            $table->decimal('latitude',10,7)->nullable();

            $table->decimal('longitude',10,7)->nullable();

            $table->boolean('is_available')->default(1)->nullable();

            $table->timestamp('last_login_at')->nullable();

            $table->boolean('status')->default(1)->nullable();

            $table->rememberToken();

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('marketing_staff');
    }
};