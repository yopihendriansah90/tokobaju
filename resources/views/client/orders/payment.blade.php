@php
    $cartCount = collect(session('cart', []))->sum('quantity');
@endphp

<x-client-layout>
    <div class="min-h-screen bg-[#4f8a63] bg-gradient-to-b from-[#4f8a63] to-[#3f6d51] text-white pb-24">
        <div class="max-w-3xl mx-auto px-4 pt-4 space-y-4">
            <div class="flex items-center justify-between">
                <a href="{{ route('orders.index') }}" class="w-11 h-11 flex items-center justify-center rounded-full bg-white/20 border border-white/30 backdrop-blur">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <h2 class="font-[Playfair_Display] text-lg tracking-wide">Pembayaran</h2>
                <a href="{{ route('cart.index') }}" class="relative w-11 h-11 flex items-center justify-center rounded-full bg-white/20 border border-white/30 backdrop-blur">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13-.8 18.2C-1.5 20 0 22 2.2 22h14.6c2.2 0 3.7-2 2.9-3.8L17 13H7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    @if($cartCount)
                        <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-semibold rounded-full px-2 py-0.5">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>

            <div class="bg-white/10 border border-white/15 rounded-2xl p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold">Langkah 2: Pembayaran</p>
                        <p class="text-xs text-white/80">Lakukan transfer dan unggah bukti pembayaran.</p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-white/20 text-xs">Konfirmasi Manual</span>
                </div>
                <div class="text-xs text-white/80">
                    Rekening contoh: BCA 123456789 a.n Disty Mall
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-4 text-gray-900 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500">Nomor Referensi</p>
                        <p class="text-sm font-semibold">{{ $order->payment_reference }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Total Tagihan</p>
                        <p class="text-base font-semibold text-[#4f8a63]">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="text-xs text-gray-500">
                    Status pembayaran: <span class="font-semibold capitalize text-gray-700">{{ str_replace('_', ' ', $order->payment_status) }}</span>
                </div>
            </div>

            <form action="{{ route('orders.payment.store', $order) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-xl p-4 text-gray-900 space-y-4">
                @csrf
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-800">Upload Bukti Transfer</label>
                    <input type="file" name="payment_proof" accept="image/*,.pdf" class="w-full rounded-xl border-gray-200 focus:ring-[#4f8a63]" required>
                    <p class="text-xs text-gray-500">Format jpg, png, atau pdf. Maks 4MB.</p>
                    @error('payment_proof')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-800">Catatan Pembayaran (Opsional)</label>
                    <textarea name="payment_notes" rows="2" class="w-full rounded-xl border-gray-200 focus:ring-[#4f8a63]">{{ old('payment_notes', $order->payment_notes) }}</textarea>
                    @error('payment_notes')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center justify-between pt-2">
                    <p class="text-sm text-gray-600">Pastikan nominal dan referensi sesuai pesanan.</p>
                    <button type="submit" class="px-5 py-3 bg-[#4f8a63] text-white font-semibold rounded-xl shadow hover:bg-[#3f704f] transition">
                        Kirim Bukti
                    </button>
                </div>
            </form>
        </div>

        @include('client.partials.bottom-nav', ['cartCount' => $cartCount])
    </div>
</x-client-layout>
