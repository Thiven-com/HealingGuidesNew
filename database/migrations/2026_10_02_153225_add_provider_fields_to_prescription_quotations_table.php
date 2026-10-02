<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('prescription_quotations', function (Blueprint $table) {

            $table->string('provider_type')
                ->nullable()
                ->after('quotation_type');

            $table->unsignedBigInteger('hospital_id')
                ->nullable()
                ->after('provider_type');

            $table->unsignedBigInteger('diagnostic_id')
                ->nullable()
                ->after('hospital_id');
        });
    }

    public function down(): void
    {
        Schema::table('prescription_quotations', function (Blueprint $table) {

            $table->dropIndex([
                'provider_type'
            ]);

            $table->dropIndex([
                'hospital_id'
            ]);

            $table->dropIndex([
                'diagnostic_id'
            ]);

            $table->dropColumn([
                'provider_type',
                'hospital_id',
                'diagnostic_id',
            ]);
        });
    }
};