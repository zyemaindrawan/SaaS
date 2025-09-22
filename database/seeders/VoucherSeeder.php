<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vouchers = [
            [
                'code' => 'WELCOME10',
                'name' => 'Welcome Discount 10%',
                'description' => 'Get 10% off on your first purchase',
                'discount_type' => 'percentage',
                'discount_value' => 10.00,
                'min_purchase' => 50000,
                'max_discount' => 50000,
                'usage_limit' => 100,
                'used_count' => 0,
                'is_active' => true,
                'starts_at' => Carbon::now()->subDays(1),
                'expires_at' => Carbon::now()->addMonths(3),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'DISCOUNT20',
                'name' => 'Special Discount 20%',
                'description' => 'Get 20% off on orders above Rp 100,000',
                'discount_type' => 'percentage',
                'discount_value' => 20.00,
                'min_purchase' => 100000,
                'max_discount' => 100000,
                'usage_limit' => 50,
                'used_count' => 0,
                'is_active' => true,
                'starts_at' => Carbon::now()->subDays(1),
                'expires_at' => Carbon::now()->addMonths(2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SAVE50000',
                'name' => 'Fixed Discount Rp 50,000',
                'description' => 'Get Rp 50,000 off on orders above Rp 200,000',
                'discount_type' => 'fixed',
                'discount_value' => 50000.00,
                'min_purchase' => 200000,
                'max_discount' => null,
                'usage_limit' => 25,
                'used_count' => 0,
                'is_active' => true,
                'starts_at' => Carbon::now()->subDays(1),
                'expires_at' => Carbon::now()->addMonths(1),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'PROMO15',
                'name' => 'Promo 15%',
                'description' => 'Limited time offer: 15% discount',
                'discount_type' => 'percentage',
                'discount_value' => 15.00,
                'min_purchase' => 75000,
                'max_discount' => 75000,
                'usage_limit' => 75,
                'used_count' => 0,
                'is_active' => true,
                'starts_at' => Carbon::now()->subDays(1),
                'expires_at' => Carbon::now()->addDays(30),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'NEWUSER',
                'name' => 'New User Special',
                'description' => 'Special discount for new users',
                'discount_type' => 'percentage',
                'discount_value' => 25.00,
                'min_purchase' => 50000,
                'max_discount' => 100000,
                'usage_limit' => 10,
                'used_count' => 0,
                'is_active' => true,
                'starts_at' => Carbon::now()->subDays(1),
                'expires_at' => Carbon::now()->addDays(7),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($vouchers as $voucher) {
            Voucher::create($voucher);
        }
    }
}
