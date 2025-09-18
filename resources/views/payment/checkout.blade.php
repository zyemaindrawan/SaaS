@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Complete Your Payment</h1>
            
            <!-- Payment Summary -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="font-semibold mb-3">Order Summary</h3>
                
                <div class="flex justify-between py-2">
                    <span>Template: {{ $payment->websiteContent->template->name }}</span>
                    <span>Rp {{ number_format($payment->websiteContent->template->price, 0, ',', '.') }}</span>
                </div>
                
                <div class="flex justify-between py-2 text-sm text-gray-600">
                    <span>Platform Fee</span>
                    <span>Rp {{ number_format($payment->fee, 0, ',', '.') }}</span>
                </div>
                
                <hr class="my-2">
                
                <div class="flex justify-between py-2 font-semibold text-lg">
                    <span>Total</span>
                    <span>Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Payment Button -->
            <div class="text-center">
                <button id="pay-button" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition duration-200">
                    Pay Now
                </button>
            </div>

            <!-- Payment Info -->
            <div class="mt-6 text-sm text-gray-600 text-center">
                <p>Payment Code: <strong>{{ $payment->code }}</strong></p>
                <p>Expires at: {{ $payment->expired_at->format('d M Y, H:i') }} WIB</p>
            </div>
        </div>
    </div>
</div>

<!-- Midtrans Snap -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script type="text/javascript">
document.getElementById('pay-button').onclick = function(){
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result) {
            window.location.href = '{{ route("payment.finish", $payment->code) }}';
        },
        onPending: function(result) {
            window.location.href = '{{ route("payment.finish", $payment->code) }}';
        },
        onError: function(result) {
            window.location.href = '{{ route("payment.error", $payment->code) }}';
        }
    });
};
</script>
@endsection
