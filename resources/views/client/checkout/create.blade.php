@php
    $cartCount = collect(session('cart', []))->sum('quantity');
@endphp

<x-client-layout>
    <div class="min-h-screen bg-[#4f8a63] bg-gradient-to-b from-[#4f8a63] to-[#3f6d51] text-white pb-24">
        <div class="max-w-5xl mx-auto px-4 pt-4 space-y-4">
            <div class="flex items-center justify-between">
                <a href="{{ route('cart.index') }}" class="w-11 h-11 flex items-center justify-center rounded-full bg-white/20 border border-white/30 backdrop-blur">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <h2 class="font-[Playfair_Display] text-lg tracking-wide">Checkout</h2>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('cart.index') }}" class="relative w-11 h-11 flex items-center justify-center rounded-full bg-white/20 border border-white/30 backdrop-blur">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13-.8 18.2C-1.5 20 0 22 2.2 22h14.6c2.2 0 3.7-2 2.9-3.8L17 13H7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        @if($cartCount)
                            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-semibold rounded-full px-2 py-0.5">{{ $cartCount }}</span>
                        @endif
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-2 rounded-full bg-white/20 border border-white/30 text-sm hover:bg-white/30 transition">Logout</button>
                    </form>
                </div>
            </div>

            <div class="bg-white/10 border border-white/15 rounded-2xl p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold">Langkah 1: Checkout</p>
                        <p class="text-xs text-white/80">Lengkapi data pengiriman, lalu lanjut ke pembayaran.</p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-white/20 text-xs">Pembayaran di langkah 2</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-4 text-gray-900 space-y-4">
                <h3 class="font-semibold">Ringkasan Pesanan</h3>
                <div class="space-y-3">
                    @foreach($cartItems as $item)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100">
                                    @if($item['product']->hasMedia('products'))
                                        <img src="{{ $item['product']->getFirstMediaUrl('products') }}" alt="{{ $item['product']->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No</div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-semibold leading-tight">{{ $item['product']->name }}</p>
                                    <p class="text-xs text-gray-500">Qty {{ $item['quantity'] }}</p>
                                </div>
                            </div>
                            <p class="text-sm font-semibold text-[#4f8a63]">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="border-t pt-3 text-sm space-y-1">
                    <div class="flex items-center justify-between">
                        <span>Subtotal</span>
                        <span class="font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-gray-600">
                        <span>Ongkir</span>
                        <span>Sesuai metode pengiriman</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-xl p-4 text-gray-900 space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-800">Nama Lengkap</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" class="w-full rounded-xl border-gray-200 focus:ring-[#4f8a63]" required>
                        @error('customer_name')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-800">Email</label>
                        <input type="email" name="customer_email" value="{{ old('customer_email', auth()->user()->email ?? '') }}" class="w-full rounded-xl border-gray-200 focus:ring-[#4f8a63]" required>
                        @error('customer_email')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-800">No. Telepon</label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" class="w-full rounded-xl border-gray-200 focus:ring-[#4f8a63]" required>
                        <p class="text-xs text-gray-500">Format: angka, spasi, tanda +, (), atau -.</p>
                        @error('customer_phone')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-sm font-semibold text-gray-800">Alamat Pengiriman</label>
                        <textarea name="shipping_address" rows="3" class="w-full rounded-xl border-gray-200 focus:ring-[#4f8a63]" required>{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-800">Metode Pengiriman</label>
                        <select name="shipping_method" class="w-full rounded-xl border-gray-200 focus:ring-[#4f8a63]" required>
                            @foreach($shippingOptions as $key => $option)
                                <option value="{{ $key }}" @selected(old('shipping_method', array_key_first($shippingOptions)) === $key)>
                                    {{ $option['label'] }} - Rp {{ number_format($option['cost'], 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        @error('shipping_method')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <p class="text-sm text-gray-600">Setelah checkout, Anda akan melihat instruksi pembayaran.</p>
                    <button type="submit" class="px-5 py-3 bg-[#4f8a63] text-white font-semibold rounded-xl shadow hover:bg-[#3f704f] transition">
                        Lanjut ke Pembayaran
                    </button>
                </div>
            </form>
        </div>

        @include('client.partials.bottom-nav', ['cartCount' => $cartCount])
    </div>
</x-client-layout>
