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
        Schema::create('doctor_appointments', function (Blueprint $table) {

            $table->id();

            $table->string('appointment_no')->unique();

            $table->unsignedBigInteger('doctor_id')->nullable();

            $table->unsignedBigInteger('hospital_id')->nullable();

            $table->unsignedBigInteger('customer_id')->nullable();

            $table->unsignedBigInteger('family_member_id')->nullable();

            $table->unsignedBigInteger('doctor_schedule_id')->nullable();

            $table->date('appointment_date')->nullable();

            $table->time('appointment_time')->nullable();

            $table->string('consultation_type')->nullable();

            $table->integer('token_no')->nullable();

            $table->decimal('consultation_fee', 10, 2)->default(0);

            $table->decimal('discount', 10, 2)->default(0);

            $table->decimal('tax', 10, 2)->default(0);

            $table->decimal('total_amount', 10, 2)->default(0);

            $table->string('payment_status')->default('pending');

            $table->string('appointment_status')->default('pending');

            $table->text('remarks')->nullable();

            $table->text('cancel_reason')->nullable();

            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_appointments');
    }
};