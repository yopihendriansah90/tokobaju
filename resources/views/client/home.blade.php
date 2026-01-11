@php
    $cartCount = collect(session('cart', []))->sum('quantity');
    $sliderItems = $banners->map(function ($banner) {
        return [
            'image' => $banner->hasMedia('banner_image') ? $banner->getFirstMediaUrl('banner_image') : null,
            'title' => $banner->title,
            'subtitle' => $banner->subtitle,
            'cta_text' => $banner->cta_text,
            'cta_link' => $banner->cta_link,
        ];
    });
    if ($sliderItems->isEmpty()) {
        $sliderItems = collect([
            [
                'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=800&q=80',
                'title' => 'Koleksi Lebaran 50%',
                'subtitle' => 'Lengkapi gaya syar\'i terkini.',
                'cta_text' => 'Belanja Sekarang',
                'cta_link' => route('client.products.index'),
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&q=80',
                'title' => 'Makanan Sehat Diskon 10%',
                'subtitle' => 'Menu segar untuk keluarga.',
                'cta_text' => 'Lihat Menu',
                'cta_link' => route('client.products.index'),
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1542293779-32d05a2b75b0?auto=format&fit=crop&w=800&q=80',
                'title' => 'Gadget Terbaru',
                'subtitle' => 'Inovasi terkini di Disty Cellular.',
                'cta_text' => 'Cek Sekarang',
                'cta_link' => route('client.products.index'),
            ],
        ]);
    }
@endphp

