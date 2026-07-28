<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('hospital_id')->nullable();

            $table->unsignedBigInteger('hospital_specialization_id')->nullable();

            $table->string('doctor_name')->nullable();

            $table->string('slug')->nullable();

            $table->string('doctor_code')->nullable();

            $table->string('qualification')->nullable();

            $table->string('designation')->nullable();

            $table->integer('experience')->default(0);

            $table->decimal('consultation_fee',10,2)->default(0);

            $table->string('email')->nullable();

            $table->string('mobile')->nullable();

            $table->date('dob')->nullable();

            $table->enum('gender',['Male','Female','Other'])->nullable();

            $table->string('blood_group')->nullable();

            $table->string('photo')->nullable();

            $table->text('address')->nullable();

            $table->text('about')->nullable();

            $table->time('available_from')->nullable();

            $table->time('available_to')->nullable();

            $table->boolean('status')->default(1);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};