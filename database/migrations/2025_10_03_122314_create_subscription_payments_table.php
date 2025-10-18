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
        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_subscription_id')
                ->constrained('user_subscriptions')
                ->cascadeOnDelete();

            $table->decimal('amount', 12, 2);
            $table->string('payment_method')->nullable();   // misal: bank_transfer, gopay
            $table->string('provider')->nullable();        // misal: midtrans, stripe, manual
            $table->string('transaction_id')->nullable();  // id unik dari provider
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->dateTime('paid_at')->nullable();
            $table->text('note')->nullable();              // catatan tambahan, opsional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_payments');
    }
};
