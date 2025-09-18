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
            $table->string('code', 50)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('website_content_id')->constrained()->cascadeOnDelete();
            $table->decimal('fee', 10, 2)->default(0.00);
            $table->decimal('gross_amount', 10, 2);
            $table->enum('status', ['pending', 'paid', 'failed', 'cancelled', 'expired'])->default('pending');
            $table->string('payment_type', 50)->nullable();
            $table->json('midtrans_data')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('website_content_id');
            $table->index('status');
            $table->index('created_at');
            $table->index('paid_at');
            $table->index('expired_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
