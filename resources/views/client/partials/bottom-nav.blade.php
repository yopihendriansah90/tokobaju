@php
    $cartCount = $cartCount ?? collect(session('cart', []))->sum('quantity');
@endphp
<nav class="fixed bottom-0 left-0 w-full bg-[#3f6d51]/95 backdrop-blur border-t border-white/10 text-white px-4 py-3 lg:hidden z-40 shadow-[0_-6px_18px_rgba(0,0,0,0.25)]">
    <div class="mx-auto flex max-w-md items-center justify-between">
        <a href="{{ route('home') }}" class="group flex flex-col items-center gap-1 rounded-2xl px-3 py-2 transition {{ request()->routeIs('home') ? 'bg-white/15 text-white' : 'text-white/80 hover:text-white hover:bg-white/10' }}">
            <span class="flex h-10 w-10 items-center justify-center rounded-full {{ request()->routeIs('home') ? 'bg-white/15' : 'bg-white/5 group-hover:bg-white/10' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
            <span class="text-[11px] font-medium tracking-wide">Home</span>
        </a>
        <a href="{{ route('client.products.index') }}" class="group flex flex-col items-center gap-1 rounded-2xl px-3 py-2 transition {{ request()->routeIs('client.products.*') ? 'bg-white/15 text-white' : 'text-white/80 hover:text-white hover:bg-white/10' }}">
            <span class="flex h-10 w-10 items-center justify-center rounded-full {{ request()->routeIs('client.products.*') ? 'bg-white/15' : 'bg-white/5 group-hover:bg-white/10' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M4 5h16v4H4zM4 11h7v8H4zM13 11h7v8h-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
            <span class="text-[11px] font-medium tracking-wide">Produk</span>
        </a>
        <a href="{{ route('cart.index') }}" class="group relative flex flex-col items-center gap-1 rounded-2xl px-3 py-2 transition {{ request()->routeIs('cart.*') || request()->routeIs('checkout.*') ? 'bg-white/15 text-white' : 'text-white/80 hover:text-white hover:bg-white/10' }}">
            <span class="flex h-10 w-10 items-center justify-center rounded-full {{ request()->routeIs('cart.*') || request()->routeIs('checkout.*') ? 'bg-white/15' : 'bg-white/5 group-hover:bg-white/10' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13-.8 18.2C-1.5 20 0 22 2.2 22h14.6c2.2 0 3.7-2 2.9-3.8L17 13H7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
            @if($cartCount)
                <span class="absolute right-2 top-1.5 bg-red-600 text-white text-[10px] font-semibold rounded-full px-2 py-0.5">{{ $cartCount }}</span>
            @endif
            <span class="text-[11px] font-medium tracking-wide">Keranjang</span>
        </a>
        <a href="{{ route('client.about') }}" class="group flex flex-col items-center gap-1 rounded-2xl px-3 py-2 transition {{ request()->routeIs('client.about') ? 'bg-white/15 text-white' : 'text-white/80 hover:text-white hover:bg-white/10' }}">
            <span class="flex h-10 w-10 items-center justify-center rounded-full {{ request()->routeIs('client.about') ? 'bg-white/15' : 'bg-white/5 group-hover:bg-white/10' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
            <span class="text-[11px] font-medium tracking-wide">Tentang</span>
        </a>
    </div>
</nav>
