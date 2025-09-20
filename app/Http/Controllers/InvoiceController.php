<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    public function show($code)
    {
        $payment = Payment::with(['user', 'websiteContent.template'])
            ->where('code', $code)
            ->firstOrFail();

        // Check if user owns this payment or is admin
        if (auth()->id() !== $payment->user_id && !auth()->user()?->is_admin) {
            abort(403, 'Unauthorized access to this invoice');
        }

        // Generate snap token if it doesn't exist and payment is pending
        if (!$payment->snap_token && in_array($payment->status, ['pending', 'initiated'])) {
            try {
                $snapToken = $this->paymentService->getSnapToken($payment);
                $payment->update([
                    'snap_token' => $snapToken,
                    'midtrans_order_id' => $payment->code,
                    'transaction_time' => now(),
                ]);
                // Refresh the payment model to get updated snap_token
                $payment->refresh();
            } catch (\Exception $e) {
                \Log::error('Failed to generate snap token for payment ' . $payment->code . ': ' . $e->getMessage(), [
                    'payment_id' => $payment->id,
                    'error' => $e->getTraceAsString()
                ]);
                // Continue without snap token - will show error in frontend
            }
        }

        return Inertia::render('Invoice/Show', [
            'payment' => [
                'id' => $payment->id,
                'code' => $payment->code,
                'status' => $payment->status,
                'gross_amount' => $payment->gross_amount,
                'fee' => $payment->fee,
                'snap_token' => $payment->snap_token,
                'payment_type' => $payment->payment_type,
                'created_at' => $payment->created_at,
                'expired_at' => $payment->expired_at,
                'paid_at' => $payment->paid_at,
                'transaction_time' => $payment->transaction_time,
            ],
            'user' => [
                'name' => $payment->user->name,
                'email' => $payment->user->email,
                'phone' => $payment->user->phone,
            ],
            'template' => [
                'id' => $payment->websiteContent->template->id,
                'name' => $payment->websiteContent->template->name,
                'slug' => $payment->websiteContent->template->slug,
                'category' => $payment->websiteContent->template->category,
                'price' => $payment->websiteContent->template->price,
                'preview_image' => $payment->websiteContent->template->preview_image,
            ],
            'website_content' => [
                'subdomain' => $payment->websiteContent->subdomain,
                'status' => $payment->websiteContent->status,
                'content_data' => $payment->websiteContent->content_data,
            ],
            'snap_token' => $payment->snap_token,
            'midtrans_client_key' => config('midtrans.client_key'),
            'routes' => [
                'finish' => route('payment.finish', $payment->code),
                'error' => route('payment.error', $payment->code),
                'pending' => route('payment.pending', $payment->code),
            ],
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'href' => '/dashboard'],
                ['name' => 'Invoice', 'href' => null],
            ]
        ]);
    }

    public function pay($code)
    {
        $payment = Payment::where('code', $code)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'initiated'])
            ->firstOrFail();

        if (!$payment->snap_token) {
            return redirect()->route('invoice.show', $code)
                ->with('error', 'Payment token not available. Please contact support.');
        }

        return Inertia::render('Invoice/Payment', [
            'payment' => [
                'code' => $payment->code,
                'gross_amount' => $payment->gross_amount,
                'snap_token' => $payment->snap_token,
                'expired_at' => $payment->expired_at,
            ],
            'template' => [
                'name' => $payment->websiteContent->template->name,
                'preview_image' => $payment->websiteContent->template->preview_image,
            ],
        ]);
    }
}
