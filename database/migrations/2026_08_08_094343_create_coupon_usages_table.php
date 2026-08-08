<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coupon_usages', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Coupon
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('coupon_id')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Customer
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('customer_id')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Hospital
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('hospital_id')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Reference
            |--------------------------------------------------------------------------
            |
            | A coupon can be used for an appointment or medicine order.
            |
            */

            $table->unsignedBigInteger('appointment_id')->nullable();

            $table->unsignedBigInteger('medicine_order_id')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Coupon Information
            |--------------------------------------------------------------------------
            */

            $table->string('coupon_code', 50)->nullable();

            $table->decimal(
                'discount_amount',
                10,
                2
            )->default(0)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Usage Date
            |--------------------------------------------------------------------------
            */

            $table->timestamp('used_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
    }
};