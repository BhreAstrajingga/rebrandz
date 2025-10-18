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
        Schema::table('service_plans', function (Blueprint $table): void {
            if (! Schema::hasColumn('service_plans', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
        });

        Schema::table('service_plans', function (Blueprint $table): void {
            if (Schema::hasColumn('service_plans', 'slug')) {
                $table->unique('slug');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_plans', function (Blueprint $table): void {
            if (Schema::hasColumn('service_plans', 'slug')) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            }
        });
    }
};
