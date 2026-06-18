<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('in_app_notifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('incident_id')
                ->nullable()
                ->constrained('incidents')
                ->nullOnDelete();

            $table->string('title');
            $table->text('message');
            $table->string('type', 80)->nullable();
            $table->boolean('is_read')->default(false);

            $table->timestamps();

            $table->index('user_id');
            $table->index('incident_id');
            $table->index('is_read');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('in_app_notifications');
    }
};