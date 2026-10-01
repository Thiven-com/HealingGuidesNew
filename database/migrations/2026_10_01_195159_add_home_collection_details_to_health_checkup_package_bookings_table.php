<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('health_checkup_package_bookings', function (Blueprint $table) {

            // Home / Centre Collection
            $table->boolean('home_collection')
                ->default(false)
                ->after('booking_time');

            // Home Collection Address
            $table->text('address')
                ->nullable()
                ->after('home_collection');

            $table->string('city')
                ->nullable()
                ->after('address');

            $table->string('state')
                ->nullable()
                ->after('city');

            $table->string('pincode', 20)
                ->nullable()
                ->after('state');

            // GPS Location
            $table->decimal('latitude', 10, 7)
                ->nullable()
                ->after('pincode');

            $table->decimal('longitude', 10, 7)
                ->nullable()
                ->after('latitude');

            // Person to contact for home collection
            $table->string('contact_name')
                ->nullable()
                ->after('longitude');

            $table->string('contact_mobile', 20)
                ->nullable()
                ->after('contact_name');

            // Cancellation / Completion
            $table->text('cancel_reason')
                ->nullable()
                ->after('notes');

            $table->timestamp('cancelled_at')
                ->nullable()
                ->after('cancel_reason');

            $table->timestamp('completed_at')
                ->nullable()
                ->after('cancelled_at');
        });
    }

    public function down(): void
    {
        Schema::table('health_checkup_package_bookings', function (Blueprint $table) {

            $table->dropColumn([
                'home_collection',
                'address',
                'city',
                'state',
                'pincode',
                'latitude',
                'longitude',
                'contact_name',
                'contact_mobile',
                'cancel_reason',
                'cancelled_at',
                'completed_at',
            ]);

        });
    }
};