<x-client-layout>
    <div class="min-h-screen bg-gradient-to-b from-[#4f8a63] via-[#457555] to-[#3f6d51] text-white pb-24">
        <div class="max-w-6xl mx-auto px-4 pt-6 space-y-8">
            {{-- Top bar search + cart --}}
            <div class="flex items-center space-x-3">
                <form action="{{ route('home') }}" method="GET" class="flex-1">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-[#4f8a63]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <input name="q" value="{{ $search }}" type="search" placeholder="Cari di Disty Mall.." class="w-full rounded-full bg-white/90 text-gray-900 placeholder-gray-500 pl-10 pr-4 py-3 shadow focus:ring-2 focus:ring-white focus:outline-none">
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
                </div>
                <a href="{{ route('cart.index') }}" class="relative flex items-center justify-center w-12 h-12 rounded-full glass-panel text-white hover:bg-white/20 transition">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13-.8 18.2C-1.5 20 0 22 2.2 22h14.6c2.2 0 3.7-2 2.9-3.8L17 13H7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    @if($cartCount)
                        <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-semibold rounded-full px-2 py-0.5">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>

            {{-- Logo / Brand --}}
            <div class="flex flex-col items-center text-center space-y-2">
                @php
                    $logoUrl = isset($siteSettings) && $siteSettings?->hasMedia('logo') ? $siteSettings->getFirstMediaUrl('logo') : null;
                @endphp
                <div class="w-20 h-20 rounded-full border border-white/60 flex items-center justify-center bg-white/10 overflow-hidden">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $siteSettings->site_name ?? 'Logo' }}" class="w-full h-full object-contain">
                    @else
                        <span class="text-3xl font-semibold tracking-[6px]">D</span>
                    @endif
                </div>
                <h1 class="text-2xl brand-heading">
                    {{ $siteSettings->site_name ?? 'DISTY MALL' }}
                </h1>
                <p class="text-sm text-white/80">
                    {{ $siteSettings->site_title ?? "Tampil Syar'i Gaya Masa Kini" }}
                </p>
            </div>

            {{-- Banner slider --}}
            <section
                x-data="{
                    current: 0,
                    items: {{ $sliderItems->values()->toJson() }},
                    next() { this.current = (this.current + 1) % this.items.length },
                    prev() { this.current = (this.current - 1 + this.items.length) % this.items.length },
                    autoplay() { setInterval(() => this.next(), 5000); }
                }"
                x-init="autoplay()"
                class="relative"
            >
                <div class="overflow-hidden rounded-3xl shadow-2xl border border-white/15">
                    <div class="flex transition-transform duration-500" :style="`transform: translateX(-${current * 100}%)`">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="w-full flex-shrink-0">
                                <div class="h-48 sm:h-56 relative">
                                    <template x-if="item.image">
                                        <img :src="item.image" class="w-full h-full object-cover" :alt="item.title">
                                    </template>
                                    <div class="absolute inset-0 bg-gradient-to-r from-[#3f6d51]/80 to-transparent"></div>
                                    <div class="absolute inset-0 p-5 flex flex-col justify-center max-w-lg">
                                        <p class="text-xs uppercase tracking-[0.2em] text-white/80">Promo</p>
                                        <h3 class="text-2xl font-semibold leading-tight mt-1" x-text="item.title"></h3>
                                        <p class="text-sm text-white/80 mt-1" x-text="item.subtitle"></p>
                                        <template x-if="item.cta_link">
                                            <a :href="item.cta_link" class="mt-4 inline-flex items-center px-4 py-2 rounded-full bg-white text-[#4f8a63] text-sm font-semibold hover:bg-white/90 transition">
                                                <span x-text="item.cta_text ?? 'Lihat Promo'"></span>
                                                <svg class="h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M5 12h14M12 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="absolute inset-0 flex items-center justify-between px-3 pointer-events-none">
                    <button @click="prev" class="w-9 h-9 rounded-full glass-panel flex items-center justify-center hover:bg-white/25 pointer-events-auto">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <button @click="next" class="w-9 h-9 rounded-full glass-panel flex items-center justify-center hover:bg-white/25 pointer-events-auto">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
                <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex space-x-2">
                    <template x-for="(item, index) in items" :key="index">
                        <span @click="current = index" class="w-2.5 h-2.5 rounded-full"
                              :class="current === index ? 'bg-white' : 'bg-white/50'"></span>
                    </template>
                </div>
            </section>

            {{-- Categories --}}
            <section class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="brand-heading text-lg">Kategori</h2>
                    <a href="{{ route('client.products.index') }}" class="text-sm text-white/80 hover:text-white">Lihat semua</a>
                </div>
                <div class="flex flex-nowrap gap-3 overflow-x-auto overflow-y-hidden overscroll-x-contain pb-2 -mx-4 px-4 md:grid md:grid-cols-8 md:gap-3 md:overflow-visible md:pb-0 md:mx-0 md:px-0">
                    @forelse($categories as $category)
                        <a href="{{ route('home', ['category' => $category->slug]) }}" class="flex flex-col items-center space-y-2 flex-none w-20 md:w-auto">
                            <div class="w-16 h-16 rounded-full bg-white text-[#4f8a63] flex items-center justify-center card-soft overflow-hidden ring-2 ring-white/70">
                                @if($category->hasMedia('category_icon'))
                                    <img src="{{ $category->getFirstMediaUrl('category_icon') }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 6v12m6-6H6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                @endif
                            </div>
                            <span class="text-xs text-white/90 text-center">{{ $category->name }}</span>
                        </a>
                    @empty
                        <p class="col-span-full text-white/80 text-sm">Belum ada kategori.</p>
                    @endforelse
                </div>
            </section>

            {{-- Featured products --}}
            <section class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="brand-heading text-lg">Produk Unggulan</h2>
                    <a href="{{ route('client.products.index', ['sort' => 'latest']) }}" class="text-sm text-white/80 hover:text-white">Lihat semua</a>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @forelse($featuredProducts as $product)
                        <a href="{{ route('client.products.show', $product) }}" class="bg-white rounded-2xl card-soft overflow-hidden text-gray-900 hover:-translate-y-1 transition transform">
                            @if($product->hasMedia('products'))
                                <img src="{{ $product->getFirstMediaUrl('products') }}" alt="{{ $product->name }}" class="w-full h-36 object-cover">
                            @else
                                <div class="w-full h-36 bg-gray-100 flex items-center justify-center text-gray-400">No Image</div>
                            @endif
                            <div class="p-3 space-y-1">
                                <h3 class="text-sm font-semibold line-clamp-2">{{ $product->name }}</h3>
                                <div class="flex items-center text-yellow-500 text-xs">
                                    @for($i=0; $i<5; $i++)
                                        <svg class="h-4 w-4 {{ $i < 4 ? 'text-yellow-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.487 7.09l6.572-.955L10 0l2.941 6.135 6.572.955-4.768 4.654 1.123 6.545z"/></svg>
                                    @endfor
                                    <span class="ml-1 text-gray-500">(4.5)</span>
                                </div>
                                <p class="text-base font-bold text-[#4f8a63]">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                        </a>
                    @empty
                        <p class="col-span-full text-white/80 text-sm">Belum ada produk.</p>
                    @endforelse
                </div>
            </section>

            {{-- Product grid with filters --}}
            <section class="space-y-5">
                <div class="flex flex-col gap-4">
                    <div class="flex items-end justify-between">
                        <div class="space-y-1">
                            <p class="text-[11px] uppercase tracking-[0.2em] text-white/60">Jelajahi</p>
                            <h2 class="brand-heading text-2xl">Produk</h2>
                        </div>
                        <span class="text-xs text-white/70 hidden sm:inline">Filter cepat</span>
                    </div>
                    <form action="{{ route('home') }}" method="GET" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-[1.2fr_1fr_auto] gap-3 rounded-2xl bg-white/95 p-3 shadow-lg ring-1 ring-white/60">
                        <input type="hidden" name="q" value="{{ $search }}">
                        <div class="col-span-2 sm:col-span-1 space-y-1">
                            <label class="text-[11px] uppercase tracking-wide text-gray-500">Kategori</label>
                            <select name="category" class="w-full rounded-xl bg-white text-gray-800 text-sm px-3 py-2.5 border border-gray-200 focus:ring-2 focus:ring-[#4f8a63]/30 focus:border-[#4f8a63]/40 focus:outline-none">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}" @selected($categorySlug === $category->slug)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[11px] uppercase tracking-wide text-gray-500">Urutkan</label>
                            <select name="sort" class="w-full rounded-xl bg-white text-gray-800 text-sm px-3 py-2.5 border border-gray-200 focus:ring-2 focus:ring-[#4f8a63]/30 focus:border-[#4f8a63]/40 focus:outline-none">
                                <option value="latest" @selected($sort === 'latest')>Terbaru</option>
                                <option value="price_asc" @selected($sort === 'price_asc')>Harga Terendah</option>
                                <option value="price_desc" @selected($sort === 'price_desc')>Harga Tertinggi</option>
                            </select>
                        </div>
                        <div class="col-span-2 sm:col-span-1 lg:col-span-1 flex items-end">
                            <button type="submit" class="w-full rounded-xl bg-[#4f8a63] text-white text-sm font-semibold px-4 py-2.5 shadow hover:bg-[#3f7453] transition">Terapkan</button>
                        </div>
                    </form>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                    @forelse($products as $product)
                        <a href="{{ route('client.products.show', $product) }}" class="bg-white rounded-3xl card-soft overflow-hidden text-gray-900 shadow-md ring-1 ring-black/5 hover:-translate-y-1.5 hover:shadow-xl transition transform">
                            @if($product->hasMedia('products'))
                                <img src="{{ $product->getFirstMediaUrl('products') }}" alt="{{ $product->name }}" class="w-full h-40 object-cover">
                            @else
                                <div class="w-full h-40 bg-gray-100 flex items-center justify-center text-gray-400">No Image</div>
                            @endif
                            <div class="p-3 space-y-1">
                                <p class="text-xs text-gray-500">{{ $product->category->name ?? 'Umum' }}</p>
                                <h3 class="text-sm font-semibold line-clamp-2">{{ $product->name }}</h3>
                                <div class="flex items-center text-yellow-500 text-xs">
                                    @for($i=0; $i<5; $i++)
                                        <svg class="h-4 w-4 {{ $i < 4 ? 'text-yellow-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.487 7.09l6.572-.955L10 0l2.941 6.135 6.572.955-4.768 4.654 1.123 6.545z"/></svg>
                                    @endfor
                                    <span class="ml-1 text-gray-500">(4.5)</span>
                                </div>
                                <p class="text-base font-bold text-[#4f8a63]">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                        </a>
                    @empty
                        <p class="col-span-full text-white/80 text-sm">Produk belum tersedia.</p>
                    @endforelse
                </div>

                <div class="mt-2">
                    {{ $products->links() }}
                </div>
            </section>
        </div>

        @include('client.partials.bottom-nav', ['cartCount' => $cartCount])
    </div>
</x-client-layout>
