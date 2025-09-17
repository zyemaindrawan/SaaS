<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_content_id')
                  ->constrained()->onDelete('cascade');
            $table->string('midtrans_order_id')->unique();
            $table->string('midtrans_transaction_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('IDR');
            $table->enum('status', [
                'pending', 'success', 'failed', 'expired', 'cancelled'
            ])->default('pending');
            $table->string('payment_type', 50)->nullable();
            $table->string('payment_method', 50)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index('midtrans_order_id');
            $table->index('midtrans_transaction_id');
            $table->index('website_content_id');
            $table->index('status');
            $table->index('paid_at');
            $table->index('expires_at');
            $table->index(['website_content_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index(['payment_type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
