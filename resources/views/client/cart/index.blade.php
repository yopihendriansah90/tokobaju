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
                <h2 class="font-[Playfair_Display] text-lg tracking-wide">Keranjang</h2>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('client.products.index') }}" class="w-11 h-11 flex items-center justify-center rounded-full bg-white/20 border border-white/30 backdrop-blur">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M4 6h16M4 12h16M4 18h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-2 rounded-full bg-white/20 border border-white/30 text-sm hover:bg-white/30 transition">Logout</button>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-white/20 border border-white/30 text-white px-4 py-3 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-600/80 border border-red-500 text-white px-4 py-3 rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            @if($cartItems->isEmpty())
                <div class="bg-white/10 border border-white/15 rounded-2xl p-6 text-center">
                    <p class="text-sm text-white/80">Keranjang masih kosong.</p>
                    <a href="{{ route('client.products.index') }}" class="inline-flex items-center mt-3 px-4 py-2 rounded-full bg-white text-[#4f8a63] font-semibold">Belanja Sekarang</a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($cartItems as $item)
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden text-gray-900">
                            <div class="flex gap-3 p-3">
                                <div class="w-24 h-24 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                                    @if($item['product']->hasMedia('products'))
                                        <img src="{{ $item['product']->getFirstMediaUrl('products') }}" alt="{{ $item['product']->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">No Image</div>
                                    @endif
                                </div>
                                <div class="flex-1 flex flex-col gap-2">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <p class="text-xs text-gray-500">{{ $item['product']->category->name ?? 'Umum' }}</p>
                                            <h3 class="text-sm font-semibold leading-snug">{{ $item['product']->name }}</h3>
                                        </div>
                                        <form action="{{ route('cart.destroy', $item['product']) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-500">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <form action="{{ route('cart.update', $item['product']) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <label class="text-xs text-gray-500">Qty</label>
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 rounded-lg border border-gray-300 px-2 py-1 text-sm">
                                            <button type="submit" class="text-xs font-semibold text-[#4f8a63]">Update</button>
                                        </form>
                                        <p class="text-sm font-bold text-[#4f8a63]">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="bg-white/15 border border-white/20 rounded-2xl p-4 space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span>Subtotal</span>
                        <span class="font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-white/80">
                        <span>Ongkir</span>
                        <span>Ditentukan saat checkout</span>
                    </div>
                </div>

                <a href="{{ route('checkout.create') }}" class="block w-full text-center bg-white text-[#4f8a63] font-semibold rounded-xl py-3 shadow-lg hover:bg-white/90 transition">
                    Lanjut ke Checkout
                </a>
            @endif
        </div>

        @include('client.partials.bottom-nav', ['cartCount' => $cartCount])
    </div>
</x-client-layout>
