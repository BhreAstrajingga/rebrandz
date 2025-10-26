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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name', 120);
            $table->string('code', 60);
            $table->boolean('is_default')->default(false);
            $table->boolean('active')->default(true);
            $table->string('slug', 120);
            $table->timestamps();

            $table->unique(['tenant_id', 'code']);
            $table->unique(['tenant_id', 'slug']);
            $table->index(['tenant_id', 'active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
