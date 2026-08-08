<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {

            $table->id();

            $table->string('created_by_type')->default('admin')->nullable();

            $table->unsignedBigInteger('created_by_id')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Hospital
            |--------------------------------------------------------------------------
            |
            | NULL = Admin / platform coupon
            | ID   = Hospital-specific coupon
            |
            */

            $table->unsignedBigInteger('hospital_id')->nullable();

            $table->string('code', 50)->nullable();

            $table->string('title')->nullable();

            $table->text('description')->nullable();

            $table->enum('coupon_type', [
                'coupon',
                'offer',
            ])->default('coupon')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Discount
            |--------------------------------------------------------------------------
            */

            $table->enum('discount_type', [
                'percentage',
                'fixed',
                'free',
            ])->default('percentage')->nullable();

            $table->decimal(
                'discount_value',
                10,
                2
            )->default(0)->nullable();

            $table->decimal(
                'max_discount',
                10,
                2
            )->nullable();

            $table->decimal(
                'min_order_amount',
                10,
                2
            )->default(0)->nullable();


            $table->string('applicable_to')->nullable();

            $table->boolean('new_customer_only')
                ->default(false)->nullable();

            $table->boolean('first_appointment_only')
                ->default(false)->nullable();

            $table->boolean('free_appointment')
                ->default(false)->nullable();

            $table->unsignedInteger('usage_limit')
                ->nullable();

            $table->unsignedInteger('usage_per_customer')
                ->default(1)->nullable();

            $table->unsignedInteger('used_count')
                ->default(0)->nullable();


            $table->timestamp('starts_at')
                ->nullable();

            $table->timestamp('expires_at')
                ->nullable();

            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};