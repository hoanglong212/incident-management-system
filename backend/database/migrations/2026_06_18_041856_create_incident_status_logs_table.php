<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_status_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('incident_id')
                ->constrained('incidents')
                ->restrictOnDelete();

            $table->string('old_status', 50)->nullable();
            $table->string('new_status', 50);

            $table->foreignId('changed_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->text('note')->nullable();

            $table->timestamps();

            $table->index('incident_id');
            $table->index('changed_by');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_status_logs');
    }
};