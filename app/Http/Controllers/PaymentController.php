<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Handle successful payment
     */
    public function finish($paymentCode)
    {
        $payment = Payment::where('code', $paymentCode)->firstOrFail();

        return redirect()->route('invoice.show', $paymentCode)
            ->with('success', 'Payment completed successfully!');
    }

    /**
     * Handle unfinished payment
     */
    public function unfinish($paymentCode)
    {
        $payment = Payment::where('code', $paymentCode)->firstOrFail();

        return redirect()->route('invoice.show', $paymentCode)
            ->with('warning', 'Payment was not completed. Please try again.');
    }

    /**
     * Handle payment error
     */
    public function error($paymentCode)
    {
        $payment = Payment::where('code', $paymentCode)->firstOrFail();

        return redirect()->route('invoice.show', $paymentCode)
            ->with('error', 'Payment failed. Please try again or contact support.');
    }

    /**
     * Handle pending payment
     */
    public function pending($paymentCode)
    {
        $payment = Payment::where('code', $paymentCode)->firstOrFail();

        return redirect()->route('invoice.show', $paymentCode)
            ->with('info', 'Payment is being processed. Please wait for confirmation.');
    }

    /**
     * Handle Midtrans webhook notifications
     */
    public function notification(Request $request)
    {
        try {
            $notificationBody = json_decode($request->getContent(), true);
            $notificationHeader = $request->server('HTTP_X_CALLBACK_TOKEN');

            // Verify notification signature if needed
            // You can add signature verification here for security

            Log::info('Midtrans notification received', [
                'order_id' => $notificationBody['order_id'] ?? null,
                'status' => $notificationBody['transaction_status'] ?? null
            ]);

            // Handle the notification
            $this->paymentService->handleNotification($notificationBody);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Midtrans notification error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['status' => 'error'], 500);
        }
    }
}
