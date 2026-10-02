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
        Schema::table('medicine_orders', function (Blueprint $table) {

            $table->unsignedBigInteger('prescription_request_id')
                ->nullable()
                ->after('prescription_id');

            $table->unsignedBigInteger('prescription_quotation_id')
                ->nullable()
                ->after('prescription_request_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medicine_orders', function (Blueprint $table) {
            //
        });
    }
};
