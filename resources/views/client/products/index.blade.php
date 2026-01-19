@php
    $cartCount = collect(session('cart', []))->sum('quantity');
@endphp

<x-client-layout>
    <div class="min-h-screen bg-[#4f8a63] bg-gradient-to-b from-[#4f8a63] to-[#3f6d51] text-white pb-24">
        <div class="max-w-6xl mx-auto px-4 pt-6">
            {{-- Header --}}
            <div class="flex items-center space-x-3">
                <a href="{{ route('home') }}" class="w-11 h-11 flex items-center justify-center rounded-full bg-white/20 border border-white/30 backdrop-blur">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <form action="{{ route('client.products.index') }}" method="GET" class="flex-1">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-[#4f8a63]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <input name="q" value="{{ $search }}" type="search" placeholder="Cari produk..." class="w-full rounded-full bg-white text-gray-900 placeholder-gray-400 pl-10 pr-4 py-3 shadow focus:ring-2 focus:ring-white focus:outline-none">
                        <input type="hidden" name="category" value="{{ $categorySlug }}">
                        <input type="hidden" name="sort" value="{{ $sort }}">
                    </div>
                </form>
                <div class="flex items-center space-x-2">
                    @auth
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-2 rounded-full bg-white/20 border border-white/30 text-sm hover:bg-white/30 transition">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-2 rounded-full bg-white/20 border border-white/30 text-sm hover:bg-white/30 transition">Login</a>
                    @endauth
                    <a href="{{ route('cart.index') }}" class="relative flex items-center justify-center w-12 h-12 rounded-full bg-white/20 border border-white/30 backdrop-blur text-white hover:bg-white/30 transition">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13-.8 18.2C-1.5 20 0 22 2.2 22h14.6c2.2 0 3.7-2 2.9-3.8L17 13H7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        @if($cartCount)
                            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-semibold rounded-full px-2 py-0.5">{{ $cartCount }}</span>
                        @endif
                    </a>
                </div>
            </div>

            {{-- Filters --}}
            <div class="mt-6 bg-white/10 backdrop-blur rounded-2xl border border-white/15 p-4 space-y-4">
                <div class="flex flex-wrap gap-2">
                    @foreach($categories as $category)
                        <a href="{{ route('client.products.index', ['category' => $category->slug, 'sort' => $sort, 'q' => $search]) }}"
                           class="px-3 py-1.5 rounded-full text-sm border {{ $categorySlug === $category->slug ? 'bg-white text-[#4f8a63] border-white' : 'bg-white/10 text-white border-white/20 hover:bg-white/20' }} transition">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <form action="{{ route('client.products.index') }}" method="GET" class="flex flex-wrap gap-2 items-center">
                        <input type="hidden" name="category" value="{{ $categorySlug }}">
                        <input type="hidden" name="q" value="{{ $search }}">
                        <label class="text-sm text-white/80">Urutkan:</label>
                        <select name="sort" class="rounded-full bg-white/90 text-gray-800 text-sm px-3 py-2 focus:ring-2 focus:ring-white/60 focus:outline-none">
                            <option value="latest" @selected($sort === 'latest')>Terbaru</option>
                            <option value="price_asc" @selected($sort === 'price_asc')>Harga Terendah</option>
                            <option value="price_desc" @selected($sort === 'price_desc')>Harga Tertinggi</option>
                        </select>
                        <button type="submit" class="rounded-full bg-white text-[#4f8a63] text-sm font-semibold px-4 py-2 shadow hover:bg-white/90 transition">Terapkan</button>
                    </form>
                    <a href="{{ route('client.products.index') }}" class="text-sm text-white/80 underline">Reset</a>
                </div>
            </div>

            {{-- Product grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mt-6">
                @forelse ($products as $product)
                    <a href="{{ route('client.products.show', $product) }}" class="group bg-white rounded-xl border border-gray-200 overflow-hidden text-gray-900 shadow-sm hover:shadow-md transition">
                        <div class="relative">
                            @if($product->hasMedia('products'))
                                <img src="{{ $product->getFirstMediaUrl('products') }}" alt="{{ $product->name }}" class="w-full aspect-square object-cover">
                            @else
                                <div class="w-full aspect-square bg-gray-100 flex items-center justify-center text-gray-400">No Image</div>
                            @endif
                            <span class="absolute top-2 left-2 text-[10px] uppercase bg-white/95 text-gray-700 border border-gray-200 px-2 py-0.5 rounded">
                                {{ $product->category->name ?? 'Umum' }}
                            </span>
                        </div>
                        <div class="p-3 space-y-1.5">
                            <h3 class="text-sm font-medium leading-snug line-clamp-2">{{ $product->name }}</h3>
                            <p class="text-base font-semibold text-[#e0462b]">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <div class="flex items-center text-yellow-500 text-xs">
                                @for($i=0; $i<5; $i++)
                                    <svg class="h-4 w-4 {{ $i < 4 ? 'text-yellow-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.487 7.09l6.572-.955L10 0l2.941 6.135 6.572.955-4.768 4.654 1.123 6.545z"/></svg>
                                @endfor
                                <span class="ml-1 text-gray-500">4.5</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="col-span-full text-white/80 text-sm">Produk belum tersedia.</p>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>

        @include('client.partials.bottom-nav', ['cartCount' => $cartCount])
    </div>
</x-client-layout>
