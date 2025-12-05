@php
    $cartCount = $cartCount ?? collect(session('cart', []))->sum('quantity');
@endphp
<nav class="fixed bottom-0 left-0 w-full bg-[#3f6d51] border-t border-white/10 text-white p-3 lg:hidden z-40 shadow-[0_-4px_12px_rgba(0,0,0,0.2)]">
    <div class="flex justify-around">
        <a href="{{ route('home') }}" class="flex flex-col items-center px-3 py-1 rounded-xl transition {{ request()->routeIs('home') ? 'text-white bg-[#4f8a63]' : 'text-white/80 hover:text-white hover:bg-[#4f8a63]' }}">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span class="text-xs mt-1">Home</span>
        </a>
        <a href="{{ route('client.products.index') }}" class="flex flex-col items-center px-3 py-1 rounded-xl transition {{ request()->routeIs('client.products.*') ? 'text-white bg-[#4f8a63]' : 'text-white/80 hover:text-white hover:bg-[#4f8a63]' }}">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M4 6h16M4 12h16M4 18h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span class="text-xs mt-1">Produk</span>
        </a>
        <a href="{{ route('cart.index') }}" class="flex flex-col items-center relative px-3 py-1 rounded-xl transition {{ request()->routeIs('cart.*') || request()->routeIs('checkout.*') ? 'text-white bg-[#4f8a63]' : 'text-white/80 hover:text-white hover:bg-[#4f8a63]' }}">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13-.8 18.2C-1.5 20 0 22 2.2 22h14.6c2.2 0 3.7-2 2.9-3.8L17 13H7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            @if($cartCount)
                <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-semibold rounded-full px-2 py-0.5">{{ $cartCount }}</span>
            @endif
            <span class="text-xs mt-1">Keranjang</span>
        </a>
        <a href="{{ route('client.about') }}" class="flex flex-col items-center px-3 py-1 rounded-xl transition {{ request()->routeIs('client.about') ? 'text-white bg-[#4f8a63]' : 'text-white/80 hover:text-white hover:bg-[#4f8a63]' }}">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span class="text-xs mt-1">Tentang</span>
        </a>
    </div>
</nav>
