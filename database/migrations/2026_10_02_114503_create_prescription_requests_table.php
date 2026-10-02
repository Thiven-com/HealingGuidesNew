<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prescription_requests', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Customer
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('customer_id')->nullable();

            $table->unsignedBigInteger('family_member_id')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Prescription
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('prescription_id')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Request Type
            |--------------------------------------------------------------------------
            | medicines / lab_tests / both
            */

            $table->string('request_type')
                ->default('medicines');

            /*
            |--------------------------------------------------------------------------
            | Prescription Details
            |--------------------------------------------------------------------------
            */

            $table->string('prescription_image')
                ->nullable();

            $table->text('notes')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Delivery / Collection Address
            |--------------------------------------------------------------------------
            */

            $table->text('address')
                ->nullable();

            $table->string('city')
                ->nullable();

            $table->string('state')
                ->nullable();

            $table->string('pincode')
                ->nullable();

            $table->decimal('latitude', 10, 7)
                ->nullable();

            $table->decimal('longitude', 10, 7)
                ->nullable();

            $table->string('status')
                ->default('pending');

            /*
            |--------------------------------------------------------------------------
            | Admin
            |--------------------------------------------------------------------------
            */

            $table->text('admin_notes')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamp('reviewed_at')
                ->nullable();

            $table->timestamp('approved_at')
                ->nullable();

            $table->timestamp('rejected_at')
                ->nullable();

            $table->timestamp('completed_at')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_requests');
    }
};