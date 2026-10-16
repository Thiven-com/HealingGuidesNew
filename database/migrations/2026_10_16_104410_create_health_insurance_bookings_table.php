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
        Schema::create('health_insurance_bookings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('provider_id');
            $table->unsignedBigInteger('plan_id');

            $table->string('booking_reference')->unique();

            $table->string('customer_name');
            $table->string('customer_phone', 25);
            $table->string('customer_email')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->unsignedInteger('members_count')->default(1);
            $table->text('notes')->nullable();

            // Price snapshots at booking time
            $table->decimal('price', 12, 2);
            $table->decimal('discount_price', 12, 2);
            $table->decimal('tax_price', 12, 2);
            $table->decimal('total_amount', 12, 2);
            $table->foreignId('payment_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('payment_status')->default('pending');
            $table->string('cancel_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_insurance_bookings');
    }
};
