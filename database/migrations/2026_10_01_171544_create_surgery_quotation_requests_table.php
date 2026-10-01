<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surgery_quotation_requests', function (Blueprint $table) {

            $table->id();

            $table->string('request_no')->nullable();

            $table->unsignedBigInteger('customer_id')->nullable();

            $table->unsignedBigInteger('family_member_id')->nullable();

            $table->unsignedBigInteger('surgery_id')->nullable();

            // Prescription uploaded by customer
            $table->string('prescription')->nullable();

            $table->text('notes')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Request Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'completed',
                'cancelled'
            ])->default('pending');

            $table->text('admin_notes')->nullable();

            $table->timestamp('approved_at')->nullable();

            $table->timestamp('rejected_at')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surgery_quotation_requests');
    }
};