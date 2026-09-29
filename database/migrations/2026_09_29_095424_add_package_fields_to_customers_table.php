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
        Schema::table('customers', function (Blueprint $table) {
            //
            $table->bigInteger('package_id')
                ->nullable()
                ->after('id');

            $table->date('package_start_date')
                ->nullable()
                ->after('package_id');

            $table->date('package_expiry_date')
                ->nullable()
                ->after('package_start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
};
