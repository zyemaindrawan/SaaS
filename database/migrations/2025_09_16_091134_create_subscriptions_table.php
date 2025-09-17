<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('website_content_id')
                  ->constrained()->onDelete('cascade');
            $table->string('plan_name', 100);
            $table->enum('status', [
                'active', 'cancelled', 'expired', 'suspended'
            ])->default('active');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->boolean('auto_renew')->default(true);
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('website_content_id');
            $table->index('status');
            $table->index('starts_at');
            $table->index('ends_at');
            $table->index('plan_name');
            $table->index(['user_id', 'status']);
            $table->index(['status', 'ends_at']);
            $table->index(['ends_at', 'auto_renew']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
