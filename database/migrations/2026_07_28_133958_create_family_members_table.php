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
        Schema::create('family_members', function (Blueprint $table) {

            $table->id();

            $table->bigInteger('customer_id')->nullable();

            $table->string('name')->nullable();

            $table->string('relationship')->nullable();

            $table->string('gender')->nullable();

            $table->date('dob')->nullable();

            $table->integer('age')->nullable();

            $table->string('blood_group')->nullable();

            $table->decimal('height', 10, 2)->nullable();

            $table->decimal('weight', 10, 2)->nullable();

            $table->string('occupation')->nullable();

            $table->string('aadhaar_no')->nullable();

            $table->string('photo')->nullable();

            $table->boolean('status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_members');
    }
};
