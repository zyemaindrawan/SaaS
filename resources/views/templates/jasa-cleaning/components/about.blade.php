@php
    function resolveAssetPath2($path) {
        if (empty($path)) return null;
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) return $path;
        if (str_starts_with($path, 'website-assets/')) return url('storage/' . $path);
        if (str_starts_with($path, 'storage/')) return url($path);
        return url('storage/' . $path);
    }

    $aboutTitle = $content['about_title'] ?? 'Mengapa Memilih Kami?';
    $aboutContent = $content['about_content'] ?? '';
    $aboutImage = resolveAssetPath2($content['about_image'] ?? null);
    $companyTagline = $content['company_tagline'] ?? '';
@endphp

<section id="about" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Content -->
            <div class="order-2 lg:order-1">
                @if($companyTagline)
                    <p class="text-clean-yellow font-bold text-lg mb-2">{{ $companyTagline }}</p>
                @endif
                <h2 class="text-3xl md:text-4xl font-extrabold text-clean-blue mb-6">
                    {{ $aboutTitle }}
                </h2>
                <div class="text-gray-700 text-lg leading-relaxed space-y-4 mb-6">
                    {!! nl2br(e($aboutContent)) !!}
                </div>
                
                <!-- Features Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start bg-blue-50 p-4 rounded-lg">
                        <svg class="w-6 h-6 text-clean-blue mr-3 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="font-bold text-gray-900">Dry Cleaning</h4>
                            <p class="text-sm text-gray-600">Cepat kering, tidak basah</p>
                        </div>
                    </div>
                    <div class="flex items-start bg-blue-50 p-4 rounded-lg">
                        <svg class="w-6 h-6 text-clean-blue mr-3 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="font-bold text-gray-900">Garansi Bersih</h4>
                            <p class="text-sm text-gray-600">100% garansi puas</p>
                        </div>
                    </div>
                    <div class="flex items-start bg-blue-50 p-4 rounded-lg">
                        <svg class="w-6 h-6 text-clean-blue mr-3 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="font-bold text-gray-900">Tim Profesional</h4>
                            <p class="text-sm text-gray-600">Berpengalaman & terlatih</p>
                        </div>
                    </div>
                    <div class="flex items-start bg-blue-50 p-4 rounded-lg">
                        <svg class="w-6 h-6 text-clean-blue mr-3 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="font-bold text-gray-900">Bahan Aman</h4>
                            <p class="text-sm text-gray-600">Ramah lingkungan</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Image -->
            <div class="order-1 lg:order-2">
                @if($aboutImage)
                    <img src="{{ $aboutImage }}" alt="{{ $aboutTitle }}" class="w-full h-auto rounded-lg shadow-2xl object-cover">
                @else
                    <div class="w-full h-96 bg-gradient-to-br from-clean-blue-light to-clean-blue rounded-lg flex items-center justify-center">
                        <div class="text-center text-white p-8">
                            <svg class="w-24 h-24 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                            </svg>
                            <p class="text-lg font-semibold">Layanan Cuci Profesional</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
