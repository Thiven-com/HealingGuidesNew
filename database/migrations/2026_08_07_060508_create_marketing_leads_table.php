<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_leads', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('marketing_staff_id')->nullable();
            $table->string('lead_type', 50)->nullable();

            $table->string('name')->nullable();

            $table->string('mobile')->nullable();

            $table->string('alternate_mobile')->nullable();

            $table->string('email')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Hospital / Organization
            |--------------------------------------------------------------------------
            */

            $table->string('organization_name')->nullable();

            $table->string('contact_person')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Doctor
            |--------------------------------------------------------------------------
            */

            $table->string('specialization')->nullable();

            $table->string('qualification')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Address
            |--------------------------------------------------------------------------
            */

            $table->text('address')->nullable();

            $table->string('country')->default('India')->nullable();

            $table->string('state')->nullable();

            $table->string('city')->nullable();

            $table->string('pincode')->nullable();

            $table->decimal('latitude',10,7)->nullable();

            $table->decimal('longitude',10,7)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Lead Information
            |--------------------------------------------------------------------------
            */

            $table->string('source')->nullable();

            $table->string('priority')->nullable()->default('normal');

            /*
            |--------------------------------------------------------------------------
            | Lead Status
            |--------------------------------------------------------------------------
            |
            | new
            | contacted
            | follow_up
            | interested
            | not_interested
            | converted
            | closed
            |
            */

            $table->string('lead_status')->default('new')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Follow-up
            |--------------------------------------------------------------------------
            */

            $table->dateTime(
                'next_followup_at'
            )->nullable();

            /*
            |--------------------------------------------------------------------------
            | Notes
            |--------------------------------------------------------------------------
            */

            $table->text('notes')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Conversion
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger(
                'converted_id'
            )->nullable();

            $table->timestamp(
                'converted_at'
            )->nullable();

            $table->boolean('status')
                ->default(1);

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('marketing_leads');
    }
};