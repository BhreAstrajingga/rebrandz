<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1) Tambah kolom nullable dulu agar aman pada data lama
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
        });

        // 2) Backfill dari users.tenant_id jika ada
        DB::statement('UPDATE user_subscriptions us JOIN users u ON u.id = us.user_id SET us.tenant_id = u.tenant_id WHERE us.tenant_id IS NULL');

        // 3) Tambahkan FK (nullOnDelete) dan index bernama
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->foreign('tenant_id')->references('id')->on('tenants')->nullOnDelete();
            $table->index(['tenant_id', 'service_id', 'status'], 'user_subscriptions_tenant_service_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {
            try {
                $table->dropIndex('user_subscriptions_tenant_service_status_idx');
            } catch (\Throwable $e) {
                try {
                    $table->dropIndex(['tenant_id', 'service_id', 'status']);
                } catch (\Throwable $ignored) {
                }
            }
            try {
                $table->dropForeign(['tenant_id']);
            } catch (\Throwable $ignored) {
            }
            if (Schema::hasColumn('user_subscriptions', 'tenant_id')) {
                $table->dropColumn('tenant_id');
            }
        });
    }
};
