<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('surgery_quotations', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('surgery_quotation_request_id')->nullable();

            $table->unsignedBigInteger('hospital_id')->nullable();

            $table->string('hospital_name')->nullable();

            $table->decimal('amount', 12, 2)->default(0);

            $table->decimal('discount', 12, 2)->default(0);

            $table->decimal('tax', 12, 2)->default(0);

            $table->decimal('total_amount', 12, 2)->default(0);

            $table->text('quotation_details')->nullable();

            $table->text('included_services')->nullable();

            $table->text('excluded_services')->nullable();

            $table->date('valid_until')->nullable();

            $table->enum('status', [
                'sent',
                'accepted',
                'rejected',
                'expired'
            ])->default('sent');

            $table->timestamp('sent_at')->nullable();

            $table->timestamp('accepted_at')->nullable();

            $table->timestamp('rejected_at')->nullable();

            $table->text('admin_notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surgery_quotations');
    }
};