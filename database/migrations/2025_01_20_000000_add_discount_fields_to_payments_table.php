<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->after('website_content_id'); // Template price
            $table->string('voucher_code')->nullable()->after('gross_amount');
            $table->decimal('voucher_discount', 10, 2)->default(0)->after('voucher_code');
            $table->decimal('final_amount', 10, 2)->after('voucher_discount'); // Final amount after discount
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['amount', 'voucher_code', 'voucher_discount', 'final_amount']);
        });
    }
};
