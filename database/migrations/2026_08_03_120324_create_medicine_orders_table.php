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
        Schema::create('medicine_orders', function (Blueprint $table) {

            $table->id();

            $table->string('order_no')->nullable();

            $table->unsignedBigInteger('customer_id')->nullable();

            $table->unsignedBigInteger('family_member_id')->nullable();

            $table->unsignedBigInteger('hospital_id')->nullable();

            // Optional when order is from doctor prescription
            $table->unsignedBigInteger('prescription_id')->nullable();

            $table->decimal('subtotal', 10, 2)->default(0)->nullable();

            $table->decimal('delivery_charge', 10, 2)->default(0)->nullable();

            $table->decimal('discount', 10, 2)->default(0)->nullable();

            $table->decimal('tax', 10, 2)->default(0)->nullable();

            $table->decimal('total_amount', 10, 2)->default(0)->nullable();


            $table->longText('delivery_address')->nullable();

            $table->string('delivery_city')->nullable();

            $table->string('delivery_state')->nullable();

            $table->string('delivery_pincode', 20)->nullable();

            $table->decimal('delivery_latitude',10,7)->nullable();

            $table->decimal('delivery_longitude',10,7)->nullable();

            $table->string('payment_method')->nullable();

            $table->string('transaction_id')->nullable();

            $table->string('payment_id')->nullable();

            $table->enum('payment_status', ['pending','paid','failed','refunded'])->default('pending')->nullable();

            $table->enum('order_status', ['pending','accepted','rejected','preparing','out_for_delivery','delivered','cancelled'])->default('pending');

            $table->text('notes')->nullable();

            $table->text('cancel_reason')->nullable();

            $table->timestamp('accepted_at')->nullable();

            $table->timestamp('rejected_at')->nullable();

            $table->timestamp('cancelled_at')->nullable();

            $table->timestamp('delivered_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_orders');
    }
};
