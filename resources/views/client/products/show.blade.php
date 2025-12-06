@php
    $cartCount = collect(session('cart', []))->sum('quantity');
@endphp

<x-client-layout>
    <div class="min-h-screen bg-[#4f8a63] bg-gradient-to-b from-[#4f8a63] to-[#3f6d51] text-white pb-28">
        <div class="max-w-5xl mx-auto px-4 pt-4">
            {{-- Header --}}
            <div class="flex items-center justify-between">
                <a href="{{ url()->previous() === url()->current() ? route('home') : url()->previous() }}" class="w-11 h-11 flex items-center justify-center rounded-full bg-white/20 border border-white/30 backdrop-blur">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <h2 class="font-[Playfair_Display] text-lg tracking-wide">Product Detail</h2>
                <div class="flex items-center space-x-2">
                    @auth
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-2 rounded-full bg-white/20 border border-white/30 text-sm hover:bg-white/30 transition">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-2 rounded-full bg-white/20 border border-white/30 text-sm hover:bg-white/30 transition">Login</a>
                    @endauth
                    <a href="{{ route('cart.index') }}" class="relative flex items-center justify-center w-11 h-11 rounded-full bg-white/20 border border-white/30 backdrop-blur text-white hover:bg-white/30 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13-.8 18.2C-1.5 20 0 22 2.2 22h14.6c2.2 0 3.7-2 2.9-3.8L17 13H7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        @if($cartCount)
                            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-semibold rounded-full px-2 py-0.5">{{ $cartCount }}</span>
                        @endif
                    </a>
                </div>
            </div>

            {{-- Product media --}}
            <div class="mt-6 bg-white rounded-3xl shadow-xl overflow-hidden">
                @if($product->hasMedia('products'))
                    <img src="{{ $product->getFirstMediaUrl('products') }}" alt="{{ $product->name }}" class="w-full h-80 object-cover">
                @else
                    <div class="w-full h-80 bg-gray-100 flex items-center justify-center text-gray-400">No Image</div>
                @endif
            </div>

            {{-- Info --}}
            <div class="bg-white text-gray-900 rounded-3xl shadow-xl p-5 -mt-8 relative z-10 space-y-4">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm text-gray-500">{{ $product->category->name ?? 'Umum' }}</p>
                        <h1 class="text-2xl font-semibold leading-snug">{{ $product->name }}</h1>
                        <div class="flex items-center text-yellow-500 text-sm mt-1">
                            @for($i=0; $i<5; $i++)
                                <svg class="h-4 w-4 {{ $i < 4 ? 'text-yellow-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.487 7.09l6.572-.955L10 0l2.941 6.135 6.572.955-4.768 4.654 1.123 6.545z"/></svg>
                            @endfor
                            <span class="ml-1 text-gray-500">4.5</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Harga</p>
                        <p class="text-2xl font-bold text-[#4f8a63]">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500">Stok: {{ $product->stock }}</p>
                    </div>
                </div>

                <div class="border-t pt-4 space-y-3">
                    <h3 class="font-semibold text-gray-800">Deskripsi</h3>
                    <div class="text-sm text-gray-700 leading-relaxed">
                        {!! $product->description ?? 'Detail produk belum tersedia.' !!}
                    </div>
                </div>

                <div class="border-t pt-4 space-y-3">
                    <h3 class="font-semibold text-gray-800">Keunggulan Produk</h3>
                    @if($product->highlights)
                        @php
                            $highlights = preg_split('/\r\n|\r|\n/', trim($product->highlights));
                        @endphp
                        <ul class="text-sm text-gray-700 space-y-1">
                            @foreach($highlights as $item)
                                @if(strlen(trim($item)) > 0)
                                    <li class="flex items-start gap-2">
                                        <span class="mt-1 block w-2 h-2 rounded-full bg-[#4f8a63]"></span>
                                        <span>{{ trim($item) }}</span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li class="flex items-start gap-2"><span class="mt-1 block w-2 h-2 rounded-full bg-[#4f8a63]"></span> Kualitas bahan premium dan adem.</li>
                            <li class="flex items-start gap-2"><span class="mt-1 block w-2 h-2 rounded-full bg-[#4f8a63]"></span> Warna netral mudah dipadukan.</li>
                            <li class="flex items-start gap-2"><span class="mt-1 block w-2 h-2 rounded-full bg-[#4f8a63]"></span> Cocok untuk aktivitas harian dan acara.</li>
                        </ul>
                    @endif
                </div>

                <div class="border-t pt-4 space-y-3">
                    <h3 class="font-semibold text-gray-800">Produk Terkait</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @forelse($relatedProducts as $related)
                            <a href="{{ route('client.products.show', $related) }}" class="bg-gray-50 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                                @if($related->hasMedia('products'))
                                    <img src="{{ $related->getFirstMediaUrl('products') }}" alt="{{ $related->name }}" class="w-full h-24 object-cover">
                                @else
                                    <div class="w-full h-24 bg-gray-200 flex items-center justify-center text-gray-400">No Image</div>
                                @endif
                                <div class="p-2">
                                    <p class="text-xs text-gray-500">{{ $related->category->name ?? 'Umum' }}</p>
                                    <h4 class="text-xs font-semibold line-clamp-2">{{ $related->name }}</h4>
                                    <p class="text-sm font-bold text-[#4f8a63] mt-1">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                                </div>
                            </a>
                        @empty
                            <p class="col-span-full text-sm text-gray-500">Belum ada produk terkait.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Action bar --}}
        <div class="fixed bottom-0 left-0 w-full bg-white shadow-2xl border-t border-gray-200 p-4 z-50">
            <div class="max-w-5xl mx-auto flex items-center gap-3">
                @if($product->stock > 0)
                    <form action="{{ route('cart.store', $product) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full px-4 py-3 border-2 border-[#4f8a63] text-[#4f8a63] font-semibold rounded-xl hover:bg-[#4f8a63] hover:text-white transition">
                            Tambahkan ke Keranjang
                        </button>
                    </form>
                    <form action="{{ route('cart.store', $product) }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="redirect" value="checkout">
                        <button type="submit" class="w-full px-4 py-3 bg-[#4f8a63] text-white font-semibold rounded-xl shadow hover:bg-[#3f704f] transition">
                            Beli Sekarang
                        </button>
                    </form>
                @else
                    <button class="flex-1 px-4 py-3 bg-gray-300 text-gray-600 font-semibold rounded-xl cursor-not-allowed" disabled>
                        Stok Habis
                    </button>
                    <button class="flex-1 px-4 py-3 bg-gray-300 text-gray-600 font-semibold rounded-xl cursor-not-allowed" disabled>
                        Tidak Tersedia
                    </button>
                @endif
            </div>
        </div>
    </div>

    @include('client.partials.bottom-nav', ['cartCount' => $cartCount])
</x-client-layout>
