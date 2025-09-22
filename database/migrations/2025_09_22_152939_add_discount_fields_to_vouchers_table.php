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
        Schema::table('vouchers', function (Blueprint $table) {
            $table->enum('discount_type', ['percentage', 'fixed'])->after('description');
            $table->decimal('discount_value', 10, 2)->after('discount_type');
            $table->decimal('min_purchase', 10, 2)->nullable()->after('discount_value');
            $table->decimal('max_discount', 10, 2)->nullable()->after('min_purchase');
            $table->integer('usage_limit')->nullable()->after('max_discount');
            $table->integer('used_count')->default(0)->after('usage_limit');
            $table->boolean('is_active')->default(true)->after('used_count');
            $table->timestamp('starts_at')->nullable()->after('is_active');
            $table->timestamp('expires_at')->nullable()->after('starts_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn([
                'discount_type',
                'discount_value',
                'min_purchase',
                'max_discount',
                'usage_limit',
                'used_count',
                'is_active',
                'starts_at',
                'expires_at'
            ]);
        });
    }
};
