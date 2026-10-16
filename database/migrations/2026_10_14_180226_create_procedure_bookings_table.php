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
        Schema::create('procedure_bookings', function (Blueprint $table) {
            $table->id();

            $table->string('booking_no')->unique();

            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('family_member_id')->nullable();
            $table->unsignedBigInteger('procedure_id');
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->unsignedBigInteger('hospital_id')->nullable();

            $table->date('booking_date');
            $table->time('booking_time');

            $table->enum('price_type', ['regular', 'member_package'])
                ->default('regular');

            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);

            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('payment_id')->nullable();

            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed',
                'refunded'
            ])->default('pending');

            $table->string('booking_status')->default('pending');

            $table->text('notes')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procedure_bookings');
    }
};
