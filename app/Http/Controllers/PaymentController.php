<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Invalid JSON in Midtrans notification', [
                    'raw_content' => $request->getContent(),
                    'json_error' => json_last_error_msg()
                ]);
                return response()->json(['status' => 'error', 'message' => 'Invalid JSON'], 400);
            }

            $notificationHeader = $request->server('HTTP_X_CALLBACK_TOKEN');

            Log::info('Midtrans notification received', [
                'order_id' => $notificationBody['order_id'] ?? null,
                'transaction_status' => $notificationBody['transaction_status'] ?? null,
                'gross_amount' => $notificationBody['gross_amount'] ?? null,
                'payment_type' => $notificationBody['payment_type'] ?? null
            ]);

            // Handle the notification
            $this->paymentService->handleNotification($notificationBody);

            Log::info('Midtrans notification processed successfully', [
                'order_id' => $notificationBody['order_id'] ?? null
            ]);

            return response()->json(['status' => 'success']);

        } catch (\Throwable $e) {
            Log::error('Midtrans notification error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_content' => $request->getContent()
            ]);

            return response()->json(['status' => 'error', 'message' => 'Internal server error'], 500);
        }
    }

    /**
     * Get payment by website content ID
     */
    public function getByWebsiteContentId($websiteContentId)
    {
        try {
            $payment = Payment::where('website_content_id', $websiteContentId)
                ->where('user_id', Auth::id()) // Ensure user owns this payment
                ->latest()
                ->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found',
                    'payment' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'payment' => $payment
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching payment by website content ID: ' . $e->getMessage(), [
                'website_content_id' => $websiteContentId,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error fetching payment data',
                'payment' => null
            ], 500);
        }
    }
}
