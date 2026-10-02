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
        Schema::table('care_service_bookings', function (Blueprint $table) {
            $table->string('booking_no')
                ->nullable()
                ->after('id');

            $table->unsignedBigInteger('customer_id')
                ->nullable()
                ->after('family_member_id');

            $table->decimal('total_amount', 10, 2)
                ->default(0)
                ->after('additional_note');

            $table->string('payment_method')
                ->nullable()
                ->after('total_amount');

            $table->unsignedBigInteger('payment_id')
                ->nullable()
                ->after('payment_method');

            $table->string('payment_status')
                ->default('pending')
                ->after('payment_id');

            $table->string('booking_status')
                ->default('pending')
                ->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('care_service_bookings', function (Blueprint $table) {
            //
        });
    }
};
