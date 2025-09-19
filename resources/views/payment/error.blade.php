@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto text-center">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-yellow-500 text-6xl mb-4">🕓</div>
            
            <h1 class="text-3xl font-bold text-yellow-600 mb-4">Payment Error!</h1>
            
            <p class="text-gray-600 mb-6">
                Terjadi kesalahan saat melakukan pembayaran...
            </p>

            <div class="space-y-3">
                <a href="{{ route('dashboard') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200">
                    Go to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
