<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {

            $table->id();
            $table->string('payment_no')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('payment_for')->nullable();

            $table->unsignedBigInteger('reference_id')->nullable();

            $table->decimal('amount', 10, 2)->default(0);

            $table->decimal('discount', 10, 2)->default(0);

            $table->decimal('tax', 10, 2)->default(0);

            $table->decimal('paid_amount', 10, 2)->default(0);

            $table->decimal('balance_amount', 10, 2)->default(0);

            $table->string('currency')->default('INR')->nullable();

            $table->string('payment_gateway')->nullable();
            // Razorpay, Cash, UPI, Stripe

            $table->string('payment_method')->nullable();
            // Online, Cash, Card, UPI, Net Banking

            $table->string('gateway_order_id')->nullable();

            $table->string('gateway_payment_id')->nullable();

            $table->text('gateway_signature')->nullable();

            $table->string('transaction_id')->nullable();

            $table->string('bank_reference_no')->nullable();

            $table->string('invoice_no')->nullable();

            $table->enum('payment_status', [
                'pending',
                'success',
                'failed',
                'cancelled',
                'refunded',
                'partial_refunded'
            ])->default('pending');

            $table->text('failure_reason')->nullable();

            $table->timestamp('paid_at')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};