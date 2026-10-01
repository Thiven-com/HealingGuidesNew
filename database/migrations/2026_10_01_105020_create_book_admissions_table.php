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
        Schema::create('book_admissions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('customer_id');

            $table->unsignedBigInteger('family_member_id')->nullable();

            $table->enum('admission_type', [
                'general_admission',
                'surgery_admission'
            ]);

            $table->date('preferred_admission_date')->nullable();

            $table->string('surgery_procedure')->nullable();

            $table->unsignedBigInteger('preferred_doctor_id')->nullable();

            $table->text('additional_information')->nullable();

            $table->string('status')->default('pending');

            $table->timestamps();

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');

            $table->foreign('family_member_id')
                ->references('id')
                ->on('family_members')
                ->onDelete('set null');

            $table->foreign('preferred_doctor_id')
                ->references('id')
                ->on('doctors')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_admissions');
    }
};
