<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();

            $table->string('code', 30)->unique();
            $table->string('title');
            $table->text('description');

            $table->foreignId('category_id')
                ->constrained('incident_categories')
                ->restrictOnDelete();

            $table->enum('priority', ['LOW', 'MEDIUM', 'HIGH', 'URGENT'])
                ->default('MEDIUM');

            $table->enum('status', [
                'NEW',
                'ASSIGNED',
                'IN_PROGRESS',
                'PENDING',
                'RESOLVED',
                'CLOSED',
                'REJECTED',
            ])->default('NEW');

            $table->foreignId('reporter_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('address')->nullable();
            $table->string('ward', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('city', 100)->nullable();

            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);

            $table->dateTime('occurred_at')->nullable();
            $table->dateTime('assigned_at')->nullable();
            $table->dateTime('resolved_at')->nullable();
            $table->dateTime('closed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('code');
            $table->index('category_id');
            $table->index('priority');
            $table->index('status');
            $table->index('reporter_id');
            $table->index('assigned_to');
            $table->index('created_at');
            $table->index('deleted_at');
        });

        DB::statement('ALTER TABLE incidents ADD location POINT NOT NULL AFTER longitude');
        DB::statement('CREATE SPATIAL INDEX idx_incidents_location ON incidents(location)');
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};