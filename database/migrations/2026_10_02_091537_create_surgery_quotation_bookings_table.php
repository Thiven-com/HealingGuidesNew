<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surgery_quotation_bookings', function (Blueprint $table) {

            $table->id();

            $table->string('booking_no')->nullable();

            $table->unsignedBigInteger('customer_id')->nullable();

            $table->unsignedBigInteger('family_member_id')->nullable();

            $table->unsignedBigInteger('surgery_quotation_request_id')->nullable();

            $table->unsignedBigInteger('surgery_quotation_id')->nullable();

            $table->unsignedBigInteger('surgery_id')->nullable();

            $table->unsignedBigInteger('hospital_id')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Quotation Amount
            |--------------------------------------------------------------------------
            */

            $table->decimal('amount', 10, 2)->default(0);

            $table->decimal('discount', 10, 2)->default(0);

            $table->decimal('tax', 10, 2)->default(0);

            $table->decimal('total_amount', 10, 2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */

            $table->string('payment_method')->nullable();

            $table->unsignedBigInteger('payment_id')->nullable();

            $table->string('transaction_id')->nullable();

            $table->string('payment_status')->default('pending');

            /*
            |--------------------------------------------------------------------------
            | Booking
            |--------------------------------------------------------------------------
            */

            $table->date('booking_date')->nullable();

            $table->time('booking_time')->nullable();

            $table->string('booking_status')->default('pending');

            /*
            |--------------------------------------------------------------------------
            | Hospital / Surgery Details
            |--------------------------------------------------------------------------
            */

            $table->text('hospital_address')->nullable();

            $table->text('notes')->nullable();

            $table->text('cancel_reason')->nullable();

            $table->timestamp('confirmed_at')->nullable();

            $table->timestamp('cancelled_at')->nullable();

            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surgery_quotation_bookings');
    }
};