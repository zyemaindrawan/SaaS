<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VoucherController extends Controller
{
    /**
     * Validate voucher code
     */
    public function check(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'subtotal' => 'required|numeric|min:0'
        ]);

        $voucher = Voucher::where('code', strtoupper(trim($request->code)))
            ->active()
            ->first();

        if (!$voucher) {
            return response()->json([
                'valid' => false,
                'message' => 'Voucher code is invalid or has expired.'
            ]);
        }

        // Check minimum purchase requirement
        if ($voucher->min_purchase && $request->subtotal < $voucher->min_purchase) {
            return response()->json([
                'valid' => false,
                'message' => "Minimum purchase amount of Rp " . number_format($voucher->min_purchase, 0, ',', '.') . " is required for this voucher."
            ], 400);
        }

        // Calculate discount
        $discount = $voucher->calculateDiscount($request->subtotal);
        $finalAmount = $request->subtotal - $discount;

        return response()->json([
            'valid' => true,
            'voucher' => [
                'id' => $voucher->id,
                'code' => $voucher->code,
                'name' => $voucher->name,
                'description' => $voucher->description,
                'discount_type' => $voucher->discount_type,
                'discount_value' => $voucher->discount_value,
                'max_discount' => $voucher->max_discount,
                'min_purchase' => $voucher->min_purchase,
            ],
            'discount' => $discount,
            'final_amount' => $finalAmount,
            'message' => $discount > 0
                ? "Voucher applied successfully! You saved Rp " . number_format($discount, 0, ',', '.')
                : "Voucher applied but no discount available."
        ]);
    }

    /**
     * Get all active vouchers (for admin or display purposes)
     */
    public function index(): JsonResponse
    {
        $vouchers = Voucher::active()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'vouchers' => $vouchers
        ]);
    }

    /**
     * Get voucher by code (for debugging)
     */
    public function show(string $code): JsonResponse
    {
        $voucher = Voucher::where('code', strtoupper(trim($code)))
            ->first();

        if (!$voucher) {
            return response()->json([
                'message' => 'Voucher not found'
            ], 404);
        }

        return response()->json([
            'voucher' => $voucher
        ]);
    }
}
