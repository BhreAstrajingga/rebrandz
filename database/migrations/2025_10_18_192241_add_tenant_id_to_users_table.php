<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            if (! Schema::hasColumn('users', 'tenant_id')) {
                $table->foreignId('tenant_id')
                    ->nullable()
                    ->constrained('tenants')
                    ->cascadeOnUpdate()
                    ->nullOnDelete()
                    ->after('user_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            if (Schema::hasColumn('users', 'tenant_id')) {
                try {
                    $table->dropConstrainedForeignId('tenant_id');
                } catch (\Throwable $e) {
                    try {
                        $table->dropForeign(['tenant_id']);
                    } catch (\Throwable $e2) {
                    }
                    $table->dropColumn('tenant_id');
                }
            }
        });
    }
};
