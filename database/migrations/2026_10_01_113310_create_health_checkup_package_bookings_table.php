<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('health_checkup_package_bookings', function (Blueprint $table) {

            $table->id();

            $table->string('booking_no')->nullable();

            $table->unsignedBigInteger('customer_id')->nullable();

            $table->unsignedBigInteger('family_member_id')->nullable();

            $table->unsignedBigInteger('health_checkup_package_id')->nullable();

            $table->date('booking_date')->nullable();

            $table->time('booking_time')->nullable();

            $table->decimal('total_amount', 10, 2)
                ->default(0);

            $table->string('payment_method')->nullable();

            $table->string('payment_id')->nullable();

            $table->string('payment_status')
                ->default('pending');

            $table->string('booking_status')
                ->default('pending');

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_checkup_package_bookings');
    }
};