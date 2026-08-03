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
        Schema::create('ambulance_bookings', function (Blueprint $table) {

            $table->id();
            $table->string('booking_no')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();

            $table->unsignedBigInteger('family_member_id')->nullable();

            $table->unsignedBigInteger('hospital_id')->nullable();

            $table->unsignedBigInteger('ambulance_type_id')->nullable();

            // Assigned later by hospital
            $table->unsignedBigInteger('ambulance_id')->nullable();

            $table->text('pickup_address')->nullable();

            $table->string('pickup_city')->nullable();

            $table->string('pickup_state')->nullable();

            $table->string('pickup_pincode', 20)->nullable();

            $table->decimal('pickup_latitude', 10, 7)->nullable();

            $table->decimal('pickup_longitude', 10, 7)->nullable();

            $table->text('destination_address')->nullable();

            $table->string('destination_city')->nullable();

            $table->string('destination_state')->nullable();

            $table->string('destination_pincode', 20)->nullable();

            $table->decimal('destination_latitude', 10, 7)->nullable();

            $table->decimal('destination_longitude', 10, 7)->nullable();

            $table->boolean('is_emergency')->default(false)->nullable();

            $table->text('emergency_notes')->nullable();

            $table->decimal('distance_km', 10, 2)->default(0)->nullable();

            $table->decimal('base_amount', 10, 2)->default(0)->nullable();

            $table->decimal('price_per_km', 10, 2)->default(0)->nullable();

            $table->decimal('distance_amount', 10, 2)->default(0)->nullable();

            $table->decimal('extra_charge', 10, 2)->default(0)->nullable();

            $table->decimal('discount', 10, 2)->default(0)->nullable();

            $table->decimal('tax', 10, 2)->default(0)->nullable();

            $table->decimal('total_amount', 10, 2)->default(0)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */

            $table->string('payment_method')->nullable();

            $table->string('transaction_id')->nullable();

            $table->string('payment_id')->nullable();

            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending')->nullable();


            $table->enum('booking_status', ['pending', 'accepted', 'ambulance_assigned', 'on_the_way', 'arrived', 'patient_picked', 'completed', 'rejected', 'cancelled'])->default('pending')->nullable();

            $table->text('notes')->nullable();

            $table->text('cancel_reason')->nullable();

            $table->timestamp('accepted_at')->nullable();

            $table->timestamp('assigned_at')->nullable();

            $table->timestamp('cancelled_at')->nullable();

            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ambulance_bookings');
    }
};