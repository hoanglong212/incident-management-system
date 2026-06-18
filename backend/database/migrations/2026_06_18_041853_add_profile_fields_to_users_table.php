<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')
                ->nullable()
                ->after('id')
                ->constrained('roles')
                ->nullOnDelete();

            $table->string('phone', 20)->nullable()->after('password');
            $table->string('avatar_url')->nullable()->after('phone');
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE')->after('avatar_url');
            $table->softDeletes();

            $table->index('role_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);

            $table->dropIndex(['role_id']);
            $table->dropIndex(['status']);

            $table->dropColumn([
                'role_id',
                'phone',
                'avatar_url',
                'status',
                'deleted_at',
            ]);
        });
    }
};