<x-client-layout>
    <div class="min-h-screen bg-[#4f8a63] bg-gradient-to-b from-[#4f8a63] to-[#3f6d51] text-white flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-6 text-center text-gray-900 space-y-4">
            <div class="w-16 h-16 mx-auto rounded-full bg-[#4f8a63] flex items-center justify-center text-white">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <h1 class="text-xl font-semibold">Pesanan Berhasil Dibuat</h1>
            <p class="text-sm text-gray-600">Kami akan memproses setelah pembayaran dikonfirmasi. Simpan nomor referensi Anda.</p>
            <div class="bg-gray-50 rounded-xl p-4 text-left space-y-1">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Referensi</span>
                    <span class="font-semibold">{{ $order->payment_reference }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Total</span>
                    <span class="font-semibold text-[#4f8a63]">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Status Pembayaran</span>
                    <span class="font-semibold capitalize">{{ str_replace('_', ' ', $order->payment_status) }}</span>
                </div>
            </div>
            <div class="space-y-2">
                <a href="{{ route('home') }}" class="block w-full bg-[#4f8a63] text-white rounded-xl py-3 font-semibold">Kembali ke Beranda</a>
                <a href="{{ route('client.products.index') }}" class="block w-full bg-white text-[#4f8a63] border border-[#4f8a63] rounded-xl py-3 font-semibold">Lihat Produk Lain</a>
            </div>
        </div>
    </div>
</x-client-layout>
