<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_notifications', function (Blueprint $table) {

            $table->id();

            // Who receives the notification
            $table->string('notifiable_type')->nullable();
            $table->unsignedBigInteger('notifiable_id')->nullable();

            // Notification information
            $table->string('type')->nullable();
            $table->string('title')->nullable();
            $table->text('message')->nullable();

            // Optional related information
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();

            // Optional action/deep link
            $table->string('action')->nullable();
            $table->json('data')->nullable();

            // Notification status
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_notifications');
    }
};