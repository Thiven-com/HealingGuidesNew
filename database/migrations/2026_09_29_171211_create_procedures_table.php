<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('procedures', function (Blueprint $table) {

            $table->id();
            $table->bigInteger('specialization_id')
                ->nullable();
            $table->string('name')->nullable();

            $table->string('slug')
                ->nullable();
            $table->string('short_description')
                ->nullable();
            $table->text('description')
                ->nullable();
            $table->text('about')
                ->nullable();
            $table->string('duration')
                ->nullable();
            $table->string('hospital_stay')
                ->nullable();
            $table->string('recovery')
                ->nullable();

            $table->string('image')
                ->nullable();

            $table->string('icon')
                ->nullable();

            $table->decimal('price', 10, 2)
                ->default(0);

            $table->unsignedInteger('display_order')
                ->default(0);

            $table->boolean('status')
                ->default(1);
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procedures');
    }
};