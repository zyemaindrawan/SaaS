<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function show($paymentCode)
    {
        $payment = Payment::where('code', $paymentCode)
            ->with(['user', 'websiteContent.template'])
            ->firstOrFail();

        // Check if user owns this payment
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        $snapToken = null;
        $error = null;

        try {
            // Generate snap token if payment is still pending
            if ($payment->status === 'pending') {
                $snapToken = $this->paymentService->getSnapToken($payment);
            }
        } catch (\Exception $e) {
            $error = 'Failed to generate payment token: ' . $e->getMessage();
        }

        return Inertia::render('Invoice/Show', [
            'payment' => $payment,
            'snapToken' => $snapToken,
            'template' => $payment->websiteContent->template,
            'website_content' => $payment->websiteContent,
            'midtrans_client_key' => config('midtrans.client_key'),
            'error' => $error
        ]);
    }
}
