<?php
namespace App\Http\Controllers;

use App\Models\WebsiteContent;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function checkout(WebsiteContent $websiteContent)
    {
        // Verify ownership
        if ($websiteContent->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if already has payment
        if ($websiteContent->payment) {
            return redirect()->route('payment.show', $websiteContent->payment->code);
        }

        // Check if content is in correct status
        if (!in_array($websiteContent->status, ['draft', 'previewed'])) {
            return redirect()->back()->with('error', 'Website content cannot be paid at this time.');
        }

        try {
            // Create payment
            $payment = $this->paymentService->createPayment($websiteContent);
            
            return redirect()->route('payment.show', $payment->code);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create payment: ' . $e->getMessage());
        }
    }

    public function show(string $paymentCode)
    {
        $payment = Payment::where('code', $paymentCode)
            ->with(['user', 'websiteContent.template'])
            ->firstOrFail();

        // Verify ownership
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        // If already paid, redirect to success page
        if ($payment->status === 'paid') {
            return view('payment.success', compact('payment'));
        }

        // If expired, show expired page
        if ($payment->expired_at < now()) {
            $payment->update(['status' => 'expired']);
            return view('payment.expired', compact('payment'));
        }

        try {
            $snapToken = $this->paymentService->getSnapToken($payment);
            return view('payment.checkout', compact('payment', 'snapToken'));
        } catch (\Exception $e) {
            return view('payment.error', [
                'payment' => $payment,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function notification(Request $request)
    {
        try {
            $this->paymentService->handleNotification($request->all());
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function finish(string $paymentCode)
    {
        $payment = Payment::where('code', $paymentCode)->firstOrFail();
        
        if ($payment->status === 'paid') {
            return view('payment.success', compact('payment'));
        }
        
        return view('payment.pending', compact('payment'));
    }

    public function unfinish(string $paymentCode)
    {
        $payment = Payment::where('code', $paymentCode)->firstOrFail();
        return view('payment.unfinish', compact('payment'));
    }

    public function error(string $paymentCode)
    {
        $payment = Payment::where('code', $paymentCode)->firstOrFail();
        return view('payment.error', compact('payment'));
    }
}
