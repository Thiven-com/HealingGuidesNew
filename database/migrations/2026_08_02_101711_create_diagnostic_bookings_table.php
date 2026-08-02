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
        Schema::create('diagnostic_bookings', function (Blueprint $table) {

            $table->id();

            $table->string('booking_no')->nullable();

            $table->unsignedBigInteger('customer_id')->nullable();

            $table->unsignedBigInteger('family_member_id')->nullable();

            $table->unsignedBigInteger('diagnostic_id')->nullable();

            $table->enum('collection_type', ['home_collection','lab_visit'])->nullable();

            $table->date('booking_date')->nullable();

            $table->time('booking_time')->nullable();
        
            $table->decimal('subtotal', 10, 2)->default(0)->nullable();

            $table->decimal('home_collection_charge',10,2)->default(0)->nullable();

            $table->decimal('discount', 10, 2)->default(0)->nullable();

            $table->decimal('tax', 10, 2)->default(0)->nullable();

            $table->decimal('total_amount', 10, 2)->default(0)->nullable();

            $table->string('payment_method')->nullable();

            $table->string('transaction_id')->nullable();

            $table->string('payment_id')->nullable();

            $table->enum('payment_status', ['pending','paid','failed','refunded'])->default('pending')->nullable();

            $table->string('booking_status')->default('pending')->nullable();

            $table->text('address')->nullable();

            $table->string('city')->nullable();

            $table->string('state')->nullable();

            $table->string('pincode', 20)->nullable();

            $table->decimal('latitude', 10, 7)->nullable();

            $table->decimal('longitude', 10, 7)->nullable();

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
        Schema::dropIfExists('diagnostic_bookings');
    }
};