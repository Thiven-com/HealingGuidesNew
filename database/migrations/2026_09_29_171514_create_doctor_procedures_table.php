<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('doctor_procedures', function (Blueprint $table) {

            $table->id();

            $table->bigInteger('doctor_id')
                ->nullable();

            $table->bigInteger('procedure_id')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_procedures');
    }
};