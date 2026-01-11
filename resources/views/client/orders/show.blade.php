@php
    $cartCount = collect(session('cart', []))->sum('quantity');
    $subtotal = $order->items->sum(fn ($item) => $item->price * $item->quantity);
@endphp

<x-client-layout>
    <div class="min-h-screen bg-[#4f8a63] bg-gradient-to-b from-[#4f8a63] to-[#3f6d51] text-white pb-24">
        <div class="max-w-4xl mx-auto px-4 pt-4 space-y-4">
            <div class="flex items-center justify-between">
                <a href="{{ route('orders.index') }}" class="w-11 h-11 flex items-center justify-center rounded-full bg-white/20 border border-white/30 backdrop-blur">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <h2 class="font-[Playfair_Display] text-lg tracking-wide">Detail Pesanan</h2>
                <a href="{{ route('cart.index') }}" class="relative w-11 h-11 flex items-center justify-center rounded-full bg-white/20 border border-white/30 backdrop-blur">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13-.8 18.2C-1.5 20 0 22 2.2 22h14.6c2.2 0 3.7-2 2.9-3.8L17 13H7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    @if($cartCount)
                        <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-semibold rounded-full px-2 py-0.5">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-4 text-gray-900 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500">Referensi</p>
                        <p class="text-sm font-semibold">{{ $order->payment_reference }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Status Pembayaran</p>
                        <p class="text-sm font-semibold capitalize">{{ str_replace('_', ' ', $order->payment_status) }}</p>
                    </div>
                </div>
                <div class="text-xs text-gray-500">
                    Tanggal: {{ $order->created_at->format('d M Y, H:i') }}
                </div>
                @if($order->payment_status === 'awaiting_payment')
                    <div class="rounded-xl bg-amber-50 text-amber-700 text-xs px-3 py-2">
                        Pembayaran belum dikirim. Silakan unggah bukti transfer untuk diproses.
                    </div>
                @elseif($order->payment_status === 'awaiting_confirmation')
                    <div class="rounded-xl bg-amber-50 text-amber-700 text-xs px-3 py-2">
                        Bukti pembayaran sudah diterima dan sedang menunggu konfirmasi admin.
                    </div>
                @else
                    <div class="rounded-xl bg-emerald-50 text-emerald-700 text-xs px-3 py-2">
                        Pembayaran sudah terkonfirmasi.
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-4 text-gray-900 space-y-3">
                <h3 class="text-sm font-semibold">Item Pesanan</h3>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100">
                                    @if($item->product?->hasMedia('products'))
                                        <img src="{{ $item->product->getFirstMediaUrl('products') }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No</div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-semibold leading-tight">{{ $item->product?->name ?? 'Produk' }}</p>
                                    <p class="text-xs text-gray-500">Qty {{ $item->quantity }}</p>
                                </div>
                            </div>
                            <p class="text-sm font-semibold text-[#4f8a63]">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="border-t pt-3 text-sm space-y-1">
                    <div class="flex items-center justify-between">
                        <span>Subtotal</span>
                        <span class="font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-gray-600">
                        <span>Ongkir ({{ $order->shipping_method ? ucfirst($order->shipping_method) : 'Belum dipilih' }})</span>
                        <span>Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-gray-900">
                        <span>Total</span>
                        <span class="font-semibold text-[#4f8a63]">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
                @if($order->payment_status === 'awaiting_payment')
                    <div class="pt-2">
                        <a href="{{ route('orders.payment', $order) }}" class="inline-flex items-center justify-center w-full rounded-xl bg-[#4f8a63] text-white text-sm font-semibold px-4 py-2.5">
                            Kirim Konfirmasi Pembayaran
                        </a>
                    </div>
                @endif
            </div>
        </div>

        @include('client.partials.bottom-nav', ['cartCount' => $cartCount])
    </div>
</x-client-layout>
