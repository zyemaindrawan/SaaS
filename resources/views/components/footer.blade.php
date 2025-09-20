<footer class="bg-gray-800 text-gray-300 py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h4 class="font-bold text-white mb-4">{{env('APP_NAME')}}</h4>
                <p class="text-sm">Jasa Pembuatan Website Profesional Murah, Cepat dan Terpercaya</p>
            </div>
            <div>
                <h5 class="font-semibold text-white mb-4">Layanan</h5>
                <ul class="space-y-2 text-sm">
                    <li><a class="hover:text-white transition duration-200" href="#">Jasa Pembuatan Website</a></li>
                    <li><a class="hover:text-white transition duration-200" href="#">Jasa Desain Website</a></li>
                    <li><a class="hover:text-white transition duration-200" href="#">Jasa SEO Website</a></li>
                    <li><a class="hover:text-white transition duration-200" href="{{route('templates.index')}}">Template Website</a></li>
                </ul>
            </div>
            <div>
                <h5 class="font-semibold text-white mb-4">Alamat</h5>
                <p class="text-sm">Jl. Jenderal Sudirman No.Kav. 52-53, Senayan, Kebayoran Baru, Kota Jakarta Selatan, DKI Jakarta 12190</p>
            </div>
            <div>
                <h5 class="font-semibold text-white mb-4">Pembayaran</h5>
                <p class="text-sm">BCA, Mandiri, BNI, BRI, OVO, Gopay, Dana, & Alfamart</p>
            </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-6 text-center text-sm">
            <p>Copyright &copy; {{ date('Y') }}. All rights reserved.</p>
        </div>
    </div>
</footer>