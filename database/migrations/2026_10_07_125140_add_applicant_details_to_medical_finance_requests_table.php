<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medical_finance_requests', function (Blueprint $table) {
            $table->string('name')->nullable()->after('customer_id');
            $table->string('email')->nullable()->after('name');
            $table->string('mobile')->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('medical_finance_requests', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'email',
                'mobile',
            ]);
        });
    }
};