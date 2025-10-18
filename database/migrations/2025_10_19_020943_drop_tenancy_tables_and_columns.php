<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'tenant_id')) {
            Schema::table('users', function (Blueprint $table): void {
                // Drop FK + column if present
                try {
                    $table->dropConstrainedForeignId('tenant_id');
                } catch (\Throwable $e) {
                    try { $table->dropForeign(['tenant_id']); } catch (\Throwable $e2) {}
                    if (Schema::hasColumn('users', 'tenant_id')) {
                        $table->dropColumn('tenant_id');
                    }
                }
            });
        }

        if (Schema::hasTable('tenants')) {
            Schema::drop('tenants');
        }
    }

    public function down(): void
    {
        // Recreate tenants table (minimal)
        if (! Schema::hasTable('tenants')) {
            Schema::create('tenants', function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('domain')->nullable()->unique();
                $table->timestamps();
            });
        }

        // Re-add users.tenant_id column
        if (Schema::hasTable('users') && ! Schema::hasColumn('users', 'tenant_id')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->foreignId('tenant_id')->nullable()->constrained('tenants')->cascadeOnUpdate()->nullOnDelete()->after('user_type');
            });
        }
    }
};

