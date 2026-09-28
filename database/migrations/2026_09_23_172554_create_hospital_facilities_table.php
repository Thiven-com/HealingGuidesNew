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
        Schema::create('hospital_facilities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('hospital_id')
                ->constrained('hospitals')
                ->cascadeOnDelete();

            $table->foreignId('facility_id')
                ->constrained('facilities')
                ->cascadeOnDelete();

            $table->text('description')->nullable();

            $table->string('short_description')->nullable();

            $table->timestamps();

            $table->unique([
                'hospital_id',
                'facility_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_facilities');
    }
};
