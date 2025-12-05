@php
    $cartCount = $cartCount ?? collect(session('cart', []))->sum('quantity');
@endphp
<header class="hidden lg:block fixed top-0 left-0 right-0 z-40">
    <div class="max-w-6xl mx-auto px-6 py-3 flex items-center justify-between bg-white/90 backdrop-blur shadow-lg rounded-b-3xl">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 rounded-full border border-[#4f8a63] flex items-center justify-center text-[#4f8a63] bg-[#f6f3eb]">
                <span class="text-xl font-semibold tracking-[0.2em]">D</span>
            </div>
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-gray-500">Disty Mall</p>
                <h1 class="brand-heading text-lg text-[#4f8a63]">Tampil Syar'i Gaya Masa Kini</h1>
            </div>
        </div>
        <nav class="flex items-center space-x-4 text-sm font-medium">
            <a href="{{ route('home') }}" class="px-3 py-2 rounded-full {{ request()->routeIs('home') ? 'bg-[#4f8a63] text-white' : 'text-gray-700 hover:bg-[#dce9e0]' }}">Home</a>
            <a href="{{ route('client.products.index') }}" class="px-3 py-2 rounded-full {{ request()->routeIs('client.products.*') ? 'bg-[#4f8a63] text-white' : 'text-gray-700 hover:bg-[#dce9e0]' }}">Produk</a>
            <a href="{{ route('client.about') }}" class="px-3 py-2 rounded-full {{ request()->routeIs('client.about') ? 'bg-[#4f8a63] text-white' : 'text-gray-700 hover:bg-[#dce9e0]' }}">Tentang</a>
            @auth
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-2 rounded-full text-gray-700 hover:bg-[#dce9e0]">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="px-3 py-2 rounded-full text-gray-700 hover:bg-[#dce9e0]">Login</a>
            @endauth
            <a href="{{ route('cart.index') }}" class="relative px-3 py-2 rounded-full text-gray-700 hover:bg-[#dce9e0] flex items-center space-x-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13-.8 18.2C-1.5 20 0 22 2.2 22h14.6c2.2 0 3.7-2 2.9-3.8L17 13H7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span>Keranjang</span>
                @if($cartCount)
                    <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[11px] font-semibold rounded-full px-2 py-0.5">{{ $cartCount }}</span>
                @endif
            </a>
        </nav>
    </div>
</header>
