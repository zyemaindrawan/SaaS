@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto text-center">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-green-500 text-6xl mb-4">✓</div>
            
            <h1 class="text-3xl font-bold text-green-600 mb-4">Payment Successful!</h1>
            
            <p class="text-gray-600 mb-6">
                Your payment has been processed successfully. Your website will be activated within 6 hours.
            </p>

            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="font-semibold mb-3">Payment Details</h3>
                <div class="text-left space-y-2">
                    <div class="flex justify-between">
                        <span>Payment Code:</span>
                        <span class="font-mono">{{ $payment->code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Amount:</span>
                        <span>Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Paid At:</span>
                        <span>{{ $payment->paid_at->format('d M Y, H:i') }} WIB</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Template:</span>
                        <span>{{ $payment->websiteContent->template->name }}</span>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                <a href="{{ route('dashboard') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200">
                    Go to Dashboard
                </a>
                
                <p class="text-sm text-gray-500">
                    We'll send you an email notification when your website is ready!
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
