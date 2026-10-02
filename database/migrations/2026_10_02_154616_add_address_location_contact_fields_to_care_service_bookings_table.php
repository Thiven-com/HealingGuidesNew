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
            // Service Address
            $table->text('address')
                ->nullable()
                ->after('pincode');

            // GPS Location
            $table->decimal('latitude', 10, 7)
                ->nullable()
                ->after('address');

            $table->decimal('longitude', 10, 7)
                ->nullable()
                ->after('latitude');

            // Person to contact for home service
            $table->string('contact_name')
                ->nullable()
                ->after('longitude');

            $table->string('contact_mobile', 20)
                ->nullable()
                ->after('contact_name');
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
