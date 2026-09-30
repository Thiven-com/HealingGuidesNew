<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('family_member_health_checkups', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('family_member_id')->nullable();
            $table->unsignedBigInteger('health_checkup_id')->nullable();

            $table->string('report_value')
                ->nullable();

            // Health score between 0 - 100
            $table->unsignedTinyInteger('percentage')
                ->nullable();

            $table->string('status')
                ->default('pending');

            $table->date('checked_at')
                ->nullable();

            $table->text('remarks')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_member_health_checkups');
    }
};