@extends('layouts.app')
@section('title', 'Payment - ' . $payment->websiteContent->template->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-4xl mx-auto text-center">
                <div class="flex items-center justify-center mb-4">
                    <span class="text-sm font-medium bg-white bg-opacity-20 px-3 py-1 rounded-full">Step 3 of 3</span>
                </div>
                
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Selesaikan Pembayaran
                </h1>
                <p class="text-xl opacity-90 max-w-2xl mx-auto">
                    Satu langkah lagi untuk membuat website Anda online!
                </p>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success') || session('error') || session('info'))
        <div class="container mx-auto px-4 pt-6">
            @if(session('success'))
                <div class="max-w-4xl mx-auto mb-6">
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="max-w-4xl mx-auto mb-6">
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="max-w-4xl mx-auto mb-6">
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700 font-medium">{{ session('info') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Breadcrumb -->
    <div class="container mx-auto px-4 py-4">
        <nav class="flex max-w-4xl mx-auto" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <a href="{{ route('templates.index') }}" class="text-gray-400 hover:text-gray-500 transition duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                        <span class="sr-only">Templates</span>
                    </a>
                </li>
                <li>
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </li>
                <li>
                    <a href="{{ route('templates.show', $payment->websiteContent->template->slug) }}" class="text-gray-400 hover:text-gray-500 transition duration-200">
                        {{ $payment->websiteContent->template->name }}
                    </a>
                </li>
                <li>
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </li>
                <li class="text-gray-500 font-medium">
                    Payment
                </li>
            </ol>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto space-y-8">
            
            <!-- Order Summary Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                            <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                        Order Summary
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Template Preview -->
                        <div>
                            <img 
                                src="{{ $payment->websiteContent->template->preview_image ?? '/images/template-placeholder.jpg' }}" 
                                alt="{{ $payment->websiteContent->template->name }}"
                                class="w-full h-48 object-cover rounded-lg mb-4"
                            >
                            
                            <div>
                                <h4 class="font-bold text-xl text-gray-900 mb-2">
                                    {{ $payment->websiteContent->template->name }}
                                </h4>
                                
                                <!-- @if($payment->websiteContent->template->category)
                                    <span class="inline-block bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full mb-3">
                                        {{ ucwords(str_replace('-', ' ', $payment->websiteContent->template->category)) }}
                                    </span>
                                @endif -->
                                
                                @if($payment->websiteContent->template->description)
                                    <p class="text-gray-600 leading-relaxed">
                                        {{ $payment->websiteContent->template->description }}
                                    </p>
                                @endif
                            </div>

                            <button 
                            id="pay-button" 
                            class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold mt-2 py-4 px-12 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-lg mb-4"
                        >
                            <svg class="w-6 h-6 inline mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                            Bayar - Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}
                        </button>


                        </div>

                        <!-- Order Details -->
                        <div class="space-y-6">
                            <!-- Pricing Breakdown -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h5 class="font-semibold text-gray-900 mb-4">Price Breakdown</h5>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Template Price</span>
                                        <span class="font-medium">
                                            Rp {{ number_format($payment->websiteContent->template->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Platform Fee</span>
                                        <span class="text-gray-500">Rp {{ number_format($payment->fee, 0, ',', '.') }}</span>
                                    </div>
                                    
                                    <hr class="border-gray-200">
                                    
                                    <div class="flex justify-between text-xl font-bold">
                                        <span>Total Amount</span>
                                        <span class="text-green-600">Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Details -->
                            <div class="bg-blue-50 rounded-lg p-4">
                                <h5 class="font-semibold text-gray-900 mb-3">Payment Details</h5>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Payment Code:</span>
                                        <span class="font-mono font-medium">{{ $payment->code }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Website Address:</span>
                                        <span class="font-medium text-blue-600">{{ $payment->websiteContent->subdomain }}.oursite.com</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Payment Expires:</span>
                                        <span class="font-medium text-orange-600">{{ $payment->expired_at->format('d M Y, H:i') }} WIB</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support Contact -->
            <div class="bg-gray-50 rounded-xl p-6 text-center">
                <div class="max-w-2xl mx-auto">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Butuh Bantuan?</h3>
                    <p class="text-gray-600 mb-4">
                        Our support team is available 24/7 to assist you with any questions.
                    </p>
                    <div class="flex justify-center space-x-4">
                        <a href="#" class="text-blue-600 hover:text-blue-500 font-medium text-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                            Email
                        </a>
                        <a href="#" class="text-blue-600 hover:text-blue-500 font-medium text-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                            </svg>
                            Telepon
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Midtrans Snap -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    const payButton = document.getElementById('pay-button');
    
    if (payButton) {
        payButton.onclick = function(){
            // Add loading state
            payButton.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing Payment...
            `;
            payButton.disabled = true;

            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    window.location.href = '{{ route("payment.finish", $payment->code) }}';
                },
                onPending: function(result) {
                    window.location.href = '{{ route("payment.finish", $payment->code) }}';
                },
                onError: function(result) {
                    window.location.href = '{{ route("payment.error", $payment->code) }}';
                },
                onClose: function() {
                    // Reset button state
                    payButton.innerHTML = `
                        <svg class="w-6 h-6 inline mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                        Pay Securely - Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}
                    `;
                    payButton.disabled = false;
                }
            });
        };
    }
});
</script>
@endsection