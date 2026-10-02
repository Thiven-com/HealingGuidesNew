<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescription_quotations', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Prescription Request
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('prescription_request_id')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Quotation Number
            |--------------------------------------------------------------------------
            */

            $table->string('quotation_no')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Quotation Type
            |--------------------------------------------------------------------------
            |
            | medicines
            | lab_tests
            | both
            |
            */

            $table->string('quotation_type')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Amount
            |--------------------------------------------------------------------------
            */

            $table->decimal('subtotal', 10, 2)
                ->default(0);

            $table->decimal('delivery_charge', 10, 2)
                ->default(0);

            $table->decimal('discount', 10, 2)
                ->default(0);

            $table->decimal('tax', 10, 2)
                ->default(0);

            $table->decimal('total_amount', 10, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Admin Details
            |--------------------------------------------------------------------------
            */

            $table->text('quotation_details')
                ->nullable();

            $table->text('admin_notes')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Validity
            |--------------------------------------------------------------------------
            */

            $table->date('valid_until')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            |
            | draft
            | sent
            | approved
            | rejected
            | payment_pending
            | paid
            | completed
            | cancelled
            |
            */

            $table->string('status')
                ->default('draft');

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamp('sent_at')
                ->nullable();

            $table->timestamp('approved_at')
                ->nullable();

            $table->timestamp('rejected_at')
                ->nullable();

            $table->timestamp('paid_at')
                ->nullable();

            $table->timestamp('completed_at')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_quotations');
    }
};