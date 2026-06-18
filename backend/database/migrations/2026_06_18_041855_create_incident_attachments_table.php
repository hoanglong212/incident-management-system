<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_attachments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('incident_id')
                ->constrained('incidents')
                ->restrictOnDelete();

            $table->foreignId('uploaded_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('file_url');
            $table->string('file_name');
            $table->string('file_type', 100);
            $table->unsignedBigInteger('file_size');

            $table->timestamps();
            $table->softDeletes();

            $table->index('incident_id');
            $table->index('uploaded_by');
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_attachments');
    }
};