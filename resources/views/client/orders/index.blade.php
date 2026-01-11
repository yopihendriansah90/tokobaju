@php
    $cartCount = collect(session('cart', []))->sum('quantity');
@endphp

<x-client-layout>
    <div class="min-h-screen bg-[#4f8a63] bg-gradient-to-b from-[#4f8a63] to-[#3f6d51] text-white pb-24">
        <div class="max-w-5xl mx-auto px-4 pt-4 space-y-4">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="w-11 h-11 flex items-center justify-center rounded-full bg-white/20 border border-white/30 backdrop-blur">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <h2 class="font-[Playfair_Display] text-lg tracking-wide">Riwayat Transaksi</h2>
                <a href="{{ route('cart.index') }}" class="relative w-11 h-11 flex items-center justify-center rounded-full bg-white/20 border border-white/30 backdrop-blur">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13-.8 18.2C-1.5 20 0 22 2.2 22h14.6c2.2 0 3.7-2 2.9-3.8L17 13H7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    @if($cartCount)
                        <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-semibold rounded-full px-2 py-0.5">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('orders.index', ['status' => 'all']) }}" class="px-4 py-2 rounded-full text-sm font-semibold transition {{ $filter === 'all' ? 'bg-white text-[#4f8a63]' : 'bg-white/10 text-white/80 hover:bg-white/20' }}">
                    Semua
                </a>
                <a href="{{ route('orders.index', ['status' => 'unconfirmed']) }}" class="px-4 py-2 rounded-full text-sm font-semibold transition {{ $filter === 'unconfirmed' ? 'bg-white text-[#4f8a63]' : 'bg-white/10 text-white/80 hover:bg-white/20' }}">
                    Belum Dikonfirmasi
                </a>
            </div>

            <div class="space-y-4">
                @forelse($orders as $order)
                    <div class="bg-white rounded-2xl shadow-xl p-4 text-gray-900 space-y-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-500">Referensi</p>
                                <p class="text-sm font-semibold">{{ $order->payment_reference }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">Total</p>
                                <p class="text-sm font-semibold text-[#4f8a63]">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-600">
                            <span>{{ $order->created_at->format('d M Y, H:i') }}</span>
                            <span class="capitalize">{{ str_replace('_', ' ', $order->payment_status) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="text-xs text-gray-500">
                                {{ $order->items->count() }} item
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('orders.show', $order) }}" class="px-3 py-2 rounded-lg border border-gray-200 text-gray-700 text-xs font-semibold">Detail</a>
                                @if($order->payment_status === 'awaiting_payment')
                                    <a href="{{ route('orders.payment', $order) }}" class="px-3 py-2 rounded-lg bg-[#4f8a63] text-white text-xs font-semibold">Bayar Sekarang</a>
                                @elseif($order->payment_status === 'awaiting_confirmation')
                                    <span class="px-3 py-2 rounded-lg bg-amber-100 text-amber-700 text-xs font-semibold">Menunggu Konfirmasi</span>
                                @else
                                    <span class="px-3 py-2 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-semibold">Terverifikasi</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white/10 border border-white/15 rounded-2xl p-6 text-center text-white/80">
                        Belum ada transaksi.
                    </div>
                @endforelse
            </div>

            <div class="mt-2">
                {{ $orders->links() }}
            </div>
        </div>

        @include('client.partials.bottom-nav', ['cartCount' => $cartCount])
    </div>
</x-client-layout>
